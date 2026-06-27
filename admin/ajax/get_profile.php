<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$stmt = $pdo->prepare("
SELECT

    email,

    first_name,

    middle_name,

    last_name,

    photo

FROM users

WHERE id=?

LIMIT 1
");

$stmt->execute([
    $_SESSION['user_id']
]);

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);
