<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$course_id = $_GET['course_id'] ?? '';

$page = max(1, intval($_GET['page'] ?? 1));

$limit = 10;
$offset = ($page - 1) * $limit;

$where = ["1=1"];
$params = [];

// SEARCH
if ($search !== '') {

    $where[] = "(
        f.employee_number LIKE ?
        OR f.first_name LIKE ?
        OR f.last_name LIKE ?
        OR f.email LIKE ?
    )";

    for ($i = 0; $i < 4; $i++) {
        $params[] = "%{$search}%";
    }
}

// STATUS
if ($status !== '') {

    $where[] = "f.status = ?";
    $params[] = $status;
}

// COURSE FILTER
if ($course_id !== '') {

    $where[] = "
        EXISTS (
            SELECT 1
            FROM faculty_courses fc
            WHERE fc.faculty_id = f.id
            AND fc.course_id = ?
        )
    ";

    $params[] = $course_id;
}

$whereSql = implode(' AND ', $where);

// COUNT
$countStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM faculty f
    WHERE $whereSql
");

$countStmt->execute($params);

$totalRecords = $countStmt->fetchColumn();

$totalPages = max(
    1,
    ceil($totalRecords / $limit)
);

// MAIN QUERY
$stmt = $pdo->prepare("
SELECT
    f.*
FROM faculty f
WHERE $whereSql
ORDER BY f.last_name, f.first_name
LIMIT $limit OFFSET $offset
");

$stmt->execute($params);

$faculty = $stmt->fetchAll(PDO::FETCH_ASSOC);

// LOAD COURSES
foreach ($faculty as &$row) {

    $courseStmt = $pdo->prepare("
        SELECT
            c.course_code,
            c.course_name
        FROM faculty_courses fc
        INNER JOIN courses c
            ON c.id = fc.course_id
        WHERE fc.faculty_id = ?
    ");

    $courseStmt->execute([
        $row['id']
    ]);

    $row['courses'] =
        $courseStmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode([
    'faculty' => $faculty,
    'current_page' => $page,
    'total_pages' => $totalPages
]);
