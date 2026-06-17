<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SJB ICAS Student Information System</title>

    <!-- ICON -->
    <script src="https://kit.fontawesome.com/92cde7fc6f.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/x-icon" href="assets/logos/sjb-logo.ico">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="css/theme.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <div class="login-wrapper">

        <div class="branding-side">

            <div class="branding-content">

                <img src="assets/logos/sjb-logo.png" alt="Logo">

                <h1>SJB ICAS</h1>

                <p>
                    Student Information System 2026
                </p>

            </div>

        </div>

        <div class="login-side">

            <div class="login-card">

                <div class="text-center">
                    <h2>Welcome Back</h2>

                    <p>Please login to continue.</p>
                </div>

                <form method="POST" action="auth/login.php">

                    <input
                        type="hidden"
                        id="loginError"
                        value="<?= htmlspecialchars($_GET['error'] ?? '') ?>">

                    <div class="mb-3">
                        <label class="form-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email"
                            required>
                    </div>

                    <div class="mb-4">

                        <label class="form-label">
                            Password
                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter password"
                                required>

                            <span
                                class="input-group-text password-toggle"
                                onclick="togglePassword()">
                                <i id="passwordIcon" class="fa-solid fa-eye"></i>
                            </span>

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="btn-login">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script src="js/login.js"></script>
    <script src="js/alertService.js"></script>

</body>

</html>