<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

if (!$id) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid curriculum record.'
    ]);

    exit;
}

// GET SUBJECT ID FIRST
$stmt = $pdo->prepare("
    SELECT subject_id
    FROM curriculum
    WHERE id = ?
");

$stmt->execute([$id]);

$subjectId = $stmt->fetchColumn();

if (!$subjectId) {

    echo json_encode([
        'success' => false,
        'message' => 'Subject was not found in curriculum.'
    ]);

    exit;
}

$stmt = $pdo->prepare("
    DELETE FROM curriculum
    WHERE id = ?
");

$stmt->execute([$id]);

echo json_encode([
    'success' => true,
    'subject_id' => $subjectId,
    'message' => 'Subject removed from curriculum successfully.'
]);
