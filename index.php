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

<!-- Staff Check-in/Check-out Button -->
<div class="mb-3">
  <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 12)): ?>
  <button id="recordAttendanceButton" class="btn btn-lg btn-success"><i class="fa fa-clock-o"></i> Record Staff Attendance</button>
  <button id="viewAttendanceButton" class="btn btn-lg btn-primary ms-2"><i class="fa fa-users"></i> View Staff Attendance</button>
  <?php endif; ?>
</div>

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
              <th>Flight #</th>
              <th>Dep. Time</th>
              <th>Arr. Time</th>
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

  <!-- Staff Attendance Modal -->
  <div class="modal fade" id="staffAttendanceModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Staff Attendance Management - <?php echo date('d M Y'); ?> (Dubai Time)</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <!-- Tabs -->
          <ul class="nav nav-tabs mb-3" id="attendanceTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily-content" type="button" role="tab" aria-controls="daily-content" aria-selected="true">Daily Attendance</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="weekly-tab" data-bs-toggle="tab" data-bs-target="#weekly-content" type="button" role="tab" aria-controls="weekly-content" aria-selected="false">Weekly Summary</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly-content" type="button" role="tab" aria-controls="monthly-content" aria-selected="false">Monthly Summary</button>
            </li>
          </ul>
          
          <!-- Tab Content -->
          <div class="tab-content" id="attendanceTabsContent">
            <!-- Daily Attendance Tab -->
            <div class="tab-pane fade show active" id="daily-content" role="tabpanel" aria-labelledby="daily-tab">
              <div class="row mb-3">
                <div class="col-md-4">
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                    <input type="date" class="form-control" id="filterDate" value="<?php echo date('Y-m-d'); ?>">
                    <button class="btn btn-primary" type="button" id="filterDateBtn">Filter</button>
                  </div>
                </div>
                <div class="col-md-8 text-end">
                  <button class="btn btn-success" id="exportDailyPdfBtn">
                    <i class="fa fa-file-pdf-o"></i> Export to A5 PDF
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Staff Name</th>
                      <th>Status</th>
                      <th>Check-in</th>
                      <th>Check-out</th>
                      <th>Break Start</th>
                      <th>Break End</th>
                      <th>Break Duration</th>
                      <th>Total Hours</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="staffAttendanceData">
                    <tr>
                      <td colspan="10" class="text-center">Loading attendance data...</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Weekly Summary Tab -->
            <div class="tab-pane fade" id="weekly-content" role="tabpanel" aria-labelledby="weekly-tab">
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Week</span>
                    <input type="week" class="form-control" id="weekFilter" value="<?php echo date('Y-\WW'); ?>">
                    <button class="btn btn-primary" type="button" id="weekFilterBtn">View Report</button>
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <button class="btn btn-success" id="exportWeeklyBtn">
                    <i class="fa fa-file-excel-o"></i> Export
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th rowspan="2">#</th>
                      <th rowspan="2">Staff Name</th>
                      <th colspan="7" class="text-center" id="weekDatesHeader">Week Days</th>
                      <th rowspan="2">Total Hours</th>
                    </tr>
                    <tr id="weekDayNames">
                      <!-- Will be filled by JavaScript -->
                    </tr>
                  </thead>
                  <tbody id="weeklyReportData">
                    <tr>
                      <td colspan="10" class="text-center">Select a week to view the report</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Monthly Summary Tab -->
            <div class="tab-pane fade" id="monthly-content" role="tabpanel" aria-labelledby="monthly-tab">
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Month</span>
                    <input type="month" class="form-control" id="monthFilter" value="<?php echo date('Y-m'); ?>">
                    <button class="btn btn-primary" type="button" id="monthFilterBtn">View Report</button>
                  </div>
                </div>
                <div class="col-md-6 text-end">
                  <button class="btn btn-success" id="exportMonthlyBtn">
                    <i class="fa fa-file-excel-o"></i> Export
                  </button>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Staff Name</th>
                      <th>Total Days Present</th>
                      <th>Total Hours</th>
                      <th>Total Break Hours</th>
                      <th>Net Hours</th>
                      <th>Daily Average</th>
                    </tr>
                  </thead>
                  <tbody id="monthlyReportData">
                    <tr>
                      <td colspan="7" class="text-center">Select a month to view the report</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="text-muted small text-center mt-2">
            <i class="fa fa-clock-o"></i> All times are displayed in Dubai timezone (UTC+4)
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Record Staff Attendance Modal -->
  <div class="modal fade" id="recordStaffAttendanceModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Record Staff Attendance</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <form id="attendanceForm">
            <div class="mb-3">
              <label for="staffSelect" class="form-label">Select Staff</label>
              <select class="form-select" id="staffSelect" required>
                <option value="">-- Select Staff Member --</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label for="attendanceDate" class="form-label">Date</label>
              <input type="date" class="form-control" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="mb-3">
              <label for="checkInTime" class="form-label">Check-in Time</label>
              <input type="time" class="form-control" id="checkInTime" required>
            </div>
            
            <div class="mb-3">
              <label for="checkOutTime" class="form-label">Check-out Time</label>
              <input type="time" class="form-control" id="checkOutTime">
              <small class="text-muted">Leave empty if staff hasn't checked out yet</small>
            </div>
            
            <div class="mb-3">
              <label class="form-label">Break Time</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Start</span>
                    <input type="time" class="form-control" id="breakStartTime">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">End</span>
                    <input type="time" class="form-control" id="breakEndTime">
                  </div>
                </div>
              </div>
              <small class="text-muted">Break duration will be calculated automatically</small>
            </div>
            
            <div class="mb-3">
              <label for="attendanceNotes" class="form-label">Notes</label>
              <textarea class="form-control" id="attendanceNotes" rows="2"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="saveAttendanceBtn">Save Attendance</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Staff Attendance Modal -->
  <div class="modal fade" id="editAttendanceModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Staff Attendance</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <form id="editAttendanceForm">
            <input type="hidden" id="editAttendanceId">
            <div class="mb-3">
              <label for="editStaffName" class="form-label">Staff Name</label>
              <input type="text" class="form-control" id="editStaffName" readonly>
            </div>
            
            <div class="mb-3">
              <label for="editAttendanceDate" class="form-label">Date</label>
              <input type="date" class="form-control" id="editAttendanceDate" required>
            </div>
            
            <div class="mb-3">
              <label for="editCheckInTime" class="form-label">Check-in Time</label>
              <input type="time" class="form-control" id="editCheckInTime" required>
            </div>
            
            <div class="mb-3">
              <label for="editCheckOutTime" class="form-label">Check-out Time</label>
              <input type="time" class="form-control" id="editCheckOutTime">
            </div>
            
            <div class="mb-3">
              <label class="form-label">Break Time</label>
              <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">Start</span>
                    <input type="time" class="form-control" id="editBreakStartTime">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <span class="input-group-text">End</span>
                    <input type="time" class="form-control" id="editBreakEndTime">
                  </div>
                </div>
              </div>
              <small class="text-muted">Break duration will be calculated automatically</small>
            </div>
            
            <div class="mb-3">
              <label for="editAttendanceNotes" class="form-label">Notes</label>
              <textarea class="form-control" id="editAttendanceNotes" rows="2"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger" id="deleteAttendanceBtn">Delete</button>
          <button type="button" class="btn btn-success" id="updateAttendanceBtn">Update</button>
        </div>
      </div>
    </div>
  </div>

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
                  <td>${dateRptInfo[i].flight_number || 'N/A'}</td>
                  <td>${dateRptInfo[i].departure_time || 'N/A'}</td>
                  <td>${dateRptInfo[i].arrival_time || 'N/A'}</td>
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

  <script>
    // Staff Check-in/Check-out functionality
    $(document).ready(function() {
      console.log("Staff attendance module initializing...");
      
      // Handle record attendance button click
      $('#recordAttendanceButton').on('click', function() {
        console.log("Record attendance button clicked");
        loadStaffList();
        $('#recordStaffAttendanceModal').modal('show');
      });
      
      // Handle view attendance button click
      $('#viewAttendanceButton').on('click', function() {
        console.log("View attendance button clicked");
        fetchStaffAttendance();
        $('#staffAttendanceModal').modal('show');
      });
      
      // Handle filter date button click
      $('#filterDateBtn').on('click', function() {
        fetchStaffAttendance();
      });
      
      // Handle save attendance button click
      $('#saveAttendanceBtn').on('click', function() {
        saveAttendance();
      });
      
      // Handle update attendance button click
      $('#updateAttendanceBtn').on('click', function() {
        updateAttendance();
      });
      
      // Handle delete attendance button click
      $('#deleteAttendanceBtn').on('click', function() {
        deleteAttendance();
      });
    });
    
    // Function to load staff list for dropdown
    function loadStaffList(staffIdToSelect) {
      console.log("Loading staff list...");
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getStaffList"
        },
        success: function(response) {
          console.log("Staff list response received");
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error loading staff list:", data.message);
              notify('Error!', data.message, 'error');
              return;
            }
            
            // Update dropdown with staff list
            const staffSelect = $('#staffSelect');
            staffSelect.empty();
            staffSelect.append('<option value="">-- Select Staff Member --</option>');
            
            $.each(data.staff, function(index, staff) {
              // Double-check to exclude staff IDs 1 and 14
              if (staff.staff_id !== 1 && staff.staff_id !== 14) {
                staffSelect.append(`<option value="${staff.staff_id}">${staff.staff_name}</option>`);
              }
            });
            
            // If staffIdToSelect is provided, select that staff member
            if (staffIdToSelect && staffIdToSelect !== 1 && staffIdToSelect !== 14) {
              staffSelect.val(staffIdToSelect);
            }
          } catch (e) {
            console.error("Error parsing staff list response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to load staff list: ' + error, 'error');
        },
        dataType: "json" // Expect JSON response
      });
    }

    // Function to fetch all staff attendance for the selected date
    function fetchStaffAttendance() {
      console.log("Fetching staff attendance...");
      const selectedDate = $('#filterDate').val() || new Date().toISOString().split('T')[0];
      
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getAttendance",
          date: selectedDate
        },
        success: function(response) {
          console.log("Attendance data response received");
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error fetching attendance:", data.message);
              notify('Error!', data.message, 'error');
              return;
            }
            
            // Filter out staff IDs 1 and 14 if they somehow got included
            const filteredAttendance = data.attendance.filter(staff => 
              staff.staff_id !== 1 && staff.staff_id !== 14);
            
            // Update table with attendance data
            const attendanceTable = $('#staffAttendanceData');
            attendanceTable.empty();
            
            if (filteredAttendance.length === 0) {
              attendanceTable.append('<tr><td colspan="10" class="text-center">No attendance records found for this date.</td></tr>');
              return;
            }
            
            // Populate the table with attendance records
            $.each(filteredAttendance, function(index, record) {
              let totalHours = 'N/A';
              
              if (record.check_in_time && record.check_out_time) {
                // Calculate total hours accounting for break time
                try {
                  const checkIn = new Date("2000/01/01 " + record.check_in_time);
                  const checkOut = new Date("2000/01/01 " + record.check_out_time);
                  
                  if (checkOut < checkIn) {
                    // If checkout is before checkin, add a day to checkout
                    checkOut.setDate(checkOut.getDate() + 1);
                  }
                  
                  let diff = checkOut - checkIn;
                  // Subtract break duration (in minutes)
                  diff -= (record.break_duration || 0) * 60 * 1000;
                  
                  const hours = Math.floor(diff / 3600000);
                  const minutes = Math.floor((diff % 3600000) / 60000);
                  totalHours = hours + 'h ' + minutes + 'm';
                } catch (e) {
                  console.error("Error calculating hours:", e);
                  totalHours = 'Error';
                }
              }
              
              let statusBadge = '';
              if (!record.check_out_time) {
                statusBadge = '<span class="badge bg-warning">On Duty</span>';
              } else {
                statusBadge = '<span class="badge bg-success">Completed</span>';
              }

              // Add break action buttons based on current break status
              let breakButtons = '';
              if (record.id > 0) {
                if (!record.break_start_time && !record.break_end_time) {
                  // No break started yet
                  breakButtons = `<button class="btn btn-sm btn-info start-break" data-id="${record.id}">Start Break</button>`;
                } else if (record.break_start_time && !record.break_end_time) {
                  // Break started but not ended
                  breakButtons = `<button class="btn btn-sm btn-warning end-break" data-id="${record.id}">End Break</button>`;
                } else {
                  // Break completed
                  breakButtons = `<span class="badge bg-success">Break Completed</span>`;
                }
              }
              
              attendanceTable.append(`
                <tr data-id="${record.id}">
                  <td>${index + 1}</td>
                  <td>${record.staff_name}</td>
                  <td>${statusBadge}</td>
                  <td>${record.check_in_time || 'N/A'}</td>
                  <td>${record.check_out_time || 'N/A'}</td>
                  <td>${record.break_start_time || 'N/A'}</td>
                  <td>${record.break_end_time || 'N/A'}</td>
                  <td>${record.break_duration || '0'} min</td>
                  <td>${totalHours}</td>
                  <td>
                    ${record.id > 0 ? 
                      `<div class="btn-group">
                        <button class="btn btn-sm btn-primary edit-attendance" data-id="${record.id}">
                          <i class="fa fa-edit"></i> Edit
                        </button>
                        ${breakButtons}
                       </div>` : 
                      `<button class="btn btn-sm btn-success add-attendance" data-staff-id="${record.staff_id}" data-staff-name="${record.staff_name}">
                        <i class="fa fa-plus"></i> Add
                      </button>`
                    }
                  </td>
                </tr>
              `);
            });
            
            // Add event listener for edit buttons
            $('.edit-attendance').on('click', function() {
              const attendanceId = $(this).data('id');
              editAttendance(attendanceId);
            });
            
            // Add event listener for add buttons
            $('.add-attendance').on('click', function() {
              const staffId = $(this).data('staff-id');
              const staffName = $(this).data('staff-name');
              
              // Load staff list and select the appropriate staff member
              loadStaffList(staffId);
              
              // Show the modal
              $('#recordStaffAttendanceModal').modal('show');
            });

            // Add event listener for start break button
            $('.start-break').on('click', function() {
              const attendanceId = $(this).data('id');
              startBreak(attendanceId);
            });

            // Add event listener for end break button
            $('.end-break').on('click', function() {
              const attendanceId = $(this).data('id');
              endBreak(attendanceId);
            });
            
          } catch (e) {
            console.error("Error parsing attendance data response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to fetch attendance data: ' + error, 'error');
        },
        dataType: "json" // Expect JSON response
      });
    }

    // Function to save new attendance record
    function saveAttendance() {
      const staffId = $('#staffSelect').val();
      const attendanceDate = $('#attendanceDate').val();
      const checkInTime = $('#checkInTime').val();
      const checkOutTime = $('#checkOutTime').val();
      const breakStartTime = $('#breakStartTime').val();
      const breakEndTime = $('#breakEndTime').val();
      const notes = $('#attendanceNotes').val();
      
      if (!staffId || !attendanceDate || !checkInTime) {
        notify('Validation Error!', 'Staff, date and check-in time are required', 'error');
        return;
      }
      
      // Extra check to prevent saving attendance for staff IDs 1 and 14
      if (staffId == 1 || staffId == 14) {
        notify('Validation Error!', 'Cannot record attendance for this staff member', 'error');
        return;
      }
      
      // Remove validation that requires both break start and break end to be provided together
      // This allows recording just the break start time when someone goes on break
      
      console.log("Saving attendance record...");
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "recordAttendance",
          staff_id: staffId,
          date: attendanceDate,
          check_in_time: checkInTime,
          check_out_time: checkOutTime,
          break_start_time: breakStartTime,
          break_end_time: breakEndTime,
          notes: notes
        },
        success: function(response) {
          console.log("Save attendance response received");
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error saving attendance:", data.message);
              notify('Error!', data.message, 'error');
              return;
            }
            
            notify('Success!', 'Attendance record saved successfully', 'success');
            $('#recordStaffAttendanceModal').modal('hide');
            
            // Reset form
            $('#attendanceForm')[0].reset();
            $('#attendanceDate').val(new Date().toISOString().split('T')[0]);
            
            // Refresh attendance table if it's open
            if ($('#staffAttendanceModal').hasClass('show')) {
              fetchStaffAttendance();
            }
          } catch (e) {
            console.error("Error parsing save response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to save attendance: ' + error, 'error');
        },
        dataType: "json" // Expect JSON response
      });
    }

    // Function to load attendance record for editing
    function editAttendance(attendanceId) {
      console.log("Editing attendance record:", attendanceId);
      
      // Find the record in the table
      const row = $(`tr[data-id="${attendanceId}"]`);
      if (row.length) {
        const staffName = row.find('td:eq(1)').text();
        // Check if this is staff ID 1 or 14 (by name)
        if (staffName.trim().toLowerCase() === 'Admin user' || staffName.includes('Faizan Shaikh')) {
          notify('Error!', 'Cannot edit attendance for this staff member', 'error');
          return;
        }
        
        const checkInTime = row.find('td:eq(3)').text() !== 'N/A' ? row.find('td:eq(3)').text() : '';
        const checkOutTime = row.find('td:eq(4)').text() !== 'N/A' ? row.find('td:eq(4)').text() : '';
        const breakStartTime = row.find('td:eq(5)').text() !== 'N/A' ? row.find('td:eq(5)').text() : '';
        const breakEndTime = row.find('td:eq(6)').text() !== 'N/A' ? row.find('td:eq(6)').text() : '';
        
        // Populate the edit form
        $('#editAttendanceId').val(attendanceId);
        $('#editStaffName').val(staffName);
        $('#editAttendanceDate').val($('#filterDate').val() || new Date().toISOString().split('T')[0]);
        $('#editCheckInTime').val(convertTo24Hour(checkInTime));
        $('#editCheckOutTime').val(convertTo24Hour(checkOutTime));
        $('#editBreakStartTime').val(convertTo24Hour(breakStartTime));
        $('#editBreakEndTime').val(convertTo24Hour(breakEndTime));
        
        // Show the edit modal
        $('#editAttendanceModal').modal('show');
      } else {
        // Fetch the record from the server if not in table
        $.ajax({
          type: "POST",
          url: "staffAttendanceController.php",
          data: {
            action: "getAttendanceRecord",
            id: attendanceId
          },
          success: function(response) {
            console.log("Get attendance record response received");
            try {
              // Try to parse the response if it's a string
              const data = typeof response === 'string' ? JSON.parse(response) : response;
              
              if (data.success === false) {
                console.error("Error fetching attendance record:", data.message);
                notify('Error!', data.message, 'error');
                return;
              }
              
              const record = data.record;
              
              // Check if this is staff ID 1 or 14
              if (record.staff_id == 1 || record.staff_id == 14) {
                notify('Error!', 'Cannot edit attendance for this staff member', 'error');
                return;
              }
              
              // Populate the edit form
              $('#editAttendanceId').val(record.id);
              $('#editStaffName').val(record.staff_name);
              $('#editAttendanceDate').val(record.date);
              $('#editCheckInTime').val(record.check_in_time);
              $('#editCheckOutTime').val(record.check_out_time || '');
              $('#editBreakStartTime').val(record.break_start_time || '');
              $('#editBreakEndTime').val(record.break_end_time || '');
              $('#editAttendanceNotes').val(record.notes || '');
              
              // Show the edit modal
              $('#editAttendanceModal').modal('show');
            } catch (e) {
              console.error("Error parsing get record response:", e);
              console.log("Raw response:", response);
              notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.log("Response Text:", xhr.responseText);
            notify('Error!', 'Failed to fetch attendance record: ' + error, 'error');
          },
          dataType: "json" // Expect JSON response
        });
      }
    }

    // Function to update attendance record
    function updateAttendance() {
      const attendanceId = $('#editAttendanceId').val();
      const staffName = $('#editStaffName').val();
      const attendanceDate = $('#editAttendanceDate').val();
      const checkInTime = $('#editCheckInTime').val();
      const checkOutTime = $('#editCheckOutTime').val();
      const breakStartTime = $('#editBreakStartTime').val();
      const breakEndTime = $('#editBreakEndTime').val();
      const notes = $('#editAttendanceNotes').val();
      
      if (!attendanceId || !attendanceDate || !checkInTime) {
        notify('Validation Error!', 'Date and check-in time are required', 'error');
        return;
      }
      
      // Check if this is staff ID 1 or 14 (by name)
      if (staffName.trim().toLowerCase() === 'Admin user' || staffName.includes('Faizan Shaikh')) {
        notify('Error!', 'Cannot update attendance for this staff member', 'error');
        return;
      }
      
      // Remove validation that requires both break start and break end to be provided together
      // This allows recording just the break start time when someone goes on break
      
      console.log("Updating attendance record...");
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "updateAttendance",
          id: attendanceId,
          date: attendanceDate,
          check_in_time: checkInTime,
          check_out_time: checkOutTime,
          break_start_time: breakStartTime,
          break_end_time: breakEndTime,
          notes: notes
        },
        success: function(response) {
          console.log("Update attendance response received");
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error updating attendance:", data.message);
              notify('Error!', data.message, 'error');
              return;
            }
            
            notify('Success!', 'Attendance record updated successfully', 'success');
            $('#editAttendanceModal').modal('hide');
            
            // Refresh attendance table
            fetchStaffAttendance();
          } catch (e) {
            console.error("Error parsing update response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to update attendance: ' + error, 'error');
        },
        dataType: "json" // Expect JSON response
      });
    }

    // Function to delete attendance record
    function deleteAttendance() {
      const attendanceId = $('#editAttendanceId').val();
      const staffName = $('#editStaffName').val();
      
      if (!attendanceId) {
        notify('Error!', 'No attendance record selected', 'error');
        return;
      }
      
      // Check if this is staff ID 1 or 14 (by name)
      if (staffName.trim().toLowerCase() === 'Admin user' || staffName.includes('Faizan Shaikh')) {
        notify('Error!', 'Cannot delete attendance for this staff member', 'error');
        return;
      }
      
      // Confirm deletion
      if (!confirm('Are you sure you want to delete this attendance record?')) {
        return;
      }
      
      console.log("Deleting attendance record...");
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "deleteAttendance",
          id: attendanceId
        },
        success: function(response) {
          console.log("Delete attendance response received");
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error deleting attendance:", data.message);
              notify('Error!', data.message, 'error');
              return;
            }
            
            notify('Success!', 'Attendance record deleted successfully', 'success');
            $('#editAttendanceModal').modal('hide');
            
            // Refresh attendance table
            fetchStaffAttendance();
          } catch (e) {
            console.error("Error parsing delete response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to delete attendance: ' + error, 'error');
        },
        dataType: "json" // Expect JSON response
      });
    }

    // Helper function to convert 12-hour format to 24-hour format for time input fields
    function convertTo24Hour(time12h) {
      if (!time12h || time12h === 'N/A') return '';
      
      const [time, modifier] = time12h.split(' ');
      let [hours, minutes] = time.split(':');
      
      if (hours === '12') {
        hours = '00';
      }
      
      if (modifier === 'PM') {
        hours = parseInt(hours, 10) + 12;
      }
      
      return `${hours}:${minutes}`;
    }

    // Handle tab change events
    $('#attendanceTabs button').on('click', function(e) {
      const targetTab = $(this).attr('id');
      
      // Load data based on selected tab
      if (targetTab === 'daily-tab') {
        fetchStaffAttendance();
      } else if (targetTab === 'weekly-tab') {
        fetchWeeklyReport();
      } else if (targetTab === 'monthly-tab') {
        fetchMonthlyReport();
      }
    });

    // Handle week filter button click
    $('#weekFilterBtn').on('click', function() {
      fetchWeeklyReport();
    });

    // Handle month filter button click
    $('#monthFilterBtn').on('click', function() {
      fetchMonthlyReport();
    });

    // Set default value for week filter
    function updateWeekFilterDefault() {
      const now = new Date();
      const year = now.getFullYear();
      const weekNum = getWeekNumber(now);
      // Format is YYYY-Www for HTML week input
      const weekStr = year + '-W' + (weekNum < 10 ? '0' + weekNum : weekNum);
      $('#weekFilter').val(weekStr);
    }

    // Function to fetch weekly report data
    function fetchWeeklyReport() {
      const weekValue = $('#weekFilter').val();
      
      if (!weekValue) {
        notify('Error!', 'Please select a valid week', 'error');
        return;
      }
      
      // Parse year and week number - expected format YYYY-Www
      const [year, weekWithW] = weekValue.split('-');
      const week = weekWithW.substring(1); // Remove the 'W' prefix
      
      console.log("Fetching weekly report for", year, "week", week);
      
      // Show loading message
      $('#weeklyReportData').html('<tr><td colspan="10" class="text-center"><i class="fa fa-spin fa-spinner"></i> Loading weekly data...</td></tr>');
      
      // Create week day headers
      createWeekDayHeaders(year, parseInt(week));
      
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getWeeklyReport",
          year: year,
          week: week
        },
        success: function(response) {
          console.log("Weekly report raw response:", response);
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error fetching weekly report:", data.message);
              notify('Error!', data.message, 'error');
              $('#weeklyReportData').html('<tr><td colspan="10" class="text-center text-danger">Error: ' + data.message + '</td></tr>');
              return;
            }
            
            // Filter out staff IDs 1 and 14
            const filteredData = data.weeklyData.filter(staff => 
              staff.staff_id !== 1 && staff.staff_id !== 14);
            
            // Populate weekly report table
            displayWeeklyReport(filteredData, data.dates);
            
          } catch (e) {
            console.error("Error parsing weekly report response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
            $('#weeklyReportData').html('<tr><td colspan="10" class="text-center text-danger">Error parsing response</td></tr>');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to fetch weekly report: ' + error, 'error');
          $('#weeklyReportData').html('<tr><td colspan="10" class="text-center text-danger">Error: ' + error + '</td></tr>');
        },
        dataType: "json"
      });
    }

    // Function to create week day headers
    function createWeekDayHeaders(year, weekNum) {
      // Create start date for the week
      const startDate = getStartDateOfWeek(year, weekNum);
      
      // Clear existing headers
      $('#weekDayNames').empty();
      
      // Generate the 7 days of the week
      const days = [];
      const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
      
      let dateRangeStart = null;
      let dateRangeEnd = null;
      
      for (let i = 0; i < 7; i++) {
        // Clone the start date and add days
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);
        
        // Save first and last date for range display
        if (i === 0) dateRangeStart = currentDate;
        if (i === 6) dateRangeEnd = currentDate;
        
        // Format the date
        const formattedDate = `${currentDate.getDate()} ${['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][currentDate.getMonth()]}`;
        
        // Create cell with day name and date
        $('#weekDayNames').append(`<th class="text-center">${dayNames[i]}<br>${formattedDate}</th>`);
        
        // Save the date in ISO format for comparison
        days.push(currentDate.toISOString().split('T')[0]);
      }
      
      // Store the days for use in the report display
      $('#weekDayNames').data('days', days);
      
      // Update the date range header
      const startMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][dateRangeStart.getMonth()];
      const endMonth = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][dateRangeEnd.getMonth()];
      
      // Format as "1 Jan - 7 Jan 2023" or "29 Dec - 4 Jan 2023-2024" if spanning years
      let rangeText;
      if (dateRangeStart.getFullYear() === dateRangeEnd.getFullYear()) {
        rangeText = `${dateRangeStart.getDate()} ${startMonth} - ${dateRangeEnd.getDate()} ${endMonth} ${dateRangeStart.getFullYear()}`;
      } else {
        rangeText = `${dateRangeStart.getDate()} ${startMonth} ${dateRangeStart.getFullYear()} - ${dateRangeEnd.getDate()} ${endMonth} ${dateRangeEnd.getFullYear()}`;
      }
      
      $('#weekDatesHeader').text(rangeText);
    }

    // Function to get the start date (Monday) of a specific week
    function getStartDateOfWeek(year, weekNum) {
      // Create a date for Jan 1 of the given year
      const januaryFirst = new Date(year, 0, 1);
      
      // Get the day of the week for Jan 1 (0 = Sunday, 1 = Monday, etc.)
      const dayOfWeek = januaryFirst.getDay();
      
      // Calculate days to first Monday of the year
      // If Jan 1 is Monday (1), offset is 0
      // If Jan 1 is Tuesday (2), offset is 6 (to previous Monday)
      // If Jan 1 is Wednesday (3), offset is 5, etc.
      // If Jan 1 is Sunday (0), offset is 1 (to next Monday)
      const daysToFirstMonday = dayOfWeek === 1 ? 0 : (dayOfWeek === 0 ? 1 : (8 - dayOfWeek));
      
      // Calculate the first Monday of the year
      const firstMondayOfYear = new Date(year, 0, 1 + daysToFirstMonday);
      
      // Calculate the Monday of the requested week
      // Weeks are 1-indexed, so for week 1, we add 0 days to the first Monday
      // For week 2, we add 7 days, etc.
      const requestedMonday = new Date(firstMondayOfYear);
      requestedMonday.setDate(firstMondayOfYear.getDate() + (weekNum - 1) * 7);
      
      return requestedMonday;
    }

    // Function to display weekly report data
    function displayWeeklyReport(weeklyData, dates) {
      const $weeklyReportData = $('#weeklyReportData');
      $weeklyReportData.empty();
      
      if (!weeklyData || weeklyData.length === 0) {
        $weeklyReportData.append('<tr><td colspan="10" class="text-center">No data available for the selected week</td></tr>');
        return;
      }
      
      // Filter out staff IDs 1 and 14
      const filteredData = weeklyData.filter(staff => 
        staff.staff_id !== 1 && staff.staff_id !== 14);
      
      // Get the days from the header data attribute
      const days = $('#weekDayNames').data('days');
      
      // Display each staff row
      filteredData.forEach((staff, index) => {
        let row = `<tr>
          <td>${index + 1}</td>
          <td>${staff.staff_name}</td>`;
        
        // Add a cell for each day of the week
        days.forEach(day => {
          // Get the data for this day if available
          const dayData = staff.days && staff.days[day] ? staff.days[day] : null;
          
          // Create the cell content
          if (dayData && dayData.status === 'present' && dayData.hours > 0) {
            row += `<td class="text-center"><span class="badge bg-success">${dayData.hours}h</span></td>`;
          } else {
            row += `<td class="text-center"><span class="badge bg-secondary">-</span></td>`;
          }
        });
        
        // Add the total
        row += `<td class="text-center fw-bold">${staff.total_hours}h</td>`;
        
        row += `</tr>`;
        $weeklyReportData.append(row);
      });
    }

    // Function to fetch monthly report data
    function fetchMonthlyReport() {
      const monthValue = $('#monthFilter').val();
      
      if (!monthValue) {
        notify('Error!', 'Please select a valid month', 'error');
        return;
      }
      
      // Parse year and month - format YYYY-MM
      const [year, month] = monthValue.split('-');
      
      console.log("Fetching monthly report for", year, "month", month);
      
      // Show loading message
      $('#monthlyReportData').html('<tr><td colspan="7" class="text-center"><i class="fa fa-spin fa-spinner"></i> Loading monthly data...</td></tr>');
      
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getMonthlyReport",
          year: year,
          month: month
        },
        success: function(response) {
          console.log("Monthly report raw response:", response);
          try {
            // Try to parse the response if it's a string
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error fetching monthly report:", data.message);
              notify('Error!', data.message, 'error');
              $('#monthlyReportData').html('<tr><td colspan="7" class="text-center text-danger">Error: ' + data.message + '</td></tr>');
              return;
            }
            
            // Filter out staff IDs 1 and 14
            const filteredData = data.monthlyData.filter(staff => 
              staff.staff_id !== 1 && staff.staff_id !== 14);
            
            // Populate monthly report table
            displayMonthlyReport(filteredData);
            
          } catch (e) {
            console.error("Error parsing monthly report response:", e);
            console.log("Raw response:", response);
            notify('Error!', 'Failed to parse server response. Check console for details.', 'error');
            $('#monthlyReportData').html('<tr><td colspan="7" class="text-center text-danger">Error parsing response</td></tr>');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
          console.log("Response Text:", xhr.responseText);
          notify('Error!', 'Failed to fetch monthly report: ' + error, 'error');
          $('#monthlyReportData').html('<tr><td colspan="7" class="text-center text-danger">Error: ' + error + '</td></tr>');
        },
        dataType: "json"
      });
    }

    // Function to display monthly report
    function displayMonthlyReport(monthlyData) {
      const $monthlyReportData = $('#monthlyReportData');
      $monthlyReportData.empty();
      
      if (!monthlyData || monthlyData.length === 0) {
        $monthlyReportData.append('<tr><td colspan="7" class="text-center">No data available for the selected month</td></tr>');
        return;
      }
      
      // Filter out staff IDs 1 and 14
      const filteredData = monthlyData.filter(staff => 
        staff.staff_id !== 1 && staff.staff_id !== 14);
      
      // Display each staff row
      filteredData.forEach((staff, index) => {
        const row = `<tr>
          <td>${index + 1}</td>
          <td>${staff.staff_name}</td>
          <td class="text-center">${staff.days_present}</td>
          <td class="text-center">${staff.total_hours}h</td>
          <td class="text-center">${staff.break_hours}h</td>
          <td class="text-center fw-bold">${staff.net_hours}h</td>
          <td class="text-center">${staff.daily_average}h/day</td>
        </tr>`;
        
        $monthlyReportData.append(row);
      });
    }

    // Utility function to get week number
    function getWeekNumber(d) {
      // Copy date so don't modify original
      d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
      // Set to nearest Thursday: current date + 4 - current day number
      // Make Sunday's day number 7
      d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
      // Get first day of year
      const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
      // Calculate full weeks to nearest Thursday
      const weekNo = Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
      return weekNo;
    }

    // Function to export weekly report to CSV
    function exportWeeklyReport() {
      const weekValue = $('#weekFilter').val();
      
      if (!weekValue) {
        notify('Error!', 'Please select a valid week before exporting', 'error');
        return;
      }
      
      // Get week dates from header
      const weekHeader = $('#weekDatesHeader').text();
      
      // Get table data
      const tableData = [];
      const headers = ['#', 'Staff Name'];
      
      // Add day headers
      $('#weekDayNames th').each(function() {
        headers.push($(this).text().replace(/\n/g, ' '));
      });
      
      headers.push('Total Hours');
      tableData.push(headers);
      
      // Add rows
      $('#weeklyReportData tr').each(function(rowIndex) {
        const rowData = [];
        $(this).find('td').each(function() {
          // Extract text content, removing any HTML
          let cellContent = $(this).text().trim();
          rowData.push(cellContent);
        });
        tableData.push(rowData);
      });
      
      // Convert to CSV
      let csvContent = "data:text/csv;charset=utf-8,";
      
      tableData.forEach(function(rowArray) {
        const row = rowArray.join(",");
        csvContent += row + "\r\n";
      });
      
      // Create download link
      const encodedUri = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", `Weekly_Attendance_Report_${weekValue}.csv`);
      document.body.appendChild(link);
      
      // Download file
      link.click();
      document.body.removeChild(link);
    }

    // Function to export monthly report to CSV
    function exportMonthlyReport() {
      const monthValue = $('#monthFilter').val();
      
      if (!monthValue) {
        notify('Error!', 'Please select a valid month before exporting', 'error');
        return;
      }
      
      // Get month name for file name
      const [year, month] = monthValue.split('-');
      const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                         'July', 'August', 'September', 'October', 'November', 'December'];
      const monthName = monthNames[parseInt(month) - 1];
      
      // Get table data
      const tableData = [];
      const headers = ['#', 'Staff Name', 'Days Present', 'Total Hours', 'Break Hours', 'Net Hours', 'Daily Average'];
      tableData.push(headers);
      
      // Add rows
      $('#monthlyReportData tr').each(function(rowIndex) {
        const rowData = [];
        $(this).find('td').each(function() {
          // Extract text content, removing any HTML
          let cellContent = $(this).text().trim();
          rowData.push(cellContent);
        });
        tableData.push(rowData);
      });
      
      // Convert to CSV
      let csvContent = "data:text/csv;charset=utf-8,";
      
      tableData.forEach(function(rowArray) {
        const row = rowArray.join(",");
        csvContent += row + "\r\n";
      });
      
      // Create download link
      const encodedUri = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", `Monthly_Attendance_Report_${monthName}_${year}.csv`);
      document.body.appendChild(link);
      
      // Download file
      link.click();
      document.body.removeChild(link);
    }
  </script>

  <!-- Initialize the attendance status tracker -->
  <script>
    $(document).ready(function() {
      // Initialize the staff status component
      loadStaffAttendanceStatus();
      
      // Setup refresh events
      $('#refreshAttendanceBtn').on('click', function() {
        loadStaffAttendanceStatus();
      });
      
      // Handle navbar view attendance button
      $('#navbarViewAttendanceBtn').on('click', function() {
        fetchStaffAttendance();
        $('#staffAttendanceModal').modal('show');
      });
      
      // Auto-refresh staff attendance status every 5 minutes
      setInterval(loadStaffAttendanceStatus, 300000);
    });

    // Function to load current staff attendance status for the navbar
    function loadStaffAttendanceStatus() {
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getAttendance",
          date: new Date().toISOString().split('T')[0]
        },
        success: function(response) {
          console.log("Staff status data received");
          try {
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error fetching staff status:", data.message);
              return;
            }
            
            // Filter out staff IDs 1 and 14 if they somehow got included
            const filteredAttendance = data.attendance.filter(staff => 
              staff.staff_id !== 1 && staff.staff_id !== 14);
            
            renderStaffStatusInNavbar(filteredAttendance);
          } catch (e) {
            console.error("Error parsing staff status response:", e);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error);
        },
        dataType: "json"
      });
    }

    // Function to render staff attendance status in the navbar
    function renderStaffStatusInNavbar(staffData) {
      const $statusTable = $('#staffAttendanceStatusTable');
      const $activeCount = $('#active-staff-count');
      
      $statusTable.empty();
      
      if (!staffData || staffData.length === 0) {
        $statusTable.append('<tr><td colspan="6" class="text-center">No attendance records found</td></tr>');
        $activeCount.text('0');
        return;
      }
      
      // Count active staff (checked in but not checked out)
      let activeStaffCount = 0;
      
      // Sort staff: active first, then checked out, then absent
      staffData.sort((a, b) => {
        // Active staff (checked in, not checked out)
        const aIsActive = a.check_in_time && !a.check_out_time;
        const bIsActive = b.check_in_time && !b.check_out_time;
        
        // Checked out staff (both check in and check out)
        const aIsCheckedOut = a.check_in_time && a.check_out_time;
        const bIsCheckedOut = b.check_in_time && b.check_out_time;
        
        if (aIsActive && !bIsActive) return -1;
        if (!aIsActive && bIsActive) return 1;
        if (aIsCheckedOut && !bIsCheckedOut) return -1;
        if (!aIsCheckedOut && bIsCheckedOut) return 1;
        
        // Alphabetical by name if same status
        return a.staff_name.localeCompare(b.staff_name);
      });
      
      // Display each staff member's status
      staffData.forEach(staff => {
        // Determine status and style
        let statusBadge, statusClass;
        
        if (staff.check_in_time && !staff.check_out_time) {
          // Active - checked in but not out
          statusBadge = '<span class="badge bg-success">Active</span>';
          statusClass = 'table-success';
          activeStaffCount++;
        } else if (staff.check_in_time && staff.check_out_time) {
          // Completed - checked in and out
          statusBadge = '<span class="badge bg-info">Completed</span>';
          statusClass = '';
        } else {
          // Absent - no check in
          statusBadge = '<span class="badge bg-secondary">Absent</span>';
          statusClass = 'text-muted';
        }
        
        // Calculate work hours if applicable
        let hours = 'N/A';
        if (staff.check_in_time && staff.check_out_time) {
          // If both check-in and check-out exist, calculate hours
          const checkIn = new Date(`2000/01/01 ${staff.check_in_time}`);
          const checkOut = new Date(`2000/01/01 ${staff.check_out_time}`);
          
          let diffMs = checkOut - checkIn;
          // Subtract break duration
          diffMs -= (staff.break_duration || 0) * 60 * 1000;
          
          // Convert to hours and minutes
          const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
          const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
          
          hours = `${diffHrs}h ${diffMins}m`;
        } else if (staff.check_in_time && !staff.check_out_time) {
          // If checked in but not out, calculate hours so far
          const checkIn = new Date(`2000/01/01 ${staff.check_in_time}`);
          const now = new Date();
          now.setFullYear(2000, 0, 1); // Set to same date for proper time diff
          
          let diffMs = now - checkIn;
          // Subtract break duration
          diffMs -= (staff.break_duration || 0) * 60 * 1000;
          
          // Convert to hours and minutes
          const diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
          const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
          
          hours = `${diffHrs}h ${diffMins}m (ongoing)`;
        }
        
        // Format break time
        let breakInfo = 'N/A';
        if (staff.break_start_time && staff.break_end_time) {
          breakInfo = `${staff.break_duration}m <small>(${staff.break_start_time} - ${staff.break_end_time})</small>`;
        } else if (staff.break_duration > 0) {
          breakInfo = `${staff.break_duration}m`;
        }
        
        // Add row to table
        const row = `
          <tr class="${statusClass}">
            <td><strong>${staff.staff_name}</strong></td>
            <td>${statusBadge}</td>
            <td>${staff.check_in_time || 'N/A'}</td>
            <td>${breakInfo}</td>
            <td>${staff.check_out_time || 'N/A'}</td>
            <td>${hours}</td>
          </tr>
        `;
        
        $statusTable.append(row);
      });
      
      // Update active staff count
      $activeCount.text(activeStaffCount);
    }
  </script>

  <!-- Initialize the attendance status tracker -->
  <script>
    $(document).ready(function() {
      // Initialize the staff status component
      loadMyAttendanceStatus();
      
      // Auto-refresh staff attendance status every 5 minutes
      setInterval(loadMyAttendanceStatus, 300000);
    });

    // Function to load current user's attendance status for the navbar
    function loadMyAttendanceStatus() {
      console.log("Loading user attendance status...");
      console.log("Browser local time:", new Date().toLocaleString());
      console.log("Date parameter:", new Date().toISOString().split('T')[0]);
      
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getMyAttendance",
          date: new Date().toISOString().split('T')[0]
        },
        success: function(response) {
          console.log("My attendance data received:", response);
          try {
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              console.error("Error fetching attendance status:", data.message);
              if (data.debug_info) {
                console.log("Server debug info:", data.debug_info);
              }
              return;
            }
            
            if (data.debug_info) {
              console.log("Server timezone:", data.debug_info.timezone);
              console.log("Server time:", data.debug_info.server_time);
            }
            
            updateMyAttendanceDisplay(data.attendance);
          } catch (e) {
            console.error("Error parsing attendance response:", e, response);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", status, error, xhr.responseText);
          try {
            const errorResponse = JSON.parse(xhr.responseText);
            console.log("Error response:", errorResponse);
          } catch (e) {
            console.log("Raw error response:", xhr.responseText);
          }
        },
        dataType: "json"
      });
    }

    // Function to update the user's attendance display in navbar
    function updateMyAttendanceDisplay(data) {
      console.log("Updating attendance display with:", data);
      
      if (!data) {
        // No attendance data found
        $('#my-status-badge').removeClass().addClass('badge bg-secondary me-2').text('Absent');
        $('#my-checkin-time').text('--:--');
        $('#my-checkout-time').text('--:--');
        $('#my-break-time').text('--');
        return;
      }
      
      // Update check-in time
      if (data.check_in_time) {
        $('#my-checkin-time').text(data.check_in_time);
      } else {
        $('#my-checkin-time').text('--:--');
      }
      
      // Update check-out time
      if (data.check_out_time) {
        $('#my-checkout-time').text(data.check_out_time);
      } else {
        $('#my-checkout-time').text('--:--');
      }
      
      // Update break information
      if (data.break_duration > 0) {
        $('#my-break-time').text(data.break_duration + 'min');
      } else {
        $('#my-break-time').text('--');
      }
      
      // Update status badge
      if (data.check_in_time && !data.check_out_time) {
        // Active - checked in but not out
        $('#my-status-badge').removeClass().addClass('badge bg-success me-2').text('Active');
      } else if (data.check_in_time && data.check_out_time) {
        // Completed - checked in and out
        $('#my-status-badge').removeClass().addClass('badge bg-info me-2').text('Completed');
      } else {
        // Absent - no check in
        $('#my-status-badge').removeClass().addClass('badge bg-secondary me-2').text('Absent');
      }
    }
  </script>

  <!-- Add functions to handle quick break start/end -->
  <script>
    // Add functions to handle quick break start/end
    function startBreak(attendanceId) {
      console.log("Starting break for attendance ID:", attendanceId);
      
      // Get current time in HH:MM format
      const now = new Date();
      const currentTime = now.getHours().toString().padStart(2, '0') + ":" + 
                         now.getMinutes().toString().padStart(2, '0');
      
      // Get date from filter or use today
      const selectedDate = $('#filterDate').val() || now.toISOString().split('T')[0];
      
      // First get the current record
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getAttendanceRecord",
          id: attendanceId
        },
        success: function(response) {
          try {
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              notify('Error!', data.message, 'error');
              return;
            }
            
            const record = data.record;
            
            // Update the record with break start time
            $.ajax({
              type: "POST",
              url: "staffAttendanceController.php",
              data: {
                action: "updateAttendance",
                id: attendanceId,
                date: selectedDate,
                check_in_time: record.check_in_time,
                check_out_time: record.check_out_time || '',
                break_start_time: currentTime,
                break_end_time: '',
                notes: record.notes || ''
              },
              success: function(updateResponse) {
                try {
                  const updateData = typeof updateResponse === 'string' ? 
                    JSON.parse(updateResponse) : updateResponse;
                  
                  if (updateData.success === false) {
                    notify('Error!', updateData.message, 'error');
                    return;
                  }
                  
                  notify('Success!', 'Break started at ' + currentTime, 'success');
                  
                  // Refresh attendance table
                  fetchStaffAttendance();
                  
                } catch (e) {
                  console.error("Error parsing break start response:", e);
                  notify('Error!', 'Failed to parse server response', 'error');
                }
              },
              error: function(xhr, status, error) {
                notify('Error!', 'Failed to update break start: ' + error, 'error');
              },
              dataType: "json"
            });
            
          } catch (e) {
            console.error("Error parsing get record response:", e);
            notify('Error!', 'Failed to parse server response', 'error');
          }
        },
        error: function(xhr, status, error) {
          notify('Error!', 'Failed to fetch attendance record: ' + error, 'error');
        },
        dataType: "json"
      });
    }

    function endBreak(attendanceId) {
      console.log("Ending break for attendance ID:", attendanceId);
      
      // Get current time in HH:MM format
      const now = new Date();
      const currentTime = now.getHours().toString().padStart(2, '0') + ":" + 
                         now.getMinutes().toString().padStart(2, '0');
      
      // Get date from filter or use today
      const selectedDate = $('#filterDate').val() || now.toISOString().split('T')[0];
      
      // First get the current record
      $.ajax({
        type: "POST",
        url: "staffAttendanceController.php",
        data: {
          action: "getAttendanceRecord",
          id: attendanceId
        },
        success: function(response) {
          try {
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (data.success === false) {
              notify('Error!', data.message, 'error');
              return;
            }
            
            const record = data.record;
            
            // Make sure we have a break start time
            if (!record.break_start_time) {
              notify('Error!', 'No break start time found', 'error');
              return;
            }
            
            // Update the record with break end time
            $.ajax({
              type: "POST",
              url: "staffAttendanceController.php",
              data: {
                action: "updateAttendance",
                id: attendanceId,
                date: selectedDate,
                check_in_time: record.check_in_time,
                check_out_time: record.check_out_time || '',
                break_start_time: record.break_start_time,
                break_end_time: currentTime,
                notes: record.notes || ''
              },
              success: function(updateResponse) {
                try {
                  const updateData = typeof updateResponse === 'string' ? 
                    JSON.parse(updateResponse) : updateResponse;
                  
                  if (updateData.success === false) {
                    notify('Error!', updateData.message, 'error');
                    return;
                  }
                  
                  notify('Success!', 'Break ended at ' + currentTime, 'success');
                  
                  // Refresh attendance table
                  fetchStaffAttendance();
                  
                } catch (e) {
                  console.error("Error parsing break end response:", e);
                  notify('Error!', 'Failed to parse server response', 'error');
                }
              },
              error: function(xhr, status, error) {
                notify('Error!', 'Failed to update break end: ' + error, 'error');
              },
              dataType: "json"
            });
            
          } catch (e) {
            console.error("Error parsing get record response:", e);
            notify('Error!', 'Failed to parse server response', 'error');
          }
        },
        error: function(xhr, status, error) {
          notify('Error!', 'Failed to fetch attendance record: ' + error, 'error');
        },
        dataType: "json"
      });
    }
  </script>

  <!-- Add jsPDF library before closing body tag -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

  <!-- Add PDF export function -->
  <script>
    $(document).ready(function() {
      // Initialize PDF export button
      $('#exportDailyPdfBtn').on('click', function() {
        exportDailyAttendancePDF();
      });
    });

    // Function to export daily attendance to A4 PDF
    function exportDailyAttendancePDF() {
      // Get the selected date
      const selectedDate = $('#filterDate').val() || new Date().toISOString().split('T')[0];
      const formattedDate = formatDateForDisplay(selectedDate);
      
      // Initialize jsPDF
      const { jsPDF } = window.jspdf;
      
      // Create a new PDF document - A4 size (210 x 297 mm) in portrait mode
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4',
        compress: true
      });
      
      // Set custom colors for the report - Black, Red, White scheme
      const primaryColor = [220, 0, 0]; // Red
      const secondaryColor = [0, 0, 0]; // Black
      const backgroundColor = [255, 255, 255]; // White
      
      // Add a colored header bar
      doc.setFillColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
      doc.rect(0, 0, doc.internal.pageSize.width, 30, 'F');
      
      // Add company logo - We'll use an image data URL for the logo
      // First check if the logo exists by creating an image element
      const img = new Image();
      img.crossOrigin = 'Anonymous';  // This helps with CORS issues
      img.src = '/assets/logo-white.png';
      
      img.onload = function() {
        // Logo loaded successfully - add to PDF
        // Get image height while maintaining aspect ratio
        const imgWidth = 35;
        const imgHeight = img.height * imgWidth / img.width;
        
        // Create a canvas to handle the image
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = img.width;
        canvas.height = img.height;
        ctx.drawImage(img, 0, 0);
        
        // Get data URL from canvas
        const imgData = canvas.toDataURL('image/png');
        
        // Add logo to PDF
        doc.addImage(imgData, 'PNG', 10, 5, imgWidth, imgHeight);
        
        // Continue with PDF creation
        finalizePDF();
      };
      
      img.onerror = function() {
        // Logo failed to load - use text instead
        console.log("Logo could not be loaded, using text instead");
        
        // Add company name as text
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(24);
        doc.setFont('helvetica', 'bold');
        doc.text('SN TRAVELS', doc.internal.pageSize.width / 2, 15, { align: 'center' });
        
        // Continue with PDF creation
        finalizePDF();
      };
      
      // Function to complete the PDF after attempting to load the logo
      function finalizePDF() {
        // Add report title
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(14);
        doc.setFont('helvetica', 'normal');
        doc.text('Staff Attendance Report', doc.internal.pageSize.width / 2, 20, { align: 'center' });
        
        // Add date section with background
        doc.setFillColor(primaryColor[0], primaryColor[1], primaryColor[2]);
        doc.rect(40, 40, doc.internal.pageSize.width - 80, 12, 'F');
        
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text(`Date: ${formattedDate}`, doc.internal.pageSize.width / 2, 48, { align: 'center' });
        
        // Extract attendance data from the table
        const tableData = [];
        
        // Get data from the table
        $('#staffAttendanceData tr').each(function(index) {
          const $row = $(this);
          // Skip error/empty message rows
          if ($row.find('td').length < 3) return;
          
          // Extract cell values
          const id = index + 1;
          const name = $row.find('td:eq(1)').text().trim();
          const status = $row.find('td:eq(2)').text().trim();
          const checkin = $row.find('td:eq(3)').text().trim();
          const checkout = $row.find('td:eq(4)').text().trim();
          const breakDuration = $row.find('td:eq(7)').text().trim();
          const totalHours = $row.find('td:eq(8)').text().trim();
          
          // Add to table data
          tableData.push({
            id: id,
            name: name,
            in: checkin === 'N/A' ? '-' : checkin,
            out: checkout === 'N/A' ? '-' : checkout,
            break: breakDuration === '0 min' ? '-' : breakDuration,
            hours: totalHours === 'N/A' ? '-' : totalHours
          });
        });
        
        // If no records, display a message
        if (tableData.length === 0) {
          tableData.push({
            id: '',
            name: 'No attendance records found',
            in: '',
            out: '',
            break: '',
            hours: ''
          });
        }
        
        // Define the table styles
        const tableStyles = {
          theme: 'grid',
          headStyles: {
            fillColor: secondaryColor,
            textColor: [255, 255, 255],
            fontStyle: 'bold',
            halign: 'center'
          },
          bodyStyles: {
            textColor: [0, 0, 0],
            fontSize: 10
          },
          alternateRowStyles: {
            fillColor: [245, 245, 245]
          },
          margin: { top: 60 },
          styles: {
            fontSize: 10,
            cellPadding: 4,
            overflow: 'linebreak'
          },
          columnStyles: {
            0: { cellWidth: 15, halign: 'center' },
            1: { cellWidth: 60, halign: 'left' },
            2: { cellWidth: 30, halign: 'center' },
            3: { cellWidth: 30, halign: 'center' },
            4: { cellWidth: 25, halign: 'center' },
            5: { cellWidth: 25, halign: 'center' }
          }
        };
        
        // Create the table
        doc.autoTable({
          startY: 60,
          head: [[
            { content: '#', styles: { halign: 'center' } },
            { content: 'Staff Name', styles: { halign: 'left' } },
            { content: 'Check In', styles: { halign: 'center' } },
            { content: 'Check Out', styles: { halign: 'center' } },
            { content: 'Break', styles: { halign: 'center' } },
            { content: 'Hours', styles: { halign: 'center' } }
          ]],
          body: tableData.map(row => [
            row.id,
            row.name,
            row.in,
            row.out,
            row.break,
            row.hours
          ]),
          ...tableStyles
        });
        
        // Add a colored footer bar
        // Get the footer position at the bottom of the page
        const pageHeight = doc.internal.pageSize.height;
        // Fixed position at the bottom of the page, with a 15mm margin
        const footerY = pageHeight - 15;
        
        // Draw the footer bar at the bottom of the page
        doc.setFillColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.rect(0, footerY, doc.internal.pageSize.width, 15, 'F');
        
        // Add footer text
        const now = new Date();
        const printDate = now.toLocaleDateString();
        const printTime = now.toLocaleTimeString();
        
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.text(`Generated on: ${printDate} at ${printTime}`, doc.internal.pageSize.width - 15, footerY + 10, { align: 'right' });
        
        // Add company info in the footer
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.text('SN TRAVELS - Staff Attendance Report', 15, footerY + 10);
        
        // Save the PDF with A4 dimensions
        const pdfName = `Staff_Attendance_${selectedDate}.pdf`;
        doc.save(pdfName);
      }
    }
    
    // Helper function to format date for display
    function formatDateForDisplay(dateString) {
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', options);
    }
  </script>
  </body>

  </html>
  </html>