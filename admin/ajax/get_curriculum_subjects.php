<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$student_id = intval($_GET['student_id'] ?? 0);
$year_level = intval($_GET['year_level'] ?? 0);
$trimester  = intval($_GET['trimester'] ?? 0);

if (
    !$student_id ||
    !$year_level ||
    !$trimester
) {

    echo json_encode([]);
    exit;
}

//=====================================
// GET STUDENT COURSE
//=====================================

$stmt = $pdo->prepare("
    SELECT course_id
    FROM students
    WHERE id = ?
");

$stmt->execute([$student_id]);

$course_id = $stmt->fetchColumn();

if (!$course_id) {

    echo json_encode([]);
    exit;
}

//=====================================
// LOAD CURRICULUM SUBJECTS
//=====================================

$stmt = $pdo->prepare("
    SELECT
        s.id,
        s.subject_code,
        s.subject_name,
        s.units
    FROM curriculum c

    INNER JOIN subjects s
        ON s.id = c.subject_id

    WHERE
        c.course_id = ?
        AND c.year_level = ?
        AND c.trimester = ?
        AND c.is_active = 1

    ORDER BY
        s.subject_code
");

$stmt->execute([
    $course_id,
    $year_level,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
