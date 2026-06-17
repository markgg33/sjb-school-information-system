<?php

require_once '../../includes/db.php';

$stmt = $pdo->query("
    SELECT *
    FROM courses
    ORDER BY course_code
");

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
