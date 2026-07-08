<?php

require_once '../../../includes/db.php';

$faculty_subject_id = $_REQUEST['faculty_subject_id'] ?? 0;
$period = $_REQUEST['period'] ?? 'Prelim';

/*
|--------------------------------------------------------------------------
| SCHEME
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM grading_schemes
WHERE faculty_subject_id = ?
AND period = ?
");

$stmt->execute([
    $faculty_subject_id,
    $period
]);

$scheme = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$scheme) {

?>

    <div class="text-center py-5 text-muted">

        Create grading components first.

    </div>

<?php

    exit;
}

$scheme_id = $scheme['id'];

/*
|--------------------------------------------------------------------------
| COMPONENTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM grading_components
WHERE grading_scheme_id=?
ORDER BY display_order
");

$stmt->execute([
    $scheme_id
]);

$components = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| STUDENTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

    es.id AS enrollment_subject_id,

    s.student_number,

    CONCAT(

        s.last_name,

        ', ',

        s.first_name

    ) AS student_name

FROM faculty_subjects fs

INNER JOIN enrollments e

    ON e.course_id = fs.course_id
    AND e.year_level = fs.year_level
    AND e.school_year = fs.school_year
    AND e.trimester = fs.trimester

    AND
    (
        (fs.section_id IS NULL AND e.section_id IS NULL)

        OR

        (fs.section_id IS NOT NULL
            AND e.section_id = fs.section_id)
    )

INNER JOIN students s

    ON s.id = e.student_id

INNER JOIN enrollment_subjects es

    ON es.enrollment_id = e.id
    AND es.subject_id = fs.subject_id

WHERE

    fs.id = ?

ORDER BY

    s.last_name,
    s.first_name

");

$stmt->execute([
    $faculty_subject_id
]);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="table-responsive">

    <table class="table table-bordered align-middle">

        <thead class="table-light">

            <tr>

                <th class="sticky-column">

                    Student

                </th>

                <?php foreach ($components as $component): ?>

                    <th class="text-center">

                        <div class="fw-semibold">

                            <?= htmlspecialchars($component['component_name']) ?>

                        </div>

                        <small class="text-muted">

                            <?= htmlspecialchars($component['component_type']) ?>

                        </small>

                        <br>

                        <span class="badge bg-secondary mt-1">

                            <?= $component['max_score'] ?>

                        </span>

                    </th>

                <?php endforeach; ?>

                <th class="text-center">

                    Period Grade

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $stmtScore = $pdo->prepare("
SELECT score
FROM student_scores
WHERE grading_component_id=?
AND enrollment_subject_id=?
");

            $stmtGrade = $pdo->prepare("
SELECT
    prelim_grade,
    midterm_grade,
    final_grade
FROM grades
WHERE enrollment_subject_id=?
");

            ?>

            <?php foreach ($students as $student): ?>

                <?php

                $stmtGrade->execute([
                    $student['enrollment_subject_id']
                ]);

                $grade = $stmtGrade->fetch(PDO::FETCH_ASSOC);

                $periodGrade = null;

                if ($grade) {

                    switch ($period) {

                        case 'Prelim':
                            $periodGrade = $grade['prelim_grade'];
                            break;

                        case 'Midterm':
                            $periodGrade = $grade['midterm_grade'];
                            break;

                        case 'Finals':
                            $periodGrade = $grade['final_grade'];
                            break;
                    }
                }

                ?>

                <tr>

                    <td>

                        <strong>

                            <?= htmlspecialchars($student['student_name']) ?>

                        </strong>

                        <br>

                        <small class="text-muted">

                            <?= htmlspecialchars($student['student_number']) ?>

                        </small>

                    </td>

                    <?php foreach ($components as $component): ?>

                        <td>

                            <?php

                            $stmtScore->execute([
                                $component['id'],
                                $student['enrollment_subject_id']
                            ]);

                            $currentScore = $stmtScore->fetchColumn();

                            $score = ($currentScore !== false)
                                ? $currentScore
                                : '';

                            ?>


                            <input
                                type="number"
                                class="form-control form-control-sm text-center gradeInput"

                                value="<?= htmlspecialchars($score) ?>"

                                min="0"
                                max="<?= $component['max_score'] ?>"

                                data-component="<?= $component['id'] ?>"
                                data-enrollment="<?= $student['enrollment_subject_id'] ?>">

                        </td>

                    <?php endforeach; ?>



                    <td

                        class="text-center fw-bold periodGrade">

                        <?= $periodGrade !== null && $periodGrade !== '' ? number_format($periodGrade, 2) : '--' ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <div class="text-end mt-3">

        <button
            class="btn btn-success"
            id="saveScoresBtn">

            <i class="fa-solid fa-floppy-disk"></i>

            Save Scores

        </button>

    </div>

</div>