<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$faculty = $_SESSION['user_id'];

/*
Subjects
*/

$stmt = $pdo->prepare("
SELECT COUNT(DISTINCT subject_id)
FROM faculty_subjects
WHERE faculty_id = ?
");

$stmt->execute([$faculty]);

$subjects = $stmt->fetchColumn();

/*
Students
*/

$stmt = $pdo->prepare("
SELECT COUNT(DISTINCT es.student_id)

FROM enrolled_subjects es

INNER JOIN faculty_subjects fs
ON fs.subject_id = es.subject_id

WHERE fs.faculty_id = ?
");

$stmt->execute([$faculty]);

$students = $stmt->fetchColumn();

/*
Sections
*/

$stmt = $pdo->prepare("
SELECT COUNT(DISTINCT CONCAT(course_id,'-',year_level))
FROM faculty_subjects
WHERE faculty_id = ?
");

$stmt->execute([$faculty]);

$sections = $stmt->fetchColumn();

/*
Pending grades
Temporary
*/

$pending = 0;

echo json_encode([

    "subjects" => $subjects,

    "students" => $students,

    "sections" => $sections,

    "pending" => $pending

]);
