<?php
/**
 * Modelo de Capacidade de Armazenamento (RF06, RF07, CA06)
 */

class Capacidade {
    public static function getAll(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM capacidade ORDER BY capacidade_gb ASC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM capacidade WHERE id_capacidade = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(int $capacidadeGb, float $precoAdicional): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO capacidade (capacidade_gb, preco_adicional) VALUES (?, ?)");
        $stmt->execute([$capacidadeGb, $precoAdicional]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, int $capacidadeGb, float $precoAdicional): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE capacidade SET capacidade_gb = ?, preco_adicional = ? WHERE id_capacidade = ?");
        return $stmt->execute([$capacidadeGb, $precoAdicional, $id]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        // Verifica se existem pedidos vinculados
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM pedido WHERE id_capacidade = ?");
        $stmtCheck->execute([$id]);
        if ((int)$stmtCheck->fetchColumn() > 0) {
            throw new Exception("Não é possível excluir esta capacidade pois existem pedidos associados a ela.");
        }

        $stmt = $pdo->prepare("DELETE FROM capacidade WHERE id_capacidade = ?");
        return $stmt->execute([$id]);
    }

    public static function formatLabel(int $gb): string {
        if ($gb >= 1024) {
            return ($gb / 1024) . ' TB';
        }
        return $gb . ' GB';
    }
}
