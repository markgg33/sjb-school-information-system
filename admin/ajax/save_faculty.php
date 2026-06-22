<?php

require_once '../../includes/db.php';

header('Content-Type: application/json');

try {

    $pdo->beginTransaction();

    $id = $_POST['id'] ?? '';

    $employee_number = trim($_POST['employee_number'] ?? '');
    $employee_number = $employee_number !== '' ? $employee_number : null;

    $email = trim($_POST['email']);

    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name'] ?? '');
    $middle_name = $middle_name !== '' ? $middle_name : null;

    $last_name = trim($_POST['last_name']);

    $gender = $_POST['gender'];

    $contact_number = trim($_POST['contact_number'] ?? '');
    $contact_number = $contact_number !== '' ? $contact_number : null;

    $address = trim($_POST['address'] ?? '');
    $address = $address !== '' ? $address : null;

    $status = $_POST['status'] ?? 'active';

    $courses = $_POST['courses'] ?? [];

    //====================================
    // CHECK EMPLOYEE NUMBER
    //====================================

    if ($employee_number !== null) {

        if (!empty($id)) {

            $check = $pdo->prepare("
                SELECT id
                FROM faculty
                WHERE employee_number = ?
                AND id <> ?
            ");

            $check->execute([
                $employee_number,
                $id
            ]);
        } else {

            $check = $pdo->prepare("
                SELECT id
                FROM faculty
                WHERE employee_number = ?
            ");

            $check->execute([
                $employee_number
            ]);
        }

        if ($check->fetch()) {
            throw new Exception(
                'Employee number already exists.'
            );
        }
    }

    //====================================
    // CHECK EMAIL
    //====================================

    if (!empty($id)) {

        $checkEmail = $pdo->prepare("
            SELECT id
            FROM faculty
            WHERE email = ?
            AND id <> ?
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
            'Email address is already in use.'
        );
    }

    //====================================
    // UPDATE
    //====================================

    if (!empty($id)) {

        $stmt = $pdo->prepare("
            UPDATE faculty
            SET
                employee_number=?,
                email=?,
                first_name=?,
                middle_name=?,
                last_name=?,
                gender=?,
                contact_number=?,
                address=?,
                status=?
            WHERE id=?
        ");

        $stmt->execute([
            $employee_number,
            $email,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $contact_number,
            $address,
            $status,
            $id
        ]);

        $faculty = $pdo->prepare("
            SELECT user_id
            FROM faculty
            WHERE id = ?
        ");

        $faculty->execute([$id]);

        $user_id = $faculty->fetchColumn();

        $pdo->prepare("
            UPDATE users
            SET
                email=?,
                status=?
            WHERE id=?
        ")
            ->execute([
                $email,
                $status,
                $user_id
            ]);

        $faculty_id = $id;
    } else {

        //====================================
        // CREATE USER
        //====================================

        $password = password_hash(
            'Faculty123!',
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
                ?, ?, 'faculty', ?
            )
        ");

        $stmt->execute([
            $email,
            $password,
            $status
        ]);

        $user_id = $pdo->lastInsertId();

        //====================================
        // CREATE FACULTY
        //====================================

        $stmt = $pdo->prepare("
            INSERT INTO faculty
            (
                user_id,
                employee_number,
                email,
                first_name,
                middle_name,
                last_name,
                gender,
                contact_number,
                address,
                status
            )
            VALUES
            (
                ?,?,?,?,?,?,?,?,?,?
            )
        ");

        $stmt->execute([
            $user_id,
            $employee_number,
            $email,
            $first_name,
            $middle_name,
            $last_name,
            $gender,
            $contact_number,
            $address,
            $status
        ]);

        $faculty_id = $pdo->lastInsertId();
    }

    //====================================
    // SAVE COURSES
    //====================================

    $pdo->prepare("
        DELETE FROM faculty_courses
        WHERE faculty_id = ?
    ")->execute([
        $faculty_id
    ]);

    foreach (array_unique($courses) as $course_id) {

        $pdo->prepare("
            INSERT INTO faculty_courses
            (
                faculty_id,
                course_id
            )
            VALUES
            (
                ?, ?
            )
        ")->execute([
            $faculty_id,
            $course_id
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => !empty($id)
            ? 'Faculty updated successfully.'
            : 'Faculty added successfully.'
    ]);
} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
