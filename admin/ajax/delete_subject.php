<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

if (!$id) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid subject.'
    ]);

    exit;
}

$stmt = $pdo->prepare("
    SELECT COUNT(*) AS total
    FROM curriculum
    WHERE subject_id = ?
");

$stmt->execute([$id]);

$curriculum = $stmt->fetch(PDO::FETCH_ASSOC);

if ($curriculum['total'] > 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Subject is currently used in a curriculum.'
    ]);

    exit;
}

$stmt = $pdo->prepare("

    DELETE FROM subjects
    WHERE id = ?
");

$stmt->execute([$id]);

echo json_encode([
    'success' => true,
    'message' => 'Subject deleted successfully.'
]);
