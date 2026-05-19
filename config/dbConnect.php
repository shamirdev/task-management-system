<?php    
    $host = "localhost";
    $dbname = "task_management_system";
    $username = "root";
    $password = "";

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); 
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($conn){
            // echo "Connected successfully<br/>";sbhbhffbhbhf
        }
        
        // Only proceed if connection was successful
        $result = $conn->query("SHOW TABLES");
        
        if (!$result) {
            echo "Query failed!";
        }
        // else {
        //     while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        //         print_r($row);
        //         echo "<br/>";
        //     }
        // }
        
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
?>