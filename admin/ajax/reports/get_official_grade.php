<?php

require_once '../../../includes/db.php';

header('Content-Type: application/json');

$enrollment_id = (int)($_GET['enrollment_id'] ?? 0);

if (!$enrollment_id) {

    echo json_encode([]);
    exit;
}

/*
|--------------------------------------------------------------------------
| HEADER INFORMATION
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

e.id,

e.course_id,
e.section_id,

e.school_year,
e.trimester,
e.year_level,

st.student_number,

CONCAT(

st.last_name,
', ',
st.first_name,

IF(
st.middle_name IS NULL,
'',
CONCAT(' ',LEFT(st.middle_name,1),'.')
)

) AS student_name,

c.course_code,
c.course_name,

sec.section_name

FROM enrollments e

INNER JOIN students st
ON st.id=e.student_id

INNER JOIN courses c
ON c.id=e.course_id

LEFT JOIN course_sections sec
ON sec.id=e.section_id

WHERE e.id=?

LIMIT 1

");

$stmt->execute([$enrollment_id]);

$header = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$header) {

    echo json_encode([]);
    exit;
}

/*
|--------------------------------------------------------------------------
| SUBJECTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

sub.subject_code,
sub.subject_name,

CONCAT(
    f.first_name,
    ' ',
    f.last_name
) AS faculty_name,

g.prelim_grade,
g.midterm_grade,
g.final_grade,
g.overall_grade,
g.remarks

FROM enrollment_subjects es

INNER JOIN subjects sub
ON sub.id = es.subject_id

LEFT JOIN grades g
ON g.enrollment_subject_id = es.id

LEFT JOIN faculty_subjects fs

ON fs.subject_id = es.subject_id
AND fs.course_id = ?
AND fs.school_year = ?
AND fs.trimester = ?
AND fs.year_level = ?

AND (

(fs.section_id IS NULL AND ? IS NULL)

OR

fs.section_id = ?

)

LEFT JOIN faculty f
ON f.id = fs.faculty_id

WHERE es.enrollment_id = ?

ORDER BY sub.subject_code

");

$stmt->execute([

    $header['course_id'],
    $header['school_year'],
    $header['trimester'],
    $header['year_level'],
    $header['section_id'],
    $header['section_id'],
    $enrollment_id

]);

$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

$passed = 0;

$failed = 0;

$total = 0;

$count = 0;

foreach ($subjects as $row) {

    if ($row['overall_grade'] !== null) {

        $total += $row['overall_grade'];

        $count++;
        
        if ($row['remarks'] == 'Passed') {

            $passed++;
        } elseif ($row['remarks'] == 'Failed') {

            $failed++;
        }
    }
}

$summary = [

    "subjects" => count($subjects),

    "passed" => $passed,

    "failed" => $failed,

    "average" =>

    $count

        ?

        round($total / $count, 2)

        :

        null

];

echo json_encode([

    "header" => $header,

    "subjects" => $subjects,

    "summary" => $summary

]);
