<?php

declare(strict_types=1);

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/database.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $connection = getDbConnection();

    if ($method === 'GET') {
        $type = $_GET['type'] ?? '';
        if ($type !== '' && !isValidType($type)) {
            sendJson(['success' => false, 'message' => 'Invalid transaction type'], 400);
        }

        $query = 'SELECT id, type, amount, description, category, transaction_date, created_at FROM transactions';
        $params = [];
        $types = '';

        if ($type !== '') {
            $query .= ' WHERE type = ?';
            $params[] = $type;
            $types = 's';
        }

        $query .= ' ORDER BY transaction_date DESC, id DESC';

        $statement = $connection->prepare($query);
        if ($type !== '') {
            $statement->bind_param($types, ...$params);
        }
        $statement->execute();
        $result = $statement->get_result();

        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }

        $statement->close();
        $connection->close();
        sendJson(['success' => true, 'data' => $transactions]);
    }

    if ($method === 'POST') {
        $payload = readJsonInput();
        $type = $payload['type'] ?? '';
        $amount = $payload['amount'] ?? 0;
        $description = trim((string) ($payload['description'] ?? ''));
        $category = trim((string) ($payload['category'] ?? 'Others'));
        $transactionDate = trim((string) ($payload['transaction_date'] ?? date('Y-m-d')));

        if (!isValidType($type) || $amount === '' || $description === '' || $transactionDate === '') {
            sendJson(['success' => false, 'message' => 'Please fill in all required fields'], 400);
        }

        $statement = $connection->prepare('INSERT INTO transactions (type, amount, description, category, transaction_date, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $statement->bind_param('sdsss', $type, $amountValue, $description, $category, $transactionDate);
        $amountValue = normalizeAmount($amount);
        $statement->execute();
        $statement->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Transaction Added Successfully']);
    }

    if ($method === 'PUT') {
        $payload = readJsonInput();
        $id = (int) ($payload['id'] ?? 0);
        $type = $payload['type'] ?? '';
        $amount = $payload['amount'] ?? 0;
        $description = trim((string) ($payload['description'] ?? ''));
        $category = trim((string) ($payload['category'] ?? 'Others'));
        $transactionDate = trim((string) ($payload['transaction_date'] ?? date('Y-m-d')));

        if ($id <= 0 || !isValidType($type) || $description === '' || $transactionDate === '') {
            sendJson(['success' => false, 'message' => 'Invalid update payload'], 400);
        }

        $statement = $connection->prepare('UPDATE transactions SET type = ?, amount = ?, description = ?, category = ?, transaction_date = ? WHERE id = ?');
        $statement->bind_param('sdsssi', $type, $amountValue, $description, $category, $transactionDate, $id);
        $amountValue = normalizeAmount($amount);
        $statement->execute();
        $statement->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Transaction Updated Successfully']);
    }

    if ($method === 'DELETE') {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            sendJson(['success' => false, 'message' => 'Invalid transaction id'], 400);
        }

        $statement = $connection->prepare('DELETE FROM transactions WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->close();
        $connection->close();

        sendJson(['success' => true, 'message' => 'Transaction Deleted Successfully']);
    }

    sendJson(['success' => false, 'message' => 'Method not allowed'], 405);
} catch (Throwable $exception) {
    sendJson(['success' => false, 'message' => $exception->getMessage()], 500);
}
