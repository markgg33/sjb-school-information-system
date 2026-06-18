<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $student_id = $_POST['student_id'] ?? 0;

    $stmt = $pdo->prepare("
        SELECT user_id
        FROM students
        WHERE id = ?
    ");

    $stmt->execute([$student_id]);

    $user_id = $stmt->fetchColumn();

    if (!$user_id) {
        throw new Exception('Student not found.');
    }

    $newPassword = 'Student123!';

    $hashedPassword = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );

    $stmt = $pdo->prepare("
        UPDATE users
        SET password = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $hashedPassword,
        $user_id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Password reset successfully.'
    ]);
} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
