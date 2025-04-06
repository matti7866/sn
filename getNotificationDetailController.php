<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetNotificationDetail'])){
            $selectQuery = $pdo->prepare("SELECT * FROM notification WHERE notification_id = :notificationID");
            $selectQuery->bindParam(':notificationID', $_POST['ID']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $GetNotificationInfo = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($GetNotificationInfo);
    }
    // Close connection
    unset($pdo); 
?>