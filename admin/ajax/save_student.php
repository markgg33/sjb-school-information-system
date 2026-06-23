<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $pdo->beginTransaction();

    $id = $_POST['id'] ?? '';

    $student_number = trim($_POST['student_number'] ?? '');
    $student_number = $student_number !== '' ? $student_number : null;

    $email = trim($_POST['email']);

    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name'] ?? '');
    $middle_name = $middle_name !== '' ? $middle_name : null;

    $last_name = trim($_POST['last_name']);

    $gender = $_POST['gender'];

    $birth_date = $_POST['birth_date'] ?? '';
    $birth_date = $birth_date !== '' ? $birth_date : null;

    $contact_number = trim($_POST['contact_number'] ?? '');
    $contact_number = $contact_number !== '' ? $contact_number : null;

    $address = trim($_POST['address'] ?? '');
    $address = $address !== '' ? $address : null;

    $student_type = $_POST['student_type'];
    $status = $_POST['status'] ?? 'active';
    $course_id = $_POST['course_id'];

    //============================================
    // CHECK STUDENT NUMBER
    //============================================

    if ($student_number !== null) {

        if (!empty($id)) {

            $check = $pdo->prepare("
            SELECT id
            FROM students
            WHERE student_number = ?
            AND id <> ?
        ");

            $check->execute([
                $student_number,
                $id
            ]);
        } else {

            $check = $pdo->prepare("
            SELECT id
            FROM students
            WHERE student_number = ?
        ");

            $check->execute([
                $student_number
            ]);
        }

        if ($check->fetch()) {

            throw new Exception(
                "Student number already exists."
            );
        }
    }

    //============================================
    // CHECK EMAIL
    //============================================

    if (!empty($id)) {

        $checkEmail = $pdo->prepare("
        SELECT s.id
        FROM students s
        WHERE s.email = ?
        AND s.id <> ?
    ");

        $checkEmail->execute([
            $email,
            $id
        ]);
    } else {

        $checkEmail = $pdo->prepare("
        SELECT id
        FROM users
        WHERE email = ?
    ");

        $checkEmail->execute([
            $email
        ]);
    }

    if ($checkEmail->fetch()) {

        throw new Exception(
            "Email address is already in use."
        );
    }

    //================================================
    // UPDATE
    //================================================

    if (!empty($id)) {

        $stmt = $pdo->prepare("
            UPDATE students
SET
    student_number=?,
    email=?,
    first_name=?,
    middle_name=?,
    last_name=?,
    gender=?,
    birth_date=?,
    contact_number=?,
    address=?,
    student_type=?,
    status=?,
    course_id=?
WHERE id=?
        ");

        $stmt->execute([
            $student_number,
            $email,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $birth_date,
            $contact_number,
            $address,
            $student_type,
            $status,
            $course_id,
            $id
        ]);

        $student = $pdo->prepare("
            SELECT user_id
            FROM students
            WHERE id = ?
        ");

        $student->execute([$id]);

        $user_id = $student->fetchColumn();

        $pdo->prepare("
    UPDATE users
    SET
        email = ?,
        status = ?
    WHERE id = ?
")
            ->execute([
                $email,
                $status,
                $user_id
            ]);
    } else {

        //============================================
        // CREATE USER
        //============================================

        $password = password_hash(
            'Student123!',
            PASSWORD_DEFAULT
        );

        $stmt = $pdo->prepare("
    INSERT INTO users
    (
        email,
        password,
        role,
        status
    )
    VALUES
    (
        ?, ?, 'student', ?
    )
");

        $stmt->execute([
            $email,
            $password,
            $status
        ]);

        $user_id = $pdo->lastInsertId();

        //============================================
        // CREATE STUDENT
        //============================================

        $stmt = $pdo->prepare("
            INSERT INTO students
            (
                user_id,
                student_number,
                email,
                first_name,
                middle_name,
                last_name,
                gender,
                birth_date,
                contact_number,
                address,
                student_type,
                status,
                course_id
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $user_id,
            $student_number,
            $email,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $birth_date,
            $contact_number,
            $address,
            $student_type,
            $status,
            $course_id
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => !empty($id)
            ? 'Student updated successfully.'
            : 'Student added successfully.'
    ]);
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $message = $e->getMessage();

    if (
        stripos($message, 'Duplicate entry') !== false
    ) {

        $message = 'A record with the same information already exists.';
    }

    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
}
