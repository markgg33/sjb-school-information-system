<?php

function computePeriodGrade(
    PDO $pdo,
    int $facultySubjectId,
    int $enrollmentSubjectId,
    string $period
) {
    /*
    |--------------------------------------------------------------------------
    | Load all grading components and scores
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT

gc.max_score,
gc.weight,
ss.score

FROM grading_components gc

INNER JOIN grading_schemes gs
ON gs.id = gc.grading_scheme_id

LEFT JOIN student_scores ss
ON ss.grading_component_id = gc.id
AND ss.enrollment_subject_id = ?

WHERE

gs.faculty_subject_id = ?
AND gs.period = ?

ORDER BY gc.display_order
    ");

    $stmt->execute([
        $enrollmentSubjectId,
        $facultySubjectId,
        $period
    ]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        return;
    }

    $periodGrade = 0;

    foreach ($rows as $row) {

        $score = $row['score'] ?? 0;

        if ($row['max_score'] <= 0) {
            continue;
        }

        $periodGrade +=
            ($score / $row['max_score'])
            * $row['weight'];
    }

    $periodGrade = round($periodGrade, 2);

    /*
    |--------------------------------------------------------------------------
    | Save to grades table
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT id
        FROM grades
        WHERE enrollment_subject_id = ?
    ");

    $stmt->execute([
        $enrollmentSubjectId
    ]);

    $grade = $stmt->fetch(PDO::FETCH_ASSOC);

    $column = match ($period) {

        'Prelim' => 'prelim_grade',

        'Midterm' => 'midterm_grade',

        'Finals' => 'final_grade',

        default => 'prelim_grade'
    };

    if ($grade) {

        $stmt = $pdo->prepare("
            UPDATE grades
            SET {$column}=?
            WHERE enrollment_subject_id=?
        ");

        $stmt->execute([
            $periodGrade,
            $enrollmentSubjectId
        ]);
    } else {

        $stmt = $pdo->prepare("
            INSERT INTO grades
            (
                enrollment_subject_id,
                {$column}
            )
            VALUES
            (?,?)
        ");

        $stmt->execute([
            $enrollmentSubjectId,
            $periodGrade
        ]);
    }

    return $periodGrade;
}
