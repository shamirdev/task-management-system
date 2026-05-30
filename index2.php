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
} else if ($_SESSION['user_role'] !== "admin") {
    header("Location: page-error-403.php");
} else {
    try {
        $getUsersStmt = $conn->prepare("SELECT * FROM users");
        $getUsersStmt->execute();
        $user = $getUsersStmt->fetchAll(PDO::FETCH_ASSOC);
        $getTasksStmt = $conn->prepare("SELECT * FROM tasks");
        $getTasksStmt->execute();
        $tasks = $getTasksStmt->fetchAll(PDO::FETCH_ASSOC);
        $completedTasks = array_filter($tasks, function ($task) {
            return $task['status'] === 'completed';
        });
        $pendingTasks = array_filter($tasks, function ($task) {
            return $task['status'] === 'pending';
        });
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
        <!--Header end ti-comment-alt-->

        <!--Sidebar start-->
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
        <!--Sidebar end-->

        <!--Content body start-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid mt-4">
                <div class="row d-flex align-items-center justify-content-center">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text"> Total Employees</div>
                                    <div class="stat-digit"> <?php echo isset($user) ? count($user) : 0; ?></div>
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
                                <div class="stat-text">Total Tasks</div>
                                <div class="stat-digit"><?php echo isset($tasks) ? count($tasks) : 0; ?></div>
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
                                <div class="stat-digit"><?php echo isset($completedTasks) ? count($completedTasks) : 0; ?></div>
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
                                <div class="stat-digit"><?php echo isset($pendingTasks) ? count($pendingTasks) : 0; ?></div>
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
            p<div class="row align-items-center justify-content-start position-relative" style="gap: 20px;">
                <div class="col-md-5 card" style="margin-top: 17px; padding:20px; margin-left: 30px;">
                    <div style="position: relative; height: 280px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6 card" style="margin-top: 17px; padding:20px;">
                    <div style="position: relative; height: 280px;">
                        <canvas id="animatedChart"></canvas>
                    </div>
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
    <script>
        // Get the canvas element
        const ctx = document.getElementById('animatedChart').getContext('2d');
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        // Create bar chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Tasks', 'Completed Tasks', 'Pending Tasks'],
                datasets: [{
                    label: 'My Tasks',
                    data: [
                        <?php echo count($tasks); ?>,
                        <?php echo count($completedTasks); ?>,
                        <?php echo count($pendingTasks); ?>
                    ],
                    backgroundColor: ['#347928', '#C0EBA6', '#FCCD2A'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            min: 0,
                            stepSize: 1,
                            precision: 0
                        }
                    }]
                },
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'My Task Overview'
                }
            }
        });
        // Create the pie chart
        new Chart(pieCtx, {
            type: 'pie', // Type: 'pie', 'doughnut', 'bar', 'line'
            data: {
                labels: ['Pending Tasks', 'Completed Tasks'],
                datasets: [{
                    label: 'My Tasks',
                    data: [<?php echo count($pendingTasks); ?>, <?php echo count($completedTasks); ?>],
                    backgroundColor: [
                        '#5AB2FF', // Pending = red/pink
                        '#CAF4FF' // Completed = green/teal
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'My Task Progress'
                    }
                }
            }
        });
    </script>
</body>

</html>