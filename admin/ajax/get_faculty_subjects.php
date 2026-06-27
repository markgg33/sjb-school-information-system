<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$faculty_id = (int)($_GET['faculty_id'] ?? 0);

$school_year = trim($_GET['school_year'] ?? '');

$trimester = (int)($_GET['trimester'] ?? 0);

$course_id = (int)($_GET['course_id'] ?? 0);

$year_level = (int)($_GET['year_level'] ?? 0);

$stmt = $pdo->prepare("
SELECT

    subject_id

FROM faculty_subjects

WHERE

    faculty_id = ?

AND school_year = ?

AND trimester = ?

AND course_id = ?

AND year_level = ?
");

$stmt->execute([
    $faculty_id,
    $school_year,
    $trimester,
    $course_id,
    $year_level
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_COLUMN)
);
