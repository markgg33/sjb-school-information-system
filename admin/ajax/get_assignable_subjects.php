<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$course_id  = (int)($_GET['course_id'] ?? 0);
$year_level = (int)($_GET['year_level'] ?? 0);
$trimester  = (int)($_GET['trimester'] ?? 0);

if (!$course_id || !$year_level || !$trimester) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
SELECT

    s.id,
    s.subject_code,
    s.subject_name,
    s.units

FROM curriculum c

INNER JOIN subjects s
    ON s.id = c.subject_id

WHERE

    c.course_id = ?
AND c.year_level = ?
AND c.trimester = ?
AND c.is_active = 1

ORDER BY s.subject_code
");

$stmt->execute([
    $course_id,
    $year_level,
    $trimester
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
