<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

try {

    $user_id = $_SESSION['user_id'];

    $email = trim($_POST['email'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($email == '') {
        throw new Exception("Email is required.");
    }

    //====================================
    // CHECK DUPLICATE EMAIL
    //====================================

    $stmt = $pdo->prepare("
        SELECT id
        FROM users
        WHERE email = ?
        AND id <> ?
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
    // USERS
    //====================================

    $pdo->prepare("
        UPDATE users
        SET email=?
        WHERE id=?
    ")->execute([
        $email,
        $user_id
    ]);

    //====================================
    // STUDENTS
    //====================================

    $pdo->prepare("
        UPDATE students
        SET
            email=?,
            contact_number=?,
            address=?
        WHERE user_id=?
    ")->execute([
        $email,
        $contact_number,
        $address,
        $user_id
    ]);

    $pdo->commit();

    $_SESSION['email'] = $email;

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
