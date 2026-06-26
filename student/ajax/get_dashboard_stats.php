<?php

session_start();

require '../../includes/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
SELECT *
FROM students
WHERE user_id = ?
");

$stmt->execute([$user_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

$enrollmentStmt = $pdo->prepare("
SELECT *
FROM enrollments
WHERE student_id = ?
ORDER BY id DESC
LIMIT 1
");

$enrollmentStmt->execute([
    $student['id']
]);

$enrollment = $enrollmentStmt->fetch(PDO::FETCH_ASSOC);

$currentSubjects = 0;
$currentUnits = 0;

if ($enrollment) {

    $subjectStmt = $pdo->prepare("
    SELECT
        COUNT(*) total_subjects,
        COALESCE(SUM(s.units),0) total_units
    FROM enrollment_subjects es
    INNER JOIN subjects s
        ON s.id = es.subject_id
    WHERE es.enrollment_id = ?
    ");

    $subjectStmt->execute([
        $enrollment['id']
    ]);

    $subjectData =
        $subjectStmt->fetch(PDO::FETCH_ASSOC);

    $currentSubjects =
        $subjectData['total_subjects'];

    $currentUnits =
        $subjectData['total_units'];
}

$completedStmt = $pdo->prepare("
SELECT COUNT(DISTINCT subject_id)
FROM enrollment_subjects es
INNER JOIN enrollments e
    ON e.id = es.enrollment_id
WHERE e.student_id = ?
");

$completedStmt->execute([
    $student['id']
]);

echo json_encode([
    'current_subjects' => $currentSubjects,
    'current_units' => $currentUnits,
    'completed_subjects' => $completedStmt->fetchColumn(),
    'status' => ucfirst($student['status']),
    'enrollment' => $enrollment
]);
