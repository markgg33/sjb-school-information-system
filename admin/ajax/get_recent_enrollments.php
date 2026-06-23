<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
SELECT
    s.first_name,
    s.last_name,
    e.school_year,
    e.year_level,
    e.trimester
FROM enrollments e
INNER JOIN students s
    ON s.id = e.student_id
ORDER BY e.id DESC
LIMIT 10
");

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
