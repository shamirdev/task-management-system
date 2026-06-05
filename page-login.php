<!-- Model -->
<?php
session_start();
// DB Connection
require_once 'config/dbConnect.php';
// Sweet Alert
$alert = "";
if (isset($_SESSION['success'])) {
    $alert = "success";
    unset($_SESSION['success']);
}
// Getting data from input fields
if (isset($_POST['signInButton'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    // var_dump($email, $password);
    // Checking if any of the fields are empty
    if (empty($email) || empty($password)) {
        $alert = "empty fields";
    }
    try {

        $getStmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $getStmt->execute([":email" => $email]);
        $user = $getStmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['logged_in'] = true;
            if ($_SESSION['user_role'] == 'admin') {
                header("Location: index2.php");
                exit();
            } else if ($_SESSION['user_role'] == 'employee') {

                header("Location: index.php");
                exit();
            }
        } else {
            $alert = "invalid credentials";
        }
    } catch (PDOException $e) {
        // echo "Error:" . $e->getMessage();
    }
}

?>
<!-- View -->
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Focus - Bootstrap Admin Dashboard </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            background-image: url('./images/task management.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .glossy{
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            border-radius: 10px;
        }
    </style>
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content glossy">
                        <div class="row no-gutters">
            
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form action="page-login.php" method="post">
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control" name="email" placeholder="Enter your email">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password" placeholder="Enter your password">
                                        </div>
                                        <!-- <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1">
                                                    <label class="form-check-label" for="basic_checkbox_1">Remember me</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <a href="page-forgot-password.html">Forgot Password?</a>
                                            </div>
                                        </div> -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btnPrimary btn-block" name="signInButton">Sign me in</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Don't have an account? <a class="text-primary" href="./page-register.php">Sign up</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script>
        let alert = "<?php echo $alert ?? ''; ?>";
        if (alert === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registration successful',
                text: 'You can now log in with your credentials.',
            });
        } else if (alert === "empty fields") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields.',
            });
        } else if (alert === "login success") {
            Swal.fire({
                icon: 'success',
                title: 'Login successful',
                text: 'Welcome back!',
            });
        } else if (alert === "invalid credentials") {
            Swal.fire({
                icon: 'error',
                title: 'Invalid credentials',
                text: 'Please check your email and password.',
            });
        }
    </script>

</body>

</html>