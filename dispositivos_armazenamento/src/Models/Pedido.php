<?php
/**
 * Modelo de Pedido (RF12, RF13, RF14, RF15, RF16, RF17, RF18, RF19, CA09, CA10, CA11, CA14)
 */

class Pedido {
    /**
     * Cria um novo pedido com validação de preços no servidor e transação atômica
     */
    public static function create(int $idUsuario, int $idDispositivo, int $idCapacidade, bool $temPersonalizacao = false, array $dadosPers = []): int {
        $pdo = Database::getConnection();

        // 1. Busca dispositivo e capacidade para calcular valor_total no servidor (RNF02, RNF05)
        $dispositivo = Dispositivo::findById($idDispositivo);
        if (!$dispositivo || !$dispositivo['ativo']) {
            throw new Exception("Dispositivo selecionado não está disponível.");
        }

        $capacidade = Capacidade::findById($idCapacidade);
        if (!$capacidade) {
            throw new Exception("Capacidade selecionada é inválida.");
        }

        $valorBase = (float)$dispositivo['preco'];
        $valorAdicional = (float)$capacidade['preco_adicional'];
        
        // Taxa opcional de serviço de personalização (se aplicável, R$ 25,00 por exemplo, ou grátis promocional)
        $taxaPersonalizacao = $temPersonalizacao ? 25.00 : 0.00;
        $valorTotal = $valorBase + $valorAdicional + $taxaPersonalizacao;

        try {
            $pdo->beginTransaction();

            // Insere o pedido
            $stmt = $pdo->prepare("
                INSERT INTO pedido (id_usuario, id_dispositivo, id_capacidade, status, valor_total, data_pedido)
                VALUES (?, ?, ?, 'Pendente', ?, NOW())
            ");
            $stmt->execute([$idUsuario, $idDispositivo, $idCapacidade, $valorTotal]);
            $idPedido = (int)$pdo->lastInsertId();

            // Insere personalização vinculada se houver (relacionamento 0:1)
            if ($temPersonalizacao) {
                $descricao = trim($dadosPers['descricao'] ?? '');
                if (empty($descricao)) {
                    throw new Exception("Por favor, descreva as características desejadas para a personalização.");
                }

                $stmtPers = $pdo->prepare("
                    INSERT INTO personalizacao (id_pedido, descricao, status, tipo_conteudo, texto_gravacao, cor_acabamento)
                    VALUES (?, ?, 'Em Análise', ?, ?, ?)
                ");
                $stmtPers->execute([
                    $idPedido,
                    $descricao,
                    $dadosPers['tipo_conteudo'] ?? 'Memórias Pessoais',
                    $dadosPers['texto_gravacao'] ?? null,
                    $dadosPers['cor_acabamento'] ?? 'Padrão'
                ]);
            }

            $pdo->commit();
            return $idPedido;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                u.nome as usuario_nome,
                u.email as usuario_email,
                d.nome as dispositivo_nome,
                d.preco as dispositivo_preco,
                d.imagem as dispositivo_imagem,
                f.nome as formato_nome,
                c.capacidade_gb,
                c.preco_adicional as capacidade_preco_adicional,
                pers.id_personalizacao,
                pers.descricao as personalizacao_descricao,
                pers.status as personalizacao_status,
                pers.tipo_conteudo as personalizacao_tipo_conteudo,
                pers.texto_gravacao as personalizacao_texto_gravacao,
                pers.cor_acabamento as personalizacao_cor_acabamento
            FROM pedido p
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            INNER JOIN dispositivo d ON p.id_dispositivo = d.id_dispositivo
            INNER JOIN formato f ON d.id_formato = f.id_formato
            INNER JOIN capacidade c ON p.id_capacidade = c.id_capacidade
            LEFT JOIN personalizacao pers ON p.id_pedido = pers.id_pedido
            WHERE p.id_pedido = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getByUsuario(int $idUsuario): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                d.nome as dispositivo_nome,
                f.nome as formato_nome,
                c.capacidade_gb,
                pers.id_personalizacao,
                pers.status as personalizacao_status
            FROM pedido p
            INNER JOIN dispositivo d ON p.id_dispositivo = d.id_dispositivo
            INNER JOIN formato f ON d.id_formato = f.id_formato
            INNER JOIN capacidade c ON p.id_capacidade = c.id_capacidade
            LEFT JOIN personalizacao pers ON p.id_pedido = pers.id_pedido
            WHERE p.id_usuario = ?
            ORDER BY p.id_pedido DESC
        ");
        $stmt->execute([$idUsuario]);
        return $stmt->fetchAll();
    }

    public static function getAll(?string $statusFilter = null, ?string $busca = null): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                p.*,
                u.nome as usuario_nome,
                u.email as usuario_email,
                d.nome as dispositivo_nome,
                f.nome as formato_nome,
                c.capacidade_gb,
                pers.id_personalizacao,
                pers.status as personalizacao_status
            FROM pedido p
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            INNER JOIN dispositivo d ON p.id_dispositivo = d.id_dispositivo
            INNER JOIN formato f ON d.id_formato = f.id_formato
            INNER JOIN capacidade c ON p.id_capacidade = c.id_capacidade
            LEFT JOIN personalizacao pers ON p.id_pedido = pers.id_pedido
            WHERE 1=1
        ";
        $params = [];

        if ($statusFilter && $statusFilter !== 'todos') {
            $sql .= " AND p.status = ?";
            $params[] = $statusFilter;
        }

        if ($busca && trim($busca) !== '') {
            $sql .= " AND (u.nome LIKE ? OR u.email LIKE ? OR d.nome LIKE ? OR p.id_pedido = ?)";
            $params[] = '%' . trim($busca) . '%';
            $params[] = '%' . trim($busca) . '%';
            $params[] = '%' . trim($busca) . '%';
            $params[] = is_numeric($busca) ? (int)$busca : 0;
        }

        $sql .= " ORDER BY p.id_pedido DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $id, string $novoStatus): bool {
        $statusValidos = [
            'Pendente', 'Em Processamento', 'Em Produção', 'Pronto para Envio', 
            'Enviado', 'Concluído', 'Cancelado'
        ];
        if (!in_array($novoStatus, $statusValidos, true)) {
            throw new Exception("Status de pedido inválido.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE pedido SET status = ? WHERE id_pedido = ?");
        return $stmt->execute([$novoStatus, $id]);
    }

    public static function getStats(): array {
        $pdo = Database::getConnection();
        $totalPedidos = (int)$pdo->query("SELECT COUNT(*) FROM pedido")->fetchColumn();
        $pendentes = (int)$pdo->query("SELECT COUNT(*) FROM pedido WHERE status = 'Pendente'")->fetchColumn();
        $emProducao = (int)$pdo->query("SELECT COUNT(*) FROM pedido WHERE status IN ('Em Processamento', 'Em Produção')")->fetchColumn();
        $concluidos = (int)$pdo->query("SELECT COUNT(*) FROM pedido WHERE status = 'Concluído'")->fetchColumn();
        $receita = (float)$pdo->query("SELECT COALESCE(SUM(valor_total), 0) FROM pedido WHERE status != 'Cancelado'")->fetchColumn();

        return [
            'total'       => $totalPedidos,
            'pendentes'   => $pendentes,
            'em_producao' => $emProducao,
            'concluidos'  => $concluidos,
            'receita'     => $receita
        ];
    }
}
