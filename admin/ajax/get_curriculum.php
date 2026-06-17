<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$course_id = $_GET['course_id'] ?? 0;
$year_level = $_GET['year_level'] ?? 0;
$trimester = $_GET['trimester'] ?? 0;

$stmt = $pdo->prepare("
    SELECT
    curriculum.id,
    curriculum.subject_id,
    subjects.subject_code,
    subjects.subject_name,
    subjects.units

    FROM curriculum

    INNER JOIN subjects
        ON subjects.id = curriculum.subject_id

    WHERE curriculum.course_id = ?
    AND curriculum.year_level = ?
    AND curriculum.trimester = ?

    ORDER BY subjects.subject_code
");

$stmt->execute([
    $course_id,
    $year_level,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
