<?php

require_once '../../includes/db.php';
require_once '../../includes/sessions.php';
require_once '../../includes/activity_logger.php';

$faculty_subject_id = (int)($_GET['faculty_subject_id'] ?? 0);

$stmt = $pdo->prepare("

SELECT

fs.id,

fs.subject_id,
fs.course_id,
fs.year_level,
fs.section_id,
fs.school_year,
fs.trimester,

s.subject_code,
s.subject_name,

c.course_code,
c.course_name,

sec.section_name,

CONCAT(
    f.first_name,
    ' ',
    f.last_name
) AS faculty_name,

st.student_number,

CONCAT(
    st.last_name,
    ', ',
    st.first_name,
    IF(
        st.middle_name IS NULL,
        '',
        CONCAT(' ',LEFT(st.middle_name,1),'.')
    )
) AS student_name,

g.prelim_grade,
g.midterm_grade,
g.final_grade

FROM faculty_subjects fs

INNER JOIN subjects s
ON s.id=fs.subject_id

INNER JOIN courses c
ON c.id=fs.course_id

INNER JOIN faculty f
ON f.id=fs.faculty_id

LEFT JOIN course_sections sec
ON sec.id=fs.section_id

INNER JOIN enrollments e

ON e.course_id=fs.course_id
AND e.year_level=fs.year_level
AND e.school_year=fs.school_year
AND e.trimester=fs.trimester

AND(

(fs.section_id IS NULL AND e.section_id IS NULL)

OR

e.section_id=fs.section_id

)

INNER JOIN students st
ON st.id=e.student_id

INNER JOIN enrollment_subjects es

ON es.enrollment_id=e.id
AND es.subject_id=fs.subject_id

LEFT JOIN grades g
ON g.enrollment_subject_id=es.id

WHERE fs.id=?

ORDER BY

st.last_name,
st.first_name

");


$stmt->execute([$faculty_subject_id]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {

    exit("No records found.");
}

$info = $rows[0];

logActivity(

    $_SESSION['user_id'],

    $_SESSION['role'],

    'Printed Final Grade Report',

    $info['subject_code'] . ' - ' . $info['subject_name'],

    $faculty_subject_id

);

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Final Grade Report</title>

    <link rel="stylesheet"
        href="../../includes/reports/report.css">

</head>

<body>

    <?php require '../../includes/reports/report_header.php'; ?>

    <div class="report-title">

        FINAL GRADE REPORT

    </div>

    <table class="info-table">

        <tr>

            <td><strong>Subject</strong></td>

            <td>

                <?= htmlspecialchars($info['subject_code']) ?>

                -

                <?= htmlspecialchars($info['subject_name']) ?>

            </td>

            <td><strong>Course</strong></td>

            <td>

                <?= htmlspecialchars($info['course_code']) ?>

                -

                <?= htmlspecialchars($info['course_name']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Faculty</strong></td>

            <td>

                <?= htmlspecialchars($info['faculty_name']) ?>

            </td>

            <td><strong>School Year</strong></td>

            <td>

                <?= htmlspecialchars($info['school_year']) ?>

            </td>

        </tr>

        <tr>

            <td><strong>Year Level</strong></td>

            <td>

                Year <?= $info['year_level'] ?>

            </td>

            <td><strong>Trimester</strong></td>

            <td>

                <?= $info['trimester'] ?>

            </td>

        </tr>

        <tr>

            <td><strong>Section</strong></td>

            <td colspan="3">

                <?= htmlspecialchars($info['section_name'] ?? '-') ?>

            </td>

        </tr>

    </table>

    <table class="student-table">

        <thead>

            <tr>

                <th width="45">#</th>

                <th width="140">

                    Student No.

                </th>

                <th>

                    Student Name

                </th>

                <th width="90">

                    Prelim

                </th>

                <th width="90">

                    Midterm

                </th>

                <th width="90">

                    Final

                </th>

                <th width="120">

                    Remarks

                </th>

            </tr>

        </thead>

        <tbody>

            <?php

            $no = 1;

            foreach ($rows as $row):

                $remarks = '';

                if ($row['final_grade'] !== null) {

                    $remarks = $row['final_grade'] >= 75
                        ? 'PASSED'
                        : 'FAILED';
                }

            ?>

                <tr>

                    <td>

                        <?= $no++ ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['student_number']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($row['student_name']) ?>

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

                        <?= $remarks ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <br><br>

    <?php require '../../includes/reports/report_footer.php'; ?>

    <script>
        window.onload = function() {

            window.print();

        };
    </script>

</body>

</html>