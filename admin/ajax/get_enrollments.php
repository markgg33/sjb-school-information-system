<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

$page = max(1, (int)($_GET['page'] ?? 1));

$limit = 10;

$offset = ($page - 1) * $limit;

$search    = trim($_GET['search'] ?? '');
$course_id = trim($_GET['course_id'] ?? '');
$status    = trim($_GET['status'] ?? '');

$where = [];

$params = [];

$where[] = "EXISTS (
    SELECT 1
    FROM enrollments e
    WHERE e.student_id = s.id
)";

//====================================
// SEARCH FILTER
//====================================

if ($search !== '') {

    $where[] = "(
        s.student_number LIKE ?
        OR s.first_name LIKE ?
        OR s.last_name LIKE ?
    )";

    $term = "%{$search}%";

    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

if ($course_id !== '') {

    $where[] = "s.course_id = ?";

    $params[] = $course_id;
}

if ($status !== '') {

    $where[] = "s.status = ?";

    $params[] = $status;
}

$whereSql = '';

if (!empty($where)) {

    $whereSql =
        'WHERE ' .
        implode(' AND ', $where);
}

//====================================
// TOTAL
//====================================

/*$totalStmt = $pdo->query("
    SELECT COUNT(*)
    FROM enrollments
");*/

$totalStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM students s
    $whereSql
");

$totalStmt->execute($params);

$totalRecords = $totalStmt->fetchColumn();

$totalPages = ceil(
    $totalRecords / $limit
);
//====================================
// DATA
//====================================

/*$stmt = $pdo->prepare("
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
");*/

$stmt = $pdo->prepare("
SELECT
    s.id,
    s.student_number,
    s.first_name,
    s.last_name,
    s.status,

    c.course_code,
    c.course_name,
    cs.section_name,

    (
        SELECT COUNT(*)
        FROM enrollments e
        WHERE e.student_id = s.id
    ) AS enrollment_count,

(
    SELECT CONCAT(
        CASE e.year_level
            WHEN 1 THEN '1st Year'
            WHEN 2 THEN '2nd Year'
            WHEN 3 THEN '3rd Year'
            WHEN 4 THEN '4th Year'
            ELSE CONCAT(e.year_level, 'th Year')
        END,
        ' - ',
        CASE e.trimester
            WHEN 1 THEN '1st Trimester'
            WHEN 2 THEN '2nd Trimester'
            WHEN 3 THEN '3rd Trimester'
        END
    )
    FROM enrollments e
    WHERE e.student_id = s.id
    ORDER BY e.id DESC
    LIMIT 1
) AS latest_enrollment

FROM students s

LEFT JOIN courses c
    ON c.id = s.course_id

LEFT JOIN enrollments le
    ON le.id =
    (
        SELECT e2.id
        FROM enrollments e2
        WHERE e2.student_id = s.id
        ORDER BY e2.id DESC
        LIMIT 1
    )

LEFT JOIN course_sections cs
    ON cs.id = le.section_id

$whereSql

ORDER BY
    s.last_name,
    s.first_name

LIMIT $limit OFFSET $offset
");

$stmt->execute($params);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    //CHANGED FROM ENROLLMENTS TO STUDENTS
    'students' => $rows,
    'current_page' => $page,
    'total_pages' => $totalPages
]);
