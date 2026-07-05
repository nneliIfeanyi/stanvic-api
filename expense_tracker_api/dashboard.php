<?php

declare(strict_types=1);

require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/database.php';

try {
    $connection = getDbConnection();

    $incomeType = 'income';
    $incomeStmt = $connection->prepare('SELECT COALESCE(SUM(amount), 0) AS total FROM transactions WHERE type = ?');
    $incomeStmt->bind_param('s', $incomeType);
    $incomeStmt->execute();
    $incomeResult = $incomeStmt->get_result();
    $incomeRow = $incomeResult->fetch_assoc();
    $incomeTotal = (float) ($incomeRow['total'] ?? 0);
    $incomeStmt->close();

    $expenseType = 'expense';
    $expenseStmt = $connection->prepare('SELECT COALESCE(SUM(amount), 0) AS total FROM transactions WHERE type = ?');
    $expenseStmt->bind_param('s', $expenseType);
    $expenseStmt->execute();
    $expenseResult = $expenseStmt->get_result();
    $expenseRow = $expenseResult->fetch_assoc();
    $expenseTotal = (float) ($expenseRow['total'] ?? 0);
    $expenseStmt->close();

    $recentStmt = $connection->prepare('SELECT id, type, amount, description, category, transaction_date FROM transactions ORDER BY transaction_date DESC, id DESC LIMIT 10');
    $recentStmt->execute();
    $recentResult = $recentStmt->get_result();
    $recentTransactions = [];

    while ($row = $recentResult->fetch_assoc()) {
        $recentTransactions[] = $row;
    }

    $recentStmt->close();
    $connection->close();

    sendJson([
        'success' => true,
        'message' => 'Dashboard data loaded',
        'data' => [
            'income_total' => $incomeTotal,
            'expense_total' => $expenseTotal,
            'savings_target' => $incomeTotal * 0.3,
            'recent_transactions' => $recentTransactions,
        ],
    ]);
} catch (Throwable $exception) {
    sendJson([
        'success' => true,
        'message' => 'Using sample dashboard data',
        'data' => [
            'income_total' => 0,
            'expense_total' => 0,
            'savings_target' => 0,
            'recent_transactions' => [],
        ],
    ]);
}
