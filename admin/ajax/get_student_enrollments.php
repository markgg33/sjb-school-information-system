<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$student_id = (int)($_GET['student_id'] ?? 0);

$stmt = $pdo->prepare("

SELECT

e.id AS enrollment_id,

e.school_year,

e.trimester,

e.year_level,

c.course_code,

cs.section_name

FROM enrollments e

INNER JOIN courses c
ON c.id=e.course_id

LEFT JOIN course_sections cs
ON cs.id=e.section_id

WHERE e.student_id=?

ORDER BY

e.school_year DESC,

e.trimester DESC

");

$stmt->execute([$student_id]);

echo json_encode(

    $stmt->fetchAll(PDO::FETCH_ASSOC)

);
