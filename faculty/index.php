<?php

require_once '../includes/sessions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SESSION['role'] !== 'faculty') {
    header("Location: ../index.php");
    exit;
}
?>

<h1>Faculty Dashboard</h1>

<a href="../auth/logout.php">
    Logout
</a>