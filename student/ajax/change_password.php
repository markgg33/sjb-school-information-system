<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header("Content-Type: application/json");

try {

    $user_id = $_SESSION['user_id'];

    $current = $_POST['current_password'];

    $new = $_POST['new_password'];

    $confirm = $_POST['confirm_password'];

    if ($new != $confirm) {

        throw new Exception(
            "Passwords do not match."
        );
    }

    $stmt = $pdo->prepare("
        SELECT password
        FROM users
        WHERE id=?
    ");

    $stmt->execute([
        $user_id
    ]);

    $password = $stmt->fetchColumn();

    if (
        !password_verify(
            $current,
            $password
        )
    ) {

        throw new Exception(
            "Current password is incorrect."
        );
    }

    $hash = password_hash(
        $new,
        PASSWORD_DEFAULT
    );

    $pdo->prepare("
        UPDATE users
        SET password=?
        WHERE id=?
    ")->execute([
        $hash,
        $user_id
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Password updated."
    ]);
} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
