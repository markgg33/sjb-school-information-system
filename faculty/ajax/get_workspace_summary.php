<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$faculty_subject_id = intval($_GET['faculty_subject_id'] ?? 0);
$period             = $_GET['period'] ?? 'Prelim';

$section_id = isset($_GET['section_id']) &&
    $_GET['section_id'] !== ''
    ? (int)$_GET['section_id']
    : null;

if (!$faculty_subject_id) {
    echo json_encode([]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Determine grade column
|--------------------------------------------------------------------------
*/

$gradeColumn = match ($period) {

    'Prelim'  => 'prelim_grade',

    'Midterm' => 'midterm_grade',

    'Finals'  => 'final_grade',

    default   => 'prelim_grade'
};

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/

/*$stmt = $pdo->prepare("

SELECT

COUNT(DISTINCT es.id) AS students,

COUNT(DISTINCT gc.id) AS components,

AVG(g.$gradeColumn) AS average_grade,

MAX(g.$gradeColumn) AS highest,

MIN(g.$gradeColumn) AS lowest,

SUM(
CASE
WHEN g.$gradeColumn>=75 THEN 1
ELSE 0
END
) AS passed,

SUM(
CASE
WHEN g.$gradeColumn<75 THEN 1
ELSE 0
END
) AS failed

FROM faculty_subjects fs

LEFT JOIN grading_schemes gs
ON gs.faculty_subject_id=fs.id

LEFT JOIN grading_components gc
ON gc.grading_scheme_id=gs.id

LEFT JOIN enrollments e
ON e.course_id = fs.course_id
AND e.year_level = fs.year_level
AND e.school_year = fs.school_year
AND e.trimester = fs.trimester

LEFT JOIN enrollment_subjects es
ON es.enrollment_id=e.id
AND es.subject_id=fs.subject_id

LEFT JOIN grades g
ON g.enrollment_subject_id=es.id

WHERE

fs.id=?

");

$stmt->execute([$faculty_subject_id]);*/

$sql = "

SELECT

COUNT(DISTINCT es.id) AS students,

COUNT(DISTINCT gc.id) AS components,

AVG(g.$gradeColumn) AS average_grade,

MAX(g.$gradeColumn) AS highest,

MIN(g.$gradeColumn) AS lowest,

SUM(
CASE
WHEN g.$gradeColumn >= 75 THEN 1
ELSE 0
END
) AS passed,

SUM(
CASE
WHEN g.$gradeColumn < 75 THEN 1
ELSE 0
END
) AS failed

FROM faculty_subjects fs

LEFT JOIN grading_schemes gs
    ON gs.faculty_subject_id = fs.id

LEFT JOIN grading_components gc
    ON gc.grading_scheme_id = gs.id

LEFT JOIN enrollments e
    ON e.course_id = fs.course_id
    AND e.year_level = fs.year_level
    AND e.school_year = fs.school_year
    AND e.trimester = fs.trimester
";

$params = [];

if ($section_id === null) {

    $sql .= "
    AND e.section_id IS NULL
    ";
} else {

    $sql .= "
    AND e.section_id = ?
    ";

    $params[] = $section_id;
}

$sql .= "

LEFT JOIN enrollment_subjects es
    ON es.enrollment_id = e.id
    AND es.subject_id = fs.subject_id

LEFT JOIN grades g
    ON g.enrollment_subject_id = es.id

WHERE

    fs.id = ?

";

$params[] = $faculty_subject_id;

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
