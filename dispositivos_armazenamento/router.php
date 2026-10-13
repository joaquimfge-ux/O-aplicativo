<?php
/**
 * Roteador para servidor embutido do PHP:
 * php -S localhost:8000 router.php
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;

// Se for um arquivo estático existente (CSS, JS, imagens), retorna false para o PHP embutido servir diretamente
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false;
}

// Caso contrário, encaminha para o index.php
require_once __DIR__ . '/index.php';
