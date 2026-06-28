<!-- Model -->
<?php
// DB Connection
require_once './config/dbConnect.php';
include_once './logout.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: page-login.php");
    exit();
} else if ($_SESSION['user_role'] == "employee") {
    header("Location: page-error-403.php");
} 
// Sweet Alert
$alert = "";
try{
    $getEmployees = $conn->prepare("SELECT user_id, full_name FROM users WHERE role = 'employee'");
    $getEmployees->execute();
    $employees = $getEmployees->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (isset($_POST['create_task'])) {
    $task_title = trim(htmlspecialchars($_POST['task_title']));
    $task_description = trim(htmlspecialchars($_POST['task_description']));
    
    if (empty($task_title) && empty($task_description)) {
        $alert = "both_empty";
    } else if (empty($task_title)) {
        $alert = "task_title_empty";
    } else if (empty($task_description)) {
        $alert = "task_description_empty";
    } else {
    if ($_SESSION['user_id']) {
        try {
            $postStmt = $conn->prepare("INSERT INTO tasks (user_id, task_title, task_description) VALUES (:user_id, :task_title, :task_description)");
            $postStmt->execute([
                "user_id" => $_SESSION['user_id'],
                "task_title" => $task_title,
                "task_description" => $task_description
            ]);
            $alert = "success";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
        <!--Sidebar end-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <div class="row page-titles mt-2 w-100 ml-1">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4>Create Your Task!</h4>
                            <p class="mb-0"><?php echo isset($_SESSION['logged_in']) ? $_SESSION['user_name'] : ''; ?></p>
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
                                <h4 class="card-title">Enter Task Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="adminCreateTask.php" method="POST">
                                    <div class="form-group d-flex">
                                        <input class="form-control form-control-lg w-75" type="text" name="task_title" placeholder="Task Title">
                                        <select name="status" id="status" class="form-control form-control-lg w-25 ml-2">
                                            <option value="" disabled>Select Employee</option>
                                            <option value="pending" <?php echo (($task['status'] ?? '') === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="completed" <?php echo (($task['status'] ?? '') === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                    </div>
                                    <textarea class="form-control form-control-lg" name="task_description" id="task_description" class="form-control"></textarea>
                                    <button name="create_task" type="submit" class="btn btn-primary mt-2 float-right">Create Task</button>
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
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <!-- <div class="footer">
            <div class="copyright">
                <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p>
            </div>
        </div> -->
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


    <!-- Summernote -->
    <!-- <script src="./vendor/summernote/js/summernote.min.js"></script> -->
    <!-- Summernote init -->
    <!-- <script src="./js/plugins-init/summernote-init.js"></script> -->

    <!-- jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- Summernote JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script> -->

    <!-- js for summernote -->
    <!-- <script>
    $(document).ready(function() {
    $('#task_description').summernote({
      height: 300,       // editor height
      placeholder: 'Task Description', 
      toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
    //   ['font', ['strikethrough']],   // no fontname here
      ['para', ['ul', 'ol', 'paragraph']],
    //   ['insert', ['link', 'picture']],
      ['view', ['fullscreen', 'codeview']]
    ]
    });
  });
</script> -->

    <script>
        ClassicEditor
            .create(document.querySelector('#task_description'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', '|',
                        'underline', 'strikethrough', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', 'blockQuote', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: false
                },
                placeholder: 'Task Description'
            })
            .catch(error => (console.error(error + "CkEditor Not Working Properly")));
    </script>
    <script>
        // Fire Sweet Alert
        let alert = "<?php echo $alert ?>";
        if (alert === "task_title_empty") {
            Swal.fire({
                icon: 'error',
                title: 'Task Title is required',
                text: 'Please enter a task title.',
            });
        } else if (alert === "task_description_empty") {
            Swal.fire({
                icon: 'error',
                title: 'Task Description is required',
                text: 'Please enter a task description.',
            });
        } else if (alert === "both_empty") {
            Swal.fire({
                icon: 'error',
                title: 'Task Title and Description are required',
                text: 'Please enter both task title and description.',
            });
        } else if (alert === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Task Created Successfully',
                text: 'Your task has been created.',
            });
        }
    </script>
</body>

</html>