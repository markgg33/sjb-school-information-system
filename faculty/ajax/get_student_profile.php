<?php

require_once '../../includes/db.php';

$enrollment_subject_id = intval($_REQUEST['enrollment_subject_id'] ?? 0);

if (!$enrollment_subject_id) {
    exit('<div class="alert alert-danger">Invalid student selected.</div>');
}

/*
|--------------------------------------------------------------------------
| STUDENT INFORMATION
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

s.student_number,

CONCAT(
    s.last_name,
    ', ',
    s.first_name,
    ' ',
    COALESCE(s.middle_name,'')
) AS student_name,

c.course_code,

sub.subject_code,
sub.subject_name,

e.school_year,
e.year_level,
e.trimester,

g.prelim_grade,
g.midterm_grade,
g.final_grade

FROM enrollment_subjects es

INNER JOIN enrollments e
ON e.id = es.enrollment_id

INNER JOIN students s
ON s.id = e.student_id

INNER JOIN courses c
ON c.id = e.course_id

INNER JOIN subjects sub
ON sub.id = es.subject_id

LEFT JOIN grades g
ON g.enrollment_subject_id = es.id

WHERE es.id = ?

");

$stmt->execute([$enrollment_subject_id]);

$student = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    exit('<div class="alert alert-warning">Student not found.</div>');
}

/*
|--------------------------------------------------------------------------
| Determine Faculty Subject
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

fs.id

FROM enrollment_subjects es

INNER JOIN enrollments e
ON e.id = es.enrollment_id

INNER JOIN faculty_subjects fs

ON fs.subject_id = es.subject_id

AND fs.course_id = e.course_id

AND fs.year_level = e.year_level

AND fs.school_year = e.school_year

AND fs.trimester = e.trimester

WHERE es.id = ?

LIMIT 1

");

$stmt->execute([
    $enrollment_subject_id
]);

$faculty_subject_id = $stmt->fetchColumn();

if (!$faculty_subject_id) {

    echo '

    <div class="alert alert-warning">

        No faculty assignment found for this student.

    </div>';

    exit;
}

/*
|--------------------------------------------------------------------------
| COMPONENT SCORES
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("

SELECT

gs.period,

gc.component_name,

gc.component_type,

gc.max_score,

gc.weight,

ss.score

FROM grading_schemes gs

INNER JOIN grading_components gc
ON gc.grading_scheme_id = gs.id

LEFT JOIN student_scores ss

ON ss.grading_component_id = gc.id

AND ss.enrollment_subject_id = ?

WHERE

gs.faculty_subject_id = ?

ORDER BY

FIELD(gs.period,'Prelim','Midterm','Finals'),

gc.display_order

");

$stmt->execute([

    $enrollment_subject_id,

    $faculty_subject_id

]);

$components = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">

    <div class="row">

        <div class="col-lg-4">

            <div class="dashboard-card h-100">

                <div class="text-center">

                    <i class="fa-solid fa-user-graduate fa-5x text-primary mb-3"></i>

                    <h4>

                        <?= htmlspecialchars($student['student_name']) ?>

                    </h4>

                    <div class="text-muted">

                        <?= htmlspecialchars($student['student_number']) ?>

                    </div>

                    <hr>

                    <div class="mb-2">

                        <strong>Course</strong><br>

                        <?= htmlspecialchars($student['course_code']) ?>

                    </div>

                    <div class="mb-2">

                        <strong>Subject</strong><br>

                        <?= htmlspecialchars($student['subject_code']) ?>

                        <br>

                        <?= htmlspecialchars($student['subject_name']) ?>

                    </div>

                    <div class="mb-2">

                        <strong>School Year</strong><br>

                        <?= htmlspecialchars($student['school_year']) ?>

                    </div>

                    <div>

                        Year <?= $student['year_level'] ?>

                        •

                        Trimester <?= $student['trimester'] ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="dashboard-card">

                <h5 class="mb-3">

                    Overall Grades

                </h5>

                <div class="alert alert-primary mt-4">

                    <strong>

                        Current Standing

                    </strong>

                    <br>

                    <?php

                    $latest = '--';

                    if ($student['final_grade'] !== null) {

                        $latest = number_format($student['final_grade'], 2);
                    } elseif ($student['midterm_grade'] !== null) {

                        $latest = number_format($student['midterm_grade'], 2);
                    } elseif ($student['prelim_grade'] !== null) {

                        $latest = number_format($student['prelim_grade'], 2);
                    }

                    ?>

                    Current Subject Grade:

                    <span class="fw-bold">

                        <?= $latest ?>

                    </span>

                </div>

                <div class="row text-center">

                    <div class="col">

                        <div class="border rounded p-3">

                            <div class="small text-muted">

                                Prelim

                            </div>

                            <h4>

                                <?= $student['prelim_grade'] !== null ? number_format($student['prelim_grade'], 2) : '--' ?>

                            </h4>

                        </div>

                    </div>

                    <div class="col">

                        <div class="border rounded p-3">

                            <div class="small text-muted">

                                Midterm

                            </div>

                            <h4>

                                <?= $student['midterm_grade'] !== null ? number_format($student['midterm_grade'], 2) : '--' ?>

                            </h4>

                        </div>

                    </div>

                    <div class="col">

                        <div class="border rounded p-3">

                            <div class="small text-muted">

                                Final

                            </div>

                            <h4>

                                <?= $student['final_grade'] !== null ? number_format($student['final_grade'], 2) : '--' ?>

                            </h4>

                        </div>

                    </div>

                </div>

                <hr>

                <h5 class="mb-3">

                    Component Scores

                </h5>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>Period</th>

                                <th>Component</th>

                                <th>Type</th>

                                <th>Weight</th>

                                <th>Score</th>

                                <th>%</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($components as $row): ?>

                                <?php

                                $score = $row['score'];

                                $percentage = '--';

                                if ($score !== null && $row['max_score'] > 0) {

                                    $percentage = number_format(

                                        ($score / $row['max_score']) * 100,

                                        2

                                    ) . '%';
                                }

                                ?>

                                <tr>

                                    <td>

                                        <span class="badge bg-primary">

                                            <?= htmlspecialchars($row['period']) ?>

                                        </span>

                                    </td>

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars($row['component_name']) ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= htmlspecialchars($row['component_type']) ?>

                                    </td>

                                    <td>

                                        <?= number_format($row['weight'], 2) ?>%

                                    </td>

                                    <td>

                                        <?= $score !== null ? $score : '--' ?>

                                        /

                                        <?= $row['max_score'] ?>

                                    </td>

                                    <td>

                                        <?= $percentage ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>