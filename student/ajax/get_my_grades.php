<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
SELECT

    e.id AS enrollment_id,

    e.school_year,
    e.year_level,
    e.trimester,

    s.subject_code,
    s.subject_name,
    s.units,

    g.prelim_grade,
    g.midterm_grade,
    g.final_grade,
    g.overall_grade,
    g.grading_status,
    g.remarks

FROM students st

INNER JOIN enrollments e
    ON e.student_id = st.id

INNER JOIN enrollment_subjects es
    ON es.enrollment_id = e.id

INNER JOIN subjects s
    ON s.id = es.subject_id

LEFT JOIN grades g
    ON g.enrollment_subject_id = es.id

WHERE st.user_id = ?

ORDER BY

    e.school_year DESC,
    e.year_level DESC,
    e.trimester DESC,
    s.subject_code
");

$stmt->execute([
    $user_id
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
