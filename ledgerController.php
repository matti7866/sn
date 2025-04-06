<?php
session_start();

include 'connection.php';
// check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
  header('location:login.php');
}


function api_response($data)
{
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

function filterInput($name)
{
  return htmlspecialchars(stripslashes(trim(isset($_POST[$name]) ? $_POST[$name] : '')));
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// check if invalid action
if (!in_array($action, ['addCustomerPayment'])) {
  api_response(['error' => 'Invalid action']);
}



// add customer payment
if ($action == 'addCustomerPayment') {


  $customerId = filterInput('customerId');
  $account = filterInput('account');
  $remarks = filterInput('remarks');
  $amount = filterInput('amount');
  $confirmAmount = filterInput('confirmAmount');

  $errors = [];
  if ($customerId == '') {
    $errors['customerId'] = 'Customer is required';
  }
  if ($account == '') {
    $errors['account'] = 'Account is required';
  } else {
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE account_ID = :account");
    $stmt->bindParam(':account', $account);
    $stmt->execute();
    $acc = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$acc) {
      $errors['account'] = 'Invalid account';
    }
  }
  if ($amount == '') {
    $errors['amount'] = 'Amount is required';
  }
  if ($confirmAmount == '') {
    $errors['confirmAmount'] = 'Confirm amount is required';
  } else {
    if ($amount != $confirmAmount) {
      $errors['confirmAmount'] = 'Amount does not match';
    }
  }
  if ($remarks == '') {
    $errors['remarks'] = 'Remarks is required';
  }

  if (count($errors) > 0) {
    api_response(['errors' => $errors, 'status' => 'error', 'message' => 'form_errors']);
  }


  $sql = "
    INSERT INTO 
    `customer_payments` (`customer_id`, `payment_amount`,`currencyID`, `staff_id`, accountID, remarks) 
    VALUES (:customer_id, :payment_amount,:currencyID, :staff_id, :accountID, :remarks)
    ";
  $stmt = $pdo->prepare($sql);



  $stmt->bindParam(':customer_id', $customerId);
  $stmt->bindParam(':payment_amount', $amount);
  $stmt->bindParam(':currencyID', $acc['curID']);
  $stmt->bindParam(':staff_id', $_SESSION['user_id']);
  $stmt->bindParam(':accountID',  $account);
  $stmt->bindParam(':remarks', $remarks);
  $stmt->execute();



  // if not success
  if (!$stmt->rowCount()) {
    api_response(['status' => 'error', 'message' => 'Failed to add payment']);
  }

  api_response(['status' => 'success', 'message' => 'Payment added successfully']);
}
