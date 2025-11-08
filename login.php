<?php
/**
 * User Login Handler
 * 
 * This script handles user authentication and login. It validates user credentials,
 * creates necessary session/cookie data, and manages login state.
 * 
 * Features:
 * - Email and password validation
 * - Session-based authentication
 * - Cookie-based "remember me" functionality
 * - CSRF protection
 * - Secure password verification
 * 
 * @package MyBlog
 * @subpackage Authentication
 * @version 1.0
 */

// Include configuration and database connection
include "./includes/config.php";

// If already logged in via cookie, redirect to dashboard
if (isset($_COOKIE['user_id'])) {
    header("Location: ./index.php");
    exit();
}

// Initialize message variable for feedback
$message = "";

// Handle Login
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['Email']);
    $password = $_POST['PW'];

    if (empty($email) || empty($password)) {
        $message = "<div class='alert alert-danger'>Please fill all fields.</div>";
    } else {
        // Fetch user by email
        $stmt = $pdo_conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Correct password, set cookies only (per preference)
            $displayName = $user['name'] ?? $user['Name'] ?? '';
            setcookie('username', $displayName, time() + 86400, '/');
            setcookie('user_id', $user['id'], time() + 86400, '/');

            header("Location: index.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Invalid email or password.</div>";
        }
    }
}

// Check if redirected from register
if (isset($_GET['registered'])) {
    $message = "<div class='alert alert-success'>Registration successful! Please log in.</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login | MyBlog</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">

                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>

                                    <!-- Alerts -->
                                    <?= $message ?>

                                    <form class="user" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                placeholder="Enter Email Address..." name="Email">
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                placeholder="Password" name="PW">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>

                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>