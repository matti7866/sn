<?php
// Debug mode
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log to file
$logDir = __DIR__ . '/logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/passport_errors.log';
ini_set('error_log', $logFile);

// Log start of script
error_log("Starting passport processing at " . date('Y-m-d H:i:s'));

include 'header.php';
?>
<link href="residenceCustom.css" rel="stylesheet">
<title>Hotel Report</title>
<style>
    #passportProcessingIndicator {
        display: none;
        margin-left: 10px;
        color: #17a2b8;
    }

    /* Ensure helper text is always visible */
    .text-muted {
        display: block !important;
        margin-top: 5px;
        visibility: visible !important;
    }
</style>
<!-- Scripts moved to end of body -->
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
$sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$insert = $insert[0]['insert'];
if ($insert == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
}

$stmt = $pdo->prepare("
    SELECT residence.*, company.username, company.password
    FROM `residence` 
    LEFT JOIN `company` ON company.company_id = residence.company
    WHERE `residenceID` = :id
    ");
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$residence = $stmt->fetch(\PDO::FETCH_ASSOC);

$res = null;

if (isset($_GET['type']) && $_GET['type'] == 'renew' && isset($_GET['oldID'])) {
    $sql = "SELECT * FROM `residence` WHERE `residenceID` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $_GET['oldID']);
    $stmt->execute();
    $res = $stmt->fetch(\PDO::FETCH_ASSOC);
    // echo '<pre>';
    // print_r($res);
    // echo '</pre>';
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel text-white">
                <div class="panel-heading bg-inverse ">
                    <h4 class="panel-title"><i class="fa fa-info"></i> Residence Entry <code> Form <i class="fa fa-arrow-down"></i></code></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="residence.php" onClick="resetForm()" class="btn btn-xs btn-icon btn-success"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger " data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>

                    </div>
                </div>
                <div class="panel-body p-3">
                    <div class="col-lg-4 offset-lg-8">
                        <a href="residenceReport.php" style="font-size:14px; font-weight:600;" class="pull-right">View Residence Report</a>
                    </div>
                    <div class="stepper-container">
                        <input type="hidden" id="GRID" />
                        <input type="hidden" id="ComStpID" />

                        <a href="#" class="stepper-item" onclick="openCompletedNode(1)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-info"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Basic Information</p>
                        </a>
                        <div class="contentDiv d-none" id="step1">
                            <form id="basicInfoForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3"><i class="fa fa-edit"></i> Manual Entry Information</h5>
                                    </div>

                                    <?php if ($res): ?>
                                        <div class="col-lg-12">
                                            <h3 class="text-danger">Renewal Residence Old File ID : <?php echo $res['residenceID']; ?> (<?php echo $res['passenger_name'] ?>)</h3>
                                        </div>
                                    <?php endif; ?>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Customer:</label>
                                        <div class="d-flex">
                                            <select class="form-control js-example-basic-single" style="width:85%" name="customer_id" id="customer_id"></select>
                                            <button type="button" class="btn btn-sm btn-primary ml-1" id="addCustomerBtn" onclick="showAddCustomerModal()" data-toggle="modal" data-target="#addCustomerModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Sale price:</label>
                                        <input type="number" value="<?php echo isset($res['sale_price']) ? $res['sale_price'] : ''; ?>" class="form-control" name="sale_amount" id="sale_amount" placeholder="Enter Sale Amount" />
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="insideOutside" class="col-form-label text-dark">Inside/Outside <span class="text-danger">*</span></label>
                                        <select name="insideOutside" id="insideOutside" class="form-select">
                                            <option value="">Choose</option>
                                            <option value="inside" <?php echo isset($res) ? 'selected' : ''; ?>>Inside</option>
                                            <option value="outside">Outside</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="staticEmail" class="col-form-label text-dark">UID</label>
                                        <input type="text" value="<?php echo isset($res['uid']) ? $res['uid'] : ''; ?>" class="form-control" id="uid" name="uid" placeholder="UID">
                                        <input type="hidden" id="visaType" name="visaType" value="17" />

                                        <input type="hidden" id="residenceID" name="residenceID" value="<?php echo isset($res['residenceID']) ? $res['residenceID'] : ''; ?>" />
                                        <input type="hidden" id="resType" name="resType" value="<?php echo isset($res) ? 'renew' : 'new'; ?>" />
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Salary:</label>
                                        <input type="number" class="form-control" placeholder="Salary Amount" name="salary_amount" id="salary_amount" value="<?php echo isset($res['salary_amount']) ? $res['salary_amount'] : ''; ?>">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Position:</label>
                                        <select class="form-control js-example-basic-single" onchange="positionFun()" style="width:100%" id="position" name="position" spry:default="select one"></select>
                                    </div>
                                </div>

                                <!-- Auto-filled Passport Fields -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3"><i class="fa fa-passport"></i> Passport Information (Auto-filled)</h5>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Passenger Name:</label>
                                        <input class="form-control" name="passengerName" id="passengerName" placeholder="Passenger Name" value="<?php echo isset($res['passenger_name']) ? $res['passenger_name'] : ''; ?>" />
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Nationality:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" name="nationality" id="nationality"></select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="col-form-label text-dark" for="passportNumber">Passport # <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="passportNumber" name="passportNumber" placeholder="Passport Number" value="<?php echo isset($res['passportNumber']) ? $res['passportNumber'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="col-form-label text-dark" for="passportExpiryDate">Passport Expiry Date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="passportExpiryDate" name="passportExpiryDate" value="<?php echo isset($res['passportExpiryDate']) ? $res['passportExpiryDate'] : ''; ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="gender" class="col-form-label text-dark">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select" style="width:100%" id="gender" name="gender">
                                            <option value="">Choose</option>
                                            <option value="male" <?php echo isset($res['gender']) && $res['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                                            <option value="female" <?php echo isset($res['gender']) && $res['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="dob" class="col-form-label text-dark">Date of Birth <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dob" name="dob" value="<?php echo isset($res['dob']) ? $res['dob'] : ''; ?>">
                                    </div>
                                </div>

                                <!-- File Uploads -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3"><i class="fa fa-file-upload"></i> Document Uploads</h5>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Passport: <span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="basicInfoFile" name="basicInfoFile" accept="image/jpeg,image/png,application/pdf">
                                            <i class="fa fa-eye text-danger d-none mt-2" id="basicDataFileIcon" onclick="getUploadedFiles(1)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                            <span id="passportProcessingIndicator"><i class="fa fa-spinner fa-spin"></i> Processing passport...</span>
                                        </div>
                                        <small class="text-muted" style="display: block !important;">Upload JPG/PNG/PDF passport documents. Powered by Google Document AI for accurate extraction of passport details.</small>
                                        <div id="extractionErrorContainer" style="display:none; margin-top:5px;">
                                            <div class="alert alert-warning" id="extractionErrorMessage"></div>
                                            <button type="button" id="useTestDataBtn" class="btn btn-sm btn-secondary">Use Test Data Instead</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Photo: <span class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="basicInfoFilePhoto" name="basicInfoFilePhoto">
                                            <i class="fa fa-eye text-danger d-none mt-2" id="basicDataFilePhotoIcon" onclick="getUploadedFiles(11)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">ID (front) / Good Conduct Cert.:</label>
                                        <div class="d-flex">
                                            <input type="file" multiple class="form-control" id="basicInfoFileIDFront" name="basicInfoFileIDFront">
                                            <i class="fa fa-eye text-danger d-none mt-2" id="basicDataFileIDFrontIcon" onclick="getUploadedFiles(12)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">ID (back):</label>
                                        <div class="d-flex">
                                            <input type="file" multiple class="form-control" id="basicInfoFileIDBack" name="basicInfoFileIDBack">
                                            <i class="fa fa-eye text-danger d-none mt-2" id="basicDataFileIDBackIcon" onclick="getUploadedFiles(13)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step1Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <a href="#" class="stepper-item " onclick="openCompletedNode(2)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-envelope"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Offer Letter</p>
                        </a>
                        <div class="contentDiv d-none" id="step2">
                            <form id="offerLetterForm" enctype="multipart/form-data">
                                <div class="row">
                                    <!-- <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Salary:</label>
                                        <input type="number" class="form-control" placeholder="Salary Amount" name="salary_amount" id="salary_amount">
                                    </div> -->
                                    <div class="col-lg-2">
                                        <label for="mb_number" class="col-form-label text-dark">MB Number:</label>
                                        <input type="text" class="form-control" id="mb_number" name="mb_number">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="salaryCur" name="salaryCur" spry:default="select one"></select>
                                    </div>
                                    <!-- <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Position:</label>
                                        <select class="form-control js-example-basic-single" onchange="positionFun()" style="width:100%" id="position" name="position" spry:default="select one"></select>
                                    </div> -->
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Estanblishment:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" name="company" id="company"></select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Offer Letter Cost:</label>
                                        <input type="number" class="form-control" placeholder="Offer Letter Cost" name="offerLetterCost" id="offerLetterCost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="offerLetterCostCur" name="offerLetterCostCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="offerLChargOpt" name="offerLChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="offerLChargedEntity" name="offerLChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="offerLetterFile" name="offerLetterFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="offerLetterFilesIcon" onclick="getUploadedFiles(2)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step2Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(3)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-plane"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Insurance</p>
                        </a>
                        <div class="contentDiv d-none" id="step3">
                            <form id="insuranceForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Insurance Cost:</label>
                                        <input type="number" class="form-control" placeholder="Insurance Cost" name="insuranceCost" id="insuranceCost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="insuranceCur" name="insuranceCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="insuranceChargOpt" name="insuranceChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="insuranceChargedEntity" name="insuranceChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="insuranceFile" name="insuranceFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="insuranceFilesIcon" onclick="getUploadedFiles(3)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step3Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(4)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-credit-card"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Labour Card</p>
                        </a>
                        <div class="contentDiv d-none" id="step4">
                            <form id="laborCardForm" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Labor Card ID:</label>
                                        <input type="text" placeholder="Labour Card ID" class="form-control" name="labor_card_id" id="labor_card_id">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Labour Card Fee:</label>
                                        <input type="number" class="form-control" placeholder="labour Card Fee" name="labour_card_fee" id="labour_card_fee">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="laborCardCur" name="laborCardCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="lrbChargOpt" name="lrbChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="lbrChargedEntity" name="lbrChargedEntity" spry:default="select one"></select>
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="laborCardFile" name="laborCardFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="laborCardFilesIcon" onclick="getUploadedFiles(4)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step4Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(5)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-ticket"></i>
                                </div>
                            </div>
                            <p class="stepper-text">E-Visa Typing</p>

                        </a>
                        <div class="contentDiv d-none" id="step5">
                            <form id="eVisaForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">E-Visa Cost:</label>
                                        <input type="number" class="form-control" placeholder="E Visa Cost" name="evisa_cost" id="evisa_cost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="eVisaCostCur" name="eVisaCostCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="eVisaFile" name="eVisaFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="eVisaTypingFilesIcon" onclick="getUploadedFiles(5)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="eVisaTChargOpt" name="eVisaTChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="eVisaTChargedEntity" name="eVisaTChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step5Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(6)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-exchange"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Change Status</p>
                        </a>
                        <div class="contentDiv d-none" id="step6">
                            <form id="changeStatusForm" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Change Status Cost:</label>
                                        <input type="number" class="form-control" placeholder="Change Status Cost" name="changeStatusCost" id="changeStatusCost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="changeStatusCur" name="changeStatusCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="changeStatusFile" name="changeStatusFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="changeStatusFilesIcon" onclick="getUploadedFiles(6)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="changeSChargOpt" name="changeSChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="changeSChargedEntity" name="changeSChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step6Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(7)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-stethoscope"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Medical Typing</p>
                        </a>
                        <div class="contentDiv d-none" id="step7">
                            <form id="medicalTypingForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Medical Cost:</label>
                                        <input type="number" class="form-control" placeholder="Medical Cost" name="medical_cost" id="medical_cost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="medicalCostCur" name="medicalCostCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="medicalFile" name="medicalFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="medicalTypingFilesIcon" onclick="getUploadedFiles(7)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="medicalTChargOpt" name="medicalTChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="medicalTChargedEntity" name="medicalTChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step7Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(8)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fa fa-id-card"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Emirates ID Typing</p>
                        </a>
                        <div class="contentDiv d-none" id="step8">
                            <form id="emiratesIDForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Emirates ID Cost:</label>
                                        <input type="number" class="form-control" placeholder="Emirates ID Cost" name="emiratesIDCost" id="emiratesIDCost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="emiratesIDCostCur" name="emiratesIDCostCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachments:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="emiratesIDFile" name="emiratesIDFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="emiratesIDFilesIcon" onclick="getUploadedFiles(8)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="emirateIDChargOpt" name="emirateIDChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="emiratesIDChargedEntity" name="emiratesIDChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step8Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(9)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fas fa-stamp"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Visa Stamping</p>
                        </a>
                        <div class="contentDiv d-none" id="step9">
                            <form id="visaStampingForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Visa Stamping Cost:</label>
                                        <input type="number" class="form-control" placeholder="Visa Stamping Cost" name="visaStampingCost" id="visaStampingCost">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Currency:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="visaStampingCur" name="visaStampingCur" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Expiry Date:</label>
                                        <input type="text" class="form-control" name="expiry_date" id="expiry_date">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Labor Card Number:</label>
                                        <input type="text" class="form-control" placeholder="Labor Card Number" name="laborCardNumber" id="laborCardNumber">
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged ON:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="visaStampChargOpt" name="visaStampChargOpt" spry:default="select one">
                                            <option value="1">Account</option>
                                            <option value="2">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="staticEmail" class="col-form-label text-dark">Charged Enitity:</label>
                                        <select class="form-control js-example-basic-single" style="width:100%" id="visaStampChargedEntity" name="visaStampChargedEntity" spry:default="select one"></select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="visaStampingFile" name="visaStampingFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="visaStampingFileIcon" onclick="getUploadedFiles(9)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 offset-lg-8 my-3">
                                        <button type="submit" id="step9Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <a href="#" class="stepper-item" onclick="openCompletedNode(10)">
                            <div class="stepper-circle">
                                <div class="stepper-icon">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                            </div>
                            <p class="stepper-text">Contract Submission</p>
                        </a>
                        <div class="contentDiv d-none" id="step10">
                            <form id="contractSubForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="staticEmail" class="col-form-label text-dark">Emirates ID Number:</label>
                                        <input type="text" class="form-control" placeholder="Emirates ID Number" name="emiratesIDNumber" id="emiratesIDNumber">
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="staticEmail" class="col-form-label text-dark">Attachment:</label>
                                        <div class="d-flex">
                                            <input type="file" class="form-control" id="contractSubmissionFile" name="contractSubmissionFile">
                                            <i class="fa fa-eye text-danger d-none  mt-2" id="contractSubFilesIcon" onclick="getUploadedFiles(10)" style="margin-left:3px;font-size:24px;cursor:pointer"></i>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 offset-lg-9 my-3">
                                        <button type="submit" id="step10Btn" class="btn btn-danger pull-right">Save & Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div class="modal fade" id="addPositionModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark  text-white">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form id="genralUpdForm">
                    <div id="ticketSection">
                        <div class="form-group row mb-3">
                            <label for="inputPassword" class="col-sm-3 col-form-label">Position Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" autofocus="autofocus" id="position_name" name="position_name" placeholder="Type position name here" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn bg-info  text-white" onclick="addPosiitonFun()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Company Modal -->
    <div class="modal fade" id="addCompanyModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark  text-white">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Add company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="genralUpdForm">
                        <div id="ticketSection">
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Company name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" autofocus="autofocus" id="companyname" name="companyname" placeholder="Type company name here" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Company number:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" autofocus="autofocus" id="companynumber" name="companynumber" placeholder="Type company number here" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Starting Quota:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" autofocus="autofocus" id="startingquota" name="startingquota" placeholder="Starting Quota" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">local Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" autofocus="autofocus" id="localname" name="localname" placeholder="localname" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Company Expriy Date:</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" autofocus="autofocus" id="companyexpiry" name="companyexpiry" placeholder="date" />
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Company Type:</label>
                                <div class="col-sm-9">
                                    <select class="form-select" name="company_type" id="company_type">
                                        <option class="mainland">Mainland</option>
                                        <option class="freezone">Freezone</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn bg-info  text-white" onclick="addCompanyFun()">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Get Uploaded Files Report -->
        <div class="modal fade" id="getUploadedFilesModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-dark  text-white">
                        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Uploaded Files</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive ">
                            <table id="myTable" class="table  text-center table-striped table-hover ">
                                <thead class="thead-danger bg-danger text-white" style="font-size:14px">
                                    <tr>
                                        <th>S#</th>
                                        <th>File Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="filesReport">
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Customer Modal -->
        <div class="modal" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title font-weight-bold" id="addCustomerModalLabel">Add Customer</h5>
                        <button type="button" class="close btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addCustomerForm">
                            <div id="customerFormSection">
                                <div class="form-group row mb-3">
                                    <label for="customerName" class="col-sm-3 col-form-label"><i class="fa fa-user"></i> Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="customerName" name="CustomerName" placeholder="Enter customer name" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="customerRef" class="col-sm-3 col-form-label"><i class="fa fa-hashtag"></i> Reference:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="customerRef" name="CustomerRef" placeholder="Enter customer reference" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="customerPhone" class="col-sm-3 col-form-label"><i class="fa fa-phone"></i> Phone:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="customerPhone" name="CustomerPhone" placeholder="Enter phone number" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="customerEmail" class="col-sm-3 col-form-label"><i class="fa fa-envelope"></i> Email:</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="customerEmail" name="CustomerEmail" placeholder="Enter email address" />
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="customerAddress" class="col-sm-3 col-form-label"><i class="fa fa-address-card"></i> Address:</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="customerAddress" name="CustomerAddress" placeholder="Enter address" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" onclick="$('#addCustomerModal').modal('hide');">Close</button>
                        <button type="button" class="btn btn-info text-white" onclick="addCustomerFun();">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include 'footer.php'; ?>
        <script src="residencePassport.js"></script>
        <script>
            $(document).ready(function() {

                getPositions(0);

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

                var url = window.location.href;
                if (hasQueryParams(url) == true) {
                    var urlFirstParam = location.search.split('&')[0];
                    var id = urlFirstParam.split('=')[1];
                    $('#GRID').val(id);
                    var stp = location.search.split('&stp=')[1];
                    $('#ComStpID').val(stp);
                    setCurrentStep(stp);

                    //some additional steps
                    getCustomer(null, <?php echo isset($res['customer_id']) ? $res['customer_id'] : ''; ?>);
                    getNationalities(null, <?php echo isset($res['Nationality']) ? $res['Nationality'] : ''; ?>);
                    //getVisaTypes('all', null);
                    getCurrencies('saleCur', null);
                } else {
                    $('.stepper-container a')[0].classList.add('active');
                    $('#step1').removeClass('d-none');
                    getCustomer('all', null);
                    getNationalities('all', null);
                    //getVisaTypes('all', null);
                    getCurrencies('saleCur', null);
                }
                $('.js-example-basic-single').select2();
            });
            // 
            function setCurrentStep(completedStep) {
                const allItems = document.querySelectorAll('.stepper-container a');
                for (var i = 0; i < completedStep; i++) {
                    allItems[i].classList.add('completed');
                }
                if (completedStep < 10) {
                    allItems[completedStep].classList.add('active');
                    if (completedStep == 6 || completedStep == 7 || completedStep == 8 || completedStep == 9) {
                        $('html,body').animate({
                            scrollTop: 240
                        }, 200);
                    }
                    $('#step' + (parseInt(completedStep) + 1)).removeClass('d-none');
                    getStepsData(parseInt(completedStep) + 1);
                }
            }
            // check if url contains any paramter to check if request is coming from pending steps
            function hasQueryParams(url) {
                return url.includes('?');
            }
            // get customers
            function getCustomer(type, id) {
                var select_customer = "select_customer";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        Select_Customer: select_customer,
                    },
                    success: function(response) {
                        var customer = JSON.parse(response);
                        var addcustomer_id = $('#addcustomer_id');
                        if (type == 'all') {
                            $('#customer_id').empty();
                            $('#customer_id').append("<option value='-1'>--Customer--</option>");
                            for (var i = 0; i < customer.length; i++) {
                                // Check if customer_ref exists and is not null or empty
                                var displayName = customer[i].customer_name;
                                if (customer[i].customer_ref && customer[i].customer_ref.trim() !== '') {
                                    displayName += ' - ' + customer[i].customer_ref;
                                }
                                $('#customer_id').append("<option value='" + customer[i].customer_id + "'>" +
                                    displayName + "</option>");
                            }
                        } else {
                            $('#customer_id').empty();
                            $('#customer_id').append("<option value='-1'>--Customer--</option>");
                            var selected = '';
                            for (var i = 0; i < customer.length; i++) {
                                if (id == customer[i].customer_id) {
                                    selected = 'selected';
                                } else {
                                    selected = '';
                                }
                                // Check if customer_ref exists and is not null or empty
                                var displayName = customer[i].customer_name;
                                if (customer[i].customer_ref && customer[i].customer_ref.trim() !== '') {
                                    displayName += ' - ' + customer[i].customer_ref;
                                }
                                $('#customer_id').append("<option " + selected + "  value='" + customer[i].customer_id + "'>" +
                                    displayName + "</option>");
                            }
                        }
                    },
                });
            }
            // get Nationalities
            function getNationalities(type, selectedOpt) {
                var getNationalities = "getNationalities";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetNationalities: getNationalities,
                    },
                    success: function(response) {
                        var nationality = JSON.parse(response);
                        if (type == "all") {
                            $('#nationality').empty();
                            $('#nationality').append("<option id='-1'> --Select Nationality -- </option>");
                            for (var i = 0; i < nationality.length; i++) {
                                $('#nationality').append("<option value='" + nationality[i].airport_id + "'>" +
                                    nationality[i].mainCountryName + "</option>");
                            }
                        } else {
                            $('#nationality').empty();
                            $('#nationality').append("<option id='-1'> --Select Nationality -- </option>");
                            for (var i = 0; i < nationality.length; i++) {
                                if (nationality[i].airport_id == selectedOpt) {
                                    $('#nationality').append("<option selected value='" + nationality[i].airport_id + "'>" +
                                        nationality[i].mainCountryName + "</option>");
                                } else {
                                    $('#nationality').append("<option value='" + nationality[i].airport_id + "'>" +
                                        nationality[i].mainCountryName + "</option>");
                                }

                            }
                        }
                    },
                });
            }
            // get visa types
            function getVisaTypes(type, selectedOpt) {
                var selectVisaType = "selectVisaType";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        SelectVisaType: selectVisaType,
                    },
                    success: function(response) {
                        var visaType = JSON.parse(response);
                        if (type == "all") {
                            $('#visaType').empty();
                            $('#visaType').append("<option value='-1'>--Visa Type--</option>");
                            for (var i = 0; i < visaType.length; i++) {
                                $('#visaType').append("<option value='" + visaType[i].country_id + "'>" +
                                    visaType[i].country_names + "</option>");
                            }
                        } else {
                            $('#visaType').empty();
                            $('#visaType').append("<option value='-1'>--Visa Type--</option>");
                            for (var i = 0; i < visaType.length; i++) {
                                if (selectedOpt == visaType[i].country_id) {
                                    $('#visaType').append("<option selected value='" + visaType[i].country_id + "'>" +
                                        visaType[i].country_names + "</option>");
                                } else {
                                    $('#visaType').append("<option value='" + visaType[i].country_id + "'>" +
                                        visaType[i].country_names + "</option>");
                                }
                            }
                        }

                    },
                });
            }
            // get currencies
            function getCurrencies(type, selectedCurrency) {
                var currencyTypes = "currencyTypes";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        CurrencyTypes: currencyTypes,
                        Type: type,
                        SelectedCurrency: selectedCurrency
                    },
                    success: function(response) {
                        var currencyType = JSON.parse(response);
                        if (type == "saleCur") {
                            $('#sale_currency_type').empty();
                            for (var i = 0; i < currencyType.length; i++) {
                                $('#sale_currency_type').append("<option value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "updsaleCur") {
                            $('#sale_currency_type').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == selectedCurrency) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#sale_currency_type').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "salaryCur") {
                            $('#salaryCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].salaryCurID) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#salaryCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "offerLCostCur") {
                            $('#offerLetterCostCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].offerLetterCostCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#offerLetterCostCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "laborCardFeeCur") {
                            $('#laborCardCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].laborCardCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#laborCardCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "EvisaTying") {
                            $('#eVisaCostCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].eVisaCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#eVisaCostCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "changeStatus") {
                            $('#changeStatusCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].changeStatusCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#changeStatusCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "medicalTyping") {
                            $('#medicalCostCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].medicalTCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#medicalCostCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "emiratesIDTyping") {
                            $('#emiratesIDCostCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].emiratesIDCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#emiratesIDCostCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "visaStamping") {
                            $('#visaStampingCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].visaStampingCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#visaStampingCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        } else if (type == "insuranceCur") {
                            $('#insuranceCur').empty();
                            var selected = "";
                            for (var i = 0; i < currencyType.length; i++) {
                                if (currencyType[i].currencyID == currencyType[i].insuranceCur) {
                                    selected = "selected";
                                } else {
                                    selected = "";
                                }
                                $('#insuranceCur').append("<option " + selected + " value='" + currencyType[i].currencyID + "'>" +
                                    currencyType[i].currencyName + "</option>");
                            }
                        }
                    },
                });
            }
            // get positions
            function getPositions(id) {
                var getPositions = "getPositions";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetPositions: getPositions,
                        ID: id
                    },
                    success: function(response) {
                        var positions = JSON.parse(response);
                        $('#position').empty();
                        $('#position').append("<option value='-1'>--Add Position--</option>");
                        var selected = '';
                        for (var i = 0; i < positions.length; i++) {
                            if (i == 0 || positions[i].PositionID == positions[i].position_id) {
                                selected = 'selected';
                            } else if (positions[i].PositionID == <?php echo isset($res['positionID']) ? $res['positionID'] : "0"; ?>) {
                                selected = 'selected';
                            } else {
                                selected = '';
                            }
                            $('#position').append("<option " + selected + "   value='" + positions[i].position_id + "'>" +
                                positions[i].posiiton_name + "</option>");
                        }
                    },
                });
            }
            // save or update basic data
            $(document).on('submit', '#basicInfoForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (type == "completed") {
                    if (GRID == "") {
                        notify('Validation Error!', 'Something went wrong! x1', 'error');
                        return;
                    }
                }
                var type = $('.stepper-container a')[0].classList[1];
                if (type != "completed" || type != "active") {
                    if (type == "") {
                        notify('Validation Error!', 'Something went wrong! x2', 'error');
                        return;
                    }
                }
                if (type == "active") {
                    if (GRID != "") {
                        notify('Validation Error!', 'Something went wrong! x3', 'error');
                        return;
                    }
                }
                var customer_id = $('#customer_id').val();
                if (customer_id == "-1") {
                    notify('Validation Error!', 'Customer name is required', 'error');
                    return;
                }
                var passengerName = $('#passengerName').val();
                if (passengerName == "") {
                    notify('Validation Error!', 'Passenger name is required', 'error');
                    return;
                }
                var nationality = $('#nationality').val();
                if (nationality == "-1") {
                    notify('Validation Error!', 'Nationality is required', 'error');
                    return;
                }
                var visaType = $('#visaType').val();
                if (visaType == "-1") {
                    notify('Validation Error!', 'Visa type is required', 'error');
                    return;
                }
                var sale_amount = $('#sale_amount').val();
                if (sale_amount == "") {
                    notify('Validation Error!', 'Sale amount is required', 'error');
                    return;
                }
                var sale_currency_type = $('#sale_currency_type').val();

                var passportNumber = $('#passportNumber').val();
                if (passportNumber == "") {
                    notify('Validation Error!', 'Passport number is required', 'error');
                    return;
                }
                var passportExpiryDate = $('#passportExpiryDate').val();
                if (passportExpiryDate == "") {
                    notify('Validation Error!', 'Passport expiry date is required', 'error');
                    return;
                }

                var dob = $('#dob').val();
                if (dob == "") {
                    notify('Validation Error!', 'Date of Birth is required', 'error');
                    return;
                }

                var gender = $('#gender').val();
                if (gender == "") {
                    notify('Validation Error!', 'Gender is required', 'error');
                    return;
                }

                var uid = $('#uid').val();
                if (uid == "") {
                    notify('Validation Error!', 'UID is required', 'error');
                    return;
                }

                var basicInfoFile = $('#basicInfoFile').val();
                var basicInfoFilePhoto = $('#basicInfoFilePhoto').val();
                var basicInfoFileIDFront = $('#basicInfoFileIDFront').val();
                var basicInfoFileIDBack = $('#basicInfoFileIDBack').val();

                if (type == "active") {
                    if (basicInfoFile == "") {
                        notify('Validation Error!', 'Passport file is required!', 'error');
                        return;
                    }

                    if (basicInfoFilePhoto == "") {
                        notify('Validation Error!', 'Photo is required!', 'error');
                        return;
                    }

                    if ($('#basicInfoFile')[0].files[0].size > 20971520) {
                        notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                        return;
                    }
                    var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                    var extension = basicInfoFile.substr((basicInfoFile.lastIndexOf('.') + 1));
                    if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                        notify('Validation Error!', 'Passport has wrong file extension is required!', 'error');
                        return;
                    }
                } else {
                    if (basicInfoFile != "") {
                        if ($('#basicInfoFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = basicInfoFile.substr((basicInfoFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Passport has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('InsertBasicData', 'InsertBasicData');
                data.append('Type', type);
                data.append('ID', GRID);
                data.append('passportNumber', passportNumber);
                data.append('passportExpiryDate', passportExpiryDate);
                data.append('dob', dob);
                data.append('gender', gender);
                $('#step1Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if ($.isNumeric(response)) {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step1').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step1Btn').attr('disabled', false);
                            } else {
                                $('#GRID').val(response);
                                $('#ComStpID').val(1);
                                $('#step1').addClass('d-none');
                                $('.stepper-container a')[0].classList.remove('active');
                                $('.stepper-container a')[0].classList.add('completed');
                                $('#step2').removeClass('d-none');
                                $('.stepper-container a')[1].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step1Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step1Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // open completed node
            function openCompletedNode(node_id) {
                if (node_id <= $('#ComStpID').val() || node_id == (parseInt($('#ComStpID').val()) + 1)) {
                    const allDiv = document.querySelectorAll('.stepper-container .contentDiv');
                    for (var i = 0; i < allDiv.length; i++) {
                        allDiv[i].classList.add('d-none');
                    }
                    $('#step' + node_id).removeClass('d-none');
                    if (node_id == 6 || node_id == 7 || node_id == 8 || node_id == 9 || node_id == 10) {
                        $('html,body').animate({
                            scrollTop: 240
                        }, 200);
                    } else {
                        $('html,body').animate({
                            scrollTop: 0
                        }, 200);
                    }
                    if (node_id <= $('#ComStpID').val()) {
                        if (node_id == 1) {
                            getBasicData();
                        } else if (node_id == 2) {
                            getOfferLetter();
                        } else if (node_id == 3) {
                            getInsurance();
                        } else if (node_id == 4) {
                            getLabourCard();
                        } else if (node_id == 5) {
                            getEVisaTyping();
                        } else if (node_id == 6) {
                            getChangeStatus();
                        } else if (node_id == 7) {
                            getMedicalTyping();
                        } else if (node_id == 8) {
                            getEmiratesIDTyping();
                        } else if (node_id == 9) {
                            getVisaStamping();
                        } else if (node_id == 10) {
                            getContractSubmmision();
                        }
                    }
                }
            }
            // get basic data
            function getBasicData() {
                var EditBasicData = "EditBasicData";
                var GRID = $('#GRID').val();
                $('#basicInfoFile').val('');
                getPositions(GRID);
                var type = $('.stepper-container a')[0].classList[1];
                if (type != "completed") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        EditBasicData: EditBasicData,
                        GRID: GRID
                    },
                    success: function(response) {
                        var dataRpt = JSON.parse(response);
                        getCustomer('byUpdate', dataRpt[0].customer_id);
                        $('#passengerName').val(dataRpt[0].passenger_name);
                        $('#passportNumber').val(dataRpt[0].passportNumber);
                        $('#passportExpiryDate').val(dataRpt[0].passportExpiryDate);
                        $('#insideOutside').val(dataRpt[0].InsideOutside);
                        $('#uid').val(dataRpt[0].uid);
                        $("#salary_amount").val(dataRpt[0].salary_amount);
                        $("#position").val(dataRpt[0].positionID);
                        getNationalities('ByUpdate', dataRpt[0].Nationality);
                        //('byUpdate', dataRpt[0].VisaType);
                        $('#sale_amount').val(dataRpt[0].sale_price);
                        getCurrencies('updsaleCur', dataRpt[0].saleCurID);
                        $('#dob').val(dataRpt[0].dob);
                        $('#gender').val(dataRpt[0].gender);
                        if (dataRpt[0].ResidenceDocID != 0) {
                            $('#basicDataFileIcon').removeClass('d-none');
                        }
                        if (dataRpt[0].ResidenceDocIDPhoto != 0) {
                            $('#basicDataFilePhotoIcon').removeClass('d-none');
                        }
                        if (dataRpt[0].ResidenceDocIDIDFront != 0) {
                            $('#basicDataFileIDFrontIcon').removeClass('d-none');
                        }
                        if (dataRpt[0].ResidenceDocIDIDBack != 0) {
                            $('#basicDataFileIDBackIcon').removeClass('d-none');
                        }
                    },
                });
            }
            // get offer letter
            function getOfferLetter() {
                var GRID = $('#GRID').val();
                $('#offerLetterFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong! 1', 'error');
                    return;
                }
                var getSalaryAndCostAmounts = "getSalaryAndCostAmounts";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetSalaryAndCostAmounts: getSalaryAndCostAmounts,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#salary_amount').val(rpt[0].salary_amount);
                        $('#offerLetterCost').val(rpt[0].offerLetterCost);
                        $("#mb_number").val(rpt[0].mb_number);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#offerLetterFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('salaryCur', GRID);
                //getPositions(GRID);
                getCompanies(GRID);
                getCurrencies('offerLCostCur', GRID);
                getOffLChargedEnitity('load', 'offerLetter');

            }
            // get step data
            function getStepsData(curStep) {
                if (curStep == 2) {
                    getOfferLetter();
                } else if (curStep == 3) {
                    getInsurance();
                } else if (curStep == 4) {
                    getLabourCard();
                } else if (curStep == 5) {
                    getEVisaTyping();
                } else if (curStep == 6) {
                    getChangeStatus();
                } else if (curStep == 7) {
                    getMedicalTyping();
                } else if (curStep == 8) {
                    getEmiratesIDTyping();
                } else if (curStep == 9) {
                    getVisaStamping();
                } else if (curStep == 10) {
                    getContractSubmmision();
                }
            }

            function positionFun() {
                if ($('#position').val() == "-1") {
                    $('#addPositionModal').modal('show');
                }
            }
            $('#addPositionModal').on('shown.bs.modal', function() {
                $('#position_name').trigger('focus');
            });
            // add position
            function addPosiitonFun() {
                var InsertPositionName = "InsertPositionName";
                var position_name = $('#position_name');
                if (position_name.val() == "") {
                    notify('Validation Error!', 'position name is required', 'error');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        InsertPositionName: InsertPositionName,
                        Position_Name: position_name.val(),
                    },
                    success: function(response) {
                        if (response == "Success") {
                            notify('Success!', response, 'success');
                            position_name.val('');
                            $('#addPositionModal').modal('hide');
                            getPositions($('#GRID').val());
                        } else {
                            notify('Error!', response, 'error');
                        }
                    },
                });
            }
            // get companies
            function getCompanies(id) {
                var getCompanies = "getCompanies";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetCompanies: getCompanies,
                        ID: id
                    },
                    success: function(response) {
                        var company = JSON.parse(response);
                        $('#company').empty();
                        $('#company').append("<option value='-1'> --Add Company -- </option>");
                        var selected = "";
                        for (var i = 0; i < company.length; i++) {
                            if (i == 0 || company[i].company_id == company[i].selectedCompay) {
                                selected = "selected";
                            } else {
                                selected = "";
                            }
                            $('#company').append("<option " + selected + " value='" + company[i].company_id + "'>" +
                                company[i].company_name + "</option>");
                        }
                    },
                });
            }
            $('#company').on('select2:select', function(e) {
                var data = e.params.data.id;
                if (data == "-1") {
                    $('#addCompanyModal').appendTo("body").modal('show');
                }
            });
            // add company
            function addCompanyFun() {
                var InsertCompanyName = "InsertCompanyName";
                var companyname = $('#companyname');
                if (companyname.val() == "") {
                    notify('Validation Error!', 'Company name is required', 'error');
                    return;
                }
                var companynumber = $('#companynumber');
                if (companynumber.val() == "") {
                    notify('Validation Error!', 'Company number is required', 'error');
                    return;
                }
                var startingquota = $('#startingquota');
                if (startingquota.val() == "") {
                    notify('Validation Error!', 'Number of quota is required', 'error');
                    return;
                }
                var localname = $('#localname');
                if (localname.val() == "") {
                    notify('Validation Error!', 'Name of Local is Required', 'error');
                    return;
                }
                var companyexpiry = $('#companyexpiry');
                if (companyexpiry.val() == "") {
                    notify('Validation Error!', 'Company Expiry Date is Required', 'error');
                    return;
                }
                var companyType = $('#company_type');
                if (companyType.val() == "-1") {
                    notify('Validation Error!', 'Company Type is Required', 'error');
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        InsertCompanyName: InsertCompanyName,
                        CompanyName: companyname.val(),
                        CompanyNumber: companynumber.val(),
                        StartingQuota: startingquota.val(),
                        LocalName: localname.val(),
                        CompanyExpiry: companyexpiry.val(),
                        CompanyType: companyType.val(),
                    },
                    success: function(response) {
                        if (response == "Success") {
                            notify('Success!', response, 'success');
                            companyname.val('');
                            companynumber.val('');
                            avalablequota.val('');
                            localname.val('');
                            compnayexpiry.val('');
                            $('#addCompanyModal').modal('hide');
                            getCompanies($('#GRID').val());
                        } else {
                            notify('Error!', response, 'error');
                        }
                    },
                });
            }
            // get charged Entitiy
            function getOffLChargedEnitity(handler, type) {
                var id = $('#GRID').val();
                var getChargedEnitity = "getChargedEnitity";
                var chargedON = '';
                if (handler == 'change' && type == "offerLetter") {
                    chargedON = $('#offerLChargOpt').val();
                } else if (handler == 'change' && type == "LaborCard") {
                    chargedON = $('#lrbChargOpt').val();
                } else if (handler == 'change' && type == "EVisaTyping") {
                    chargedON = $('#eVisaTChargOpt').val();
                } else if (handler == 'change' && type == "changeStatus") {
                    chargedON = $('#changeSChargOpt').val();
                } else if (handler == 'change' && type == "medicalTyping") {
                    chargedON = $('#medicalTChargOpt').val();
                } else if (handler == 'change' && type == "emiratesIDTyping") {
                    chargedON = $('#emirateIDChargOpt').val();
                } else if (handler == 'change' && type == "visaStamping") {
                    chargedON = $('#visaStampChargOpt').val();
                } else if (handler == 'change' && type == "insuranceCur") {
                    chargedON = $('#insuranceChargOpt').val();
                }
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetChargedEnitity: getChargedEnitity,
                        Type: type,
                        Handler: handler,
                        ChargedON: chargedON,
                        ID: id
                    },
                    success: function(response) {
                        var chargedEntitiy = JSON.parse(response);
                        if (type == "offerLetter") {
                            $('#offerLChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#offerLChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#offerLChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#offerLChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#offerLChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#offerLChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#offerLChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "LaborCard") {
                            $('#lbrChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#lrbChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#lbrChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#lbrChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#lrbChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#lbrChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#lbrChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "EVisaTyping") {
                            $('#eVisaTChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#eVisaTChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#eVisaTChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#eVisaTChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#eVisaTChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#eVisaTChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#eVisaTChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "changeStatus") {
                            $('#changeSChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#changeSChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#changeSChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#changeSChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#changeSChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#changeSChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#changeSChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "medicalTyping") {
                            $('#medicalTChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#medicalTChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#medicalTChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#medicalTChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#medicalTChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#medicalTChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#medicalTChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "emiratesIDTyping") {
                            $('#emiratesIDChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#emirateIDChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#emiratesIDChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#emiratesIDChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#emirateIDChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#emiratesIDChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#emiratesIDChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "visaStamping") {
                            $('#visaStampChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#visaStampChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#visaStampChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#visaStampChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#visaStampChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#visaStampChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#visaStampChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        } else if (type == "insuranceCur") {
                            $('#insuranceChargedEntity').empty();

                            if (chargedEntitiy[0].chargedON == 1) {

                                var selected = "";
                                $('#insuranceChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#insuranceChargedEntity').append("<option value='-1'>--Select Account--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].account_ID == chargedEntitiy[i].selectedAccount) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#insuranceChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].account_ID + "'>" +
                                        chargedEntitiy[i].account_Name + "</option>");
                                }
                            } else if (chargedEntitiy[0].chargedON == 2) {
                                var selected = "";
                                $('#insuranceChargOpt').val(chargedEntitiy[0].chargedON).trigger('change', [true]);
                                $('#insuranceChargedEntity').append("<option value='-1'>--Select Supplier--</option>");
                                for (var i = 0; i < chargedEntitiy.length; i++) {
                                    if (chargedEntitiy[i].supp_id == chargedEntitiy[i].selectedSupplier) {
                                        selected = "selected";
                                    } else {
                                        selected = "";
                                    }
                                    $('#insuranceChargedEntity').append("<option " + selected + " value='" + chargedEntitiy[i].supp_id + "'>" +
                                        chargedEntitiy[i].supp_name + "</option>");
                                }
                            }
                        }
                    },
                });
            }
            // distinguish calls on offer letter charge dropdown to avoid infinite loop
            $('#offerLChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'offerLetter');
            });
            // get insurance info
            function getInsurance() {
                var GRID = $('#GRID').val();
                $('#insuranceFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getInsuranceCost = "getInsuranceCost";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetInsuranceCost: getInsuranceCost,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#insuranceCost').val(rpt[0].insuranceCost);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#insuranceFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('insuranceCur', GRID);
                getOffLChargedEnitity('load', 'insuranceCur');
            }
            // distinguish calls on insurance charge dropdown to avoid infinite loop
            $('#insuranceChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'insuranceCur');
            });
            // save offer letter info
            $(document).on('submit', '#offerLetterForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var salary_amount = $('#salary_amount').val();

                var salaryCur = $('#salaryCur').val();
                var position = $('#position').val();
                if (position == "-1") {
                    notify('Validation Error!', 'Position is required', 'error');
                    return;
                }
                var company = $('#company').val();
                if (company == "-1") {
                    notify('Validation Error!', 'Company is required', 'error');
                    return;
                }
                var offerLetterCost = $('#offerLetterCost').val();
                if (offerLetterCost == "" || offerLetterCost <= 0) {
                    notify('Validation Error!', 'Offer letter cost is required', 'error');
                    return;
                }
                var offerLetterCostCur = $('#offerLetterCostCur').val();
                var offerLChargOpt = $('#offerLChargOpt').val();
                if (offerLChargOpt == "1") {
                    if ($('#offerLChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (offerLChargOpt == "2") {
                    if ($('#offerLChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                var offerLChargedEntity = $('#offerLChargedEntity').val();
                var type = $('.stepper-container a')[1].classList[1];
                var offerLetterFile = $('#offerLetterFile').val();
                if (type == "active") {
                    // if(offerLetterFile == ""){
                    //     notify('Validation Error!', 'Offer letter file is required!', 'error');
                    //     return;
                    // }
                    if (offerLetterFile) {

                        if ($('#offerLetterFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = offerLetterFile.substr((offerLetterFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Offer letter has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (offerLetterFile != "") {
                        if ($('#offerLetterFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = offerLetterFile.substr((offerLetterFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Offer letter has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('SaveOfferLetterData', 'SaveOfferLetterData');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step2Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step2').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step2Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(2);
                                $('#step2').addClass('d-none');
                                $('.stepper-container a')[1].classList.remove('active');
                                $('.stepper-container a')[1].classList.add('completed');
                                $('#step3').removeClass('d-none');
                                $('.stepper-container a')[2].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step2Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step2Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // save insurance info
            $(document).on('submit', '#insuranceForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var insuranceCost = $('#insuranceCost').val();
                if (insuranceCost == "" || insuranceCost <= 0) {
                    notify('Validation Error!', 'Insurance cost is required', 'error');
                    return;
                }
                var insuranceCur = $('#insuranceCur').val();
                var insuranceChargOpt = $('#insuranceChargOpt').val();
                if (insuranceChargOpt == "1") {
                    if ($('#insuranceChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (insuranceChargOpt == "2") {
                    if ($('#insuranceChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                var insuranceChargedEntity = $('#insuranceChargedEntity').val();
                var type = $('.stepper-container a')[2].classList[1];
                var insuranceFile = $('#insuranceFile').val();
                if (type == "active") {
                    // if (insuranceFile == "") {
                    //     notify('Validation Error!', 'Insurance file is required!', 'error');
                    //     return;
                    // }
                    if (insuranceFile) {
                        if ($('#insuranceFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = insuranceFile.substr((insuranceFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Insurance has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (insuranceFile != "") {
                        if ($('#insuranceFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = insuranceFile.substr((insuranceFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Insurance has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('SaveInsuranceData', 'SaveInsuranceData');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step3Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step3').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step3Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(3);
                                $('#step3').addClass('d-none');
                                $('.stepper-container a')[2].classList.remove('active');
                                $('.stepper-container a')[2].classList.add('completed');
                                $('#step4').removeClass('d-none');
                                $('.stepper-container a')[3].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step3Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step3Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get labour card info
            function getLabourCard() {
                var GRID = $('#GRID').val();
                $('#laborCardFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getLabourCrdIDAndFee = "getLabourCrdIDAndFee";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetLabourCrdIDAndFee: getLabourCrdIDAndFee,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#labor_card_id').val(rpt[0].laborCardID);
                        $('#labour_card_fee').val(rpt[0].laborCardFee);
                        $('#mb_number').val(rpt[0].mb_number);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#laborCardFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('laborCardFeeCur', GRID);
                getOffLChargedEnitity('load', 'LaborCard');
            }
            // distinguish calls on labor card charge dropdown to avoid infinite loop
            $('#lrbChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'LaborCard');
            });
            // save labor card info
            $(document).on('submit', '#laborCardForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var labor_card_id = $('#labor_card_id').val();
                if (labor_card_id == "") {
                    notify('Validation Error!', 'Labor card id is required', 'error');
                    return;
                }
                var labour_card_fee = $('#labour_card_fee').val();
                if (labour_card_fee <= 0 || labour_card_fee == "") {
                    notify('Validation Error!', 'Labor card fee is required', 'error');
                    return;
                }
                var laborCardCur = $('#laborCardCur').val();
                var lrbChargOpt = $('#lrbChargOpt').val();
                if (lrbChargOpt == "1") {
                    if ($('#lbrChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (lrbChargOpt == "2") {
                    if ($('#lbrChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                var lbrChargedEntity = $('#lbrChargedEntity').val();
                var type = $('.stepper-container a')[3].classList[1];
                var laborCardFile = $('#laborCardFile').val();
                if (type == "active") {
                    // if (laborCardFile == "") {
                    //     notify('Validation Error!', 'Labour card file is required!', 'error');
                    //     return;
                    // }
                    if (laborCardFile) {
                        if ($('#laborCardFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = laborCardFile.substr((laborCardFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Labour card has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (laborCardFile != "") {
                        if ($('#laborCardFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = laborCardFile.substr((laborCardFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Labour card has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('SaveLabourCardData', 'SaveLabourCardData');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step4Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step4').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step4Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(4);
                                $('#step4').addClass('d-none');
                                $('.stepper-container a')[3].classList.remove('active');
                                $('.stepper-container a')[3].classList.add('completed');
                                $('#step5').removeClass('d-none');
                                $('.stepper-container a')[4].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step4Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step4Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get E-Visa Typing
            function getEVisaTyping() {
                var GRID = $('#GRID').val();
                $('#eVisaFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getVisaTypingFee = "getVisaTypingFee";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetVisaTypingFee: getVisaTypingFee,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#evisa_cost').val(rpt[0].eVisaCost);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#eVisaTypingFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('EvisaTying', GRID);
                getOffLChargedEnitity('load', 'EVisaTyping');
            }
            // distinguish calls on e-Visa types dropdown to avoid infinite loop
            $('#eVisaTChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'EVisaTyping');
            });
            // save E-visa Typing info
            $(document).on('submit', '#eVisaForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var evisa_cost = $('#evisa_cost').val();
                if (evisa_cost == "" || evisa_cost <= 0) {
                    notify('Validation Error!', 'E-Visa cost is required!', 'error');
                    return;
                }
                var eVisaCostCur = $('#eVisaCostCur').val();
                var eVisaFile = $('#eVisaFile').val();
                var type = $('.stepper-container a')[4].classList[1];
                if (type == "active") {
                    // if (eVisaFile == "") {
                    //     notify('Validation Error!', 'E-visa file is required!', 'error');
                    //     return;
                    // }
                    if (eVisaFile) {
                        if ($('#eVisaFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = eVisaFile.substr((eVisaFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'E-visa has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (eVisaFile != "") {
                        if ($('#eVisaFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = eVisaFile.substr((eVisaFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'E-visa has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                var eVisaTChargOpt = $('#eVisaTChargOpt').val();
                var eVisaTChargedEntity = $('#eVisaTChargedEntity');
                if (eVisaTChargOpt == "1") {
                    if ($('#eVisaTChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (eVisaTChargOpt == "2") {
                    if ($('#eVisaTChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                data = new FormData(this);
                data.append('SaveEVisaTyping', 'SaveEVisaTyping');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step5Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step5').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step5Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(5);
                                $('#step5').addClass('d-none');
                                $('.stepper-container a')[4].classList.remove('active');
                                $('.stepper-container a')[4].classList.add('completed');
                                $('#step6').removeClass('d-none');
                                $('.stepper-container a')[5].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step5Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step5Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get uploaded files report
            function getUploadedFiles(type) {
                var getUploadedFiles = "getUploadedFiles";
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                $('#getUploadedFilesModal').appendTo("body").modal('show');
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetUploadedFiles: getUploadedFiles,
                        ID: GRID,
                        Type: type
                    },
                    success: function(response) {
                        var filesRpt = JSON.parse(response);
                        if (filesRpt.length == 0) {
                            $('#filesReport').empty();
                            var finalTable = "<tr><td></td><td>Record Not Found</td><td></td></tr>";
                            $('#filesReport').append(finalTable);
                        } else {
                            $('#filesReport').empty();
                            var j = 1;
                            var finalTable = "";
                            for (var i = 0; i < filesRpt.length; i++) {
                                finalTable = "<tr><th scope='row'>" + j + "</th><td><a href='downloadResDocuments.php?id=" + filesRpt[i].ResidenceDocID + "'>" +
                                    filesRpt[i].original_name + "</a></td>";
                                finalTable += "<td><button type='button' onclick='deleteFile(" + filesRpt[i].ResidenceDocID + ")'" +
                                    "class='btn btn-danger'><i class='fa fa-trash o fa-2x' aria-hidden='true'></i></button>&nbsp;</td></tr>";
                                $('#filesReport').append(finalTable);

                                j += 1;
                            }
                        }
                    },
                });
            }
            // delete file
            function deleteFile(id) {
                var DeleteFile = "DeleteFile";
                $.confirm({
                    title: 'Delete!',
                    content: "Do you want to delete this file?",
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        tryAgain: {
                            text: 'Yes',
                            btnClass: 'btn-red',
                            action: function() {
                                $.ajax({
                                    type: "POST",
                                    url: "residenceController.php",
                                    data: {
                                        DeleteFile: DeleteFile,
                                        ID: id
                                    },
                                    success: function(response) {

                                        if (response == 'Success') {
                                            notify('Success!', "File Successfully deleted", 'success');
                                            $('#getUploadedFilesModal').modal('hide');
                                        } else {
                                            notify('Opps!', response, 'error');
                                        }
                                    },
                                });
                            }
                        },
                        close: function() {}
                    }
                });
            }
            // get change status
            function getChangeStatus() {
                var GRID = $('#GRID').val();
                $('#changeStatusFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getChangeStatusFee = "getChangeStatusFee";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetChangeStatusFee: getChangeStatusFee,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#changeStatusCost').val(rpt[0].changeStatusCost);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#changeStatusFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('changeStatus', GRID);
                getOffLChargedEnitity('load', 'changeStatus');
            }
            // distinguish calls on change status dropdown to avoid infinite loop
            $('#changeSChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'changeStatus');
            });
            // save change status info
            $(document).on('submit', '#changeStatusForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var changeStatusCost = $('#changeStatusCost').val();

                /*
                if (changeStatusCost == "" || changeStatusCost <= 0) {
                    notify('Validation Error!', 'Change status cost is required!', 'error');
                    return;
                }*/
                var changeStatusCur = $('#changeStatusCur').val();
                var changeStatusFile = $('#changeStatusFile').val();
                var type = $('.stepper-container a')[5].classList[1];
                if (type == "active") {
                    // if (changeStatusFile == "") {
                    //     notify('Validation Error!', 'Change status file is required!', 'error');
                    //     return;
                    // }
                    if (changeStatusFile) {
                        if ($('#changeStatusFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = changeStatusFile.substr((changeStatusFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Change status has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (changeStatusFile != "") {
                        if ($('#changeStatusFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = changeStatusFile.substr((changeStatusFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Change status has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                var changeSChargOpt = $('#changeSChargOpt').val();
                var changeSChargedEntity = $('#changeSChargedEntity');
                if (changeSChargOpt == "1") {
                    if ($('#changeSChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (changeSChargOpt == "2") {
                    if ($('#changeSChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                data = new FormData(this);
                data.append('SaveChangeStatus', 'SaveChangeStatus');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step6Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step6').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step6Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(6);
                                $('#step6').addClass('d-none');
                                $('.stepper-container a')[5].classList.remove('active');
                                $('.stepper-container a')[5].classList.add('completed');
                                $('#step7').removeClass('d-none');
                                $('.stepper-container a')[6].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step6Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step6Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get medical typing
            function getMedicalTyping() {
                var GRID = $('#GRID').val();
                $('#medicalFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getMedicalFee = "getMedicalFee";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetMedicalFee: getMedicalFee,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#medical_cost').val(rpt[0].medicalTCost);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#medicalTypingFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('medicalTyping', GRID);
                getOffLChargedEnitity('load', 'medicalTyping');
            }
            // distinguish calls on medical typing dropdown to avoid infinite loop
            $('#medicalTChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'medicalTyping');
            });
            // save medical typing info
            $(document).on('submit', '#medicalTypingForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var medical_cost = $('#medical_cost').val();
                if (medical_cost == "" || medical_cost <= 0) {
                    notify('Validation Error!', 'Medical typing cost is required!', 'error');
                    return;
                }
                var medicalCostCur = $('#medicalCostCur').val();
                var medicalFile = $('#medicalFile').val();
                var type = $('.stepper-container a')[6].classList[1];
                if (type == "active") {
                    // if (medicalFile == "") {
                    //     notify('Validation Error!', 'Medical typing file is required!', 'error');
                    //     return;
                    // }
                    if (medicalFile) {
                        if ($('#medicalFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = medicalFile.substr((medicalFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Medical typing has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (medicalFile != "") {
                        if ($('#medicalFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = medicalFile.substr((medicalFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Medical typing has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                var medicalTChargOpt = $('#medicalTChargOpt').val();
                var medicalTChargedEntity = $('#medicalTChargedEntity');
                if (medicalTChargOpt == "1") {
                    if ($('#medicalTChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (medicalTChargOpt == "2") {
                    if ($('#medicalTChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                data = new FormData(this);
                data.append('SaveMedicalTyping', 'SaveMedicalTyping');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step7Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step7').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step7Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(7);
                                $('#step7').addClass('d-none');
                                $('.stepper-container a')[6].classList.remove('active');
                                $('.stepper-container a')[6].classList.add('completed');
                                $('#step8').removeClass('d-none');
                                $('.stepper-container a')[7].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step7Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step7Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get emirates ID typing
            function getEmiratesIDTyping() {
                var GRID = $('#GRID').val();
                $('#emiratesIDFile').val('');
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var getEmiratesIDTCost = "getEmiratesIDTCost";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetEmiratesIDTCost: getEmiratesIDTCost,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#emiratesIDCost').val(rpt[0].emiratesIDCost);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#emiratesIDFilesIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('emiratesIDTyping', GRID);
                getOffLChargedEnitity('load', 'emiratesIDTyping');
            }
            // distinguish calls on emirates id typing dropdown to avoid infinite loop
            $('#emirateIDChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'emiratesIDTyping');
            });
            // save emirates id typing info
            $(document).on('submit', '#emiratesIDForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var emiratesIDCost = $('#emiratesIDCost').val();
                if (emiratesIDCost == "" || emiratesIDCost <= 0) {
                    notify('Validation Error!', 'Emirates ID typing cost is required!', 'error');
                    return;
                }
                var emiratesIDCostCur = $('#emiratesIDCostCur').val();
                var emiratesIDFile = $('#emiratesIDFile').val();
                var type = $('.stepper-container a')[7].classList[1];
                if (type == "active") {
                    // if (emiratesIDFile == "") {
                    //     notify('Validation Error!', 'Emirates ID typing file is required!', 'error');
                    //     return;
                    // }
                    if (emiratesIDFile) {
                        if ($('#emiratesIDFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = emiratesIDFile.substr((emiratesIDFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Emirates ID typing has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (emiratesIDFile != "") {
                        if ($('#emiratesIDFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = emiratesIDFile.substr((emiratesIDFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Emirates ID typing has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                var emirateIDChargOpt = $('#emirateIDChargOpt').val();
                var emiratesIDChargedEntity = $('#emiratesIDChargedEntity');
                if (emirateIDChargOpt == "1") {
                    if ($('#emiratesIDChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (emirateIDChargOpt == "2") {
                    if ($('#emiratesIDChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                data = new FormData(this);
                data.append('SaveEmiratesIDTyping', 'SaveEmiratesIDTyping');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step8Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step8').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step8Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(8);
                                $('#step8').addClass('d-none');
                                $('.stepper-container a')[7].classList.remove('active');
                                $('.stepper-container a')[7].classList.add('completed');
                                $('#step9').removeClass('d-none');
                                $('.stepper-container a')[8].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step8Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step8Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get visa stamping  info
            function getVisaStamping() {
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                $('#visaStampingFile').val('');
                //Date picker 

                $('#expiry_date').datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd'
                }).on('show', function(e) {
                    if (e.date) {
                        $(this).data('stickyDate', e.date);
                    } else if ($(this).val()) {

                        /**auto-populate existing selection*/
                        $(this).data('stickyDate', new Date($(this).val()));
                        $(this).datepicker('setDate', new Date($(this).val()));
                    } else {
                        $(this).data('stickyDate', null);
                    }
                }).on('hide', function(e) {
                    var stickyDate = $(this).data('stickyDate');
                    if (!e.date && stickyDate) {
                        $(this).datepicker('setDate', stickyDate);
                        $(this).data('stickyDate', null);
                    }
                });
                const date = new Date();
                month = date.getMonth() + 1;
                if (month == 1) {
                    month = "01";
                } else if (month == 2) {
                    month = "02";
                } else if (month == 3) {
                    month = "03";
                } else if (month == 4) {
                    month = "04";
                } else if (month == 5) {
                    month = "05";
                } else if (month == 6) {
                    month = "06";
                } else if (month == 7) {
                    month = "07";
                } else if (month == 8) {
                    month = "08";
                } else if (month == 9) {
                    month = "09";
                }
                var day = date.getDate();
                if (day == 1) {
                    day = "01";
                } else if (day == 2) {
                    day = "02";
                } else if (day == 3) {
                    day = "03";
                } else if (day == 4) {
                    day = "04";
                } else if (day == 5) {
                    day = "05";
                } else if (day == 6) {
                    day = "06";
                } else if (day == 7) {
                    day = "07";
                } else if (day == 8) {
                    day = "08";
                } else if (day == 9) {
                    day = "09";
                }
                $('#expiry_date').val(date.getFullYear() + '-' + month + '-' + day);
                var getVisaStamping = "getVisaStamping";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetVisaStamping: getVisaStamping,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#visaStampingCost').val(rpt[0].visaStampingCost);
                        $('#expiry_date').val(rpt[0].expiry_date);
                        $('#laborCardNumber').val(rpt[0].LabourCardNumber);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#visaStampingFileIcon').removeClass('d-none');
                        }
                    },
                });
                getCurrencies('visaStamping', GRID);
                getOffLChargedEnitity('load', 'visaStamping');
            }
            // distinguish calls on visa stamping dropdown to avoid infinite loop
            $('#visaStampChargOpt').on("change", function(e, state) {
                //we check state if exists and is true then event was triggered by code
                if (typeof state != 'undefined' && state) {
                    return false;
                }
                getOffLChargedEnitity('change', 'visaStamping');
            });
            // save visa stamping info
            $(document).on('submit', '#visaStampingForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var visaStampingCost = $('#visaStampingCost').val();
                if (visaStampingCost <= 0 || visaStampingCost == "") {
                    notify('Validation Error!', 'Visa stamping cost is required', 'error');
                    return;
                }
                var visaStampingCur = $('#visaStampingCur').val();
                var expiry_date = $('#expiry_date').val();
                if (expiry_date == "") {
                    notify('Validation Error!', 'Expiry date is required!', 'error');
                    return;
                }
                var laborCardNumber = $('#laborCardNumber').val();
                if (laborCardNumber == "") {
                    notify('Validation Error!', 'Labor card number is required!', 'error');
                    return;
                }
                var visaStampChargOpt = $('#visaStampChargOpt').val();
                if (visaStampChargOpt == "1") {
                    if ($('#visaStampChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Account is required', 'error');
                        return;
                    }
                } else if (visaStampChargOpt == "2") {
                    if ($('#visaStampChargedEntity').val() == '-1') {
                        notify('Validation Error!', 'Supplier is required', 'error');
                        return;
                    }
                }
                var visaStampChargedEntity = $('#visaStampChargedEntity').val();
                var type = $('.stepper-container a')[8].classList[1];
                var visaStampingFile = $('#visaStampingFile').val();
                if (type == "active") {
                    // if (visaStampingFile == "") {
                    //     notify('Validation Error!', 'Visa Stamping file is required!', 'error');
                    //     return;
                    // }
                    if (visaStampingFile) {
                        if ($('#visaStampingFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = visaStampingFile.substr((visaStampingFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Visa stamping has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (visaStampingFile != "") {
                        if ($('#visaStampingFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = visaStampingFile.substr((visaStampingFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Visa stamping has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('SaveVisaStamping', 'SaveVisaStamping');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step9Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step9').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step9Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(9);
                                $('#step9').addClass('d-none');
                                $('.stepper-container a')[8].classList.remove('active');
                                $('.stepper-container a')[8].classList.add('completed');
                                $('#step10').removeClass('d-none');
                                $('.stepper-container a')[9].classList.add('active');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step9Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step9Btn').attr('disabled', false);
                        }
                    },
                });
            });
            // get contract submission  info
            function getContractSubmmision() {
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                $('#contractSubmissionFile').val('');
                var getContractSubmmision = "getContractSubmmision";
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        GetContractSubmmision: getContractSubmmision,
                        ID: GRID
                    },
                    success: function(response) {
                        var rpt = JSON.parse(response);
                        $('#emiratesIDNumber').val(rpt[0].EmiratesIDNumber);
                        if (rpt[0].ResidenceDocID != 0) {
                            $('#contractSubFilesIcon').removeClass('d-none');
                        }
                    },
                });
            }
            // save contract submission info
            $(document).on('submit', '#contractSubForm', function(event) {
                event.preventDefault();
                var GRID = $('#GRID').val();
                if (GRID == "") {
                    notify('Validation Error!', 'Something went wrong!', 'error');
                    return;
                }
                var emiratesIDNumber = $('#emiratesIDNumber').val();
                if (emiratesIDNumber == "") {
                    notify('Validation Error!', 'Contract submission emirates id number is required!', 'error');
                    return;
                }
                var contractSubmissionFile = $('#contractSubmissionFile').val();
                var type = $('.stepper-container a')[9].classList[1];
                if (type == "active") {
                    // if (contractSubmissionFile == "") {
                    //     notify('Validation Error!', 'Contract submission file is required!', 'error');
                    //     return;
                    // }
                    if (contractSubmissionFile) {
                        if ($('#contractSubmissionFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = contractSubmissionFile.substr((contractSubmissionFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Contract submission file has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                } else {
                    if (contractSubmissionFile != "") {
                        if ($('#contractSubmissionFile')[0].files[0].size > 20971520) {
                            notify('Error!', 'File size is greater than 20 MB. Make Sure It should be less than 20 MB ', 'error');
                            return;
                        }
                        var allowedExtension = ['jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'gif', 'txt', 'csv', 'ppt', 'pptx', 'rar', 'xls', 'xlsx', 'zip'];
                        var extension = contractSubmissionFile.substr((contractSubmissionFile.lastIndexOf('.') + 1));
                        if (jQuery.inArray(extension.toLowerCase(), allowedExtension) == -1) {
                            notify('Validation Error!', 'Contract submission file has wrong file extension is required!', 'error');
                            return;
                        }
                    }
                }
                data = new FormData(this);
                data.append('SaveContractSubmission', 'SaveContractSubmission');
                data.append('Type', type);
                data.append('ID', GRID);
                $('#step10Btn').attr('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response == "Succcess") {
                            if (type == "completed") {
                                notify('Success!', "Record Successfully updated", 'success');
                                $('#step10').addClass('d-none');
                                if ($('#ComStpID').val() != 10) {
                                    $('#step' + (parseInt($('#ComStpID').val()) + 1)).removeClass('d-none');
                                    setCurrentStep($('#ComStpID').val());
                                }
                                $('#step10Btn').attr('disabled', false);
                            } else {
                                $('#ComStpID').val(10);
                                $('#step10').addClass('d-none');
                                $('.stepper-container a')[9].classList.remove('active');
                                $('.stepper-container a')[9].classList.add('completed');
                                notify('Success!', "Record Successfully added", 'success');
                                setCurrentStep($('#ComStpID').val());
                                $('#step10Btn').attr('disabled', false);
                            }
                        } else {
                            notify('Error!', response, 'error');
                            $('#step10Btn').attr('disabled', false);
                        }
                    },
                });
            });
        </script>

        <!-- Scripts placed at end of body for proper DOM loading -->
        <script>
            console.log('Debug: Before loading residencePassport.js');
        </script>
        <script src="residencePassport.js"></script>
        <script>
            // Initialize passport processing when the document is ready
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Debug: Document ready, initializing passport processing');

                // Get the file input
                const fileInput = document.getElementById('basicInfoFile');
                console.log('Debug: File input found:', fileInput);

                // Set up the Use Test Data button
                const useTestDataBtn = document.getElementById('useTestDataBtn');
                if (useTestDataBtn) {
                    useTestDataBtn.addEventListener('click', function() {
                        console.log('Debug: Using test data button clicked');

                        // Test data to fill the form
                        const testData = {
                            passport_number: 'AB1234567',
                            country_code: 'USA',
                            surname: 'DOE',
                            given_names: 'JOHN',
                            nationality: 'AFG', // Change to Afghanistan to test
                            dob: '01/01/1980',
                            gender: 'M',
                            expiry_date: '01/01/2030',
                            name: 'JOHN DOE',
                            date_of_birth: '01/01/1980'
                        };

                        // Populate the form fields with test data
                        const fields = {
                            'passportNumber': testData.passport_number || '',
                            'passportExpiryDate': testData.expiry_date || '',
                            'dob': testData.date_of_birth || testData.dob || '',
                            'gender': (testData.gender || '').toLowerCase() === 'm' ? 'male' : (testData.gender || '').toLowerCase() === 'f' ? 'female' : '',
                            // Create full name by combining given_names and surname
                            'passengerName': ((testData.given_names || '') + ' ' + (testData.surname || '')).trim() || testData.name || ''
                        };

                        console.log('Debug: Populating with test data:', fields);

                        for (const [id, value] of Object.entries(fields)) {
                            const field = document.getElementById(id);
                            if (field && value) {
                                field.value = value;
                                console.log('Debug: Set field', id, 'to', value);

                                // Trigger change event
                                const event = new Event('change', {
                                    bubbles: true
                                });
                                field.dispatchEvent(event);
                            } else {
                                console.log('Debug: Field not found or no value:', id,
                                    field ? 'element exists' : 'element not found',
                                    value ? 'has value' : 'no value');
                            }
                        }

                        // Handle nationality dropdown separately
                        if (testData.nationality) {
                            const nationalityField = document.getElementById('nationality');
                            if (nationalityField) {
                                console.log('Looking for nationality match for:', testData.nationality);

                                // Country code mapping to full names
                                const countryCodeMap = {
                                    'AFG': 'Afghanistan',
                                    'USA': 'United States',
                                    'GBR': 'United Kingdom',
                                    'CAN': 'Canada',
                                    'AUS': 'Australia',
                                    'IND': 'India',
                                    'PAK': 'Pakistan',
                                    'ARE': 'United Arab Emirates',
                                    'IRN': 'Iran'
                                    // Add more as needed
                                };

                                // Convert country code to full name if available
                                const fullCountryName = countryCodeMap[testData.nationality] || testData.nationality;
                                console.log('Mapped country code', testData.nationality, 'to:', fullCountryName);

                                // Try multiple matching strategies
                                let found = false;

                                // First try: Exact match on full name
                                for (let i = 0; i < nationalityField.options.length; i++) {
                                    const option = nationalityField.options[i];
                                    if (option.text.toUpperCase() === fullCountryName.toUpperCase()) {
                                        nationalityField.selectedIndex = i;
                                        console.log('Found exact match for nationality:', option.text);
                                        found = true;
                                        break;
                                    }
                                }

                                // Second try: Contains match on full name
                                if (!found) {
                                    for (let i = 0; i < nationalityField.options.length; i++) {
                                        const option = nationalityField.options[i];
                                        if (option.text.toUpperCase().includes(fullCountryName.toUpperCase()) ||
                                            fullCountryName.toUpperCase().includes(option.text.toUpperCase())) {
                                            nationalityField.selectedIndex = i;
                                            console.log('Found partial match for nationality:', option.text);
                                            found = true;
                                            break;
                                        }
                                    }
                                }

                                // If found, trigger change event
                                if (found) {
                                    const event = new Event('change', {
                                        bubbles: true
                                    });
                                    nationalityField.dispatchEvent(event);
                                } else {
                                    console.log('Could not find matching nationality in dropdown');
                                }
                            }
                        }
                    });
                }

                if (fileInput) {
                    // Remove any existing event listeners
                    const newFileInput = fileInput.cloneNode(true);
                    fileInput.parentNode.replaceChild(newFileInput, fileInput);

                    // Add direct event listener for testing
                    newFileInput.addEventListener('change', function(e) {
                        console.log('Debug: File input change event triggered');
                        console.log('Debug: File selected:', this.files[0]);
                        console.log('Debug: File details:', {
                            name: this.files[0].name,
                            size: this.files[0].size,
                            type: this.files[0].type
                        });

                        // Show processing indicator
                        const processingIndicator = document.getElementById('passportProcessingIndicator');
                        if (processingIndicator) {
                            processingIndicator.style.display = 'inline-block';
                        }

                        // Create FormData
                        const formData = new FormData();
                        formData.append('basicInfoFile', this.files[0]);

                        // Log FormData contents
                        console.log('Debug: FormData contents:');
                        for (let pair of formData.entries()) {
                            console.log(pair[0] + ': ' + pair[1]);
                        }

                        // Send to processPassport.php
                        fetch('processPassport.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                console.log('Debug: Response status:', response.status);
                                console.log('Debug: Response headers:', Object.fromEntries(response.headers));
                                return response.text().then(text => {
                                    console.log('Debug: Raw response:', text);
                                    try {
                                        // Check if the response contains HTML tags (error messages)
                                        if (text.includes('<br />') || text.includes('<b>')) {
                                            console.error('Debug: Response contains HTML error messages:', text);
                                            // Extract the JSON part if possible
                                            const jsonStartPos = text.indexOf('{');
                                            const jsonEndPos = text.lastIndexOf('}') + 1;
                                            if (jsonStartPos !== -1 && jsonEndPos !== -1) {
                                                const jsonPart = text.substring(jsonStartPos, jsonEndPos);
                                                console.log('Debug: Extracted JSON part:', jsonPart);
                                                try {
                                                    return JSON.parse(jsonPart);
                                                } catch (e) {
                                                    console.error('Debug: Failed to parse extracted JSON:', e);
                                                }
                                            }

                                            // If JSON extraction failed, return a formatted error object
                                            return {
                                                success: false,
                                                data: {
                                                    extraction_error: 'Server encountered an error processing the image. Please try a different image file.'
                                                },
                                                error: 'PHP error occurred'
                                            };
                                        }

                                        return JSON.parse(text);
                                    } catch (e) {
                                        console.error('Debug: Failed to parse JSON:', e, text);
                                        throw new Error('Invalid JSON response');
                                    }
                                });
                            })
                            .then(data => {
                                console.log('Debug: Parsed response data:', data);
                                if (data.success) {
                                    console.log('Debug: Processing successful, data:', data.data);

                                    // Check if extraction error exists
                                    const extractionError = data.data && data.data.extraction_error;
                                    if (extractionError) {
                                        console.log('Debug: Extraction error detected:', extractionError);

                                        // Show extraction error container
                                        const errorContainer = document.getElementById('extractionErrorContainer');
                                        const errorMessage = document.getElementById('extractionErrorMessage');

                                        if (errorContainer && errorMessage) {
                                            errorMessage.textContent = extractionError;
                                            errorContainer.style.display = 'block';
                                        }

                                        return; // Don't try to populate fields with empty data
                                    }

                                    // Auto-fill form fields
                                    if (data.data) {
                                        const fields = {
                                            'passportNumber': data.data.passport_number || '',
                                            'passportExpiryDate': data.data.expiry_date || '',
                                            'dob': data.data.date_of_birth || data.data.dob || '',
                                            'gender': (data.data.gender || '').toLowerCase() === 'm' ? 'male' : (data.data.gender || '').toLowerCase() === 'f' ? 'female' : '',
                                            // Create full name by combining given_names and surname
                                            'passengerName': ((data.data.given_names || '') + ' ' + (data.data.surname || '')).trim() || data.data.name || ''
                                        };

                                        console.log('Debug: Mapped field values:', fields);

                                        // Check if only surname was detected (no given names)
                                        if (data.data.surname && !data.data.given_names) {
                                            console.log('Debug: Only surname detected, no given names');
                                            // Show a discreet notification near the name field
                                            setTimeout(() => {
                                                const nameField = document.getElementById('passengerName');
                                                if (nameField && nameField.parentNode) {
                                                    // Add a small hint below the field
                                                    const hintEl = document.createElement('small');
                                                    hintEl.className = 'text-warning';
                                                    hintEl.style.display = 'block';
                                                    hintEl.textContent = 'Only surname detected. Please add the first name if available.';

                                                    // Check if hint already exists
                                                    const existingHint = nameField.parentNode.querySelector('.text-warning');
                                                    if (!existingHint) {
                                                        nameField.parentNode.appendChild(hintEl);

                                                        // Focus the field to prompt the user to edit
                                                        nameField.focus();

                                                        // Set selection to beginning to make it easier to add first name
                                                        nameField.setSelectionRange(0, 0);

                                                        // Remove the hint after 10 seconds
                                                        setTimeout(() => {
                                                            if (hintEl.parentNode) {
                                                                hintEl.parentNode.removeChild(hintEl);
                                                            }
                                                        }, 10000);
                                                    }
                                                }
                                            }, 500);
                                        }

                                        for (const [id, value] of Object.entries(fields)) {
                                            const field = document.getElementById(id);
                                            if (field && value) {
                                                field.value = value;
                                                console.log('Debug: Set field', id, 'to', value);

                                                // Trigger change event
                                                const event = new Event('change', {
                                                    bubbles: true
                                                });
                                                field.dispatchEvent(event);
                                            } else {
                                                console.log('Debug: Field not found or no value:', id, field ? 'element exists' : 'element not found', value ? 'has value' : 'no value');
                                            }
                                        }

                                        // Handle nationality dropdown separately
                                        if (data.data.nationality || data.data.country_code) {
                                            const nationalityField = document.getElementById('nationality');
                                            if (nationalityField) {
                                                // Use nationality if available, otherwise fallback to country_code
                                                const nationalityValue = data.data.nationality || data.data.country_code;
                                                console.log('Looking for nationality match for:', nationalityValue);

                                                // Country code mapping to full names
                                                const countryCodeMap = {
                                                    'AFG': 'Afghanistan',
                                                    'USA': 'United States',
                                                    'GBR': 'United Kingdom',
                                                    'CAN': 'Canada',
                                                    'AUS': 'Australia',
                                                    'IND': 'India',
                                                    'PAK': 'Pakistan',
                                                    'ARE': 'United Arab Emirates',
                                                    'IRN': 'Iran',
                                                    'CHN': 'China',
                                                    'JPN': 'Japan',
                                                    'DEU': 'Germany',
                                                    'FRA': 'France',
                                                    'RUS': 'Russia',
                                                    'SAU': 'Saudi Arabia'
                                                    // Add more mappings as needed
                                                };

                                                // Convert country code to full name if available
                                                const countryCode = nationalityValue;
                                                const fullCountryName = countryCodeMap[countryCode] || countryCode;
                                                console.log('Mapped country code', countryCode, 'to:', fullCountryName);

                                                // Try to find the nationality in the dropdown
                                                let found = false;
                                                let selectedValue = null;

                                                // First try: Exact match on full name
                                                for (let i = 0; i < nationalityField.options.length; i++) {
                                                    const option = nationalityField.options[i];
                                                    if (option.text.toUpperCase() === fullCountryName.toUpperCase()) {
                                                        selectedValue = option.value;
                                                        console.log('Found exact match for nationality:', option.text, 'with value:', selectedValue);
                                                        found = true;
                                                        break;
                                                    }
                                                }

                                                // Second try: Contains match on full name
                                                if (!found) {
                                                    for (let i = 0; i < nationalityField.options.length; i++) {
                                                        const option = nationalityField.options[i];
                                                        if (option.text.toUpperCase().includes(fullCountryName.toUpperCase()) ||
                                                            fullCountryName.toUpperCase().includes(option.text.toUpperCase())) {
                                                            selectedValue = option.value;
                                                            console.log('Found partial match for nationality:', option.text, 'with value:', selectedValue);
                                                            found = true;
                                                            break;
                                                        }
                                                    }
                                                }

                                                // If found, use Select2 to update the selection
                                                if (found && selectedValue) {
                                                    console.log('Setting dropdown to value:', selectedValue);

                                                    // Use Select2's method to update the selection if available
                                                    if (typeof $ !== 'undefined' && $(nationalityField).data('select2')) {
                                                        console.log('Using Select2 API to update selection');
                                                        $(nationalityField).val(selectedValue).trigger('change');
                                                    } else {
                                                        console.log('Using standard DOM to update selection');
                                                        nationalityField.value = selectedValue;
                                                        const event = new Event('change', {
                                                            bubbles: true
                                                        });
                                                        nationalityField.dispatchEvent(event);
                                                    }
                                                } else {
                                                    console.log('Could not find matching nationality after all attempts, showing hint');

                                                    // Add a hint to manually select nationality
                                                    setTimeout(() => {
                                                        if (nationalityField && nationalityField.parentNode) {
                                                            // Add a small hint below the field
                                                            const hintEl = document.createElement('small');
                                                            hintEl.className = 'text-warning';
                                                            hintEl.style.display = 'block';
                                                            hintEl.textContent = `Please select nationality manually (detected code: ${countryCode})`;

                                                            // Check if hint already exists
                                                            const existingHint = nationalityField.parentNode.querySelector('.text-warning');
                                                            if (!existingHint) {
                                                                nationalityField.parentNode.appendChild(hintEl);

                                                                // Focus the field to prompt the user to edit
                                                                nationalityField.focus();

                                                                // Remove the hint after 10 seconds
                                                                setTimeout(() => {
                                                                    if (hintEl.parentNode) {
                                                                        hintEl.parentNode.removeChild(hintEl);
                                                                    }
                                                                }, 10000);
                                                            }
                                                        }
                                                    }, 700);
                                                }
                                            }
                                        }
                                    } else {
                                        console.log('Debug: No data in response');
                                    }
                                } else {
                                    console.error('Debug: Processing failed:', data.error || 'Unknown error');

                                    // Check if there's an extraction error in the response data
                                    const extractionError = data.data && data.data.extraction_error;
                                    if (extractionError) {
                                        console.log('Debug: Extraction error detected in failure response:', extractionError);

                                        // Show extraction error container
                                        const errorContainer = document.getElementById('extractionErrorContainer');
                                        const errorMessage = document.getElementById('extractionErrorMessage');

                                        if (errorContainer && errorMessage) {
                                            errorMessage.textContent = extractionError;
                                            errorContainer.style.display = 'block';
                                        }
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Debug: Error processing passport:', error);
                            })
                            .finally(() => {
                                // Hide processing indicator
                                if (processingIndicator) {
                                    processingIndicator.style.display = 'none';
                                }
                            });
                    });
                }
            });

            // Function to add a new customer
            function addCustomerFun() {
                var InsertCustomer = "InsertCustomer";
                var customerName = $('#customerName').val();
                if (customerName == "") {
                    notify('Validation Error!', 'Customer name is required', 'error');
                    return;
                }

                var customerRef = $('#customerRef').val();
                var customerPhone = $('#customerPhone').val();
                if (customerPhone == "") {
                    notify('Validation Error!', 'Phone number is required', 'error');
                    return;
                }

                var customerEmail = $('#customerEmail').val();
                var customerAddress = $('#customerAddress').val();

                // Disable the button to prevent multiple submissions
                $(".btn-info").prop("disabled", true);

                console.log("Submitting customer data:", {
                    name: customerName,
                    ref: customerRef,
                    phone: customerPhone,
                    email: customerEmail,
                    address: customerAddress
                });

                $.ajax({
                    type: "POST",
                    url: "residenceController.php",
                    data: {
                        InsertCustomer: InsertCustomer,
                        CustomerName: customerName,
                        CustomerRef: customerRef,
                        CustomerPhone: customerPhone,
                        CustomerEmail: customerEmail,
                        CustomerAddress: customerAddress
                    },
                    success: function(response) {
                        console.log("Server response:", response);
                        if (response == "Success" || response.includes("Success")) {
                            notify('Success!', "Customer added successfully", 'success');

                            // Reset form fields
                            $('#customerName').val('');
                            $('#customerPhone').val('');
                            $('#customerEmail').val('');
                            $('#customerAddress').val('');

                            // Close modal (try both Bootstrap 4 and 5 methods)
                            try {
                                if (typeof bootstrap !== 'undefined') {
                                    // Bootstrap 5
                                    var modalElement = document.getElementById('addCustomerModal');
                                    var modal = bootstrap.Modal.getInstance(modalElement);
                                    if (modal) {
                                        modal.hide();
                                    }
                                } else {
                                    // Bootstrap 4
                                    $('#addCustomerModal').modal('hide');
                                }
                            } catch (e) {
                                console.log("Error closing modal:", e);
                                // Fallback
                                $('#addCustomerModal').modal('hide');
                            }

                            // Refresh the customer dropdown
                            console.log("Refreshing customer dropdown");
                            getCustomer('all', null);
                        } else {
                            try {
                                // Try to parse as JSON in case it is returning in that format
                                const result = JSON.parse(response);
                                if (result.success) {
                                    notify('Success!', "Customer added successfully", 'success');

                                    // Close modal
                                    $('#addCustomerModal').modal('hide');

                                    // Refresh the customer dropdown
                                    getCustomer('all', null);
                                } else {
                                    notify('Error!', result.message || "Failed to add customer", 'error');
                                }
                            } catch (e) {
                                // If not JSON, display the raw response as error
                                notify('Error!', response, 'error');
                            }
                        }
                        // Re-enable the button
                        $(".btn-info").prop("disabled", false);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX error:", xhr.responseText);
                        notify('Error!', "Failed to communicate with the server: " + error, 'error');
                        // Re-enable the button
                        $(".btn-info").prop("disabled", false);
                    }
                });
            }

            // Initialize modal focus handling
            $('#addCustomerModal').on('shown.bs.modal', function() {
                $('#customerName').trigger('focus');
            });

            // Add this new function to handle modal display
            function showAddCustomerModal() {
                console.log("Add Customer button clicked");

                // Force using jQuery method regardless of Bootstrap version
                try {
                    // First try direct DOM selection with jQuery
                    var $modal = $('#addCustomerModal');

                    // Ensure modal is properly configured
                    $modal.attr('tabindex', '-1');
                    $modal.attr('role', 'dialog');
                    $modal.attr('aria-hidden', 'true');

                    // Ensure the modal is attached to body
                    if ($modal.parent().is('body') === false) {
                        $modal.appendTo('body');
                    }

                    // Try to display using jQuery
                    console.log("Forcing modal display with jQuery");
                    $modal.modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });

                    // Additionally trigger show as a fallback
                    $modal.modal('show');

                    // Force visibility as last resort
                    setTimeout(function() {
                        if (!$modal.hasClass('show')) {
                            console.log("Modal still not showing - forcing display");
                            $modal.addClass('show').css('display', 'block');
                            $('body').addClass('modal-open');
                            $('.modal-backdrop').remove();
                            $('<div class="modal-backdrop fade show"></div>').appendTo('body');
                        }
                    }, 300);
                } catch (error) {
                    console.error("Modal display error:", error);
                    alert("Could not open the Add Customer form. Please refresh and try again.");
                }
            }
        </script>
        </body>

        </html>