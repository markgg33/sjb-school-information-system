<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {
    $faculty_id  = (int)($_POST['faculty_id'] ?? 0);
    $school_year = trim($_POST['school_year'] ?? '');
    $trimester   = (int)($_POST['trimester'] ?? 0);
    $assignments = $_POST['assignments'] ?? null;

    if (!$faculty_id || !$school_year || !$trimester) {
        throw new Exception("Please complete all required fields.");
    }

    if (is_string($assignments)) {
        $assignments = json_decode($assignments, true);
    }

    if (!is_array($assignments) || empty($assignments)) {
        $course_id  = (int)($_POST['course_id'] ?? 0);
        $year_level = (int)($_POST['year_level'] ?? 0);
        $subjects   = $_POST['subjects'] ?? [];

        if (!$course_id || !$year_level) {
            throw new Exception("Please complete all required fields.");
        }

        $assignments = [[
            'course_id'  => $course_id,
            'year_level' => $year_level,
            'subjects'   => $subjects,
        ]];
    }

    $totalSubjects = 0;

    foreach ($assignments as $assignment) {
        $subjects = $assignment['subjects'] ?? [];
        $totalSubjects += is_array($subjects) ? count($subjects) : 0;
    }

    if ($totalSubjects === 0) {
        throw new Exception("Please assign at least one subject.");
    }

    $conflictStmt = $pdo->prepare("
        SELECT
            s.subject_code,
            s.subject_name,
            f.first_name,
            f.last_name
        FROM faculty_subjects fs
        INNER JOIN subjects s
            ON s.id = fs.subject_id
        INNER JOIN faculty f
            ON f.id = fs.faculty_id
        WHERE fs.school_year = ?
        AND fs.trimester = ?
        AND fs.course_id = ?
        AND fs.year_level = ?
        AND fs.faculty_id != ?
        AND fs.subject_id = ?
        LIMIT 1
    ");

    foreach ($assignments as $assignment) {
        $course_id  = (int)($assignment['course_id'] ?? 0);
        $year_level = (int)($assignment['year_level'] ?? 0);
        $subjects   = $assignment['subjects'] ?? [];

        if (!$course_id || !$year_level) {
            throw new Exception("Invalid assignment data.");
        }

        foreach ($subjects as $subject) {
            $conflictStmt->execute([
                $school_year,
                $trimester,
                $course_id,
                $year_level,
                $faculty_id,
                (int)$subject,
            ]);

            $conflict = $conflictStmt->fetch(PDO::FETCH_ASSOC);

            if ($conflict) {
                throw new Exception(
                    $conflict['subject_code'] .
                    " is already assigned to " .
                    $conflict['last_name'] . ", " .
                    $conflict['first_name'] . "."
                );
            }
        }
    }

    $pdo->beginTransaction();

    $delete = $pdo->prepare("
        DELETE FROM faculty_subjects
        WHERE faculty_id = ?
        AND course_id = ?
        AND year_level = ?
        AND school_year = ?
        AND trimester = ?
    ");

    $insert = $pdo->prepare("
        INSERT INTO faculty_subjects
        (
            faculty_id,
            subject_id,
            course_id,
            year_level,
            school_year,
            trimester
        )
        VALUES (?,?,?,?,?,?)
    ");

    foreach ($assignments as $assignment) {
        $course_id  = (int)($assignment['course_id'] ?? 0);
        $year_level = (int)($assignment['year_level'] ?? 0);
        $subjects   = $assignment['subjects'] ?? [];

        if (!$course_id || !$year_level) {
            throw new Exception("Invalid assignment data.");
        }

        $delete->execute([
            $faculty_id,
            $course_id,
            $year_level,
            $school_year,
            $trimester,
        ]);

        foreach ($subjects as $subject) {
            $insert->execute([
                $faculty_id,
                (int)$subject,
                $course_id,
                $year_level,
                $school_year,
                $trimester,
            ]);
        }
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Subject assignment updated.",
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
    ]);
}
