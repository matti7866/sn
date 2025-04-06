<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetEmployeeInfo'])){
            $selectQuery = $pdo->prepare("SELECT `staff_name`, `staff_pic` FROM `staff` WHERE staff_id = :StaffID");
            $selectQuery->bindParam(':StaffID', $_SESSION['user_id']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $GetEmployeeInfo = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($GetEmployeeInfo);
    }else if(isset($_POST['ChangePassword'])){
        try{
                         $sql = "UPDATE `staff` SET `Password`=:Password
                         WHERE staff_id =:staff_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':Password', $_POST['Password']);
                         $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                         $stmt->execute();
                echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>