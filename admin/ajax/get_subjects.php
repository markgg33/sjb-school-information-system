<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';

header('Content-Type: application/json');

$courseId  = $_GET['course_id'] ?? '';
$yearLevel = $_GET['year_level'] ?? '';
$trimester = $_GET['trimester'] ?? '';
$search    = trim($_GET['search'] ?? '');

$page = max(1, intval($_GET['page'] ?? 1));

$limit = 10;
$offset = ($page - 1) * $limit;

//=======================================
// MAIN QUERY
//=======================================

$sql = "
SELECT DISTINCT
    s.*,

    (
        SELECT COUNT(*)
        FROM curriculum c2
        WHERE c2.subject_id = s.id
    ) AS curriculum_count

FROM subjects s

LEFT JOIN curriculum c
    ON c.subject_id = s.id

WHERE 1=1
";

//=======================================
// COUNT QUERY
//=======================================

$countSql = "
SELECT COUNT(DISTINCT s.id)

FROM subjects s

LEFT JOIN curriculum c
    ON c.subject_id = s.id

WHERE 1=1
";

$params = [];
$countParams = [];

//=======================================
// COURSE FILTER
//=======================================

if (!empty($courseId)) {

    $sql .= " AND c.course_id = ?";
    $countSql .= " AND c.course_id = ?";

    $params[] = $courseId;
    $countParams[] = $courseId;
}

//=======================================
// YEAR LEVEL FILTER
//=======================================

if (!empty($yearLevel)) {

    $sql .= " AND c.year_level = ?";
    $countSql .= " AND c.year_level = ?";

    $params[] = $yearLevel;
    $countParams[] = $yearLevel;
}

//=======================================
// TRIMESTER FILTER
//=======================================

if (!empty($trimester)) {

    $sql .= " AND c.trimester = ?";
    $countSql .= " AND c.trimester = ?";

    $params[] = $trimester;
    $countParams[] = $trimester;
}

//=======================================
// SEARCH FILTER
//=======================================

if (!empty($search)) {

    $sql .= "
        AND (
            s.subject_code LIKE ?
            OR s.subject_name LIKE ?
        )
    ";

    $countSql .= "
        AND (
            s.subject_code LIKE ?
            OR s.subject_name LIKE ?
        )
    ";

    $params[] = "%{$search}%";
    $params[] = "%{$search}%";

    $countParams[] = "%{$search}%";
    $countParams[] = "%{$search}%";
}

//=======================================
// TOTAL RECORDS
//=======================================

$countStmt = $pdo->prepare($countSql);

$countStmt->execute($countParams);

$totalRecords = $countStmt->fetchColumn();

$totalPages = max(
    1,
    ceil($totalRecords / $limit)
);

//=======================================
// PAGINATION
//=======================================

$sql .= "
ORDER BY s.subject_code ASC
LIMIT $limit OFFSET $offset
";

//=======================================
// FETCH DATA
//=======================================

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
