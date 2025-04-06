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
if (!in_array($action, ['changeStatus'])) {
  api_response(['error' => 'Invalid action']);
}

// changeStatus
if ($action == 'changeStatus') {
  $id = filterInput('id');
  $status = filterInput('status');

  if ($id == "" || $status == "") {
    api_response(['message' => 'Invalid input', 'status' => 'error']);
  }

  // load agent
  $sql = "SELECT * FROM agents WHERE id = $id";
  $req = $pdo->prepare($sql);
  $req->execute();
  $agent = $req->fetch(PDO::FETCH_OBJ);

  if (!$agent) {
    api_response(['message' => 'Agent not found', 'status' => 'error']);
  }

  // update status
  $sql = "UPDATE agents SET status = :status WHERE id = :id";

  $req = $pdo->prepare($sql);
  $req->execute(['status' => $status, 'id' => $id]);

  api_response(['message' => 'Status updated successfully', 'status' => 'success']);
}
