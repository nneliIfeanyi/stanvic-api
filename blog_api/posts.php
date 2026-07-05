<?php

/**
 * posts.php
 * -----------------------------------------------------------------------
 * REST-ish endpoint backing the Inkwell blog reader.
 *
 *   GET  /api/posts.php            -> list every post (newest first)
 *   GET  /api/posts.php?id=5       -> a single post
 *   GET  /api/posts.php?category=Design  -> posts filtered by category
 *   POST /api/posts.php            -> create a new post (JSON body)
 * -----------------------------------------------------------------------
 */

require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/config.php';

$pdo = get_db_connection();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handle_get($pdo);
        break;

    case 'POST':
        handle_post($pdo);
        break;

    case 'PUT':
        handle_put($pdo);
        break;

    case 'DELETE':
        handle_delete($pdo);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed.']);
        break;
}

/**
 * Fetch either a single post (?id=) or a list, optionally filtered by
 * ?category=. Rows are mapped to the camelCase shape the front-end
 * JavaScript expects.
 */
function handle_get(PDO $pdo): void
{
    try {
        if (!empty($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => (int) $_GET['id']]);
            $row = $stmt->fetch();

            if (!$row) {
                http_response_code(404);
                echo json_encode(['success' => false, 'error' => 'Post not found.']);
                return;
            }

            echo json_encode(['success' => true, 'data' => map_post($row)]);
            return;
        }

        if (!empty($_GET['category'])) {
            $stmt = $pdo->prepare(
                'SELECT * FROM posts WHERE category = :category ORDER BY date_posted DESC'
            );
            $stmt->execute(['category' => $_GET['category']]);
        } else {
            $stmt = $pdo->query('SELECT * FROM posts ORDER BY date_posted DESC');
        }

        $rows = $stmt->fetchAll();
        $posts = array_map('map_post', $rows);

        echo json_encode(['success' => true, 'data' => $posts, 'count' => count($posts)]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to fetch posts.']);
    }
}

/**
 * Create a new post from a JSON request body.
 * Expected fields: title, author, date (YYYY-MM-DD), category, content.
 * Optional fields: lastEdited, readingTime, thumbnail, excerpt.
 */
function handle_post(PDO $pdo): void
{
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON body.']);
        return;
    }

    $required = ['title', 'author', 'date', 'category', 'content'];
    $missing = array_filter($required, fn($field) => empty($input[$field]));

    if (!empty($missing)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'error'   => 'Missing required field(s): ' . implode(', ', $missing),
        ]);
        return;
    }

    try {
        $stmt = $pdo->prepare('
            INSERT INTO posts
                (title, author, date_posted, last_edited, reading_time, category, thumbnail, excerpt, content)
            VALUES
                (:title, :author, :date_posted, :last_edited, :reading_time, :category, :thumbnail, :excerpt, :content)
        ');

        $stmt->execute([
            'title'        => trim($input['title']),
            'author'       => trim($input['author']),
            'date_posted'  => $input['date'],
            'last_edited'  => $input['lastEdited'] ?? $input['date'],
            'reading_time' => (int) ($input['readingTime'] ?? estimate_reading_time($input['content'])),
            'category'     => trim($input['category']),
            'thumbnail'    => $input['thumbnail'] ?? null,
            'excerpt'      => $input['excerpt'] ?? make_excerpt($input['content']),
            'content'      => $input['content'],
        ]);

        $newId = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $newId]);
        $row = $stmt->fetch();

        http_response_code(201);
        echo json_encode(['success' => true, 'data' => map_post($row)]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to create post.']);
    }
}

function handle_put(PDO $pdo): void
{
    $input = json_decode(file_get_contents('php://input'), true);
    $postId = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON body.']);
        return;
    }

    if (!$postId && !empty($input['id'])) {
        $postId = (int) $input['id'];
    }

    if (!$postId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Post id is required.']);
        return;
    }

    $required = ['title', 'author', 'date', 'category', 'content'];
    $missing = array_filter($required, fn($field) => empty($input[$field]));

    if (!empty($missing)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'error'   => 'Missing required field(s): ' . implode(', ', $missing),
        ]);
        return;
    }

    try {
        $stmt = $pdo->prepare('
            UPDATE posts
            SET title = :title,
                author = :author,
                date_posted = :date_posted,
                last_edited = :last_edited,
                reading_time = :reading_time,
                category = :category,
                thumbnail = :thumbnail,
                excerpt = :excerpt,
                content = :content
            WHERE id = :id
        ');

        $stmt->execute([
            'id'           => $postId,
            'title'        => trim($input['title']),
            'author'       => trim($input['author']),
            'date_posted'  => $input['date'],
            'last_edited'  => $input['lastEdited'] ?? $input['date'],
            'reading_time' => (int) ($input['readingTime'] ?? estimate_reading_time($input['content'])),
            'category'     => trim($input['category']),
            'thumbnail'    => $input['thumbnail'] ?? null,
            'excerpt'      => $input['excerpt'] ?? make_excerpt($input['content']),
            'content'      => $input['content'],
        ]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Post not found.']);
            return;
        }

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $postId]);
        $row = $stmt->fetch();

        echo json_encode(['success' => true, 'data' => map_post($row)]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to update post.']);
    }
}

function handle_delete(PDO $pdo): void
{
    $postId = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if (!$postId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Post id is required.']);
        return;
    }

    try {
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->execute(['id' => $postId]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Post not found.']);
            return;
        }

        echo json_encode(['success' => true, 'message' => 'Post deleted.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to delete post.']);
    }
}

/** Convert a DB row (snake_case) into the shape the front end expects. */
function map_post(array $row): array
{
    return [
        'id'          => (int) $row['id'],
        'title'       => $row['title'],
        'author'      => $row['author'],
        'date'        => $row['date_posted'],
        'lastEdited'  => $row['last_edited'],
        'readingTime' => (int) $row['reading_time'],
        'category'    => $row['category'],
        'thumbnail'   => $row['thumbnail'],
        'excerpt'     => $row['excerpt'],
        'content'     => $row['content'],
    ];
}

/** Rough reading-time estimate (~200 words per minute) when none is supplied. */
function estimate_reading_time(string $html): int
{
    $words = str_word_count(strip_tags($html));
    return max(1, (int) ceil($words / 200));
}

/** Build a short plain-text excerpt from HTML content when none is supplied. */
function make_excerpt(string $html): string
{
    $text = trim(strip_tags($html));
    return mb_strlen($text) > 160 ? mb_substr($text, 0, 157) . '…' : $text;
}
