<!-- Model -->
<?php
session_start();
require_once './config/dbConnect.php';

if (!isset($_SESSION['logged_in'])) {
    header("Location: page-login.php");
    exit();
} else if ($_SESSION['user_role'] == "admin") {
    header("Location: page-error-403.php");
} 
$taskId = $_GET['id'] ?? null;
$taskId = (int)$taskId;
if ($_SESSION['logged_in'] && $taskId) {
    try {
        $deleteStmt = $conn->prepare("DELETE FROM tasks WHERE task_id = :id AND user_id = :user_id");
        $deleteStmt->execute([':id' => $taskId, ':user_id' => $_SESSION['user_id']]);
        $_SESSION['delete_success'] = true;
        header("Location: employeeTasks.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!-- View -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>