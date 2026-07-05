<?php

declare(strict_types=1);

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/database.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $connection = getDbConnection();

    if ($method === 'GET') {
        $statement = $connection->prepare('SELECT id, name, created_at FROM categories ORDER BY name ASC');
        $statement->execute();
        $result = $statement->get_result();

        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }

        $statement->close();
        $connection->close();

        sendJson(['success' => true, 'data' => $categories]);
    }

    if ($method === 'POST') {
        $payload = readJsonInput();
        $name = trim((string) ($payload['name'] ?? ''));

        if ($name === '') {
            sendJson(['success' => false, 'message' => 'Category name is required'], 400);
        }

        $statement = $connection->prepare('SELECT id FROM categories WHERE name = ? LIMIT 1');
        $statement->bind_param('s', $name);
        $statement->execute();
        $statement->store_result();

        if ($statement->num_rows > 0) {
            $statement->close();
            $connection->close();
            sendJson(['success' => false, 'message' => 'Category already exists'], 409);
        }

        $statement->close();

        $insert = $connection->prepare('INSERT INTO categories (name) VALUES (?)');
        $insert->bind_param('s', $name);
        $insert->execute();

        if ($insert->affected_rows <= 0) {
            $insert->close();
            $connection->close();
            sendJson(['success' => false, 'message' => 'Unable to create category'], 500);
        }

        $categoryId = $insert->insert_id;
        $insert->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Category created successfully', 'data' => ['id' => $categoryId, 'name' => $name]]);
    }

    if ($method === 'PUT') {
        $payload = readJsonInput();
        $id = (int) ($payload['id'] ?? 0);
        $name = trim((string) ($payload['name'] ?? ''));

        if ($id <= 0 || $name === '') {
            sendJson(['success' => false, 'message' => 'Invalid category data'], 400);
        }

        $statement = $connection->prepare('SELECT id FROM categories WHERE name = ? AND id <> ? LIMIT 1');
        $statement->bind_param('si', $name, $id);
        $statement->execute();
        $statement->store_result();

        if ($statement->num_rows > 0) {
            $statement->close();
            $connection->close();
            sendJson(['success' => false, 'message' => 'Another category with this name already exists'], 409);
        }

        $statement->close();

        $update = $connection->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $update->bind_param('si', $name, $id);
        $update->execute();

        if ($update->affected_rows < 0) {
            $update->close();
            $connection->close();
            sendJson(['success' => false, 'message' => 'Unable to update category'], 500);
        }

        $update->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Category updated successfully']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            sendJson(['success' => false, 'message' => 'Invalid category id'], 400);
        }

        $delete = $connection->prepare('DELETE FROM categories WHERE id = ?');
        $delete->bind_param('i', $id);
        $delete->execute();

        if ($delete->affected_rows === 0) {
            $delete->close();
            $connection->close();
            sendJson(['success' => false, 'message' => 'Category not found'], 404);
        }

        $delete->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Category deleted successfully']);
    }

    sendJson(['success' => false, 'message' => 'Method not allowed'], 405);
} catch (Throwable $exception) {
    sendJson(['success' => false, 'message' => $exception->getMessage()], 500);
}
