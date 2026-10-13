<?php
/**
 * Configurações Gerais da Aplicação
 */

if (session_status() === PHP_SESSION_NONE) {
    // Configurações de segurança para sessão (RNF01, RNF04, RNF08)
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

define('APP_NAME', 'CustomStorage');
define('APP_TAGLINE', 'Dispositivos de Armazenamento Físico & Personalizados');

// Detecção flexível de BASE_URL e BASE_PATH
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || ($_SERVER['SERVER_PORT'] ?? 80) == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = ($scriptDir === '/' || $scriptDir === '.') ? '' : $scriptDir;

// Se executando pelo PHP built-in server ou diretamente na raiz
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . $host . $basePath);
}

// Autoloader PSR-4 simplificado para a pasta src/ e Models
spl_autoload_register(function ($class) {
    $prefixes = [
        'App\\Models\\' => __DIR__ . '/../src/Models/',
        'App\\Controllers\\' => __DIR__ . '/../src/Controllers/',
        'App\\Helpers\\' => __DIR__ . '/../src/Helpers/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
    
    // Fallback sem namespace para compatibilidade direta
    $legacyDirs = [
        __DIR__ . '/../src/Models/',
        __DIR__ . '/../src/Controllers/',
        __DIR__ . '/../src/Helpers/'
    ];
    foreach ($legacyDirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../src/Helpers/Auth.php';
require_once __DIR__ . '/../src/Helpers/View.php';
