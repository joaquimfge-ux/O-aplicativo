<?php
/**
 * Helper de Autenticação e Controle de Acesso (RBAC)
 * Atende aos requisitos: RF01, RF02, RF03, RNF01, RNF03, RNF04, RNF08, CA01, CA02, CA03
 */

class Auth {
    public static function check(): bool {
        return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']['id_usuario']);
    }

    public static function user(): ?array {
        return $_SESSION['usuario'] ?? null;
    }

    public static function id(): ?int {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public static function nome(): string {
        return $_SESSION['usuario']['nome'] ?? '';
    }

    public static function email(): string {
        return $_SESSION['usuario']['email'] ?? '';
    }

    public static function tipo(): ?string {
        return $_SESSION['usuario']['tipo_usuario'] ?? null;
    }

    public static function isCliente(): bool {
        return self::tipo() === 'cliente';
    }

    public static function isFuncionario(): bool {
        return self::tipo() === 'funcionario' || self::tipo() === 'administrador';
    }

    public static function isAdmin(): bool {
        return self::tipo() === 'administrador';
    }

    public static function login(array $usuario): void {
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id_usuario'   => (int)$usuario['id_usuario'],
            'nome'         => $usuario['nome'],
            'email'        => $usuario['email'],
            'tipo_usuario' => $usuario['tipo_usuario'],
            'ativo'        => (int)($usuario['ativo'] ?? 1)
        ];
    }

    public static function logout(): void {
        $_SESSION['usuario'] = null;
        unset($_SESSION['usuario']);
        session_destroy();
    }

    /**
     * Exige autenticação básica. Se não logado, redireciona para login.
     * Atende CA03 e RNF04
     */
    public static function requireAuth(): void {
        if (!self::check()) {
            View::flash('danger', 'Você precisa estar conectado para acessar esta página.');
            header('Location: ' . View::url('/login'));
            exit;
        }
    }

    /**
     * Exige papel específico ou lista de papéis
     * Atende RF03, RNF03, CA03
     */
    public static function requireRole(string|array $roles): void {
        self::requireAuth();
        $userRole = self::tipo();

        if (is_string($roles)) {
            $roles = [$roles];
        }

        // Administrador tem acesso irrestrito às áreas de gestão
        if ($userRole === 'administrador') {
            return;
        }

        if (!in_array($userRole, $roles, true)) {
            View::flash('danger', 'Acesso negado: seu perfil não tem permissão para esta funcionalidade.');
            header('Location: ' . View::url('/'));
            exit;
        }
    }
}
