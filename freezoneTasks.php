<?php include 'header.php';

$stmp = $pdo->prepare("
  SELECT * FROM company
  WHERE company.company_type = 'Freezone'
  ORDER BY company.company_id DESC  
  ");
$stmp->execute();
$companies = $stmp->fetchAll(PDO::FETCH_OBJ);

$stmp = $pdo->prepare("SELECT customer_id, customer_name FROM customer ORDER BY customer_name");
$stmp->execute();
$customers = $stmp->fetchAll(PDO::FETCH_OBJ);

// countries
$selectQuery = $pdo->prepare("SELECT DISTINCT countryName AS mainCountryName, (SELECT airport_id FROM airports WHERE 
        countryName = mainCountryName LIMIT 1) AS airport_id FROM airports ORDER BY countryName ASC");
$selectQuery->execute();
$countries = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);

$selectQuery =  $pdo->prepare("SELECT position_id,posiiton_name, (SELECT IFNULL(positionID,0) FROM 
            residence WHERE residenceID = :residenceID) AS PositionID FROM position ORDER BY posiiton_name ASC");
$selectQuery->bindParam(':residenceID', $_POST['ID']);
$selectQuery->execute();
/* Fetch all of the remaining rows in the result set */
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


$steps = array(
  '1' => 'eVisa',
  '1a' => 'eVisa (Submitted)',
  '2' => 'Change Status',
  '3' => 'Medical',
  '4' => "Emirates ID",
  '5' => 'Visa Stamping',
  '6' => 'Completed'
);

$step = isset($_GET['step']) ? "$_GET[step]" : '1';
$queryStep = str_replace('a', '', $step);


// get total counts



$stmp = $pdo->prepare("SELECT completedSteps as step, COUNT(*) as total FROM freezone GROUP BY completedSteps");
$stmp->execute();
$totalCounts = $stmp->fetchAll(PDO::FETCH_ASSOC);
$totalCounts = array_combine(array_column($totalCounts, 'step'), array_column($totalCounts, 'total'));

// get count where completedStpes == 1 and evisaStatus == pending
$stmp = $pdo->prepare("SELECT COUNT(*) as total FROM freezone WHERE completedSteps = 1 AND evisaStatus = 'pending'");
$stmp->execute();
$totalCounts['1'] = $stmp->fetchColumn();


// get count where completedStpes == 1 and evisaStatus == processing
$stmp = $pdo->prepare("SELECT COUNT(*) as total FROM freezone WHERE completedSteps = 1 AND evisaStatus = 'processing'");
$stmp->execute();
$totalCounts['1a'] = $stmp->fetchColumn();



$where = '';
if ($step == '1') {
  $where .= " AND freezone.evisaStatus = 'pending'";
}
if ((string)$step == '1a') {
  $where .= " AND freezone.evisaStatus = 'processing'";
}


$stmp = $pdo->prepare("
SELECT freezone.*, customer.customer_name, position.posiiton_name, airports.countryCode, airports.countryName, company.company_name
FROM freezone 
LEFT JOIN customer ON customer.customer_id = freezone.customerID
LEFT JOIN position ON position.position_id = freezone.positionID
LEFT JOIN airports ON airports.airport_id = freezone.nationality
LEFT JOIN company ON company.company_id = freezone.companyID
WHERE completedSteps = :completedSteps {$where}");
$stmp->bindParam(':completedSteps', $queryStep);
$stmp->execute();
$residences = $stmp->fetchAll(PDO::FETCH_OBJ);


?>
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
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mb-2">
      <div class="d-flex">
        <h3>Residence Tasks (Freezone)</h3>
        <div class="ms-auto">
          <button class="btn btn-success" id="btnAddNewResidence">Add New Residence</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12" id="message"></div>
  </div>


  <div class="row">
    <div class="col-md-12 btn-group">
      <?php foreach ($steps as $key => $value): ?>
        <a href="?step=<?php echo $key ?>" class="btn <?php echo (string)$step == (string)$key ? 'btn-primary' : 'btn-white' ?>">
          <?php echo $value ?>
          <?php if (isset($totalCounts[$key]) && $totalCounts[$key] > 0): ?>
            <span class="badge bg-danger"><?php echo $totalCounts[$key] ?></span>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 mt-3">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <div class="panel-title">Freezone Residence List</div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>App. Date</th>
                <th>Passanger</th>
                <th>Customer</th>
                <?php if ($step != '1'): ?>
                  <th>Establishment</th>
                <?php endif; ?>
                <th>Passport</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($residences as $residence): ?>
                <tr id="row-<?php echo $residence->id ?>">
                  <td><?php echo $residence->id ?></td>
                  <td><?php echo date('d-m-Y', strtotime($residence->created_at)) ?></td>
                  <td>
                    <?php
                    $pxName = "<strong>{$residence->passangerName}</strong>";
                    $pxName .= "<br /><strong>Nationality:</strong> {$residence->countryName} ({$residence->countryCode})";
                    if ($residence->UID != '' && strtolower($residence->UID) != 'outside') {
                      $pxName .= "<br /><strong>UID:</strong> {$residence->UID}";
                    }
                    echo $pxName;
                    ?>
                  </td>
                  <td><?php echo $residence->customer_name ?></td>
                  <?php if ($step != '1'): ?>
                    <td><?php echo $residence->company_name ?></td>
                  <?php endif; ?>
                  <td>Number: <?php echo $residence->passportNumber ?><br />Exp: <?php echo date('d-m-Y', strtotime($residence->passportExpiryDate)) ?></td>
                  <td>



                    <?php if ($step == '1'): ?>
                      <button class="btn btn-sm btn-primary btn-seteVisa" data-position-id="<?php echo $residence->positionID ?>" data-id="<?php echo $residence->id ?>">Continue</button>
                    <?php endif; ?>
                    <?php if ($step == '1a'): ?>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-success btn-sm btn-approve btn-seteVisaApprove">Approve</button>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-danger btn-sm btn-reject btn-reject-evisa">Reject</button>
                    <?php endif; ?>
                    <?php if ($step == '2'): ?>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-success btn-sm btn-approve btn-setChangeStatus">Continue</button>
                    <?php endif; ?>
                    <?php if ($step == '3'): ?>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-success btn-sm btn-approve btn-setMedical">Continue</button>
                    <?php endif; ?>
                    <?php if ($step == '4'): ?>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-success btn-sm btn-approve btn-setEmiratesID">Continue</button>
                    <?php endif; ?>
                    <?php if ($step == '5'): ?>
                      <button data-id="<?php echo $residence->id ?>" class="btn btn-success btn-sm btn-approve btn-setVisaStamping">Continue</button>
                    <?php endif; ?>
                    <button data-id="<?php echo $residence->id ?>" class="btn btn-sm btn-primary btn-files">
                      <i class="fa fa-paperclip"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>



</div>
</div>




<!-- MODALS -->
<div class="modal fade" id="modalNewResidence" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmBasicData" data-popup="modalNewResidence" class=" frmAjax" method="POST" data-message="msgBasicData" enctype="multipart/form-data" action="freezoneTasksController.php">
      <input type="hidden" id="basicDataAction" name="action" value="saveGeneralData">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add New Freezone Residence</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgBasicData"></div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-2">
              <label for="customerID" class="form-label">Customer <span class="text-danger">*</span></label>
              <select name="customerID" id="customerID" class="form-select searchable-select">
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                  <option value="<?php echo $customer->customer_id ?>"><?php echo $customer->customer_name ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback customerID"></div>
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
              <label for="passangerName" class="form-label">Passanger Name <span class="text-danger">*</span></label>
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
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
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
                <option value="Inside">Inside</option>
                <option value="Outside">Outside</option>
              </select>
              <div class="invalid-feedback insideOutside"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="positionID" class="form-label">Position <span class="text-danger">*</span></label>
              <select name="positionID" id="positionID" class="form-select">
                <option value="">Select Position</option>
                <?php foreach ($positions as $pos): ?>
                  <option value="<?php echo $pos['position_id'] ?>"><?php echo $pos['posiiton_name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback positionID"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="salary" class="form-label">Salary <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="salary" name="salary">
              <div class="invalid-feedback salary"></div>
            </div>

            <div class="col-md-4 mb-2">
              <label for="salePrice" class="form-label">Sale Price <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="salePrice" name="salePrice">
              <div class="invalid-feedback salePrice"></div>
            </div>

            <div class="col-md-4 mb-2">
              <label for="saleCurrency" class="form-label">Sale Price Currency <span class="text-danger">*</span></label>
              <select name="saleCurrency" id="saleCurrency" class="form-select">
                <?php foreach ($currencies as $currency): ?>
                  <option value="<?php echo $currency['currencyID'] ?>"><?php echo $currency['currencyName'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback saleCurrency"></div>
            </div>


            <div class="col-md-6 mb-2">
              <label for="passportFile" class="form-label">Passport File <span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="passportFile" name="passportFile" accept="image/*">
              <div class="invalid-feedback passportFile"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="photoFile" class="form-label">Photo File <span class="text-danger">*</span></label>
              <input type="file" class="form-control" id="photoFile" name="photoFile" accept="image/*">
              <div class="invalid-feedback photoFile"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="idFrontFile" class="form-label">ID (front) / Good Conduct Cert.</label>
              <input type="file" class="form-control" id="idFrontFile" name="idFrontFile" accept="image/*">
              <div class="invalid-feedback idFrontFile"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="id_file_back" class="form-label">ID (back) / Good Conduct Cert. </label>
              <input type="file" class="form-control" id="idBackFile" name="idBackFile" accept="image/*">
              <div class="invalid-feedback idBackFile"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- eVisa Model -->
<div class="modal fade" id="modaleVisa" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmSubmiteVisa" data-popup="modaleVisa" class="frmAjax" method="POST" data-message="msgeVisa" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="eVisaAction" name="action" value="seteVisa">
      <input type="hidden" id="eVisaID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Submit eVisa</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeVisa"></div>
          </div>
          <div class="row">
            <div class="col-md-12 mb-2">
              <label for="eVisaCompanyID">Establishment <span class="text-danger">*</span></label>
              <select name="companyID" id="companyID" class="form-select doSelect2" data-popup="modaleVisa">
                <option value="">Select Establishment</option>
                <?php foreach ($companies as $company): ?>
                  <option value="<?php echo $company->company_id ?>"><?php echo $company->company_name ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback companyID"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="eVisaPositionID">Position <span class="text-danger">*</span></label>
              <select name="eVisaPositionID" id="eVisaPositionID" class="form-select" data-popup="modaleVisa">
                <option value="">Select Position </option>
                <?php foreach ($positions as $pos): ?>
                  <option value="<?php echo $pos['position_id'] ?>"><?php echo $pos['posiiton_name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback eVisaPositionID"></div>
            </div>

            <div class="col-md-4 mb-2">
              <label for="eVisaCost">eVisa Cost <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="eVisaCost" name="eVisaCost" value="4020">
              <div class="invalid-feedback eVisaCost"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- eVisaAccept Model -->
<div class="modal fade" id="modaleVisaAccept" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmSubmiteVisa" data-popup="modaleVisaAccept" class="frmAjax" method="POST" data-message="msgeVisa" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="eVisaAcceptAction" name="action" value="seteVisaAccept">
      <input type="hidden" id="eVisaAcceptID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Accept eVisa</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeVisaAccept"></div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <label for="eVisaFile">eVisa File</label>
              <input type="file" class="form-control" id="eVisaFile" name="eVisaFile">
              <div class="invalid-feedback eVisaFile"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- ChanageStatus Model -->
<div class="modal fade" id="modalChangeStatus" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmChangeStatus" data-popup="modalChangeStatus" class="frmAjax" method="POST" data-message="msgeChangeStatus" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="changeStatusAction" name="action" value="setChangeStatus">
      <input type="hidden" id="changeStatusID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Change Status</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeChangeStatus"></div>
          </div>


          <div class="row">
            <div class="col-md-4 mb-2">
              <label for="changeStatusCost" class="form-label">Change Status Cost <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="changeStatusCost" name="changeStatusCost" value="1520">
              <div class="invalid-feedback changeStatusCost"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="changeStatusAccountType" class="form-label">Change Status Account Type <span class="text-danger">*</span></label>
              <select name="changeStatusAccountType" id="changeStatusAccountType" class="form-select">
                <option value="1">Account</option>
                <option value="2">Supplier</option>
              </select>
            </div>

            <div class="col-md-12 mb-2" id="colChangeStatusAccountID">
              <label for="changeStatusAccountID" class="form-label">Account <span class="text-danger">*</span></label>
              <select name="changeStatusAccountID" id="changeStatusAccountID" class="form-select">
                <option value="">Select Account</option>
                <?php foreach ($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback changeStatusAccountID"></div>
            </div>

            <div class="col-md-12 mb-2 d-none" id="colChangeStatusSupplierID">
              <label for="changeStatusSupplierID" class="form-label">Supplier <span class="text-danger">*</span></label>
              <select name="changeStatusSupplierID" id="changeStatusSupplierID" class="form-select doSelect2" data-popup="modalChangeStatus">
                <option value="">Select Supplier</option>
                <?php foreach ($suppliers as $supplier): ?>
                  <option value="<?php echo $supplier['supp_id'] ?>"><?php echo $supplier['supp_name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback changeStatusSupplierID"></div>
            </div>


            <div class="col-md-12 mb-2">
              <label for="changeStatusFile" class="form-label">Change Status File </label>
              <input type="file" class="form-control" id="changeStatusFile" name="changeStatusFile">
              <div class="invalid-feedback changeStatusFile"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Medical Model -->
<div class="modal fade" id="modalMedical" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmMedical" data-popup="modalMedical" class="frmAjax" method="POST" data-message="msgeMedical" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="medicalAction" name="action" value="setMedical">
      <input type="hidden" id="medicalID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Medical</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeMedical"></div>
          </div>


          <div class="row">
            <div class="col-md-4 mb-2">
              <label for="medicalCost" class="form-label">Medical Cost <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="medicalCost" name="medicalCost" value="275">
              <div class="invalid-feedback medicalCost"></div>
            </div>



            <div class="col-md-12 mb-2">
              <label for="medicalFile" class="form-label">Medical File </label>
              <input type="file" class="form-control" id="medicalFile" name="medicalFile">
              <div class="invalid-feedback medicalFile"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Emirates ID Model -->
<div class="modal fade" id="modalEmiratesID" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmEmiratesID" data-popup="modalEmiratesID" class="frmAjax" method="POST" data-message="msgeEmiratesID" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="emiratesIDAction" name="action" value="setEmiratesID">
      <input type="hidden" id="emiratesIDID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Emirates ID</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeEmiratesID"></div>
          </div>


          <div class="row">
            <div class="col-md-4 mb-2">
              <label for="emiratesIDCost" class="form-label">Emirates ID Cost <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="emiratesIDCost" name="emiratesIDCost" value="375">
              <div class="invalid-feedback emiratesIDCost"></div>
            </div>



            <div class="col-md-12 mb-2">
              <label for="emiratesIDFile" class="form-label">Emirates ID File </label>
              <input type="file" class="form-control" id="emiratesIDFile" name="emiratesIDFile">
              <div class="invalid-feedback emiratesIDFile"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- Visa Stamping Model -->
<div class="modal fade" id="modalVisaStamping" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmVisaStamping" data-popup="modalVisaStamping" class="frmAjax" method="POST" data-message="msgeVisaStamping" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <input type="hidden" id="visaStampingAction" name="action" value="setVisaStamping">
      <input type="hidden" id="visaStampingID" name="id" value="0">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Visa Stamping</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgeVisaStamping"></div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-2">
              <label for="emiratesIDNumber" class="form-label">Emirates ID Number <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="emiratesIDNumber" name="emiratesIDNumber">
              <div class="invalid-feedback emiratesIDNumber"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="visaExpiryDate" class="form-label">Visa Expiry Date <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="visaExpiryDate" name="visaExpiryDate">
              <div class="invalid-feedback visaExpiryDate"></div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


<!-- eVisa Model -->
<div class="modal fade" id="modalFiles" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="frmFiles" data-popup="modalFiles" class="frmAjax" method="POST" data-message="msgeFiles" enctype="multipart/form-data" action="freezoneTasksController.php" data-delete-row="true">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Submit eVisa</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" id="msgFiles"></div>
          </div>

          <div id="bodyFiles"></div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php include 'footer.php' ?>
<script type="text/javascript">
  $(document).ready(function() {

    // add select2
    $('.doSelect2').each(function() {
      $(this).select2({
        dropdownParent: $(this).data('popup') ? $('#' + $(this).data('popup')) : $(this).parent()
      });
    });
    $('#changeStatusAccountType').on('change', function() {
      if ($(this).val() == '1') {
        $('#colChangeStatusAccountID').removeClass('d-none');
        $('#colChangeStatusSupplierID').addClass('d-none');
      } else {
        $('#colChangeStatusAccountID').addClass('d-none');
        $('#colChangeStatusSupplierID').removeClass('d-none');
      }
    });


    $('.form-select,input[type=file]').on('change', function() {
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

    function resetFormBasicData(action = '') {
      $('#frmBasicData')[0].reset();
      $('#frmBasicData').find('.invalid-feedback').html('');
      $('#frmBasicData').find('.is-invalid').removeClass('is-invalid');
    }

    $('#btnAddNewResidence').on('click', function() {
      resetFormBasicData('addResidence');
      $('#modalNewResidence').modal('show');
    });

    $("#customerID").select2({
      dropdownParent: $('#modalNewResidence')
    });
    // nationality
    $("#nationality").select2({
      dropdownParent: $('#modalNewResidence')
    });
    //position
    $("#positionID").select2({
      dropdownParent: $('#modalNewResidence')
    });

    // date of birth
    $("#dob").datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true
    });

    $('#passportExpiryDate').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true
    });
    $('#visaExpiryDate').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true
    });





    $('.frmAjax').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = frm.find('button[type="submit"]');
      frm.find('.invalid-feedback').html('');
      frm.find('.is-invalid').removeClass('is-invalid');
      var formData = new FormData(frm[0]);


      if (frm.attr('data-delete-row') == 'true') {
        frm.attr('data-id', frm.find('input[name="id"]').val());
      }

      $.ajax({
        url: frm.attr('action'),
        type: frm.attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
          btn.attr('data-temp-text', btn.html()).attr('disabled', true);
        },
        success: function(e) {
          btn.attr('disabled', false).html(btn.attr('data-temp-text'));
          if (e.status == 'success') {


            if (frm.attr('data-delete-row') == 'true') {
              $('#row-' + frm.attr('data-id')).fadeOut('slow', function() {
                $(this).remove();
              });
            }

            if (frm.data('popup')) {
              $('#' + frm.data('popup')).modal('hide');
            }
            resetFormBasicData();
            $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
          } else {
            if (e.message == 'form_errors') {
              $.each(e.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value);
              });
            } else {
              $('#' + frm.attr('data-message')).html('<div class="alert alert-danger">' + e.message + '</div>');
            }
          }
        },
        error: function(resp) {
          btn.attr('disabled', false).html(btn.attr('data-temp-text'));
        }
      });
    });


    $('.btn-reject-evisa').on('click', function() {

      var btn = $(this);
      var id = btn.data('id');

      $.confirm({
        title: 'Reject eVisa',
        content: 'Are you sure you want to reject this eVisa?',
        type: 'red',
        typeAnimated: true,
        buttons: {
          tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function() {
              btn.attr('disabled', true);
              $.ajax({
                url: 'freezoneTasksController.php',
                type: 'POST',
                data: {
                  action: 'rejectEVisa',
                  id: id
                },
                success: function(e) {
                  btn.attr('disabled', false);
                  $('#row-' + id).fadeOut(300, function() {
                    $(this).remove();
                  });
                }
              });
            }
          },
          close: function() {}
        }
      });
    });




    /// popup triggers
    $(".btn-seteVisa").on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      var position = btn.data('position-id');
      $('#eVisaID').val(id);
      $('#eVisaPositionID').val(position);
      $('#modaleVisa').modal('show');
    });

    $(".btn-seteVisaApprove").on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      $('#eVisaAcceptID').val(id);
      $('#modaleVisaAccept').modal('show');
    });

    $(".btn-setChangeStatus").on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      $('#changeStatusID').val(id);
      $('#modalChangeStatus').modal('show');
    });

    $(".btn-setMedical").on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      $('#medicalID').val(id);
      $('#modalMedical').modal('show');
    });

    $('.btn-setEmiratesID').on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      $('#emiratesIDID').val(id);
      $('#modalEmiratesID').modal('show');
    });

    $(".btn-setVisaStamping").on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      $('#visaStampingID').val(id);
      $('#modalVisaStamping').modal('show');
    });

    $('.btn-files').on('click', function() {
      var btn = $(this);
      var id = btn.data('id');
      btn.attr('data-temp', btn.html()).html('<i class="fa fa-spinner fa-spin"></i>').attr('disabled', true);
      $.ajax({
        url: 'freezoneTasksController.php',
        type: 'POST',
        data: {
          action: 'files',
          id: id
        },
        success: function(e) {
          btn.html(btn.attr('data-temp')).attr('disabled', false);
          $('#bodyFiles').html(e.files);
        },
        error: function(resp) {
          btn.html(btn.attr('data-temp')).attr('disabled', false);
          $('#msgFiles').html('<div class="alert alert-danger">' + e.message + '</div>');
        }
      });
      $('#modalFiles').modal('show');
    });

  });
</script>