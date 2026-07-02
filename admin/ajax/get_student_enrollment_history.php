<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$student_id = (int)($_GET['student_id'] ?? 0);

$stmt = $pdo->prepare("
SELECT
    e.*,

    cs.section_name

FROM enrollments e

LEFT JOIN course_sections cs
    ON cs.id = e.section_id

WHERE e.student_id = ?

ORDER BY
    e.school_year DESC,
    e.trimester DESC
");

$stmt->execute([$student_id]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
