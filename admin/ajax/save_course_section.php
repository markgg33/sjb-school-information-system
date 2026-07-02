<?php

require '../../includes/db.php';

header('Content-Type: application/json');

$id = $_POST['id'] ?? '';

$course_id = $_POST['course_id'];

$year_level = $_POST['year_level'];

$section_name = trim($_POST['section_name']);

$status = $_POST['status'];

$display_order = $_POST['display_order'];

$stmt = $pdo->prepare("
SELECT id
FROM course_sections
WHERE course_id = ?
AND year_level = ?
AND section_name = ?
AND id <> ?
");

$stmt->execute([
    $course_id,
    $year_level,
    $section_name,
    $id ?: 0
]);

if ($stmt->fetch()) {

    echo json_encode([
        "success" => false,
        "message" => "This section already exists for the selected year."
    ]);

    exit;
}

if ($id) {

    $stmt = $pdo->prepare("
    UPDATE course_sections
    SET
        year_level=?,
        section_name=?,
        status=?,
        display_order=?
    WHERE id=?
    ");

    $stmt->execute([

        $year_level,

        $section_name,

        $status,

        $display_order,

        $id

    ]);
} else {

    $stmt = $pdo->prepare("
    INSERT INTO course_sections(

        course_id,

        year_level,

        section_name,

        status,

        display_order

    )

    VALUES(?,?,?,?,?)
    ");

    $stmt->execute([

        $course_id,

        $year_level,

        $section_name,

        $status,

        $display_order

    ]);
}

echo json_encode([
    "success" => true
]);
