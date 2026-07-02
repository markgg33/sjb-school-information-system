<?php

require '../../includes/db.php';

$course_id = intval($_GET['course_id'] ?? 0);

$stmt = $pdo->prepare("
SELECT *
FROM course_sections
WHERE course_id=?
ORDER BY
year_level,
display_order,
section_name
");

$stmt->execute([
    $course_id
]);

$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($sections as $row) {

    $data[$row['year_level']][] = $row;
}

echo json_encode($data);
