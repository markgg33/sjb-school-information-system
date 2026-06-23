<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$enrollment_id =
    (int)($_GET['enrollment_id'] ?? 0);

$stmt = $pdo->prepare("
SELECT
    es.id,
    s.id AS subject_id,
    s.subject_code,
    s.subject_name,
    s.units
FROM enrollment_subjects es
INNER JOIN subjects s
    ON s.id = es.subject_id
WHERE es.enrollment_id = ?
ORDER BY s.subject_code
");

$stmt->execute([
    $enrollment_id
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
