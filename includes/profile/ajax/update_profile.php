<?php

require_once '../../db.php';
require_once '../../sessions.php';

header('Content-Type: application/json');

try {

    $user_id = $_SESSION['user_id'];

    $email = trim($_POST['email'] ?? '');

    $first_name = trim($_POST['first_name'] ?? '');

    $middle_name = trim($_POST['middle_name'] ?? '');

    $last_name = trim($_POST['last_name'] ?? '');

    if ($email == '') {
        throw new Exception("Email is required.");
    }

    //====================================
    // GET USER ROLE
    //====================================

    $stmt = $pdo->prepare("
SELECT role
FROM users
WHERE id=?
");

    $stmt->execute([$user_id]);

    $role = $stmt->fetchColumn();


    //====================================
    // CHECK DUPLICATE EMAIL
    //====================================

    $stmt = $pdo->prepare("
SELECT id
FROM users
WHERE email=?
AND id<>?
");

    $stmt->execute([
        $email,
        $user_id
    ]);

    if ($stmt->fetch()) {

        throw new Exception(
            "Email already exists."
        );
    }

    $pdo->beginTransaction();

    //====================================
    // UPDATE ACCOUNT
    //====================================

    $pdo->prepare("
UPDATE users
SET
    email=?,
    first_name=?,
    middle_name=?,
    last_name=?
WHERE id=?
")->execute([
        $email,
        $first_name,
        $middle_name,
        $last_name,
        $user_id
    ]);


    //====================================
    // UPDATE PROFILE
    //====================================

    switch ($role) {

        case 'faculty':

            $pdo->prepare("
        UPDATE faculty
        SET
            email=?,
            first_name=?,
            middle_name=?,
            last_name=?
        WHERE user_id=?
        ")->execute([
                $email,
                $first_name,
                $middle_name,
                $last_name,
                $user_id
            ]);

            break;


        case 'student':

            $pdo->prepare("
        UPDATE students
        SET
            email=?,
            first_name=?,
            middle_name=?,
            last_name=?
        WHERE user_id=?
        ")->execute([
                $email,
                $first_name,
                $middle_name,
                $last_name,
                $user_id
            ]);

            break;

        case 'admin':
            // Nothing else to update.
            break;
    }

    $pdo->commit();

    $_SESSION['email'] = $email;

    $_SESSION['display_name'] = trim(
        $first_name .
            ' ' .
            ($middle_name
                ? $middle_name . ' '
                : '') .
            $last_name
    );

    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully."
    ]);
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
