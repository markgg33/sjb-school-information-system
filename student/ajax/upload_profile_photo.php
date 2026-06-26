<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

try {

    if (empty($_FILES['photo'])) {

        throw new Exception(
            "No image selected."
        );
    }

    $user_id = $_SESSION['user_id'];

    $allowed = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    $extension = strtolower(
        pathinfo(
            $_FILES['photo']['name'],
            PATHINFO_EXTENSION
        )
    );

    if (!in_array($extension, $allowed)) {

        throw new Exception(
            "Invalid image format."
        );
    }

    $filename = "user_" . $user_id . "." . $extension;

    $destination =
        "../../uploads/profile/" . $filename;

    move_uploaded_file(
        $_FILES['photo']['tmp_name'],
        $destination
    );

    $pdo->prepare("
        UPDATE users
        SET photo=?
        WHERE id=?
    ")->execute([
        $filename,
        $user_id
    ]);

    $_SESSION['photo'] = $filename;

    echo json_encode([
        "success" => true,
        "filename" => $filename
    ]);
} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
