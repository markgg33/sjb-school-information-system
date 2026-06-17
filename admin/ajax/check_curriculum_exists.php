<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$course_id = $_GET['course_id'] ?? 0;
$year_level = $_GET['year_level'] ?? 0;
$trimester = $_GET['trimester'] ?? 0;

$stmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM curriculum
    WHERE course_id = ?
    AND year_level = ?
    AND trimester = ?
");

$stmt->execute([
    $course_id,
    $year_level,
    $trimester
]);

$count = $stmt->fetchColumn();

echo json_encode([
    'exists' => $count > 0,
    'count' => (int)$count
]);
