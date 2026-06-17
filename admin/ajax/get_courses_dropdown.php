<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
    SELECT
        id,
        course_code,
        course_name
    FROM courses
    WHERE status = 'active'
    ORDER BY course_code
");

$stmt->execute();

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
