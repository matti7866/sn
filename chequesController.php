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
if (!in_array($action, ['searchCheques', 'addCheque', 'updateCheque', 'deleteCheque', 'getCheque'])) {
  api_response(['error' => 'Invalid action']);
}


// search cheques
if ($action == 'searchCheques') {

  $search = filterInput('search');
  $type = filterInput('type');
  $account_id = filterInput('account_id');
  $startDate = filterInput('startDate');
  $endDate = filterInput('endDate');


  $where = [];

  if ($search != '') {
    $where[] = "payee LIKE '%$search%' OR number LIKE '%$search%'";
  } else {
    if ($type != '') {
      $where[] = "type = '$type'";
    }
    if ($account_id != '') {
      $where[] = "account_id = $account_id";
    }
    if ($startDate != '') {
      $where[] = "date >= '$startDate'";
    }
    if ($endDate != '') {
      $where[] = "date <= '$endDate'";
    }
  }


  $where = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';


  $stmt = $pdo->prepare("
    SELECT cheques.*, IFNULL(accounts.account_Name,'') as account 
    FROM cheques 
    LEFT JOIN accounts ON accounts.account_ID = cheques.account_id
    $where ORDER BY date DESC
    ");
  $stmt->execute();

  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($result)) {
    $html = '';
    foreach ($result as $row) {
      $html .= '<tr>';
      $html .= '<td>' . $row['id'] . '</td>';
      $html .= '<td>' . $row['date'] . '</td>';
      $html .= '<td>' . $row['number'] . '</td>';
      $html .= '<td>' . ($row['type'] == 'payable' ? '<span class="badge bg-danger">Payable</span>' : '<span class="badge bg-success">Receivable</span>') . '</td>';
      $html .= '<td>' . $row['payee'] . '</td>';
      $html .= '<td>' . ($row['type'] == 'payable' ? $row['account'] : $row['bank']) . '</td>';
      $html .= '<td>' . $row['amount'] . '</td>';
      $html .= '<td>';
      if ($row['filename'] != ''):
        $html .= '<a href="/attachment/cheques/' . $row['filename'] . '" class="btn btn-sm btn-info" target="_blank"><i class="fa fa-photo"></i></a>&nbsp;';
      endif;
      $html .= '<button class="btn btn-sm btn-primary btn-edit" data-id="' . $row['id'] . '"><i class="fa fa-edit"></i></button>&nbsp;';
      $html .= '<button class="btn btn-sm btn-danger btn-delete"  data-id="' . $row['id'] . '"><i class="fa fa-trash"></i></button>';
      $html .= '</td>';
      $html .= '</tr>';
    }

    api_response(['status' => 'success', 'html' => $html]);
  }

  api_response(['status' => 'error', 'message' => 'No records found']);
}

// addCheque
if ($action == 'addCheque') {
  $date = filterInput('dateAdd');
  $number = filterInput('numberAdd');
  $type = filterInput('typeAdd');
  $amount = filterInput('amountAdd');
  $account_id = (int)filterInput('accountIDAdd');
  $bank = filterInput('bankAdd');
  $payee = filterInput('payeeAdd');
  $amountAdd = filterInput('amountAdd');
  $amountConfirmAdd = filterInput('amountConfirmAdd');
  $filename = isset($_FILES['filename']['name']) ? $_FILES['filename'] : ['name' => ''];

  $errors = [];
  if ($date == '') {
    $errors['dateAdd'] = 'Date is required';
  }
  if ($number == '') {
    $errors['numberAdd'] = 'Number is required';
  }
  if ($type == '') {
    $errors['typeAdd'] = 'Select type';
  } else {
    if (!in_array($type, ['payable', 'receivable'])) {
      $errors['typeAdd'] = 'Invalid type';
    } else {
      if ($type == 'payable' && $account_id == '') {
        $errors['accountIDAdd'] = 'Account is required';
      } elseif ($type === 'receivable' && $bank == '') {
        $errors['bankAdd'] = 'Bank is required';
      }
    }
  }

  if ($payee == '') {
    $errors['payeeAdd'] = 'Payee is required';
  }
  if ($amount == '') {
    $errors['amountAdd'] = 'Amount is required';
  } else {
    if ($amount != $amountConfirmAdd) {
      $errors['amountConfirmAdd'] = 'Amounts do not match';
    }
  }

  if ($filename['name'] == '') {
    $errors['filename'] = 'Attachment is required';
  }


  if ($errors) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  }

  // check if check number already exists
  $stmt = $pdo->prepare("SELECT * FROM cheques WHERE number = :number");
  $stmt->bindParam(":number", $number);
  $stmt->execute();

  // fetch row
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($result)) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['numberAdd' => 'Check number already exists']]);
  }

  // upload attachment
  $tempFilename = time() . '_' . $filename['name'];
  $uploadDir = 'attachment/cheques/';
  if (!move_uploaded_file($filename['tmp_name'],  $uploadDir . $tempFilename)) {
    api_response(['status' => 'error', 'message' => 'Failed to upload attachment']);
  }

  // add cheque
  $stmt = $pdo->prepare("INSERT INTO cheques (`type`, `number`,	`date`,	payee,	amount,	bank,	account_id, filename,	created_by) VALUES (:type, :number, :date,:payee,:amount,:bank,:account_id, :filename, :created_by)");
  $stmt->bindParam(":date", $date);
  $stmt->bindParam(":number", $number);
  $stmt->bindParam(":type", $type);
  $stmt->bindParam(":amount", $amount);
  $stmt->bindParam(":account_id", $account_id);
  $stmt->bindParam(":bank", $bank);
  $stmt->bindParam(":payee", $payee);
  $stmt->bindParam(":filename", $tempFilename);
  $stmt->bindParam(":created_by", $_SESSION['user_id']);

  $status = $stmt->execute();

  if (!$status) {
    api_response(['status' => 'error', 'message' => $stmt->errorInfo() . $account_id]);
  }

  api_response(['status' => 'success', 'message' => 'Cheque added successfully']);
}


// deleteCheque
if ($action == 'deleteCheque') {
  $id = (int)filterInput('id');

  $stmt = $pdo->prepare("DELETE FROM cheques WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $status = $stmt->execute();

  if (!$status) {
    api_response(['status' => 'error', 'message' => $stmt->errorInfo()]);
  }

  api_response(['status' => 'success', 'message' => 'Cheque deleted successfully']);
}

// getCheque
if ($action == 'getCheque') {
  $id = (int)filterInput('id');

  $stmt = $pdo->prepare("SELECT * FROM cheques WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    api_response(['status' => 'error', 'message' => 'Cheque not found']);
  }

  api_response(['status' => 'success', 'data' => $result]);
}

// updateCheque
if ($action == 'updateCheque') {
  $id = (int)filterInput('idEdit');
  $date = filterInput('dateEdit');
  $number = filterInput('numberEdit');
  $type = filterInput('typeEdit');
  $amount = filterInput('amountEdit');
  $account_id = (int)filterInput('accountIDEdit');
  $bank = filterInput('bankEdit');
  $payee = filterInput('payeeEdit');
  $amountEdit = filterInput('amountEdit');
  $amountConfirmEdit = filterInput('amountConfirmEdit');
  $filename = isset($_FILES['filename']['name']) ? $_FILES['filename'] : ['name' => ''];

  $errors = [];
  if ($date == '') {
    $errors['dateEdit'] = 'Date is required';
  }
  if ($number == '') {
    $errors['numberEdit'] = 'Number is required';
  }
  if ($type == '') {
    $errors['typeEdit'] = 'Select type';
  } else {
    if (!in_array($type, ['payable', 'receivable'])) {
      $errors['typeEdit'] = 'Invalid type';
    } else {
      if ($type == 'payable' && $account_id == '') {
        $errors['accountIDEdit'] = 'Account is required';
      } elseif ($type === 'receivable' && $bank == '') {
        $errors['bankEdit'] = 'Bank is required';
      }
    }
  }

  if ($payee == '') {
    $errors['payeeEdit'] = 'Payee is required';
  }
  if ($amount == '') {
    $errors['amountEdit'] = 'Amount is required';
  } else {
    if ($amount != $amountConfirmEdit) {
      $errors['amountConfirmEdit'] = 'Amounts do not match';
    }
  }


  // load cheque
  $stmt = $pdo->prepare("SELECT * FROM cheques WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();
  $cheque = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$cheque) {
    api_response(['status' => 'error', 'message' => 'Cheque not found']);
  }

  // upload attachment
  $tempFilename = $cheque['filename'];
  if ($filename['name'] != '') {
    $tempFilename = time() . '_' . $filename['name'];
    $uploadDir = 'attachment/cheques/';
    if (!move_uploaded_file($filename['tmp_name'],  $uploadDir . $tempFilename)) {
      api_response(['status' => 'error', 'message' => 'Failed to upload attachment']);
    }
  }


  $smt = $pdo->prepare("SELECT * FROM cheques WHERE number = :number AND id != :id");
  $smt->bindParam(":number", $number);
  $smt->bindParam(":id", $id);
  $smt->execute();

  $result = $smt->fetchAll(PDO::FETCH_ASSOC);


  if (count($result)) {
    $errors['numberEdit'] = 'Check number already exists';
  }



  if ($errors) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  }

  // update cheque
  $stmt = $pdo->prepare("
    UPDATE cheques 
    SET 
      `type` = :type, 
      `number` = :number,
      `date` = :date,	
      payee = :payee,	
      amount = :amount,	
      bank = :bank,	
      account_id = :account_id,
      filename = :filename
      WHERE id = :id
    ");
  $stmt->bindParam(":id", $id);
  $stmt->bindParam(":date", $date);
  $stmt->bindParam(":number", $number);
  $stmt->bindParam(":type", $type);
  $stmt->bindParam(":amount", $amount);
  $stmt->bindParam(":account_id", $account_id);
  $stmt->bindParam(":bank", $bank);
  $stmt->bindParam(":payee", $payee);
  $stmt->bindParam(":filename", $tempFilename);
  $status = $stmt->execute();


  if (!$status) {
    api_response(['status' => 'error', 'message' => $stmt->errorInfo()]);
  }

  api_response(['status' => 'success', 'message' => 'Cheque updated successfully']);
}
