<?php
/**
 * Modelo de Usuário (RF01, RF02, RF03, RF24, RF25, RF26, RF27)
 */

class Usuario {
    public static function create(string $nome, string $email, string $senha, string $tipo = 'cliente', int $ativo = 1, ?string $telefone = null): int {
        $pdo = Database::getConnection();
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO usuario (nome, email, telefone, senha, tipo_usuario, ativo)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            trim($nome), 
            trim(strtolower($email)), 
            !empty($telefone) ? trim($telefone) : null,
            $hash, 
            $tipo, 
            $ativo
        ]);
        return (int)$pdo->lastInsertId();
    }

    public static function findByEmail(string $email): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE LOWER(email) = LOWER(?) LIMIT 1");
        $stmt->execute([trim($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function findById(int $id): ?array {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT id_usuario, nome, email, telefone, tipo_usuario, ativo, criado_em FROM usuario WHERE id_usuario = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public static function getAll(?string $tipo = null, ?string $busca = null): array {
        $pdo = Database::getConnection();
        $sql = "SELECT id_usuario, nome, email, telefone, tipo_usuario, ativo, criado_em FROM usuario WHERE 1=1";
        $params = [];

        if ($tipo && $tipo !== 'todos') {
            $sql .= " AND tipo_usuario = ?";
            $params[] = $tipo;
        }

        if ($busca && trim($busca) !== '') {
            $sql .= " AND (nome LIKE ? OR email LIKE ? OR telefone LIKE ?)";
            $params[] = '%' . trim($busca) . '%';
            $params[] = '%' . trim($busca) . '%';
            $params[] = '%' . trim($busca) . '%';
        }

        $sql .= " ORDER BY id_usuario DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function update(int $id, string $nome, string $email, ?string $novaSenha = null, ?string $tipo = null, ?int $ativo = null, ?string $telefone = null): bool {
        $pdo = Database::getConnection();
        
        $fields = ["nome = ?", "email = ?"];
        $params = [trim($nome), trim(strtolower($email))];

        if ($telefone !== null) {
            $fields[] = "telefone = ?";
            $params[] = trim($telefone) !== '' ? trim($telefone) : null;
        }

        if ($novaSenha !== null && trim($novaSenha) !== '') {
            $fields[] = "senha = ?";
            $params[] = password_hash($novaSenha, PASSWORD_DEFAULT);
        }

        if ($tipo !== null) {
            $fields[] = "tipo_usuario = ?";
            $params[] = $tipo;
        }

        if ($ativo !== null) {
            $fields[] = "ativo = ?";
            $params[] = $ativo;
        }

        $params[] = $id;
        $sql = "UPDATE usuario SET " . implode(", ", $fields) . " WHERE id_usuario = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public static function toggleAtivo(int $id, int $novoStatus): bool {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE usuario SET ativo = ? WHERE id_usuario = ?");
        return $stmt->execute([$novoStatus, $id]);
    }

    public static function countByTipo(): array {
        $pdo = Database::getConnection();
        $stmt = $pdo->query("SELECT tipo_usuario, COUNT(*) as total FROM usuario GROUP BY tipo_usuario");
        $counts = ['cliente' => 0, 'funcionario' => 0, 'administrador' => 0];
        while ($row = $stmt->fetch()) {
            $counts[$row['tipo_usuario']] = (int)$row['total'];
        }
        return $counts;
    }
}
