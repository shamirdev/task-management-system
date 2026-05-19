<?php 
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: page-login.php");
    exit();
}
if(isset($_POST['logout'])) {
session_destroy();
header("Location: page-login.php");
exit();
}
?>