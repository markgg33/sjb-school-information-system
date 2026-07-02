<?php

require '../../includes/db.php';

header('Content-Type: application/json');

$course_id = (int)($_GET['course_id'] ?? 0);
$year_level = (int)($_GET['year_level'] ?? 0);

$stmt = $pdo->prepare("
    SELECT
        id,
        section_name
    FROM course_sections
    WHERE course_id = ?
    AND year_level = ?
    AND status = 'active'
    ORDER BY display_order, section_name
");

$stmt->execute([
    $course_id,
    $year_level
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
