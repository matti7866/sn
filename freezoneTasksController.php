<?php

use function PHPSTORM_META\map;

session_start();

include 'connection.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
  header('location:login.php');
  exit;
}

// Function for API response
function api_response($data)
{
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

// Function to filter input
function filterInput($name)
{
  return htmlspecialchars(stripslashes(trim(isset($_POST[$name]) ? $_POST[$name] : '')));
}

function uploadFile($name, $id, $filetype)
{
  global $pdo;
  $new_image_name = '';
  if ($_FILES[$name]['size'] <= 2097152) {
    if (isset($_FILES[$name]) && isset($_FILES[$name]['name'])) {


      $file_name = $_FILES[$name]['name'];
      $extension = pathinfo($file_name, PATHINFO_EXTENSION);
      $valid_extensions = array('jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip');
      if (in_array(strtolower($extension), $valid_extensions)) {
        $new_image_name = rand() . '.' . $extension;
        $path = "freezoneFiles/" . $new_image_name;
        if (move_uploaded_file($_FILES[$name]['tmp_name'], $path)) {
          $fileStmt = $pdo->prepare("INSERT INTO `freezonedocuments` (`freezoneID`, `file_name`, `original_name`, `fileType`) 
                    VALUES (:ResID,:file_name,:original_name,:fileType)");
          $fileStmt->bindParam(':ResID', $id);
          $fileStmt->bindParam(':file_name', $new_image_name);
          $fileStmt->bindParam(':original_name', $_FILES[$name]['name']);
          $fileStmt->bindParam(':fileType', $filetype);
          $fileStmt->execute();

          // id
          $id = $pdo->lastInsertId();

          return $id;
        }
      }
    }
  }
  return $new_image_name;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// Check if invalid action
if (!in_array($action, [
  'saveGeneralData',
  'seteVisa',
  'seteVisaSubmission',
  'rejectEVisa',
  'seteVisaAccept',
  'setChangeStatus',
  'setMedical',
  'setEmiratesID',
  'setVisaStamping',
  'files'
])) {
  api_response(['message' => 'Invalid action', 'status' => 'error']);
}


if ($action == "saveGeneralData") {


  $customerID = filterInput('customerID');
  $uid = filterInput('uid');
  $passportNumber = filterInput('passportNumber');
  $passportExpiryDate = filterInput('passportExpiryDate');
  $passangerName = filterInput('passangerName');
  $nationality = filterInput('nationality');
  $gender = filterInput('gender');
  $dob = filterInput('dob');
  $insideOutside = filterInput('insideOutside');
  $positionID = filterInput('positionID');
  $salary = filterInput('salary');
  $salePrice = filterInput('salePrice');
  $saleCurrency = filterInput('saleCurrency');

  $passportFile = isset($_FILES['passportFile']['name']) ? $_FILES['passportFile'] : ['name' => ''];
  $photoFile = isset($_FILES['photoFile']['name']) ? $_FILES['photoFile'] : ['name' => ''];
  $idFrontFile = isset($_FILES['idFrontFile']['name']) ? $_FILES['idFrontFile'] : ['name' => ''];
  $idBackFile = isset($_FILES['idBackFile']['name']) ? $_FILES['idBackFile'] : ['name' => ''];

  $errors = [];

  if ($customerID == "") {
    $errors['customerID'] = "Customer is required";
  }
  if ($uid == "") {
    $errors['uid'] = "uid is required";
  }
  if ($passportNumber == "") {
    $errors['passportNumber'] = "Passport number is required";
  }
  if ($passportExpiryDate == "") {
    $errors['passportExpiryDate'] = "Passport expiry date is required";
  }
  if ($passangerName == "") {
    $errors['passangerName'] = "Passanger name is required";
  }
  if ($nationality == "") {
    $errors['nationality'] = "Nationality is required";
  }
  if ($gender == "") {
    $errors['gender'] = "Gender is required";
  }
  if ($dob == "") {
    $errors['dob'] = "Date of birth is required";
  }
  if ($insideOutside == "") {
    $errors['insideOutside'] = "Inside/Outside is required";
  }
  if ($positionID == "") {
    $errors['positionID'] = "Position is required";
  }
  if ($salary == "") {
    $errors['salary'] = "Salary is required";
  }
  if ($salePrice == "") {
    $errors['salePrice'] = "Sale price is required";
  }
  if ($saleCurrency == "") {
    $errors['saleCurrency'] = "Sale currency is required";
  }

  if ($passportFile['name'] == '') {
    $errors['passportFile'] = "Passport file is required";
  }
  if ($photoFile['name'] == '') {
    $errors['photoFile'] = "Photo file is required";
  }

  if (count($errors) > 0) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  } else {

    $files = [
      'passportFile' => 0,
      'photoFile' => 0,
      'idFrontFile' => 0,
      'idBackFile' => 0
    ];


    $stmt = $pdo->prepare("
    INSERT INTO freezone SET
      `customerID` = :customerID,
      `UID` = :uid,
      `passangerName` = :passwordName,
      `passportNumber` = :passportNumber,
      `passportExpiryDate` = :passportExpiryDate,
      `nationality` = :nationality,
      `dob` = :dob,
      `gender` = :gender,
      `insideOutside` = :insideOutside,
      `positionID` = :positionID,
      `salary` = :salary,
      `salePrice` = :salePrice,
      `saleCurrency` = :saleCurrency,
      `passportFile` = :passportFile,
      `photoFile` = :photoFile,
      `idFrontFile` = :idFrontFile,
      `idBackFile` = :idBackFile,
      `completedSteps` = 1,
      `addedBy` = :addedBy
    ");


    $stmt->bindParam(':customerID', $customerID);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':passwordName', $passangerName);
    $stmt->bindParam(':passportNumber', $passportNumber);
    $stmt->bindParam(':passportExpiryDate', $passportExpiryDate);
    $stmt->bindParam(':nationality', $nationality);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':insideOutside', $insideOutside);
    $stmt->bindParam(':positionID', $positionID);
    $stmt->bindParam(':salary', $salary);
    $stmt->bindParam(':salePrice', $salePrice);
    $stmt->bindParam(':saleCurrency', $saleCurrency);
    $stmt->bindParam(':passportFile', $files['passportFile']);
    $stmt->bindParam(':photoFile', $files['photoFile']);
    $stmt->bindParam(':idFrontFile', $files['idFrontFile']);
    $stmt->bindParam(':idBackFile', $files['idBackFile']);
    $stmt->bindParam(':addedBy', $_SESSION['user_id']);
    $stmt->execute();

    // get the last inserted id
    $lastInsertId = $pdo->lastInsertId();


    $files['passportFile'] = uploadFile('passportFile', $lastInsertId, 'passport');
    $files['photoFile'] = uploadFile('photoFile', $lastInsertId, 'photo');
    $files['idFrontFile'] = uploadFile('idFrontFile', $lastInsertId, 'idFront');
    $files['idBackFile'] = uploadFile('idBackFile', $lastInsertId, 'idBack');

    // update ids
    $stmt = $pdo->prepare("
    UPDATE freezone SET
      `passportFile` = :passportFile,
      `photoFile` = :photoFile,
      `idFrontFile` = :idFrontFile,
      `idBackFile` = :idBackFile
    WHERE `id` = :id
    ");

    $stmt->bindParam(':passportFile', $files['passportFile']);
    $stmt->bindParam(':photoFile', $files['photoFile']);
    $stmt->bindParam(':idFrontFile', $files['idFrontFile']);
    $stmt->bindParam(':idBackFile', $files['idBackFile']);
    $stmt->bindParam(':id', $lastInsertId);
    $stmt->execute();

    api_response(['status' => 'success', 'message' => 'Data saved successfully', 'id' => $lastInsertId]);
  }
}


if ($action == "seteVisa") {
  $companyID = filterInput('companyID');
  $eVisaPositionID = filterInput('eVisaPositionID');
  $eVisaCost = filterInput('eVisaCost');
  $id = filterInput('id');

  $errors = [];

  if ($companyID == "") {
    $errors['companyID'] = "Company is required";
  }
  if ($eVisaPositionID == "") {
    $errors['eVisaPositionID'] = "Position is required";
  }

  if (count($errors) > 0) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  } else {

    $stmt = $pdo->prepare("
    UPDATE freezone SET
      `companyID` = :companyID,
      `positionID` = :eVisaPositionID,
      `evisaCost` = :eVisaCost,
      `evisaStatus` = 'processing',
      `evisaApplyDate` = NOW()
    WHERE `id` = :id
    ");

    $stmt->bindParam(':companyID', $companyID);
    $stmt->bindParam(':eVisaPositionID', $eVisaPositionID);
    $stmt->bindParam(':eVisaCost', $eVisaCost);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    api_response(['status' => 'success', 'message' => 'eVisa set successfully']);
  }
}


if ($action == 'rejectEVisa') {
  $id = filterInput('id');

  $stmt = $pdo->prepare("UPDATE freezone SET `evisaStatus` = 'pending' WHERE `id` = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  api_response(['status' => 'success', 'message' => 'eVisa rejected successfully']);
}

if ($action == 'seteVisaAccept') {
  $id = filterInput('id');


  // load file
  $residence = $pdo->prepare("SELECT * FROM freezone WHERE id = :id");
  $residence->bindParam(':id', $id);
  $residence->execute();
  $residence = $residence->fetch(PDO::FETCH_OBJ);
  $nextStatus = $residence->insideOutside == 'Inside' ? 2 : 3;


  $fileName = uploadFile('eVisaFile', $id, 'eVisa');



  $stmt = $pdo->prepare("
  UPDATE freezone SET 
  `evisaFile` = :eVisaFile,
  `evisaStatus` = 'approved',
  `completedSteps` = :nextStatus
  WHERE `id` = :id
  ");
  $stmt->bindParam(':eVisaFile', $fileName);
  $stmt->bindParam(':id', $id);
  $stmt->bindParam(':nextStatus', $nextStatus);
  $stmt->execute();

  api_response(['status' => 'success', 'message' => 'eVisa accepted successfully']);
}


if ($action == 'setChangeStatus') {


  $id = filterInput('id');
  $changeStatusCost = filterInput('changeStatusCost');
  $changeStatusAccountType = filterInput('changeStatusAccountType');
  $changeStatusAccountID = filterInput('changeStatusAccountID');
  $changeStatusSupplierID = filterInput('changeStatusSupplierID');


  $changeStatusFileName = uploadFile('changeStatusFile', $id, 'changeStatus');


  $errors = [];
  if ($id == "") {
    $errors['id'] = "ID is required";
  }
  if ($changeStatusCost == "" || !is_numeric($changeStatusCost) || $changeStatusCost <= 0) {
    $errors['changeStatusCost'] = "Cost is required and must be a number greater than 0";
  }
  if ($changeStatusAccountType == "" || !in_array($changeStatusAccountType, [1, 2])) {
    $errors['changeStatusAccountType'] = "Account type is required";
  } else {
    if ($changeStatusAccountType == 1) {
      if ($changeStatusAccountID == "") {
        $errors['changeStatusAccountID'] = "Account is required";
      }
    } else {
      if ($changeStatusSupplierID == "") {
        $errors['changeStatusSupplierID'] = "Supplier is required";
      }
    }
  }

  if (count($errors) > 0) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  } else {

    $stmt = $pdo->prepare("
    UPDATE freezone SET 
      `changeStatusFile` = :changeStatusFile,
      `changeStatusCost` = :changeStatusCost,
      `changeStatusCostAccountType` = :changeStatusAccountType,
      `changeStatusCostAccount` = :changeStatusCostAccount,
      `changeStatusDate` = NOW(),
      `changeStatusStaffID` = :changeStatusStaffID,
      `completedSteps` = 3
    WHERE `id` = :id
    ");

    $stmt->bindParam(':changeStatusFile', $changeStatusFileName);
    $stmt->bindParam(':changeStatusCost', $changeStatusCost);
    $stmt->bindParam(':changeStatusAccountType', $changeStatusAccountType);
    if ($changeStatusAccountType == '1') {
      $stmt->bindParam(':changeStatusCostAccount', $changeStatusAccountID);
    } else {
      $stmt->bindParam(':changeStatusCostAccount', $changeStatusSupplierID);
    }
    $stmt->bindParam(':changeStatusStaffID', $_SESSION['user_id']);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    api_response(['status' => 'success', 'message' => 'Status changed successfully']);
  }
}

if ($action == 'setMedical') {
  $id = filterInput('id');
  $medicalCost = filterInput('medicalCost');

  $medicalFileName = uploadFile('medicalFile', $id, 'medical');

  if ($medicalCost == '' || !is_numeric($medicalCost)) {
    api_response(['status' => 'error', 'message' => 'Medical cost is required and must be a number']);
  }

  // update medical
  $stmt = $pdo->prepare("
  UPDATE freezone SET 
    `medicalFile` = :medicalFile,
    `medicalCost` = :medicalCost,
    `medicalDate` = NOW(),
    `completedSteps` = 4,
    `medicalStaffID` = :medicalStaffID
  WHERE `id` = :id
  ");
  $stmt->bindParam(':medicalFile', $medicalFileName);
  $stmt->bindParam(':medicalCost', $medicalCost);
  $stmt->bindParam(':id', $id);
  $stmt->bindParam(':medicalStaffID', $_SESSION['user_id']);
  $stmt->execute();

  api_response(['status' => 'success', 'message' => 'Medical set successfully']);
}


if ($action == 'setEmiratesID') {
  $id = filterInput('id');
  $emiratesIDCost = filterInput('emiratesIDCost');
  $emiratesIDFile = uploadFile('emiratesIDFile', $id, 'emiratesID');

  $stmt = $pdo->prepare("
  UPDATE freezone SET 
    `eidTypingFile` = :emiratesIDFile,
    `eidTypingCost` = :emiratesIDCost,
    `eidTypingStaffID` = :eidTypingStaffID,
    `completedSteps` = 5
  WHERE `id` = :id
  ");
  $stmt->bindParam(':emiratesIDFile', $emiratesIDFile);
  $stmt->bindParam(':emiratesIDCost', $emiratesIDCost);
  $stmt->bindParam(':eidTypingStaffID', $_SESSION['user_id']);
  $stmt->bindParam(':id', $id);
  $stmt->execute();

  api_response(['status' => 'success', 'message' => 'Emirates ID set successfully']);
}


if ($action == 'setVisaStamping') {

  $id = filterInput('id');
  $emiratesIDNumber = filterInput('emiratesIDNumber');
  $visaExpiryDate = filterInput('visaExpiryDate');

  $errors = [];
  if ($emiratesIDNumber == "") {
    $errors['emiratesIDNumber'] = "Emirates ID number is required";
  }
  if ($visaExpiryDate == "") {
    $errors['visaExpiryDate'] = "Visa expiry date is required";
  }

  if (count($errors) > 0) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  } else {
    // update
    $stmt = $pdo->prepare("
    UPDATE freezone SET 
      `visaStampingDate` = NOW(),
      `visaStampingStaffID` = :visaStampingStaffID,
      `eidNumber` = :emiratesIDNumber,
      `visaExpiryDate` = :visaExpiryDate,
      `completedSteps` = 6
    WHERE `id` = :id
    ");
    $stmt->bindParam(':visaStampingStaffID', $_SESSION['user_id']);
    $stmt->bindParam(':emiratesIDNumber', $emiratesIDNumber);
    $stmt->bindParam(':visaExpiryDate', $visaExpiryDate);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    api_response(['status' => 'success', 'message' => 'Visa stamping set successfully']);
  }
}

if ($action == 'files') {
  $id = filterInput('id');


  $stmt = $pdo->prepare("SELECT * FROM freezonedocuments WHERE freezoneID = :id");
  $stmt->bindParam(':id', $id);
  $stmt->execute();
  $files = $stmt->fetchAll(PDO::FETCH_OBJ);

  if (count($files) == 0) {
    $html = '<div class="alert alert-info">No files found</div>';
  } else {
    $html = '';
    foreach ($files as $file) {
      $html .= '<div class="media d-flex mb-2"><h1 class="me-2"><span class="fiv-cla fiv-icon-jpeg text-lg"></span></h1><div class="media-body"><h4 class="mb-0">' . $file->fileType . '</h4>
            <a target="_blank" class="text-decoration-none" href="/freezoneFiles/' . $file->file_name . '"><i class="fa fa-eye"></i> Preview</a> 
          </div></div>';
    }
  }

  api_response(['status' => 'success', 'message' => 'Files fetched successfully', 'files' => $html]);
}
