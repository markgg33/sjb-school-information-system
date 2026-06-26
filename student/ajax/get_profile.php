<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
SELECT

    u.email,
    u.photo,

    s.student_number,
    s.first_name,
    s.middle_name,
    s.last_name,
    s.contact_number,
    s.address,

    c.course_code,
    c.course_name

FROM users u

INNER JOIN students s
    ON s.user_id = u.id

LEFT JOIN courses c
    ON c.id = s.course_id

WHERE u.id = ?

LIMIT 1
");

$stmt->execute([$user_id]);

echo json_encode(
    $stmt->fetch(PDO::FETCH_ASSOC)
);
