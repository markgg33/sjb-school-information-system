<?php

require_once '../../includes/faculty_session.php';

$faculty = $currentFacultyId;

header('Content-Type: application/json');


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
SELECT COUNT(DISTINCT e.student_id)

FROM faculty_subjects fs

INNER JOIN enrollment_subjects es
    ON es.subject_id = fs.subject_id

INNER JOIN enrollments e
    ON e.id = es.enrollment_id
    AND e.course_id = fs.course_id
    AND e.year_level = fs.year_level
    AND e.school_year = fs.school_year
    AND e.trimester = fs.trimester
    AND (
        (fs.section_id IS NULL AND e.section_id IS NULL)
        OR fs.section_id = e.section_id
    )

WHERE fs.faculty_id = ?
");

$stmt->execute([$faculty]);

$students = $stmt->fetchColumn();

/*
Sections
*/

$stmt = $pdo->prepare("
SELECT COUNT(*)

FROM faculty_subjects

WHERE faculty_id = ?
");

$stmt->execute([$faculty]);

$sections = $stmt->fetchColumn();

/*
|--------------------------------------------------------------------------
| Grade Progress
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

COUNT(*) total,

SUM(

CASE

WHEN g.grading_status='Completed'

THEN 1

ELSE 0

END

) completed

FROM faculty_subjects fs

INNER JOIN enrollment_subjects es
ON es.subject_id=fs.subject_id

INNER JOIN enrollments e

ON e.id=es.enrollment_id
AND e.course_id=fs.course_id
AND e.year_level=fs.year_level
AND e.school_year=fs.school_year
AND e.trimester=fs.trimester

AND(

(fs.section_id IS NULL AND e.section_id IS NULL)

OR

fs.section_id=e.section_id

)

LEFT JOIN grades g
ON g.enrollment_subject_id=es.id

WHERE fs.faculty_id=?

");

$stmt->execute([$faculty]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$progress = 0;

if ($row['total'] > 0) {

    $progress = round(($row['completed'] / $row['total']) * 100);
}

echo json_encode([

    "subjects" => $subjects,

    "students" => $students,

    "sections" => $sections,

    "gradeProgress" => $progress

]);
