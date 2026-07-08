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

f.first_name,
f.last_name,

st.student_number,
st.first_name AS student_first,
st.last_name AS student_last,
st.middle_name,
st.gender,

e.status

FROM faculty_subjects fs

INNER JOIN subjects s
ON s.id=fs.subject_id

INNER JOIN courses c
ON c.id=fs.course_id

LEFT JOIN course_sections sec
ON sec.id=fs.section_id

INNER JOIN faculty f
ON f.id=fs.faculty_id

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

WHERE fs.id=?

ORDER BY

st.last_name,
st.first_name

");

$stmt->execute([$faculty_subject_id]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    exit("No students found.");
}

$info = $rows[0];

logActivity(

    $_SESSION['user_id'],

    $_SESSION['role'],

    'Printed Class List',

    $info['subject_code'] . ' - ' . $info['subject_name'],

    $faculty_subject_id

);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Class List</title>

    <link rel="stylesheet"
        href="../../includes/reports/report.css">

</head>

<body>

    <?php require '../../includes/reports/report_header.php';; ?>

    <div class="report-title">

        CLASS LIST

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

                <?= htmlspecialchars($info['first_name'] . ' ' . $info['last_name']) ?>

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

                <th width="50">#</th>

                <th width="140">Student No.</th>

                <th>Student Name</th>

                <th width="80">Gender</th>

                <th width="120">Status</th>

                <th width="180">Signature</th>

            </tr>

        </thead>

        <tbody>

            <?php

            $i = 1;

            foreach ($rows as $row):

            ?>

                <tr>

                    <td><?= $i++ ?></td>

                    <td><?= htmlspecialchars($row['student_number']) ?></td>

                    <td>

                        <?= htmlspecialchars(

                            $row['last_name'] . ', ' . $row['student_first']

                        ) ?>

                    </td>

                    <td><?= ucfirst($row['gender']) ?></td>

                    <td><?= ucfirst($row['status']) ?></td>

                    <td></td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <?php require 'includes/report_footer.php'; ?>

    <script>
        window.onload = function() {

            window.print();

        }
    </script>

</body>

</html>