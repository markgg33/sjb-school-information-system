<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$courseId  = $_GET['course_id'] ?? '';
$yearLevel = $_GET['year_level'] ?? '';
$trimester = $_GET['trimester'] ?? '';

$stmt = $pdo->prepare("
    SELECT subject_id
    FROM curriculum
    WHERE course_id = ?
    AND year_level = ?
    AND trimester = ?
");

$stmt->execute([
    $courseId,
    $yearLevel,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_COLUMN)
);
