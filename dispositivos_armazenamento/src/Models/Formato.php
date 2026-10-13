<?php
/**
 * Modelo de Formato de Dispositivo (DER: FORMATO 1:N DISPOSITIVO, CA05)
 */

class Formato {
    public static function getAll(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT * FROM formato ORDER BY nome ASC");
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM formato WHERE id_formato = ? LIMIT 1");
        $stmt->execute([$id]);
        $formato = $stmt->fetch();
        return $formato ?: null;
    }

    public static function create(string $nome, string $descricao = '', string $icone = 'bi-hdd'): int {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO formato (nome, descricao, icone) VALUES (?, ?, ?)");
        $stmt->execute([trim($nome), trim($descricao), trim($icone)]);
        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, string $nome, string $descricao = '', string $icone = 'bi-hdd'): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE formato SET nome = ?, descricao = ?, icone = ? WHERE id_formato = ?");
        return $stmt->execute([trim($nome), trim($descricao), trim($icone), $id]);
    }

    public static function delete(int $id): bool {
        $pdo = Database::getConnection();
        // Verifica se existem dispositivos vinculados
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM dispositivo WHERE id_formato = ?");
        $stmtCheck->execute([$id]);
        if ((int)$stmtCheck->fetchColumn() > 0) {
            throw new Exception("Não é possível excluir este formato pois existem dispositivos associados a ele.");
        }

        $stmt = $pdo->prepare("DELETE FROM formato WHERE id_formato = ?");
        return $stmt->execute([$id]);
    }
}
