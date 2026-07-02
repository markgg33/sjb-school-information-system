<?php

require_once '../../../includes/db.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

$stmt = $pdo->prepare("
DELETE
FROM grading_components
WHERE id=?
");

$stmt->execute([$id]);

echo json_encode([
    "success" => true
]);
