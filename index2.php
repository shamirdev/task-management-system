<!-- Model -->
<?php
// including logout.php to handle logout functionality
include_once './logout.php';
// DB Connection
require_once 'config/dbConnect.php';
// Sweet Alert
$alert = "";
// Checking if user is logged in
if (!$_SESSION['logged_in']) {
    header("Location: page-login.php");
    exit();
} else if($_SESSION['user_role'] !== "admin"){
    header("Location: page-error-403.php");
} else {
    try {
        $getStmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $getStmt->execute(['user_id' => $_SESSION['user_id']]);
        $user = $getStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    <link href="./vendor/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <link href="./vendor/chartist/css/chartist.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <!--Preloader start-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--Preloader end-->

    <!--Main wrapper start-->
    <div id="main-wrapper">
        <!--Nav header start-->
        <div class="nav-header">
            <a href="index.php" class="brand-logo">
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
        <!--Nav header end-->

        <!--Header start-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-end">

                        <h4 class="pt-3">Welcome <?php echo ucfirst($_SESSION['user_name']); ?></h4>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile pt-1">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <form method="POST">
                                        <button type="submit" class="dropdown-item" name="logout">
                                            <i class="fa-solid fa-right-to-bracket"></i>
                                            <span class="ml-2">Logout </span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--Header end ti-comment-alt-->

        <!--Sidebar start-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a class="" href="./index2.php" aria-expanded="false"><i
                                class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
                    </li>
                    <li class="nav-label">Activities</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="fa-solid fa-user"></i><span class="nav-text">&nbsp;&nbsp;&nbsp;Employees & Tasks</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./allEmployees.php">All Employees</a></li>
                            <li><a href="./allTasks.php">All Tasks</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--Sidebar end-->

        <!--Content body start-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text"> Total Tasks </div>
                                    <div class="stat-digit"> <?php echo isset($tasks) ? count($tasks) : 0; ?></div>
                                </div>
                                <div class="progress"">
                                    <div class=" progress-bar progress-bar-primary w-100" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Total Employees</div>
                                <div class="stat-digit"><?php echo isset($CompletedTasks) ? count($CompletedTasks) : 0; ?></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-warning w-100" role="progressbar" ariavaluenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Completed Tasks</div>
                                <div class="stat-digit"><?php echo isset($CompletedTasks) ? count($CompletedTasks) : 0; ?></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-success w-100" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="card">
                        <div class="stat-widget-two card-body">
                            <div class="stat-content">
                                <div class="stat-text">Pending Tasks</div>
                                <div class="stat-digit"><?php echo isset($PendingTasks) ? count($PendingTasks) : 0; ?></div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger w-100" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /# card -->
                </div>
                <!-- /# column -->
            </div>
            <div class="row d-flex align-items-center justify-content-center">
                <div class="col-md-5" style="width: 400px; margin-top: 25px;">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="col-md-6" style="width: 400px; margin-top: 25px;">
                    <canvas id="animatedChart"></canvas>
                </div>
            </div>
        </div>

    </div>
    <!--Content body end-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script src="./vendor/chartist/js/chartist.min.js"></script>

    <script src="./vendor/moment/moment.min.js"></script>
    <script src="./vendor/pg-calendar/js/pignose.calendar.min.js"></script>


    <script src="./js/dashboard/dashboard-2.js"></script>
    <!-- Circle progress -->

</body>

</html>