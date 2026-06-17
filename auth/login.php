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

if (!password_verify($password, $user['password'])) {
    header("Location: ../index.php?error=invalid_credentials");
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['email']   = $user['email'];
$_SESSION['role']    = $user['role'];

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
