<?php

require_once '../../../includes/db.php';

$faculty_subject_id =
    (int)($_GET['faculty_subject_id'] ?? 0);

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

f.first_name,

f.last_name,

st.student_number,

st.first_name AS student_first,

st.last_name AS student_last,

st.middle_name,

st.gender,

e.status

FROM faculty_subjects fs

INNER JOIN subjects s
ON s.id=fs.subject_id

INNER JOIN courses c
ON c.id=fs.course_id

LEFT JOIN course_sections sec
ON sec.id=fs.section_id

INNER JOIN faculty f
ON f.id=fs.faculty_id

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

WHERE

fs.id=?

ORDER BY

st.last_name,
st.first_name

");

$stmt->execute([
    $faculty_subject_id
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
