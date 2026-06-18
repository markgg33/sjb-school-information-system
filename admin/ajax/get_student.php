<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT *
    FROM students
    WHERE id = ?
");

$stmt->execute([$id]);

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);
