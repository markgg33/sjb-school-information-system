<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sessions.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$currentUserId = (int) $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT id
    FROM faculty
    WHERE user_id = ?
    LIMIT 1
");

$stmt->execute([$currentUserId]);

$currentFacultyId = (int)$stmt->fetchColumn();

if (!$currentFacultyId) {
    exit('Faculty account not found.');
}
