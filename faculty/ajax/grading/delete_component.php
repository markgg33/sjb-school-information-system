<?php

require_once '../../../includes/db.php';
require_once '../../../includes/sessions.php';
require_once '../../../includes/activity_logger.php';

header('Content-Type: application/json');

$id = (int)($_POST['id'] ?? 0);

try {

    $pdo->beginTransaction();

    /*
    -----------------------------------------
    GET COMPONENT
    -----------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT
            component_name
        FROM grading_components
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $component = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$component) {

        throw new Exception("Component not found.");
    }

    /*
    -----------------------------------------
    DELETE SCORES
    -----------------------------------------
    */

    $stmt = $pdo->prepare("
        DELETE ss
        FROM student_scores ss

        INNER JOIN grading_components gc

            ON gc.id = ss.grading_component_id

        WHERE gc.id=?
    ");

    $stmt->execute([$id]);

    /*
    -----------------------------------------
    DELETE COMPONENT
    -----------------------------------------
    */

    $stmt = $pdo->prepare("
        DELETE
        FROM grading_components
        WHERE id=?
    ");

    $stmt->execute([$id]);

    $pdo->commit();

    logActivity(

        $_SESSION['user_id'],

        $_SESSION['role'],

        "Deleted Grading Component",

        $component['component_name'],

        $id

    );

    echo json_encode([

        "success" => true

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
