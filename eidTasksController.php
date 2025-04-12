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

$validActions = ['getResidence', 'extractEidData', 'setMarkReceived', 'setMarkDelivered', 'getPositionName', 'getPositions', 'getCompanies', 'updateResidence'];
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
                SELECT r.*, p.posiiton_name as positionName, c.company_name
                FROM residence r
                LEFT JOIN position p ON r.positionID = p.position_id
                LEFT JOIN company c ON r.company = c.company_id
                WHERE r.residenceID = :id
            ");
        } else { // FZ
            $stmt = $pdo->prepare("
                SELECT f.*, p.posiiton_name as positionName, c.company_name
                FROM freezone f
                LEFT JOIN position p ON f.positionID = p.position_id
                LEFT JOIN company c ON f.company = c.company_id
                WHERE f.id = :id
            ");
        }

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $residence = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($residence) {
            // Add extra debugging information
            $residence['debug_company_info'] = [
                'company_id_field' => isset($residence['company_id']) ? 'exists' : 'missing',
                'companyID_field' => isset($residence['companyID']) ? 'exists' : 'missing',
                'establishment_field' => isset($residence['establishment']) ? 'exists' : 'missing',
                'company_field' => isset($residence['company']) ? 'exists' : 'missing',
                'company_name' => $residence['company_name'],
                'position_name' => $residence['positionName']
            ];
            
            $response = [
                'status' => 'success',
                'residence' => $residence
            ];
        } else {
            $response = ['status' => 'error', 'message' => 'Residence not found'];
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
    $eid_number = filterInput('eidNumber');
    $eid_expiry_date = filterInput('eidExpiryDate');
    $passenger_name = filterInput('passenger_name');
    $gender = filterInput('gender');
    $dob = filterInput('dob');
    $positionID = filterInput('occupation'); // This is already the position ID from the select
    $companyID = filterInput('establishmentName'); // This is already the company ID from the select
    
    error_log("setMarkReceived - ID: $id, Type: $type, EID: $eid_number");
    error_log("Position ID: $positionID, Company ID: $companyID");
    
    // Process front image data from base64
    $frontImageData = null;
    $frontImagePath = null;
    if (!empty($_POST['frontImageData'])) {
        // Extract the base64 data part
        $frontImageBase64 = $_POST['frontImageData'];
        if (strpos($frontImageBase64, 'base64,') !== false) {
            $frontImageBase64 = explode('base64,', $frontImageBase64)[1];
        }
        
        // Create directory if it doesn't exist
        $uploadDir = 'uploads/emirates_id/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generate unique filename
        $frontImagePath = $uploadDir . $id . '_front_' . time() . '.jpg';
        
        // Save image to file
        file_put_contents($frontImagePath, base64_decode($frontImageBase64));
    }
    
    // Process back image from file upload
    $backImagePath = null;
    if (!empty($_FILES['emiratesIDBack']['tmp_name'])) {
        $uploadDir = 'uploads/emirates_id/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $backImagePath = $uploadDir . $id . '_back_' . time() . '.jpg';
        
        // Move uploaded file
        move_uploaded_file($_FILES['emiratesIDBack']['tmp_name'], $backImagePath);
    }

    $errors = [];
    if (empty($id)) $errors['id'] = 'ID is required';
    if (empty($type)) $errors['type'] = 'Type is required';
    if (empty($eid_number)) $errors['eidNumber'] = 'EID Number is required';
    if (empty($passenger_name)) $errors['passenger_name'] = 'Passenger Name is required';
    if (empty($gender)) $errors['gender'] = 'Gender is required';
    if (empty($dob)) $errors['dob'] = 'Date of Birth is required';

    if (!empty($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    try {
        if ($type === 'ML') {
            $sql = "UPDATE residence SET eid_received = 1, EmiratesIDNumber = :eid, 
                   eid_expiry = :expiry, passenger_name = :name, gender = :gender, 
                   dob = :dob, eid_front_image = :front_image, eid_back_image = :back_image,
                   eid_received_date = NOW(), eid_received_by = :user";
            
            // Add position ID to update if found
            if (!empty($positionID)) {
                $sql .= ", positionID = :positionID";
            }
            
            // Add company ID to update if found
            if (!empty($companyID)) {
                $sql .= ", company = :companyID";
            }
            
            $sql .= " WHERE residenceID = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':eid', $eid_number);
            $stmt->bindParam(':expiry', $eid_expiry_date);
            $stmt->bindParam(':name', $passenger_name);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':front_image', $frontImagePath);
            $stmt->bindParam(':back_image', $backImagePath);
            $stmt->bindParam(':user', $_SESSION['user_id']);
            $stmt->bindParam(':id', $id);
            
            if (!empty($positionID)) {
                $stmt->bindParam(':positionID', $positionID);
                error_log("Binding positionID: $positionID");
            }
            
            if (!empty($companyID)) {
                $stmt->bindParam(':companyID', $companyID);
                error_log("Binding companyID: $companyID");
            }
            
            $stmt->execute();
        } else { // FZ
            $sql = "UPDATE freezone SET eid_received = 1, eidNumber = :eid, 
                   eid_expiry = :expiry, passangerName = :name, gender = :gender, 
                   dob = :dob, eid_front_image = :front_image, eid_back_image = :back_image,
                   eid_received_date = NOW(), eid_received_by = :user";
            
            // Add position ID to update if found
            if (!empty($positionID)) {
                $sql .= ", positionID = :positionID";
            }
            
            // Add company ID to update if found
            if (!empty($companyID)) {
                $sql .= ", company = :companyID";
            }
            
            $sql .= " WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':eid', $eid_number);
            $stmt->bindParam(':expiry', $eid_expiry_date);
            $stmt->bindParam(':name', $passenger_name);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':front_image', $frontImagePath);
            $stmt->bindParam(':back_image', $backImagePath);
            $stmt->bindParam(':user', $_SESSION['user_id']);
            $stmt->bindParam(':id', $id);
            
            if (!empty($positionID)) {
                $stmt->bindParam(':positionID', $positionID);
                error_log("Binding positionID: $positionID");
            }
            
            if (!empty($companyID)) {
                $stmt->bindParam(':companyID', $companyID);
                error_log("Binding companyID: $companyID");
            }
            
            $stmt->execute();
        }
        
        if ($stmt->rowCount() > 0) {
            api_response(['status' => 'success', 'message' => 'EID marked as received successfully']);
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

if ($action == 'getPositionName') {
    $positionID = $_POST['positionID'];
    
    if (empty($positionID)) {
        api_response(['status' => 'error', 'message' => 'Position ID is required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT posiiton_name as positionName FROM position WHERE position_id = :positionID");
        $stmt->bindParam(':positionID', $positionID);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            api_response(['status' => 'success', 'positionName' => $result['positionName']]);
        } else {
            api_response(['status' => 'error', 'message' => 'Position not found']);
        }
    } catch (PDOException $e) {
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action == 'getPositions') {
    try {
        $stmt = $pdo->prepare("SELECT position_id, posiiton_name as position_name FROM position ORDER BY posiiton_name ASC");
        $stmt->execute();
        $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        api_response(['status' => 'success', 'positions' => $positions]);
    } catch (PDOException $e) {
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action == 'getCompanies') {
    try {
        $stmt = $pdo->prepare("SELECT company_id, company_name FROM company ORDER BY company_name ASC");
        $stmt->execute();
        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        api_response(['status' => 'success', 'companies' => $companies]);
    } catch (PDOException $e) {
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

if ($action == 'updateResidence') {
    $residenceID = filterInput('residenceID');
    $positionID = filterInput('newOccupation');
    $companyID = filterInput('newEstablishmentName');
    $type = filterInput('type');
    
    // Log incoming data
    error_log("updateResidence called with: ID=$residenceID, Type=$type, Position=$positionID, Company=$companyID");
    
    if (empty($residenceID) || empty($type)) {
        api_response(['status' => 'error', 'message' => 'Residence ID and type are required']);
        exit;
    }
    
    try {
        // First check if the record exists
        if ($type === 'ML') {
            $checkStmt = $pdo->prepare("SELECT residenceID FROM residence WHERE residenceID = :id");
            $checkStmt->bindParam(':id', $residenceID);
            $checkStmt->execute();
            $record = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$record) {
                api_response(['status' => 'error', 'message' => "Record not found: No residence with ID $residenceID"]);
                exit;
            }
            
            $sql = "UPDATE residence SET ";
            
            $hasFields = false;
            
            if (!empty($positionID)) {
                $sql .= "positionID = :positionID";
                $hasFields = true;
            }
            
            if (!empty($companyID)) {
                if ($hasFields) {
                    $sql .= ", ";
                }
                $sql .= "company = :companyID";
                $hasFields = true;
            }
            
            // Only proceed if we have fields to update
            if (!$hasFields) {
                api_response([
                    'status' => 'error', 
                    'message' => 'No fields to update', 
                    'debug' => ['positionID' => $positionID, 'companyID' => $companyID]
                ]);
                exit;
            }
            
            $sql .= " WHERE residenceID = :id";
            error_log("SQL Query: $sql");
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $residenceID);
            
            if (!empty($positionID)) {
                $stmt->bindParam(':positionID', $positionID);
            }
            
            if (!empty($companyID)) {
                $stmt->bindParam(':companyID', $companyID);
            }
        } else { // FZ
            $checkStmt = $pdo->prepare("SELECT id FROM freezone WHERE id = :id");
            $checkStmt->bindParam(':id', $residenceID);
            $checkStmt->execute();
            $record = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$record) {
                api_response(['status' => 'error', 'message' => "Record not found: No freezone with ID $residenceID"]);
                exit;
            }
            
            $sql = "UPDATE freezone SET ";
            
            $hasFields = false;
            
            if (!empty($positionID)) {
                $sql .= "positionID = :positionID";
                $hasFields = true;
            }
            
            if (!empty($companyID)) {
                if ($hasFields) {
                    $sql .= ", ";
                }
                $sql .= "company = :companyID";
                $hasFields = true;
            }
            
            // Only proceed if we have fields to update
            if (!$hasFields) {
                api_response([
                    'status' => 'error', 
                    'message' => 'No fields to update', 
                    'debug' => ['positionID' => $positionID, 'companyID' => $companyID]
                ]);
                exit;
            }
            
            $sql .= " WHERE id = :id";
            error_log("SQL Query: $sql");
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $residenceID);
            
            if (!empty($positionID)) {
                $stmt->bindParam(':positionID', $positionID);
            }
            
            if (!empty($companyID)) {
                $stmt->bindParam(':companyID', $companyID);
            }
        }
        
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        error_log("Update result: $rowCount rows affected");
        
        if ($rowCount > 0) {
            api_response(['status' => 'success', 'message' => 'Residence information updated successfully']);
        } else {
            // Check if the values are the same as existing ones
            if ($type === 'ML') {
                $currentStmt = $pdo->prepare("SELECT positionID, company FROM residence WHERE residenceID = :id");
                $currentStmt->bindParam(':id', $residenceID);
                $currentStmt->execute();
                $current = $currentStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($current) {
                    $currentPosID = $current['positionID'];
                    $currentCompID = $current['company'];
                    
                    if ((!empty($positionID) && $positionID == $currentPosID) && 
                        (!empty($companyID) && $companyID == $currentCompID)) {
                        api_response([
                            'status' => 'success', 
                            'message' => 'No changes needed - values already match',
                            'debug' => [
                                'current' => $current,
                                'new' => ['positionID' => $positionID, 'companyID' => $companyID]
                            ]
                        ]);
                        exit;
                    }
                }
            }
            
            api_response([
                'status' => 'error', 
                'message' => 'No changes made',
                'debug' => [
                    'sql' => $sql,
                    'id' => $residenceID,
                    'positionID' => $positionID, 
                    'companyID' => $companyID
                ]
            ]);
        }
    } catch (PDOException $e) {
        error_log("Update Residence Error: " . $e->getMessage());
        api_response(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>