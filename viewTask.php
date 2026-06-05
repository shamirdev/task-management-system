<?php
// session_start();
require_once './config/dbConnect.php';
// Check if user click logout
include_once './logout.php';
if (!isset($_SESSION['logged_in'])) {
    header("Location: page-login.php");
    exit();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$id = $_GET['id'];
$id = (int)$id;
if ($id <= 0) {
    die("Invalid task ID.");
}
if (empty($id)) {
    echo "Error: Task ID is required.";
    return;
}
try {
    $getStmt = $conn->prepare("SELECT * FROM tasks where task_id = :id");
    $getStmt->execute(['id' => $id]);
    $task = $getStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Your Task</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link href="./css/style.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <i class="mdi mdi-account"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <form method="POST" action="./logout.php">
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
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="quixnav">
            <div class="quixnav-scroll">
                <ul class="metismenu" id="menu">
                    <li class="nav-label first">Main Menu</li>
                    <li><a class="" href="./index.php" aria-expanded="false"><i
                                class="icon icon-single-04"></i><span class="nav-text">Dashboard</span></a>
                    </li>
                    <li class="nav-label">Activities</li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false"><i
                                class="icon icon-app-store"></i><span class="nav-text">Tasks</span></a>
                        <ul aria-expanded="false">
                            <li><a href="./employeeTasks.php">All Tasks</a></li>
                            <!-- <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a> -->
                            <!-- <ul aria-expanded="false">
                                    <li><a href="./email-compose.php">All Tasks</a></li>
                                    <li><a href="./email-inbox.php">Create Tasks</a></li>
                                    <li><a href="./email-read.php">Update Tasks</a></li>
                                </ul> -->
                            <!-- </li> -->
                            <li><a href="./createTask.php">Create Tasks</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
        <!-- Content -->
        <style>
            .view-task-card .card-header {
                background: linear-gradient(90deg, rgba(27, 54, 102, 0.08) 0%, rgba(27, 54, 102, 0) 100%);
                border-bottom: 2px solid #1b3666;
            }

            .view-task-card .card-title {
                font-weight: 700;
            }

            .task-section-label {
                font-size: 0.72rem;
                letter-spacing: 1.5px;
                color: #8a8a8a;
                font-weight: 700;
                text-transform: uppercase;
                margin-bottom: 0.5rem;
            }

            .task-title-text {
                color: #1f1f1f;
                font-weight: 600;
                font-size: 1.5rem;
                margin: 0;
                word-break: break-word;
            }

            .task-description-box {
                background: #f7f7ff;
                border-left: 4px solid #1b3666;
                padding: 1rem 1.25rem;
                border-radius: 0.35rem;
                line-height: 1.75;
                color: #444;
                min-height: 120px;
            }

            .task-info-panel {
                background-color: #1b3666;
                color: #fff;
                border-radius: 0.6rem;
                padding: 1.5rem;
                box-shadow: 0 8px 24px rgba(27, 54, 102, 0.25);
            }

            .task-info-panel h5 {
                color: #fff;
                font-weight: 700;
                letter-spacing: 0.5px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.25);
                padding-bottom: 0.75rem;
                margin-bottom: 1.25rem;
            }

            .info-item {
                padding: 0.65rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            }

            .info-item:last-of-type {
                border-bottom: none;
            }

            .info-item .info-label {
                font-size: 0.72rem;
                letter-spacing: 1px;
                text-transform: uppercase;
                color: rgba(255, 255, 255, 0.75);
                display: block;
                margin-bottom: 0.25rem;
            }

            .info-item .info-value {
                font-size: 0.95rem;
                font-weight: 600;
                color: #fff;
            }

            .status-pill {
                display: inline-block;
                padding: 0.4rem 0.85rem;
                border-radius: 50rem;
                font-size: 0.8rem;
                font-weight: 700;
                letter-spacing: 0.5px;
            }

            .status-pill.completed {
                background: #d4edda;
                color: #155724;
            }

            .status-pill.pending {
                background: #fff3cd;
                color: #856404;
            }

            .btn-edit-task {
                background: #fff;
                color: #1b3666;
                border: none;
                font-weight: 600;
                padding: 0.6rem 1rem;
                border-radius: 0.4rem;
                width: 100%;
                margin-top: 0.5rem;
                transition: all 0.2s ease;
            }



            .btn-back-tasks {
                color: gray;
            }
        </style>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card view-task-card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h4 class="card-title mb-0">
                                    <i class="fa-solid fa-clipboard-list mr-2"></i> View Task
                                </h4>
                                <a href="EmployeeTasks.php" class="btn-back-tasks">
                                    <i class="fa-solid fa-arrow-left mr-1"></i> Back to All Tasks
                                </a>
                            </div>
                            <div class="card-body">
                                <?php
                                $status = strtolower($task['status'] ?? 'pending');
                                if ($status === 'completed') {
                                    $statusClass = 'completed';
                                    $statusIcon = 'fa-circle-check';
                                    $statusLabel = 'Completed';
                                } else {
                                    $statusClass = 'pending';
                                    $statusIcon = 'fa-hourglass-half';
                                    $statusLabel = 'Pending';
                                }
                                $createdAt = !empty($task['created_at']) ? date('M d, Y · h:i A', strtotime($task['created_at'])) : '—';
                                $updatedAt = !empty($task['updated_at']) ? date('M d, Y · h:i A', strtotime($task['updated_at'])) : '—';
                                ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-4">
                                            <p class="task-section-label">
                                                <i class="fa-solid fa-heading mr-1"></i> Title
                                            </p>
                                            <h2 class="task-title-text"><?php echo htmlspecialchars($task['task_title']); ?></h2>
                                        </div>
                                        <div>
                                            <p class="task-section-label">
                                                <i class="fa-solid fa-align-left mr-1"></i> Description
                                            </p>
                                            <div class="task-description-box">
                                                <?php echo html_entity_decode($task['task_description']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="task-info-panel">
                                            <h5><i class="fa-solid fa-circle-info mr-2"></i> Task Info</h5>

                                            <div class="info-item">
                                                <span class="info-label"><i class="fa-solid fa-flag mr-1"></i> Status</span>
                                                <span class="status-pill <?php echo $statusClass; ?>">
                                                    <i class="fa-solid <?php echo $statusIcon; ?> mr-1"></i><?php echo $statusLabel; ?>
                                                </span>
                                            </div>

                                            <div class="info-item">
                                                <span class="info-label"><i class="fa-solid fa-hashtag mr-1"></i> Task ID</span>
                                                <span class="info-value">#<?php echo (int)$task['task_id']; ?></span>
                                            </div>

                                            <div class="info-item">
                                                <span class="info-label"><i class="fa-solid fa-calendar-plus mr-1"></i> Created At</span>
                                                <span class="info-value"><?php echo $createdAt; ?></span>
                                            </div>

                                            <div class="info-item">
                                                <span class="info-label"><i class="fa-solid fa-pen-to-square mr-1"></i> Last Updated</span>
                                                <span class="info-value"><?php echo $updatedAt; ?></span>
                                            </div>

                                            <a href="updateTask.php?id=<?php echo (int)$task['task_id']; ?>" class="btn-edit-task">
                                                <i class="fa-solid fa-pen mr-1"></i> Edit Task
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scripts -->
            <!-- Required vendors -->
            <script src="./vendor/global/global.min.js"></script>
            <script src="./js/quixnav-init.js"></script>
            <script src="./js/custom.min.js"></script>
            <!-- Datatable -->
            <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
            <script src="./js/plugins-init/datatables.init.js"></script>

</body>

</html>