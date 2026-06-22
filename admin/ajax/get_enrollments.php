<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$page = max(1, (int)($_GET['page'] ?? 1));

$limit = 10;

$offset = ($page - 1) * $limit;

$where = [];

$params = [];

//====================================
// TOTAL
//====================================

$totalStmt = $pdo->query("
    SELECT COUNT(*)
    FROM enrollments
");

$totalRecords = $totalStmt->fetchColumn();

$totalPages = ceil(
    $totalRecords / $limit
);

//====================================
// DATA
//====================================

$stmt = $pdo->prepare("
    SELECT
        e.*,

        s.student_number,
        s.first_name,
        s.last_name,

        c.course_code,
        c.course_name

    FROM enrollments e

    INNER JOIN students s
        ON s.id = e.student_id

    INNER JOIN courses c
        ON c.id = e.course_id

    ORDER BY e.id DESC

    LIMIT $limit OFFSET $offset
");

$stmt->execute();

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'enrollments' => $rows,
    'current_page' => $page,
    'total_pages' => $totalPages
]);
