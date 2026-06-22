<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$search    = trim($_GET['search'] ?? '');
$course_id = $_GET['course_id'] ?? '';
$page      = max(1, intval($_GET['page'] ?? 1));

$limit = 10;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if ($search !== '') {

    $where[] = "(
        s.student_number LIKE ?
        OR s.first_name LIKE ?
        OR s.last_name LIKE ?
    )";

    $searchTerm = "%{$search}%";

    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if ($course_id !== '') {

    $where[] = "s.course_id = ?";

    $params[] = $course_id;
}

$whereSql = '';

if ($where) {
    $whereSql = 'WHERE ' . implode(' AND ', $where);
}

$countSql = "
    SELECT COUNT(*)
    FROM students s
    $whereSql
";

$stmt = $pdo->prepare($countSql);
$stmt->execute($params);

$totalRecords = $stmt->fetchColumn();

$totalPages = ceil($totalRecords / $limit);

$sql = "
    SELECT
        s.*,
        c.course_code,
        c.course_name
    FROM students s
    LEFT JOIN courses c
        ON c.id = s.course_id

    $whereSql

    ORDER BY
        s.last_name,
        s.first_name

    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'students' => $students,
    'current_page' => $page,
    'total_pages' => $totalPages
]);
