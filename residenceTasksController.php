<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
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
                $path = "residence/" . $new_image_name;
                if (move_uploaded_file($_FILES[$name]['tmp_name'], $path)) {
                    $fileStmt = $pdo->prepare("INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) 
                    VALUES (:ResID,:file_name,:original_name,:fileType)");
                    $fileStmt->bindParam(':ResID', $id);
                    $fileStmt->bindParam(':file_name', $new_image_name);
                    $fileStmt->bindParam(':original_name', $_FILES[$name]['name']);
                    $fileStmt->bindParam(':fileType', $filetype);
                    $fileStmt->execute();
                    return $new_image_name;
                }
            }
        }
    }
    return $new_image_name;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// Check if invalid action
if (!in_array($action, [
    'setOfferLetterStatus',
    'seteVisaStatus',
    'setHold',
    'getPassenger',
    'addResidence',
    'setOfferLetter',
    'setInsurance',
    'setLabourCard',
    'setEVisa',
    'setChangeStatus',
    'setMedical',
    'setEmiratesID',
    'setVisaStamping',
    'setContractSubmission',
    'setTawjeeh',
    'setILOE'
])) {
    api_response(['message' => 'Invalid action', 'status' => 'error']);
}

if ($action == 'setOfferLetterStatus') {
    $id = filterInput('id');
    $value = filterInput('value');

    if ($id == "" || $value == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $stmt = $pdo->prepare("SELECT * FROM residence WHERE residenceID = :id");
    $stmt->execute(['id' => $id]);
    $residence = $stmt->fetch(PDO::FETCH_OBJ);

    if ($value == 'accepted') {
        $stmt = $pdo->prepare("UPDATE residence SET offerLetterStatus = :value WHERE residenceID = :id");
        $stmt->execute(['value' => $value, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE residence SET offerLetterStatus = :value, completedStep = 1 WHERE residenceID = :id");
        $stmt->execute(['value' => 'pending', 'id' => $id]);
    }

    api_response([
        'status' => 'success',
        'message' => 'Offer letter status updated successfully',
    ]);
}

if ($action == 'seteVisaStatus') {
    $id = filterInput('id');
    $value = filterInput('value');

    if ($id == "" || $value == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $stmt = $pdo->prepare("SELECT * FROM residence WHERE residenceID = :id");
    $stmt->execute(['id' => $id]);
    $residence = $stmt->fetch(PDO::FETCH_OBJ);

    if ($value == 'accepted') {
        $stmt = $pdo->prepare("UPDATE residence SET eVisaStatus = :value WHERE residenceID = :id");
        $stmt->execute(['value' => $value, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE residence SET eVisaStatus = :value, completedStep = 4 WHERE residenceID = :id");
        $stmt->execute(['value' => 'pending', 'id' => $id]);
    }

    api_response([
        'status' => 'success',
        'message' => 'eVisa status updated successfully',
    ]);
}

if ($action == 'setHold') {
    $id = filterInput('id');
    $value = filterInput('value');

    if ($id == "" || $value == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $stmt = $pdo->prepare("SELECT * FROM residence WHERE residenceID = :id");
    $stmt->execute(['id' => $id]);
    $residence = $stmt->fetch(PDO::FETCH_OBJ);

    if ($value == '1') {
        $stmt = $pdo->prepare("UPDATE residence SET hold = :value WHERE residenceID = :id");
        $stmt->execute(['value' => 1, 'id' => $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE residence SET hold = :value WHERE residenceID = :id");
        $stmt->execute(['value' => 0, 'id' => $id]);
    }

    api_response([
        'status' => 'success',
        'message' => 'Hold status updated successfully',
    ]);
}

if ($action == 'getPassenger') {
    $field = filterInput('field');
    $value = filterInput('value');

    if ($field == "" || $value == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input', 'data' => []]);
    }

    if ($field == 'uid' && strtoupper($value) == 'OUTSIDE') {
        api_response(['status' => 'error', 'message' => '', 'data' => []]);
    }

    $stmt = $pdo->prepare("SELECT * FROM residence WHERE UCASE({$field})  = UCASE(:value)");
    $stmt->execute(['value' => $value]);
    $customer = $stmt->fetch(PDO::FETCH_OBJ);

    if ($customer) {
        api_response(['status' => 'success', 'message' => '', 'data' => $customer]);
    } else {
        api_response(['status' => 'error', 'message' => 'Customer not found', 'data' => []]);
    }
}


if ($action == 'addResidence') {

    $customer_id = filterInput('customer_id');
    $uid = filterInput('uid');
    $passportNumber = filterInput('passportNumber');
    $passportExpiryDate = filterInput('passportExpiryDate');
    $passangerName = filterInput('passangerName');
    $nationality = filterInput('nationality');
    $dob = filterInput('dob');
    $gender = filterInput('gender');
    $insideOutside = filterInput('insideOutside');
    $position = filterInput('position');
    $salary = filterInput('salary');
    $salePrice = filterInput('sale_price');
    $salePriceCurrency = filterInput('sale_price_currency');

    $errors = [];
    if ($customer_id == '') {
        $errors['customer_id'] = 'Customer is required';
    }
    if ($uid == '') {
        $errors['uid'] = 'UID is required';
    }
    if ($passportNumber == '') {
        $errors['passportNumber'] = 'Passport number is required';
    }
    if ($passportExpiryDate == '') {
        $errors['passportExpiryDate'] = 'Passport expiry date is required';
    }
    if ($passangerName == '') {
        $errors['passangerName'] = 'Passanger name is required';
    }
    if ($nationality == '') {
        $errors['nationality'] = 'Nationality is required';
    }
    if ($dob == '') {
        $errors['dob'] = 'Date of birth is required';
    }
    if ($gender == '') {
        $errors['gender'] = 'Gender is required';
    }

    if ($insideOutside == '') {
        $errors['insideOutside'] = 'Inside/Outside is required';
    }

    if ($position == '') {
        $errors['position'] = 'Position is required';
    }

    if ($salary == '') {
        $errors['salary'] = 'Salary is required';
    }

    if ($salePrice == '') {
        $errors['sale_price'] = 'Sale price is required';
    }

    if ($salePriceCurrency == '') {
        $errors['sale_price_currency'] = 'Sale price currency is required';
    }


    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }
}

if ($action == 'setOfferLetter') {
    $id = filterInput('id');
    $company_id = filterInput('company_id');
    $mbNumber = filterInput('mbNumber');
    $offerLetterCost = filterInput('offerLetterCost');
    $offerLetterCurrency = filterInput('offerLetterCurrency');
    $offerLetterChargeOn = filterInput('offerLetterChargeOn');
    $offerLetterChargeAccount = filterInput('offerLetterChargeAccount');
    $offerLetterChargeSupplier = filterInput('offerLetterChargeSupplier');

    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];

    if ($company_id == "") {
        $errors['company_id'] = 'Company is required';
    }

    if ($mbNumber == "") {
        $errors['mbNumber'] = 'MB number is required';
    }
    if ($offerLetterCost == "") {
        $errors['offerLetterCost'] = 'Offer letter charge account is required';
    }
    if ($offerLetterCurrency == "") {
        $errors['offerLetterCurrency'] = 'Offer letter currency is required';
    }
    if ($offerLetterChargeOn == "") {
        $errors['offerLetterChargeOn'] = 'Offer letter charge on is required';
    } else {
        if ($offerLetterChargeOn == "1" && $offerLetterChargeAccount == "") {
            $errors['offerLetterChargeAccount'] = 'Offer letter charge account is required';
        }
        if ($offerLetterChargeOn == 2 && $offerLetterChargeSupplier == "") {
            $errors['offerLetterChargeSupplier'] = 'Offer letter charge supplier is required';
        }
    }


    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    // load the residence
    $stmt = $pdo->prepare("SELECT * FROM residence WHERE residenceID = :id");
    $stmt->execute(['id' => $id]);
    $residence = $stmt->fetch(PDO::FETCH_OBJ);


    // if (isset($_FILES['offerLetterFile']) && isset($_FILES['offerLetterFile']['name']) && $_FILES['offerLetterFile']['name'] != '') {
    //     $offerLetter = uploadFile('offerLetterFile');
    //     if ($offerLetter == '') {
    //         api_response(['status' => 'error', 'message' => 'Error uploading offer letter file']);
    //     }

    //     $filetype = 2;
    //     $fileStmt = $pdo->prepare("INSERT INTO `residencedocuments`(`ResID`, `file_name`, `original_name`, `fileType`) 
    //     VALUES (:ResID,:file_name,:original_name,:fileType)
    //     ");
    //     $fileStmt->bindParam(':ResID', $id);
    //     $fileStmt->bindParam(':file_name', $offerLetter);
    //     $fileStmt->bindParam(':original_name', $_FILES['offerLetterFile']['name']);
    //     $fileStmt->bindParam(':fileType', $filetype);
    //     $fileStmt->execute();
    // }



    uploadFile('offerLetterFile', $id, 2);



    // update resi
    $stmp = $pdo->prepare("
    UPDATE residence SET 
        company = :company,
        offerLetterCost = :offerLetterCost,
        offerLetterCostCur = :offerLetterCostCur,
        offerLetterSupplier = :offerLetterSupplier,
        offerLetterAccount = :offerLetterAccount,
        stepTwoUploder = :stepTwoUploder,
        mb_number = :mb_number,
        offerLetterStatus = 'submitted',
        completedStep = 2
    WHERE residenceID = :id
    ");

    $stmp->execute([
        'company' => $company_id,
        'offerLetterCost' => $offerLetterCost,
        'offerLetterCostCur' => $offerLetterCurrency,
        'offerLetterSupplier' => $offerLetterChargeSupplier ? $offerLetterChargeSupplier : NULL,
        'offerLetterAccount' => $offerLetterChargeAccount ? $offerLetterChargeAccount : NULL,
        'stepTwoUploder' => $_SESSION['user_id'],
        'mb_number' => $mbNumber,
        'id' => $id
    ]);

    /// check if error
    if ($stmp->errorInfo()[0] != '00000') {
        api_response(['status' => 'error', '12' => 'Error updating residence', 'message' => $stmp->errorInfo()]);
    }

    api_response(['status' => 'success', 'message' => 'Offer letter set successfully']);
}


// set insurance
if ($action  == 'setInsurance') {

    $insuranceCost = filterInput('insuranceCost');
    $insuranceCurrency = filterInput('insuranceCurrency');
    $insuranceChargeOn = filterInput('insuranceChargeOn');
    $insuranceChargeAccount = filterInput('insuranceChargeAccount');
    $insuranceChargeSupplier = filterInput('insuranceChargeSupplier');
    $id = filterInput('id');



    $errors = [];

    if ($insuranceCost == "") {
        $errors['insuranceCost'] = 'Insurance cost is required';
    }
    if ($insuranceCurrency == "") {
        $errors['insuranceCurrency'] = 'Insurance currency is required';
    }
    if ($insuranceChargeOn == "") {
        $errors['insuranceChargeOn'] = 'Insurance charge on is required';
    } else {
        if ($insuranceChargeOn == 1 && $insuranceChargeAccount == "") {
            $errors['insuranceChargeAccount'] = 'Insurance charge account is required';
        }
        if ($insuranceChargeOn == 2 && $insuranceChargeSupplier == "") {
            $errors['insuranceChargeSupplier'] = 'Insurance charge supplier is required';
        }
    }



    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['insuranceFile'] && isset($_FILES['insuranceFile']['name']) && $_FILES['insuranceFile']['name'] != '') {
        uploadFile('insuranceFile', $id, 3);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        insuranceCost = :insuranceCost,
        insuranceCur = :insuranceCur,
        insuranceSupplier = :insuranceSupplier,
        insuranceAccount = :insuranceAccount,
        stepThreeUploader = :stepThreeUploader,
        completedStep = 3
    WHERE residenceID = :id
    ");
    $stmt->execute([
        'insuranceCost' => $insuranceCost,
        'insuranceCur' => $insuranceCurrency,
        'insuranceSupplier' => $insuranceChargeOn == 1 ? NULL : $insuranceChargeSupplier,
        'insuranceAccount' => $insuranceChargeOn == 1 ? $insuranceChargeAccount : NULL,
        'stepThreeUploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'Insurance set successfully']);
}


if ($action == 'setLabourCard') {
    $id = filterInput('id');
    $labourCardNumber = filterInput('labourCardNumber');
    $labourCardCost = filterInput('labourCardCost');
    $labourCardCurrency = filterInput('labourCardCurrency');
    $labourCardChargeOn = filterInput('labourCardChargeOn');
    $labourCardChargeAccount = filterInput('labourCardChargeAccount');
    $labourCardChargeSupplier = filterInput('labourCardChargeSupplier');

    // first check if id is valid
    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    // validation
    $errors = [];

    if ($labourCardNumber == "") {
        $errors['labourCardNumber'] = 'Labour card number is required';
    }

    if ($labourCardCost == "") {
        $errors['labourCardCost'] = 'Labour card cost is required';
    }

    if ($labourCardCurrency == "") {
        $errors['labourCardCurrency'] = 'Labour card currency is required';
    }

    if ($labourCardChargeOn == "") {
        $errors['labourCardChargeOn'] = 'Labour card charge on is required';
    } else {
        if ($labourCardChargeOn == 1 && $labourCardChargeAccount == "") {
            $errors['labourCardChargeAccount'] = 'Labour card charge account is required';
        }
        if ($labourCardChargeOn == 2 && $labourCardChargeSupplier == "") {
            $errors['labourCardChargeSupplier'] = 'Labour card charge supplier is required';
        }
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    // upload file
    if ($_FILES['labourCardFile'] && isset($_FILES['labourCardFile']['name']) && $_FILES['labourCardFile']['name'] != '') {
        uploadFile('labourCardFile', $id, 4);
    }

    try {
        $stmt = $pdo->prepare("UPDATE `residence` SET 
        laborCardID=:laborCardID,
        laborCardFee=:laborCardFee,
        laborCardCur=:laborCardCur,
        laborCardSupplier=:laborCardSupplier,
        laborCardAccount=:laborCardAccount,
        stepfourUploader=:stepfourUploader,
        completedStep= 4
        WHERE residenceID = :id
        ");
        $stmt->execute([
            'laborCardID' => $labourCardNumber,
            'laborCardFee' => $labourCardCost,
            'laborCardCur' => $labourCardCurrency,
            'laborCardSupplier' => $labourCardChargeOn == '1' ? NULL : $labourCardChargeSupplier,
            'laborCardAccount' => $labourCardChargeOn == '1' ? $labourCardChargeAccount : NULL,
            'stepfourUploader' => $_SESSION['user_id'],
            'id' => $id
        ]);

        api_response(['status' => 'success', 'message' => 'Labour card set successfully']);
    } catch (PDOException $e) {
        api_response(['status' => 'error', 'message' => 'Error updating residence', 'error' => $e->getMessage()]);
    }
}

if ($action == 'setEVisa') {

    $id = filterInput('id');
    $eVisaCost = filterInput('eVisaCost');
    $eVisaCurrency = filterInput('eVisaCurrency');
    $eVisaChargeOn = filterInput('eVisaChargeOn');
    $eVisaChargeAccount = filterInput('eVisaChargeAccount');
    $eVisaChargeSupplier = filterInput('eVisaChargeSupplier');

    $errors = [];

    if ($eVisaCost == "") {
        $errors['eVisaCost'] = 'E-Visa cost is required';
    }

    if ($eVisaCurrency == "") {
        $errors['eVisaCurrency'] = 'E-Visa currency is required';
    }

    if ($eVisaChargeOn == "") {
        $errors['eVisaChargeOn'] = 'E-Visa charge on is required';
    } else {
        if ($eVisaChargeOn == 1 && $eVisaChargeAccount == "") {
            $errors['eVisaChargeAccount'] = 'E-Visa charge account is required';
        }
        if ($eVisaChargeOn == 2 && $eVisaChargeSupplier == "") {
            $errors['eVisaChargeSupplier'] = 'E-Visa charge supplier is required';
        }
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['eVisaFile'] && isset($_FILES['eVisaFile']['name']) && $_FILES['eVisaFile']['name'] != '') {
        uploadFile('eVisaFile', $id, 5);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        eVisaCost=:eVisaCost,
        eVisaCur=:eVisaCur,
        eVisaSupplier=:eVisaSupplier,
        eVisaStatus = 'submitted',
        eVisaAccount=:eVisaAccount,
        stepfiveUploader=:stepfiveUploader,
        completedStep = 5
    WHERE residenceID = :id
    ");


    $stmt->execute([
        'eVisaCost' => $eVisaCost,
        'eVisaCur' => $eVisaCurrency,
        'eVisaSupplier' => $eVisaChargeOn == 1 ? NULL : $eVisaChargeSupplier,
        'eVisaAccount' => $eVisaChargeOn == 1 ? $eVisaChargeAccount : NULL,
        'stepfiveUploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'E-Visa set successfully']);
}



// set change status
if ($action == 'setChangeStatus') {
    $id = filterInput('id');
    $changeStatusCost = filterInput('changeStatusCost');
    $changeStatusCurrency = filterInput('changeStatusCurrency');
    $changeStatusChargeOn = filterInput('changeStatusChargeOn');
    $changeStatusChargeAccount = filterInput('changeStatusChargeAccount');
    $changeStatusChargeSupplier = filterInput('changeStatusChargeSupplier');

    $errors = [];

    if ($changeStatusCost == "") {
        $errors['changeStatusCost'] = 'Change status cost is required';
    }

    if ($changeStatusCurrency == "") {
        $errors['changeStatusCurrency'] = 'Change status currency is required';
    }

    if ($changeStatusChargeOn == "") {
        $errors['changeStatusChargeOn'] = 'Change status charge on is required';
    } else {
        if ($changeStatusChargeOn == 1 && $changeStatusChargeAccount == "") {
            $errors['changeStatusChargeAccount'] = 'Change status charge account is required';
        }
        if ($changeStatusChargeOn == 2 && $changeStatusChargeSupplier == "") {
            $errors['changeStatusChargeSupplier'] = 'Change status charge supplier is required';
        }
    }


    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['changeStatusFile'] && isset($_FILES['changeStatusFile']['name']) && $_FILES['changeStatusFile']['name'] != '') {
        uploadFile('changeStatusFile', $id, 6);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        changeStatusCost=:changeStatusCost,
        changeStatusCur=:changeStatusCur,
        changeStatusSupplier=:changeStatusSupplier,
        changeStatusAccount=:changeStatusAccount,
        stepsixUploader=:stepsixUploader,
        completedStep = 6
    WHERE residenceID = :id
    ");

    $stmt->execute([
        'changeStatusCost' => $changeStatusCost,
        'changeStatusCur' => $changeStatusCurrency,
        'changeStatusSupplier' => $changeStatusChargeOn == 1 ? NULL : $changeStatusChargeSupplier,
        'changeStatusAccount' => $changeStatusChargeOn == 1 ? $changeStatusChargeAccount : NULL,
        'stepsixUploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'Change status set successfully']);
}

if ($action == 'setMedical') {
    $id = filterInput('id');
    $medicalCost = filterInput('medicalCost');
    $medicalCurrency = filterInput('medicalCurrency');
    $medicalChargeOn = filterInput('medicalChargeOn');
    $medicalChargeAccount = filterInput('medicalChargeAccount');
    $medicalChargeSupplier = filterInput('medicalChargeSupplier');


    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];

    if ($medicalCost == "") {
        $errors['medicalCost'] = 'Medical cost is required';
    }

    if ($medicalCurrency == "") {
        $errors['medicalCurrency'] = 'Medical currency is required';
    }

    if ($medicalChargeOn == "") {
        $errors['medicalChargeOn'] = 'Medical charge on is required';
    } else {
        if ($medicalChargeOn == 1 && $medicalChargeAccount == "") {
            $errors['medicalChargeAccount'] = 'Medical charge account is required';
        }
        if ($medicalChargeOn == 2 && $medicalChargeSupplier == "") {
            $errors['medicalChargeSupplier'] = 'Medical charge supplier is required';
        }
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['medicalFile'] && isset($_FILES['medicalFile']['name']) && $_FILES['medicalFile']['name'] != '') {
        uploadFile('medicalFile', $id, 7);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        medicalTCost=:medicalTCost,
        medicalTCur=:medicalTCur,
        medicalSupplier=:medicalSupplier,
        medicalAccount=:medicalAccount,
        stepsevenUpploader=:stepsevenUpploader,
        completedStep = 7
    WHERE residenceID = :id
    ");

    $stmt->execute([
        'medicalTCost' => $medicalCost,
        'medicalTCur' => $medicalCurrency,
        'medicalSupplier' => $medicalChargeOn == 1 ? NULL : $medicalChargeSupplier,
        'medicalAccount' => $medicalChargeOn == 1 ? $medicalChargeAccount : NULL,
        'stepsevenUpploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'Medical set successfully']);
}

if ($action == 'setEmiratesID') {
    $id = filterInput('id');
    $emiratesIDCost = filterInput('emiratesIDCost');
    $emiratesIDCurrency = filterInput('emiratesIDCurrency');
    $emiratesIDChargeOn = filterInput('emiratesIDChargeOn');
    $emiratesIDChargeAccount = filterInput('emiratesIDChargeAccount');
    $emiratesIDChargeSupplier = filterInput('emiratesIDChargeSupplier');

    if ($id == '') {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];
    if ($emiratesIDCost == '') {
        $errors['emiratesIDCost'] = 'Emirates ID cost is required';
    }

    if ($emiratesIDCurrency == '') {
        $errors['emiratesIDCurrency'] = 'Emirates ID currency is required';
    }

    if ($emiratesIDChargeOn == '') {
        $errors['emiratesIDChargeOn'] = 'Emirates ID charge on is required';
    } else {
        if ($emiratesIDChargeOn == 1 && $emiratesIDChargeAccount == '') {
            $errors['emiratesIDChargeAccount'] = 'Emirates ID charge account is required';
        }
        if ($emiratesIDChargeOn == 2 && $emiratesIDChargeSupplier == '') {
            $errors['emiratesIDChargeSupplier'] = 'Emirates ID charge supplier is required';
        }
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['emiratesIDFile'] && isset($_FILES['emiratesIDFile']['name']) && $_FILES['emiratesIDFile']['name'] != '') {
        uploadFile('emiratesIDFile', $id, 8);
    }


    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        emiratesIDCost=:emiratesIDCost,
        emiratesIDCur=:emiratesIDCur,
        emiratesIDSupplier=:emiratesIDSupplier,
        emiratesIDAccount=:emiratesIDAccount,
        stepEightUploader=:stepEightUploader,
        completedStep = 8
    WHERE residenceID = :id
    ");

    $stmt->execute([
        'emiratesIDCost' => $emiratesIDCost,
        'emiratesIDCur' => $emiratesIDCurrency,
        'emiratesIDSupplier' => $emiratesIDChargeOn == 1 ? NULL : $emiratesIDChargeSupplier,
        'emiratesIDAccount' => $emiratesIDChargeOn == 1 ? $emiratesIDChargeAccount : NULL,
        'stepEightUploader' => $_SESSION['user_id'],
        'id' => $id
    ]);
    api_response(['status' => 'success', 'message' => 'Emirates ID set successfully']);
}

if ($action == 'setVisaStamping') {
    $id = filterInput('id');
    $visaStampingExpiryDate = filterInput('visaStampingExpiryDate');
    $visaStampingCost = filterInput('visaStampingCost');
    $visaStampingCurrency = filterInput('visaStampingCurrency');
    $visaStampingChargeOn = filterInput('visaStampingChargeOn');
    $visaStampingChargeAccount = filterInput('visaStampingChargeAccount');
    $visaStampingChargeSupplier = filterInput('visaStampingChargeSupplier');

    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];


    if ($visaStampingExpiryDate == "") {
        $errors['visaStampingExpiryDate'] = 'Visa stamping expiry date is required';
    }
    if ($visaStampingCost == "") {
        $errors['visaStampingCost'] = 'Visa stamping cost is required';
    }

    if ($visaStampingCurrency == "") {
        $errors['visaStampingCurrency'] = 'Visa stamping currency is required';
    }

    if ($visaStampingChargeOn == "") {
        $errors['visaStampingChargeOn'] = 'Visa stamping charge on is required';
    }

    if ($visaStampingChargeOn == 1 && $visaStampingChargeAccount == "") {
        $errors['visaStampingChargeAccount'] = 'Visa stamping charge account is required';
    }
    if ($visaStampingChargeOn == 2 && $visaStampingChargeSupplier == "") {
        $errors['visaStampingChargeSupplier'] = 'Visa stamping charge supplier is required';
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['visaStampingFile'] && isset($_FILES['visaStampingFile']['name']) && $_FILES['visaStampingFile']['name'] != '') {
        uploadFile('visaStampingFile', $id, 9);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET 
        visaStampingCost=:visaStampingCost,
        visaStampingCur=:visaStampingCur,
        expiry_date=:expiry_date,
        LabourCardNumber=:LabourCardNumber,
        visaStampingSupplier=:visaStampingSupplier,
        visaStampingAccount=:visaStampingAccount,
        stepNineUpploader=:stepNineUpploader,
        completedStep = 9
    WHERE residenceID = :id
    ");

    $stmt->execute([
        'visaStampingCost' => $visaStampingCost,
        'visaStampingCur' => $visaStampingCurrency,
        'expiry_date' => $visaStampingExpiryDate,
        'LabourCardNumber' => $visaStampingLabourCardNumber,
        'visaStampingSupplier' => $visaStampingChargeOn == 1 ? NULL : $visaStampingChargeSupplier,
        'visaStampingAccount' => $visaStampingChargeOn == 1 ? $visaStampingChargeAccount : NULL,
        'stepNineUpploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'Visa stamping set successfully']);
}

if ($action ==   'setContractSubmission') {
    $id = filterInput('id');
    $contractSubmissionEID = filterInput('contractSubmissionEID');
    $contractSubmissionFile = filterInput('contractSubmissionFile');

    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];
    if ($contractSubmissionEID == "") {
        $errors['contractSubmissionEID'] = 'Emirates ID number is required';
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    if ($_FILES['contractSubmissionFile'] && isset($_FILES['contractSubmissionFile']['name']) && $_FILES['contractSubmissionFile']['name'] != '') {
        uploadFile('contractSubmissionFile', $id, 10);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` 
    SET EmiratesIDNumber=:EmiratesIDNumber,
        steptenUploader=:steptenUploader,
        status=2,
        completedStep = 10
    WHERE residenceID = :id
    ");

    $stmt->execute([
        'EmiratesIDNumber' => $contractSubmissionEID,
        'steptenUploader' => $_SESSION['user_id'],
        'id' => $id
    ]);

    api_response(['status' => 'success', 'message' => 'Contract submission set successfully']);
}


if ($action == 'setTawjeeh') {
    $id = filterInput('id');
    $tawjeehAmount = filterInput('tawjeehAmount');

    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];
    if ($tawjeehAmount == "") {
        $errors['tawjeehAmount'] = 'Tawjeeh amount is required';
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }


    $stmt = $pdo->prepare("
    UPDATE `residence` SET tawjeeh_charge=:tawjeehCharge, sale_price = sale_price + :tawjeehCharge WHERE residenceID = :id
    ");
    $stmt->execute([
        'tawjeehCharge' => $tawjeehAmount,
        'id' => $id
    ]);
    api_response(['status' => 'success', 'message' => 'Tawjeeh set successfully']);
}

if ($action == 'setILOE') {
    $id = filterInput('id');
    $iloeAmount = filterInput('iloeAmount');

    if ($id == "") {
        api_response(['status' => 'error', 'message' => 'Invalid input']);
    }

    $errors = [];
    if ($iloeAmount == "") {
        $errors['iloeAmount'] = 'ILOE amount is required';
    }

    if (count($errors)) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
    }

    $stmt = $pdo->prepare("
    UPDATE `residence` SET iloe_charge=:iloeCharge, sale_price = sale_price + :iloeCharge WHERE residenceID = :id
    ");
    $stmt->execute([
        'iloeCharge' => $iloeAmount,
        'id' => $id
    ]);
    api_response(['status' => 'success', 'message' => 'ILOE set successfully']);
}
