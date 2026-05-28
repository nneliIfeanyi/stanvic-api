<?php
require_once '../config/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

try {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);

    if (empty($name) || $price <= 0 || $id <= 0) {
        throw new Exception("Invalid data");
    }

    $newImagePaths = [];

    // Handle new image uploads (optional)
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $files = $_FILES['images'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;

            $fileType = mime_content_type($files['tmp_name'][$i]);
            if (!in_array($fileType, ['image/jpeg', 'image/png', 'image/webp'])) continue;

            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            $newName = uniqid('prod_') . '_' . time() . '.' . $ext;
            $destPath = $uploadDir . $newName;

            if (move_uploaded_file($files['tmp_name'][$i], $destPath)) {
                $newImagePaths[] = 'uploads/products/' . $newName;
            }
        }
    }

    // Build update query
    if (!empty($newImagePaths)) {
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, description=?, image1=?, image2=?, image3=? WHERE id=?");
        $img1 = $newImagePaths[0] ?? null;
        $img2 = $newImagePaths[1] ?? null;
        $img3 = $newImagePaths[2] ?? null;
        $stmt->bind_param("sdsssssi", $name, $price, $category, $description, $img1, $img2, $img3, $id);
    } else {
        // No new images - update only text fields
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, category=?, description=? WHERE id=?");
        $stmt->bind_param("sdssi", $name, $price, $category, $description, $id);
    }

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception("Database update failed");
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
