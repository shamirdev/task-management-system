<!-- Model -->
<?php
session_start();
// Include the database connection file
require_once 'config/dbConnect.php';
// Getting data from input fields
if (isset($_POST['signUpButton'])) {
    $signUpName = htmlspecialchars($_POST['signUpName']);
    $signUpEmail = filter_input(INPUT_POST, 'signUpEmail', FILTER_SANITIZE_EMAIL);
    $signUpPhone = filter_input(INPUT_POST, 'signUpPhone', FILTER_SANITIZE_NUMBER_INT);
    $signUpPassword = $_POST['signUpPassword'];
    // $signUpPassword = password_hash($_POST['signUpPassword'], PASSWORD_DEFAULT);
    // $signUpPassword = $_POST['signUpPassword'];
    $alert = "";
    // Checking if any of the fields are empty
    if (empty($signUpName) || empty($signUpEmail) || empty($signUpPhone) || empty($signUpPassword)) {
        echo "All fields are required.";
        exit();
    }

    // Submiting data to database
    try {
        $getStmt = $conn->prepare("SELECT email, phone_number FROM users WHERE email = :email OR phone_number = :phone_number");
        $getStmt->execute([":email" => $signUpEmail, ":phone_number" => $signUpPhone]);
        $existingUser = $getStmt->fetch(PDO::FETCH_ASSOC);
        if ($existingUser) {
            if ($existingUser['email'] === $signUpEmail && $existingUser['phone_number'] === $signUpPhone) {
                $alert = "both_exist";
            } elseif ($existingUser['email'] === $signUpEmail) {
                $alert = "email_exist";
            } elseif ($existingUser['phone_number'] === $signUpPhone) {
                $alert = "phone_exist";
            }
        }
        $postStmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, password) VALUES (:full_name, :email, :phone_number, :password)");
        $postStmt->execute([
            ":full_name" => $signUpName,
            ":email" => $signUpEmail,
            ":phone_number" => $signUpPhone,
            ":password" => $signUpPassword
        ]);
        $_SESSION['success'] = "Registration successful";
        session_destroy();        
        header("Location: page-login.php");
        exit();
    } catch (PDOException $e) {
        // echo "Error:" . $e->getMessage();
    }
}
// $signUpConfirmPassword = filter_input(INPUT_POST, 'signUpConfirmPassword', FILTER_SANITIZE_STRING);
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
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">Sign up your account</h4>
                                    <form action="page-register.php" method="post">
                                        <div class="form-group">
                                            <label><strong>Name</strong></label>
                                            <input type="text" class="form-control" name="signUpName" placeholder="Enter your full name">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Email</strong></label>
                                            <input type="email" class="form-control" name="signUpEmail" placeholder="hello@example.com">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Phone Number</strong></label>
                                            <input type="text" class="form-control" name="signUpPhone" placeholder="Enter your phone number">
                                        </div>
                                        <div class="form-group">
                                            <label><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="signUpPassword" placeholder="Enter your password">
                                        </div>
                                        <!-- <div class="form-group">
                                            <label><strong>Confirm Password</strong></label>
                                            <input name="signUpConfirmPassword" type="password" class="form-control" placeholder="Confirm your password">
                                        </div> -->
                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-block" name="signUpButton">Sign me up</button>
                                        </div>
                                    </form>
                                    <div class="new-account mt-3">
                                        <p>Already have an account? <a class="text-primary" href="page-login.php">Sign in</a></p>
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
    <!--endRemoveIf(production)-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let alert = "<?php echo $alert; ?>";
            if (alert === "both_exist") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Both email and phone number already exist.'
                });
            } else if (alert === "email_exist") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Email already exists.'
                });
            } else if (alert === "phone_exist") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Phone number already exists.'
                });
            } 
        });
    </script>
</body>

</html>