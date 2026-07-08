<?php

require_once '../../db.php';
require_once '../../sessions.php';

header("Content-Type: application/json");

try {

    $user_id = $_SESSION['user_id'];

    $current = trim($_POST['current_password'] ?? '');
    $new = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    /*
    |--------------------------------------------------------------------------
    | REQUIRED
    |--------------------------------------------------------------------------
    */

    if ($current === '' || $new === '' || $confirm === '') {

        throw new Exception(
            "All password fields are required."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MATCH
    |--------------------------------------------------------------------------
    */

    if ($new !== $confirm) {

        throw new Exception(
            "Passwords do not match."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MINIMUM LENGTH
    |--------------------------------------------------------------------------
    */

    if (strlen($new) < 7) {

        throw new Exception(
            "Password must be at least 7 characters long."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPPERCASE
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/[A-Z]/', $new)) {

        throw new Exception(
            "Password must contain at least one uppercase letter."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOWERCASE
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/[a-z]/', $new)) {

        throw new Exception(
            "Password must contain at least one lowercase letter."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/[0-9]/', $new)) {

        throw new Exception(
            "Password must contain at least one number."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SPECIAL CHARACTER
    |--------------------------------------------------------------------------
    */

    if (!preg_match('/[!@#$%^&*()_\-+=\[\]{}|:;"\'<>,.?\/\\\\]/', $new)) {

        throw new Exception(
            "Password must contain at least one special character."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CURRENT PASSWORD
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
    SELECT password
    FROM users
    WHERE id=?
    ");

    $stmt->execute([$user_id]);

    $password = $stmt->fetchColumn();

    if (!$password_verify = password_verify($current, $password)) {

        throw new Exception(
            "Current password is incorrect."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SAME PASSWORD
    |--------------------------------------------------------------------------
    */

    if (password_verify($new, $password)) {

        throw new Exception(
            "New password must be different from the current password."
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

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
        "message" => "Password updated successfully."
    ]);
} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
