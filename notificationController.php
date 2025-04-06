<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
      include 'connection.php';
//     $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Reminder' ";
// $stmt = $pdo->prepare($sql);
// $stmt->bindParam(':role_id', $_SESSION['role_id']);
// $stmt->execute();
// $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
// $select = $records[0]['select'];
// $update = $records[0]['update'];
// $delete = $records[0]['delete'];
// $insert = $records[0]['insert'];
// if($select == 0){
//   echo "<script>window.location.href='pageNotFound.php'</script>";
// }
if(isset($_POST['INSERT'])){
        try{
            // First of all, let's begin a transaction
            $pdo->beginTransaction();
        //    if($insert == 1) {
                  // create prepared statement
            $sql = "INSERT INTO `notification`(`notification_subject`, `notification_description`,`notification_setBy`)
            VALUES(:notification_subject,:notification_description,:notification_setBy)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':notification_subject', $_POST['Notification_subject']);
            $stmt->bindParam(':notification_description', $_POST['Notification_Description']);
            $stmt->bindParam(':notification_setBy', $_SESSION['user_id']);
            // execute the prepared statement
            $stmt->execute();
            $id = $pdo->lastInsertId();
            if($_POST['Employees_ID'] == "-1"){
                $selectQuery = $pdo->prepare("SELECT staff_id FROM staff");
                $selectQuery->execute();
                /* Fetch all of the remaining rows in the result set */
                $employees = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
                foreach($employees as $row) {
                    $staffid = $row['staff_id'];
                    $sql1 = "INSERT INTO `notification_analysis`(`notificationID`, `employee_ID`)
                    VALUES(:notificationID,:employee_ID)";
                    $stmt1 = $pdo->prepare($sql1);
                    // bind parameters to statement
                    $stmt1->bindParam(':notificationID', $id);
                    $stmt1->bindParam(':employee_ID', $staffid);
                    // execute the prepared statement
                    $stmt1->execute();
                 }
            }else{
                $sql1 = "INSERT INTO `notification_analysis`(`notificationID`, `employee_ID`)
                VALUES(:notificationID,:employee_ID)";
                $stmt1 = $pdo->prepare($sql1);
                // bind parameters to statement
                $stmt1->bindParam(':notificationID', $id);
                $stmt1->bindParam(':employee_ID', $_POST['Employees_ID']);
                // execute the prepared statement
                $stmt1->execute();
            }
            $pdo->commit();
            require __DIR__ . '/vendor/autoload.php';
            $options = array(
                'cluster' => 'ap3',
                'useTLS' => true
            );
            $pusher = new Pusher\Pusher(
                'dc1fea2910912ae4812a',
                'c5757843212487df6e93',
                '1268079',
                $options
            );
            $data['message'] = $_POST['Employees_ID'];
            $pusher->trigger('my-channel', 'my-event', $data); 
           // echo "Success";
            /* }else{
                     echo "<script>window.location.href='pageNotFound.php'</script>";
            } */

        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetEmployees'])){
        $selectQuery = $pdo->prepare("SELECT staff_id, staff_name FROM staff ORDER BY staff_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $employees = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($employees);
    }else if(isset($_POST['GetReminders'])){
        if($_POST['Start'] == 1){
        
            $selectQuery = $pdo->prepare("SELECT  *,(SELECT IFNULL(COUNT(reminder_id),0) FROM reminder WHERE reminderSetBy =
            :reminderSetBy) AS TotalRecord FROM `reminder` WHERE reminderSetBy = :reminderSetBy ORDER BY reminder_id DESC 
            LIMIT ".  $_POST['End'] . " ");
            $selectQuery->bindParam(':reminderSetBy', $_SESSION['user_id']);
        }else{
            $selectQuery = $pdo->prepare("SELECT  *,(SELECT IFNULL(COUNT(reminder_id),0) FROM reminder WHERE reminderSetBy 
            = :reminderSetBy) AS TotalRecord FROM `reminder` WHERE reminderSetBy = :reminderSetBy ORDER BY reminder_id 
            DESC LIMIT ". $_POST['Start'] . "," . $_POST['End']);
            $selectQuery->bindParam(':reminderSetBy', $_SESSION['user_id']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $reminders = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($reminders);
    }
    // Close connection
    unset($pdo); 
?>
