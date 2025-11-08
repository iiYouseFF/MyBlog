<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "./includes/config.php";
$message = "";
if (isset($_COOKIE['user_id'])) {
    header("Location:index.php");
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $first = trim($_POST['FN']);
    $last = trim($_POST['LN']);
    $email = trim($_POST['Email']);
    $password = $_POST['PW'];
    $repeat = $_POST['RPW'];
    // Basic Validations
    if (empty($first) || empty($last) || empty($email) || empty($password) || empty($repeat)) {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Invalid email format.</div>";
    } else if ($password !== $repeat) {
        $message = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        // Check if email exists
        $stmt = $pdo_conn->prepare("SELECT id FROM `users` WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "<div class='alert alert-danger'>Email already in use.</div>";
        } else {
            // Create username
            $username = $first . $last;

            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo_conn->prepare("INSERT INTO `users` (`name`, `email`, `password` , `age`) VALUES (?, ?, ? , ?)");
            $stmt->execute([$username, $email, $hashed, $_POST['age']]);
            $stmt3 = $pdo_conn->prepare("SELECT * FROM `users` WHERE Email = ?");
            $stmt3->execute([$email]);
            $row = $stmt3->fetch();
            $user_id = $row['id'];
            // Check if admin request checkbox is checked
            if (isset($_POST['request_admin']) && $_POST['request_admin'] == 'on') {
                $stmt2 = $pdo_conn->prepare("INSERT INTO admin_requests (user_id) VALUES (?)");
                $stmt2->execute([$user_id]);
            }
            setcookie("username", $username, time() + 86400, "/");
            setcookie("user_id", $user_id, time() + 86400, "/");

            // Redirect after successful register
            header("Location:login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Register | MyBlog</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">

    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="p-5">

                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>

                            <!-- Display alerts -->
                            <?= $message ?>

                            <form class="user" method="post">
                                <div class="form-group row">
                                    <div class="col-sm-5 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            placeholder="First Name" name="FN">
                                    </div>
                                    <div class="col-sm-5 mb-2 mb-sm-0">
                                        <input type="text" class="form-control form-control-user"
                                            placeholder="Last Name" name="LN">
                                    </div>
                                    <div class="col-sm-2 mb-2 mb-sm-0">
                                        <input type="number" class="form-control form-control-user"
                                            placeholder="Age" name="age">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user"
                                        placeholder="Email Address" name="Email">
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user"
                                            placeholder="Password" name="PW">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user"
                                            placeholder="Repeat Password" name="RPW">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="adminCheck" name="request_admin">
                                            <label class="custom-control-label" for="adminCheck">Request Admin Access</label>
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>

                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
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