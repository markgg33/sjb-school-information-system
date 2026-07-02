<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
SELECT

    fs.id AS faculty_subject_id,

    fs.subject_id,
    fs.course_id,
    fs.year_level,
    fs.school_year,
    fs.trimester,

    s.subject_code,
    s.subject_name,

    c.course_code,
    c.course_name,

    COUNT(DISTINCT e.student_id) AS students

FROM faculty_subjects fs

INNER JOIN faculty f
    ON f.id = fs.faculty_id

INNER JOIN subjects s
    ON s.id = fs.subject_id

INNER JOIN courses c
    ON c.id = fs.course_id

LEFT JOIN enrollment_subjects es
    ON es.subject_id = fs.subject_id

LEFT JOIN enrollments e
    ON e.id = es.enrollment_id
    AND e.course_id = fs.course_id
    AND e.year_level = fs.year_level
    AND e.school_year = fs.school_year
    AND e.trimester = fs.trimester

WHERE

    f.user_id = ?

GROUP BY

    fs.id,

    fs.subject_id,
    fs.course_id,
    fs.year_level,
    fs.school_year,
    fs.trimester,

    s.subject_code,
    s.subject_name,

    c.course_code,
    c.course_name

ORDER BY

    c.course_code,
    fs.year_level,
    s.subject_code
");

$stmt->execute([
    $_SESSION['user_id']
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
