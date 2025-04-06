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
if (!in_array($action, ['searchAgents', 'addAgent', 'getAgent', 'updateAgent', 'deleteAgent'])) {
  api_response(['error' => 'Invalid action']);
}

if ($action == 'searchAgents') {

  $search = filterInput('search');
  $status = filterInput('status');

  $where = "";

  if ($search != '') {
    $where .= "  AND (company LIKE '%{$search}%' OR email LIKE '%{$search}%') ";
  }
  if ($status != '') {
    $where .= "  AND status = {$status} ";
  }

  $req = $pdo->prepare("
  SELECT agents.*, customer.customer_name 
  FROM agents 
  LEFT JOIN customer ON customer.customer_id = agents.customer_id
  WHERE 1=1 AND deleted = 0 {$where}
  ");
  $req->execute();

  if ($req->rowCount() == 0) {
    api_response(['status' => 'error', 'message' => 'No agents found']);
  }

  $agents = $req->fetchAll();

  $html = '';
  foreach ($agents as $agent) {
    $html .= '
    <tr>
      <td>' . $agent['id'] . '</td>
      <td><a target="_blank" href="viewAgent.php?id=' . $agent['id'] . '"><strong>' . $agent['company'] . '</strong></a><br />Customer: ' . $agent['customer_name'] . '</td>
      <td>' . $agent['email'] . '</td>
      <td>' . ($agent['status'] == 1 ? 'Active' : 'Inactive') . '</td>
      <td>
        <button class="btn btn-sm btn-primary btn-edit" data-id="' . $agent['id'] . '" >Edit</button>
        <button class="btn btn-sm btn-danger btn-delete" data-id="' . $agent['id'] . '" >Delete</button>
      </td>
    </tr>
    ';
  }

  api_response(['status' => 'success', 'message' => 'Agents found', 'html' => $html]);
}

if ($action == 'addAgent') {
  $company = filterInput('companyAdd');
  $customer = filterInput('customerAdd');
  $email = filterInput('emailAdd');

  $errors = [];
  if ($company == '') {
    $errors['companyAdd'] = 'Company name is required';
  }
  if ($customer == '') {
    $errors['customerAdd'] = 'Customer name is required';
  }
  if ($email == '') {
    $errors['emailAdd'] = 'Email is required';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['emailAdd'] = 'Invalid email';
  }


  if (count($errors) > 0) {
    api_response(['errors' => $errors, 'status' => 'errors', 'message' => 'form_errors']);
  }


  // check if email already exists
  $req = $pdo->prepare("SELECT * FROM agents WHERE LCASE(email) = :email");
  $req->execute(['email' => strtolower($email)]);
  $agent = $req->fetch();

  if ($agent) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['emailAdd' => 'Email already exists']]);
  }

  // check this customer id already linked with agent
  $req = $pdo->prepare("SELECT * FROM agents WHERE customer_id = :customer_id");
  $req->execute(['customer_id' => $customer]);
  $agent = $req->fetch();

  if ($agent) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['customerAdd' => 'Customer already linked with agent']]);
  }

  // generate random password
  $password = bin2hex(random_bytes(4));
  $status = 1;

  $req = $pdo->prepare("
  INSERT INTO agents 
  (company, customer_id, email, password, status, added_by) 
  VALUES (:company, :customer_id, :email, :password , :status , :added_by)
  ");

  $passwordEncypted = md5(md5($password . "sntravels123"));

  $req->bindParam('company', $company);
  $req->bindParam('customer_id', $customer);
  $req->bindParam('email', $email);
  $req->bindParam('password', $passwordEncypted);
  $req->bindParam('status', $status);
  $req->bindParam('added_by', $_SESSION['user_id']);
  $req->execute();

  // check if agent added successfully
  if ($req->rowCount() == 0) {
    $lastError = $req->errorInfo();
    api_response(['status' => 'error', 'message' => $lastError]);
  }

  api_response(['status' => 'success', 'message' => 'Agent added successfully']);
}


// getAgent
if ($action == 'getAgent') {
  $id = (int)filterInput('id');

  $stmt = $pdo->prepare("SELECT * FROM agents WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    api_response(['status' => 'error', 'message' => 'Agent not found']);
  }

  api_response(['status' => 'success', 'data' => $result]);
}


// updateAgent
if ($action == "updateAgent") {
  $id = (int)filterInput('idEdit');
  $company = filterInput('companyEdit');
  $customer = filterInput('customerEdit');
  $email = filterInput('emailEdit');

  $errors = [];
  if ($company == '') {
    $errors['companyEdit'] = 'Company name is required';
  }
  if ($customer == '') {
    $errors['customerEdit'] = 'Customer name is required';
  }
  if ($email == '') {
    $errors['emailEdit'] = 'Email is required';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['emailEdit'] = 'Invalid email';
  }

  if (count($errors) > 0) {
    api_response(['errors' => $errors, 'status' => 'errors', 'message' => 'form_errors']);
  }

  // check if email already exists
  $req = $pdo->prepare("SELECT * FROM agents WHERE LCASE(email) = :email AND id != :id");
  $req->execute(['email' => strtolower($email), 'id' => $id]);
  $agent = $req->fetch(PDO::FETCH_OBJ);



  if ($agent) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['emailEdit' => 'Email already exists']]);
  }

  // check this customer id already linked with agent
  $req = $pdo->prepare("SELECT * FROM agents WHERE customer_id = :customer_id AND id != :id");
  $req->execute(['customer_id' => $customer, 'id' => $id]);
  $agent = $req->fetch(PDO::FETCH_OBJ);


  if ($agent) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['customerEdit' => 'Customer already linked with agent']]);
  }

  $stmt = $pdo->prepare("UPDATE agents SET company = :company, customer_id = :customer_id, email = :email WHERE id = :id");
  $stmt->bindParam(":company", $company);
  $stmt->bindParam(":customer_id", $customer);
  $stmt->bindParam(":email", $email);
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  // check if affected rows is 0
  if ($stmt->rowCount() == 0) {
    api_response(['status' => 'error', 'message' => 'You have not made any changes']);
  }
  api_response(['status' => 'success', 'message' => 'Agent updated successfully']);
}


// deleteAgent
if ($action == 'deleteAgent') {
  $id = (int)filterInput('id');


  // load the agent
  $stmt = $pdo->prepare("SELECT * FROM agents WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $agent = $stmt->fetch(PDO::FETCH_OBJ);

  if ($agent == null) {
    api_response(['status' => 'error', 'message' => 'Agent not found']);
  }

  $stmt = $pdo->prepare("UPDATE agents SET deleted = 1 WHERE id = :id");
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  // create delete_request
  $stmt = $pdo->prepare("
  INSERT INTO delete_requests
  SET 
    datetime = :datetime,
    added_by = :added_by,
    type = :type,
    unique_id = :unique_id,
    metadata = :meta,
    status = :status
  ");

  $stmt->bindParam(":datetime", date('Y-m-d H:i:s'));
  $stmt->bindParam(":added_by", $_SESSION['user_id']);
  $stmt->bindValue(":type", 'agent');
  $stmt->bindParam(":unique_id", $id);
  $stmt->bindValue(":meta", json_encode($agent));
  $stmt->bindValue(":status", 'pending');

  $stmt->execute();


  api_response(['status' => 'success', 'message' => 'Agent deleted successfully']);
}
