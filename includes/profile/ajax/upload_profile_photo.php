<?php

require_once '../../db.php';
require_once '../../sessions.php';

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

    //====================================
    // FILE SIZE
    //====================================

    if ($_FILES['photo']['size'] > (2 * 1024 * 1024)) {

        throw new Exception(
            "Image must not exceed 2MB."
        );
    }

    if (!in_array($extension, $allowed)) {

        //====================================
        // VALIDATE IMAGE
        //====================================

        $imageInfo = getimagesize(
            $_FILES['photo']['tmp_name']
        );

        if ($imageInfo === false) {

            throw new Exception(
                "Invalid image."
            );
        }

        throw new Exception(
            "Invalid image format."
        );
    }

    $filename = "user_" . $user_id . "." . $extension;

    $destination =
        "../../../uploads/profile/" . $filename;

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
