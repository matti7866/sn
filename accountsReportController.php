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



  $customerPayments = [];
  $result = $pdo->query("SELECT accountID, SUM(payment_amount) as payment_amount FROM customer_payments GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
  foreach($result as $row){
    $customerPayments[$row['accountID']] = $row['payment_amount'];
  }

  $loans = [];
  $result = $pdo->query("SELECT accountID, SUM(amount) as amount FROM loan GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
  foreach($result as $row){
    $loans[$row['accountID']] = $row['amount'];
  }

  $expenses = [];
  $result = $pdo->query("SELECT accountID, SUM(expense_amount) as expense_amount FROM expense GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
  foreach($result as $row){
    $expenses[$row['accountID']] = $row['expense_amount'];
  }

  $payments = [];
  $result = $pdo->query("SELECT accountID, SUM(payment_amount) as payment_amount FROM payment GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
  foreach($result as $row){
    $payments[$row['accountID']] = $row['payment_amount'];
  }

  $deposits = [];
  $result = $pdo->query("SELECT accountID, SUM(deposit_amount) as deposit_amount FROM deposits GROUP BY accountID")->fetchAll(\PDO::FETCH_ASSOC);
  foreach($result as $row){
    $deposits[$row['accountID']] = $row['deposit_amount'];
  }

  $servicePayments = [];
  $result = $pdo->query("
  SELECT accoundID, IFNULL(SUM(salePrice),0) as payment_amount 
  FROM servicedetails 
  WHERE DATE(service_date) >= '2024-09-10'
  GROUP BY accoundID")->fetchAll(PDO::FETCH_ASSOC);
  foreach($result as $row){
    $servicePayments[$row['accoundID']] = $row['payment_amount'];
  }
  


  $accounts = $pdo->query("
  SELECT  account_ID , account_Name, 0 as Account_Balance
  FROM accounts
  ")->fetchAll(\PDO::FETCH_ASSOC);


  $html = '';
  if( count($accounts) == 0 ){
    $html = '<tr><td colspan="3">No data found</td></tr>';
  }else{

    $total = 0;
    foreach($accounts as $account){

      $account['Account_Balance'] += isset($customerPayments[$account['account_ID']]) ? $customerPayments[$account['account_ID']] : 0;
      $account['Account_Balance'] -= isset($loans[$account['account_ID']]) ? $loans[$account['account_ID']] : 0;
      $account['Account_Balance'] -= isset($expenses[$account['account_ID']]) ? $expenses[$account['account_ID']] : 0;
      $account['Account_Balance'] -= isset($payments[$account['account_ID']]) ? $payments[$account['account_ID']] : 0;
      $account['Account_Balance'] += isset($deposits[$account['account_ID']]) ? $deposits[$account['account_ID']] : 0;
      $account['Account_Balance'] -=  isset($servicePayments[$account['account_ID']]) ? $servicePayments[$account['account_ID']] : 0;

      $total += $account['Account_Balance'];

      $html .= '<tr>';
      $html .= '<td>'.$account['account_ID'].'</td>';
      $html .= '<td>'.$account['account_Name'].'</td>';
      $html .= '<td>'.number_format($account['Account_Balance']).'</td>';
      $html .= '</tr>';
    }

    $html .= '<tr class="table-success">
      <td style="text-align:right;" colspan="2"><strong>TOTAL BALANCES: </strong></td>
      <td><strong>'.number_format($total).'</strong></td>
    </tr>';
  }

  echo $html;
