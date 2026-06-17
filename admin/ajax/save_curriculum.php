<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$course_id = $_POST['course_id'] ?? 0;
$year_level = $_POST['year_level'] ?? 0;
$trimester = $_POST['trimester'] ?? 0;
$subjects = $_POST['subjects'] ?? [];

if (
    !$course_id ||
    !$year_level ||
    !$trimester ||
    empty($subjects)
) {
    echo json_encode([
        'success' => false,
        'message' => 'Please complete all fields.'
    ]);

    exit;
}

try {

    $pdo->beginTransaction();

    //=======================================
    // REMOVE EXISTING CURRICULUM
    //=======================================

    $stmt = $pdo->prepare("
        DELETE FROM curriculum
        WHERE course_id = ?
        AND year_level = ?
        AND trimester = ?
    ");

    $stmt->execute([
        $course_id,
        $year_level,
        $trimester
    ]);

    //=======================================
    // INSERT NEW CURRICULUM
    //=======================================

    $stmt = $pdo->prepare("
        INSERT INTO curriculum (
            course_id,
            year_level,
            trimester,
            subject_id
        )
        VALUES (
            ?, ?, ?, ?
        )
    ");

    foreach ($subjects as $subject_id) {

        $stmt->execute([
            $course_id,
            $year_level,
            $trimester,
            $subject_id
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Curriculum saved successfully.'
    ]);
    
} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
