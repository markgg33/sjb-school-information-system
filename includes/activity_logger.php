<?php

require_once __DIR__ . '/db.php';

function logActivity(
    int $userId,
    string $role,
    string $activity,
    string $description = '',
    ?int $referenceId = null
): void {

    global $pdo;

    $stmt = $pdo->prepare("
        INSERT INTO activity_logs
        (
            user_id,
            role,
            activity,
            description,
            reference_id
        )
        VALUES
        (
            ?, ?, ?, ?, ?
        )
    ");

    $stmt->execute([
        $userId,
        $role,
        $activity,
        $description,
        $referenceId
    ]);
}
