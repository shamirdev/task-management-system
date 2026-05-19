<!-- Model -->
<?php
session_start();
// Check if user click logout
include_once './logout.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: page-login.php");
    exit();
}
// DB Connection
require_once 'config/dbConnect.php';
//Sweet Alert
$alert = "";
if (isset($_SESSION['update_success'])) {
    $alert = "update_success";
    unset($_SESSION['update_success']);
} else if (isset($_SESSION['delete_success'])) {
    $alert = "delete_success";
    unset($_SESSION['delete_success']);
}

$getTasks = $conn->prepare("SELECT * FROM tasks where user_id = :user_id");
$getTasks->execute(['user_id' => $_SESSION['user_id']]);
$tasks = $getTasks->fetchAll(PDO::FETCH_ASSOC);
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

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Welcome Back! <?php echo ucfirst($_SESSION['user_name']); ?></h4>
                        </div>
                    </div>
                    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./createTask.php"></a></li>
                            <li class="breadcrumb-item active"><a href="./createTask.php">Create</a></li>
                        </ol>
                    </div>
                </div>
                <!-- row -->


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tasks</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            foreach ($tasks as $task) {
                                                $status = strtolower($task['status']);
                                                if ($status == 'completed') {
                                                    $badge = "<span class='badge bg-success text-white'>Completed</span>";
                                                } else {
                                                    $badge = "<span class='badge bg-warning text-dark'>Pending</span>";
                                                }
                                                echo "
                                                <tr>
                                                <a href='viewTask.php?id={$task['task_id']}' style='text-decoration: underline; color: #1b3666;'>
                                                        <td>{$task['task_id']}</td>
                                                        <td>{$task['task_title']}</td>  
                                                        <td style='padding-top: 32px;'>" . substr(html_entity_decode($task['task_description']), 0, 25) . "...</td>
                                                        <td>{$task['created_at']}</td>
                                                        <td>{$task['updated_at']}</td>
                                                        <td>{$badge}</td>
                                                        <td style='width: 150px;'>
                                                            <a href='updateTask.php?id={$task['task_id']}' class='btn btn-sm btn-primary'><i class='fa-solid fa-pencil'></i></a>
                                                            <a href='deleteTask.php?id={$task['task_id']}' class='btn btn-sm btn-danger btn-delete' data-name='{$task['task_title']}'><i class='fa-solid fa-trash'></i></a>
                                                            <a href='viewTask.php?id={$task['task_id']}' class='btn btn-sm btn-success'><i class='fa-solid fa-eye'></i></a>
                                                        </td>
                                                        </a>
                                                    </tr>";
                                            }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>



    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>

    <!-- Custom Js -->
    <script>
        // const completedBadges = document.querySelectorAll('.completedBadge');
        // const pendingBadges = document.querySelectorAll('.pendingBadge');
        let alert = "<?php echo $alert; ?>"
        if (alert === "update_success") {
            Swal.fire({
                icon: 'success',
                title: 'Task Updated Successfully',
                showConfirmButton: false,
                timer: 1500
            });
        } else if (alert === "delete_success") {
            Swal.fire({
                icon: 'success',
                title: 'Task Deleted Successfully',
                showConfirmButton: false,
                timer: 1500
            });
        }
        // Select all buttons with the 'btn-delete' class
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Stop the link from redirecting immediately

                const url = this.getAttribute('href');
                const taskName = this.getAttribute('data-name');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to delete: " + taskName,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, Delete It!',
                    cancelButtonText: 'No, Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If the user clicks "Yes", redirect to deleteTask.php
                        window.location.href = url;
                    }
                });
            });
        });
    </script>
    </script>

</body>

</html>