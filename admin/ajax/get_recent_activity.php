<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
SELECT

    al.id,

    al.activity,
    al.description,

    al.created_at,

    u.first_name,
    u.middle_name,
    u.last_name,

    al.role

FROM activity_logs al

LEFT JOIN users u
    ON u.id = al.user_id

ORDER BY al.created_at DESC

LIMIT 15
");

$stmt->execute();

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_ASSOC)
);
