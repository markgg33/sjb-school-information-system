<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $pdo->beginTransaction();

    $student_id  = $_POST['student_id'] ?? 0;
    $school_year = trim($_POST['school_year'] ?? '');
    $year_level  = $_POST['year_level'] ?? 0;
    $trimester   = $_POST['trimester'] ?? 0;

    $subject_ids = $_POST['subject_ids'] ?? [];
    $manual_subject_ids = $_POST['manual_subject_ids'] ?? [];

    if (
        empty($student_id) ||
        empty($school_year) ||
        empty($year_level) ||
        empty($trimester)
    ) {
        throw new Exception(
            'Please complete all required fields.'
        );
    }

    $all_subject_ids = array_unique(
        array_merge(
            $subject_ids,
            $manual_subject_ids
        )
    );

    if (empty($all_subject_ids)) {

        throw new Exception(
            'Please select at least one subject.'
        );
    }

    //====================================
    // GET STUDENT COURSE
    //====================================

    $stmt = $pdo->prepare("
        SELECT course_id
        FROM students
        WHERE id = ?
    ");

    $stmt->execute([$student_id]);

    $course_id = $stmt->fetchColumn();

    if (!$course_id) {
        throw new Exception(
            'Student course not found.'
        );
    }

    //====================================
    // CHECK DUPLICATE ENROLLMENT
    //====================================

    $stmt = $pdo->prepare("
        SELECT id
        FROM enrollments
        WHERE student_id = ?
        AND school_year = ?
        AND trimester = ?
    ");

    $stmt->execute([
        $student_id,
        $school_year,
        $trimester
    ]);

    if ($stmt->fetch()) {
        throw new Exception(
            'Student is already enrolled for this trimester.'
        );
    }

    //====================================
    // CHECK PREVIOUSLY TAKEN SUBJECTS
    //====================================

    $existingSubjects = $pdo->prepare("
    SELECT es.subject_id
    FROM enrollment_subjects es
    INNER JOIN enrollments e
        ON e.id = es.enrollment_id
    WHERE e.student_id = ?
");

    $existingSubjects->execute([
        $student_id
    ]);

    $takenSubjects =
        $existingSubjects->fetchAll(
            PDO::FETCH_COLUMN
        );

    $all_subject_ids = array_diff(
        $all_subject_ids,
        $takenSubjects
    );

    if (empty($all_subject_ids)) {

        throw new Exception(
            'All selected subjects were already taken by the student.'
        );
    }


    //====================================
    // CREATE ENROLLMENT
    //====================================

    $stmt = $pdo->prepare("
        INSERT INTO enrollments
        (
            student_id,
            course_id,
            school_year,
            year_level,
            trimester,
            status
        )
        VALUES
        (
            ?, ?, ?, ?, ?, 'enrolled'
        )
    ");

    $stmt->execute([
        $student_id,
        $course_id,
        $school_year,
        $year_level,
        $trimester
    ]);

    $enrollment_id = $pdo->lastInsertId();

    //====================================
    // SAVE SUBJECTS
    //====================================

    $stmtSubject = $pdo->prepare("
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

    //====================================
    // INSERT GRADES
    //====================================

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

    foreach ($all_subject_ids as $subject_id) {

        $stmtSubject->execute([
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

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Enrollment saved successfully.'
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
