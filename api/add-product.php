<?php
require_once '../config/db.php';

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uploadDir = '../uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$response = ['success' => false, 'error' => ''];

try {
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category = $_POST['category'] ?? '';
    $description = trim($_POST['description'] ?? '');

    // Basic validation
    if (empty($name) || $price <= 0 || empty($category) || empty($description)) {
        throw new Exception("Missing required fields");
    }

    if (!isset($_FILES['images']) || count($_FILES['images']['name']) === 0) {
        throw new Exception("At least one image is required");
    }

    $files = $_FILES['images'];
    $imagePaths = [];

    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

        $tmpName = $files['tmp_name'][$i];
        $fileType = mime_content_type($tmpName);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($fileType, $allowed)) {
            throw new Exception("Invalid image type");
        }

        $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
        $newName = uniqid('prod_') . '_' . time() . '.' . strtolower($ext);
        $dest = $uploadDir . $newName;

        if (move_uploaded_file($tmpName, $dest)) {
            $imagePaths[] = 'uploads/products/' . $newName;
        }
    }

    if (empty($imagePaths)) {
        throw new Exception("Failed to upload images");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products (name, price, category, description, image1, image2, image3) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");

    $image1 = $imagePaths[0] ?? null;
    $image2 = $imagePaths[1] ?? null;
    $image3 = $imagePaths[2] ?? null;

    $stmt->bind_param("sdsssss", $name, $price, $category, $description, $image1, $image2, $image3);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        throw new Exception("Database error");
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
