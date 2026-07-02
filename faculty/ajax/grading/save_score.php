<?php

require_once '../../../includes/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$component = $data['component_id'];
$enrollment = $data['enrollment_subject_id'];
$score = $data['score'];

$stmt = $pdo->prepare("
SELECT id
FROM student_scores
WHERE grading_component_id=?
AND enrollment_subject_id=?
");

$stmt->execute([
    $component,
    $enrollment
]);

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {

    $stmt = $pdo->prepare("
    UPDATE student_scores
    SET score=?
    WHERE id=?
    ");

    $stmt->execute([
        $score,
        $row['id']
    ]);
} else {

    $stmt = $pdo->prepare("
    INSERT INTO student_scores(

        grading_component_id,

        enrollment_subject_id,

        score

    )

    VALUES(?,?,?)

    ");

    $stmt->execute([
        $component,
        $enrollment,
        $score
    ]);
}

echo json_encode([
    "success" => true
]);
