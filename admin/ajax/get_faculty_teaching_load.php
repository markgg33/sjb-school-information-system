<?php

require '../../includes/db.php';

header('Content-Type: application/json');

$faculty_id = (int)$_GET['faculty_id'];

$school_year = $_GET['school_year'];

$trimester = (int)$_GET['trimester'];

$stmt = $pdo->prepare("

SELECT

    c.course_code,

    fs.year_level,

    s.subject_code,

    s.subject_name,

    s.units

FROM faculty_subjects fs

INNER JOIN courses c
    ON c.id = fs.course_id

INNER JOIN subjects s
    ON s.id = fs.subject_id

WHERE

    fs.faculty_id = ?

AND fs.school_year = ?

AND fs.trimester = ?

ORDER BY

    c.course_code,

    fs.year_level,

    s.subject_code

");

$stmt->execute([
    $faculty_id,
    $school_year,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
