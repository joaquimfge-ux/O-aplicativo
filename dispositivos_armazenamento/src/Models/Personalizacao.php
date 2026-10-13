<?php
/**
 * Modelo de Personalização de Dispositivo (RF08, RF09, RF10, RF11, CA07, CA08, CA15)
 */

class Personalizacao {
    public static function getByPedidoId(int $idPedido): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM personalizacao WHERE id_pedido = ? LIMIT 1");
        $stmt->execute([$idPedido]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function getAllWithPedido(?string $statusFilter = null): array {
        $pdo = Database::getConnection();
        $sql = "
            SELECT 
                pers.*,
                p.id_usuario,
                p.data_pedido,
                p.status as pedido_status,
                p.valor_total,
                u.nome as usuario_nome,
                u.email as usuario_email,
                d.nome as dispositivo_nome,
                f.nome as formato_nome,
                c.capacidade_gb
            FROM personalizacao pers
            INNER JOIN pedido p ON pers.id_pedido = p.id_pedido
            INNER JOIN usuario u ON p.id_usuario = u.id_usuario
            INNER JOIN dispositivo d ON p.id_dispositivo = d.id_dispositivo
            INNER JOIN formato f ON d.id_formato = f.id_formato
            INNER JOIN capacidade c ON p.id_capacidade = c.id_capacidade
            WHERE 1=1
        ";
        $params = [];

        if ($statusFilter && $statusFilter !== 'todos') {
            $sql .= " AND pers.status = ?";
            $params[] = $statusFilter;
        }

        $sql .= " ORDER BY pers.id_personalizacao DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function updateStatus(int $idPersonalizacao, string $novoStatus): bool {
        $statusValidos = ['Em Análise', 'Aprovada', 'Em Produção', 'Finalizada', 'Rejeitada'];
        if (!in_array($novoStatus, $statusValidos, true)) {
            throw new Exception("Status de personalização inválido.");
        }

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE personalizacao SET status = ? WHERE id_personalizacao = ?");
        return $stmt->execute([$novoStatus, $idPersonalizacao]);
    }

    public static function countPendentes(): int {
        $pdo = Database::getConnection();
        return (int)$pdo->query("SELECT COUNT(*) FROM personalizacao WHERE status = 'Em Análise'")->fetchColumn();
    }
}
