<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$subject_id   = $_GET['subject_id'] ?? 0;
$course_id    = $_GET['course_id'] ?? 0;
$year_level   = $_GET['year_level'] ?? 0;
$school_year  = $_GET['school_year'] ?? '';
$trimester    = $_GET['trimester'] ?? 0;

$sql = "

SELECT

    es.id AS enrollment_subject_id,

    st.id,

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

    e.status,

    ROUND(

        COALESCE(
            (
                g.final_grade
            ),
            COALESCE(
                g.midterm_grade,
                g.prelim_grade
            )
        ),

        2

    ) AS current_grade

FROM enrollments e

INNER JOIN students st
ON st.id = e.student_id

INNER JOIN enrollment_subjects es
ON es.enrollment_id = e.id

LEFT JOIN grades g
ON g.enrollment_subject_id = es.id

WHERE

es.subject_id = ?

AND e.course_id = ?

AND e.year_level = ?

AND e.school_year = ?

AND e.trimester = ?

ORDER BY

st.last_name,
st.first_name

";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    $subject_id,
    $course_id,
    $year_level,
    $school_year,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
