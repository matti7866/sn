<?php

session_start();

include 'connection.php';
// check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
  header('location:login.php');
}

$types = array(
  'residence' => 'Residence'
);


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
if (!in_array($action, ['searchDeleteRequests', 'acceptRequest', 'rejectRequest', 'deleteRequest', 'getRequestDetails'])) {
  api_response(['error' => 'Invalid action']);
}


$statusLabels = array(
  'pending' => '<span class="badge bg-warning">Pending</span>',
  'accepted' => '<span class="badge bg-success">Accepted</span>',
  'rejected' => '<span class="badge bg-danger">Rejected</span>'
);

// searchDeleteRequests
if ($action == 'searchDeleteRequests') {


  $bydate = filterInput('bydate');
  $startDate = filterInput('startDate');
  $endDate = filterInput('endDate');
  $staff_id = filterInput('staff_id');
  $type = filterInput('type');
  $status = filterInput('status');

  $where = '';

  if ($bydate == 1) {
    $where  .= " AND date(delete_requests.datetime) between '$startDate' and '$endDate' ";
  }
  if ($staff_id != '') {
    $where .= " AND delete_requests.staff_id = $staff_id ";
  }
  if ($type != '') {
    $where .= " AND delete_requests.type = '$type' ";
  }
  if ($status != 'all') {
    $where .= " AND delete_requests.status = '$status' ";
  }

  $sql = "
    SELECT delete_requests.*, staff.staff_name 
    FROM delete_requests 
    LEFT JOIN staff ON staff.staff_id = delete_requests.added_by
    WHERE 1=1 {$where} GROUP BY delete_requests.id ORDER BY datetime DESC";
  $smtn = $pdo->prepare($sql);
  $smtn->execute();
  $requests = $smtn->fetchAll(PDO::FETCH_ASSOC);

  if (count($requests) == 0) {
    api_response(['status' => 'error', 'message' => 'No requests found']);
  } else {

    $html = '';

    foreach ($requests as $request) {
      $html .= '<tr>';
      $html .= '<td>' . $request['id'] . '</td>';
      $html .= '<td>' . date("M d, Y h:i A", strtotime($request['datetime'])) . '</td>';
      $html .= '<td>' . (isset($types[$request['type']]) ? $types[$request['type']] : $request['type']) . '<br /><button data-id="' . $request['id'] . '" class="btn btn-xs btn-success btn-request-details">View Details</button></td>';
      $html .= '<td>' . $request['staff_name'] . '</td>';
      $html .= '<td>' . $statusLabels[$request['status']] . '</td>';
      $html .= '<td>';
      if ($request['status'] == 'pending'):
        $html .= '<button class="btn btn-success btn-approval btn-sm" data-id="' . $request['id'] . '" data-action="acceptRequest">Accept</button> ';
        $html .= '<button class="btn btn-danger btn-approval btn-sm" data-id="' . $request['id'] . '" data-action="rejectRequest">Reject</button>';
      endif;
      $html .= '</td>';
      $html .= '</tr>';
    }

    api_response(['status' => 'success', 'html' => $html]);
  }
}

// rejectRequest
if ($action == 'rejectRequest') {

  $id = filterInput('id');

  // load the request
  $stm = $pdo->prepare("SELECT * FROM delete_requests WHERE id = :id ");
  $stm->execute(['id' => $id]);
  $req = $stm->fetch(PDO::FETCH_ASSOC);

  if (!$req) {
    api_response(['status' => 'error', 'message' => 'Request not found']);
  }

  if ($req['type'] == 'residence') {
    $stm = $pdo->prepare("UPDATE residence SET deleted = 0 WHERE residenceID = :id ");
    $stm->execute(['id' => $req['unique_id']]);
  }

  if ($req['type'] == 'agent') {
    $stm = $pdo->prepare("UPDATE agents SET deleted = 0 WHERE id = :id ");
    $stm->execute(['id' => $req['unique_id']]);
  }

  $stm = $pdo->prepare("UPDATE delete_requests SET status = 'rejected' WHERE id = :id ");
  $stm->execute(['id' => $id]);

  api_response(['status' => 'success', 'message' => 'Request rejected']);
}

// getRequestDetails
if ($action == 'getRequestDetails') {
  $id = filterInput('id');

  $stm = $pdo->prepare("SELECT * FROM delete_requests WHERE id = :id ");
  $stm->execute(['id' => $id]);
  $req = $stm->fetch(PDO::FETCH_ASSOC);

  if (!$req) {
    api_response(['status' => 'error', 'message' => 'Request not found']);
  }

  $html = '<table class="table table-bordered">';
  $html .= '<tr><td>Request ID</td><td>' . $req['id'] . '</td></tr>';
  $html .= '<tr><td>Date</td><td>' . date("M d, Y h:i A", strtotime($req['datetime'])) . '</td></tr>';


  if ($req['type'] == 'residence') {
    $meta = json_decode($req['metadata']);

    $html .= '<tr>
        <td colspan="2" class="text-center font-bold"><strong>RESIDENCY DETAILS</strong></td>
      </tr>';

    $html .= '<tr>
        <td>Residency ID</td>
        <td>' . $meta->residenceID . '</td>
      </tr>
      <tr>
        <td>Passenger Name:</td>
        <td>' . strtoupper($meta->passenger_name) . '</td>
      </tr>
      <tr>
        <td>Passport#</td>
        <td>' . strtoupper($meta->passportNumber) . '</td>
      </tr>
      <tr>
        <td>Passport Expiry:</td>
        <td>' . $meta->passportExpiryDate . '</td>
      </tr>
      ';
  }

  if ($req['type'] == 'agent') {
    $meta = json_decode($req['metadata']);

    $html .= '<tr>
        <td colspan="2" class="text-center font-bold"><strong>AGENT DETAILS</strong></td>
      </tr>';

    $html .= '<tr>
        <td>Agent ID</td>
        <td>' . $meta->id . '</td>
      </tr>
      <tr>
        <td>Company Name:</td>
        <td>' . strtoupper($meta->company) . '</td>
      </tr>
      <tr>
        <td>Customer ID</td>
        <td>' . strtoupper($meta->customer_id) . '</td>
      </tr>
      <tr>
        <td>Email:</td>
        <td>' . $meta->email . '</td>
      </tr>
      ';
  }

  $html .= '</table>';

  api_response(['status' => 'success', 'html' => $html]);
}



// acceptRequest
if ($action == 'acceptRequest') {
  $id = filterInput('id');

  $stm = $pdo->prepare("SELECT * FROM delete_requests WHERE id = :id ");
  $stm->execute(['id' => $id]);
  $req = $stm->fetch(PDO::FETCH_ASSOC);

  if (!$req) {
    api_response(['status' => 'error', 'message' => 'Request not found']);
  }

  $unique_id = $req['unique_id'];

  if ($req['type'] == 'residence') {

    // delete residence fines
    $ResidenceFineSql = "DELETE FROM `residencefine` WHERE residenceID = :residenceFineID";
    $ResidenceFineStmt = $pdo->prepare($ResidenceFineSql);
    $ResidenceFineStmt->bindParam(':residenceFineID', $unique_id);
    $ResidenceFineStmt->execute();

    // delete customer paymet
    $deleteResidencePSQL = "DELETE FROM `customer_payments` WHERE PaymentFor = :residenceID";
    $deleteResidencePStmt = $pdo->prepare($deleteResidencePSQL);
    $deleteResidencePStmt->bindParam(':residenceID', $unique_id);
    $deleteResidencePStmt->execute();

    // document
    $ResidenceDocsSql = "DELETE FROM `residencedocuments` WHERE ResID = :residenceID";
    $ResidenceDocStmt = $pdo->prepare($ResidenceDocsSql);
    $ResidenceDocStmt->bindParam(':residenceID', $unique_id);
    $ResidenceDocStmt->execute();

    $deleteResidenceSQL = "DELETE FROM `residence` WHERE residenceID = :residenceID";
    $deleteResidenceStmt = $pdo->prepare($deleteResidenceSQL);
    $deleteResidenceStmt->bindParam(':residenceID', $unique_id);
    $deleteResidenceStmt->execute();
  }

  if ($req['type'] == 'agent') {
    $deleteAgentSQL = "DELETE FROM `agents` WHERE id = :agentID";
    $deleteAgentStmt = $pdo->prepare($deleteAgentSQL);
    $deleteAgentStmt->bindParam(':agentID', $unique_id);
    $deleteAgentStmt->execute();

    // delete agents_login_history
    $deleteAgentLoginHistorySQL = "DELETE FROM `agents_login_history` WHERE agent_id = :agentID";
    $deleteAgentLoginHistoryStmt = $pdo->prepare($deleteAgentLoginHistorySQL);
    $deleteAgentLoginHistoryStmt->bindParam(':agentID', $unique_id);
    $deleteAgentLoginHistoryStmt->execute();
  }

  $stm = $pdo->prepare("UPDATE delete_requests SET status = 'accepted' WHERE id = :id ");
  $stm->execute(['id' => $id]);

  api_response(['status' => 'success', 'message' => 'Request accepted Successfully. ' . $req['type'] . " deleted successully. "]);
}
