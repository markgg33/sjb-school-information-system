<?php

require_once '../../../includes/db.php';
require_once '../../../includes/grading_helper.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

try {

    $pdo->beginTransaction();

    $students = [];

    foreach ($data['scores'] as $row) {

        $stmt = $pdo->prepare("
        SELECT id
        FROM student_scores
        WHERE grading_component_id=?
        AND enrollment_subject_id=?
        ");

        $stmt->execute([
            $row['component_id'],
            $row['enrollment_subject_id']
        ]);

        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {

            $stmt = $pdo->prepare("
            UPDATE student_scores
            SET score=?
            WHERE id=?
            ");

            $stmt->execute([
                $row['score'],
                $existing['id']
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

                $row['component_id'],

                $row['enrollment_subject_id'],

                $row['score']

            ]);
        }

        $students[$row['enrollment_subject_id']] = true;
    }

    /*
|--------------------------------------------------------------------------
| COMPUTE PERIOD GRADES
|--------------------------------------------------------------------------
*/

    $period = $data['period'];

    foreach (array_keys($students) as $enrollmentSubjectId) {

        computePeriodGrade(
            $pdo,
            $data['faculty_subject_id'],
            $enrollmentSubjectId,
            $period
        );
    }

    $pdo->commit();

    echo json_encode([
        "success" => true
    ]);
} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
