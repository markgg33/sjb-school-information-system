<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$students = $pdo->query("
    SELECT COUNT(*)
FROM students
WHERE status='active'
")->fetchColumn();

$faculty = $pdo->query("
    SELECT COUNT(*)
    FROM faculty
    WHERE status='active'
")->fetchColumn();

$courses = $pdo->query("
    SELECT COUNT(*)
    FROM courses
")->fetchColumn();

$subjects = $pdo->query("
    SELECT COUNT(*)
    FROM subjects
")->fetchColumn();

echo json_encode([
    'students' => $students,
    'faculty'  => $faculty,
    'courses'  => $courses,
    'subjects' => $subjects
]);
