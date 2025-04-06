<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['Insert_CountryName'])){
        try{
                $sql = "INSERT INTO `expense_type`(`expense_type`) VALUES(:expense_type)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':expense_type', $_POST['expense_type']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetExpensesTypeReport'])){
            $selectQuery = $pdo->prepare("SELECT * FROM expense_type ORDER BY expense_type ASC");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
                        $sql = "DELETE FROM expense_type WHERE expense_type_id = :expense_type_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':expense_type_id', $_POST['ID']);
                        $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM expense_type WHERE expense_type_id = :expense_type_id");
        $selectQuery->bindParam(':expense_type_id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_CountryName'])){
        try{
                $sql = "UPDATE `expense_type` SET expense_type = :expense_type WHERE expense_type_id = :expense_type_id";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':expense_type', $_POST['updexpense_type']);
                $stmt->bindParam(':expense_type_id', $_POST['expenseTID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>