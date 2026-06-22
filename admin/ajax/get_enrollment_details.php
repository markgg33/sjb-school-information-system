<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

//====================================
// ENROLLMENT
//====================================

$stmt = $pdo->prepare("
    SELECT

        e.*,

        s.student_number,
        s.first_name,
        s.last_name,

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

//====================================
// SUBJECTS
//====================================

$stmt = $pdo->prepare("
    SELECT

        s.subject_code,
        s.subject_name,
        s.units

    FROM enrollment_subjects es

    INNER JOIN subjects s
        ON s.id = es.subject_id

    WHERE es.enrollment_id = ?

    ORDER BY s.subject_code
");

$stmt->execute([$id]);

$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'enrollment' => $enrollment,
    'subjects' => $subjects
]);
