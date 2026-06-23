<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

// enrollment

$stmt = $pdo->prepare("
SELECT
    e.*,
    s.student_number,
    s.first_name,
    s.last_name,
    s.course_id,
    c.course_code,
    c.course_name
FROM enrollments e
INNER JOIN students s
    ON s.id = e.student_id
INNER JOIN courses c
    ON c.id = e.course_id
WHERE e.id = ?
");

$stmt->execute([$id]);

$enrollment = $stmt->fetch(PDO::FETCH_ASSOC);

// enrolled subjects

$stmt = $pdo->prepare("
SELECT subject_id
FROM enrollment_subjects
WHERE enrollment_id = ?
");

$stmt->execute([$id]);

$subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    'enrollment' => $enrollment,
    'subjects' => $subjects
]);
