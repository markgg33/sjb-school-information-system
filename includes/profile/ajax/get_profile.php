<?php

require_once '../../db.php';
require_once '../../sessions.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| GET USER ACCOUNT
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT
    id,
    email,
    role,
    photo
FROM users
WHERE id=?
LIMIT 1
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([]);
    exit;
}

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

if ($user['role'] === 'admin') {

    $stmt = $pdo->prepare("
SELECT
first_name,
middle_name,
last_name,
contact_number,
address,
birth_date
FROM admins
WHERE user_id=?
");

    $stmt->execute([$user_id]);

    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| FACULTY
|--------------------------------------------------------------------------
*/ elseif ($user['role'] === 'faculty') {

    $stmt = $pdo->prepare("
SELECT

employee_number,

first_name,
middle_name,
last_name,

birth_date,
contact_number,
address

    FROM faculty

    WHERE user_id=?
    ");

    $stmt->execute([$user_id]);

    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
}

/*
|--------------------------------------------------------------------------
| STUDENT
|--------------------------------------------------------------------------
*/ else {

    $stmt = $pdo->prepare("
 SELECT

student_number,

c.course_code,
c.course_name,

first_name,
middle_name,
last_name,

birth_date,
contact_number,
address

    FROM students s

    LEFT JOIN courses c
        ON c.id=s.course_id

    WHERE user_id=?
    ");

    $stmt->execute([$user_id]);

    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
}

echo json_encode(array_merge($user, $profile ?: []));
