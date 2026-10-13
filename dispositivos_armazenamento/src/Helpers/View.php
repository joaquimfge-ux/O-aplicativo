<?php
/**
 * Helper de Renderização de Views, Mensagens Flash e Utilitários de Interface
 */

class View {
    public static function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);

        $viewFile = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new Exception("View não encontrada: {$viewFile}");
        }

        // Se layout for vazio ou false, renderiza apenas a view
        if (!$layout) {
            require $viewFile;
            return;
        }

        // Bufferiza o conteúdo da view filha
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Renderiza com header e footer padrão
        require __DIR__ . '/../../views/partials/header.php';
        echo $content;
        require __DIR__ . '/../../views/partials/footer.php';
    }

    public static function url(string $path = ''): string {
        $path = ltrim($path, '/');
        $base = rtrim(BASE_PATH, '/');
        
        // Se a rota usar querystrings (ex: index.php?route=...) ou rotas limpas
        if (defined('USE_QUERY_ROUTES') && USE_QUERY_ROUTES) {
            return $base . '/index.php?r=' . $path;
        }
        
        // Formato padrão amigável com suporte fallback
        return $base . ($path !== '' ? '/' . $path : '/');
    }

    public static function flash(string $type, string $message): void {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function getFlashes(): array {
        $flashes = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $flashes;
    }

    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function csrfField(): string {
        $token = self::csrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verifyCsrf(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
                return false;
            }
        }
        return true;
    }

    public static function money(float|int|string|null $val): string {
        $num = (float)($val ?? 0);
        return 'R$ ' . number_format($num, 2, ',', '.');
    }

    public static function date(?string $dateStr): string {
        if (!$dateStr) return '-';
        $timestamp = strtotime($dateStr);
        return date('d/m/Y H:i', $timestamp);
    }

    public static function statusBadge(string $status): string {
        $map = [
            'Pendente'         => 'bg-warning text-dark',
            'Em Processamento' => 'bg-info text-dark',
            'Em Produção'      => 'bg-primary text-white',
            'Pronto para Envio'=> 'bg-info text-white',
            'Enviado'          => 'bg-secondary text-white',
            'Concluído'        => 'bg-success text-white',
            'Cancelado'        => 'bg-danger text-white',
            // Personalizações
            'Em Análise'       => 'bg-warning text-dark',
            'Aprovada'         => 'bg-success text-white',
            'Finalizada'       => 'bg-teal text-white',
            'Rejeitada'        => 'bg-danger text-white'
        ];

        $class = $map[$status] ?? 'bg-secondary text-white';
        return '<span class="badge ' . $class . ' px-2 py-1 fs-7 shadow-xs">' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</span>';
    }
}
