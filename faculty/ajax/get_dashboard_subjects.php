<?php

require_once '../../includes/faculty_session.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("

SELECT

fs.id,

s.subject_code,

s.subject_name,

c.course_code,

fs.year_level,

cs.section_name,

(

SELECT COUNT(DISTINCT e.student_id)

FROM enrollments e

INNER JOIN enrollment_subjects es

ON es.enrollment_id=e.id

AND es.subject_id=fs.subject_id

WHERE

e.course_id=fs.course_id

AND e.school_year=fs.school_year

AND e.trimester=fs.trimester

AND e.year_level=fs.year_level

AND(

(fs.section_id IS NULL AND e.section_id IS NULL)

OR

e.section_id=fs.section_id

)

) students

FROM faculty_subjects fs

INNER JOIN subjects s

ON s.id=fs.subject_id

INNER JOIN courses c

ON c.id=fs.course_id

LEFT JOIN course_sections cs

ON cs.id=fs.section_id

WHERE fs.faculty_id=?

ORDER BY

s.subject_code

");

$stmt->execute([$currentFacultyId]);

echo json_encode(

    $stmt->fetchAll(PDO::FETCH_ASSOC)

);
