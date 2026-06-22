<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT
    s.*,
    c.course_code,
    c.course_name
FROM students s
LEFT JOIN courses c
    ON c.id = s.course_id
WHERE s.id = ?
");

$stmt->execute([$id]);

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);
