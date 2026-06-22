<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
    SELECT
        id,
        student_number,
        first_name,
        last_name,
        course_id,
        status
    FROM students
    WHERE status = 'active'
    ORDER BY last_name, first_name
");

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
