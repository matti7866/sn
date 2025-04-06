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
    if(isset($_POST['GetSalaryReport'])){
        if($_POST['SearchTerm'] == 'DateAndAccWise'){
            $selectQuery = $pdo->prepare("SELECT accounts.account_ID, accounts.account_Name,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM `customer_payments` WHERE DATE(customer_payments.datetime)
            BETWEEN :fromdate AND :todate AND customer_payments.accountID = :account_id) - (SELECT 
            IFNULL(SUM(loan.amount),0) FROM loan WHERE DATE(loan.datetime) BETWEEN :fromdate AND :todate AND 
            loan.accountID = :account_id ) - (SELECT IFNULL(SUM(expense.expense_amount),0) FROM expense WHERE 
            DATE(expense.time_creation) BETWEEN :fromdate AND :todate AND expense.accountID = :account_id) - (SELECT 
            IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE DATE(payment.time_creation) BETWEEN :fromdate AND 
            :todate AND payment.accountID = :account_id ) + (SELECT IFNULL(SUM(deposits.deposit_amount),0) FROM deposits 
            WHERE DATE(deposits.datetime) BETWEEN :fromdate AND :todate AND deposits.accountID =:account_id) AS 
            Account_Balance FROM accounts WHERE accounts.account_ID = :account_id");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':account_id', $_POST['Searchaccount_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT accounts.account_ID, accounts.account_Name,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM `customer_payments` WHERE DATE(customer_payments.datetime)
            BETWEEN :fromdate AND :todate AND customer_payments.accountID = account_ID) - (SELECT 
            IFNULL(SUM(loan.amount),0) FROM loan WHERE DATE(loan.datetime) BETWEEN :fromdate AND :todate AND 
            loan.accountID = account_ID ) - (SELECT IFNULL(SUM(expense.expense_amount),0) FROM expense WHERE 
            DATE(expense.time_creation) BETWEEN :fromdate AND :todate AND expense.accountID = account_ID) - (SELECT 
            IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE DATE(payment.time_creation) BETWEEN :fromdate AND 
            :todate AND payment.accountID = account_ID) + (SELECT IFNULL(SUM(deposits.deposit_amount),0) FROM deposits 
            WHERE DATE(deposits.datetime) BETWEEN :fromdate AND :todate AND deposits.accountID =account_ID) AS 
            Account_Balance FROM accounts GROUP BY accounts.account_ID ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'AccWise'){
            $selectQuery = $pdo->prepare("SELECT accounts.account_ID, accounts.account_Name,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM `customer_payments` WHERE customer_payments.accountID = 
            accounts.account_ID) - (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE  loan.accountID = account_ID ) -  
            (SELECT IFNULL(SUM(expense.expense_amount),0) FROM expense  WHERE expense.accountID = account_ID) - (SELECT 
            IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.accountID = account_ID) + (SELECT 
            IFNULL(SUM(deposits.deposit_amount),0) FROM deposits WHERE deposits.accountID =account_ID ) AS 
            Account_Balance FROM accounts WHERE accounts.account_ID = :account_id");
            $selectQuery->bindParam(':account_id', $_POST['Searchaccount_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $visa = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($visa);
    }
    // Close connection
    unset($pdo); 
?>