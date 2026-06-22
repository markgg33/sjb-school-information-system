<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT *
    FROM faculty
    WHERE id = ?
");

$stmt->execute([$id]);

$faculty = $stmt->fetch(PDO::FETCH_ASSOC);

$courseStmt = $pdo->prepare("
    SELECT course_id
    FROM faculty_courses
    WHERE faculty_id = ?
");

$courseStmt->execute([$id]);

$faculty['courses'] =
    $courseStmt->fetchAll(
        PDO::FETCH_COLUMN
    );

echo json_encode($faculty);
