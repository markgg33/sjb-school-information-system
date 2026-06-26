<?php

session_start();

require '../../includes/db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT id
    FROM students
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

$student_id = $stmt->fetchColumn();

if (!$student_id) {

    echo json_encode([
        'success' => false,
        'message' => 'Student not found.'
    ]);

    exit;
}

$enrollmentStmt = $pdo->prepare("
    SELECT *
    FROM enrollments
    WHERE student_id = ?
    ORDER BY id DESC
    LIMIT 1
");

$enrollmentStmt->execute([
    $student_id
]);

$enrollment = $enrollmentStmt->fetch(PDO::FETCH_ASSOC);

if (!$enrollment) {

    echo json_encode([
        'success' => true,
        'enrollment' => null,
        'subjects' => []
    ]);

    exit;
}

$subjectStmt = $pdo->prepare("
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

$subjectStmt->execute([
    $enrollment['id']
]);

echo json_encode([
    'success' => true,
    'enrollment' => $enrollment,
    'subjects' => $subjectStmt->fetchAll(PDO::FETCH_ASSOC)
]);
