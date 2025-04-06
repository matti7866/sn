<?php 

session_start();
include 'connection.php';
// check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])){
  header('location:login.php');
}
// load the permission for the user
$rolId = $_SESSION['role_id'];
$result = $pdo->prepare("SELECT * FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ");
$result->bindParam(':role_id', $rolId);
$result->execute();
$permission = $result->fetch(\PDO::FETCH_ASSOC);

if( $permission['select'] != 1 ){
  header("Location: /pageNotFound.php");
}


$id = isset($_POST['id']) ? $_POST['id'] : '';

if( $id == '' ){
  $output = array('status' => 'error', 'message' => 'Account ID is required');
}else{

  $account = $pdo->prepare("SELECT account_ID , account_Name FROM accounts WHERE account_ID = :account_ID ");
  $account->bindParam(':account_ID', $id);
  $account->execute();

  $account = $account->fetch(\PDO::FETCH_ASSOC);

  // check if account exists
  if( !$account ){
    $output = array('status' => 'error', 'message' => 'Account not found');
  }else{


    $customerPayments = 0;
    $result = $pdo->query("SELECT accountID, SUM(payment_amount) as payment_amount FROM customer_payments WHERE accountID = {$id} GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
    foreach($result as $row){
      $customerPayments = $row['payment_amount'];
    }

    $loans = 0;
    $result = $pdo->query("SELECT accountID, SUM(amount) as amount FROM loan  WHERE accountID = {$id} GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
    foreach($result as $row){
      $loans = $row['amount'];
    }

    $expenses = 0;
    $result = $pdo->query("SELECT accountID, SUM(expense_amount) as expense_amount FROM expense  WHERE accountID = {$id} GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
    foreach($result as $row){
      $expenses = $row['expense_amount'];
    }

    $payments = 0;
    $result = $pdo->query("SELECT accountID, SUM(payment_amount) as payment_amount FROM payment  WHERE accountID = {$id} GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
    foreach($result as $row){
      $payments = $row['payment_amount'];
    }

    $deposits = 0;
    $result = $pdo->query("SELECT accountID, SUM(deposit_amount) as deposit_amount FROM deposits  WHERE accountID = {$id} GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
    foreach($result as $row){
      $deposits = $row['deposit_amount'];
    }

    $account['Account_Balance'] = 0;

    $account['Account_Balance'] += $customerPayments;
    $account['Account_Balance'] -= $loans;
    $account['Account_Balance'] -= $expenses;
    $account['Account_Balance'] -= $payments;
    $account['Account_Balance'] += $deposits;

    $account['Account_Balance'] = number_format($account['Account_Balance']);

    $output = array('status' => 'success', 'account' => $account);

  }

  

}
header("Content-type: application/json");
echo json_encode($output);