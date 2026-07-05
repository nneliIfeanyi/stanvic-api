<?php
/**
 * auth.php
 * -----------------------------------------------------------------------
 * Verifies the password shown by the modal on add.html before a user is
 * allowed to create or edit a post.
 *
 *   POST /api/auth.php   body: { "password": "..." }
 *   -> { "success": true }                      on correct password
 *   -> { "success": false, "error": "..." }      on incorrect password
 *
 * Note: this only guards the UI on add.html. If you need the API itself
 * protected (so posts.php rejects writes from anyone who hasn't entered
 * the password), extend this endpoint to issue a session/token and have
 * posts.php require it on POST/PUT/DELETE.
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input) || empty($input['password'])) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => 'Password is required.']);
    exit();
}

$pdo = get_db_connection();

try {
    $stmt = $pdo->prepare('SELECT password_hash FROM admin_auth WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => 'admin']);
    $row = $stmt->fetch();

    if (!$row || !password_verify($input['password'], $row['password_hash'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Incorrect password.']);
        exit();
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not verify password.']);
}
