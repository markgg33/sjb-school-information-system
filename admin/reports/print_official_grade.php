<?php

$data = json_decode(

    file_get_contents(

        "http://" . $_SERVER['HTTP_HOST'] .

            dirname($_SERVER['PHP_SELF']) .

            "/../ajax/reports/get_official_grade.php?enrollment_id=" .

            $_GET['enrollment_id']

    ),

    true

);

if (!$data) {
    exit("No records found.");
}

$header   = $data['header'];
$subjects = $data['subjects'];
$summary  = $data['summary'];

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Official Grade Report</title>

    <link rel="stylesheet"
      href="../../includes/reports/report.css">

</head>

<body>

    <?php require '../../includes/reports/report_header.php'; ?>

    <div class="report-title">

        OFFICIAL GRADE REPORT

    </div>

    <table class="info-table">

        <tr>

            <td width="150"><strong>Student No.</strong></td>

            <td>

                <?= htmlspecialchars($header['student_number']) ?>

            </td>

            <td width="150"><strong>School Year</strong></td>

            <td>

                <?= htmlspecialchars($header['school_year']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Student Name</strong></td>

            <td>

                <?= htmlspecialchars($header['student_name']) ?>

            </td>

            <td><strong>Trimester</strong></td>

            <td>

                <?= htmlspecialchars($header['trimester']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Course</strong></td>

            <td>

                <?= htmlspecialchars($header['course_code']) ?>

                -

                <?= htmlspecialchars($header['course_name']) ?>

            </td>

            <td><strong>Year Level</strong></td>

            <td>

                Year <?= $header['year_level'] ?>

            </td>

        </tr>

        <tr>

            <td><strong>Section</strong></td>

            <td colspan="3">

                <?= htmlspecialchars($header['section_name'] ?? '-') ?>

            </td>

        </tr>

    </table>

    <table class="student-table">

        <thead>

            <tr>

                <th width="45">

                    #

                </th>

                <th width="110">

                    Subject Code

                </th>

                <th>

                    Subject Description

                </th>

                <th width="170">

                    Instructor

                </th>

                <th width="80">

                    Prelim

                </th>

                <th width="80">

                    Midterm

                </th>

                <th width="80">

                    Final

                </th>

                <th width="90">

                    Remarks

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $no = 1;

            foreach ($subjects as $row):

            ?>

                <tr>

                    <td>

                        <?= $no++ ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['subject_code']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['subject_name']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['faculty_name'] ?? '-') ?>

                    </td>

                    <td>

                        <?= $row['prelim_grade'] !== null

                            ? number_format($row['prelim_grade'], 2)

                            : '' ?>

                    </td>

                    <td>

                        <?= $row['midterm_grade'] !== null

                            ? number_format($row['midterm_grade'], 2)

                            : '' ?>

                    </td>

                    <td>

                        <?= $row['final_grade'] !== null

                            ? number_format($row['final_grade'], 2)

                            : '' ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['remarks'] ?? '') ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <br>

    <table class="info-table">

        <tr>

            <td width="220">

                <strong>Total Subjects</strong>

            </td>

            <td>

                <?= $summary['subjects'] ?>

            </td>

            <td width="220">

                <strong>Passed</strong>

            </td>

            <td>

                <?= $summary['passed'] ?>

            </td>

        </tr>

        <tr>

            <td>

                <strong>Failed</strong>

            </td>

            <td>

                <?= $summary['failed'] ?>

            </td>

            <td>

                <strong>General Average</strong>

            </td>

            <td>

                <?=

                $summary['average'] !== null

                    ? number_format($summary['average'], 2)

                    : ''

                ?>

            </td>

        </tr>

    </table>

    <br><br>

    <div style="width:350px; float:right; text-align:center;">

        <br><br>

        _______________________________

        <br>

        Registrar

    </div>

    <div style="clear:both;"></div>

    <?php require '../../includes/reports/report_footer.php'; ?>

    <script>
        window.onload = function() {

            window.print();

        };
    </script>

</body>

</html>