<?php
require_once '../config/db.php';

// CORS
//header("Access-Control-Allow-Origin: *");
$allowedOrigins = [
    'http://localhost',
    'http://127.0.0.1',
    'https://budgetdroid.com.ng',
    'https://www.budgetdroid.com.ng',
];

if (in_array($_SERVER['HTTP_ORIGIN'] ?? '', $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
// header("Access-Control-Allow-Methods: GET, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept, Origin");
header("Access-Control-Max-Age: 3600");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $result = $conn->query("SELECT id, name, price, category, image1, image2, image3, description 
                           FROM products 
                           WHERE stock > 0 
                           ORDER BY id DESC");

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode($products);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch products']);
}
