<?php

require_once '../../../includes/db.php';

header('Content-Type: application/json');

$faculty_subject_id = (int)($_GET['faculty_subject_id'] ?? 0);
$period = $_GET['period'] ?? 'Prelim';

if (!$faculty_subject_id) {
    echo json_encode([]);
    exit;
}

/*
|--------------------------------------------------------------------------
| HEADER
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

fs.id,

fs.subject_id,
fs.course_id,
fs.year_level,
fs.section_id,
fs.school_year,
fs.trimester,

s.subject_code,
s.subject_name,

c.course_code,
c.course_name,

sec.section_name,

CONCAT(
    f.first_name,
    ' ',
    f.last_name
) AS faculty_name

FROM faculty_subjects fs

INNER JOIN subjects s
ON s.id=fs.subject_id

INNER JOIN courses c
ON c.id=fs.course_id

INNER JOIN faculty f
ON f.id=fs.faculty_id

LEFT JOIN course_sections sec
ON sec.id=fs.section_id

WHERE fs.id=?

");

$stmt->execute([$faculty_subject_id]);

$header = $stmt->fetch(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| COMPONENTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

gc.id,
gc.component_name,
gc.component_type,
gc.max_score,
gc.weight

FROM grading_schemes gs

INNER JOIN grading_components gc
ON gc.grading_scheme_id=gs.id

WHERE

gs.faculty_subject_id=?

AND gs.period=?

ORDER BY

gc.component_type,
gc.id

");

$stmt->execute([
    $faculty_subject_id,
    $period
]);

$components = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| STUDENTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

es.id AS enrollment_subject_id,

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

g.prelim_grade,
g.midterm_grade,
g.final_grade

FROM faculty_subjects fs

INNER JOIN enrollments e

ON e.course_id=fs.course_id
AND e.year_level=fs.year_level
AND e.school_year=fs.school_year
AND e.trimester=fs.trimester

AND(

(fs.section_id IS NULL AND e.section_id IS NULL)

OR

e.section_id=fs.section_id

)

INNER JOIN students st
ON st.id=e.student_id

INNER JOIN enrollment_subjects es

ON es.enrollment_id=e.id

AND es.subject_id=fs.subject_id

LEFT JOIN grades g
ON g.enrollment_subject_id = es.id

WHERE

fs.id=?

ORDER BY

st.last_name,
st.first_name

");

$stmt->execute([$faculty_subject_id]);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| SCORES
|--------------------------------------------------------------------------
*/

$scoreMap = [];

if (!empty($components)) {

    $componentIds = array_column($components, 'id');

    $placeholders = implode(',', array_fill(0, count($componentIds), '?'));

    $stmt = $pdo->prepare("

SELECT

grading_component_id,
enrollment_subject_id,
score

FROM student_scores

WHERE grading_component_id IN($placeholders)

");

    $stmt->execute($componentIds);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $scoreMap[$row['enrollment_subject_id']][$row['grading_component_id']]

            =

            $row['score'];
    }
}

foreach ($students as &$student) {

    $student['scores'] = [];

    foreach ($components as $component) {

        $student['scores'][] =

            $scoreMap[$student['enrollment_subject_id']][$component['id']]

            ??

            "";
    }
}

foreach ($students as &$student) {

    switch ($period) {

        case 'Prelim':
            $student['period_grade'] = $student['prelim_grade'];
            break;

        case 'Midterm':
            $student['period_grade'] = $student['midterm_grade'];
            break;

        case 'Finals':
            $student['period_grade'] = $student['final_grade'];
            break;

        default:
            $student['period_grade'] = null;
    }
}

echo json_encode([
    "header" => $header,
    "components" => $components,
    "students" => $students
]);
