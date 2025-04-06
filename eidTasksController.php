<?php
session_start();

include 'connection.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header('location:login.php');
    exit;
}

// Function for API response
function api_response($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Function to filter input
function filterInput($name) {
    return htmlspecialchars(stripslashes(trim(isset($_POST[$name]) ? $_POST[$name] : '')));
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

$validActions = ['getResidence', 'extractEidData', 'setMarkReceived', 'setMarkDelivered'];
if (!in_array($action, $validActions)) {
    api_response(['status' => 'error', 'message' => 'Invalid action']);
}

if ($action == 'getResidence') {
    $id = filterInput('id');
    $type = filterInput('type');

    if (empty($id) || empty($type)) {
        api_response(['status' => 'error', 'message' => 'Missing ID or type']);
    }

    try {
        if ($type === 'ML') {
            $stmt = $pdo->prepare("
                SELECT passenger_name, dob, gender 
                FROM residence 
                WHERE residenceID = :id
            ");
        } else { // FZ
            $stmt = $pdo->prepare("
                SELECT passangerName as passenger_name, dob, gender 
                FROM freezone 
                WHERE id = :id
            ");
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result) {
            $response = [
                'status' => 'success',
                'residence' => [
                    'passenger_name' => $result->passenger_name ?? '',
                    'dob' => $result->dob ?? '',
                    'gender' => $result->gender ?? 'male'
                ]
            ];
        } else {
            $response = ['status' => 'error', 'message' => 'No residence found'];
        }
    } catch (PDOException $e) {
        error_log("Get Residence Error: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
    api_response($response);
}

if ($action == 'extractEidData') {
    $extractedData = [
        'passenger_name' => 'John Doe',
        'dob' => '1990-01-01',
        'eid_number' => '784-1234567-1',
        'gender' => 'male'
    ];

    api_response([
        'status' => 'success',
        'data' => $extractedData,
        'message' => 'Data extracted successfully (dummy)'
    ]);
}

if ($action == 'setMarkReceived') {
    $id = filterInput('id');
    $type = filterInput('type');
    $eidNumber = filterInput('eidNumber');
    $eidExpiryDate = filterInput('eidExpiryDate'); // Still received but not used
    $passengerName = filterInput('passenger_name');
    $gender = filterInput('gender');
    $dob = filterInput('dob');

    error_log("setMarkReceived - ID: $id, Type: $type, EID: $eidNumber"); // Debug log

    $errors = [];
    if (empty($id)) $errors['id'] = 'ID is required';
    if (empty($type)) $errors['type'] = 'Type is required';
    if (empty($eidNumber)) $errors['eidNumber'] = 'EID Number is required';
    if (empty($passengerName)) $errors['passenger_name'] = 'Passenger Name is required';
    if (empty($gender)) $errors['gender'] = 'Gender is required';
    if (empty($dob)) $errors['dob'] = 'Date of Birth is required';

    if (!empty($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    try {
        if ($type === 'ML') {
            $stmt = $pdo->prepare("
                UPDATE residence 
                SET eid_received = 1, 
                    EmiratesIDNumber = :eidNumber, 
                    passenger_name = :passengerName, 
                    gender = :gender, 
                    dob = :dob
                WHERE residenceID = :id
            ");
        } else { // FZ
            $stmt = $pdo->prepare("
                UPDATE freezone 
                SET eid_received = 1, 
                    eidNumber = :eidNumber, 
                    passangerName = :passengerName, 
                    gender = :gender, 
                    dob = :dob
                WHERE id = :id
            ");
        }

        $stmt->execute([
            ':id' => $id,
            ':eidNumber' => $eidNumber,
            ':passengerName' => $passengerName,
            ':gender' => $gender,
            ':dob' => $dob
        ]);

        if ($stmt->rowCount() > 0) {
            api_response(['status' => 'success', 'message' => 'Emirates ID marked as received']);
        } else {
            error_log("No rows affected for ID: $id, Type: $type");
            api_response(['status' => 'error', 'message' => 'No record updated. Check if ID exists.']);
        }
    } catch (PDOException $e) {
        error_log("Set Mark Received Error: " . $e->getMessage());
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

if ($action == 'setMarkDelivered') {
    $id = filterInput('id');
    $type = filterInput('type');

    if (empty($id) || empty($type)) {
        api_response(['status' => 'error', 'message' => 'Missing ID or type']);
    }

    try {
        if ($type === 'ML') {
            $stmt = $pdo->prepare("
                UPDATE residence 
                SET eid_delivered = 1 
                WHERE residenceID = :id AND eid_received = 1
            ");
        } else { // FZ
            $stmt = $pdo->prepare("
                UPDATE freezone 
                SET eid_delivered = 1 
                WHERE id = :id AND eid_received = 1
            ");
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            api_response(['status' => 'success', 'message' => 'Emirates ID marked as delivered']);
        } else {
            error_log("No rows affected for Deliver ID: $id, Type: $type");
            api_response(['status' => 'error', 'message' => 'Record not found or not yet received']);
        }
    } catch (PDOException $e) {
        error_log("Set Mark Delivered Error: " . $e->getMessage());
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>