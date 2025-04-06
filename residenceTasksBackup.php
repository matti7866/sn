<?php include 'header.php' ?>  
<title>Residence Tasks</title>  
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
  
$stmp = $pdo->prepare("SELECT company_id, company_name FROM company ORDER BY company_name");  
$stmp->execute();  
$companies = $stmp->fetchAll(PDO::FETCH_OBJ);  
  
$stmt = $pdo->prepare("  
  SELECT COUNT(*) as count, completedStep  
  FROM residence  
  WHERE DATE(datetime) >= '{$dateAfter}'  
  GROUP BY completedStep  
  ");  
$stmt->execute();  
$result = $stmt->fetchAll(PDO::FETCH_OBJ);  
  
foreach ($result as $row) {  
  if (isset($steps[$row->completedStep])) {  
   $steps[$row->completedStep]['count'] = $row->count;  
  }  
}  
  
$stmt = $pdo->prepare("  
SELECT IFNULL(COUNT(*),0) as total  
FROM residence  
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 2 AND offerLetterStatus = 'submitted'  
");  
$stmt->execute();  
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;  
  
$steps['1a']['count'] = $offerLetterSubmitted;  
  
$stmt = $pdo->prepare("  
SELECT IFNULL(COUNT(*),0) as total  
FROM residence  
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 5 AND eVisaStatus = 'submitted'  
");  
$stmt->execute();  
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;  
  
$steps['4a']['count'] = $offerLetterSubmitted;  
  
$stmt = $pdo->prepare("  
SELECT IFNULL(COUNT(*),0) as total  
FROM residence  
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 2 AND offerLetterStatus = 'accepted'  
");  
$stmt->execute();  
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;  
  
$steps['2']['count'] = $offerLetterSubmitted;  
  
$stmt = $pdo->prepare("  
SELECT IFNULL(COUNT(*),0) as total  
FROM residence  
WHERE DATE(datetime) >= '{$dateAfter}' AND completedStep = 5 AND eVisaStatus = 'accepted'  
");  
$stmt->execute();  
$offerLetterSubmitted = $stmt->fetch(PDO::FETCH_OBJ)->total;  
  
$steps['5']['count'] = $offerLetterSubmitted;  
  
$where = '';  
if ($s == '1a') {  
  $where = " AND completedStep = 2 AND offerLetterStatus = 'submitted' ";  
} elseif ($s == '4a') {  
  $where = " AND completedStep = 5 AND eVisaStatus = 'submitted' ";  
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
  
$stmt = $pdo->prepare("  
  SELECT  
   residence.*,  
   residence.insideOutside,  
   customer.customer_name,  
   airports.countryName,  
   airports.countryCode,  
   company.company_name,  
   company.company_number,  
   company.username,  
   company.password,  
   country_name.country_names as visaType,  
   residence.mohreStatus,  
   residence.mohreStatusDatetime,  
   residence.mb_number,  
   residence.uid,  
   residence.LabourCardNumber,  
   residence.hold,  
   residence.sale_price as payment  
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
?>  
<div class="container-fluid">  
  <div class="row">  
   <div class="col-md-5 mb-2">  
    <h3>Residence Tasks</h3>  
   </div>  
  </div>  
  <div class="row">  
   <div class="col-md-12" id="message"></div>  
   <div class="col-md-12 mb-2">  
    <div class="btn-group btn-group-block">  
      <?php foreach ($steps as $key => $step): ?>  
       <a href="/residenceTasks.php?step=<?php echo $key ?>" data-step="<?= $key ?>" class="btn btn-white btn-block<?php echo (string)$key == $s ? ' active' : '' ?>">  
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
  <input type="search" id="search-input" placeholder="Search...">  
  <table id="datatable" width="100%" class="table table-striped table-bordered align-middle text-nowrap">  
          <thead>  
           <tr>  
            <th>ID</th>  
            <th>App. Date</th>  
            <th>Passenger Name</th>  
            <th>Customer</th>  
            <th>Payment</th>  
            <th>Passport</th>  
            <th>Passport Expiry</th>  
            <th>Action</th>  
           </tr>  
          </thead>  
          <tbody>  
           <?php  
           if (count($residences)) {  
            
foreach ($residences as $res) {  
  $actionButtons = '  
  ' . ($res->hold == 0 ? '<a target="_blank" href="/residence.php?id=' . $res->residenceID . '&stp=' . $s . '" class="btn btn-sm btn-primary">Continue</a>' : '') . '  
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
               $mohreStatus .= '</div>';  
              }  
  
              $companyAccess = '';  
              if ($currentStepInfo['showAccess']) {  
               $companyAccess .= $res->username != '' ? '<br /><strong>Username: </strong>' . $res->username : '';  
               $companyAccess .= $res->password != '' ? '<br /><strong>Password: </strong>' . $res->password : '';  
              }  
  
              $companyName = '';  
              if ($res->company_name != '') {  
               $companyName = "<strong>{$res->company_name}</strong>";  
               $companyName .= $res->company_number != '' ? ' - ' . $res->company_number : '';  
              }  
  
              $px = $res->passenger_name;  
  
              if ($res->LabourCardNumber != '' && $s == 9) {  
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
  
              echo '<tr data-hold="' . $res->hold . '">  
                <td>' . $res->residenceID . '</td>  
                <td>' . date('M d, Y', strtotime($res->datetime)) . '</td>  
                <td><img data-toggle="tooltip" data-placement="bottom" title="' . $res->countryName . '" height="12" class="me-2" src="https://flagpedia.net/data/flags/h24/' . strtolower($res->countryCode) . '.png" /> ' . $px . ($res->uid != '' ? '<br /><strong>UID: </strong>' . $res->uid : '') . '</td>  
                <td>' . $res->customer_name . '</td>  
                <td>' . $res->payment . '</td>  
                <td>' . $ppNumber . '</td>  
                <td>' . ($res->passportExpiryDate ? date('M d, Y', strtotime($res->passportExpiryDate)) : '') . '</td>  
                <td>' . $actionButtons . '</td>  
               </tr>';  
            }  
           }  
           ?>  
          </tbody>  
        </table>  
       </div>  
      </div>  
    </div>  
   </div>  
  
  </div>  
  
</div>  
  
<div class="modal fade" id="modalAttachments" role="dialog" aria-labelledby="" aria-hidden="true">  
  <div class="modal-dialog" role="document">  
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
  
<?php include 'footer.php' ?>  
<script type="text/javascript">  
  $(document).ready(function() {  
  var table = $('#datatable').DataTable({  
   responsive: false,  
   "paging": true,  
   "searching": true,  
   "pagingType": "full_numbers",  
   "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],  
   "search": {  
    "regex": true  
   }  
  });  
  
   $('#establishment').on('change', function() {  
    var company_id = $(this).val();  
    if (company_id == '') {  
      window.location == '/residenceTasks.php?step=<?php echo $s ?>';  
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
           $('#message').html('<div class="alert alert-danger">An error occured while setting offer letter status</div>');  
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
    })  
  
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
           $('#message').html('<div class="alert alert-danger">An error occured while setting eVisa status</div>');  
          },  
          success: function(res) {  
           if (res.status == '  if (res.status == 'success') {  
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
    })  
  
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
           $('#message').html('<div class="alert alert-danger">An error occured while setting hold status</div>');  
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
    })  
  
   })  
  
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
       $('#message').html('<div class="alert alert-danger">An error occured while loading attachments</div>');  
      },  
      success: function(res) {  
       if (res.status == 'success') {  
        $('#modalAttachmentsBody').html(res.html);  
       } else {  
        $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');  
       }  
       btn.removeAttr('disabled').html(btn.attr('data-temp'));  
      }  
    });  
  
    modal.modal('show');  
   })  
  
  });  
</script>