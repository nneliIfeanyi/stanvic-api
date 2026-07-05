<?php
/**
 * config.php
 * -----------------------------------------------------------------------
 * Database connection settings for the Inkwell API.
 * Update the constants below to match your MySQL / MariaDB environment.
 * -----------------------------------------------------------------------
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'inkwell_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Returns a shared PDO connection, or sends a 500 JSON error and exits
 * if the connection can't be established.
 */
function get_db_connection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error'   => 'Database connection failed.',
        ]);
        exit();
    }
}
