<?php
session_start();

include 'connection.php';
// Check if user is logged in
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

function uploadFile($file, $type = '')
{
  $uploadDir = 'letters/' . ($type == '' ? '' : $type . '_');
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }
  $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
  $newFilename = uniqid() . time() . '.' . $fileExt;
  $uploadFile = $uploadDir . $newFilename;

  if (!@move_uploaded_file($file['tmp_name'], $uploadFile)) {
    api_response(['status' => 'error', 'message' => 'Failed to upload ' . $type . ' file']);
  }

  return $type != '' ? "{$type}_{$newFilename}" : "{$newFilename}";
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// Check if invalid action
if (!in_array($action, ['searchCompanies', 'addCompany', 'loadCompany', 'updateCompany', 'deleteCompany', 'addQuota', 'cancelEmployee', 'loadAttachments', 'deleteImage'])) {
  api_response(['message' => 'Invalid action', 'status' => 'error']);
}

$typesLabels = array(
  'Mainland' => '<span class="badge bg-primary">Mainland</span>',
  'Freezone' => '<span class="badge bg-purple">Freezone</span>',
);

// searchCompanies
if ($action == 'searchCompanies') {
  $search = filterInput('search');
  $type = filterInput('type');

  $where = "";
  if ($search != '') {
    $where .= " AND (company.local_name LIKE '%{$search}%' OR company.company_name LIKE '%{$search}%' OR company_number LIKE '%{$search}%') ";
  }
  if ($type != '') {
    $where .= " AND company.company_type = '{$type}' ";
  }

  $stmt = $pdo->prepare("SELECT * FROM company WHERE 1=1 {$where}");
  $stmt->execute();
  $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (count($companies) == 0) {
    api_response(['message' => 'No records found', 'status' => 'error']);
  }

  $html = '';
  foreach ($companies as $company) {
    $html .= '<tr>
        <td>' . $company['company_number'] . '</td>
        <td>' . $company['company_name'] . '</td>
        <td>' . $typesLabels[$company['company_type']] . '</td>
        <td>' . $company['local_name'] . '</td>
        <td>' . ($company['company_expiry'] == '0000-00-00' ? 'n/a' : date('M d, Y', strtotime($company['company_expiry']))) . '</td>
        <td>' . $company['starting_quota'] . '</td>
        <td>
          <button class="btn btn-warning btn-edit" data-id="' . $company['company_id'] . '"><i class="fa fa-edit"></i></button>
          <button class="btn btn-danger btn-delete" data-id="' . $company['company_id'] . '"><i class="fa fa-trash"></i></button>
          <button class="btn btn-primary btn-quota" data-id="' . $company['company_id'] . '">Add Quota</button>
        </td>
      </tr>';
  }

  api_response(['html' => $html, 'status' => 'success']);
}

// addCompany
if ($action == 'addCompany') {
  $name = filterInput('nameAdd');
  $type = filterInput('typeAdd');
  $localName = filterInput('localNameAdd');
  $quota = filterInput('quotaAdd');
  $expiry = filterInput('expiryAdd');
  $number = filterInput('numberAdd');
  $username = filterInput('usernameAdd');
  $password = filterInput('passwordAdd');

  $errors = [];
  if ($name == '') {
    $errors['nameAdd'] = 'Company name is required';
  }
  if ($type == '') {
    $errors['typeAdd'] = 'Company type is required';
  }
  if ($quota == '') {
    $errors['quotaAdd'] = 'Starting quota is required';
  }
  if ($expiry == '') {
    $errors['expiryAdd'] = 'Expiry date is required';
  }
  if ($number == '') {
    $errors['numberAdd'] = 'Company number is required';
  }

  if (count($errors) > 0) {
    api_response(['errors' => $errors, 'status' => 'error', 'message' => 'form_errors']);
  }

  // Check if company number already exists
  $stmt = $pdo->prepare("SELECT * FROM company WHERE company_number = ?");
  $stmt->execute([$number]);
  $company = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($company) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['numberAdd' => 'Company number already exists']]);
  }

  $letterhead = isset($_FILES['letterheadAdd']) ? $_FILES['letterheadAdd'] : ['name' => ''];
  $stamp = isset($_FILES['stampAdd']) ? $_FILES['stampAdd'] : ['name' => ''];
  $signature = isset($_FILES['signatureAdd']) ? $_FILES['signatureAdd'] : ['name' => ''];

  $letterheadFile = '';
  $stampFile = '';
  $signatureFile = '';

  if ($letterhead['name'] != '') {
    $letterheadFile = uploadFile($letterhead, 'letterhead');
  }
  if ($stamp['name'] != '') {
    $stampFile = uploadFile($stamp, 'stamp');
  }
  if ($signature['name'] != '') {
    $signatureFile = uploadFile($signature, 'signature');
  }

  $stmt = $pdo->prepare("
    INSERT INTO company 
    (company_name, company_type, local_name, starting_quota, company_expiry, company_number, username, password, letterhead, stamp, signature) 
    VALUES (:name, :type, :local_name, :quota, :expiry, :number, :username, :password, :letterhead, :stamp, :signature)
  ");
  $stmt->execute([
    'name' => $name,
    'type' => $type,
    'local_name' => $localName,
    'quota' => $quota,
    'expiry' => $expiry,
    'number' => $number,
    'username' => $username,
    'password' => $password,
    'letterhead' => $letterheadFile,
    'stamp' => $stampFile,
    'signature' => $signatureFile,
  ]);

  api_response(['status' => 'success', 'message' => 'Company added successfully']);
}

// deleteCompany
if ($action == 'deleteCompany') {
  $id = filterInput('id');
  $stmt = $pdo->prepare("SELECT * FROM residence WHERE company = ?");
  $stmt->execute([$id]);
  $residence = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($residence) {
    api_response(['status' => 'error', 'message' => 'Company is in use with residence']);
  }

  $stmt = $pdo->prepare("DELETE FROM company WHERE company_id = ?");
  $stmt->execute([$id]);
  api_response(['status' => 'success', 'message' => 'Company deleted successfully']);
}

// loadCompany
if ($action == 'loadCompany') {
  $id = filterInput('id');
  $stmt = $pdo->prepare("SELECT * FROM company WHERE company_id = ?");
  $stmt->execute([$id]);
  $company = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$company) {
    api_response(['status' => 'error', 'message' => 'Company not found']);
  }

  api_response(['status' => 'success', 'company' => $company]);
}

// updateCompany
if ($action == 'updateCompany') {
  $id = filterInput('idEdit');
  $name = filterInput('nameEdit');
  $type = filterInput('typeEdit');
  $localName = filterInput('localNameEdit');
  $quota = filterInput('quotaEdit');
  $expiry = filterInput('expiryEdit');
  $number = filterInput('numberEdit');
  $username = filterInput('usernameEdit');
  $password = filterInput('passwordEdit');

  $errors = [];
  if ($name == '') {
    $errors['nameEdit'] = 'Company name is required';
  }
  if ($type == '') {
    $errors['typeEdit'] = 'Company type is required';
  }
  if ($quota == '') {
    $errors['quotaEdit'] = 'Starting quota is required';
  }
  if ($expiry == '') {
    $errors['expiryEdit'] = 'Expiry date is required';
  }
  if ($number == '') {
    $errors['numberEdit'] = 'Company number is required';
  }

  if (count($errors) > 0) {
    api_response(['errors' => $errors, 'status' => 'error', 'message' => 'form_errors']);
  }

  // Check if company number already exists (excluding current company)
  $stmt = $pdo->prepare("SELECT * FROM company WHERE company_number = ? AND company_id != ?");
  $stmt->execute([$number, $id]);
  $company = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($company) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['numberEdit' => 'Company number already exists']]);
  }

  // Load existing company data to preserve old file references
  $stmt = $pdo->prepare("SELECT letterhead, stamp, signature FROM company WHERE company_id = ?");
  $stmt->execute([$id]);
  $existingCompany = $stmt->fetch(PDO::FETCH_ASSOC);

  $letterhead = isset($_FILES['letterHeadEdit']) ? $_FILES['letterHeadEdit'] : ['name' => ''];
  $stamp = isset($_FILES['stampEdit']) ? $_FILES['stampEdit'] : ['name' => ''];
  $signature = isset($_FILES['signatureEdit']) ? $_FILES['signatureEdit'] : ['name' => ''];

  $letterheadFile = $existingCompany['letterhead'];
  $stampFile = $existingCompany['stamp'];
  $signatureFile = $existingCompany['signature'];
  $oldLetterheadFile = '';
  $oldStampFile = '';
  $oldSignatureFile = '';

  if ($letterhead['name'] != '' && $letterhead['error'] === UPLOAD_ERR_OK) {
    $letterheadFile = uploadFile($letterhead, 'letterhead');
    $oldLetterheadFile = $existingCompany['letterhead'];
  }
  if ($stamp['name'] != '' && $stamp['error'] === UPLOAD_ERR_OK) {
    $stampFile = uploadFile($stamp, 'stamp'); // Processed by remove.bg, saved as PNG
    $oldStampFile = $existingCompany['stamp'];
  }
  if ($signature['name'] != '' && $signature['error'] === UPLOAD_ERR_OK) {
    $signatureFile = uploadFile($signature, 'signature'); // Processed by remove.bg, saved as PNG
    $oldSignatureFile = $existingCompany['signature'];
  }

  $stmt = $pdo->prepare("
    UPDATE company 
    SET 
      company_name = :name, 
      company_type = :type, 
      local_name = :local_name, 
      starting_quota = :quota, 
      company_expiry = :expiry, 
      company_number = :number,
      username = :username,
      password = :password,
      letterhead = :letterhead,
      stamp = :stamp,
      signature = :signature
    WHERE company_id = :id
  ");
  $stmt->execute([
    'name' => $name,
    'type' => $type,
    'local_name' => $localName,
    'quota' => $quota,
    'expiry' => $expiry,
    'number' => $number,
    'username' => $username,
    'password' => $password,
    'letterhead' => $letterheadFile,
    'stamp' => $stampFile,
    'signature' => $signatureFile,
    'id' => $id,
  ]);

  // Delete old files if they were replaced
  if ($oldLetterheadFile != '' && $oldLetterheadFile != $letterheadFile) {
    @unlink('letters/' . $oldLetterheadFile);
  }
  if ($oldStampFile != '' && $oldStampFile != $stampFile) {
    @unlink('letters/' . $oldStampFile);
  }
  if ($oldSignatureFile != '' && $oldSignatureFile != $signatureFile) {
    @unlink('letters/' . $oldSignatureFile);
  }

  api_response(['status' => 'success', 'message' => 'Company updated successfully']);
}

// addQuota
if ($action == 'addQuota') {
  $id = filterInput('id');
  $quota = filterInput('quota');

  $stmt = $pdo->prepare("SELECT * FROM company WHERE company_id = ?");
  $stmt->execute([$id]);
  $company = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$company) {
    api_response(['status' => 'error', 'message' => 'Company not found']);
  }
  if ($quota == '' || $quota <= 0) {
    api_response(['status' => 'error', 'message' => 'Quota is required']);
  }

  $stmt = $pdo->prepare("UPDATE company SET starting_quota = starting_quota + ? WHERE company_id = ?");
  $stmt->execute([$quota, $id]);

  api_response(['status' => 'success', 'message' => 'Quota added successfully']);
}

// cancelEmployee
if ($action == 'cancelEmployee') {
  $employeeId = filterInput('employeeId');
  $cancelDate = filterInput('cancelDate');
  $remarks = filterInput('remarks');
  $cancelPaper = isset($_FILES['cancelPaper']) ? $_FILES['cancelPaper'] : ['name' => ''];

  // Check resident
  $stmt = $pdo->prepare("SELECT * FROM residence WHERE residenceID = ?");
  $stmt->execute([$employeeId]);
  $employee = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$employee) {
    api_response(['status' => 'error', 'message' => 'Employee not found']);
  }

  $errors = [];
  if ($cancelDate == '') {
    $errors['cancelDate'] = 'Cancel date is required';
  }
  if ($cancelPaper['name'] == '') {
    $errors['cancelPaper'] = 'Cancel paper is required';
  }

  if (count($errors)) {
    api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
  }

  $uploadDir = 'residence/';
  $fileExt = pathinfo($cancelPaper['name'], PATHINFO_EXTENSION);
  $newFilename = uniqid() . '.' . $fileExt;
  $uploadFile = $uploadDir . $newFilename;

  if (!move_uploaded_file($cancelPaper['tmp_name'], $uploadFile)) {
    api_response(['status' => 'error', 'message' => 'Failed to upload cancel paper']);
  }

  // Add file to database
  $stmt = $pdo->prepare("
    INSERT INTO `residencedocuments` 
    (`ResID`, `file_name`, `original_name`, `fileType`)
    VALUES 
    (:ResID, :file_name, :original_name, :fileType)
  ");
  $stmt->execute([
    'ResID' => $employeeId,
    'file_name' => $newFilename,
    'original_name' => $cancelPaper['name'],
    'fileType' => '14'
  ]);

  $statement = $pdo->prepare("
    UPDATE 
    `residence` 
    SET 
      `cancelled` = '1', 
      `cancelDate` = :cancelDate, 
      `cancelRemarks` = :remarks, 
      `canceledBy` = :canceledBy 
    WHERE `residenceID` = :empid
  ");
  $statement->bindParam(':empid', $employeeId);
  $statement->bindParam(':cancelDate', $cancelDate);
  $statement->bindParam(':remarks', $remarks);
  $statement->bindParam(':canceledBy', $_SESSION['user_id']);
  $statement->execute();

  api_response(['status' => 'success', 'message' => 'Employee cancelled successfully']);
}

// loadAttachments
if ($action == 'loadAttachments') {
  $id = filterInput('id');
  $stmt = $pdo->prepare("SELECT * FROM `residencedocuments` WHERE `ResID` = :res");
  $stmt->bindParam(':res', $id);
  $stmt->execute();
  $attachments = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $attachmentTypes = [
    '1' => 'Passport',
    '11' => 'Photo',
    '12' => 'ID (Front) / Good Conduct Cert',
    '13' => 'ID (Back)',
    '2' => 'Offer Letter',
    '3' => 'Insurance',
    '4' => 'Labour Card',
    '5' => 'eVisa Typing',
    '6' => 'Change Status',
    '7' => 'Medical Typing',
    '8' => 'Emirates ID Typing',
    '9' => 'Visa Stamping',
    '10' => 'Contract Submission',
    '14' => 'Cancel Paper',
  ];

  $imageFileExtens = ['jpg', 'jpeg', 'png', 'gif'];
  $docFileExtens = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar'];

  $html = '<div class="row">';
  foreach ($attachments as $attachment) {
    $fileExt = strtolower(pathinfo($attachment['original_name'], PATHINFO_EXTENSION));
    $downloadLink = '/downloadResDocuments.php?id=' . $attachment['ResidenceDocID'];
    $fileName = isset($attachmentTypes[$attachment['fileType']]) ? $attachmentTypes[$attachment['fileType']] : $attachment['original_name'];

    $html .= '<div class="col-md-6 mb-4">';
    $html .= '<div class="card h-100">';
    $html .= '<div class="card-header bg-light">';
    $html .= '<h5 class="mb-0"><span class="fiv-cla fiv-icon-' . $fileExt . '"></span> ' . $fileName . '</h5>';
    $html .= '</div>';
    $html .= '<div class="card-body">';

    // Display preview based on file type
    if (in_array($fileExt, $imageFileExtens)) {
      $html .= '<div class="text-center mb-3">';
      $html .= '<img src="/residence/' . $attachment['file_name'] . '" class="img-fluid" style="max-height: 300px;">';
      $html .= '</div>';
    } else if ($fileExt === 'pdf') {
      $html .= '<div class="embed-responsive embed-responsive-16by9 mb-3">';
      $html .= '<iframe class="embed-responsive-item" src="/residence/' . $attachment['file_name'] . '" style="width:100%; height:300px;" allowfullscreen></iframe>';
      $html .= '</div>';
    } else if (in_array($fileExt, $docFileExtens)) {
      $hostName = $_SERVER['HTTP_HOST'];
      $schema = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'http';
      $fileURL = $schema . '://' . $hostName . '/residence/' . $attachment['file_name'];
      $previewLink = 'https://drive.google.com/viewer/viewer?embedded=true&url=' . urlencode($fileURL);
      
      $html .= '<div class="embed-responsive embed-responsive-16by9 mb-3">';
      $html .= '<iframe class="embed-responsive-item" src="' . $previewLink . '" style="width:100%; height:300px;" allowfullscreen></iframe>';
      $html .= '</div>';
    } else {
      $html .= '<div class="text-center mb-3">';
      $html .= '<i class="fa fa-file-' . $fileExt . ' fa-5x"></i>';
      $html .= '<p class="mt-2">Preview not available for this file type</p>';
      $html .= '</div>';
    }
    
    // Download button
    $html .= '<a href="' . $downloadLink . '" class="btn btn-primary btn-block w-100"><i class="fa fa-download"></i> Download File</a>';
    $html .= '</div>'; // end card-body
    $html .= '</div>'; // end card
    $html .= '</div>'; // end col
  }
  $html .= '</div>'; // end row

  api_response(['status' => 'success', 'html' => $html]);
}

// deleteImage
if ($action == 'deleteImage') {
  $id = filterInput('id');
  $type = filterInput('type');

  if ($id == "" || $type == "") {
    api_response(['status' => 'error', 'message' => 'Invalid request']);
  }

  $stmt = $pdo->prepare("SELECT * FROM company WHERE company_id = ?");
  $stmt->execute([$id]);
  $company = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($type == 'letterhead' && $company['letterhead']) {
    @unlink('letters/' . $company['letterhead']);
    $stmt = $pdo->prepare("UPDATE company SET letterhead = '' WHERE company_id = ?");
    $stmt->execute([$id]);
  } else if ($type == 'stamp' && $company['stamp']) {
    @unlink('letters/' . $company['stamp']);
    $stmt = $pdo->prepare("UPDATE company SET stamp = '' WHERE company_id = ?");
    $stmt->execute([$id]);
  } else if ($type == 'signature' && $company['signature']) {
    @unlink('letters/' . $company['signature']);
    $stmt = $pdo->prepare("UPDATE company SET signature = '' WHERE company_id = ?");
    $stmt->execute([$id]);
  } else {
    api_response(['status' => 'error', 'message' => 'Invalid type provided or no file to delete']);
  }

  api_response(['status' => 'success', 'message' => 'File deleted successfully']);
}
?>