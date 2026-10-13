<?php
/**
 * Configurações e Conexão com o Banco de Dados MySQL (PDO)
 * Atende aos requisitos RNF01, RNF02, RNF05
 */

if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: '3306');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'dispositivos_db');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
// Testado no ambiente local: alunoifro (com fallback para senha vazia se necessário)
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'alunoifro');

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $host = DB_HOST;
            $port = DB_PORT;
            $dbname = DB_NAME;
            $user = DB_USER;
            $pass = DB_PASS;

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Tenta fallback com senha vazia caso o ambiente MySQL não tenha senha configurada
                if ($pass !== '') {
                    try {
                        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                        self::$instance = new PDO($dsn, $user, '', $options);
                        return self::$instance;
                    } catch (PDOException $e2) {
                        // continua para lançar erro original
                    }
                }
                throw new PDOException("Falha na conexão com o banco de dados: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }
}
