<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $pdo->beginTransaction();

    $enrollment_id = $_POST['enrollment_id'] ?? 0;

    $school_year = trim($_POST['school_year'] ?? '');

    $year_level = (int)($_POST['year_level'] ?? 0);

    $trimester = (int)($_POST['trimester'] ?? 0);


    $section_id = !empty($_POST['section_id'])
        ? (int)$_POST['section_id']
        : null;

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
        $school_year === '' ||
        !$year_level ||
        !$trimester
    ) {
        throw new Exception(
            'Please complete all required fields.'
        );
    }

    if (empty($all_subject_ids)) {

        throw new Exception(
            'Please select subjects.'
        );
    }

    //====================================
    // GET STUDENT ID
    //====================================

    $stmt = $pdo->prepare("
SELECT student_id
FROM enrollments
WHERE id = ?
");

    $stmt->execute([
        $enrollment_id
    ]);

    $student_id = $stmt->fetchColumn();

    if (!$student_id) {

        throw new Exception(
            'Enrollment not found.'
        );
    }

    //====================================
    // CHECK DUPLICATE ENROLLMENT
    //====================================

    $sql = "
SELECT id
FROM enrollments
WHERE student_id = ?
AND school_year = ?
AND year_level = ?
AND trimester = ?
AND id <> ?
";

    $params = [

        $student_id,
        $school_year,
        $year_level,
        $trimester,
        $enrollment_id

    ];

    if ($section_id === null) {

        $sql .= " AND section_id IS NULL";
    } else {

        $sql .= " AND section_id = ?";

        $params[] = $section_id;
    }

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    if ($stmt->fetch()) {

        throw new Exception(
            'Another enrollment already exists.'
        );
    }

    //====================================
    // UPDATE ENROLLMENT DETAILS
    //====================================

    $stmt = $pdo->prepare("
UPDATE enrollments
SET

    school_year = ?,
    year_level = ?,
    trimester = ?,
    section_id = ?

WHERE id = ?
");

    $stmt->execute([

        $school_year,
        $year_level,
        $trimester,
        $section_id,
        $enrollment_id

    ]);

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

        $gradeStmt->execute([
            $pdo->lastInsertId()
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

