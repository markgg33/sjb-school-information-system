<?php

require_once '../../../includes/db.php';
require_once '../../../includes/sessions.php';
require_once '../../../includes/activity_logger.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$faculty_subject_id = $data['faculty_subject_id'];
$period = $data['period'];

$name = trim($data['component_name']);
$type = trim($data['component_type']);

$max = $data['max_score'];
$weight = $data['weight'];

try {

    $pdo->beginTransaction();

    /*
    ----------------------------------
    CREATE SCHEME IF NONE EXISTS
    ----------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT id
        FROM grading_schemes
        WHERE faculty_subject_id=?
        AND period=?
    ");

    $stmt->execute([
        $faculty_subject_id,
        $period
    ]);

    $scheme = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$scheme) {

        $stmt = $pdo->prepare("
            INSERT INTO grading_schemes
            (
                faculty_subject_id,
                period
            )
            VALUES
            (?,?)
        ");

        $stmt->execute([
            $faculty_subject_id,
            $period
        ]);

        $scheme_id = $pdo->lastInsertId();
    } else {

        $scheme_id = $scheme['id'];
    }

    /*
    ----------------------------------
    ONE EXAM ONLY
    ----------------------------------
    */

    if ($type == "Exam") {

        $stmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM grading_components
            WHERE grading_scheme_id=?
            AND component_type='Exam'
        ");

        $stmt->execute([$scheme_id]);

        if ($stmt->fetchColumn() > 0) {

            throw new Exception(
                "Only one Exam is allowed."
            );
        }
    }

    /*
    ----------------------------------
    TOTAL WEIGHT
    ----------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT IFNULL(
            SUM(weight),
            0
        )
        FROM grading_components
        WHERE grading_scheme_id=?
    ");

    $stmt->execute([$scheme_id]);

    $total = $stmt->fetchColumn();

    if (($total + $weight) > 100) {

        throw new Exception(
            "Weight exceeds 100%."
        );
    }

    /*
    ----------------------------------
    DISPLAY ORDER
    ----------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT
        IFNULL(MAX(display_order),0)+1
        FROM grading_components
        WHERE grading_scheme_id=?
    ");

    $stmt->execute([
        $scheme_id
    ]);

    $order = $stmt->fetchColumn();

    /*
    ----------------------------------
    INSERT
    ----------------------------------
    */

    $stmt = $pdo->prepare("

        INSERT INTO grading_components(

            grading_scheme_id,

            component_name,

            component_type,

            max_score,

            weight,

            display_order

        )

        VALUES(?,?,?,?,?,?)

    ");

    $stmt->execute([

        $scheme_id,

        $name,

        $type,

        $max,

        $weight,

        $order

    ]);

    $pdo->commit();

    logActivity(
        $_SESSION['user_id'],
        $_SESSION['role'],
        'Added Grading Component',
        "{$period}: {$name} ({$type})",
        $scheme_id
    );

    echo json_encode([
        "success" => true
    ]);
} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([

        "success" => false,

        "message" => $e->getMessage()

    ]);
}
