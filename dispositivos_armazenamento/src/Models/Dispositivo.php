<?php
/**
 * Modelo de Dispositivo / Produto (Jogos em Mídia Física)
 * (RF04, RF05, RF20, RF21, RF22, RF23, CA04, CA12)
 */

class Dispositivo {
    public static function getAll(bool $somenteAtivos = false, ?int $formatoId = null, ?string $busca = null, ?string $genero = null, ?string $plataforma = null): array {
        $pdo = Database::getConnection();
        
        $sql = "
            SELECT d.*, f.nome as formato_nome, f.icone as formato_icone
            FROM dispositivo d
            INNER JOIN formato f ON d.id_formato = f.id_formato
            WHERE 1=1
        ";
        $params = [];

        if ($somenteAtivos) {
            $sql .= " AND d.ativo = 1";
        }

        if ($formatoId) {
            $sql .= " AND d.id_formato = ?";
            $params[] = $formatoId;
        }

        if ($genero && $genero !== 'todos') {
            $sql .= " AND d.genero = ?";
            $params[] = $genero;
        }

        if ($plataforma && $plataforma !== 'todos') {
            $sql .= " AND d.plataforma = ?";
            $params[] = $plataforma;
        }

        if ($busca && trim($busca) !== '') {
            $term = '%' . trim($busca) . '%';
            $sql .= " AND (d.nome LIKE ? OR d.descricao LIKE ? OR d.genero LIKE ? OR d.plataforma LIKE ? OR d.desenvolvedora LIKE ?)";
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
            $params[] = $term;
        }

        $sql .= " ORDER BY d.id_dispositivo DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT d.*, f.nome as formato_nome, f.descricao as formato_descricao, f.icone as formato_icone
            FROM dispositivo d
            INNER JOIN formato f ON d.id_formato = f.id_formato
            WHERE d.id_dispositivo = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(array $data): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO dispositivo (id_formato, nome, genero, plataforma, desenvolvedora, ano_lancamento, descricao, preco, imagem, ativo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            (int)$data['id_formato'],
            trim($data['nome']),
            trim($data['genero'] ?? 'Aventura'),
            trim($data['plataforma'] ?? 'Super Nintendo (SNES)'),
            !empty($data['desenvolvedora']) ? trim($data['desenvolvedora']) : null,
            !empty($data['ano_lancamento']) ? (int)$data['ano_lancamento'] : null,
            trim($data['descricao'] ?? ''),
            (float)$data['preco'],
            !empty($data['imagem']) ? trim($data['imagem']) : 'assets/images/dispositivo_cartucho.svg',
            isset($data['ativo']) ? (int)$data['ativo'] : 1
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            UPDATE dispositivo 
            SET id_formato = ?, 
                nome = ?, 
                genero = ?, 
                plataforma = ?, 
                desenvolvedora = ?, 
                ano_lancamento = ?, 
                descricao = ?, 
                preco = ?, 
                imagem = ?, 
                ativo = ?
            WHERE id_dispositivo = ?
        ");
        return $stmt->execute([
            (int)$data['id_formato'],
            trim($data['nome']),
            trim($data['genero'] ?? 'Aventura'),
            trim($data['plataforma'] ?? 'Super Nintendo (SNES)'),
            !empty($data['desenvolvedora']) ? trim($data['desenvolvedora']) : null,
            !empty($data['ano_lancamento']) ? (int)$data['ano_lancamento'] : null,
            trim($data['descricao'] ?? ''),
            (float)$data['preco'],
            !empty($data['imagem']) ? trim($data['imagem']) : 'assets/images/dispositivo_cartucho.svg',
            isset($data['ativo']) ? (int)$data['ativo'] : 1,
            $id
        ]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        // Verifica se existem pedidos associados
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM pedido WHERE id_dispositivo = ?");
        $stmtCheck->execute([$id]);
        if ((int)$stmtCheck->fetchColumn() > 0) {
            // Se já tiver pedido, desativa para manter integridade histórica
            return self::toggleAtivo($id, 0);
        }

        $stmt = $pdo->prepare("DELETE FROM dispositivo WHERE id_dispositivo = ?");
        return $stmt->execute([$id]);
    }

    public static function toggleAtivo(int $id, int $novoStatus): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE dispositivo SET ativo = ? WHERE id_dispositivo = ?");
        return $stmt->execute([$novoStatus, $id]);
    }

    public static function getGeneros(): array {
        return [
            'Plataforma',
            'Ação / Aventura',
            'RPG Clássico',
            'Luta',
            'Corrida',
            'Arcade / Coletânea',
            'Estratégia',
            'Esportes',
            'Tiro / Shoot \'em up',
            'Terror / Sobrevivência'
        ];
    }

    public static function getPlataformas(): array {
        return [
            'Super Nintendo (SNES)',
            'Sega Mega Drive',
            'Nintendo 64 (N64)',
            'Game Boy / GBA',
            'Nintendo Entertainment System (NES)',
            'PlayStation 1 (PS1)',
            'Arcade / MAME',
            'Multi-Plataforma Retrô'
        ];
    }
}
