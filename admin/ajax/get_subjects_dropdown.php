<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$search = trim($_GET['search'] ?? '');
$units  = $_GET['units'] ?? '';

$page = max(1, intval($_GET['page'] ?? 1));

$limit = 15;
$offset = ($page - 1) * $limit;

//=======================================
// COUNT QUERY
//=======================================

$countSql = "
    SELECT COUNT(*)
    FROM subjects
    WHERE status = 'active'
";

$countParams = [];

//=======================================
// SEARCH FILTER
//=======================================

if (!empty($search)) {

    $countSql .= "
        AND (
            subject_code LIKE ?
            OR subject_name LIKE ?
        )
    ";

    $countParams[] = "%{$search}%";
    $countParams[] = "%{$search}%";
}

//=======================================
// UNITS FILTER
//=======================================

if (!empty($units)) {

    $countSql .= "
        AND units = ?
    ";

    $countParams[] = $units;
}

$countStmt = $pdo->prepare($countSql);

$countStmt->execute($countParams);

$totalRecords = $countStmt->fetchColumn();

$totalPages = max(
    1,
    ceil($totalRecords / $limit)
);

//=======================================
// MAIN QUERY
//=======================================

$sql = "
    SELECT
        id,
        subject_code,
        subject_name,
        units

    FROM subjects

    WHERE status = 'active'
";

$params = [];

//=======================================
// SEARCH FILTER
//=======================================

if (!empty($search)) {

    $sql .= "
        AND (
            subject_code LIKE ?
            OR subject_name LIKE ?
        )
    ";

    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

//=======================================
// UNITS FILTER
//=======================================

if (!empty($units)) {

    $sql .= "
        AND units = ?
    ";

    $params[] = $units;
}

//=======================================
// PAGINATION
//=======================================

$sql .= "
    ORDER BY subject_code
    LIMIT $limit OFFSET $offset
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

//=======================================
// RESPONSE
//=======================================

echo json_encode([
    'subjects'      => $subjects,
    'current_page'  => $page,
    'total_pages'   => $totalPages,
    'total_records' => $totalRecords
]);
