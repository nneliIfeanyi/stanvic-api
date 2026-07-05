<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

function getDbConnection(): mysqli
{
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($connection->connect_error) {
        throw new RuntimeException('Database connection failed: ' . $connection->connect_error);
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}
