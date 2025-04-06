<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'pending Tasks' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    $insert = $records[0]['insert'];
if(isset($_POST['INSERT'])){
        try{
           if($insert == 1) {
            // formating the date to be aaceptable by SQL
            $task_date  = SqlFormatDate($_POST['Task_Date']);
            // create prepared statement\
            $sql = "INSERT INTO `pending_tasks`(`task_name`, `task_description`, `AssigedTo`, `AssignedBy`, 
            `status`, `task_date`) VALUES (:task_title,:task_description,:assignTo,:assignBy,:status,
            :task_date)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':task_title', $_POST['Task_Title']);
            $stmt->bindParam(':task_description', $_POST['Task_Description']);
            $stmt->bindParam(':assignTo', $_POST['Assignee']);
            $stmt->bindParam(':assignBy', $_SESSION['user_id']);
            $stmt->bindParam(':status', $_POST['Status']);
            $stmt->bindParam(':task_date', $task_date);
            // execute the prepared statement
            $stmt->execute();
            echo "Success";
            }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['Select_Employee'])){
        $selectQuery = $pdo->prepare("SELECT * FROM staff ORDER BY staff_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $employee = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($employee);
    }else if(isset($_POST['GetPendingTasks'])){
        try {
            if($select == 1) {
                $selectQuery = $pdo->prepare("SELECT task_id, task_name, task_description, AssigedTo.staff_name AssigedTo, 
                AssignedBy.staff_name as AssignedBy, DATE_FORMAT(task_date, '%d-%b-%Y') as task_date, 'Pending' as status, 
                ABS(task_date - CURRENT_DATE()) as daysRemining, CASE WHEN task_date >= CURRENT_DATE() THEN 'day(s) remaining' ELSE 
                'day(s) passed' END AS 'dateStatus' FROM pending_tasks INNER JOIN staff as AssigedTo ON AssigedTo.staff_id = 
                pending_tasks.AssigedTo INNER JOIN staff as AssignedBy ON AssignedBy.staff_id = pending_tasks.AssignedBy WHERE
                 pending_tasks.status = 1");
                // execute query
                $selectQuery->execute();
                /* Fetch all of the remaining rows in the result set */
                $reminders = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
                // encoding array to json format
                echo json_encode($reminders);
            }
        } catch (PDOException $e) {
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    else if(isset($_POST['CompleteTask'])){
        try{
            $sql = "UPDATE `pending_tasks` SET `status` = 0,`is_completedBy` = :staff_id WHERE `task_id` = :task_id  ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $stmt->bindParam(':task_id', $_POST['ID']);
            $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    function SqlFormatDate($date){
        // first we split the date base on the - sign
        $splitedDate = explode("-", $date);
        // then we change the string order to reverse order like 2022-09-01
        return $splitedDate[2] . '-' .  $splitedDate[1] . '-' .  $splitedDate[0];;
    }
    // Close connection
    unset($pdo); 
?>
