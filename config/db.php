<?php
// config/db.php
$host = 'localhost';
$dbname = 'revivall_api';
$username = 'revivall_stanvicbest';
$password = 'Avalanche@25';

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection error']);
    exit;
}
