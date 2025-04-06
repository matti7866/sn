<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetCalculation'])){
        if($_POST['Type'] == "DateWise"){
            $selectQuery = $pdo->prepare("SELECT DATEDIFF(:todate,:fromdate) AS diff");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $GetEmployeeInfo = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($GetEmployeeInfo);
        }else if($_POST['Type'] == "daysWise"){
            $selectQuery = $pdo->prepare("SELECT DATE_ADD(:from_date,INTERVAL :days DAY) AS diff");
            $selectQuery->bindParam(':from_date', $_POST['From_Date']);
            $selectQuery->bindParam(':days', $_POST['Days']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $GetEmployeeInfo = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($GetEmployeeInfo);
        }
    }
    // Close connection
    unset($pdo); 
?>