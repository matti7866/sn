<?php
   include 'header.php';
   include "connection.php";
   $sql = "SELECT role_name FROM `roles`  WHERE role_id = :role_id";
	 $stmt = $pdo->prepare($sql);
	 $stmt->bindParam(':role_id', $_SESSION['role_id']);
	 $stmt->execute();
	 $role_name = $stmt->fetchAll(\PDO::FETCH_ASSOC);
	 $role_name = $role_name[0]['role_name'];
   // permission for pending tasks
   $sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
   $stmt = $pdo->prepare($sql);
   $stmt->bindParam(':role_id', $_SESSION['role_id']);
   $stmt->execute();
   $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
   $PT_Select = $records[0]['select'];
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
   .apexcharts-tooltip{
     color:black;
   }
   .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active{
    
     
      background: linear-gradient(to bottom,#ff7471 0,#ff423e 100%);
       color:white;
   }
   .nav-tabs .nav-link:focus, .nav-tabs .nav-link:hover{
    color:white;
   }
   .table th {
      background-color: #ff423e !important;
      color: white;
  }
   .FineTableArea{
    margin-right:10px;
    margin-top:15px;
   }
   .iconArea{
    cursor:pointer;
   }   
   .chartBox {
      width: auto;
      height:400px;
      padding: 20px;
      border-radius: 20px;
   }
   #searchDropdown{
    position:absolute;
    z-index:100;
    width:31.3%;
    display:none;
    height: 150px;
    overflow:auto;
   }
   #searchDropdown ul li:hover{
    cursor: pointer;
    background-color: #ADD8E6;
    font-weight: bold;
   }
   
   /* Progress circle styling */
   .progress-container {
     padding: 10px;
     display: flex;
     justify-content: center;
     align-items: center;
   }
   
   .circle-progress {
     width: 80px !important;
     height: 80px !important;
     max-width: 80px;
     max-height: 80px;
     position: relative;
   }
   
   .circle-progress-value {
     stroke-width: 9px !important;
     stroke-linecap: round !important;
   }
   
   .circle-progress-circle {
     stroke-width: 2px !important;
   }
   
   .circle-progress-text {
     fill: white !important;
     font-size: 18px !important;
     font-weight: bold !important;
     dominant-baseline: middle !important;
     text-anchor: middle !important;
   }
</style>
<?php
  include 'nav.php';
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="panel text-white">
            <div class="panel-heading bg-inverse ">
                    <h4 class="panel-title"><i class="fa fa-info"></i> Residence <code> Report <i class="fa fa-arrow-down"></i></code></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="residence.php" onClick="resetForm()" class="btn btn-xs btn-icon btn-success" ><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger " data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
                    </div>
            </div>
            <div class="panel-body p-3 bg-gray-800">
                <div class="row mb-3">
                    <div class="col-12" >
                        <div class="card border-0 mb-3 bg-gray-800 text-white   " data-scrollbar="true" data-height="100%" style="height: 100%;" >
                            <div class="card-body" >
                                <div class="row align-items-center mb-3">  
                                  <div class="col-lg-8">
                                      <div class="input-group">
                                          <input type="text" onclick="getSearchRpt()" class="form-control" onkeyup="getSearchRpt()" placeholder="Customer,Passenger,Company name, Company Number" id="search"/>
                                          <a href="residence.php" class="btn btn-primary ms-2"><i class="fa fa-plus-circle me-1"></i> Add New Residence</a>
                                      </div>
                                      <div class="card mb-4 border-0" id="searchDropdown"></div>
                                  </div>
                                  <div class="col-lg-2 d-none" id="currencyAreaForRpt">
                                      <select class="form-control" onchange="getAbstrictView()" style="width:100%" name="residenceCurrency" id="residenceCurrency" spry:default="select one"></select>
                                  </div>
                                </div>
                                <hr />
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs " id="residenceTabs" >
                                      <li class="nav-item">
                                      <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active">All Residence Records</a>
                                      </li>
                                     </ul>
                                </div>
                                
                              <div class="tab-content bg-gray-800 panel p-3 rounded-0 rounded-bottom">
                                  <div class="tab-pane  fade active show bg-gray-800" id="default-tab-1">
                                      <hr />
                                      <!-- Begging of Accordin -->
                                      <div class="accordion" id="accordion">
                                          <div class="accordion-item border-0">
                                            <div class="accordion-header" id="headingOne">
                                              <button class="accordion-button bg-gray-900 text-white px-3 py-10px pointer-cursor" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                                <i class="fa fa-circle fa-fw text-blue me-2 fs-8px"></i> View Residence Outstaning
                                              </button>
                                            </div>
                                            <div id="collapseOne" class="accordion-collapse collapse " data-bs-parent="#accordion">
                                              <div class="accordion-body bg-gray-800 text-white">
                                                <div class="chartMenu">
                                                </div>
                                                <div class="chartCard">
                                                  <div class="chartBox">
                                                    <canvas id="myChart"></canvas>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      <!-- End of Accordin -->
                                      <hr />
                                      <div id="DailyRpt"></div>
                                      <div id="DailyRptPagination" class="d-flex justify-content-center m-3"></div>
                                  </div>
                              </div>
                              
                              
                              </div>
                          </div>
                      </div>
                  </div>
                  </div>
          </div>
      </div>
  </div>
</div>
<!-- Payment Modal -->
<div class="modal fade" id="myModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white bg-dark" >
        <h5 class="modal-title" id="exampleModalLongTitle">Residence Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form>
        <input type="hidden" id="resID">
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payable Amount</label>
            <div class="col-sm-8">
                <input type="text"  disabled="disabled" class="form-control" id="balance">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label text-danger"><i class="fa fa-fw fa-info"></i>Alert</label>
            <div class="col-sm-8">
              <p class="text-danger">Payment Currency will be taken automatically from sale price currency. if the sale price currency is
                AED then payment currency should also be AED. In case customer pays in dollar then convert that money to AED and write 
                remarks<p>

            </div>
        </div>
      
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payment Amount</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="pay" placeholder="Type amount here">

            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-comment"></i>Remarks</label>
            <div class="col-sm-8">
               <textarea class="form-control" rows="5" id="remarks"></textarea>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  js-example-basic-single" style="width:100%" name="account_id" id="account_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" onclick="makePay()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Extra Charges Modal -->
<div class="modal fade" id="fineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="ExChargeHeader">Add Residence Fine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="rID" name="rID">
          <div class="form-group row mb-2 fSection">
            <label for="inputPassword" class="col-sm-3 col-form-label">Fine Amount:</label>
            <div class="col-sm-9" >
              <input type="number" class="form-control" name="fine_amount" id="fine_amount" placeholder="Fine Amount">
            </div>
          </div>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9 accountArea">
              <select class="form-control  chargesSelect2" onchange="checkType()" style="width:100%" name="chargeAccount" id="chargeAccount"></select>
            </div>
            <div class="col-sm-3 currencyArea d-none">
                <select class="form-control chargesSelect2"   style="width:100%" id="fine_currency_type" name="fine_currency_type" spry:default="select one"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="saveResidenceFine()" class="btn btn-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- View Fine Charges Modal -->
<div class="modal fade" id="viewFineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel"><i class="fa fa-money-bill me-2"></i>Residence Fine Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body p-0">
        <!-- Summary Section -->
        <div class="bg-dark p-3 mb-3">
          <div class="row">
            <div class="col-md-12">
              <div class="alert bg-dark text-white border border-secondary mb-0" id="fineSummaryAlert">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <h6 class="mb-1 text-white">Outstanding Fine Balance</h6>
                    <div id="outstandingFineAmount"></div>
                  </div>
                  <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
                    <i class="fa fa-refresh me-1"></i> Refresh
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <form class="d-none" method="post" enctype="multipart/form-data" id="chargeUpload">
          <input type="file" name="Chargesuploader" id="Chargesuploader" />
          <input type="text" name="uploadChargesID" id="uploadChargesID" />
          <button type="submit" id="submitChargeUploadForm">Call</button>
        </form>
        
        <div class="table-responsive px-3">
          <table class="table table-bordered table-hover">
            <thead class="bg-primary text-white">
              <tr>
                <th width="5%">#</th>
                <th width="8%">Type</th>
                <th width="15%">Fine Amount</th>
                <th width="15%">Account</th>
                <th width="15%">Date</th>
                <th width="15%">Charged By</th>
                <th width="12%">Receipt</th>
                <th width="15%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody id="viewFineTable">
               <!-- Data will be loaded here -->
            </tbody>
          </table>
        </div> 
      </div>
      <div class="modal-footer bg-dark">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fa fa-times me-1"></i> Close
        </button>
        <button type="button" class="btn btn-primary" onclick="openResidenceFineDialog($('#uploadChargesID').val())">
          <i class="fa fa-plus me-1"></i> Add New Fine
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Extra Update Fine Modal -->
<div class="modal fade" id="updfineModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="ExChargeHeader">Update Residence Fine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="updrID" name="updrID">
          <div class="form-group row mb-2 fSection">
            <label for="inputPassword" class="col-sm-3 col-form-label">Fine Amount:</label>
            <div class="col-sm-9" >
              <input type="number" class="form-control" name="updfine_amount" id="updfine_amount" placeholder="Fine Amount">
            </div>
          </div>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9 updaccountArea">
              <select class="form-control  chargesSelecte3" onchange="updcheckType()" style="width:100%" name="updchargeAccount" id="updchargeAccount"></select>
            </div>
            <div class="col-sm-3 updcurrencyArea d-none">
                <select class="form-control chargesSelecte3"   style="width:100%" id="updfine_currency_type" name="updfine_currency_type" spry:default="select one"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updResidenceFine()" class="btn btn-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Payment For Fine Modal -->
<div class="modal fade" id="FinePaymentModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white bg-dark" >
        <h5 class="modal-title" id="exampleModalLongTitle">Residence Fine Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form>
        <input type="hidden" id="resFPaymentID">
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payable Amount</label>
            <div class="col-sm-8">
                <input type="text"  disabled="disabled" class="form-control" id="fine_balance">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label text-danger"><i class="fa fa-fw fa-info"></i>Alert</label>
            <div class="col-sm-8">
              <p class="text-danger">Payment Currency will be taken automatically from fine price currency. if the sale price currency is
                AED then payment currency should also be AED. In case customer pays in dollar then convert that money to AED and write 
                remarks<p>

            </div>
        </div>
      
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payment Amount</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="Fine_payAmount" placeholder="Type amount here">

            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-comment"></i>Remarks</label>
            <div class="col-sm-8">
               <textarea class="form-control" rows="5" id="fine_remarks"></textarea>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  js-example-basic-single" style="width:100%" name="fine_account_id" id="fine_account_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" onclick="saveFinePayment()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Payment History Modal -->
<div class="modal fade" id="paymentHistoryModal" tabindex="-1" role="dialog" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentHistoryModalLabel">Payment History</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Account</th>
                                <th>Payment Type</th>
                                <th>Staff Name</th>
                                <th>Remarks</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody id="paymentHistoryTableBody">
                            <!-- Payment history will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <?php
      include 'footer.php';
    ?>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script src="dashboardCustom.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./views/components/date/dateFormat.js"></script>
<script src="./views/components/select2/search.js"></script>
<script>
 $(document).ready(function () {
    // Initialize all residence records
    getPendingResidence();
    
    // Simple direct search handler
    $("#search").on("keyup", function() {
        // Reset to page 1
        if($('#currentPage').length) {
            $('#currentPage').val(1);
        }
        
        // Call search function
        getPendingResidence();
    });
    
    // Initialize select2 for dropdowns
    $("#account_id").select2({
      dropdownParent: $("#myModel")
    });
    
    // Initialize select2 for the charge dialog
    $('.chargesSelect2').select2({
       dropdownParent: $("#fineModal")
    });
    
    // Initialize select2 for the update dialog
    $('.chargesSelecte3').select2({
       dropdownParent: $("#updfineModal")
    });
    
    // Initialize select2 for fine payment
    $('#fine_account_id').select2({
       dropdownParent: $("#FinePaymentModel")
    });

    showTotalFineView();
});

// search 
function getSearchRpt(){
    // Reset to page 1 for new searches
    if($('#currentPage').length){
        $('#currentPage').val(1);
    }
    
    // Call main function to get all residence records
    getPendingResidence();
}

function getPendingResidence(){
  var getPendingResidence = "getPendingResidence";
  var search = $('#search').val();
  var page = 1;
  
  if($('#currentPage').length){
    page = $('#currentPage').val();
  }
  
  console.log("getPendingResidence called with search: '" + search + "', page: " + page);
  
  // Create data object carefully - search can be null/undefined/empty
  var data = {
    GetPendingResidence: getPendingResidence,
    Page: page
  };
  
  // Only add Search parameter if it's not empty
  if (search && search.trim() !== '') {
    data.Search = search.trim();
  } else {
    data.Search = '';  // Explicitly send empty string
  }
  
  console.log("Sending to server:", data);
  
  $.ajax({
    type: "POST",
    url: "residenceReportController.php",  
    data: data,
    success: function (response) { 
        console.log("Response received for residence records");
        
        // Log raw response for debugging
        if (typeof response === 'string' && response.length < 1000) {
          console.log("Raw response:", response);
        } else {
          console.log("Raw response is too large to log completely");
        }
        
        try {
          // Check if response is empty
          if (!response || response.trim() === '') {
            console.error("Empty response received");
            $('#DailyRpt').empty().append('<div class="row"><h1 class="text-center text-danger">Empty response from server</h1></div>');
            $('#DailyRptPagination').empty();
            return;
          }
          
          var pendingResidenceRpt = JSON.parse(response);
          
          // Check for error response from server
          if (pendingResidenceRpt.error === true) {
            console.error("Server returned error:", pendingResidenceRpt.message);
            $('#DailyRpt').empty().append('<div class="row"><h1 class="text-center text-danger">Server Error: ' + pendingResidenceRpt.message + '</h1></div>');
            $('#DailyRptPagination').empty();
            return;
          }
          
          var dailyrpt = $('#DailyRpt');
        
          // Handle response format
          var dataArray = [];
          var totalRecords = 0;
          
          if (pendingResidenceRpt.records) {
            // New format with pagination
            dataArray = pendingResidenceRpt.records;
            totalRecords = pendingResidenceRpt.totalRecords;
            console.log("Format: " + dataArray.length + " records of " + totalRecords + " total");
          } else {
            // Old format
            dataArray = pendingResidenceRpt;
            console.log("Old format: " + dataArray.length + " records");
          }
          
          if (dataArray.length == 0) {
            dailyrpt.empty();
            dailyrpt.append('<div class="row"><h1 class="text-center">No Residence Records Found...</h1></div>');
            $('#DailyRptPagination').empty();
          } else {
            dailyrpt.empty();
            var dailyrptTable = '';
            var j = 1;
            
            for (var i = 0; i < dataArray.length; i++) {
              // Determine record status based on completedStep
              var statusClass = "";
              var statusText = "";
              
              if (dataArray[i].completedStep == 10) {
                statusClass = "bg-success"; // Green for completed
                statusText = "Completed";
              } else if (dataArray[i].total >= dataArray[i].sale_price) {
                statusClass = "bg-warning"; // Yellow for pending processing with payment complete
                statusText = "Pending Processing (Payment Complete)";
              } else {
                statusClass = "bg-danger"; // Red for pending payment
                statusText = "Pending Payment";
              }
              
              dailyrptTable += '<div class="row mb-3">';
              dailyrptTable += '<div class="col-12">';
              
              // New structured information display with proper formatting - full width card
              dailyrptTable += '<div class="card border-0 bg-gray-700 mb-2">';
              dailyrptTable += '<div class="card-body p-3">';
              
              // Header with name and progress indicator
              dailyrptTable += '<div class="row mb-3 align-items-center">';
              dailyrptTable += '<div class="col-md-8">';
              dailyrptTable += '<h2 class="mb-2"><i>' + dataArray[i].passenger_name + '</i></h2>';
              dailyrptTable += '<div class="badge ' + statusClass + ' mb-2">' + statusText + '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '<div class="col-md-4 text-end">';
              dailyrptTable += '<div class="d-flex justify-content-end align-items-center">';
              dailyrptTable += '<div class="text-white-50 me-3">Completion: </div>';
              dailyrptTable += '<div class="progress-container p-2" style="min-width:100px;">';
              dailyrptTable += '<div class="progress" id="pendingCircle'+ j +'" style="height:80px;width:80px;background-color:#2d353c;font-size:15px;margin:auto;"></div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              
              // Main content area
              dailyrptTable += '<div class="row">';
              
              // Left side - Customer and Company info
              dailyrptTable += '<div class="col-md-8">';
              
              // Customer section
              dailyrptTable += '<div class="row mb-3 border-bottom pb-2">';
              dailyrptTable += '<div class="col-md-12">';
              dailyrptTable += '<h6 class="text-white-50 mb-1 small">CUSTOMER INFORMATION</h6>';
              dailyrptTable += '<div class="row">';
              dailyrptTable += '<div class="col-md-6"><strong class="text-white">Customer Name:</strong> <span class="text-light">' + dataArray[i].customer_name + '</span></div>';
              dailyrptTable += '<div class="col-md-6"><strong class="text-white">Nationality:</strong> <span class="text-light">' + dataArray[i].countryName + '</span></div>';
              dailyrptTable += '</div>';
              dailyrptTable += '<div class="row mt-1">';
              dailyrptTable += '<div class="col-md-12"><strong class="text-white">Residence Type:</strong> <span class="text-light">' + dataArray[i].country_names + '</span></div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              
              // Company section
              dailyrptTable += '<div class="row mb-3 border-bottom pb-2">';
              dailyrptTable += '<div class="col-md-12">';
              dailyrptTable += '<h6 class="text-white-50 mb-1 small">COMPANY INFORMATION</h6>';
              dailyrptTable += '<div class="row">';
              dailyrptTable += '<div class="col-md-6"><strong class="text-white">Company Name:</strong> <span class="text-light">' + dataArray[i].company_name + '</span></div>';
              dailyrptTable += '<div class="col-md-6"><strong class="text-white">Company Number:</strong> <span class="text-light">' + dataArray[i].company_number + '</span></div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              
              dailyrptTable += '</div>'; // End left column
              
              // Right side - Financial summary
              dailyrptTable += '<div class="col-md-4 border-start border-dark">';
              
              // Financial section
              dailyrptTable += '<div class="row">';
              dailyrptTable += '<div class="col-md-12">';
              dailyrptTable += '<h6 class="text-white-50 mb-1 small">FINANCIAL SUMMARY</h6>';
              
              // Sale price - always show this
              dailyrptTable += '<div class="mb-2"><strong class="text-white">Sale Price:</strong> <span class="text-info">' + numeral(dataArray[i].sale_price).format('0,0') + ' ' + dataArray[i].currencyName + '</span></div>';
              
              // Add fine information if it exists
              if(dataArray[i].total_Fine != 0){
                dailyrptTable += '<div class="mb-2"><strong class="text-white">Total Fine:</strong> <span class="text-info">' + numeral(dataArray[i].total_Fine).format('0,0') + ' ' + dataArray[i].residenceFineCurrency + '</span></div>';
                dailyrptTable += '<div class="mb-2"><strong class="text-white">Total Fine Paid:</strong> <span class="text-info">' + numeral(dataArray[i].totalFinePaid).format('0,0') + ' ' + dataArray[i].residenceFineCurrency + '</span></div>';
              }
              
              // Total paid - always show this
              dailyrptTable += '<div class="mb-2"><strong class="text-white">Total Paid:</strong> <span class="text-info">' + numeral(dataArray[i].total).format('0,0') + ' ' + dataArray[i].currencyName + '</span></div>';
              
              // Calculate total remaining
              let totalRemaining = parseFloat(dataArray[i].sale_price) - parseFloat(dataArray[i].total);
              
              // Add fine remaining if currencies match
              if(dataArray[i].total_Fine != 0 && dataArray[i].currencyName === dataArray[i].residenceFineCurrency) {
                totalRemaining += (parseFloat(dataArray[i].total_Fine) - parseFloat(dataArray[i].totalFinePaid));
              }
              
              // Total remaining - always show this
              dailyrptTable += '<div class="mb-2"><strong class="text-white font-weight-bold">Total Remaining:</strong> <span class="text-danger font-weight-bold">' + numeral(totalRemaining).format('0,0') + ' ' + dataArray[i].currencyName + '</span></div>';
              
              // Continue/View button
              dailyrptTable += '<div class="mt-3">';
              dailyrptTable += '<a href="residence.php?id=' + dataArray[i].main_residenceID + '&stp=' + dataArray[i].completedStep + '" class="btn btn-info w-100" type="button"><i class="fa fa-' + (dataArray[i].completedStep == 10 ? 'eye' : 'arrow-right') + '"></i> ' + (dataArray[i].completedStep == 10 ? 'View' : 'Continue') + '</a>';
              dailyrptTable += '</div>';
              
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>'; // End right column
              
              dailyrptTable += '</div>'; // End main content row
              
              // Action buttons row
              dailyrptTable += '<div class="row mt-3">';
              dailyrptTable += '<div class="col-12">';
              dailyrptTable += '<div class="d-flex flex-wrap gap-2">';
              dailyrptTable += '<button class="btn btn-danger" type="button" onclick="getPendingPayment('+dataArray[i].main_residenceID+')"><i class="fa fa-cc-paypal"></i> Make Payment</button>';
              dailyrptTable += '<button class="btn btn-warning" type="button" onclick="viewFine('+dataArray[i].main_residenceID+')"><i class="fa fa-money"></i> Pay Fine</button>';
              dailyrptTable += '<button class="btn btn-info" type="button" onclick="viewPaymentHistory('+dataArray[i].main_residenceID+')"><i class="fa fa-history"></i> Payment History</button>';
              dailyrptTable += '<a href="printLetter.php?id='+dataArray[i].main_residenceID+'&type=noc" class="btn btn-primary" type="button"><i class="fa fa-file-text"></i> NOC</a>';
              dailyrptTable += '<button class="btn btn-success" type="button" onclick="openSalaryCertificateDialog('+dataArray[i].main_residenceID+')"><i class="fa fa-file-text"></i> Salary Certificate</button>';
              dailyrptTable += '<div class="btn-group">';
              dailyrptTable += '<button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
              dailyrptTable += 'More <i class="fa fa-caret-down" aria-hidden="true"></i></button>';
              dailyrptTable += '<ul class="dropdown-menu">';
              dailyrptTable += '<li><button class="dropdown-item" type="button" onclick="openResidenceFineDialog('+dataArray[i].main_residenceID+')"><i class="fa fa-plus"></i> Add Fine</button></li>';
              dailyrptTable += '<li><button class="dropdown-item" type="button" onclick="viewFine('+dataArray[i].main_residenceID+')"><i class="fa fa-eye"></i> View Fine</button></li>';
              dailyrptTable += '<li><button class="dropdown-item" type="button" onclick="deleteResidence('+dataArray[i].main_residenceID+')"><i class="fa fa-trash"></i> Delete Residence</button></li>';
              dailyrptTable += '</ul>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              dailyrptTable += '</div>';
              
              dailyrptTable += '</div>'; // End card-body
              dailyrptTable += '</div>'; // End card
              
              dailyrptTable += '</div>'; // End col-12
              dailyrptTable += '</div>'; // End row
              dailyrptTable += '<hr class="reportLineBreaker" />';
              j++;
            }
            
            dailyrpt.append(dailyrptTable);
            var pendingCircleCounter = 1;
            for(var c = 0; c < dataArray.length; c++){
              $('#pendingCircle'+pendingCircleCounter).circleProgress({
                max: 10,
                value: dataArray[c].completedStep,
                textFormat: 'percent',
              });
              pendingCircleCounter++;
            }
            setCircleAttributes('#ff423e');
            
            // Create pagination if we have the pagination info
            if(totalRecords > 0) {
              createPagination(totalRecords, page, 'DailyRptPagination', 'getPendingResidence');
            } else {
              $('#DailyRptPagination').empty();
            }
          }
        } catch (error) {
          console.error("Error handling response:", error);
          console.log("Raw response:", response);
          $('#DailyRpt').empty().append('<div class="row"><h1 class="text-center text-danger">Error loading data: ' + error.message + '</h1></div>');
          $('#DailyRptPagination').empty();
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("AJAX error:", textStatus, errorThrown);
      $('#DailyRpt').empty().append('<div class="row"><h1 class="text-center text-danger">Error communicating with server: ' + textStatus + '</h1></div>');
      $('#DailyRptPagination').empty();
    }
  });
}

// Add pagination function after existing functions
function createPagination(totalRecords, currentPage, paginationDivId, callbackFunction) {
  var recordsPerPage = 10;
  var totalPages = Math.ceil(totalRecords / recordsPerPage);
  var paginationHtml = '';
  
  if (totalPages > 1) {
    paginationHtml += '<input type="hidden" id="currentPage" value="' + currentPage + '">';
    paginationHtml += '<nav aria-label="Page navigation"><ul class="pagination">';
    
    // Previous button
    if (currentPage > 1) {
      paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="changePage(' + (parseInt(currentPage) - 1) + ', \'' + callbackFunction + '\')">Previous</a></li>';
    } else {
      paginationHtml += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Previous</a></li>';
    }
    
    // Page numbers
    var startPage = Math.max(1, currentPage - 2);
    var endPage = Math.min(totalPages, startPage + 4);
    
    if (startPage > 1) {
      paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="changePage(1, \'' + callbackFunction + '\')">1</a></li>';
      if (startPage > 2) {
        paginationHtml += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">...</a></li>';
      }
    }
    
    for (var i = startPage; i <= endPage; i++) {
      if (i == currentPage) {
        paginationHtml += '<li class="page-item active"><a class="page-link" href="javascript:void(0)">' + i + '</a></li>';
      } else {
        paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="changePage(' + i + ', \'' + callbackFunction + '\')">' + i + '</a></li>';
      }
    }
    
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        paginationHtml += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">...</a></li>';
      }
      paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="changePage(' + totalPages + ', \'' + callbackFunction + '\')">' + totalPages + '</a></li>';
    }
    
    // Next button
    if (currentPage < totalPages) {
      paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" onclick="changePage(' + (parseInt(currentPage) + 1) + ', \'' + callbackFunction + '\')">Next</a></li>';
    } else {
      paginationHtml += '<li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>';
    }
    
    paginationHtml += '</ul></nav>';
  }
  
  $('#' + paginationDivId).html(paginationHtml);
}

function changePage(page, callbackFunction) {
  $('#currentPage').val(page);
  if(callbackFunction === 'getPendingResidence') {
    getPendingResidence();
  } else if(callbackFunction === 'getPendingPayForResidence') {
    getPendingPayForResidence();
  } else if(callbackFunction === 'getCompletedResidence') {
    getCompletedResidence();
  }
}
function setCircleAttributes(color){
    $('.circle-progress-value').css({'stroke-width': '9px','stroke': color,'stroke-linecap': 'round'});
    $('.circle-progress-circle').css({'stroke-width': '2px'});
    $('.circle-progress-text').css({'fill':'white', 'font-size': '18px', 'font-weight': 'bold'});
    $('.circle-progress').css({'width': '80px', 'height': '80px'});
}
function getPendingPayment(id){
  event.preventDefault();
  $('#resID').val(id);
  $('#myModel').modal('show');
  getAccounts("forPendingPayment",null);
  var getPendingResidencePayment = 'getPendingResidencePayment';
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetPendingResidencePayment:getPendingResidencePayment,
            ID:id
        },
        success: function (response) {  
           var resPendPayment = JSON.parse(response);
          $('#balance').val(numeral(resPendPayment[0].remaining).format('0,0') + ' ' + resPendPayment[0].currencyName);
        },
    });
}
function makePay(){
    var insert_payment ="INSERT_Payment";
    var payment = $('#pay');
    if(payment.val() == ""){
        notify('Validation Error!', 'payment is required', 'error');
        return;
    }
    var remarks= $('#remarks');
    var account_id = $('#account_id');
    if(account_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var resID = $('#resID');
    if(resID.val() == "-1"){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
                Insert_Payment:insert_payment,
                Payment : payment.val(),
                Remarks: remarks.val(),
                Account_ID:account_id.val(),
                ResID:resID.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#myModel').modal('hide');
                    if($("#residenceTabs li a.active").attr('href') == "#default-tab-1"){
                      getPendingResidence();
                    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-2"){
                      getPendingPayForResidence();
                    }
                    payment.val('');
                    resID.val('');
                    $('#remarks').val('');
                    showTotalFineView();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
}
function getAccounts(type,id){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
            if(type =="forPendingPayment"){
              $('#account_id').empty();
              $('#account_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#account_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }else if(type == "forFine"){
              $('#chargeAccount').empty();
              $('#chargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#chargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
              checkType();
            }else if(type == "byUpdateWithoutCash"){
              $('#updchargeAccount').empty();
              $('#updchargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updchargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
              updcheckType();
            }else if(type == "byUpdate"){
              $('#updchargeAccount').empty();
              $('#updchargeAccount').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updchargeAccount').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }else if(type == "forFinePayment"){
              $('#fine_account_id').empty();
              $('#fine_account_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                $('#fine_account_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }
            
              
        },
    });
}
function getPendingPayForResidence(){
  var page = parseInt($('#PendingPaymentPagination').pagination('getCurrentPage'));
  var getPendingPayForResidence = "getPendingPayForResidence";
  var search = $('#search').val();
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetPendingPayForResidence:getPendingPayForResidence,
          Search:search,
          Page:page
        },
        success: function (response) { 
          var pendingPaymentResidenceRpt = JSON.parse(response);
            var PendingPayment = $('#PendingPayment');
            if(pendingPaymentResidenceRpt.length == 0){
              PendingPayment.empty();
              PendingPayment.append('<div class="row"><h1 class="text-center">No Residence Pending Payment Report...</h1></div>');
              $('#PendingPaymentPagination').pagination('updateItems', 1);
            }else{
              PendingPayment.empty();
              var pendingPaymentTable = '';
              var j = 1;
              for(var i =0; i<pendingPaymentResidenceRpt.length; i++){
                pendingPaymentTable += '<div class="row"><div class="col-12">';
                
                // New structured information display with proper formatting - full width card
                pendingPaymentTable += '<div class="card border-0 bg-gray-700 mb-2">';
                pendingPaymentTable += '<div class="card-body p-3">';
                
                // Header with name and progress indicator
                pendingPaymentTable += '<div class="row mb-3 align-items-center">';
                pendingPaymentTable += '<div class="col-md-8">';
                pendingPaymentTable += '<h2 class="mb-2"><i>' + pendingPaymentResidenceRpt[i].passenger_name + '</i></h2>';
                pendingPaymentTable += '<div class="badge bg-warning text-dark mb-2">Payment Pending</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '<div class="col-md-4 text-end">';
                pendingPaymentTable += '<div class="d-flex justify-content-end align-items-center">';
                pendingPaymentTable += '<div class="text-white-50 me-3">Completion: </div>';
                pendingPaymentTable += '<div class="progress-container p-2" style="min-width:100px;">';
                pendingPaymentTable += '<div class="progress" id="pendingPaymentCircle'+ j +'" style="height:80px;width:80px;background-color:#2d353c;font-size:15px;margin:auto;"></div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                
                // Main content area
                pendingPaymentTable += '<div class="row">';
                
                // Left side - Customer and Company info
                pendingPaymentTable += '<div class="col-md-8">';
                
                // Customer section
                pendingPaymentTable += '<div class="row mb-3 border-bottom pb-2">';
                pendingPaymentTable += '<div class="col-md-12">';
                pendingPaymentTable += '<h6 class="text-white-50 mb-1 small">CUSTOMER INFORMATION</h6>';
                pendingPaymentTable += '<div class="row">';
                pendingPaymentTable += '<div class="col-md-6"><strong class="text-white">Customer Name:</strong> <span class="text-light">' + pendingPaymentResidenceRpt[i].customer_name + '</span></div>';
                pendingPaymentTable += '<div class="col-md-6"><strong class="text-white">Nationality:</strong> <span class="text-light">' + pendingPaymentResidenceRpt[i].countryName + '</span></div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '<div class="row mt-1">';
                pendingPaymentTable += '<div class="col-md-12"><strong class="text-white">Residence Type:</strong> <span class="text-light">' + pendingPaymentResidenceRpt[i].country_names + '</span></div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                
                // Company section
                pendingPaymentTable += '<div class="row mb-3 border-bottom pb-2">';
                pendingPaymentTable += '<div class="col-md-12">';
                pendingPaymentTable += '<h6 class="text-white-50 mb-1 small">COMPANY INFORMATION</h6>';
                pendingPaymentTable += '<div class="row">';
                pendingPaymentTable += '<div class="col-md-6"><strong class="text-white">Company Name:</strong> <span class="text-light">' + pendingPaymentResidenceRpt[i].company_name + '</span></div>';
                pendingPaymentTable += '<div class="col-md-6"><strong class="text-white">Company Number:</strong> <span class="text-light">' + pendingPaymentResidenceRpt[i].company_number + '</span></div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                
                pendingPaymentTable += '</div>'; // End left column
                
                // Right side - Financial summary
                pendingPaymentTable += '<div class="col-md-4 border-start border-dark">';
                
                // Financial section
                pendingPaymentTable += '<div class="row">';
                pendingPaymentTable += '<div class="col-md-12">';
                pendingPaymentTable += '<h6 class="text-white-50 mb-1 small">FINANCIAL SUMMARY</h6>';
                
                // Sale price - always show this
                pendingPaymentTable += '<div class="mb-2"><strong class="text-white">Sale Price:</strong> <span class="text-info">' + numeral(pendingPaymentResidenceRpt[i].sale_price).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].currencyName + '</span></div>';
                
                // Add fine information if it exists
                if(pendingPaymentResidenceRpt[i].total_Fine != 0){
                  pendingPaymentTable += '<div class="mb-2"><strong class="text-white">Total Fine:</strong> <span class="text-info">' + numeral(pendingPaymentResidenceRpt[i].total_Fine).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].residenceFineCurrency + '</span></div>';
                  pendingPaymentTable += '<div class="mb-2"><strong class="text-white">Total Fine Paid:</strong> <span class="text-info">' + numeral(pendingPaymentResidenceRpt[i].totalFinePaid).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].residenceFineCurrency + '</span></div>';
                }
                
                // Total paid - always show this
                pendingPaymentTable += '<div class="mb-2"><strong class="text-white">Total Paid:</strong> <span class="text-info">' + numeral(pendingPaymentResidenceRpt[i].total).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].currencyName + '</span></div>';
                
                // Calculate total remaining
                let totalRemaining = parseFloat(pendingPaymentResidenceRpt[i].sale_price) - parseFloat(pendingPaymentResidenceRpt[i].total);
                
                // Add fine remaining if currencies match
                if(pendingPaymentResidenceRpt[i].total_Fine != 0 && pendingPaymentResidenceRpt[i].currencyName === pendingPaymentResidenceRpt[i].residenceFineCurrency) {
                  totalRemaining += (parseFloat(pendingPaymentResidenceRpt[i].total_Fine) - parseFloat(pendingPaymentResidenceRpt[i].totalFinePaid));
                }
                
                // Total remaining - always show this
                pendingPaymentTable += '<div class="mb-2"><strong class="text-white font-weight-bold">Total Remaining:</strong> <span class="text-danger font-weight-bold">' + numeral(totalRemaining).format('0,0') + ' ' + pendingPaymentResidenceRpt[i].currencyName + '</span></div>';
                
                // View button
                pendingPaymentTable += '<div class="mt-3">';
                pendingPaymentTable += '<a href="residence.php?id=' + pendingPaymentResidenceRpt[i].main_residenceID + '&stp=' + pendingPaymentResidenceRpt[i].completedStep + '" class="btn btn-info w-100" type="button"><i class="fa fa-eye"></i> View</a>';
                pendingPaymentTable += '</div>';

                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>'; // End right column
                
                pendingPaymentTable += '</div>'; // End main content row
                
                // Action buttons row
                pendingPaymentTable += '<div class="row mt-3">';
                pendingPaymentTable += '<div class="col-12">';
                pendingPaymentTable += '<div class="d-flex flex-wrap gap-2">';
                pendingPaymentTable += '<button class="btn btn-danger" type="button" onclick="getPendingPayment('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-cc-paypal"></i> Make Payment</button>';
                pendingPaymentTable += '<button class="btn btn-warning" type="button" onclick="viewFine('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-money"></i> Pay Fine</button>';
                pendingPaymentTable += '<button class="btn btn-info" type="button" onclick="viewPaymentHistory('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-history"></i> Payment History</button>';
                pendingPaymentTable += '<div class="btn-group">';
                pendingPaymentTable += '<button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
                pendingPaymentTable += 'More <i class="fa fa-caret-down" aria-hidden="true"></i></button>';
                pendingPaymentTable += '<ul class="dropdown-menu">';
                pendingPaymentTable += '<li><button class="dropdown-item" type="button" onclick="openResidenceFineDialog('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-plus"></i> Add Fine</button></li>';
                pendingPaymentTable += '<li><button class="dropdown-item" type="button" onclick="viewFine('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-eye"></i> View Fine</button></li>';
                pendingPaymentTable += '<li><button class="dropdown-item" type="button" onclick="deleteResidence('+pendingPaymentResidenceRpt[i].main_residenceID+')"><i class="fa fa-trash"></i> Delete Residence</button></li>';
                pendingPaymentTable += '</ul>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                pendingPaymentTable += '</div>';
                
                pendingPaymentTable += '</div>'; // End card-body
                pendingPaymentTable += '</div>'; // End card
                
                pendingPaymentTable += '</div></div>'; // End col and row
                pendingPaymentTable += '<hr class="reportLineBreaker" />';
                j++;
              }
             
              PendingPayment.append(pendingPaymentTable);
              var pendingPaymentCircleCounter = 1;
              for(var c = 0; c<pendingPaymentResidenceRpt.length;c++){
                $('#pendingPaymentCircle'+pendingPaymentCircleCounter).circleProgress({
	                max: 10,
	                value:pendingPaymentResidenceRpt[c].completedStep,
	                textFormat: 'percent',
                });
                pendingPaymentCircleCounter++;
              }
              setCircleAttributes('#f59c1a');
              $('#PendingPaymentPagination').pagination('updateItems', pendingPaymentResidenceRpt[0].totalRow);
              
             
            }
            
        },
    });

}
 $('#PendingPaymentPagination').on('click', function(){
    getPendingPayForResidence();
 });
 $('#completedResidencePagination').on('click', function(){
  getCompletedResidence();
 });
 function getCompletedResidence(){
  var page = parseInt($('#completedResidencePagination').pagination('getCurrentPage'));
  var getCompletedResidence = "getCompletedResidence";
  var search = $('#search').val();
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetCompletedResidence:getCompletedResidence,
          Search:search,
          Page:page
        },
        success: function (response) { 
          var compeletedResidenceRpt = JSON.parse(response);
            var completedResidence = $('#completedResidence');
            if(compeletedResidenceRpt.length == 0){
              completedResidence.empty();
              completedResidence.append('<div class="row"><h1 class="text-center">No Completed Residence Report...</h1></div>');
              $('#completedResidencePagination').pagination('updateItems', 1);
            }else{
              completedResidence.empty();
              var completeTable = '';
              var j = 1;
              for(var i =0; i<compeletedResidenceRpt.length; i++){
                completeTable += '<div class="row"><div class="col-12">';
                
                // New structured information display with proper formatting - full width card
                completeTable += '<div class="card border-0 bg-gray-700 mb-2">';
                completeTable += '<div class="card-body p-3">';
                
                // Header with name and progress indicator
                completeTable += '<div class="row mb-3 align-items-center">';
                completeTable += '<div class="col-md-8">';
                completeTable += '<h2 class="mb-2"><i>' + compeletedResidenceRpt[i].passenger_name + '</i></h2>';
                completeTable += '<div class="badge bg-success mb-2">Completed</div>';
                completeTable += '</div>';
                completeTable += '<div class="col-md-4 text-end">';
                completeTable += '<div class="d-flex justify-content-end align-items-center">';
                completeTable += '<div class="text-white-50 me-3">Completion: </div>';
                completeTable += '<div class="progress-container p-2" style="min-width:100px;">';
                completeTable += '<div class="progress" id="completedCircle'+ j +'" style="height:80px;width:80px;background-color:#2d353c;font-size:15px;margin:auto;"></div>';
                completeTable += '</div>';
                completeTable += '</div>';
                completeTable += '</div>';
                
                // Main content area
                completeTable += '<div class="row">';
                
                // Left side - Customer and Company info
                completeTable += '<div class="col-md-8">';
                
                // Customer section
                completeTable += '<div class="row mb-3 border-bottom pb-2">';
                completeTable += '<div class="col-md-12">';
                completeTable += '<h6 class="text-white-50 mb-1 small">CUSTOMER INFORMATION</h6>';
                completeTable += '<div class="row">';
                completeTable += '<div class="col-md-6"><strong class="text-white">Customer Name:</strong> <span class="text-light">' + compeletedResidenceRpt[i].customer_name + '</span></div>';
                completeTable += '<div class="col-md-6"><strong class="text-white">Nationality:</strong> <span class="text-light">' + compeletedResidenceRpt[i].countryName + '</span></div>';
                completeTable += '</div>';
                completeTable += '<div class="row mt-1">';
                completeTable += '<div class="col-md-12"><strong class="text-white">Residence Type:</strong> <span class="text-light">' + compeletedResidenceRpt[i].country_names + '</span></div>';
                completeTable += '</div>';
                completeTable += '</div>';
                completeTable += '</div>';
                
                // Company section
                completeTable += '<div class="row mb-3 border-bottom pb-2">';
                completeTable += '<div class="col-md-12">';
                completeTable += '<h6 class="text-white-50 mb-1 small">COMPANY INFORMATION</h6>';
                completeTable += '<div class="row">';
                completeTable += '<div class="col-md-6"><strong class="text-white">Company Name:</strong> <span class="text-light">' + compeletedResidenceRpt[i].company_name + '</span></div>';
                completeTable += '<div class="col-md-6"><strong class="text-white">Company Number:</strong> <span class="text-light">' + compeletedResidenceRpt[i].company_number + '</span></div>';
                completeTable += '</div>';
                completeTable += '</div>';
                completeTable += '</div>';
                
                completeTable += '</div>'; // End left column
                
                // Right side - Financial summary
                completeTable += '<div class="col-md-4 border-start border-dark">';
                
                // Financial section
                completeTable += '<div class="row">';
                completeTable += '<div class="col-md-12">';
                completeTable += '<h6 class="text-white-50 mb-1 small">FINANCIAL SUMMARY</h6>';
                
                // Sale price - always show this
                completeTable += '<div class="mb-2"><strong class="text-white">Sale Price:</strong> <span class="text-info">' + numeral(compeletedResidenceRpt[i].sale_price).format('0,0') + ' ' + compeletedResidenceRpt[i].currencyName + '</span></div>';
                
                // Add fine information if it exists
                if(compeletedResidenceRpt[i].total_Fine != 0){
                  completeTable += '<div class="mb-2"><strong class="text-white">Total Fine:</strong> <span class="text-info">' + numeral(compeletedResidenceRpt[i].total_Fine).format('0,0') + ' ' + compeletedResidenceRpt[i].residenceFineCurrency + '</span></div>';
                  completeTable += '<div class="mb-2"><strong class="text-white">Total Fine Paid:</strong> <span class="text-info">' + numeral(compeletedResidenceRpt[i].totalFinePaid).format('0,0') + ' ' + compeletedResidenceRpt[i].residenceFineCurrency + '</span></div>';
                }
                
                // Total paid - always show this
                completeTable += '<div class="mb-2"><strong class="text-white">Total Paid:</strong> <span class="text-info">' + numeral(compeletedResidenceRpt[i].total).format('0,0') + ' ' + compeletedResidenceRpt[i].currencyName + '</span></div>';
                
                // Calculate total remaining
                let totalRemaining = parseFloat(compeletedResidenceRpt[i].sale_price) - parseFloat(compeletedResidenceRpt[i].total);
                
                // Add fine remaining if currencies match
                if(compeletedResidenceRpt[i].total_Fine != 0 && compeletedResidenceRpt[i].currencyName === compeletedResidenceRpt[i].residenceFineCurrency) {
                  totalRemaining += (parseFloat(compeletedResidenceRpt[i].total_Fine) - parseFloat(compeletedResidenceRpt[i].totalFinePaid));
                }
                
                // Total remaining - always show this
                completeTable += '<div class="mb-2"><strong class="text-white font-weight-bold">Total Remaining:</strong> <span class="text-danger font-weight-bold">' + numeral(totalRemaining).format('0,0') + ' ' + compeletedResidenceRpt[i].currencyName + '</span></div>';
                
                // View button
                completeTable += '<div class="mt-3">';
                completeTable += '<a href="residence.php?id=' + compeletedResidenceRpt[i].main_residenceID + '&stp=' + compeletedResidenceRpt[i].completedStep + '" class="btn btn-info w-100" type="button"><i class="fa fa-eye"></i> View</a>';
                completeTable += '</div>';

                completeTable += '</div>';
                completeTable += '</div>';
                completeTable += '</div>'; // End right column
                
                completeTable += '</div>'; // End main content row
                
                completeTable += '</div>'; // End card-body
                completeTable += '</div>'; // End card
                
                completeTable += '</div></div>'; // End col and row
                completeTable += '<hr class="reportLineBreaker" />';
                j++;
              }
              completedResidence.append(completeTable);
              var completedCounter = 1;
              for(var c = 0; c<compeletedResidenceRpt.length;c++){
                $('#completedCircle'+completedCounter).circleProgress({
	                max: 10,
	                value:compeletedResidenceRpt[c].completedStep,
	                textFormat: 'percent',
                });
                completedCounter++;
              }
              setCircleAttributes('#348fe2');
              $('#completedResidencePagination').pagination('updateItems', compeletedResidenceRpt[0].totalRow);
              
             
            }
            
        },
    });

}
function deleteResidence(id){
  var DeleteResidence = "DeleteResidence";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this residence? Remember deleting residence will also delete the fine on residence!',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "residenceReportController.php",  
                data: {
                  DeleteResidence:DeleteResidence,
                  ID:id
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                    getSearchRpt();
                    showTotalFineView();
                }else{
                  notify('Opps!', response, 'error');
                }
              },
            });
            }
        },
        close: function () {
        }
    }
});
}

function openResidenceFineDialog(id){
  var showFineAmount = "showFineAmount";
  $('#rID').val(id);
  getAccounts('forFine',null);
  $('#fineModal').appendTo("body").modal('show');
}
function saveResidenceFine(){
  var saveResidenceFine = "saveResidenceFine";
  var rid = $('#rID').val();
  if(rid == '' || rid == 'undefined' || rid == 0){
        notify('Error!', 'Something went wrong! Please refresh the page', 'error');
        return;
  }
  var fine_amount = $('#fine_amount');
  if(fine_amount.val() < 1){
        notify('Validation Error!', 'Fine Amount should be greater than 0', 'error');
        return;
  }
  if(fine_amount.val() == ''){
        notify('Validation Error!', 'Fine Amount should not be empty', 'error');
        return;
  }
  var chargeAccount = $('#chargeAccount').select2('data');
  if(chargeAccount[0].id <= 1 || chargeAccount[0].id == ""  || chargeAccount[0].id == "undefined"){
            notify('Validation Error!', 'Please select acount', 'error');
            return;
  }
  var fine_currency_type = $('#fine_currency_type');
    $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          SaveResidenceFine:saveResidenceFine,
          Fine_Amount:fine_amount.val(),
          ChargeAccount:chargeAccount[0].id,
          Fine_currency_type:fine_currency_type.val(),
          RID:rid
        },
        success: function (response) {  
          if(response == "Success"){
            notify('Success!', 'Record added successfully', 'success');
            $('#rID').val('');
            $('#fineModal').modal('hide');
            $('#fine_amount').val('');
            getSearchRpt();
            showTotalFineView();
          }else{
            notify('Error!', 'Something went wrong!', 'error');
          }
              
        },
    });
}

function getCurrencies(type, id){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            if(type  == "getAll"){
                $('#fine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(i == 0){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#fine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type  == "updgetAll"){
                $('#updfine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(i == 0){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#updfine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type  == "updgetSpecific"){
                $('#updfine_currency_type').empty();
                var selected = "";
                for(var i=0; i<currencyType.length; i++){
                  if(id == currencyType[i].currencyID){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                  $('#updfine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                $('.updaccountArea').removeClass('col-sm-9');
                $('.updaccountArea').addClass('col-sm-6');
                $('.updcurrencyArea').removeClass('d-none');
            }
                
        },
    });
}
function checkType(){
 
  var chargeAccount = $('#chargeAccount').select2('data');
  if(chargeAccount[0].text == "Cash"){
    getCurrencies('getAll',null);
    $('.accountArea').removeClass('col-sm-9');
    $('.accountArea').addClass('col-sm-6');
    $('.currencyArea').removeClass('d-none');
  }else{
    $('.accountArea').removeClass('col-sm-6');
    $('.accountArea').addClass('col-sm-9');
    $('.currencyArea').addClass('d-none');
  }
}

function updcheckType(){
  var updchargeAccount = $('#updchargeAccount').select2('data');
  if(updchargeAccount[0].text == "Cash"){
    getCurrencies('updgetAll',null);
    $('.updaccountArea').removeClass('col-sm-9');
    $('.updaccountArea').addClass('col-sm-6');
    $('.updcurrencyArea').removeClass('d-none');
  }else{
    $('.updaccountArea').removeClass('col-sm-6');
    $('.updaccountArea').addClass('col-sm-9');
    $('.updcurrencyArea').addClass('d-none');
  }
}
    function viewFine(id){
      var viewFine = "viewFine";
      $('#viewFineModal').appendTo("body").modal('show');
      $('#uploadChargesID').val(id); // Store the residence ID for adding new fines
      
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            ViewFine:viewFine,
            ID:id
        },
        success: function (response) {
          var viewFineRpt = JSON.parse(response);
          if(viewFineRpt.length === 0){
            $('#viewFineTable').empty();
            var finalTable = '<tr><td colspan="8" class="text-center py-4">No fine records found</td></tr>';
            $('#viewFineTable').append(finalTable);
            
            // Update the summary section
            $('#outstandingFineAmount').html('<span class="text-success fw-bold fs-5">No outstanding fines</span>');
          }else{
            $('#viewFineTable').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<viewFineRpt.length; i++){
              finalTable = '<tr class="align-middle">';
              finalTable += '<td class="text-center">'+ j + '</td>';
              finalTable += '<td><span class="badge bg-warning text-dark">Fine</span></td>';
              finalTable += '<td>'+ numeral(viewFineRpt[i].fineAmount).format('0,0') + ' ' + 
                            '<small class="text-primary">' + viewFineRpt[i].currencyName + '</small></td>';
              finalTable += '<td>'+ viewFineRpt[i].account_Name +'</td>';
              finalTable += '<td>'+ viewFineRpt[i].residenceFineDate +'</td>';
              finalTable += '<td>'+ viewFineRpt[i].staff_name + '</td>';
              
              // Receipt column with improved styling
              if(viewFineRpt[i].docName == null || viewFineRpt[i].docName == '' ){
                finalTable += '<td><button type="button" onclick="uploadExraFile('+ viewFineRpt[i].residenceFineID +')" ' +
                            'class="btn btn-sm btn-outline-primary"><i class="fa fa-upload me-1"></i>Upload</button></td>';
              }else{
                finalTable += '<td class="text-center">' + 
                            '<div class="btn-group">' +
                            '<a href="downloadFineDocs.php?id=' + viewFineRpt[i].residenceFineID +'&type=2" class="btn btn-sm btn-outline-info">' +
                            '<i class="fa fa-download me-1"></i>Download</a>' +
                            '<button type="button" onclick="deleteFile(' + viewFineRpt[i].residenceFineID +')" class="btn btn-sm btn-outline-danger">' +
                            '<i class="fa fa-trash"></i></button>' +
                            '</div></td>';
              }
              
              // Actions column with improved styling
              finalTable += '<td class="text-center">';
              finalTable += '<div class="btn-group">';
              finalTable += '<button type="button" onclick="openExtraCModal(' + viewFineRpt[i].residenceFineID +')" ' +
                          'class="btn btn-sm btn-outline-warning"><i class="fa fa-edit me-1"></i>Edit</button>';
              finalTable += '<button type="button" onclick="DeleteFine(' + viewFineRpt[i].residenceFineID +')" ' +
                          'class="btn btn-sm btn-outline-danger"><i class="fa fa-trash me-1"></i>Delete</button>';
              finalTable += '<button type="button" onclick="payFine(' + viewFineRpt[i].residenceFineID +')" ' +
                          'class="btn btn-sm btn-outline-success"><i class="fa fa-money me-1"></i>Pay</button>';
              finalTable += '</div>';
              finalTable += '</td>';
              finalTable += '</tr>';
              $('#viewFineTable').append(finalTable);
              j++;
            }
             
            // Fetch and display the fine total
            getFineTotal(viewFineRpt[0].residenceID);
          }
        },
      });
    }
    
    function getFineTotal(id){
      var getFineTotal = "getFineTotal";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetFineTotal:getFineTotal,
              ID:id
            },
            success: function (response) {
              var viewRF = JSON.parse(response);
              
              if (viewRF.length === 0) {
                // If no data, show no outstanding fines
                $('#outstandingFineAmount').html('<span class="text-success fw-bold fs-5">No outstanding fines</span>');
                return;
              }
              
              // Create a nicely formatted display of outstanding fines
              var totalHtml = '';
              for(var i = 0; i < viewRF.length; i++){
                var badgeClass = parseFloat(viewRF[i].RF) > 0 ? 'text-danger' : 'text-success';
                var badgeText = parseFloat(viewRF[i].RF) > 0 ? 'Outstanding' : 'Paid';
                
                totalHtml += '<div class="d-flex justify-content-between align-items-center">';
                totalHtml += '<span class="fs-5 fw-bold ' + badgeClass + '">' + numeral(viewRF[i].RF).format('0,0') + ' ' + viewRF[i].currencyName + '</span>';
                totalHtml += '<span class="badge bg-' + (parseFloat(viewRF[i].RF) > 0 ? 'danger' : 'success') + '">' + badgeText + '</span>';
                totalHtml += '</div>';
                
                if (i < viewRF.length - 1) {
                  totalHtml += '<hr class="my-2">';
                }
              }
              
              $('#outstandingFineAmount').html(totalHtml);
              
              // Also append to the table
              var table = "";
              table += '<tr class="bg-light">';
              table += '<td colspan="6" class="text-end fw-bold">Outstanding Fine Balance:</td>';
              table += '<td colspan="2" class="fw-bold">';
              
              for(var i = 0; i < viewRF.length; i++){
                var textClass = parseFloat(viewRF[i].RF) > 0 ? 'text-danger' : 'text-success';
                table += '<div class="' + textClass + '">' + numeral(viewRF[i].RF).format('0,0') + ' ' + viewRF[i].currencyName + '</div>';
              }
              
              table += '</td></tr>';
              $('#viewFineTable').append(table);
            },
        });
    }
    function uploadExraFile(id){
      $('#uploadChargesID').val(id);
      $('#Chargesuploader').click();
    }
    document.getElementById("Chargesuploader").onchange = function(event) {
      $('#submitChargeUploadForm').click();
    };
    $(document).on('submit', '#chargeUpload', function(event){
      event.preventDefault();
      var uploadChargesID = $('#uploadChargesID');
      var Chargesuploader = $('#Chargesuploader').val();
      if(uploadChargesID.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#Chargesuploader').val() != ''){
        if($('#Chargesuploader')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('Upload_ExraChargeDoc','Upload_ExraChargeDoc');
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    uploadChargesID.val('');
                    $('#Chargesuploader').val('');
                    $('#viewFineModal').modal('hide');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function deleteFile(id){
      var DeleteFile = "DeleteFile";
        $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this file',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "residenceReportController.php",  
                    data: {
                      DeleteFile:DeleteFile,
                      ID:id,
                    },
                    success: function (response) {  
                    if(response == 'Success'){
                      notify('Success!', response, 'success');
                      $('#viewFineModal').modal('hide');
                      getSearchRpt();
                      showTotalFineView();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                });
                }
            },
            close: function () {
            }
        }
        });
    }
    function openExtraCModal(id){
      $('#viewFineModal').modal('hide');
      $('#updrID').val(id);
      getDataForUpdate = "getDataForUpdate";
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetDataForUpdate:getDataForUpdate,
            ID:id
        },
        success: function (response) {
          var updviewFineRpt = JSON.parse(response);
          $('#updfine_amount').val(updviewFineRpt[0].fineAmount);
          if(updviewFineRpt[0].account_Name != "Cash"){
              getAccounts('byUpdateWithoutCash', updviewFineRpt[0].account_ID);
          }else{
            getAccounts('byUpdate', updviewFineRpt[0].account_ID);
            getCurrencies('updgetSpecific', updviewFineRpt[0].fineCurrencyID);
          }
        }
      });
      $('#updfineModal').appendTo("body").modal('show');
    }
    function updResidenceFine(){
      var UpdSaveResidenceFine = "UpdSaveResidenceFine";
      var updrID = $('#updrID').val();
      if(updrID =="" || updrID == 0 || updrID== "undefined"){
        notify('Error!', "Something went wrong! refresh the page", 'error');
        return;
      }
      var updfine_amount = $('#updfine_amount');
      if(updfine_amount.val() < 1){
            notify('Validation Error!', 'Fine Amount should be greater than 0', 'error');
            return;
      }
      if(updfine_amount.val() == ''){
            notify('Validation Error!', 'Fine Amount should not be empty', 'error');
            return;
      }
      var updchargeAccount = $('#updchargeAccount').select2('data');
      if(updchargeAccount[0].id <= 1 || updchargeAccount[0].id == ""  || updchargeAccount[0].id == "undefined"){
            notify('Validation Error!', 'Please select acount', 'error');
            return;
      }
      var updfine_currency_type = $('#updfine_currency_type');
      $.ajax({
          type: "POST",
          url: "residenceReportController.php",  
          data: {
            UpdSaveResidenceFine:UpdSaveResidenceFine,
            Updfine_Amount:updfine_amount.val(),
            UpdchargeAccount:updchargeAccount[0].id,
            Updfine_Currency_type:updfine_currency_type.val(),
            UpdrID:updrID
          },
          success: function (response) {  
            if(response == "Success"){
              notify('Success!', 'Record updated successfully', 'success');
              $('#updrID').val('');
              $('#updfineModal').modal('hide');
              $('#updfine_amount').val('');
              getSearchRpt();
              showTotalFineView();
            }else{
              notify('Error!', 'Something went wrong!', 'error');
            }
                
          },
      });
    }
    function DeleteFine(id){
      var DeleteFine = "DeleteFine";
        $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this Fine',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "residenceReportController.php",  
                    data: {
                      DeleteFine:DeleteFine,
                      ID:id,
                    },
                    success: function (response) {  
                    if(response == 'Success'){
                      notify('Success!', response, 'success');
                      $('#viewFineModal').modal('hide');
                      getSearchRpt();
                      showTotalFineView();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                });
                }
            },
            close: function () {
            }
        }
        });
    }
    function payFine(id){
      $('#viewFineModal').modal('hide');
      var resFPaymentID = $('#resFPaymentID').val(id);
      if(resFPaymentID < 1 || resFPaymentID =="" || resFPaymentID == "undefined"){
        notify('Error!', "Something went wrong! Please refresh the page.", 'error');
      }
      getTotalFine = "getTotalFine";
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
            GetTotalFine:getTotalFine,
            ID:id
        },
        success: function (response) {
          var totalFineRpt = JSON.parse(response);
          $('#fine_balance').val(numeral(totalFineRpt[0].fineAmount).format('0,0') + ' ' + totalFineRpt[0].currencyName);
          getAccounts('forFinePayment',null);
        }
      });
      $('#FinePaymentModel').appendTo("body").modal('show');
    }
    function saveFinePayment(){
    var INSERT_FINE_PAYMENT ="INSERT_FINE_PAYMENT";
    var Fine_payAmount = $('#Fine_payAmount');
    if(Fine_payAmount.val() == "" || Fine_payAmount.val() < 0){
        notify('Validation Error!', 'payment amount is required', 'error');
        return;
    }
    var fine_remarks= $('#fine_remarks');
    var fine_account_id = $('#fine_account_id');
    if(fine_account_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var resFPaymentID = $('#resFPaymentID');
    if(resFPaymentID.val() < 0 || resFPaymentID.val() == "" || resFPaymentID.val() == "undefined"){
        notify('Validation Error!', 'Something went wrong!Please refresh the page', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              INSERT_FINE_PAYMENT:INSERT_FINE_PAYMENT,
              Fine_payAmount : Fine_payAmount.val(),
              Fine_remarks: fine_remarks.val(),
              Fine_account_id:fine_account_id.val(),
              ResFPaymentID:resFPaymentID.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#FinePaymentModel').modal('hide');
                    if($("#residenceTabs li a.active").attr('href') == "#default-tab-1"){
                      getPendingResidence();
                    }else if($("#residenceTabs li a.active").attr('href') == "#default-tab-2"){
                      getPendingPayForResidence();
                    }
                    Fine_payAmount.val('');
                    resFPaymentID.val('');
                    $('#fine_remarks').val('');
                    getSearchRpt();
                    showTotalFineView();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    function showTotalFineView(){
      var labelArr = [];
      var dataArr = [];
      var getTotalResidencePendingP = "getTotalResidencePendingP";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetTotalResidencePendingP:getTotalResidencePendingP,
            },
            success: function (response) {
              var getTFPRpt = JSON.parse(response);
              for(var i=0;i<getTFPRpt.length;i++){
                labelArr.push(getTFPRpt[i].currencyName);
                dataArr.push(parseInt(getTFPRpt[i].TotalBalance));
              }
              drawPendingResidencePayment(labelArr,dataArr);
            },
        });
      
      
    }
    
    function drawPendingResidencePayment(labelInp,dataInp){
      const data = {
          labels: labelInp ,
          datasets: [{
            label: 'Total Residence Balance',
            data: dataInp,
            backgroundColor: [
              'rgba(255, 26, 104, 0.4)',
              'rgba(54, 162, 235, 0.4)',
              'rgba(255, 206, 86, 0.4)',
              'rgba(75, 192, 192, 0.4)',
              'rgba(153, 102, 255, 0.4)',
              'rgba(255, 159, 64, 0.4)',
              'rgba(0, 0, 0, 0.4)',
            ],
            borderColor: [
              'rgba(255, 26, 104, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 1
          }]
      };
        // config 
        const config = {
          type: 'bar',
          data,
          options: {
            plugins: {
              legend: {
                labels: {
                  color: 'white'
                }
              },
              datalabels: {
                color: 'white',
                formatter: function(value) {
                  return value.toLocaleString();
                }
              }
            },
            scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    color: 'white'
                  }
                },
                x: {
                  ticks: {
                    color: 'white'
                  }
                }
              },
              
            },
            plugins: [ChartDataLabels],
        };
        let chartStatus = Chart.getChart("myChart"); // <canvas> id
        if (chartStatus != undefined) {
          chartStatus.destroy();
        }
          // render init block
          const myChart = new Chart(
            document.getElementById('myChart'),
            config
          );
    }
    // this function gives idea for the user
    function getSearchResult(searchTerm){
      var getSearchResult = "getSearchResult";
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetSearchResult:getSearchResult,
              SearchTerm:searchTerm
            },
            success: function (response) {
              let searchResult = JSON.parse(response);
              let searchDropdown = $('#searchDropdown');
              $('#searchDropdown').css('height', '150px');
              let finalResult = '';
              searchDropdown.empty();
              searchDropdown.append('<ul class="list-group list-group-flush">');
              $('#searchDropdown').show();
              if(searchResult.length == 0){
                $('#searchDropdown').css('height', '42px');
                finalResult+= '<li class="list-group-item text-capitalize text-center">No Result Found...</li>';
                finalResult += '</ul>';
                searchDropdown.append(finalResult);
                return;
              }
              for(let i = 0; i<searchResult.length; i++){
                if(searchResult[i].identifier == 1){
                  finalResult += '<li class="list-group-item text-capitalize" onclick="searchForDuePayments('+ 
                  searchResult[i].customer_id +',\'C\',\'null\')">'+ searchResult[i].customer_name  +'</li>';  
                }else{
                  finalResult += '<li class="list-group-item text-capitalize" onclick="searchForDuePayments('+ 
                  searchResult[i].customer_id +',\'P\',\''+ searchResult[i].passenger_name + '\')">'+ searchResult[i].customer_name  +' <span class="badge rounded-pill '+
                  ' bg-primary text-capitalize">'+
                  searchResult[i].passenger_name +'</span></li>';  
                }
              }
              finalResult += '</ul>';
              searchDropdown.append(finalResult);
              document.getElementById('searchDropdown').addEventListener('mouseover', function(event){
                if(event.target.tagName === 'LI'){
                   event.target.style.cursor = 'pointer';
                   event.target.style.backgroundColor = '#ADD8E6';
                   event.target.style.fontWeight = 'bold';
                }
              });
              document.getElementById('searchDropdown').addEventListener('mouseout', function(event){
                if(event.target.tagName === 'LI'){
                   event.target.style.cursor = 'default';
                   event.target.style.backgroundColor = 'white';
                   event.target.style.fontWeight = 'normal';
                }
              });
              document.addEventListener('mouseup', function(event){
                let dropdown = document.getElementById('searchDropdown');
                if(!dropdown.contains(event.target)){
                  dropdown.style.display = 'none';
                }
              });
              let searchbox = document.getElementById('search');
              searchbox.addEventListener('click', function(){
                  if(searchbox.value.length > 2 && $("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'block';
                    
                  }else{
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'none';
                  }
              });
              searchbox.addEventListener('input', function(){
                  if(searchbox.value.trim() === ''){
                    let dropdown = document.getElementById('searchDropdown');
                    dropdown.style.display = 'none';
                  }
              });
            },
        });
    }
    function searchForDuePayments(id, flag, passengerName){
      // first hide the dropdown
      let dropdown = document.getElementById('searchDropdown');
      dropdown.style.display = 'none';
      // show the loader
      $('#customerID').val(id);
      $('#passengerName').val(passengerName);
      getCustomerCurrencyForSearch();
    }
    // get customer currency for search
    function getCustomerCurrencyForSearch(){
      const getCustomerCurrencyForSearch = "getCustomerCurrencyForSearch";
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
     
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetCustomerCurrencyForSearch:getCustomerCurrencyForSearch,
              CustomerID:customerID,
              PassengerName:passengerName
            },
            success: function (response) {
              let getCusCurRpt = JSON.parse(response);
              let searchAreaDiv = $('#searchAreaDiv');
              if(getCusCurRpt.length == 0){
                hideCurrencyDropdownForSearch();
                $('#customerID').val('');
                $('#passengerName').val('');
                $('#residenceCurrency').empty(); 
                $('#abstractViewArea').empty();
                $('#abstractViewArea').append('<h2 class="text-center">No Record Found...</h2><hr/>');
                return;
              }
              if($("#residenceTabs li a.active").attr('href') == "#default-tab-4"){
                $('#residenceCurrency').empty();
                showCurrencyDropdownForSearch();
              } 
              let selected = '';       
               for(var i=0; i<getCusCurRpt.length; i++){
                if(i == 0){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#residenceCurrency').append("<option "+ selected + "  value='"+ getCusCurRpt[i].currencyID +"'>"+ 
                getCusCurRpt[i].currencyName +"</option>");
              }
              getAbstrictView();
            },
        });
      
    }
    // the function is used for showing the abstrict view of customer balance
    function getAbstrictView(){
      const getAbstrictView = "getAbstrictView";
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
      const residenceCurrency = $('#residenceCurrency').val();
      $.ajax({
            type: "POST",
            url: "residenceReportController.php",  
            data: {
              GetAbstrictView:getAbstrictView,
              CustomerID:customerID,
              PassengerName:passengerName,
              ResidenceCurrency:residenceCurrency
            },
            success: function (response) {
              let getBlanceRpt = JSON.parse(response);
              $('#abstractViewArea').removeClass('d-none');
              $('#abstractViewArea').empty();
              let totalBalance = (parseInt(getBlanceRpt[0].total_residenceCost) + parseInt(getBlanceRpt[0].residenceFine)) - (parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment));
              if(Number.isNaN(totalBalance)){
                totalBalance = 0;
              }
              let totalBalancePercentage =  Math.round(((parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment))* 100) / (parseInt(getBlanceRpt[0].total_residenceCost) + parseInt(getBlanceRpt[0].residenceFine)));
              if(Number.isNaN(totalBalancePercentage)){
                totalBalancePercentage = 0;
              }
              let totalResidenceCostsPercentage =  Math.round(((parseInt(getBlanceRpt[0].total_residency_payment))* 100) / (parseInt(getBlanceRpt[0].total_residenceCost)));
              if(Number.isNaN(totalResidenceCostsPercentage)){
                totalResidenceCostsPercentage = 0;
              }
              let totalFinePercentage =  Math.round(((parseInt(getBlanceRpt[0].total_fine_payment))* 100) / (parseInt(getBlanceRpt[0].residenceFine)));
              if(Number.isNaN(totalFinePercentage)){
                totalFinePercentage = 0;
              }
              let totalPaid = parseInt(getBlanceRpt[0].total_residency_payment) + parseInt(getBlanceRpt[0].total_fine_payment);
              if(Number.isNaN(totalPaid)){
                totalPaid = 0;
              }
              let balanceLable = '';
              let residenceCostLabel = '';
              let fineLable = '';
              if(totalBalance !=  0 ){
                balanceLable = 'Due Payment';
              }else{
                balanceLable = 'Payments completed';
              }
              if(parseInt(getBlanceRpt[0].total_residenceCost) - parseInt(getBlanceRpt[0].total_residency_payment) == 0){
                residenceCostLabel = 'Residence payment completed';
              }else{
                residenceCostLabel = 'Due Residence payment';
              }
              if(parseInt(getBlanceRpt[0].residenceFine) - parseInt(getBlanceRpt[0].total_fine_payment) == 0){
                fineLable = 'Fine Cleared';
              }else{
                fineLable = 'Due Fine';
              }
              $('#abstractViewArea').append(`<div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-cc-visa" style="font-size:50px;color:#49b6d6"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Residence Cost</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${getBlanceRpt[0].total_residenceCost}">${numeral(getBlanceRpt[0].total_residenceCost).format('0,0')} </div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-info" data-animation="width" data-value="${totalResidenceCostsPercentage}%" style="width: ${totalResidenceCostsPercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalResidenceCostsPercentage}">${totalResidenceCostsPercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-info fs-10px ps-2 pe-2">${residenceCostLabel}</a>
                                        </div>
                                        <div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-facebook-f" style="font-size:50px;color:#ff5b57"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Fine</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${getBlanceRpt[0].residenceFine}">${numeral(getBlanceRpt[0].residenceFine).format('0,0')}</div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-danger" data-animation="width" data-value="${totalFinePercentage}%" style="width: ${totalFinePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalFinePercentage}">${parseInt(totalFinePercentage)}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-danger fs-10px ps-2 pe-2">${fineLable}</a>
                                        </div>
                                        <div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-paypal" style="font-size:50px;color:#f59c1a"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Paid</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${totalPaid}">${numeral(totalPaid).format('0,0')} </div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-warning" data-animation="width" data-value="${totalBalancePercentage}%" style="width: ${totalBalancePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalBalancePercentage}">${totalBalancePercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-warning fs-10px ps-2 pe-2">${balanceLable}</a>
                                        </div><div class="col-lg-1">
                                          <div class="h-100px d-flex align-items-center justify-content-center">
                                            <i class="fa fa-credit-card" style="font-size:50px;color:#8753de"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-2">
                                          <div class="mb-2px text-truncate">Total Due Balance</div>
                                          <div class="fs-18px mb-5px fw-bold" data-animation="number" data-value="${totalBalance}">${numeral(totalBalance).format('0,0')}</div>
                                          <div class="d-flex align-items-center mb-2px">
                                            <div class="flex-grow-1">
                                              <div class="progress h-5px rounded-pill bg-white bg-opacity-10">
                                                <div class="progress-bar progress-bar-striped bg-indigo" data-animation="width" data-value="${totalBalancePercentage}%" style="width: ${totalBalancePercentage}%;"></div>
                                              </div>
                                            </div>
                                            <div class="ms-2 small w-30px text-center"><span data-animation="number" data-value="${totalBalancePercentage}">${totalBalancePercentage}</span>%</div>
                                          </div>
                                          <div class="text-gray-500 small mb-15px text-truncate">
                                           Currnecy: <span> ${getBlanceRpt[0].currencyName}</span>
                                          </div>
                                          <a href="#" class="btn btn-xs btn-indigo fs-10px ps-2 pe-2">${balanceLable}</a>
                                        </div><hr style="margin-top: 25px"/>`);
                                        getCustomerInfo(customerID);
                                        $('#currencyInfo').empty();
                                        $('#currencyInfo').append(getBlanceRpt[0].currencyName);
                                        getResidenceLedger();

            },
          });
    }
    // for showing currency dropdown while searching in tab 4
    function showCurrencyDropdownForSearch(){
      const searchAreaDiv = $('#searchAreaDiv');
      searchAreaDiv.removeClass('offset-6');
      searchAreaDiv.addClass('offset-4');
      $('#currencyAreaForRpt').removeClass('d-none');
    }
    // for hiding currency dropdown while searching in other tabs
    function hideCurrencyDropdownForSearch(){
      const searchAreaDiv = $('#searchAreaDiv');
      searchAreaDiv.removeClass('offset-4');
      searchAreaDiv.addClass('offset-6');
      $('#currencyAreaForRpt').addClass('d-none');
    }
    function getCustomerInfo(id){
    var getCustomerInfo = "getCustomerInfo";
      $.ajax({
          type: "POST",
          url: "InvoiceController.php",  
          data: {
            GetCustomerInfo:getCustomerInfo,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#name').text(report[0].customer_name);
            
            if(report[0].customer_email == ""){
                
                $('#email').text('Nill');
            }else{
                $('#email').text(report[0].customer_email);
            }
            if(report[0].customer_phone == ''){
                $('#phone').text('Nill');
            }else{
                $('#phone').text(report[0].customer_phone);
            }
            
          },
      });
    }
    function getResidenceLedger(){
      const customerID = $('#customerID').val();
      const passengerName = $('#passengerName').val();
      const residenceCurrency = $('#residenceCurrency').val();
      let getResidenceLedger = "getResidenceLedger";
      $.ajax({
          type: "POST",
          url: "residenceReportController.php",  
          data: {
            GetResidenceLedger:getResidenceLedger,
            CustomerID:customerID,
            PassengerName:passengerName,
            CurID:residenceCurrency,
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var remaining = 0;
            var total = 0;
            var totalRefund = 0;
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].transactionType == "Residence Payment"){
                    remaining = remaining - parseInt(report[i].credit);
                    customerTotalPaid += parseInt(report[i].credit);
                    finalTable = "<tr><td class='payment' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize payment' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize payment' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td style='-webkit-print-color-adjust: exact;' class='payment'>"+report[i].visaType+"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence application"){
                    total += parseInt(report[i].debit);
                    remaining = remaining + parseInt(report[i].debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence Fine"){
                    total += parseInt(report[i].debit);
                    remaining = remaining + parseInt(report[i].debit);
                    finalTable = "<tr><td class='fine' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize fine' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize fine' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='fine' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].transactionType == "Residence Fine Payment"){
                    remaining = remaining - parseInt(report[i].credit);
                    customerTotalPaid += parseInt(report[i].credit);
                    finalTable = "<tr><td class='fine_payment' scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize fine_payment' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='text-capitalize fine_payment' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ report[i].dt +
                    "</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+report[i].visaType+"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].debit).format('0,0') +"</td><td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].credit).format('0,0') +"</td>"+
                    "<td class='fine_payment' style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }
                
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            
              
              
            }
            calculateBalancesForLedger(total,customerTotalPaid,remaining);
          },
      });
    }
    function calculateBalancesForLedger(total,customerTotalPaid,remaining){
            const currencyLabel = $('#residenceCurrency').text();
            $('#total').text(numeral(total).format('0,0') + ' ' + currencyLabel );
            $('#total_paid').text(numeral(customerTotalPaid).format('0,0') + ' ' + currencyLabel);
            $('#outstanding_balance').text(numeral(remaining).format('0,0') + ' ' + currencyLabel);
    }
    function printLedger(){
      // Get the current row data
      var currentRow = $('#myTable').DataTable().row('.selected').data();
      if (!currentRow) {
        alert('Please select a receipt to print');
        return;
      }
      
      // Create filename with passenger name, amount and invoice number
      var passengerName = currentRow[2].replace(/ /g, '_'); // Replace spaces with underscores
      var amount = parseFloat(currentRow[4]).toFixed(2);
      var invoiceNumber = currentRow[1];
      var fileName = passengerName + '_' + amount + '_' + invoiceNumber;
      
      printJS({ 
          printable: 'printThisArea', 
          type: 'html', 
          documentTitle: fileName,
          css: [
              'bootstrap-4.3.1-dist/css/bootstrap.min.css',
              'customBootstrap.css',
              'https://fonts.googleapis.com/css?family=Arizonia',
              'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
          ],
          targetStyles: ['*'],
          onLoadingStart: ()=>{
            $('#myTable').removeClass('table-dark');
            $('#myTable').addClass('table-striped');
          },
          onLoadingEnd: () => {
            $('#myTable').addClass('table-dark');
            $('#myTable').removeClass('table-striped');
          },
      })
    }

    function viewPaymentHistory(id) {
        $('#paymentHistoryModal').modal('show');
        $.ajax({
            url: 'residenceReportController.php',
            type: 'POST',
            data: {
                GetPaymentHistory: true,
                ID: id
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    let tableBody = '';
                    if (data.length > 0) {
                        data.forEach((payment, index) => {
                            // Get receipt ID if it exists
                            let receiptButton = '';
                            if (payment.receiptID) {
                                receiptButton = `<a href="/receipt/?id=${payment.receiptID}&hash=${md5(payment.receiptID + "::::::" + payment.receiptID)}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-file-text"></i> View Receipt</a>`;
                            } else {
                                receiptButton = `<button class="btn btn-sm btn-success" onclick="generateReceipt(${payment.paymentID})"><i class="fa fa-plus"></i> Generate Receipt</button>`;
                            }
                            
                            tableBody += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${payment.payment_date}</td>
                                    <td>${payment.amount} ${payment.currency_name}</td>
                                    <td>${payment.account_name}</td>
                                    <td>${payment.payment_type}</td>
                                    <td>${payment.staff_name}</td>
                                    <td>${payment.remarks || ''}</td>
                                    <td>${receiptButton}</td>
                                </tr>
                            `;
                        });
                    } else {
                        tableBody = '<tr><td colspan="8" class="text-center">No payment history found</td></tr>';
                    }
                    $('#paymentHistoryTableBody').html(tableBody);
                } catch (e) {
                    console.error('Error parsing payment history:', e);
                    $('#paymentHistoryTableBody').html('<tr><td colspan="8" class="text-center text-danger">Failed to load payment history</td></tr>');
                }
            },
            error: function() {
                $('#paymentHistoryTableBody').html('<tr><td colspan="8" class="text-center text-danger">Failed to load payment history</td></tr>');
            }
        });
    }

    // Add event listener for the close button
    $(document).ready(function() {
        $('#paymentHistoryModal .btn-secondary').on('click', function() {
            $('#paymentHistoryModal').modal('hide');
        });
    });

    function generateReceipt(paymentID) {
      // Disable the button to prevent double-clicks
      $(`button[onclick="generateReceipt(${paymentID})"]`).attr("disabled", true);
      
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",
        data: {
          GenerateReceipt: true,
          PaymentID: paymentID
        },
        success: function(response) {
          var data = JSON.parse(response);
          if (data.message === "Success") {
            notify('Success!', 'Receipt generated successfully', 'success');
            // Generate the receipt link for printing and downloading
            const receiptHash = md5(data.receiptID + "::::::" + data.receiptID);
            const baseReceiptUrl = `/receipt/?id=${data.receiptID}&hash=${receiptHash}`;
            
            // Replace the generate button with print and download buttons
            let buttonsHtml = `
              <a href="${baseReceiptUrl}" target="_blank" class="btn btn-sm btn-info mr-1" style="margin-right: 5px;">
                <i class="fa fa-print"></i> Print
              </a>
              <a href="${baseReceiptUrl}&download=true" target="_blank" class="btn btn-sm btn-success">
                <i class="fa fa-download"></i> Download
              </a>`;
            
            $(`button[onclick="generateReceipt(${paymentID})"]`).parent().html(buttonsHtml);
          } else {
            notify('Error!', data.error || 'Failed to generate receipt', 'error');
            $(`button[onclick="generateReceipt(${paymentID})"]`).attr("disabled", false);
          }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          notify('Error!', 'Failed to generate receipt', 'error');
          $(`button[onclick="generateReceipt(${paymentID})"]`).attr("disabled", false);
        }
      });
    }

    function redirectToReceiptDetails(receiptID) {
        window.location.href = '../receipt/receiptDetails.php?rcptID=' + receiptID;
    }

    function uploadFile(receiptID) {
        // Set the receipt ID in the uploader form
        $('#FileID').val(receiptID);
        // Show the uploader modal
        $('#uploaderModal').modal('show');
    }

    function downloadPaymentReceipt(id) {
        // Get the current row data
        var currentRow = $('#myTable').DataTable().row('.selected').data();
        if (!currentRow) {
            alert('Please select a receipt to download');
            return;
        }
        
        // Create filename with passenger name, amount and invoice number
        var passengerName = currentRow[2].replace(/ /g, '_'); // Replace spaces with underscores
        var amount = parseFloat(currentRow[4]).toFixed(2);
        var invoiceNumber = currentRow[1];
        
        // Add the filename parameters to the URL
        window.location.href = '../../../controller/residence/residence_receipt/downloadPaymentReceipt.php?id=' + id + 
                             '&passenger=' + encodeURIComponent(passengerName) + 
                             '&amount=' + encodeURIComponent(amount) + 
                             '&invoice=' + encodeURIComponent(invoiceNumber);
    }

    function deletePaymentReceiptFile(id) {
        $.confirm({
            title: 'Delete!',
            content: 'Do you want to delete this receipt file?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function() {
                        $.ajax({
                            type: "POST",
                            url: "../../../controller/residence/residence_receipt/deleteResidenceReceipt.php",
                            data: {
                                deletePaymentReceiptFile: "deletePaymentReceiptFile",
                                ID: id,
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.message == 'Success') {
                                    notify('Success!', 'Receipt file deleted successfully', 'success');
                                    // Refresh the payment data to update UI
                                    loadPaymentData(); // Make sure you have this function to refresh your data
                                } else {
                                    notify('Oops!', data.error || 'Failed to delete receipt file', 'error');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                var response = JSON.parse(jqXHR.responseText);
                                notify('Error!', response.error, 'error');
                            }
                        });
                    }
                },
                close: function() {}
            }
        });
    }

    // Add MD5 function if not already present
    function md5(string) {
      function rotateLeft(lValue, iShiftBits) {
        return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
      }
      
      function addUnsigned(lX, lY) {
        var lX4, lY4, lX8, lY8, lResult;
        lX8 = (lX & 0x80000000);
        lY8 = (lY & 0x80000000);
        lX4 = (lX & 0x40000000);
        lY4 = (lY & 0x40000000);
        lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
        if (lX4 & lY4) return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
        if (lX4 | lY4) {
          if (lResult & 0x40000000) return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
          else return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
        } else {
          return (lResult ^ lX8 ^ lY8);
        }
      }
      
      function F(x, y, z) { return (x & y) | ((~x) & z); }
      function G(x, y, z) { return (x & z) | (y & (~z)); }
      function H(x, y, z) { return (x ^ y ^ z); }
      function I(x, y, z) { return (y ^ (x | (~z))); }
      
      function FF(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(F(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
      }
      
      function GG(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(G(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
      }
      
      function HH(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(H(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
      }
      
      function II(a, b, c, d, x, s, ac) {
        a = addUnsigned(a, addUnsigned(addUnsigned(I(b, c, d), x), ac));
        return addUnsigned(rotateLeft(a, s), b);
      }
      
      function convertToWordArray(string) {
        var lWordCount;
        var lMessageLength = string.length;
        var lNumberOfWords_temp1 = lMessageLength + 8;
        var lNumberOfWords_temp2 = (lNumberOfWords_temp1 - (lNumberOfWords_temp1 % 64)) / 64;
        var lNumberOfWords = (lNumberOfWords_temp2 + 1) * 16;
        var lWordArray = Array(lNumberOfWords - 1);
        var lBytePosition = 0;
        var lByteCount = 0;
        while (lByteCount < lMessageLength) {
          lWordCount = (lByteCount - (lByteCount % 4)) / 4;
          lBytePosition = (lByteCount % 4) * 8;
          lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
          lByteCount++;
        }
        lWordCount = (lByteCount - (lByteCount % 4)) / 4;
        lBytePosition = (lByteCount % 4) * 8;
        lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
        lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
        lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
        return lWordArray;
      }
      
      function wordToHex(lValue) {
        var wordToHexValue = "",
          wordToHexValue_temp = "",
          lByte, lCount;
        for (lCount = 0; lCount <= 3; lCount++) {
          lByte = (lValue >>> (lCount * 8)) & 255;
          wordToHexValue_temp = "0" + lByte.toString(16);
          wordToHexValue = wordToHexValue + wordToHexValue_temp.substr(wordToHexValue_temp.length - 2, 2);
        }
        return wordToHexValue;
      }
      
      var x = Array();
      var k, AA, BB, CC, DD, a, b, c, d
      var S11 = 7,
        S12 = 12,
        S13 = 17,
        S14 = 22;
      var S21 = 5,
        S22 = 9,
        S23 = 14,
        S24 = 20;
      var S31 = 4,
        S32 = 11,
        S33 = 16,
        S34 = 23;
      var S41 = 6,
        S42 = 10,
        S43 = 15,
        S44 = 21;
      
      string = string.toString();
      x = convertToWordArray(string);
      a = 0x67452301;
      b = 0xEFCDAB89;
      c = 0x98BADCFE;
      d = 0x10325476;
      
      for (k = 0; k < x.length; k += 16) {
        AA = a;
        BB = b;
        CC = c;
        DD = d;
        a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
        d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
        c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
        b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
        a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
        d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
        c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
        b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
        a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
        d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
        c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
        b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
        a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
        d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
        c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
        b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
        a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
        d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
        c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
        b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
        a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
        d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
        c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
        b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
        a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
        d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
        c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
        b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
        a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
        d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
        c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
        b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
        a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
        d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
        c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
        b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
        a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
        d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
        c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
        b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
        a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
        d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
        c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
        b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
        a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
        d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
        c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
        b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
        a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
        d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
        c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
        b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
        a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
        d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
        c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
        b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
        a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
        d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
        c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
        b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
        a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
        d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
        c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
        b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
        a = addUnsigned(a, AA);
        b = addUnsigned(b, BB);
        c = addUnsigned(c, CC);
        d = addUnsigned(d, DD);
      }
      
      var temp = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);
      return temp.toLowerCase();
    }

    // Added for Salary Certificate functionality
    function openSalaryCertificateDialog(id){
      $('#salaryCertResidenceID').val(id);
      $('#bankSelectorModal').modal('show');
      
      // Try-catch block to handle potential errors with loadBanks
      try {
        loadBanks();
      } catch (e) {
        console.error("Error loading banks:", e);
        notify('Error!', 'Could not load banks, but you can still proceed.', 'warning');
      }
    }
    
    function loadBanks(){
      var getBanks = "getBanks";
      $.ajax({
        type: "POST",
        url: "residenceReportController.php",  
        data: {
          GetBanks: getBanks
        },
        success: function (response) {
          try {
            var banks = JSON.parse(response);
            var bankSelect = $('#bankSelector');
            bankSelect.empty();
            bankSelect.append('<option value="">-- Select Bank --</option>');
            
            if (banks && banks.length > 0) {
              for(var i = 0; i < banks.length; i++){
                bankSelect.append('<option value="' + banks[i].id + '">' + banks[i].bank_name + '</option>');
              }
            } else {
              bankSelect.append('<option value="default">Default Bank</option>');
            }
          } catch (e) {
            console.error("Error processing banks:", e);
            $('#bankSelector').empty().append('<option value="">-- No Banks Available --</option>');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX error loading banks:", status, error);
          $('#bankSelector').empty().append('<option value="">-- Could Not Load Banks --</option>');
        }
      });
    }
    
    function generateSalaryCertificate(){
      var bankId = $('#bankSelector').val();
      if (!bankId) bankId = "default"; // Fallback value
      
      var residenceId = $('#salaryCertResidenceID').val();
      
      window.open('printLetter.php?id=' + residenceId + '&type=salary_certificate&bank_id=' + bankId, '_blank');
      $('#bankSelectorModal').modal('hide');
    }
</script>

<!-- Bank Selector Modal for Salary Certificate -->
<div class="modal fade" id="bankSelectorModal" tabindex="-1" role="dialog" aria-labelledby="bankSelectorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="bankSelectorModalLabel">Select Bank for Salary Certificate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="salaryCertResidenceID">
        <div class="form-group">
          <label for="bankSelector">Bank:</label>
          <select class="form-control" id="bankSelector">
            <option value="">-- Select Bank --</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" onclick="generateSalaryCertificate()">Generate Certificate</button>
      </div>
    </div>
  </div>
</div>
</body>
</html>