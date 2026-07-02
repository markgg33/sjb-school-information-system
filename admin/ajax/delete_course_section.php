<?php

require '../../includes/db.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
DELETE
FROM course_sections
WHERE id=?
");

$stmt->execute([
    $_POST['id']
]);

echo json_encode([
    "success" => true
]);
