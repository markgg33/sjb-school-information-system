<?php

require_once '../../includes/db.php';
require_once '../../includes/activity_logger.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

$subject_code = trim($_POST['subject_code'] ?? '');
$subject_name = trim($_POST['subject_name'] ?? '');
$units = (int) ($_POST['units'] ?? 3);
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? 'active';

if (
    empty($subject_code) ||
    empty($subject_name)
) {
    echo json_encode([
        'success' => false,
        'message' => 'Required fields are missing.'
    ]);
    exit;
}

try {

    //=======================================
    // CHECK IF SUBJECT CODE ALREADY EXISTS
    //=======================================
    $stmt = $pdo->prepare("
    SELECT id
    FROM subjects
    WHERE subject_code = ?
    AND id != ?
");

    $stmt->execute([
        $subject_code,
        $id ?: 0
    ]);

    if ($stmt->fetch()) {

        echo json_encode([
            'success' => false,
            'message' => 'Subject code already exists.'
        ]);

        exit;
    }

    if ($id) {

        $stmt = $pdo->prepare("
            UPDATE subjects
            SET
                subject_code = ?,
                subject_name = ?,
                units = ?,
                description = ?,
                status = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $subject_code,
            $subject_name,
            $units,
            $description,
            $status,
            $id
        ]);

        logActivity(
            $_SESSION['user_id'],
            $_SESSION['role'],
            'Updated Subject',
            "Updated {$subject_code} - {$subject_name}",
            $id
        );

        echo json_encode([
            'success' => true,
            'message' => 'Subject updated successfully.'
        ]);
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO subjects (
                subject_code,
                subject_name,
                units,
                description,
                status
            )
            VALUES (
                ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $subject_code,
            $subject_name,
            $units,
            $description,
            $status
        ]);

        logActivity(
            $_SESSION['user_id'],
            $_SESSION['role'],
            'Created Subject',
            "Created {$subject_code} - {$subject_name}"
        );

        echo json_encode([
            'success' => true,
            'message' => 'Subject added successfully.'
        ]);
    }
} catch (PDOException $e) {

    if ($e->getCode() == 23000) {

        echo json_encode([
            'success' => false,
            'message' => 'Duplicate subject detected.'
        ]);
    } else {

        echo json_encode([
            'success' => false,
            'message' => 'An unexpected error occurred.'
        ]);
    }
}
