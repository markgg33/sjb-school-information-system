<?php

require '../../includes/db.php';

header('Content-Type: application/json');

$search = trim($_GET['search'] ?? '');

$sql = "
SELECT
    id,
    subject_code,
    subject_name,
    units
FROM subjects
WHERE status='active'
";

$params = [];

if (!empty($search)) {

    $sql .= "
        AND (
            subject_code LIKE ?
            OR subject_name LIKE ?
        )
    ";

    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

$sql .= "
ORDER BY subject_code
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
