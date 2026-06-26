<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $pdo->beginTransaction();

    $enrollment_id = $_POST['enrollment_id'] ?? 0;

    $subject_ids = $_POST['subject_ids'] ?? [];

    $manual_subject_ids = $_POST['manual_subject_ids'] ?? [];

    $all_subject_ids = array_unique(
        array_merge(
            $subject_ids,
            $manual_subject_ids
        )
    );

    if (
        !$enrollment_id ||
        empty($all_subject_ids)
    ) {
        throw new Exception(
            'Please select subjects.'
        );
    }

    // remove old

    //====================================
    // GET CURRENT SUBJECTS
    //====================================

    $stmt = $pdo->prepare("
    SELECT subject_id
    FROM enrollment_subjects
    WHERE enrollment_id = ?
");

    $stmt->execute([
        $enrollment_id
    ]);

    $current_subjects =
        $stmt->fetchAll(PDO::FETCH_COLUMN);

    //====================================
    // COMPARE CHANGES
    //====================================

    $to_add = array_diff(
        $all_subject_ids,
        $current_subjects
    );

    $to_remove = array_diff(
        $current_subjects,
        $all_subject_ids
    );

    //====================================
    // ADD NEW SUBJECTS
    //====================================

    $insertStmt = $pdo->prepare("
    INSERT INTO enrollment_subjects
    (
        enrollment_id,
        subject_id
    )
    VALUES
    (
        ?, ?
    )
");

    $gradeStmt = $pdo->prepare("
    INSERT INTO grades
    (
        enrollment_subject_id
    )
    VALUES
    (
        ?
    )
");

    foreach ($to_add as $subject_id) {

        $insertStmt->execute([
            $enrollment_id,
            $subject_id
        ]);

        $enrollment_subject_id =
            $pdo->lastInsertId();

        $pdo->prepare("
INSERT INTO grades
(
    enrollment_subject_id
)
VALUES
(
    ?
)
")->execute([
            $enrollment_subject_id
        ]);

        $enrollment_subject_id =
            $pdo->lastInsertId();

        $gradeStmt->execute([
            $enrollment_subject_id
        ]);
    }

    //====================================
    // REMOVE SUBJECTS
    //====================================

    $deleteStmt = $pdo->prepare("
    DELETE FROM enrollment_subjects
    WHERE enrollment_id = ?
    AND subject_id = ?
");

    foreach ($to_remove as $subject_id) {

        $deleteStmt->execute([
            $enrollment_id,
            $subject_id
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Enrollment updated successfully.'
    ]);
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
