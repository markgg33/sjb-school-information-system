<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

if (!$id) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid course.'
    ]);

    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM courses
    WHERE id = ?
");

$stmt->execute([$id]);

echo json_encode([
    'success' => true,
    'message' => 'Course deleted successfully.'
]);
