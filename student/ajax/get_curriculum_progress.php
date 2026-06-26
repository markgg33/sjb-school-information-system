<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

//====================================
// GET STUDENT
//====================================

$stmt = $pdo->prepare("
SELECT
    id,
    course_id
FROM students
WHERE user_id = ?
LIMIT 1
");

$stmt->execute([
    $user_id
]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {

    echo json_encode([
        'completed' => 0,
        'current' => 0,
        'remaining' => 0,
        'progress' => 0,
        'curriculum' => []
    ]);

    exit;
}

$student_id = $student['id'];
$course_id  = $student['course_id'];


//====================================
// GET LATEST ENROLLMENT
//====================================

$stmt = $pdo->prepare("
SELECT id
FROM enrollments
WHERE student_id = ?
ORDER BY id DESC
LIMIT 1
");

$stmt->execute([
    $student_id
]);

$current_enrollment =
    $stmt->fetchColumn();


//====================================
// GET CURRICULUM
//====================================

$stmt = $pdo->prepare("
SELECT

    c.subject_id,

    c.year_level,

    c.trimester,

    s.subject_code,
    s.subject_name,
    s.units

FROM curriculum c

INNER JOIN subjects s
    ON s.id = c.subject_id

WHERE
    c.course_id = ?
AND
    c.is_active = 1

ORDER BY

    c.year_level,
    c.trimester,
    s.subject_code
");

$stmt->execute([
    $course_id
]);

$curriculum =
    $stmt->fetchAll(PDO::FETCH_ASSOC);


//====================================
// GET ALL COMPLETED SUBJECTS
//====================================

$stmt = $pdo->prepare("
SELECT DISTINCT
    es.subject_id
FROM enrollment_subjects es

INNER JOIN enrollments e
    ON e.id = es.enrollment_id

INNER JOIN grades g
    ON g.enrollment_subject_id = es.id

WHERE
    e.student_id = ?
AND
    g.remarks = 'Passed'
");

$stmt->execute([
    $student_id
]);

$completed =
    $stmt->fetchAll(PDO::FETCH_COLUMN);


//====================================
// GET CURRENT SUBJECTS
//====================================

$current = [];

if ($current_enrollment) {

    $stmt = $pdo->prepare("
    SELECT
        subject_id
    FROM enrollment_subjects
    WHERE enrollment_id = ?
    ");

    $stmt->execute([
        $current_enrollment
    ]);

    $current =
        $stmt->fetchAll(PDO::FETCH_COLUMN);
}


//====================================
// BUILD ROADMAP
//====================================

$roadmap = [];

$completedCount = 0;
$currentCount = 0;

foreach ($curriculum as $row) {

    if (in_array($row['subject_id'], $completed)) {

        $row['status'] = 'completed';
        $completedCount++;
    } elseif (in_array($row['subject_id'], $current)) {

        $row['status'] = 'current';
        $currentCount++;
    } else {

        $row['status'] = 'remaining';
    }

    $year = $row['year_level'];
    $trimester = $row['trimester'];

    if (!isset($roadmap[$year])) {

        $roadmap[$year] = [
            'year' => $year,
            'trimesters' => []
        ];
    }

    if (!isset($roadmap[$year]['trimesters'][$trimester])) {

        $roadmap[$year]['trimesters'][$trimester] = [
            'trimester' => $trimester,
            'subjects' => []
        ];
    }

    $roadmap[$year]['trimesters'][$trimester]['subjects'][] = $row;
}

foreach ($roadmap as &$year) {

    $year['trimesters'] = array_values($year['trimesters']);
}

$roadmap = array_values($roadmap);

$total = count($curriculum);

$remaining = $total - $completedCount - $currentCount;

$progress = $total
    ? round(($completedCount / $total) * 100)
    : 0;

echo json_encode([

    'completed' => $completedCount,

    'current' => $currentCount,

    'remaining' => $remaining,

    'progress' => $progress,

    'roadmap' => $roadmap

]);
