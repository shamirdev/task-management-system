<?php
// Session start here
include_once './logout.php';
// Db Connection here
require_once './config/dbConnect.php';
// Sweet Alert
$alert = "";
if (!$_SESSION['logged_in']) {
    header("Location: page-login.php");
    exit();
} else if ($_SESSION['user_role'] !== "admin") {
    header("Location: page-error-403.php");
    exit();
} else {
    if(isset($_POST['add_employee'])){
        $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_NUMBER_INT);
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($phone_number) || empty($password)) {
            $alert = "empty_fields";
        } elseif (strlen($password) < 8) {
            $alert = "password_short";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                // check if user exist
                $getUserStmt = $conn->prepare("SELECT email, phone_number FROM users WHERE email = :email OR phone_number = :phone_number");
                $getUserStmt->execute([
                    ':email' => $email,
                    ':phone_number' => $phone_number
                ]);
                $existingUser = $getUserStmt->fetch(PDO::FETCH_ASSOC);
                if ($existingUser) {
                    if ($existingUser['email'] === $email && $existingUser['phone_number'] === $phone_number) {
                        $alert = "person_exist";
                    } elseif ($existingUser['email'] === $email) {
                        $alert = "person_exist";
                    } elseif ($existingUser['phone_number'] === $phone_number) {
                        $alert = "person_exist";
                    }
                } else {
                    $postNewUser = $conn->prepare("INSERT INTO users (full_name, email, phone_number, password, role) VALUES (:full_name, :email, :phone_number, :password, :role)");
                    $postNewUser->execute([
                        ':full_name' => $name,
                        ':email' => $email,
                        ':phone_number' => $phone_number,
                        ':password' => $hashed_password,
                        ':role' => 'employee'
                    ]);
                    $alert = "employee_added";
                }
            } catch (PDOException $e) {
                // echo "Error:" . $e->getMessage();
            }
        }
    }   
}
    
?>
<!-- View -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Focus - Bootstrap Admin Dashboard </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Summernote -->
    <!-- <link href="./vendor/summernote/summernote.css" rel="stylesheet"> -->
    <!-- Custom Stylesheet -->
    <link href="./css/style.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"> -->

    <!-- Summernote CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css"> -->

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- CK Editor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <!-- Style -->
    <style>
        .ck-editor__editable {
            color: #000000 !important;
        }
    </style>
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="index.html" class="brand-logo">
                <img class="logo-abbr" src="./images/logo.png" alt="">
                <img class="logo-compact" src="./images/logo-text.png" alt="">
                <img class="brand-title" src="./images/logo-text.png" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-start" style="margin-left:-70px;">
                        <h4 class="welcome-heading">
                            <span>
                                Welcome <strong><?php echo ucfirst($_SESSION['user_name']); ?></strong>
                            </span>
                        </h4>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav focus-sidebar">
            <div class="focus-sidebar-inner">

                <div class="focus-brand">
                    <div class="focus-logo">
                        <span>F</span>
                    </div>
                    <h4>FOCUS</h4>
                </div>

                <div class="quixnav-scroll focus-menu-area">
                    <ul class="metismenu focus-menu" id="menu">

                        <li class="nav-label first">Main Menu</li>

                        <li>
                            <a href="./index.php" aria-expanded="false" style="background-color: #1F415E;">
                                <i class="icon icon-single-04"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-label">Activities</li>

                        <li>
                            <a class="has-arrow" href="javascript:void(0)" aria-expanded="false" style="background-color: #1F415E;">
                                <i class="icon icon-app-store"></i>
                                <span class="nav-text">Tasks</span>
                            </a>

                            <ul aria-expanded="false">
                                <a href="./allEmployees.php">All Employees</a>
                                <a href="./allTasks.php">All Tasks</a>
                                <a href="./adminAddEmployee.php">Add Employee</a>
                                <a href="./adminCreateTask.php">Assign Task</a>
                            </ul>
                        </li>

                    </ul>
                </div>

                <div class="focus-user-box">
                    <div class="focus-user-info">
                        <div class="focus-avatar">
                            <?php
                            $userName = $_SESSION['user_name'] ?? 'User';
                            echo strtoupper(substr($userName, 0, 1));
                            ?>
                        </div>

                        <div>
                            <h5>
                                <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                            </h5>
                            <small><?php echo htmlspecialchars($_SESSION['user_role']); ?></small>
                        </div>
                    </div>

                    <form method="POST">
                        <button type="submit" class="focus-logout-btn" name="logout">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span class="ml-2">Logout </span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mt-2 w-100 ml-1">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Add Employee Account!</h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href=""></a></li>
                            <li class="breadcrumb-item active"><a href="./employeeTasks.php">All Tasks</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Enter Employee Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="adminAddEmployee.php" method="POST" id="employeeForm" novalidate>
                                    <div class="d-flex gap-5" style="gap: 10px;">
                                        <input class="form-control w-50 form-control-lg" type="text" name="name" placeholder="Employee Name" required>
                                        <input class="form-control w-50 form-control-lg" type="email" name="email" placeholder="Employee Email" required>
                                    </div>
                                    <div class="d-flex gap-5 mt-4" style="gap: 10px;">
                                        <input class="form-control form-control-lg" type="text" name="phone_number" placeholder="Employee Phone Number" required>
                                         <input class="form-control form-control-lg" type="password" name="password" placeholder="Employee Password" minlength="8" autocomplete="new-password" required>
                                    </div>
                                    <button name="add_employee" type="submit" class="btn btn-primary mt-2 float-right">Add Employee</button>
                                </form>
                                <!-- <div class="summernote"></div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        **********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const employeeForm = document.getElementById("employeeForm");
            const nameInput = document.querySelector('input[name="name"]');
            const emailInput = document.querySelector('input[name="email"]');
            const phoneInput = document.querySelector('input[name="phone_number"]');
            const passwordInput = document.querySelector('input[name="password"]');

            employeeForm.addEventListener("submit", function(event) {
                if (!nameInput.value.trim() || !emailInput.value.trim() || !phoneInput.value.trim() || !passwordInput.value.trim()) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please fill in all fields.'
                    });
                    return;
                }

                if (!emailInput.validity.valid) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid email',
                        text: 'Please enter a valid email address.'
                    }).then(function() {
                        emailInput.focus();
                    });
                    return;
                }

                if (passwordInput.value.length < 8) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password too short',
                        text: 'Your password must be at least 8 characters long.'
                    }).then(function() {
                        passwordInput.focus();
                    });
                }
            });

            let alert = "<?php echo $alert; ?>";
            if (alert === "empty_fields") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fill in all fields.'
                });
            } else if (alert === "password_short") {
                Swal.fire({
                    icon: 'error',
                    title: 'Password too short',
                    text: 'Your password must be at least 8 characters long.'
                });
            } else if (alert === "person_exist") {
                Swal.fire({
                    icon: 'error',
                    title: 'Employee exists',
                    text: 'An employee with this email or phone number already exists.'
                });
            } else if (alert === "employee_added") {
                Swal.fire({
                    icon: 'success',
                    title: 'Employee Added',
                    text: 'The employee account has been created successfully.'
                });
            }
        });
    </script>
</body>
</html>
