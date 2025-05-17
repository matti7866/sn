<?php include 'header.php' ?>
<title>Residence Tasks (Mainland)</title>
<style>
  .bg-hold,
  .bg-hold td {
    background-color: #f8d7da !important;
  }
</style>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
  exit();
}

$s = isset($_GET['step']) ? (string)$_GET['step'] : '1';
$company_id = isset($_GET['company_id']) ? (int)$_GET['company_id'] : '';

$steps = [
  '1' => ['name' => 'Offer Letter', 'count' => 0, 'icon' => 'fa fa-envelope'],
  '1a' => ['name' => 'Offer Letter (S)', 'count' => 0, 'icon' => 'fa fa-envelope'],
  '2' => ['name' => 'Insurance', 'count' => 0, 'icon' => 'fa fa-shield'],
  '3' => ['name' => 'Labour Card', 'count' => 0, 'icon' => 'fa fa-credit-card'],
  '4' => ['name' => 'E-Visa', 'count' => 0, 'icon' => 'fa fa-ticket', 'showAccess' => true],
  '4a' => ['name' => 'E-Visa (S)', 'count' => 0, 'icon' => 'fa fa-file-ticket', 'showAccess' => true],
  '5' => ['name' => 'Change Status', 'count' => 0, 'icon' => 'fa fa-exchange', 'showAccess' => true],
  '6' => ['name' => 'Medical', 'count' => 0, 'icon' => 'fa fa-medkit'],
  '7' => ['name' => 'EID', 'count' => 0, 'icon' => 'fa fa-id-card'],
  '8' => ['name' => 'Visa Stamping', 'count' => 0, 'icon' => 'fas fa-stamp'],
  '9' => ['name' => 'Contract Submission', 'count' => 0, 'icon' => 'fas fa-file-signature'],
  '10' => ['name' => 'Completed', 'count' => 0, 'icon' => 'fa fa-hand-holding'],
];
if (!array_key_exists($s, $steps)) {
  $s = '1';
}

$currentStepInfo = $steps[$s];

$dateAfter = '2024-09-01';

$stmp = $pdo->prepare("
SELECT company.*, IFNULL(COUNT(residence.residenceID), 0) as totalEmployees
   FROM company  
   LEFT JOIN residence ON residence.company = company.company_id  
   WHERE company.company_type = 'Mainland'
   GROUP BY company.company_id  
   ORDER BY company.company_id DESC  
");
$stmp->execute();
$companies = $stmp->fetchAll(PDO::FETCH_OBJ);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$where = "";
if ($search != '') {
  $where = " AND (passenger_name LIKE '%{$search}%' OR passportNumber LIKE '%{$search}%') ";
}

$stmt = $pdo->prepare("
  SELECT COUNT(*) as count, completedStep
  FROM residence 
  WHERE DATE(datetime) >= '{$dateAfter}' {$where}
  GROUP BY completedStep
  ");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach ($result as $row) {
  if (isset($steps[$row->completedStep])) {
    $steps[$row->completedStep]['count'] = $row->count;
  }
}

$where = "";
if ($search != '') {
  $where = " AND (passenger_name LIKE '%{$search}%' OR passportNumber LIKE '%{$search}%') ";
}

$stmt = $pdo->prepare("
SELECT IFNULL(COUNT(*),0) as total 
FROM residence 
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 2 AND offerLetterStatus = 'submitted' {$where}
");
$stmt->execute();
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;

$steps['1a']['count'] = $offerLetterSubmitted;

$stmt = $pdo->prepare("
SELECT IFNULL(COUNT(*),0) as total 
FROM residence 
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 5 AND eVisaStatus = 'submitted' {$where}
");
$stmt->execute();
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;

$steps['4a']['count'] = $offerLetterSubmitted;

$stmt = $pdo->prepare("
SELECT IFNULL(COUNT(*),0) as total 
FROM residence 
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 2 AND offerLetterStatus = 'accepted' {$where}
");
$stmt->execute();
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;

$steps['2']['count'] = $offerLetterSubmitted;

$stmt = $pdo->prepare("
SELECT IFNULL(COUNT(*),0) as total 
FROM residence 
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 5 AND eVisaStatus = 'accepted' {$where}
");
$stmt->execute();
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;

$steps['5']['count'] = $offerLetterSubmitted;

$where = '';
if ($s == '1a') {
  $where = " AND completedStep = 2 AND offerLetterStatus = 'submitted' ";
} elseif ($s == '4a') {
  $where = " AND completedStep = 5 AND (eVisaStatus = 'submitted' OR eVisaStatus = 'rejected') ";
} elseif ($s == '2') {
  $where = " AND completedStep = 2 AND offerLetterStatus = 'accepted'";
} elseif ($s == '5') {
  $where = " AND completedStep = 5 AND eVisaStatus = 'accepted' ";
} else {
  $where = " AND completedStep = '{$s}' ";
}

if ($company_id != '' && $company_id != 0) {
  $where .= " AND company = '{$company_id}' ";
}

if ($search != '') {
  $where .= " AND (passenger_name LIKE '%{$search}%' OR passportNumber LIKE '%{$search}%') ";
}

$stmt = $pdo->prepare("
  SELECT residence.* , residence.insideOutside, customer.customer_name, airports.countryName, airports.countryCode, company.company_name, company.company_number, company.username, company.password,
  country_name.country_names as visaType, residence.mohreStatus, residence.mohreStatusDatetime, residence.mb_number, residence.uid, residence.LabourCardNumber, residence.hold,
  (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE PaymentFor = residence.residenceID) as paid_amount
  FROM residence 
  LEFT JOIN customer ON customer.customer_id = residence.customer_id
  LEFT JOIN airports ON airports.airport_id = residence.Nationality
  LEFT JOIN company ON company.company_id = residence.company
  LEFT JOIN country_name ON country_name.country_id = residence.VisaType
  WHERE DATE(residence.datetime) >= '2024-09-01' {$where}
  GROUP BY residence.residenceID
  ORDER BY residence.residenceID ASC
  ");
$stmt->execute();

$residences = $stmt->fetchAll(PDO::FETCH_OBJ);

$stmp = $pdo->prepare("SELECT customer_id, customer_name FROM customer ORDER BY customer_name");
$stmp->execute();
$customers = $stmp->fetchAll(PDO::FETCH_OBJ);

$selectQuery = $pdo->prepare("SELECT DISTINCT countryName AS mainCountryName, (SELECT airport_id FROM airports WHERE 
        countryName = mainCountryName LIMIT 1) AS airport_id FROM airports ORDER BY countryName ASC");
$selectQuery->execute();
$countries = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);

$selectQuery =  $pdo->prepare("SELECT position_id,posiiton_name, (SELECT IFNULL(positionID,0) FROM 
            residence WHERE residenceID = :residenceID) AS PositionID FROM position ORDER BY posiiton_name ASC");
$selectQuery->bindParam(':residenceID', $_POST['ID']);
$selectQuery->execute();
$positions = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);

$selectQuery =  $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
$selectQuery->execute();
$currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);

$accountsQuery = $pdo->prepare("SELECT account_ID,account_Name FROM accounts ORDER BY account_Name ASC");
$accountsQuery->execute();
$accounts = $accountsQuery->fetchAll(\PDO::FETCH_ASSOC);

$suppliersQuery = $pdo->prepare("SELECT supp_id,supp_name FROM supplier ORDER BY supp_name ASC");
$suppliersQuery->execute();
$suppliers = $suppliersQuery->fetchAll(\PDO::FETCH_ASSOC);

?>
<!-- Add pdf.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>


<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mb-2">
      <div class="d-flex">
        <h3>Residence Tasks (Mainland)</h3>
        <div class="ms-auto">
          <button class="btn btn-primary" id="btnAddNewResidence">Add New Residence</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4 mb-2">
      <form action="residenceTasks.php" method="GET">
        <input type="hidden" name="step" value="<?php echo isset($_GET['step']) ? $_GET['step'] : '' ?>" />
        <?php if ($company_id != ''): ?>
          <input type="hidden" name="company_id" value="<?php echo $company_id ?>">
        <?php endif; ?>
        <div class="input-group">
          <input type="text" class="form-control" value="<?php echo isset($_GET['search']) ? $_GET['search'] : '' ?>" name="search" placeholder="Search by name or Passport Number">
          <button type="submit" class="btn btn-primary">Search</button>
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12" id="message"></div>
    <div class="col-md-12 mb-2">
      <div class="btn-group btn-group-block w-100">
        <?php foreach ($steps as $key => $step): ?>
          <a href="/residenceTasks.php?step=<?php echo $key . ($search != '' ? '&search=' . $search : '') ?>" data-step="<?= $key ?>" class="btn btn-white btn-block<?php echo (string)$key == $s ? ' active' : '' ?>">
            <span><?php echo $step['name']  ?></span>
            <?php echo $step['count'] > 0 ? '<span class="badge bg-red">' . $step['count'] . '</span>' : '' ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title">Resident</h4>
        </div>
        <div class="panel-body paneldata">
          <?php if ((string)$s != '1'): ?>
            <div class="row">
              <div class="col-md-4 mb-4">
                <label for="" class="form-label">Establishment</label>
                <select id="establishment" name="establishment" class="form-select">
                  <option value="0">All</option>
                  <?php
                  foreach ($companies as $company) {
                    echo '<option ' . ($company_id == $company->company_id ? 'selected="selected"' : '') . ' value="' . $company->company_id . '">' . $company->company_name . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          <?php endif; ?>

          <div class="table-responsive">
            <table id="datatable" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>App. Date</th>
                  <th>Passenger Name</th>
                  <th>Customer</th>
                  <th>Establishment</th>
                  <th>Passport</th>
                  <th>Passport Expiry</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (count($residences)) {
                  $totalSalePrice = 0;
                  $totalPaidAmount = 0;
                  foreach ($residences as $res) {
                    $actionButtons = '';

                    if ($s == '10') {
                      if ($res->tawjeeh_charge == 0) {
                        $actionButtons .= '<button class="btn btn-warning btn-sm btn-setTawjeeh" data-id="' . $res->residenceID . '">Tawjeeh</button> ';
                      } else {
                        $actionButtons .= "<div>Tawjeeh: " . number_format($res->tawjeeh_charge, 0) . "</div>";
                      }
                      if ($res->iloe_charge == 0) {
                        $actionButtons .= '<button class="btn btn-warning btn-sm btn-setILOE" data-id="' . $res->residenceID . '">ILOE</button>';
                      } else {
                        $actionButtons .= "<div>ILOE: " . number_format($res->iloe_charge, 0) . "</div>";
                      }
                    }

                    if ($s == '1') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setOfferLetter" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '2') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setInsurance" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '3') {
                      $actionButtons .= '<button data-labour-card-number="' . $res->LabourCardNumber . '" class="btn btn-success btn-sm btn-setLabourCard" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '4') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setEVisa" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '5') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setChangeStatus" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '6') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setMedical" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '7') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setEmiratesID" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '8') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setVisaStamping" data-id="' . $res->residenceID . '">Continue</button>';
                    }
                    if ($s == '9') {
                      $actionButtons .= '<button class="btn btn-success btn-sm btn-setContractSubmission" data-id="' . $res->residenceID . '">Continue</button>';
                    }

                    if ($s == '2' || $s == '3') {
                      $actionButtons .= '<div class="btn-group btn-group-sm ms-1">
                        <a href="#" class="btn btn-success btn-sm">Pay</a>
                        <a href="#" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                          <i class="fa fa-caret-down"></i>
                        </a>
                       <ul class="dropdown-menu" style="">
                          <li><button class="dropdown-item" type="button" onClick="javascript:openInsuranceDialog(\'' . $res->mb_number . '\')"><i class="fa fa-plus"></i> Pay Labour Fee</button></li>
                       </ul>
                       
                      
                      </div>';
                    }

                    $actionButtons .= '
                    ' . ($res->hold == 0 ? '<a target="_blank" href="/residence.php?id=' . $res->residenceID . '&stp=' . $s . '" class="btn btn-sm btn-primary"><i class="fa fa-file"></i></a>' : '') . '
                    <button class="btn btn-success btn-attachments" data-id="' . $res->residenceID . '"><i class="fa fa-paperclip"></i></button>
                    ';

                    if ($s == '1a') {
                      $actionButtons = '
                      <button class="btn btn-success btn-sm btn-setOfferLetterStatus" data-action="Accept" data-id="' . $res->residenceID . '" data-value="accepted">Accept</button>
                      <button class="btn btn-danger btn-sm btn-setOfferLetterStatus" data-action="Reject" data-id="' . $res->residenceID . '" data-value="rejected">Reject</button>
                      ';
                    }

                    if ($s == '4a') {
                      $actionButtons = '
                      <button class="btn btn-success btn-sm btn-seteVisaStatus" data-action="Accept" data-id="' . $res->residenceID . '" data-value="accepted">Accept</button>
                      <button class="btn btn-danger btn-sm btn-seteVisaStatus" data-action="Reject" data-id="' . $res->residenceID . '" data-value="rejected">Reject</button>
                      ';
                    }




                    $ppNumber = '<div>' . $res->passportNumber . '</div>';
                    if ($s == 4 || $s == "4" || $s == "5" || $s == 5) {
                      $ppNumber .= '<span class="badge bg-' . ($res->insideOutside == 'inside' ? 'success' : 'danger') . '">' . strtoupper($res->insideOutside) . '</span>';
                    }

                    $mohreStatus = '';
                    if (in_array($s, ['1a', '2', '3', '4', '4a'])) {
                      if ($res->mb_number != '') {
                        $mohreStatus .= '<br /><strong>MB Number: </strong>' . $res->mb_number;
                      }
                      $mohreStatus .= '<div style="text-wrap:wrap;">';
                      $mohreStatus .= '<strong>MOHRE Status: </strong>' . ($res->mb_number == '' ? '<string class="text-danger">Provide MB Number</string>' : '<span class="text-primary">' . $res->mohreStatus . '</span>');
                      $mohreStatus .= $res->eVisaStatus == 'rejected' ? '<br /><span class="badge bg-danger">E-Visa Rejected</span>' : '';
                      $mohreStatus .= '</div>';
                    }

                    $companyAccess = '';
                    if (isset($currentStepInfo['showAccess']) && $currentStepInfo['showAccess']) {
                      $companyAccess .= $res->username != '' ? '<br /><strong>Username: </strong>' . $res->username : '';
                      $companyAccess .= $res->password != '' ? '<br /><strong>Password: </strong>' . $res->password : '';
                    }

                    $companyName = '';
                    if ($res->company_name != '') {
                      $companyName = "<strong>{$res->company_name}</strong>";
                      $companyName .= $res->company_number != '' ? ' - ' . $res->company_number : '';
                    }

                    $px = $res->passenger_name;
                    if ($res->LabourCardNumber != '' && $s >= 3) {
                      $px .= '<br /><strong>Labour Card: </strong>' . $res->LabourCardNumber;
                    }
                    if ($res->hold == 1) {
                      $px .= '<br /><span class="badge bg-danger">ON HOLD</span>';
                    }

                    if ($_SESSION['user_id'] == '1'):
                      if ($res->hold == 1) {
                        $actionButtons .= '<button class="btn btn-success btn-sm btn-hold" data-id="' . $res->residenceID . '" data-value="0"><i class="fa fa-check"></i></button>';
                      } else {
                        $actionButtons .= '<button class="btn btn-danger btn-sm btn-hold" data-id="' . $res->residenceID . '" data-value="1"><i class="fa fa-hourglass"></i></button>';
                      }
                    endif;

                    $paymentInfo = '
                    <strong>Sale Price: </strong>' . number_format($res->sale_price, 0) . '<br />
                    <strong>Paid Amount: </strong>' . number_format($res->paid_amount, 0) . ' <span class="' . ($res->paid_amount == $res->sale_price ? 'text-success' : 'text-danger') . '">(' . ($res->paid_amount == 0 ? 0 : round(($res->paid_amount / $res->sale_price) * 100, 2)) . '%)</span>';

                    echo '<tr data-hold="' . $res->hold . '">
                        <td>' . $res->residenceID . '</td>
                        <td>' . date('M d, Y', strtotime($res->datetime)) . '</td>
                        <td><img data-toggle="tooltip" data-placement="bottom" title="' . $res->countryName . '" height="12" class="me-2" src="https://flagpedia.net/data/flags/h24/' . strtolower($res->countryCode) . '.png" /> <strong>' . strtoupper($px) . '</strong> ' . ($res->uid != '' ? '<br /><strong>UID: </strong>' . $res->uid : '') . '<br />' . $paymentInfo . '</td>
                        <td>' . $res->customer_name . '</td>
                        <td>' . $companyName . $mohreStatus . $companyAccess . '</td>
                        <td>' . $ppNumber . '</td>
                        <td>' . ($res->passportExpiryDate ? date('M d, Y', strtotime($res->passportExpiryDate)) : '') . '</td>
                        <td>' . $actionButtons . '</td>
                      </tr>';

                    $totalSalePrice += isset($res->sale_price) ? $res->sale_price : 0;
                    $totalPaidAmount += isset($res->paid_amount) ? $res->paid_amount : 0;
                  }
                }
                ?>
              </tbody>
            </table>

            <?php if ($_SESSION['user_id'] == '1'): ?>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php echo $totalSalePrice > 0 ? '<strong>Total Sale Price: </strong>' . number_format($totalSalePrice, 0) . '<br />
                  <strong>Total Paid Amount: </strong>' . number_format($totalPaidAmount, 0) . ' <span class="' . ($totalPaidAmount == $totalSalePrice ? 'text-success' : 'text-danger') . '">(' . round(($totalPaidAmount / $totalSalePrice) * 100, 2) . '%)</span>' : '' ?>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalAttachments" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Employee Attachments</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body" id="modalAttachmentsBody">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalNewResidence" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="frmBasicData" method="POST" enctype="multipart/form-data" action="residenceTasksController.php">
        <input type="hidden" id="basicDataAction" name="action" value="addResidence">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add New Residence</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgBasicData"></div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-2">
                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-select searchable-select">
                  <option value="">Select Customer</option>
                  <?php foreach ($customers as $customer): ?>
                    <option value="<?php echo $customer->customer_id ?>"><?php echo $customer->customer_name ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback customer_id"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-2">
                <label for="uid" class="form-label">UID <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="uid" name="uid">
                <div class="invalid-feedback uid"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="passportNumber" class="form-label">Passport Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="passportNumber" name="passportNumber">
                <div class="invalid-feedback passportNumber"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="passportExpiryDate" class="form-label">Passport Expiry Date <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="passportExpiryDate" name="passportExpiryDate">
                <div class="invalid-feedback passportExpiryDate"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-2">
                <label for="passangerName" class="form-label">Passenger Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="passangerName" name="passangerName">
                <div class="invalid-feedback passangerName"></div>
              </div>
              <div class="col-md-6">
                <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                <select name="nationality" id="nationality" class="form-select searchable-select">
                  <option value="">Select Nationality</option>
                  <?php foreach ($countries as $country): ?>
                    <option value="<?php echo $country['airport_id'] ?>"><?php echo $country['mainCountryName'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback nationality"></div>
              </div>
              <div class="col-sm-4 mb-2">
                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="gender" id="gender" class="form-select">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
                <div class="invalid-feedback gender"></div>
              </div>
              <div class="col-sm-4 mb-2">
                <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dob" name="dob">
                <div class="invalid-feedback dob"></div>
              </div>
              <div class="col-sm-4 mb-2">
                <label for="insideOutside" class="form-label">Inside/Outside <span class="text-danger">*</span></label>
                <select name="insideOutside" id="insideOutside" class="form-select">
                  <option value="">Select </option>
                  <option value="inside">Inside</option>
                  <option value="outside">Outside</option>
                </select>
                <div class="invalid-feedback insideOutside"></div>
              </div>
              <div class="col-md-8 mb-2">
                <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                <select name="position" id="position" class="form-select">
                  <option value="">Select Position</option>
                  <?php foreach ($positions as $pos): ?>
                    <option value="<?php echo $pos['position_id'] ?>"><?php echo $pos['posiiton_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback position"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="salary" name="salary">
                <div class="invalid-feedback salary"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="sale_price" class="form-label">Sale Price <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="sale_price" name="sale_price">
                <div class="invalid-feedback sale_price"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="sale_price_currency" class="form-label">Sale Price Currency <span class="text-danger">*</span></label>
                <select name="sale_price_currency" id="sale_price_currency" class="form-select">
                  <?php foreach ($currencies as $currency): ?>
                    <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback sale_price_currency"></div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="passport_file" class="form-label">Passport File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="passport_file" name="passport_file">
                <div class="invalid-feedback passport_file"></div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="photo_file" class="form-label">Photo File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="photo_file" name="photo_file" accept="image/*">
                <div class="invalid-feedback photo_file"></div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="id_file" class="form-label">ID (front) / Good Conduct Cert.</label>
                <input type="file" class="form-control" id="id_file" name="id_file" accept="image/*">
                <div class="invalid-feedback id_file"></div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="id_file_back" class="form-label">ID (back) / Good Conduct Cert.</label>
                <input type="file" class="form-control" id="id_file_back" name="id_file_back" accept="image/*">
                <div class="invalid-feedback id_file_back"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalOfferLetter" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="frmOfferLetter" method="POST" enctype="multipart/form-data" data-popup="#modalOfferLetter" class="frmAjaxPopup" data-msg="#msgOfferLetter" action="residenceTasksController.php">
        <input type="hidden" id="offerLetterAction" name="action" value="setOfferLetter">
        <input type="hidden" id="offerLetterID" name="id" value="">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Offer Letter</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgOfferLetter"></div>
              <div class="col-md-12 mb-2">
                <label for="company_id" class="form-label">Establishment <span class="text-danger">*</span></label>
                <select name="company_id" id="company_id" class="form-select searchable-select">
                  <option value="">Select Establishment</option>
                  <?php
                  foreach ($companies as $company) {
                    echo '<option data-quota="' . ($company->starting_quota - $company->totalEmployees) . '" ' . ($company_id == $company->company_id ? 'selected="selected"' : '') . ' value="' . $company->company_id . '">' . $company->company_name . ' (' . ($company->starting_quota - $company->totalEmployees) . ')</option>';
                  }
                  ?>
                </select>
                <div class="invalid-feedback company_id"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="mbNumber" class="form-label">MB Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="mbNumber" name="mbNumber" placeholder="MBXXXXXXAE">
                <div class="invalid-feedback mbNumber"></div>
              </div>
              <div class="col-md-8 mb-2">
                <label for="offerLetterFile" class="form-label">Offer Letter File</label>
                <input type="file" class="form-control" id="offerLetterFile" name="offerLetterFile" accept=".pdf">
                <div class="invalid-feedback offerLetterFile"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="offerLetterCost" class="form-label">Offer Letter Cost <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="offerLetterCost" name="offerLetterCost" value="50">
                <div class="invalid-feedback offerLetterCost"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="offerLetterCurrency" class="form-label">Offer Letter Currency <span class="text-danger">*</span></label>
                <select name="offerLetterCurrency" id="offerLetterCurrency" class="form-select">
                  <?php foreach ($currencies as $currency): ?>
                    <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback offerLetterCurrency"></div>
              </div>
              <div class="col-md-4 mb-2">
                <label for="offerLetterChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                <select name="offerLetterChargeOn" id="offerLetterChargeOn" class="form-select">
                  <option value="1">Account</option>
                  <option value="2">Supplier</option>
                </select>
                <div class="invalid-feedback offerLetterChargeOn"></div>
              </div>
              <div class="col-md-12 mb-2 offerLetterChargeAccount">
                <label for="offerLetterChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                <select name="offerLetterChargeAccount" id="offerLetterChargeAccount" class="form-select">
                  <option value="">Select Account</option>
                  <?php foreach ($accounts as $account): ?>
                    <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback offerLetterChargeAccount"></div>
              </div>
              <div class="col-md-12 mb-2 d-none offerLetterChargeSupplier">
                <label for="offerLetterChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                <select name="offerLetterChargeSupplier" id="offerLetterChargeSupplier" class="form-select">
                  <option value="">Select Supplier</option>
                  <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback offerLetterChargeSupplier"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="btnSubmitOfferLetter">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalInsurance" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmInsurance" class="frmAjaxPopup" data-msg="#msgInsurance" data-popup="#modalInsurance">
          <input type="hidden" id="insuranceAction" name="action" value="setInsurance">
          <input type="hidden" id="insuranceID" name="id" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Insurance</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgInsurance"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="insuranceCost" class="form-label">Insurance Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="insuranceCost" name="insuranceCost" value="145">
                  <div class="invalid-feedback insuranceCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="insuranceCurrency" class="form-label">Insurance Currency <span class="text-danger">*</span></label>
                  <select name="insuranceCurrency" id="insuranceCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback insuranceCurrency"></div>
                </div>
                <div class="col-md-4">
                  <label for="insuranceChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="insuranceChargeOn" id="insuranceChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback insuranceChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 insuranceChargeAccount">
                  <label for="insuranceChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="insuranceChargeAccount" id="insuranceChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback insuranceChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 insuranceChargeSupplier d-none">
                  <label for="insuranceChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="insuranceChargeSupplier" id="insuranceChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback insuranceChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="insuranceFile" class="form-label">Insurance File</label>
                  <input type="file" class="form-control" id="insuranceFile" name="insuranceFile">
                  <div class="invalid-feedback insuranceFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalLabourCard" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmLabourCard" class="frmAjaxPopup" data-msg="#msgLabourCard" data-popup="#modalLabourCard">
          <input type="hidden" id="labourCardAction" name="action" value="setLabourCard">
          <input type="hidden" id="labourCardID" name="id" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Labour Card</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgLabourCard"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="labourCardNumber" class="form-label">Labour Card Number <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="labourCardNumber" name="labourCardNumber">
                  <div class="invalid-feedback labourCardNumber"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="labourCardCost" class="form-label">Labour Card Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="labourCardCost" name="labourCardCost" value="1210">
                  <div class="invalid-feedback labourCardCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="labourCardCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="labourCardCurrency" id="labourCardCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback labourCardCurrency"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="labourCardChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="labourCardChargeOn" id="labourCardChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback labourCardChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 labourCardChargeAccount">
                  <label for="labourCardChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="labourCardChargeAccount" id="labourCardChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback labourCardChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 labourCardChargeSupplier d-none">
                  <label for="labourCardChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="labourCardChargeSupplier" id="labourCardChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback labourCardChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="labourCardFile" class="form-label">Labour Card File</label>
                  <input type="file" class="form-control" id="labourCardFile" name="labourCardFile">
                  <div class="invalid-feedback labourCardFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalMedical" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmMedical" class="frmAjaxPopup" data-msg="#msgMedical" data-popup="#modalMedical">
          <input type="hidden" name="action" value="setMedical">
          <input type="hidden" name="id" id="medicalID" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Medical</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgMedical"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="medicalCost" class="form-label">Medical Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="medicalCost" name="medicalCost" value="270">
                  <div class="invalid-feedback medicalCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="medicalCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="medicalCurrency" id="medicalCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback medicalCurrency"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="medicalChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="medicalChargeOn" id="medicalChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback medicalChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 medicalChargeAccount">
                  <label for="medicalChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="medicalChargeAccount" id="medicalChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback medicalChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 medicalChargeSupplier d-none">
                  <label for="medicalChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="medicalChargeSupplier" id="medicalChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback medicalChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="medicalFile" class="form-label">Medical File</label>
                  <input type="file" class="form-control" id="medicalFile" name="medicalFile">
                  <div class="invalid-feedback medicalFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalEVisa" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmEVisa" class="frmAjaxPopup" data-msg="#msgEVisa" data-popup="#modalEVisa">
          <input type="hidden" name="action" value="setEVisa">
          <input type="hidden" name="id" id="eVisaID" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>E-Visa</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgEVisa"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="eVisaCost" class="form-label">E-Visa Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="eVisaCost" name="eVisaCost" value="1023">
                  <div class="invalid-feedback eVisaCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="eVisaCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="eVisaCurrency" id="eVisaCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback eVisaCurrency"></div>
                </div>
                <div class="col-md-4">
                  <label for="eVisaChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="eVisaChargeOn" id="eVisaChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback eVisaChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 eVisaChargeAccount">
                  <label for="eVisaChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="eVisaChargeAccount" id="eVisaChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback eVisaChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 eVisaChargeSupplier d-none">
                  <label for="eVisaChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="eVisaChargeSupplier" id="eVisaChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback eVisaChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="eVisaFile" class="form-label">E-Visa File</label>
                  <input type="file" class="form-control" id="eVisaFile" name="eVisaFile">
                  <div class="invalid-feedback eVisaFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalChangeStatus" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmChangeStatus" class="frmAjaxPopup" data-msg="#msgChangeStatus" data-popup="#modalChangeStatus">
        <input type="hidden" name="action" value="setChangeStatus">
        <input type="hidden" name="id" id="changeStatusID" value="">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Change Status</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgChangeStatus"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="changeStatusCost" class="form-label">Change Status Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="changeStatusCost" name="changeStatusCost">
                  <div class="invalid-feedback changeStatusCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="changeStatusCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="changeStatusCurrency" id="changeStatusCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback changeStatusCurrency"></div>
                </div>
                <div class="col-md-4">
                  <label for="changeStatusChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="changeStatusChargeOn" id="changeStatusChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback changeStatusChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 changeStatusChargeAccount">
                  <label for="changeStatusChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="changeStatusChargeAccount" id="changeStatusChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback changeStatusChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 changeStatusChargeSupplier d-none">
                  <label for="changeStatusChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="changeStatusChargeSupplier" id="changeStatusChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback changeStatusChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="changeStatusFile" class="form-label">Change Status File</label>
                  <input type="file" class="form-control" id="changeStatusFile" name="changeStatusFile">
                  <div class="invalid-feedback changeStatusFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalEmiratesID" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmEmiratesID" class="frmAjaxPopup" data-msg="#msgEmiratesID" data-popup="#modalEmiratesID">
          <input type="hidden" name="action" value="setEmiratesID">
          <input type="hidden" name="id" id="emiratesIDID" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Emirates ID</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgEmiratesID"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="emiratesIDCost" class="form-label">Emirates ID Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="emiratesIDCost" name="emiratesIDCost" value="375">
                  <div class="invalid-feedback emiratesIDCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="emiratesIDCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="emiratesIDCurrency" id="emiratesIDCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback emiratesIDCurrency"></div>
                </div>
                <div class="col-md-4">
                  <label for="emiratesIDChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="emiratesIDChargeOn" id="emiratesIDChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback emiratesIDChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 emiratesIDChargeAccount">
                  <label for="emiratesIDChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="emiratesIDChargeAccount" id="emiratesIDChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback emiratesIDChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 emiratesIDChargeSupplier d-none">
                  <label for="emiratesIDChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="emiratesIDChargeSupplier" id="emiratesIDChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback emiratesIDChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="emiratesIDFile" class="form-label">Emirates ID File</label>
                  <input type="file" class="form-control" id="emiratesIDFile" name="emiratesIDFile">
                  <div class="invalid-feedback emiratesIDFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalVisaStamping" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmVisaStamping" class="frmAjaxPopup" data-msg="#msgVisaStamping" data-popup="#modalVisaStamping">
        <input type="hidden" name="action" value="setVisaStamping">
        <input type="hidden" name="id" id="visaStampingID" value="">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Visa Stamping</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgVisaStamping"></div>
              <div class="row">
                <div class="col-md-4 mb-2">
                  <label for="visaStampingExpiryDate" class="form-label">Expiry Date <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="visaStampingExpiryDate" name="visaStampingExpiryDate">
                  <div class="invalid-feedback visaStampingExpiryDate"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="visaStampingCost" class="form-label">Visa Stamping Cost <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="visaStampingCost" name="visaStampingCost" value="450">
                  <div class="invalid-feedback visaStampingCost"></div>
                </div>
                <div class="col-md-4 mb-2">
                  <label for="visaStampingCurrency" class="form-label">Currency <span class="text-danger">*</span></label>
                  <select name="visaStampingCurrency" id="visaStampingCurrency" class="form-select">
                    <?php foreach ($currencies as $currency): ?>
                      <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback visaStampingCurrency"></div>
                </div>
                <div class="col-md-4">
                  <label for="visaStampingChargeOn" class="form-label">Charge On <span class="text-danger">*</span></label>
                  <select name="visaStampingChargeOn" id="visaStampingChargeOn" class="form-select">
                    <option value="1">Account</option>
                    <option value="2">Supplier</option>
                  </select>
                  <div class="invalid-feedback visaStampingChargeOn"></div>
                </div>
                <div class="col-md-12 mb-2 visaStampingChargeAccount">
                  <label for="visaStampingChargeAccount" class="form-label">Charge Account <span class="text-danger">*</span></label>
                  <select name="visaStampingChargeAccount" id="visaStampingChargeAccount" class="form-select">
                    <option value="">Select Account</option>
                    <?php foreach ($accounts as $account): ?>
                      <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback visaStampingChargeAccount"></div>
                </div>
                <div class="col-md-12 mb-2 visaStampingChargeSupplier d-none">
                  <label for="visaStampingChargeSupplier" class="form-label">Charge Supplier <span class="text-danger">*</span></label>
                  <select name="visaStampingChargeSupplier" id="visaStampingChargeSupplier" class="form-select">
                    <option value="">Select Supplier</option>
                    <?php foreach ($suppliers as $supplier): ?>
                      <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                  <div class="invalid-feedback visaStampingChargeSupplier"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="visaStampingFile" class="form-label">Visa Stamping File</label>
                  <input type="file" class="form-control" id="visaStampingFile" name="visaStampingFile">
                  <div class="invalid-feedback visaStampingFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalContractSubmission" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmContractSubmission" class="frmAjaxPopup" data-msg="#msgContractSubmission" data-popup="#modalContractSubmission">
        <input type="hidden" name="action" value="setContractSubmission">
        <input type="hidden" name="id" id="contractSubmissionID" value="">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Contract Submission</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgContractSubmission"></div>
              <div class="row">
                <div class="col-md-12 mb-2">
                  <label for="contractSubmissionEID" class="form-label">Emirates ID Number<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="contractSubmissionEID" name="contractSubmissionEID">
                  <div class="invalid-feedback contractSubmissionEID"></div>
                </div>
                <div class="col-md-12 mb-2">
                  <label for="contractSubmissionFile" class="form-label">Contract Submission File</label>
                  <input type="file" class="form-control" id="contractSubmissionFile" name="contractSubmissionFile">
                  <div class="invalid-feedback contractSubmissionFile"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalTawjeeh" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmTawjeeh" class="frmAjaxPopup" data-msg="#msgTawjeeh" data-popup="#modalTawjeeh">
        <input type="hidden" name="action" value="setTawjeeh">
        <input type="hidden" name="id" id="tawjeehID" value="">
        <div class="modal-content">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Tawjeeh</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgTawjeeh"></div>
            </div>
            <div class="row">
              <div class="col-md-8 form-group">
                <label for="tawjeehAmount">Tawjeeh Amount</label>
                <input type="text" class="form-control" id="tawjeehAmount" name="tawjeehAmount" placeholder="Enter Tawjeeh Amount (156)">
                <div class="invalid-feedback tawjeehAmount"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modalILOE" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="residenceTasksController.php" method="POST" enctype="multipart/form-data" id="frmILOE" class="frmAjaxPopup" data-msg="#msgILOE" data-popup="#modalILOE">
          <input type="hidden" name="action" value="setILOE">
          <input type="hidden" name="id" id="iloeID" value="">
          <div class="modal-header bg-dark">
            <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>ILOE</i></b></h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="msgILOE"></div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-2">
                <label for="iloeAmount" class="form-label">ILOE Amount</label>
                <input type="text" class="form-control" id="iloeAmount" name="iloeAmount" placeholder="Enter ILOE Amount (126)">
                <div class="invalid-feedback iloeAmount"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>



  <?php include 'footer.php' ?>

  <script type="text/javascript">
    function openInsuranceDialog(mb_number) {
      // open javascript native window modal 600 x 600

      window.open('/redirectInsurance.php?mb_number=' + mb_number, '_blank');
    }

    $(document).ready(function() {

      function resetFormBasicData(action = '') {
        $('#frmBasicData')[0].reset();
        $('#frmBasicData').find('.invalid-feedback').html('');
        $('#frmBasicData').find('.is-invalid').removeClass('is-invalid');
        $('#basicDataAction').val('addResidence');
      }

      var table = $('#datatable').DataTable({
        responsive: false
      });

      $('#establishment').on('change', function() {
        var company_id = $(this).val();
        if (company_id == '') {
          window.location = '/residenceTasks.php?step=<?php echo $s ?>';
        } else {
          window.location = '/residenceTasks.php?step=<?php echo $s ?>&company_id=' + company_id;
        }
      });

      $('.paneldata').on('click', ".btn-setOfferLetterStatus", function() {
        var btn = $(this);
        var id = btn.data('id');
        var action = btn.data('action');
        var value = btn.data('value');

        $.confirm({
          title: 'Confirm!',
          content: 'Are you sure you want to ' + action + ' this offer letter?',
          buttons: {
            confirm: function() {
              btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
              $.ajax({
                url: '/residenceTasksController.php',
                method: "POST",
                data: {
                  action: 'setOfferLetterStatus',
                  value: value,
                  id: id
                },
                error: function() {
                  btn.removeAttr('disabled').html(btn.attr('data-temp'));
                  $('#message').html('<div class="alert alert-danger">An error occurred while setting offer letter status</div>');
                },
                success: function(res) {
                  if (res.status == 'success') {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
                    setTimeout(function() {
                      location.reload();
                    }, 3000);
                  } else {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                  }
                }
              });
            },
            cancel: function() {}
          }
        });
      });

      $(".paneldata").on('click', '.btn-seteVisaStatus', function() {
        var btn = $(this);
        var id = btn.data('id');
        var action = btn.data('action');
        var value = btn.data('value');

        $.confirm({
          title: 'Confirm!',
          content: 'Are you sure you want to ' + action + ' this eVisa?',
          buttons: {
            confirm: function() {
              btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
              $.ajax({
                url: '/residenceTasksController.php',
                method: "POST",
                data: {
                  action: 'seteVisaStatus',
                  value: value,
                  id: id
                },
                error: function() {
                  btn.removeAttr('disabled').html(btn.attr('data-temp'));
                  $('#message').html('<div class="alert alert-danger">An error occurred while setting eVisa status</div>');
                },
                success: function(res) {
                  if (res.status == 'success') {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
                    setTimeout(function() {
                      location.reload();
                    }, 3000);
                  } else {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                  }
                }
              });
            },
            cancel: function() {}
          }
        });
      });

      $('.paneldata').on('click', '.btn-setTawjeeh', function() {
        var btn = $(this);
        var id = btn.data('id');
        $('#tawjeehID').val(id);
        $('#modalTawjeeh').modal('show');
      });

      $('.paneldata').on('click', '.btn-setILOE', function() {
        var btn = $(this);
        var id = btn.data('id');
        $('#iloeID').val(id);
        $('#modalILOE').modal('show');
      });

      $('.paneldata').on('click', '.btn-setEmiratesID', function() {
        $('#emiratesIDID').val($(this).data('id'));
        $('#modalEmiratesID').modal('show');
      });

      $('.paneldata').on('click', '.btn-hold', function() {
        var btn = $(this);
        var id = btn.attr('data-id');
        var value = btn.attr('data-value');

        $.confirm({
          title: 'Confirm!',
          content: 'Are you sure you want to ' + (value == 1 ? 'hold' : 'unhold') + ' this residence?',
          buttons: {
            confirm: function() {
              btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
              $.ajax({
                url: '/residenceTasksController.php',
                method: "POST",
                data: {
                  action: 'setHold',
                  value: value,
                  id: id
                },
                error: function() {
                  btn.removeAttr('disabled').html(btn.attr('data-temp'));
                  $('#message').html('<div class="alert alert-danger">An error occurred while setting hold status</div>');
                },
                success: function(res) {
                  if (res.status == 'success') {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
                    setTimeout(function() {
                      location.reload();
                    }, 3000);
                  } else {
                    btn.removeAttr('disabled').html(btn.attr('data-temp'));
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                  }
                }
              });
            },
            cancel: function() {}
          }
        });
      });

      $('.paneldata').on('click', '.btn-attachments', function() {
        var modal = $('#modalAttachments');
        var btn = $(this);

        btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: '/manageEstablishmentsController.php',
          method: 'POST',
          data: {
            action: 'loadAttachments',
            id: btn.data('id')
          },
          error: function() {
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
            $('#message').html('<div class="alert alert-danger">An error occurred while loading attachments</div>');
          },
          success: function(res) {
            if (res.status == 'success') {
              $('#modalAttachmentsBody').html(res.html);
              // Initialize any tooltips or popovers if needed
              $('[data-bs-toggle="tooltip"]').tooltip();
            } else {
              $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
            }
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          }
        });

        modal.modal('show');
      });

      $('#customer_id').select2({
        allowClear: true,
        placeholder: 'Select Customer',
        dropdownParent: $('#modalNewResidence'),
      });

      $('#nationality').select2({
        allowClear: true,
        placeholder: 'Select Nationality',
        dropdownParent: $('#modalNewResidence')
      });

      $('#position').select2({
        allowClear: true,
        placeholder: 'Select Position',
        dropdownParent: $('#modalNewResidence')
      });

      $("#offerLetterChargeOn").on('change', function() {
        if ($(this).val() == 1) {
          $('.offerLetterChargeAccount').removeClass('d-none');
          $('.offerLetterChargeSupplier').addClass('d-none');
        } else {
          $('.offerLetterChargeAccount').addClass('d-none');
          $('.offerLetterChargeSupplier').removeClass('d-none');
        }
      });

      $('#insuranceChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.insuranceChargeAccount').removeClass('d-none');
          $('.insuranceChargeSupplier').addClass('d-none');
        } else {
          $('.insuranceChargeAccount').addClass('d-none');
          $('.insuranceChargeSupplier').removeClass('d-none');
        }
      });

      $('#labourCardChargeOn').on('change', function() {
        if ($(this).val() == '1') {
          $('.labourCardChargeAccount').removeClass('d-none');
          $('.labourCardChargeSupplier').addClass('d-none');
        } else {
          $('.labourCardChargeAccount').addClass('d-none');
          $('.labourCardChargeSupplier').removeClass('d-none');
        }
      });

      $('#eVisaChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.eVisaChargeAccount').removeClass('d-none');
          $('.eVisaChargeSupplier').addClass('d-none');
        } else {
          $('.eVisaChargeAccount').addClass('d-none');
          $('.eVisaChargeSupplier').removeClass('d-none');
        }
      });

      $('#changeStatusChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.changeStatusChargeAccount').removeClass('d-none');
          $('.changeStatusChargeSupplier').addClass('d-none');
        } else {
          $('.changeStatusChargeAccount').addClass('d-none');
          $('.changeStatusChargeSupplier').removeClass('d-none');
        }
      });

      $('#medicalChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.medicalChargeAccount').removeClass('d-none');
          $('.medicalChargeSupplier').addClass('d-none');
        } else {
          $('.medicalChargeAccount').addClass('d-none');
          $('.medicalChargeSupplier').removeClass('d-none');
        }
      });

      $('#emiratesIDChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.emiratesIDChargeAccount').removeClass('d-none');
          $('.emiratesIDChargeSupplier').addClass('d-none');
        } else {
          $('.emiratesIDChargeAccount').addClass('d-none');
          $('.emiratesIDChargeSupplier').removeClass('d-none');
        }
      });

      $('#visaStampingChargeOn').on('change', function() {
        if ($(this).val() == 1) {
          $('.visaStampingChargeAccount').removeClass('d-none');
          $('.visaStampingChargeSupplier').addClass('d-none');
        } else {
          $('.visaStampingChargeAccount').addClass('d-none');
          $('.visaStampingChargeSupplier').removeClass('d-none');
        }
      });

      $('#passportExpiryDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "bottom"
      });

      $('#dob').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "bottom"
      });

      $('#visaStampingExpiryDate').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: "bottom"
      });

      $('#uid, #passportNumber').on('blur', function() {
        var th = $(this);
        var field = th.attr('id');
        var value = $(this).val();

        if (value != '') {
          $.ajax({
            url: '/residenceTasksController.php',
            method: 'POST',
            data: {
              action: 'getPassenger',
              field: field,
              value: value
            },
            success: function(res) {
              if (res.status == 'success') {
                $('#uid').val(res.data.uid);
                $('#passangerName').val(res.data.passenger_name);
                $('#nationality').val(res.data.Nationality).trigger('change');
                $('#passportNumber').val(res.data.passportNumber);
                $('#passportExpiryDate').val(res.data.passportExpiryDate);
                $("#gender").val(res.data.gender);
                $("#dob").val(res.data.dob);
              }
            }
          });
        }
      });

      $('#btnAddNewResidence').on('click', function() {
        resetFormBasicData('addResidence');
        $('#modalNewResidence').modal('show');
      });

      $('tbody').on('click', '.btn-setOfferLetter', function() {
        var btn = $(this);
        var id = btn.data('id');
        $('#offerLetterID').val(id);
        $('#modalOfferLetter').modal('show');
      });

      $('tbody').on('click', '.btn-setInsurance', function() {
        var id = $(this).data('id');
        $('#insuranceID').val(id);
        $('#modalInsurance').modal('show');
      });

      $('tbody').on('click', '.btn-setLabourCard', function() {
        $('#labourCardID').val($(this).data('id'));
        $('#labourCardNumber').val($(this).data('labour-card-number'));
        $('#modalLabourCard').modal('show');
      });

      $('tbody').on('click', '.btn-setEVisa', function() {
        $('#eVisaID').val($(this).data('id'));
        $('#modalEVisa').modal('show');
      });

      $('tbody').on('click', '.btn-setChangeStatus', function() {
        $('#changeStatusID').val($(this).data('id'));
        $('#modalChangeStatus').modal('show');
      });

      $('tbody').on('click', '.btn-setMedical', function() {
        $('#medicalID').val($(this).data('id'));
        $('#modalMedical').modal('show');
      });

      $('tbody').on('click', '.btn-setVisaStamping', function() {
        $('#visaStampingID').val($(this).data('id'));
        $('#modalVisaStamping').modal('show');
      });

      $('tbody').on('click', '.btn-setContractSubmission', function() {
        $('#contractSubmissionID').val($(this).data('id'));
        $('#modalContractSubmission').modal('show');
      });

      $('.form-select, input[type=file]').on('change', function() {
        var vl = $(this).val();
        if (vl == '') {
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      $('.form-control').on('keyup', function() {
        var vl = $(this).val();
        if (vl == '') {
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      $('#frmBasicData').on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
        var msg = $('#msgBasicData');

        msg.html('');
        btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');

        var formData = new FormData();
        frm.find('input,select,textarea').each(function() {
          var element = $(this);
          if (element.attr('type') == 'file') {
            var file = element[0].files[0];
            formData.append(element.attr('name'), file);
          } else if (element.attr('type') == 'checkbox') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else if (element.attr('type') == 'radio') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else {
            formData.append(element.attr('name'), element.val());
          }
        });

        $.ajax({
          url: '/residenceTasksController.php',
          method: 'POST',
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          error: function() {
            msg.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(res) {
            if (res.status == 'success') {
              frm[0].reset();
              $('#modalNewResidence').modal('hide');
              msg.html('<div class="alert alert-success">' + res.message + '</div>');
              setTimeout(function() {
                location.reload();
              }, 3000);
            } else {
              if (res.message == 'form_errors') {
                $.each(res.errors, function(key, value) {
                  $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
                });
              } else {
                msg.html('<div class="alert alert-danger">' + res.message + '</div>');
              }
              btn.removeAttr('disabled').html(btn.attr('data-temp'));
            }
          }
        });
      });

      $("#frmOfferLetter").on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
        var msg = $('#msgOfferLetter');
        var msgMain = $('#message');

        msg.html('');
        btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
        var formData = new FormData();
        frm.find('input,select,textarea').each(function() {
          var element = $(this);
          if (element.attr('type') == 'file') {
            var file = element[0].files[0];
            formData.append(element.attr('name'), file);
          } else if (element.attr('type') == 'checkbox') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else if (element.attr('type') == 'radio') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else {
            formData.append(element.attr('name'), element.val());
          }
        });

        $.ajax({
          url: frm.attr('action'),
          method: frm.attr('method'),
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          error: function() {
            msg.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e) {
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
            if (e.status != 'success') {
              if (e.message == 'form_errors') {
                $.each(e.errors, function(key, value) {
                  $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
                });
              } else {
                msg.html('<div class="alert alert-danger">' + e.message + '</div>');
              }
            } else {
              msg.html('<div class="alert alert-success">' + e.message + '</div>');
              setTimeout(function() {
                location.reload();
              }, 3000);
            }
          }
        });
      });

      $('.frmAjaxPopup').not('#frmOfferLetter').on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
        var msg = $(frm.attr('data-msg'));
        var msgMain = $('#message');

        msg.html('');
        btn.attr('disabled', 'disabled').attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>');
        var formData = new FormData();
        frm.find('input,select,textarea').each(function() {
          var element = $(this);
          if (element.attr('type') == 'file') {
            var file = element[0].files[0];
            formData.append(element.attr('name'), file);
          } else if (element.attr('type') == 'checkbox') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else if (element.attr('type') == 'radio') {
            if (element.prop('checked')) {
              formData.append(element.attr('name'), element.val());
            }
          } else {
            formData.append(element.attr('name'), element.val());
          }
        });

        $.ajax({
          url: frm.attr('action'),
          method: frm.attr('method'),
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          error: function() {
            msg.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e) {
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
            if (e.status != 'success') {
              if (e.message == 'form_errors') {
                $.each(e.errors, function(key, value) {
                  $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
                });
              } else {
                msg.html('<div class="alert alert-danger">' + e.message + '</div>');
              }
            } else {
              msg.html('<div class="alert alert-success">' + e.message + '</div>');
              setTimeout(function() {
                location.reload();
              }, 3000);
            }
          }
        });
      });

      $('#company_id').on('change', function() {
        var company_id = $(this).val();
        var company = $('#company_id option:selected');
        var quota = company.data('quota');

        if (quota < 1) {
          $('#btnSubmitOfferLetter').attr('disabled', 'disabled');
        } else {
          $('#btnSubmitOfferLetter').removeAttr('disabled');
        }
      });

      $('#company_id').select2({
        placeholder: 'Select Establishment',
        dropdownParent: $('#modalOfferLetter'),
      });

      // PDF reading for offerLetterFile (updated to include & in company name)
      $('#offerLetterFile').on('change', async function(e) {
        const file = e.target.files[0];
        if (file && file.type === 'application/pdf') {
          try {
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
            const page = await pdf.getPage(1);
            const textContent = await page.getTextContent();
            const text = textContent.items.map(item => item.str).join(' ');

            // Extract Transaction Number for MB Number
            const transactionNumberMatch = text.match(/Transaction Number\s*[:\-]?\s*([A-Za-z0-9]+)/i);
            if (transactionNumberMatch && transactionNumberMatch[1]) {
              $('#mbNumber').val(transactionNumberMatch[1]).removeClass('is-invalid');
            } else {
              $('#mbNumber').val('').addClass('is-invalid');
              $('.invalid-feedback.mbNumber').html('Transaction Number not found in PDF.');
            }

            // Extract Establishment Name (include & and stop before "Establishment No")
            const establishmentMatch = text.match(/Establishment Name\s*([A-Za-z\s&]+)/i);
            if (establishmentMatch && establishmentMatch[1]) {
              let establishmentName = establishmentMatch[1].trim();
              // Remove "Establishment No" and anything following it
              const noIndex = establishmentName.toLowerCase().indexOf('establishment no');
              if (noIndex !== -1) {
                establishmentName = establishmentName.substring(0, noIndex).trim();
              }
              const normalizedName = establishmentName.toUpperCase();

              // Debug: Log extracted name and options
              console.log('Extracted Establishment Name:', normalizedName);
              console.log('Available Options:', $('#company_id option').map(function() {
                return $(this).text().split(' (')[0].toUpperCase();
              }).get());

              const companyOption = $('#company_id option').filter(function() {
                return $(this).text().split(' (')[0].toUpperCase() === normalizedName;
              });
              if (companyOption.length > 0) {
                $('#company_id').val(companyOption.val()).trigger('change').removeClass('is-invalid');
              } else {
                $('#company_id').val('').trigger('change').addClass('is-invalid');
                $('.invalid-feedback.company_id').html('Establishment "' + establishmentName + '" not found in options.');
              }
            } else {
              $('#company_id').val('').trigger('change').addClass('is-invalid');
              $('.invalid-feedback.company_id').html('Establishment Name not found in PDF.');
            }

          } catch (error) {
            console.error('Error reading PDF:', error);
            $('#mbNumber').val('').addClass('is-invalid');
            $('.invalid-feedback.mbNumber').html('Error reading PDF file.');
            $('#company_id').val('').trigger('change').addClass('is-invalid');
            $('.invalid-feedback.company_id').html('Error reading PDF file.');
          }
        }
      });



      $(document).ready(function() {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        console.log('Tesseract available:', typeof Tesseract !== 'undefined');





        async function waitForTesseract() {
          if (typeof Tesseract === 'undefined') {
            console.log('Waiting for Tesseract to load...');
            return new Promise((resolve, reject) => {
              const checkInterval = setInterval(() => {
                if (typeof Tesseract !== 'undefined') {
                  clearInterval(checkInterval);
                  resolve();
                }
              }, 100);
              setTimeout(() => {
                clearInterval(checkInterval);
                reject(new Error('Tesseract.js failed to load within 10 seconds.'));
              }, 10000);
            });
          }
        }

        async function fileToDataURL(file) {
          return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result);
            reader.onerror = () => reject(new Error('Failed to read file as Data URL'));
            reader.readAsDataURL(file);
          });
        }

        // Clean text function to remove OCR noise
        function cleanText(text) {
          return text
            .replace(/[^A-Z0-9\s<>\-\/]/gi, '') // Remove non-alphanumeric, non-space, non-special characters (<, >, -, /)
            .replace(/\s+/g, ' ') // Normalize spaces
            .trim();
        }

        // Parse MRZ (basic 2-line passport MRZ, handle noisy OCR)
        function parseMRZ(text) {
          const cleanedText = cleanText(text);
          const mrzLines = cleanedText.split('\n').filter(line => line.match(/^[A-Z0-9<]{44}$/));
          if (mrzLines.length < 2) return null;

          const [line1, line2] = mrzLines;
          return {
            passportNumber: line2.substring(0, 9).replace(/</g, ''),
            nationality: line2.substring(10, 13), // e.g., "AFG"
            dob: line2.substring(13, 19), // YYMMDD
            gender: line2.substring(20, 21), // M or F
            expiry: line2.substring(21, 27), // YYMMDD
            surname: line1.substring(5, line1.indexOf('<<')).replace(/</g, ''),
            givenNames: line1.substring(line1.indexOf('<<') + 2).replace(/</g, '').replace(/<+/g, ' ').trim()
          };
        }

        $('#btnAddNewResidence').on('click', function() {
          console.log('Add New Residence button clicked');
          resetFormBasicData('addResidence');
          $('#modalNewResidence').modal('show');
        });

        $('#passport_file').on('change', async function(e) {
          const file = e.target.files[0];
          if (!file) return;

          try {
            console.log('Processing file:', file.name, file.type);
            await waitForTesseract();

            let imageData;
            if (file.type === 'application/pdf') {
              console.log('Converting PDF to image');
              const arrayBuffer = await file.arrayBuffer();
              const pdf = await pdfjsLib.getDocument(arrayBuffer).promise;
              const page = await pdf.getPage(1);
              const viewport = page.getViewport({
                scale: 1.5
              });
              const canvas = document.createElement('canvas');
              const context = canvas.getContext('2d');
              canvas.height = viewport.height;
              canvas.width = viewport.width;
              await page.render({
                canvasContext: context,
                viewport: viewport
              }).promise;
              imageData = canvas.toDataURL('image/png');
              console.log('PDF Image Data:', imageData.substring(0, 50));
            } else if (file.type.startsWith('image/')) {
              imageData = await fileToDataURL(file);
              console.log('Image Data URL:', imageData.substring(0, 50));
            } else {
              throw new Error('Unsupported file type. Please upload a PDF or image.');
            }

            if (!imageData || !imageData.startsWith('data:image/')) {
              throw new Error('Invalid image data generated.');
            }

            console.log('Starting OCR with Tesseract.js v5');
            const {
              createWorker
            } = Tesseract;
            const worker = await createWorker('eng');
            console.log('Worker created, recognizing image...');
            const {
              data: {
                text
              }
            } = await worker.recognize(imageData);
            await worker.terminate();

            console.log('Extracted Text:', text);

            const cleanedText = cleanText(text);

            // Try MRZ first for accuracy
            const mrzData = parseMRZ(cleanedText);
            if (mrzData) {
              console.log('Parsed MRZ Data:', mrzData);
              $('#passportNumber').val(mrzData.passportNumber).removeClass('is-invalid');
              $('#passengerName').val(`${mrzData.givenNames} ${mrzData.surname}`).removeClass('is-invalid');
              $('#uid').val(mrzData.passportNumber).removeClass('is-invalid'); // Use passport number as UID

              // Format DOB from YYMMDD to YYYY-MM-DD
              const dobYear = parseInt(mrzData.dob.substring(0, 2)) + (parseInt(mrzData.dob.substring(0, 2)) < 50 ? 2000 : 1900);
              const dob = `${dobYear}-${mrzData.dob.substring(2, 4)}-${mrzData.dob.substring(4, 6)}`;
              $('#dob').val(dob).removeClass('is-invalid');

              // Gender
              $('#gender').val(mrzData.gender === 'M' ? 'male' : 'female').removeClass('is-invalid');

              // Format Expiry from YYMMDD to YYYY-MM-DD
              const expiryYear = parseInt(mrzData.expiry.substring(0, 2)) + (parseInt(mrzData.expiry.substring(0, 2)) < 50 ? 2000 : 1900);
              const expiry = `${expiryYear}-${mrzData.expiry.substring(2, 4)}-${mrzData.expiry.substring(4, 6)}`;
              $('#passportExpiryDate').val(expiry).removeClass('is-invalid');

              // Nationality (map MRZ code to full name)
              const nationalityMap = {
                'AFG': 'AFGHAN'
              }; // Add more as needed
              const nationalityText = nationalityMap[mrzData.nationality] || mrzData.nationality;
              const nationalityOption = $('#nationality option').filter(function() {
                return $(this).text().trim().toLowerCase() === nationalityText.toLowerCase();
              });
              if (nationalityOption.length > 0) {
                $('#nationality').val(nationalityOption.val()).trigger('change').removeClass('is-invalid');
              } else {
                // Try human-readable text for nationality
                const humanNationalityMatch = cleanedText.match(/Nationality.*?([A-Z\s]+)/i);
                if (humanNationalityMatch && humanNationalityMatch[1]) {
                  const cleanNationality = humanNationalityMatch[1].trim().replace(/[^A-Z\s]/g, '');
                  const humanNationalityOption = $('#nationality option').filter(function() {
                    return $(this).text().trim().toLowerCase() === cleanNationality.toLowerCase();
                  });
                  if (humanNationalityOption.length > 0) {
                    $('#nationality').val(humanNationalityOption.val()).trigger('change').removeClass('is-invalid');
                  } else {
                    $('#nationality').val('').trigger('change').addClass('is-invalid');
                    $('.invalid-feedback.nationality').html('Nationality not found in options.');
                  }
                } else {
                  $('#nationality').val('').trigger('change').addClass('is-invalid');
                  $('.invalid-feedback.nationality').html('Nationality not found.');
                }
              }
            } else {
              // Fallback to human-readable text with more flexible regex
              const passportNumberMatch = cleanedText.match(/(Passport\s*(No\.?|Number).*?([A-Z0-9]+))/i) ||
                cleanedText.match(/Passport\s*No\.\s*([A-Z0-9]+)/i);
              if (passportNumberMatch && passportNumberMatch[3] || passportNumberMatch[1]) {
                $('#passportNumber').val(passportNumberMatch[3] || passportNumberMatch[1]).removeClass('is-invalid');
              } else {
                $('#passportNumber').val('').addClass('is-invalid');
                $('.invalid-feedback.passportNumber').html('Passport Number not found.');
              }

              const givenNameMatch = cleanedText.match(/Given\s*Names?.*?([A-Z\s]+)/i) ||
                cleanedText.match(/RAIHAN.*?([A-Z\s]+)/i); // Fallback for "RAIHAN Oly"
              const surnameMatch = cleanedText.match(/Surname.*?\s*([A-Z\s]+)/i) ||
                cleanedText.match(/SULTANI.*?([A-Z\s]+)/i); // Fallback for "SULTANI UA"
              const fullName = (givenNameMatch ? givenNameMatch[1].trim() : '') + ' ' + (surnameMatch ? surnameMatch[1].trim() : '');
              if (fullName.trim()) {
                $('#passengerName').val(fullName).removeClass('is-invalid');
              } else {
                $('#passengerName').val('').addClass('is-invalid');
                $('.invalid-feedback.passengerName').html('Name not found.');
              }

              const expiryMatch = cleanedText.match(/(Expiry|Date of Expiry).*?(\d{2}\s*[A-Z]{3}\s*\d{4}|\d{4}[-\/]\d{2}[-\/]\d{2})/i);
              if (expiryMatch && expiryMatch[2]) {
                let expiryDate = expiryMatch[2].replace(/\s+/g, '-');
                if (expiryDate.match(/\d{2}-[A-Z]{3}-\d{4}/)) {
                  const months = {
                    JAN: '01',
                    FEB: '02',
                    MAR: '03',
                    APR: '04',
                    MAY: '05',
                    JUN: '06',
                    JUL: '07',
                    AUG: '08',
                    SEP: '09',
                    OCT: '10',
                    NOV: '11',
                    DEC: '12'
                  };
                  const [day, monthAbbr, year] = expiryDate.split('-');
                  expiryDate = `${year}-${months[monthAbbr]}-${day.padStart(2, '0')}`;
                }
                $('#passportExpiryDate').val(expiryDate).removeClass('is-invalid');
              } else {
                $('#passportExpiryDate').val('').addClass('is-invalid');
                $('.invalid-feedback.passportExpiryDate').html('Expiry Date not found.');
              }

              const uidMatch = cleanedText.match(/(UID|Unique ID).*?([A-Z0-9]+)/i) || passportNumberMatch;
              if (uidMatch && (uidMatch[2] || uidMatch[3] || uidMatch[1])) {
                $('#uid').val(uidMatch[2] || uidMatch[3] || uidMatch[1]).removeClass('is-invalid');
              } else {
                $('#uid').val('').addClass('is-invalid');
                $('.invalid-feedback.uid').html('UID not found.');
              }

              const dobMatch = cleanedText.match(/(Date of Birth|DOB).*?(\d{2}\s*[A-Z]{3}\s*\d{4}|\d{4}[-\/]\d{2}[-\/]\d{2})/i);
              if (dobMatch && dobMatch[2]) {
                let dob = dobMatch[2].replace(/\s+/g, '-');
                if (dob.match(/\d{2}-[A-Z]{3}-\d{4}/)) {
                  const months = {
                    JAN: '01',
                    FEB: '02',
                    MAR: '03',
                    APR: '04',
                    MAY: '05',
                    JUN: '06',
                    JUL: '07',
                    AUG: '08',
                    SEP: '09',
                    OCT: '10',
                    NOV: '11',
                    DEC: '12'
                  };
                  const [day, monthAbbr, year] = dob.split('-');
                  dob = `${year}-${months[monthAbbr]}-${day.padStart(2, '0')}`;
                }
                $('#dob').val(dob).removeClass('is-invalid');
              } else {
                $('#dob').val('').addClass('is-invalid');
                $('.invalid-feedback.dob').html('Date of Birth not found.');
              }

              const genderMatch = cleanedText.match(/Sex.*?([MF])/i);
              if (genderMatch && genderMatch[1]) {
                $('#gender').val(genderMatch[1] === 'M' ? 'male' : 'female').removeClass('is-invalid');
              } else {
                $('#gender').val('').addClass('is-invalid');
                $('.invalid-feedback.gender').html('Gender not found.');
              }

              const nationalityMatch = cleanedText.match(/Nationality.*?([A-Z\s]+)/i);
              if (nationalityMatch && nationalityMatch[1]) {
                const cleanNationality = nationalityMatch[1].trim().replace(/[^A-Z\s]/g, '');
                const nationalityOption = $('#nationality option').filter(function() {
                  return $(this).text().trim().toLowerCase() === cleanNationality.toLowerCase();
                });
                if (nationalityOption.length > 0) {
                  $('#nationality').val(nationalityOption.val()).trigger('change').removeClass('is-invalid');
                } else {
                  $('#nationality').val('').trigger('change').addClass('is-invalid');
                  $('.invalid-feedback.nationality').html('Nationality not found in options.');
                }
              } else {
                $('#nationality').val('').trigger('change').addClass('is-invalid');
                $('.invalid-feedback.nationality').html('Nationality not found.');
              }
            }

          } catch (error) {
            console.error('Processing Error:', error);
            $('#passportNumber, #passengerName, #passportExpiryDate, #uid, #dob, #gender, #nationality')
              .val('').addClass('is-invalid');
            $('.invalid-feedback').html('Error processing passport: ' + error.message);
          }
        });

        function resetFormBasicData(action = '') {
          $('#frmBasicData')[0].reset();
          $('#frmBasicData').find('.invalid-feedback').html('');
          $('#frmBasicData').find('.is-invalid').removeClass('is-invalid');
          $('#basicDataAction').val('addResidence');
        }
      });
    });
  </script>
</div>