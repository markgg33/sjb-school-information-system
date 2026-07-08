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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Faculty Dashboard</title>

    <!-- ICON -->
    <script src="https://kit.fontawesome.com/92cde7fc6f.js" crossorigin="anonymous"></script>

    <link rel="icon"
        type="image/x-icon"
        href="../assets/logos/sjb-logo.ico">

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- SweetAlert -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="../css/theme.css">
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

    <div class="dashboard-layout">

        <div id="sidebarOverlay"></div>

        <!-- SIDEBAR -->

        <aside class="sidebar">

            <div class="sidebar-brand">

                <div class="sidebar-brand-left">

                    <img
                        src="../assets/logos/sjb-logo.png"
                        class="sidebar-logo">

                    <div>

                        <h5>SJB ICAS</h5>

                        <small>

                            Faculty Portal

                        </small>

                    </div>

                </div>

                <button
                    id="sidebarClose"
                    class="sidebar-close">

                    <i class="fa-solid fa-xmark"></i>

                </button>

            </div>

            <div class="sidebar-body">

                <ul class="sidebar-menu">

                    <li class="menu-title">
                        Dashboard
                    </li>

                    <li data-page="dashboard">

                        <i class="fa-solid fa-chart-line"></i>

                        Dashboard

                    </li>

                    <li class="menu-title">
                        Academics
                    </li>

                    <li data-page="my-subjects">

                        <i class="fa-solid fa-book-open-reader"></i>

                        My Subjects

                    </li>

                    <li class="menu-title">
                        System
                    </li>

                    <li data-page="profile-settings">

                        <i class="fa-solid fa-user-gear"></i>

                        Profile Settings

                    </li>

                </ul>

            </div>

            <div class="sidebar-footer">

                <a
                    href="#"
                    id="logoutBtn"
                    class="logout-link">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    Logout

                </a>

            </div>

        </aside>

        <!-- MAIN -->

        <main class="main-content">

            <nav class="topbar">

                <button
                    class="menu-toggle"
                    id="menuToggle">

                    <i class="fa-solid fa-bars"></i>

                </button>

                <div class="topbar-user">

                    <div
                        class="user-profile"
                        id="openProfile">

                        <img
                            id="topbarProfilePhoto"
                            src="<?= !empty($_SESSION['photo'])
                                        ? '../uploads/profile/' . htmlspecialchars($_SESSION['photo'])
                                        : '../uploads/profile/default.png'; ?>"
                            class="topbar-avatar">

                        <div>

                            <div
                                id="topbarDisplayName"
                                class="fw-semibold">

                                <?= htmlspecialchars($_SESSION['display_name'] ?? $_SESSION['email']) ?>

                            </div>

                            <small class="text-muted">

                                Faculty

                            </small>

                        </div>

                    </div>

                    <button
                        class="theme-toggle"
                        id="themeToggle">

                        <i class="fa-solid fa-moon"></i>

                    </button>

                </div>

            </nav>

            <div id="content-area">

            </div>

        </main>

    </div>

    <!-- GLOBAL LOADER -->

    <div id="globalLoader">

        <div class="loader-content">

            <div class="loader-spinner"></div>

            <div class="loader-text">

                Loading...

            </div>

        </div>

    </div>

    <!-- GLOBAL JS -->

    <script src="../js/alertService.js"></script>
    <script src="../js/login.js"></script>

    <!-- FACULTY JS -->

    <script src="js/dashboard.js"></script>
    <script src="js/faculty.js"></script>
    <script src="js/subjects.js"></script>
    <script src="js/grades.js"></script>
    <script src="../includes/profile/js/profile.js"></script>

</body>

</html>