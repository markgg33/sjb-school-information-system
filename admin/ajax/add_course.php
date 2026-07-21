<?php

require_once '../../includes/db.php';
require_once '../../includes/activity_logger.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

$course_code = trim($_POST['course_code'] ?? '');
$course_name = trim($_POST['course_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? 'active';

if (
    empty($course_code) ||
    empty($course_name)
) {
    echo json_encode([
        'success' => false,
        'message' => 'Please complete all required fields.'
    ]);
    exit;
}

try {

    //=======================================
    // UPDATE COURSE
    //=======================================

    if (!empty($id)) {

        $stmt = $pdo->prepare("
            SELECT id
            FROM courses
            WHERE course_code = ?
            AND id != ?
        ");

        $stmt->execute([
            $course_code,
            $id
        ]);

        if ($stmt->fetch()) {

            echo json_encode([
                'success' => false,
                'message' => 'Course code already exists.'
            ]);

            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE courses
            SET
                course_code = ?,
                course_name = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ");

        $success = $stmt->execute([
            $course_code,
            $course_name,
            $description,
            $status,
            $id
        ]);

        if ($success) {

            logActivity(
                $_SESSION['user_id'],
                $_SESSION['role'],
                'Updated Course',
                "Updated {$course_code} - {$course_name}",
                $id
            );
        }

        echo json_encode([
            'success' => $success,
            'message' => $success
                ? 'Course updated successfully.'
                : 'Unable to update course.'
        ]);

        exit;
    }

    //=======================================
    // ADD COURSE
    //=======================================

    $stmt = $pdo->prepare("
        SELECT id
        FROM courses
        WHERE course_code = ?
    ");

    $stmt->execute([$course_code]);

    if ($stmt->fetch()) {

        echo json_encode([
            'success' => false,
            'message' => 'Course code already exists.'
        ]);

        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO courses
        (
            course_code,
            course_name,
            description,
            status
        )
        VALUES
        (?, ?, ?, ?)
    ");

    $success = $stmt->execute([
        $course_code,
        $course_name,
        $description,
        $status
    ]);

    if ($success) {

        logActivity(
            $_SESSION['user_id'],
            $_SESSION['role'],
            'Created Course',
            "Created {$course_code} - {$course_name}"
        );
    }

    echo json_encode([
        'success' => $success,
        'message' => $success
            ? 'Course added successfully.'
            : 'Unable to save course.'
    ]);
} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
