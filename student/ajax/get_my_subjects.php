<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT id
    FROM students
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$student_id = $stmt->fetchColumn();

$query = $pdo->prepare("
SELECT

    e.id AS enrollment_id,
    e.school_year,
    e.year_level,
    e.trimester,

    s.subject_code,
    s.subject_name,
    s.units

FROM enrollments e

INNER JOIN enrollment_subjects es
    ON es.enrollment_id = e.id

INNER JOIN subjects s
    ON s.id = es.subject_id

WHERE e.student_id = ?

ORDER BY
    e.school_year DESC,
    e.year_level DESC,
    e.trimester DESC,
    s.subject_code
");

$query->execute([$student_id]);

echo json_encode(
    $query->fetchAll(PDO::FETCH_ASSOC)
);
