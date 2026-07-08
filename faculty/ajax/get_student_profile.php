<?php

require_once '../../includes/db.php';

$enrollment_subject_id = (int)($_REQUEST['enrollment_subject_id'] ?? 0);

$faculty_subject_id = (int)($_REQUEST['faculty_subject_id'] ?? 0);

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
e.section_id,

cs.section_name,

s.gender,
s.email,
s.contact_number,
s.address,

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

LEFT JOIN course_sections cs
ON cs.id = e.section_id

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

                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">

                    <div class="me-4 text-center">

                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"

                            style="width:90px;height:90px;">

                            <i class="fa-solid fa-user-graduate fa-3x text-primary"></i>

                        </div>

                    </div>

                    <div>

                        <h3 class="mb-1 fw-bold">

                            <?= htmlspecialchars($student['student_name']) ?>

                        </h3>

                        <div class="text-muted">

                            <span class="badge bg-primary">

                                <?= htmlspecialchars($student['student_number']) ?>

                            </span>

                        </div>

                    </div>

                </div>

                <div class="row g-3">

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Course
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['course_code']) ?>
                        </div>

                    </div>

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Section
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['section_name'] ?? '--') ?>
                        </div>

                    </div>

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Year Level
                        </small>

                        <div class="fw-semibold">
                            <?= $student['year_level'] ?>
                        </div>

                    </div>

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Trimester
                        </small>

                        <div class="fw-semibold">
                            <?= $student['trimester'] ?>
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-uppercase text-secondary fw-semibold">
                            School Year
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['school_year']) ?>
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Subject
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['subject_code']) ?>
                            —
                            <?= htmlspecialchars($student['subject_name']) ?>
                        </div>

                    </div>

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Gender
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['gender'] ?? '--') ?>
                        </div>

                    </div>

                    <div class="col-6">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Contact Number
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['contact_number'] ?? '--') ?>
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Email Address
                        </small>

                        <div class="fw-semibold text-break">
                            <?= htmlspecialchars($student['email'] ?? '--') ?>
                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-uppercase text-secondary fw-semibold">
                            Address
                        </small>

                        <div class="fw-semibold">
                            <?= htmlspecialchars($student['address'] ?? '--') ?>
                        </div>

                    </div>

                </div>

            </div> <!-- dashboard-card -->

        </div> <!-- col-lg-4 -->

        <div class="col-lg-8">

            <div class="dashboard-card">

                <h5 class="mb-3">

                    Overall Grades

                </h5>

                <div class="alert alert-primary mt-4 mb-3">

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

                    <div class="alert alert-primary d-flex justify-content-between align-items-center">

                        <div>

                            <strong>

                                Current Subject Grade

                            </strong>

                        </div>

                        <h3 class="mb-0">

                            <?= $latest ?>

                        </h3>

                    </div>

                </div>

                <div class="row g-3 mt-2">

                    <div class="col">

                        <div class="dashboard-card text-center h-100">

                            <div class="small text-muted">

                                Prelim

                            </div>

                            <h4>

                                <?= $student['prelim_grade'] !== null ? number_format($student['prelim_grade'], 2) : '--' ?>

                            </h4>

                        </div>

                    </div>

                    <div class="col">

                        <div class="dashboard-card text-center h-100">

                            <div class="small text-muted">

                                Midterm

                            </div>

                            <h4>

                                <?= $student['midterm_grade'] !== null ? number_format($student['midterm_grade'], 2) : '--' ?>

                            </h4>

                        </div>

                    </div>

                    <div class="col">

                        <div class="dashboard-card text-center h-100">

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

                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-light">

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