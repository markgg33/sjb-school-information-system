<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $enrollment_id = (int)($_POST['enrollment_id'] ?? 0);

    if (!$enrollment_id) {
        throw new Exception('Invalid enrollment selected.');
    }

    $pdo->beginTransaction();

    // Delete subjects first
    $stmt = $pdo->prepare("
        DELETE FROM enrollment_subjects
        WHERE enrollment_id = ?
    ");

    $stmt->execute([
        $enrollment_id
    ]);

    // Delete enrollment record
    $stmt = $pdo->prepare("
        DELETE FROM enrollments
        WHERE id = ?
    ");

    $stmt->execute([
        $enrollment_id
    ]);

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Enrollment deleted successfully.'
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
