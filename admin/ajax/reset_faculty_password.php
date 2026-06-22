<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT user_id
    FROM faculty
    WHERE id = ?
");

$stmt->execute([$id]);

$user_id = $stmt->fetchColumn();

if (!$user_id) {

    echo json_encode([
        'success' => false,
        'message' => 'Faculty not found.'
    ]);

    exit;
}

$password = password_hash(
    'Faculty123!',
    PASSWORD_DEFAULT
);

$pdo->prepare("
    UPDATE users
    SET password = ?
    WHERE id = ?
")->execute([
    $password,
    $user_id
]);

echo json_encode([
    'success' => true,
    'message' => 'Password reset successfully.'
]);
