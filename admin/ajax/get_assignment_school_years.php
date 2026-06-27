<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("
SELECT DISTINCT school_year
FROM enrollments
ORDER BY school_year DESC
");

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_COLUMN)
);
