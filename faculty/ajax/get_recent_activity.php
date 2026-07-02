<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
SELECT

    activity,
    description,
    created_at

FROM activity_logs

WHERE user_id = ?

ORDER BY created_at DESC

LIMIT 10
");

$stmt->execute([
    $_SESSION['user_id']
]);

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
