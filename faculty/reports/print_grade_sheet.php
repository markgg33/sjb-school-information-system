<?php

require_once '../../includes/sessions.php';
require_once '../../includes/activity_logger.php';

$data = json_decode(

    file_get_contents(

        "http://" . $_SERVER['HTTP_HOST'] .

            dirname($_SERVER['PHP_SELF']) .

            "/../ajax/reports/get_grade_sheet.php?faculty_subject_id=" . $_GET['faculty_subject_id'] .

            "&period=" . urlencode($_GET['period'] ?? 'Prelim')

    ),

    true

);

$header = $data['header'];

$components = $data['components'];

$students = $data['students'];

logActivity(

    $_SESSION['user_id'],

    $_SESSION['role'],

    'Printed Grade Sheet',

    $header['subject_code']
        . ' - '
        . ($_GET['period'] ?? 'Prelim'),

    $_GET['faculty_subject_id']

);

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Faculty Grade Sheet</title>

    <link rel="stylesheet"
        href="../../includes/reports/report.css">

</head>

<body>

    <?php require '../../includes/reports/report_header.php'; ?>

    <div class="report-title">

        FACULTY GRADE SHEET

    </div>

    <table class="info-table">

        <tr>

            <td><strong>Subject</strong></td>

            <td>

                <?= htmlspecialchars($header['subject_code']) ?>

                -

                <?= htmlspecialchars($header['subject_name']) ?>

            </td>

            <td><strong>Course</strong></td>

            <td>

                <?= htmlspecialchars($header['course_code']) ?>

                -

                <?= htmlspecialchars($header['course_name']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Faculty</strong></td>

            <td>

                <?= htmlspecialchars($header['faculty_name']) ?>

            </td>

            <td><strong>School Year</strong></td>

            <td>

                <?= htmlspecialchars($header['school_year']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Year Level</strong></td>

            <td>

                Year <?= $header['year_level'] ?>

            </td>

            <td><strong>Trimester</strong></td>

            <td>

                <?= $header['trimester'] ?>

            </td>

        </tr>

        <tr>

            <td><strong>Section</strong></td>

            <td>

                <?= htmlspecialchars($header['section_name'] ?? '-') ?>

            </td>

            <td><strong>Period</strong></td>

            <td>

                <?= htmlspecialchars($_GET['period'] ?? 'Prelim') ?>

            </td>

        </tr>

    </table>

    <table class="student-table">

        <thead>

            <tr>

                <th width="45">#</th>

                <th width="130">

                    Student No.

                </th>

                <th>Student Name</th>

                <?php foreach ($components as $component) { ?>

                    <th>

                        <?= htmlspecialchars($component['component_name']) ?>

                        <br>

                        <small>

                            <?= $component['max_score'] ?> pts

                        </small>

                    </th>

                <?php } ?>

                <th style="width:90px">

                    <?= htmlspecialchars($_GET['period'] ?? 'Prelim') ?>

                    Grade

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $no = 1;

            foreach ($students as $student) {

            ?>

                <tr>

                    <td>

                        <?= $no++ ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($student['student_number']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($student['student_name']) ?>

                    </td>

                    <?php foreach ($student['scores'] as $score) { ?>

                        <td>

                            <?= htmlspecialchars($score) ?>

                        </td>

                    <?php } ?>

                    <td>

                        <?=

                        $student['period_grade'] !== null

                            ?

                            number_format($student['period_grade'], 2)

                            :

                            ''

                        ?>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

    <br><br>

    <?php require '../../includes/reports/report_footer.php'; ?>


    <script>
        window.onload = function() {

            window.print();

        }
    </script>

</body>

</html>