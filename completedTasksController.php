<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetCompletedTasks'])){
        try {
                $selectQuery = $pdo->prepare("SELECT task_id, task_name, task_description, AssigedTo.staff_name AssigedTo, 
                AssignedBy.staff_name as AssignedBy, DATE_FORMAT(task_date, '%d-%b-%Y') as task_date, 'Completed' as status, 
                is_completedBy.staff_name as completedBy FROM pending_tasks INNER JOIN staff as AssigedTo ON AssigedTo.staff_id = 
                pending_tasks.AssigedTo INNER JOIN staff as AssignedBy ON AssignedBy.staff_id = pending_tasks.AssignedBy INNER JOIN
                staff as is_completedBy ON is_completedBy.staff_id = pending_tasks.is_completedBy WHERE
                 pending_tasks.status = 0");
                // execute query
                $selectQuery->execute();
                /* Fetch all of the remaining rows in the result set */
                $reminders = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
                // encoding array to json format
                echo json_encode($reminders);
        } catch (PDOException $e) {
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    else if(isset($_POST['TaskUndo'])){
        try{
            $sql = "UPDATE `pending_tasks` SET `status` = 1,`is_completedBy` = :staff_id WHERE `task_id` = :task_id  ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $stmt->bindParam(':task_id', $_POST['ID']);
            $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>
