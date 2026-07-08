<?php

require_once '../includes/db.php';
require_once '../includes/sessions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../index.php?error=empty_fields");
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM users
    WHERE email = ?
    AND status = 'active'
    LIMIT 1
");

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: ../index.php?error=invalid_credentials");
    exit;
}

//=======================================
// PASSWORD VERIFICATION
//=======================================

if (!password_verify($password, $user['password'])) {
    header("Location: ../index.php?error=invalid_credentials");
    exit;
}

//======================================
// LOAD DISPLAY INFORMATION
//======================================

$displayName = $user['email'];
$photo = $user['photo'] ?? null;

switch ($user['role']) {

    case 'student':

        $stmt = $pdo->prepare("
            SELECT
                first_name,
                middle_name,
                last_name
            FROM students
            WHERE user_id = ?
            LIMIT 1
        ");

        $stmt->execute([$user['id']]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $displayName =
                trim(
                    $row['first_name'] . ' ' .
                        ($row['middle_name']
                            ? strtoupper(substr($row['middle_name'], 0, 1)) . '. '
                            : '') .
                        $row['last_name']
                );
        }

        break;

    case 'faculty':

        $stmt = $pdo->prepare("
            SELECT
                first_name,
                middle_name,
                last_name
            FROM faculty
            WHERE user_id = ?
            LIMIT 1
        ");

        $stmt->execute([$user['id']]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $displayName =
                trim(
                    $row['first_name'] . ' ' .
                        ($row['middle_name']
                            ? strtoupper(substr($row['middle_name'], 0, 1)) . '. '
                            : '') .
                        $row['last_name']
                );
        }

        break;

    case 'admin':

        if (!empty($user['first_name'])) {

            $displayName =
                trim(
                    $user['first_name'] . ' ' .
                        ($user['middle_name']
                            ? strtoupper(substr($user['middle_name'], 0, 1)) . '. '
                            : '') .
                        $user['last_name']
                );
        }

        break;
}

$_SESSION['user_id'] = $user['id'];

$_SESSION['email'] = $user['email'];

$_SESSION['role'] = $user['role'];

$_SESSION['display_name'] = $displayName;

$_SESSION['photo'] = $photo;

switch ($user['role']) {

    case 'admin':
        header("Location: ../admin/");
        break;

    case 'faculty':
        header("Location: ../faculty/");
        break;

    case 'student':
        header("Location: ../student/");
        break;

    default:
        session_destroy();
        header("Location: ../index.php");
        break;
}

exit;
