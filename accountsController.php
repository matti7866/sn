<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
    if(isset($_POST['Insert_CountryName'])){
        try{
            if($insert == 1){
                $sql = "INSERT INTO `accounts`(`account_Name`, `accountNum`, `accountType`) VALUES(:account_Name,
                :accountNum,:accountType)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':account_Name', $_POST['account_name']);
                $stmt->bindParam(':accountNum', $_POST['account_number']);
                $stmt->bindParam(':accountType', $_POST['accountType']);
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            } 
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetAccountsReport'])){
            $selectQuery = $pdo->prepare("SELECT `account_ID`, `account_Name`, CASE WHEN `accountNum` THEN accountNum ELSE
            '' END AS accountNum, CASE WHEN accountType = 1 THEN 'Personal' WHEN accountType = 2 THEN 'Bussiness'  
            WHEN accountType = 3 THEN 'Cash' END AS accountType FROM `accounts` ORDER BY account_Name ASC");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
                if($delete == 1){
                        $sql = "DELETE FROM accounts WHERE account_ID = :account_ID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':account_ID', $_POST['ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM accounts WHERE account_ID = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_CountryName'])){
        try{
                if($update == 1){
                    $sql = "UPDATE `accounts` SET account_Name = :account_Name,accountNum=:accountNum,accountType= :accountType
                    WHERE account_ID = :account_ID";
                    // create prepared statement
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':account_Name', $_POST['updaccount_name']);
                    $stmt->bindParam(':accountNum', $_POST['updaccount_number']);
                    $stmt->bindParam(':accountType', $_POST['updaccountType']);
                    $stmt->bindParam(':account_ID', $_POST['accountID']);
                    // execute the prepared statement
                    $stmt->execute();
                    echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>