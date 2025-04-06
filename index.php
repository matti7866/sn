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
$sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'pending Tasks' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$PT_Select = $records[0]['select'];


/// upcoming cheques
$sql = "SELECT * FROM `cheques` WHERE `date` >= CURDATE() AND `date` <= CURDATE() + INTERVAL 15 DAY";
$stmt = $pdo->prepare($sql);

$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$upcomingCheques = $records;

?>
<link href='index.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
<style>
  .apexcharts-tooltip {
    color: black;
  }

  ,
</style>
<?php
include 'nav.php';
?>
<!-- Quick Links -->
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="Ticket.php">Ticket</a></li>
  <li class="breadcrumb-item"><a href="visa.php">Visa</a></li>
  <li class="breadcrumb-item"><a href="pending_payments.php">Customer Ledger</a></li>
  <li class="breadcrumb-item"><a href="pending_supplier.php">Supplier Ledger</a></li>
</ol>
<h1 class="page-header mb-3">Dashboard v1.2</h1>
<!-- End Quick Links -->

<!-- Todays Report -->
<?php if ($role_name == 'Admin') { ?>
  <div class="row" id="DailyRpt">
  </div>
<?php } ?>
<!-- Beginning of daily entry report -->
<?php if ($role_name == 'Admin') { ?>
  <div class="row mb-3">
    <div class="col-12">
      <div class="card border-0 mb-3 bg-gray-800 text-white   " data-scrollbar="true" data-height="100%" style="height: 100%;">
        <div class="card-body">
          <div class="row">
            <h2 class="text-center">Daily Entries Report <i class="fas fa-clone text-red"></i></h2>
            <div class="col-lg-2">
              <label for="staticEmail2">From: </label>&nbsp;&nbsp;<br />
              <input type="text" class="form-control" id="fromdate">&nbsp;
            </div>
            <div class="col-lg-2">
              <label for="staticEmail2">To:</label>&nbsp;
              <input type="text" class="form-control" id="todate">&nbsp;
            </div>
            <div class="col-lg-2">

              <button class="btn btn-red mt-3" onclick="getDailyEntryReport()"><i class="fa fa-search"></i> Search</button>
            </div>

            <div class="table-responsive">
              <table class="table mb-0  table-dark table-hover table-striped ">
                <thead>
                  <tr class="bg-dark text-white" style="font-size:13px; font-weight: 600; line-height: 35px;min-height: 35px;height: 35px;">
                    <th>#</th>
                    <th>Entry Type</th>
                    <th>Customer Name</th>
                    <th>Passenger Name</th>
                    <th>Entry Details</th>
                    <th>Date Time</th>
                    <th>Entry By</th>
                  </tr>
                </thead>
                <tbody id="dailyEntryTable" style="font-size:12px; font-weight: 600;">

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<!-- End of daily entry report  -->
<!-- Pending Tasks Report -->
<?php if ($PT_Select == 1) { ?>
  <!-- <div class="row mb-3">
        <div class="col-lg">
          <div id='calendar' ></div>
        </div>
      </div> -->
<?php } ?>


<!-- End of Pending Tasks Report -->
<!-- End Todays Report -->
<!-- Date Report  -->
<!-- <div class="row" id="dateRpt">
        <div class="col-xl-6">
            <div class="card border-0 mb-3 overflow-hidden bg-gray-800 text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-7 col-lg-8">
                            <div class="mb-3 text-gray-500">
                                <b>TOTAL SALES</b>
                                <span class="ms-2">
                                    <i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total sales" data-bs-placement="top" data-bs-content="Total sales are calculated based on profit of Visa added with profit of Ticket that are sold over particular period."></i>
                                </span>
                            </div>
                        <div class="d-flex mb-1">
                            <h2 class="mb-0">$<span data-animation="number" id="total_tkVisa" data-value="0">0</span></h2>
                            <div class="ms-auto mt-n1 mb-n1"><div style="margin-top:-30px; margin-bottom:-28px"  id="apex-line-chart"></div></div>
                        </div>
                        <div class="mb-3 text-gray-500">
                             Profit of ticket and visa is calculated
                        </div>
                        <hr class="bg-white-transparent-5" />
                        <div class="row text-truncate">
                            <div class="col-6">
                                <div class="fs-12px text-gray-500">Total ticket sales</div>
                                    <div class="fs-18px mb-5px fw-bold">$ <span data-animation="number" id="total_ticket" data-value="0">0</span></div>
                                    <div class="progress h-5px rounded-3 bg-gray-900 mb-5px">
                                        <div class="progress-bar progress-bar-striped rounded-right bg-teal" data-animation="width" id="total_ticketProg" data-value="0%" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="fs-12px text-gray-500">Total visa sales</div>
                                    <div class="fs-18px mb-5px fw-bold">$ <span data-animation="number" id="total_visa" data-value="0">0</span></div>
                                    <div class="progress h-5px rounded-3 bg-gray-900 mb-5px">
                                        <div class="progress-bar progress-bar-striped rounded-right" id="total_visaProg" data-animation="width" data-value="0%" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-4 align-items-center d-flex justify-content-center">
                            <img src="flights.jpg" height="150px" class="d-none d-lg-block rounded" />
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
<!-- Second Trans -->
<!-- <div class="col-xl-6">
          <div class="row">
            <div class="col-sm-6">
              <div class="card border-0 text-truncate mb-3 bg-gray-800 text-white">
                <div class="card-body">
                  <div class="mb-3 text-gray-500">
                    <b class="mb-3">Total Expenses</b>
                    <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total Expenses" data-bs-placement="top" data-bs-content="Total expenses are calculated based on number of purchases done during the specified time stamp" data-original-title="" title=""></i></span>
                  </div>
                  <div class="d-flex align-items-center mb-1">
                    <h2 class="text-white mb-0"><span data-animation="number" id="totalexpenses" data-value="0">0</span></h2>
                    <div class="ms-auto">
                    <div class="ms-auto mt-n1 mb-n1"><div style="margin-top:-30px; margin-bottom:-37px"  id="expenses-sparkline"></div></div>
                    </div>
                  </div>
                  <div class="mb-4 text-gray-500 ">
                     Expenses calculated over specified time interval
                  </div>
                  
                  <div id="topThreePro" style="margin-bottom:17px"></div>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="card border-0 text-truncate mb-3 bg-gray-800 text-white">
                <div class="card-body">
                  <div class="mb-3 text-gray-500">
                    <b class="mb-3">Total Profit</b>
                    <span class="ms-2"><i class="fa fa-info-circle" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-title="Total Profit" data-bs-placement="top" data-bs-content="Total profit is calculated by total of visa and ticket profit minus expenses over specified time interval" data-original-title="" title=""></i></span>
                  </div>
                  <div class="d-flex align-items-center mb-1">
                    <h2 class="text-white mb-0"><span data-animation="number" id="totalProfit" data-value="0">0</span></h2>
                    <div class="ms-auto">
                    <div class="ms-auto mt-n1 mb-n1"><div style="margin-top:-30px; margin-bottom:-37px"  id="profit-sparkline"></div></div>
                    </div>
                  </div>
                  <div class="mb-4 text-gray-500 ">
                     Comparsion based on total of visa and ticket minus total of expenses 
                  </div>
                  <div class="d-flex mb-2">
                    <div class="d-flex align-items-center">
                      <i class="fa fa-circle text-teal fs-8px me-2"></i>
                        Total Ticket & Visa profit
                    </div>
                    <div class="d-flex align-items-center ms-auto">
                      <div class="text-gray-500 fs-11px"><span class="caret_def"></span> <span data-animation="number" id="totalVTPPer" data-value="0">0</span>%</div>
                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="totalVTP"  data-value="0">0</span></div>
                    </div>
                  </div>
                  <div class="d-flex mb-2">
                    <div class="d-flex align-items-center">
                      <i class="fa fa-circle text-blue fs-8px me-2"></i>
                        Total Expenses
                    </div>
                    <div class="d-flex align-items-center ms-auto">
                      <div class="text-gray-500 fs-11px"><span class="caret_def"></span> <span data-animation="number" id="tExpensesPer" data-value="0">0</span>%</div>
                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="tExpenses" data-value="0">0</span></div>
                    </div>
                  </div>
                  <div class="d-flex">
                    <div class="d-flex align-items-center">
                      <i class="fa fa-circle text-cyan fs-8px me-2"></i>
                      Total Profit
                    </div>
                    <div class="d-flex align-items-center ms-auto">
                      <div class="text-gray-500 fs-11px"><span class="caret_def"></span> <span data-animation="number" id="tProfitPer" data-value="0"></span>%</div>
                      <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="tProfit" data-value="0">0</span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> -->
<!-- </div>  -->
<!-- End Date Report -->
<!-- Todays & Tommrrow Customers Flight Report -->
<div class="row">
  <div class="col-xl-12">
    <div class="panel panel-inverse" data-sortable-id="index-6">
      <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title">Upcoming Flights</h4>
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-panel align-middle mb-0">
          <thead>
            <tr>
              <th>Pnr</th>
              <th>Ticket</th>
              <th>Customer Name</th>
              <th>Passenger Name</th>
              <th>From</th>
              <th>To</th>
              <th>Dep. Date</th>
            </tr>
          </thead>
          <tbody id="todayFlight">

          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- <div class="col-xl-6">
        <div class="panel panel-inverse" data-sortable-id="index-6">
          <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Tommrrow's Flights</h4>
            <div class="panel-heading-btn">
              <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
              <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-panel align-middle mb-0">
              <thead>
                <tr>
                  <th>Pnr</th>
                  <th>Ticket</th>
                  <th>Customer Name</th>
                  <th>Passenger Name</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Remark</th>
                </tr>
              </thead>
              <tbody id="tommrowFlight">
                
              </tbody>
            </table>
          </div>
        </div>
      </div> -->
</div>

<div class="row">
  <div class="col-xl-6">
    <div class="panel panel-inverse" data-sortable-id="index-6">
      <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title">Upcoming Cheques</h4>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-panel align-middle mb-0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Number</th>
              <th>Type</th>
              <th>Payee</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody id="upcomingCheques">
            <?php
            if (count($upcomingCheques)) {
              foreach ($upcomingCheques as $key => $row) {
                echo '<tr>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['number'] . '</td>';
                echo '<td>' . ($row['type'] == 'payable' ? '<span class="badge bg-danger">Payable</span>' : '<span class="badge bg-success">Receivable</span>') . '</td>';
                echo '<td>' . $row['payee'] . '</td>';
                echo '<td>' . number_format($row['amount']) . '</td>';
                echo '</tr>';
              }
            } else {
              echo '<tr><td colspan="5" class="text-center">No records found</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>



  <!-- Insert Modal Dialog -->
  <div class="modal fade" id="InsertModalDialog">
    <div class="modal-dialog-centered modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Plan</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <form>
            <input type="hidden" id="dt">
            <div class="row mb-3">
              <label class="form-label col-form-label col-md-2">Title:</label>
              <div class="col-md-10">
                <input type="text" class="form-control" id="title" placeholder="Event Title" />
              </div>
            </div>
            <div class="row mb-3">
              <label class="form-label col-form-label col-md-2">Description:</label>
              <div class="col-md-10">
                <textarea class="form-control" rows="3" id="description" placeholder="Event Description"></textarea>
              </div>
            </div>
            <div class="row mb-3">
              <label class="form-label col-form-label col-md-2">Assign To:</label>
              <div class="col-md-10">
                <select class="form-select" id="employees"></select>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
          <a href="javascript:;" class="btn btn-danger" onclick="savePlan()">Save Plan</a>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- End Todays Flight -->
  <?php
  include 'footer.php';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
  <!-- <script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/core/index.global.js" type="text/javascript"></script>
<script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/daygrid/index.global.js" type="text/javascript"></script>
<script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/timegrid/index.global.js" type="text/javascript"></script>
<script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/interaction/index.global.js" type="text/javascript"></script>
<script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/list/index.global.js" type="text/javascript"></script>
<script src="color_admin_v5.0/admin/template/assets/plugins/@fullcalendar/bootstrap/index.global.js" type="text/javascript"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
      var calendar;
      getEvents();
      getEmployees();

      $('#fromdate').dateTimePicker();
      $('#todate').dateTimePicker();
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
      $('#fromdate').val(date.getFullYear() + '-' + month + '-' + day);
      $('#todate').val(date.getFullYear() + '-' + month + '-' + day);
      // $("#daterangeFilter").on('DOMSubtreeModified',function(){
      //     $('#daterange-comparsion').text($('#daterangeFilter').text());
      //     if($('#daterangeFilter').text() != ''){
      //       dateRptInfo();
      //     }

      //  });
      <?php if ($role_name == 'Admin') { ?>
        getDailyEntryReport();
        getTodaysInfo();
      <?php } ?>
      <?php if ($PT_Select == 1) { ?>
        getPendingTasks();
      <?php  } ?>
      GetTodaysFlight();
      //GetTommrowsFlight();

    });

    function getTodaysInfo() {
      var select_todaysInfo = "select_todaysInfo";
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          Select_todaysInfo: select_todaysInfo,
        },
        success: function(response) {

          var getTodaysInfo = JSON.parse(response);
          var dailyrpt = $('#DailyRpt');
          dailyrpt.empty();
          var dailyrptTable = "";
          dailyrptTable += "<div class='col-xl-3 col-md-6'><div class='widget widget-stats bg-dark'><div " +
            "class='stats-icon stats-icon-lg'><i class='fa fa-ticket fa-fw'></i></div><div class='stats-content'>" +
            "<div class='stats-title'>Today's Tickets Sale</div><div class='stats-number'>" + getTodaysInfo[0].Todays_Ticket +
            "</div><div class='stats-progress progress'><div class='progress-bar' style='width: " + (getTodaysInfo[0].Todays_Ticket * 100) / getTodaysInfo[0].ticket_profit + "%'></div>" +
            "</div><div class='stats-desc'>Profit: " + getTodaysInfo[0].ticket_profit + "</div></div></div></div>";
          dailyrptTable += "<div class='col-xl-3 col-md-6'><div class='widget widget-stats bg-dark'><div " +
            "class='stats-icon stats-icon-lg'><i class='fa fa-cc-visa fa-fw'></i></div><div class='stats-content'>" +
            "<div class='stats-title'>Today's Visa Sale</div><div class='stats-number'>" + getTodaysInfo[0].Todays_Visa +
            "</div><div class='stats-progress progress'><div class='progress-bar' style='width: " + (getTodaysInfo[0].Todays_Visa * 100) / getTodaysInfo[0].Visa_Profit + "%'></div>" +
            "</div><div class='stats-desc'>Profit: " + getTodaysInfo[0].Visa_Profit + "</div></div></div></div>";
          dailyrptTable += "<div class='col-xl-3 col-md-6'><div class='widget widget-stats bg-dark'><div " +
            "class='stats-icon stats-icon-lg'><i class='fa fa-money fa-fw'></i></div><div class='stats-content'>" +
            "<div class='stats-title'>Today's Expenses</div><div class='stats-number'>" + getTodaysInfo[0].Total_Expense +
            "</div><div class='stats-progress progress'><div class='progress-bar' style='width: " + (getTodaysInfo[0].Total_Expense * 100) / 5000 + "%'></div>" +
            "</div><div class='stats-desc'>Expense: " + getTodaysInfo[0].Total_Expense + "</div></div></div></div>";
          dailyrptTable += "<div class='col-xl-3 col-md-6'><div class='widget widget-stats bg-dark'><div " +
            "class='stats-icon stats-icon-lg'><i class='fas fa-hand-holding-usd fa-fw'></i></div><div class='stats-content'>" +
            "<div class='stats-title'>Today's Profit </div><div class='stats-number'>" + (parseFloat(getTodaysInfo[0].ticket_profit) + parseFloat(getTodaysInfo[0].Visa_Profit) - parseFloat(getTodaysInfo[0].Total_Expense)) +
            "</div><div class='stats-progress progress'><div class='progress-bar' style='width: " + (parseFloat(getTodaysInfo[0].ticket_profit) + parseFloat(getTodaysInfo[0].Visa_Profit) - parseFloat(getTodaysInfo[0].Total_Expense)) + "%'></div>" +
            "</div><div class='stats-desc'>Profit : " + (parseFloat(getTodaysInfo[0].ticket_profit) + parseFloat(getTodaysInfo[0].Visa_Profit) - parseFloat(getTodaysInfo[0].Total_Expense)) + "</div></div></div></div>";
          dailyrpt.append(dailyrptTable);
        },
      });
    }
    // function dateRptInfo(){
    //    var daterange = $('#daterangeFilter').text();
    //    var splitedDate = daterange.split(" ");
    //    var fromdate = splitedDate[2] + "-" + getDateNumber(splitedDate[1]) + "-" + splitedDate[0];
    //    var todate = splitedDate[6] + "-" + getDateNumber(splitedDate[5]) + "-" + splitedDate[4];
    //    var select_RangeTikVisaInfo = "select_RangeTikVisaInfo";
    //     $.ajax({
    //         type: "POST",
    //         url: "indexController.php",  
    //         data: {
    //           Select_RangeTikVisaInfo:select_RangeTikVisaInfo,
    //           Fromdate:fromdate,
    //           Todate:todate
    //         },
    //         success: function (response) {  
    //             var dateRptInfo = JSON.parse(response);

    //             var dataArr = [];
    //             for(var i = 0; i< dateRptInfo.length; i++){
    //                dataArr.push(parseInt(dateRptInfo[i].total));
    //             }
    //             var total = parseFloat(dateRptInfo[0].TicketProfit) + parseFloat(dateRptInfo[0].VisaProfit);
    //             var totalTicket = parseFloat(dateRptInfo[0].TicketProfit);
    //             var totalVisa = parseFloat(dateRptInfo[0].VisaProfit);
    //             setElmentAttribute($('#total_tkVisa'),total);
    //             setElmentAttribute($('#total_ticket'),totalTicket);
    //             setElmentAttribute($('#total_visa'),totalVisa);
    //             setProgressAttribute($('#total_ticketProg'), Math.floor(totalTicket * 100 /total));
    //             setProgressAttribute($('#total_visaProg'), Math.floor(totalVisa * 100 /total));
    //             if(total != 0){
    //               var getLineForTikVisa = "getLineForTikVisa";
    //               $.ajax({
    //                 type: "POST",
    //                 url: "indexController.php",  
    //                 data: {
    //                   GetLineForTikVisa:getLineForTikVisa,
    //                   Fromdate:fromdate,
    //                   Todate:todate
    //                 },
    //                 success: function (response) {  
    //                   var dateRptInfo = JSON.parse(response);
    //                   var dataArr = [];
    //                   for(var i = 0; i< dateRptInfo.length; i++){
    //                     dataArr.push(parseInt(dateRptInfo[i].total));
    //                   }
    //                   drawDataLine('Total Sales',dataArr,['#552586'],document.querySelector("#apex-line-chart"));
    //                 },
    //                 });
    //             }
    //             getTotalExpenses(fromdate,todate);
    //         },
    //     });
    // }
    // function setElmentAttribute(el,no){
    //   el.attr("data-value", no);
    //   animateNumber(el,el.attr("data-value"),1000);
    // }
    // function setProgressAttribute(el,no){
    //   el.attr("data-value", no +'%');
    //   animateProgress(el,no,1000);
    // }
    // function animateNumber(ele,no,stepTime){
    //   $({someValue: 0}).animate({someValue: no}, {
    //         duration: stepTime,
    //         step: function() { // called on every step. Update the element's text with value:
    //             ele.text(Math.floor(this.someValue+1));
    //         },
    //         complete : function(){
    //             ele.text(numeral(no).format('0,0'));
    //         }
    //   });
    // }
    // function animateProgress(ele,no,stepTime){
    //   $({someValue: 0}).animate({someValue: no}, {
    //         duration: stepTime,
    //         step: function() { // called on every step. Update the element's text with value:
    //             ele.width(Math.floor(this.someValue+1) + '%');
    //         },
    //         complete : function(){
    //             ele.width(no + '%');
    //         }
    //   });
    // }
    // function drawDataLine(name,data,color,areaToDraw){
    //   var options = {
    //           series: [{
    //           name: name,
    //           data: data
    //         }],
    //           chart: {
    //           height: 80,
    //           width:200,
    //           type: 'line',
    //           toolbar: { show: false }
    //         }, 
    //         stroke: {
    //           width: 3,
    //           curve: 'smooth'
    //         },
    //         grid: {
    //           show: false,
    //           borderColor: 'black',
    //           strokeDashArray: 0,
    //           position: 'back',
    //           xaxis: {
    //             lines: {
    //               show: false
    //             }
    //           },   
    //           yaxis: {
    //             lines: {
    //               show: false
    //             }
    //           },  
    //           row: {
    //             colors: undefined,
    //             opacity: 0.5
    //           },  
    //           column: {
    //             colors: undefined,
    //             opacity: 0.5
    //           },  
    //           padding: {
    //             top: 0,
    //             right: 0,
    //             bottom: 0,
    //             left: 0
    //           },  
    //         },
    //         legend: {
    //           show: false,
    //         },
    //         fill: {
    //           type: 'gradient',
    //           gradient: {
    //             shade: 'dark',
    //             gradientToColors: color,
    //             shadeIntensity: 1,
    //             type: 'horizontal',
    //             opacityFrom: 1,
    //             opacityTo: 1,
    //             stops: [0, 100, 100, 100]
    //           },
    //         },
    //         yaxis: {
    //           show: false,
    //         },
    //         xaxis:{
    //           categories: [],
    //           labels: {
    //             show:false,
    //           },
    //           axisBorder: {
    //             show: false,
    //           },
    //           axisTicks: {
    //             show: false,
    //           },
    //         },
    //         };

    //         var chart = new ApexCharts(areaToDraw, options);
    //         chart.render();

    // }
    // function getDateNumber(month){
    //   if(month == "January"){
    //       return "01";
    //   }else if(month == "February"){
    //     return "02";
    //   }else if(month == "March"){
    //     return "03";
    //   }else if(month == "April"){
    //     return "04";
    //   }else if(month == "May"){
    //     return "05";
    //   }else if(month == "June"){
    //     return "06";
    //   }else if(month == "July"){
    //     return "07";
    //   }else if(month == "August"){
    //     return "08";
    //   }else if(month == "September"){
    //     return "09";
    //   }else if(month == "October"){
    //     return "10";
    //   }else if(month == "November"){
    //     return "11";
    //   }else if(month == "December"){
    //     return "12";
    //   }
    // }
    // function getTotalExpenses(fromdate,todate){
    //   var getTotalExpenses = "getTotalExpenses";
    //     $.ajax({
    //         type: "POST",
    //         url: "indexController.php",  
    //         data: {
    //           GetTotalExpenses:getTotalExpenses,
    //           Fromdate:fromdate,
    //           Todate:todate
    //         },
    //         success: function (response) {  
    //             var dateRptInfo = JSON.parse(response);
    //             var dataArr = [];
    //             var total = 0;
    //             for(var i = 0; i< dateRptInfo.length; i++){
    //                dataArr.push(parseInt(dateRptInfo[i].Total));
    //                total += parseInt(dateRptInfo[i].Total);
    //             }
    //             drawDataLine('Total Expenses',dataArr,['#FD7F2C'],document.querySelector("#expenses-sparkline"));
    //             setElmentAttribute($('#totalexpenses'),total);
    //             getTopThreeProd(fromdate,todate,total);
    //         },
    //     });
    // }
    // function getTopThreeProd(fromdate,todate,total){
    //   var getTopThreeProd = "getTopThreeProd";
    //     $.ajax({
    //         type: "POST",
    //         url: "indexController.php",  
    //         data: {
    //           GetTopThreeProd:getTopThreeProd,
    //           Fromdate:fromdate,
    //           Todate:todate
    //         },
    //         success: function (response) {  
    //             var dateRptInfo = JSON.parse(response);
    //             setElmentAttribute($('#totalexpenses'),total);
    //             var topThreePro = $('#topThreePro');
    //             var colors = ['text-red','text-warning','text-lime'];
    //             topThreePro.empty();
    //             var carot = "";
    //             if(dateRptInfo.length == 0){
    //               for(var i = 0; i< colors.length; i++){
    //                 topThreePro.append(`<div class="d-flex " >
    //                     <div class="d-flex align-items-center"  >
    //                       <i class="fa fa-circle ${colors[i]} fs-8px me-2"></i>
    //                        No purchased has made
    //                     </div>
    //                     <div class="d-flex align-items-center ms-auto">

    //                     <div class="text-gray-500 fs-11px"> <i class="fa fa-caret-down"></i> <span data-animation="number" id="topProPer${i}" data-value="0">0</span>%</div>
    //                       <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="topProValue${i}" data-value="0">0</span></div>
    //                     </div>
    //                   </div>`);
    //               }
    //             }else{
    //               for(var i = 0; i< 3; i++){
    //               if($('#topProValue'+i) <= 0){
    //                 carot = '<i class="fa fa-caret-down"></i>';
    //               }else{
    //                 carot = '<i class="fa fa-caret-up"></i>';
    //               }
    //               if(dateRptInfo[i]){
    //                 topThreePro.append(`<div class="d-flex " >
    //                     <div class="d-flex align-items-center"  >
    //                       <i class="fa fa-circle ${colors[i]} fs-8px me-2"></i>
    //                        ${dateRptInfo[i].expense_type}
    //                     </div>
    //                     <div class="d-flex align-items-center ms-auto">
    //                     <div class="text-gray-500 fs-11px"> ${carot} <span data-animation="number" id="topProPer${i}" data-value="0">0</span>%</div>
    //                       <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="topProValue${i}" data-value="0">0</span></div>

    //                     </div>
    //                   </div>`);
    //                   setElmentAttribute($('#topProValue'+i),dateRptInfo[i].productLvlTotal);
    //                   setElmentAttribute($('#topProPer'+i),dateRptInfo[i].productLvlTotal * 100/total);
    //               }else{
    //                 topThreePro.append(`<div class="d-flex " >
    //                     <div class="d-flex align-items-center"  >
    //                       <i class="fa fa-circle ${colors[i]} fs-8px me-2"></i>
    //                        No Data Availlable
    //                     </div>
    //                     <div class="d-flex align-items-center ms-auto">
    //                     <div class="text-gray-500 fs-11px"> <i class="fa fa-caret-down"></i> <span data-animation="number" id="topProPer${i}" data-value="0">0</span>%</div>
    //                       <div class="w-50px text-end ps-2 fw-bold"><span data-animation="number" id="topProValue${i}" data-value="0">0</span></div>

    //                     </div>
    //                   </div>`);
    //                   setElmentAttribute($('#topProValue'+i),0);
    //                   setElmentAttribute($('#topProPer'+i),0 * 100/total);
    //               }

    //                 }
    //             }
    //             getTotalProfit();
    //         },
    //     });
    // }
    // function getTotalProfit(){
    //    var totalTicket = parseInt($('#total_ticket').attr("data-value"));
    //    var totalVisa = parseInt($('#total_visa').attr("data-value"));
    //    var totalexpenses = parseInt($('#totalexpenses').attr("data-value"));
    //    var totalProfit = totalVisa + totalTicket - totalexpenses;
    //    var totalTicketVisa = totalTicket + totalVisa;
    //    var sumofAll = totalexpenses + totalTicketVisa;
    //    setElmentAttribute($('#totalProfit'),totalProfit);
    //    drawDataLine('Total Profit',[totalTicketVisa,totalexpenses,totalProfit],['#09EBEE'],document.querySelector("#profit-sparkline"));
    //    setElmentAttribute($('#totalVTP'),totalTicketVisa);
    //    setElmentAttribute($('#totalVTPPer'),totalTicketVisa * 100/sumofAll);
    //    setElmentAttribute($('#tExpenses'),totalexpenses);
    //    setElmentAttribute($('#tExpensesPer'), totalexpenses * 100/sumofAll);
    //    setElmentAttribute($('#tExpenses'),totalexpenses);
    //    setElmentAttribute($('#tExpensesPer'), totalexpenses * 100/sumofAll);
    //    setElmentAttribute($('#tProfit'),totalProfit);
    //    setElmentAttribute($('#tProfitPer'), totalProfit * 100/sumofAll);
    //    $('.caret_def').each(function(i, obj) {
    //     if(i == 0){
    //       if(totalTicketVisa <0){
    //         obj.innerHTML = '<i class="fa fa-caret-down"></i>';
    //       }else{
    //         obj.innerHTML = '<i class="fa fa-caret-up"></i>';
    //       }
    //     }else if(i == 1){
    //       if(totalexpenses <0){
    //         obj.innerHTML ='<i class="fa fa-caret-down"></i>';
    //       }else{
    //         obj.innerHTML = '<i class="fa fa-caret-up"></i>';
    //       }
    //     }else if(i == 2){
    //       if(totalProfit <0){
    //         obj.innerHTML = '<i class="fa fa-caret-down"></i>';
    //       }else{
    //         obj.innerHTML = '<i class="fa fa-caret-up"></i>';
    //       }
    //     }
    //   }); 
    // }
    function GetTodaysFlight() {
      var GetTodaysFlight = "GetTodaysFlight";
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          GetTodaysFlight: GetTodaysFlight
        },
        success: function(response) {
          var dateRptInfo = JSON.parse(response);
          var todayFlight = $('#todayFlight');
          var bgcolors = ['bg-danger', 'bg-warning', 'bg-success', 'bg-info', 'bg-blue', 'bg-default', 'bg-inverse'];

          for (var i = 0; i < dateRptInfo.length; i++) {
            if (i == bgcolors.length) {
              bgcolors.push('bg-danger', 'bg-warning', 'bg-success', 'bg-info', 'bg-blue', 'bg-default', 'bg-inverse');
            }

            todayFlight.append(`<tr>
                  <td nowrap=""><label class="badge ${bgcolors[i]}">${dateRptInfo[i].Pnr}</label></td>
                  <td>${dateRptInfo[i].ticketNumber}</td>
                  <td>${dateRptInfo[i].customer_name}</td>
                  <td>${dateRptInfo[i].passenger_name}</td>
                  <td>${dateRptInfo[i].from_place}</td>
                  <td>${dateRptInfo[i].to_place}</td>
                  <td>${dateRptInfo[i].date_of_travel}</td>
                </tr>`);
          }
        },
      });
    }

    function GetTommrowsFlight() {
      var GetTommrowsFlight = "GetTommrowsFlight";
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          GetTommrowsFlight: GetTommrowsFlight
        },
        success: function(response) {
          var dateRptInfo = JSON.parse(response);
          var tommrowFlight = $('#tommrowFlight');
          var bgcolors = ['bg-inverse', 'bg-blue', 'bg-info', 'bg-success', 'bg-warning', 'bg-danger'];
          for (var i = 0; i < dateRptInfo.length; i++) {
            if (i == bgcolors.length) {
              bgcolors.push('bg-inverse', 'bg-blue', 'bg-info', 'bg-success', 'bg-warning', 'bg-danger');
            }
            tommrowFlight.append(`<tr>
                  <td nowrap=""><label class="badge ${bgcolors[i]}">${dateRptInfo[i].Pnr}</label></td>
                  <td>${dateRptInfo[i].ticketNumber}</td>
                  <td>${dateRptInfo[i].customer_name}</td>
                  <td>${dateRptInfo[i].passenger_name}</td>
                  <td>${dateRptInfo[i].from_place}</td>
                  <td>${dateRptInfo[i].to_place}</td>
                  <td>${dateRptInfo[i].remarks}</td>
                </tr>`);
          }
        },
      });
    }

    function getPendingTasks() {
      var getPendingTasks = "getPendingTasks";
      $.ajax({
        type: "POST",
        url: "pendingTaskController.php",
        data: {
          GetPendingTasks: getPendingTasks
        },
        success: function(response) {
          var tasks = JSON.parse(response);
          var tasksTable = $('#tasksTable');
          tasksTable.empty();
          if (tasks.length != 0) {
            var dateStatus = '';
            for (var i = 0; i < tasks.length; i++) {
              tasks[i].dateStatus == 'day(s) remaining' ? dateStatus = `<button type="button" class="btn btn-blue"><span class="fa fa-clock-o"></span> ${tasks[i].daysRemining} ${tasks[i].dateStatus}</button>` :
                dateStatus = `<button type="button" class="btn btn-red"><span class="fa fa-clock-o"></span> ${tasks[i].daysRemining} ${tasks[i].dateStatus}</span>`;
              tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td >${i+1}</td><td>
                        ${tasks[i].task_name}</td><td>${tasks[i].task_description}</td>
                        <td>${tasks[i].AssigedTo}</td><td>${tasks[i].task_date}</td><td>${tasks[i].AssignedBy}</td>
                        <td><button type="button" class="btn btn-warning"><span class="fa fa-spinner"></span> ${tasks[i].status}</button></td><td>${dateStatus}</td><td><button type="button"
                        onclick="taskCompleted(${tasks[i].task_id}, ' ${tasks[i].task_name} ')" class="btn btn-danger"><span class="fa fa-check"></span> Completed</button></td></tr>`);
            }
          } else {
            tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td></td><td></td><td></td><td></td>
                    <td></td><td>No Pending Tasks</td><td></td><td></td><td></td></tr>`);

          }

        },
      });
    }

    function getDailyEntryReport() {

      var getDailyEntryReport = "getDailyEntryReport";
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          GetDailyEntryReport: getDailyEntryReport,
          FromDate: fromdate.val(),
          ToDate: todate.val()
        },
        success: function(response) {
          var dailyEntryRpt = JSON.parse(response);
          var dailyEntryTable = $('#dailyEntryTable');
          if (dailyEntryRpt.length != 0) {
            dailyEntryTable.empty();
            var finalTable = "";
            var j = 1;
            for (var i = 0; i < dailyEntryRpt.length; i++) {
              finalTable += '<tr><td>' + j + '</td><td>' + dailyEntryRpt[i].EntryType + '</td><td>' +
                dailyEntryRpt[i].customer_name + '</td><td>' + dailyEntryRpt[i].passenger_name + '</td><td>' +
                dailyEntryRpt[i].Details + '</td><td>' + dailyEntryRpt[i].datetime + '</td><td>' + dailyEntryRpt[i].staff_name +
                '</td></tr>';
              j++;
            }
            dailyEntryTable.append(finalTable);
          } else {
            dailyEntryTable.empty();
            dailyEntryTable.append('<tr><td></td><td></td><td></td><td>No Record...</td><td></td><td></td><td></td></tr>')
          }

        },
      });
    }

    function taskCompleted(id, task_title) {
      var completeTask = "completeTask";
      $.confirm({
        title: 'Complete!',
        content: 'Are you sure, task with title ' + task_title + ' completed?',
        type: 'red',
        typeAnimated: true,
        buttons: {
          tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function() {
              $.ajax({
                type: "POST",
                url: "pendingTaskController.php",
                data: {
                  CompleteTask: completeTask,
                  ID: id
                },
                success: function(response) {
                  if (response == "Success") {
                    notify('Success!', "Task completed successfully", 'success');
                    getPendingTasks();
                  } else {
                    notify('Error!', response, 'error');
                  }
                },
              });
            }
          },
          close: function() {}
        }
      });
    }
    // get employees
    function getEmployees() {
      var select_employees = "select_employees";
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          Select_Employees: select_employees,
        },
        success: function(response) {
          var employees = JSON.parse(response);
          $('#employees').empty();
          $('#employees').append("<option value=''>--Select Employees--</option>");
          for (var i = 0; i < employees.length; i++) {
            $('#employees').append("<option value='" + employees[i].staff_id + "'>" +
              employees[i].staff_name + "</option>");
          }
        },
      });
    }
    // save the plan
    function savePlan() {
      const savePlan = "savePlan";
      let title = $('#title');
      if (title.val() == '') {
        notify('Validation Error!', 'Title for plan is required', 'error');
        return;
      }
      let description = $('#description');
      let employees = $('#employees');
      const dt = $('#dt').val();
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          SavePlan: savePlan,
          Title: title.val(),
          Description: description.val(),
          Employees: employees.val(),
          DT: dt
        },
        success: function(response) {
          if (response == "Success") {
            notify('Success!', 'Plan successfully Created!', 'success');
            title.val('');
            description.val('');
            $('#employees').prop("selectedIndex", 0);
            $('#InsertModalDialog').modal('hide');


            getEvents();
          } else {
            notify('Error!', response, 'error');
          }

        },
      });
    }

    function getEvents() {
      var getEvents = "getEvents";
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          GetEvents: getEvents
        },
        success: function(response) {
          var ParsedEvents = JSON.parse(response);
          var events = new Array();
          var colorFormat = '';
          let date = '';
          for (let i = 0; i < ParsedEvents.length; i++) {
            currentDt = new Date(getCurrentDate());
            serverDt = new Date(ParsedEvents[i].eventDate);
            dtDifference = currentDt - serverDt;
            dayDiff = dtDifference / (1000 * 60 * 60 * 24);
            if (serverDt < currentDt) {


              if (dayDiff == 1 || dayDiff == 2) {
                colorFormat = '#ffe000';
              } else if (dayDiff < 5 && dayDiff > 2) {
                colorFormat = '#ffb75e';
              } else {
                colorFormat = '#ff0000';
              }

            } else {
              if (dayDiff == 0) {
                colorFormat = '#ff0084';
              } else if (dayDiff == -1 || dayDiff == -2 || dayDiff == -3 || dayDiff == -4 || dayDiff == -5) {
                colorFormat = '#3788d8';
              } else {
                colorFormat = '#52c234';
              }

            }

            events.push({
              id: ParsedEvents[i].id,
              title: ParsedEvents[i].title.initCap(),
              start: ParsedEvents[i].eventDate,
              description: ParsedEvents[i].description,
              assignedBy: ParsedEvents[i].assignedBy,
              FormattedeventDate: ParsedEvents[i].FormattedeventDate,
              color: colorFormat
            });

          }
          if ($('#calendar').children().length > 0) {
            calendar.destroy()
          }

          var calendarEl = document.getElementById('calendar');
          calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 450,
            selectable: true,
            selectHelper: true,
            themeSystem: 'bootstrap5',
            titleFormat: {
              year: 'numeric',
              month: 'long',
              day: '2-digit'
            },
            events: events,
            select: function(start, end) {
              $('#dt').val(start.startStr);
              $('#InsertModalDialog').modal('show');
            },
            eventClick: function(info) {
              info.jsEvent.preventDefault(); // don't let the browser navigate
              Swal.fire({
                title: info.event.title,
                html: `
                          <h4>${info.event.extendedProps.description}</h4>,
                          <hr/>
                          <div class='d-flex justify-content-between'> <p>${info.event.extendedProps.FormattedeventDate}</p>
                          <p>${info.event.extendedProps.assignedBy}</p> </div>
                          <hr/>
                        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Completed"
              }).then((result) => {
                if (result.isConfirmed) {
                  eventCompleted(info.event.id);

                }
              });
            }
          });
          calendar.render();
        },
      });
    }

    function getCurrentDate() {
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
      return date.getFullYear() + '-' + month + '-' + day;
    }
    String.prototype.initCap = function() {
      return this.toLowerCase().replace(/(?:^|\s)[a-z]/g, function(m) {
        return m.toUpperCase();
      });
    }

    function eventCompleted(id) {
      const eventCompleted = 'eventCompleted';
      $.ajax({
        type: "POST",
        url: "indexController.php",
        data: {
          EventCompleted: eventCompleted,
          ID: id
        },
        success: function(response) {
          if (response == "Success") {
            notify('Success!', 'Plan successfully Completed!', 'success');
            Swal.fire({
              title: 'Plan Completed!',
              text: "Plan successfully Completed!",
              icon: "success"
            });
            getEvents();
          } else {
            notify('Error!', 'Something went wrong!', 'error');
          }
        },
      });
    }
  </script>
  </body>

  </html>