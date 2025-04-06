<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Withdrawal' ";
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
    if(isset($_POST['AddSalary'])){
        try{
            if($insert == 1){
                $sql = "INSERT INTO `withdrawals`(`withdrawal_amount`,currencyID,`withdrawalBy`, `accountID`, `remarks`) 
                VALUES(:withdrawal_amount,:currencyID,:withdrawalBy,:accountID,:remarks)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':withdrawal_amount', $_POST['Withdrawal_Amount']);
                $stmt->bindParam(':currencyID', $_POST['Currency_Type']);
                $stmt->bindParam(':withdrawalBy',  $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Addaccount_ID']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            } 
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetSalaryReport'])){
        if($_POST['SearchTerm'] == 'DateAndAccWise'){
            $selectQuery = $pdo->prepare("SELECT `withdrawal_ID`, `withdrawal_amount`,currencyName, `datetime`, staff_name ,
            account_Name,`remarks` FROM `withdrawals` INNER JOIN staff ON staff.staff_id = withdrawals.withdrawalBy INNER JOIN 
            accounts ON accounts.account_ID = withdrawals.accountID INNER JOIN currency ON currency.currencyID = withdrawals.currencyID
            WHERE DATE(datetime) BETWEEN :fromdate  AND :todate AND withdrawals.accountID = :account_id  ORDER BY datetime DESC ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':account_id', $_POST['Searchaccount_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT `withdrawal_ID`, `withdrawal_amount`,currencyName, `datetime`, staff_name ,
            account_Name,`remarks` FROM `withdrawals` INNER JOIN staff ON staff.staff_id = withdrawals.withdrawalBy INNER JOIN accounts ON 
            accounts.account_ID = withdrawals.accountID INNER JOIN currency ON currency.currencyID = withdrawals.currencyID 
            WHERE DATE(datetime) BETWEEN :fromdate  AND :todate ORDER BY datetime DESC");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'AccWise'){
            $selectQuery = $pdo->prepare("SELECT `withdrawal_ID`, `withdrawal_amount`,currencyName, `datetime`, staff_name ,
            account_Name,`remarks` FROM `withdrawals` INNER JOIN staff ON staff.staff_id = withdrawals.withdrawalBy INNER JOIN accounts ON 
            accounts.account_ID = withdrawals.accountID INNER JOIN currency ON currency.currencyID = withdrawals.currencyID
             WHERE withdrawals.accountID = :account_id  ORDER BY datetime DESC");
            $selectQuery->bindParam(':account_id', $_POST['Searchaccount_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $visa = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($visa);
    }else if(isset($_POST['GetTotal'])){
        if($_POST['SearchTerm'] == 'DateAndAccWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(withdrawal_amount) AS amount,currency.currencyName FROM 
            `withdrawals` INNER JOIN currency ON currency.currencyID = withdrawals.currencyID WHERE withdrawals.accountID = :accountID
            AND DATE(datetime) BETWEEN  :fromdate AND :todate   GROUP BY currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':accountID', $_POST['SearchAccount_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(withdrawal_amount) AS amount,currency.currencyName FROM 
            `withdrawals` INNER JOIN currency ON currency.currencyID = withdrawals.currencyID WHERE DATE(datetime) BETWEEN 
            :fromdate AND :todate   GROUP BY currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'AccWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(withdrawal_amount) AS amount,currency.currencyName FROM 
            `withdrawals` INNER JOIN currency ON currency.currencyID = withdrawals.currencyID WHERE withdrawals.accountID = :accountID
            GROUP BY currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':accountID', $_POST['SearchAccount_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $total = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($total);
    }else if(isset($_POST['Delete'])){
        try{
                if($delete == 1){
                        $sql = "DELETE FROM withdrawals WHERE withdrawal_ID = :withdrawal_ID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':withdrawal_ID', $_POST['ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM withdrawals WHERE withdrawal_ID = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['UpdSalary'])){
        try{
                if($update == 1){
                    $sql = "UPDATE `withdrawals` SET withdrawal_amount = :withdrawal_amount,currencyID = :currencyID,withdrawalBy=:withdrawalBy
                    ,accountID= :accountID ,remarks = :remarks WHERE withdrawal_ID = :withdrawal_ID";
                    // create prepared statement
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':withdrawal_amount', $_POST['Updwithdrawal_Amount']);
                    $stmt->bindParam(':currencyID', $_POST['UpdCurrency_Type']);
                    $stmt->bindParam(':withdrawalBy', $_SESSION['user_id']);
                    $stmt->bindParam(':accountID', $_POST['Updaccount_ID']);
                    $stmt->bindParam(':remarks', $_POST['Updremarks']);
                    $stmt->bindParam(':withdrawal_ID', $_POST['WithdrawalID']);
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