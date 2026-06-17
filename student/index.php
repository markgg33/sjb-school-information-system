<?php

require_once '../includes/sessions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}
?>

<h1>Student Dashboard</h1>

<a href="../auth/logout.php">
    Logout
</a>