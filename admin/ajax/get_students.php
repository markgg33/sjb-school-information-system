<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$type = $_GET['student_type'] ?? '';
$course_id = $_GET['course_id'] ?? '';

$page = max(1, intval($_GET['page'] ?? 1));

$limit = 10;
$offset = ($page - 1) * $limit;

$where = ["1=1"];
$params = [];

// Search
if ($search !== '') {

    $where[] = "(
        s.student_number LIKE ?
        OR s.first_name LIKE ?
        OR s.last_name LIKE ?
        OR s.email LIKE ?
    )";

    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Status
if ($status !== '') {

    $where[] = "s.status = ?";
    $params[] = $status;
}

// Student Type
if ($type !== '') {

    $where[] = "s.student_type = ?";
    $params[] = $type;
}

// Course
if ($course_id !== '') {

    $where[] = "s.course_id = ?";
    $params[] = $course_id;
}

$whereSql = implode(' AND ', $where);

// COUNT
$countStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM students s
    WHERE $whereSql
");

$countStmt->execute($params);

$totalRecords = $countStmt->fetchColumn();

$totalPages = max(
    1,
    ceil($totalRecords / $limit)
);

// MAIN QUERY
$sql = "
SELECT

    s.*,

    c.course_code

FROM students s

LEFT JOIN courses c
    ON c.id = s.course_id

WHERE $whereSql

ORDER BY s.last_name,
         s.first_name

LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

echo json_encode([
    'students'      => $stmt->fetchAll(PDO::FETCH_ASSOC),
    'current_page'  => $page,
    'total_pages'   => $totalPages
]);
