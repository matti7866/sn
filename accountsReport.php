<?php
  include 'header.php';
?>
<title>Accounts Report - Detailed Transactions</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<style>
  #customBtn{ color:#29323c;border-color:#29323c; }
  #customBtn:hover{color:  #FFFFFF;background-color:#485563;border-color:#485563}
  .bg-graident-lightcrimson{
    background: #485563;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }
  .text-graident-lightcrimson{
    color: #485563;  /* fallback for old browsers */
    color: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
  .credit { color: #28a745; font-weight: bold; }
  .debit { color: #dc3545; font-weight: bold; }
  .transfer { color: #ffc107; font-weight: bold; }
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px; margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light">
        <h2 class="text-graident-lightcrimson"><b><i class="fa fa-fw fa-money text-dark"></i> <i>Accounts Report - Detailed Transactions</i></b></h2>
      </div>
      
      <!-- Filter Section -->
      <div class="card-body">
        <!-- Reset Section -->
        <div class="row mb-3 p-3" style="background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #dc3545;">
          <div class="col-md-12">
            <h5 class="text-danger mb-3"><i class="fa fa-refresh"></i> Balance Reset Control</h5>
            <div class="row">
              <div class="col-md-4">
                <label><strong>Reset Balances From Date:</strong></label>
                <input type="date" id="resetFromDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                <small class="text-muted">Only transactions from this date onwards will be calculated</small>
              </div>
              <div class="col-md-4">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-warning" onclick="resetBalancesFromDate()">
                  <i class="fa fa-refresh"></i> Reset Balances from Selected Date
                </button>
              </div>
              <div class="col-md-4">
                <label>&nbsp;</label><br>
                <button type="button" class="btn btn-danger" onclick="resetBalancesFromToday()">
                  <i class="fa fa-calendar"></i> Reset Balances from TODAY
                </button>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-12">
                <div class="alert alert-info" style="margin-bottom: 0;">
                  <i class="fa fa-info-circle"></i> <strong>Note:</strong> This will set all account balances to ZERO and recalculate from the selected date onwards. Previous transactions will be ignored in balance calculations.
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Transaction Filters -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label>From Date:</label>
            <input type="date" id="fromDate" class="form-control" value="<?php echo date('Y-m-01'); ?>">
          </div>
          <div class="col-md-3">
            <label>To Date:</label>
            <input type="date" id="toDate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-md-3">
            <label>Account:</label>
            <select id="accountFilter" class="form-control">
              <option value="">All Accounts</option>
              <?php
              $accounts = $pdo->query("SELECT account_ID, account_Name FROM accounts ORDER BY account_Name")->fetchAll(PDO::FETCH_ASSOC);
              foreach($accounts as $account) {
                echo "<option value='".$account['account_ID']."'>".$account['account_Name']."</option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-3">
            <label>Transaction Type:</label>
            <select id="typeFilter" class="form-control">
              <option value="">All Types</option>
              <option value="credit">Credits (Money In)</option>
              <option value="debit">Debits (Money Out)</option>
              <option value="transfer">Transfers</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <button type="button" class="btn btn-primary" onclick="loadTransactions()">
              <i class="fa fa-search"></i> Load Transactions
            </button>
            <button type="button" class="btn btn-info" onclick="toggleAccountBalances()">
              <i class="fa fa-balance-scale"></i> <span id="balanceToggleText">Show Account Balances</span>
            </button>
            <button type="button" class="btn btn-success" onclick="exportToExcel()">
              <i class="fa fa-file-excel-o"></i> Export to Excel
            </button>
          </div>
        </div>
      </div>

      <!-- Account Balances Section (Initially Hidden) -->
      <div class="card-body" id="accountBalancesSection" style="display: none;">
        <div class="row">
          <div class="col-md-12">
            <h4 class="text-graident-lightcrimson mb-3">
              <i class="fa fa-balance-scale"></i> Current Account Balances
            </h4>
            <div class="table-responsive">
              <table id="accountBalancesTable" class="table table-striped table-hover">
                <thead class="text-white bg-graident-lightcrimson">
                  <tr style="font-size:14px">
                    <th>Account ID</th>
                    <th>Account Name</th>
                    <th>Total Credits</th>
                    <th>Total Debits</th>
                    <th>Current Balance</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="AccountBalancesTbl">
                  <tr><td colspan="6" class="text-center">Click "Show Account Balances" to load data</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <hr>
      </div>

      <!-- Summary Cards -->
      <div class="card-body">
        <div class="row" id="summaryCards">
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h5>Total Credits</h5>
                <h3 id="totalCredits">0.00</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <h5>Total Debits</h5>
                <h3 id="totalDebits">0.00</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <h5>Total Transfers</h5>
                <h3 id="totalTransfers">0.00</h3>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-info text-white">
              <div class="card-body">
                <h5>Net Balance</h5>
                <h3 id="netBalance">0.00</h3>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="row">
        <div class="col-md-12">
          <div class="table-responsive">
            <table id="transactionsTable" class="table table-striped table-hover">
              <thead class="text-white bg-graident-lightcrimson">
                <tr style="font-size:14px">
                  <th>Date</th>
                  <th>Transaction Type</th>
                  <th>Account</th>
                  <th>Description</th>
                  <th>Reference</th>
                  <th>Credit (+)</th>
                  <th>Debit (-)</th>
                  <th>Currency</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody id="TransactionsTbl">
                <tr><td colspan="9" class="text-center">Click "Load Transactions" to view data</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
<?php include 'footer.php'; ?>

<script type="text/javascript">

function loadTransactions(){
  const fromDate = $('#fromDate').val();
  const toDate = $('#toDate').val();
  const accountFilter = $('#accountFilter').val();
  const typeFilter = $('#typeFilter').val();
  const resetDate = localStorage.getItem('balanceResetDate') || '';

  $('#TransactionsTbl').html('<tr><td colspan="9" class="text-center">Loading transactions...</td></tr>');
  
  $.ajax({
    url: 'accountsReportController.php',
    method: 'POST',
    dataType: 'text',
    data: {
      action: 'getDetailedTransactions',
      fromDate: fromDate,
      toDate: toDate,
      accountFilter: accountFilter,
      typeFilter: typeFilter,
      resetDate: resetDate
    },
    success: function(response){
      const data = JSON.parse(response);
      $('#TransactionsTbl').html(data.html);
      
      // Update summary cards
      $('#totalCredits').text(data.summary.totalCredits);
      $('#totalDebits').text(data.summary.totalDebits);
      $('#totalTransfers').text(data.summary.totalTransfers);
      $('#netBalance').text(data.summary.netBalance);
      
      // Show reset date info if active
      if(resetDate) {
        $('#totalCredits').append(` <small>(from ${resetDate})</small>`);
      }
      
      // Initialize DataTable for better functionality
      if ($.fn.DataTable.isDataTable('#transactionsTable')) {
        $('#transactionsTable').DataTable().destroy();
      }
      $('#transactionsTable').DataTable({
        "pageLength": 50,
        "order": [[ 0, "desc" ]], // Sort by date descending
        "responsive": true
      });
    },
    error: function(){
      $('#TransactionsTbl').html('<tr><td colspan="9" class="text-center text-danger">Error loading transactions</td></tr>');
    }
  });
}

function exportToExcel(){
  const fromDate = $('#fromDate').val();
  const toDate = $('#toDate').val();
  const accountFilter = $('#accountFilter').val();
  const typeFilter = $('#typeFilter').val();

  window.open(`accountsReportController.php?action=exportExcel&fromDate=${fromDate}&toDate=${toDate}&accountFilter=${accountFilter}&typeFilter=${typeFilter}`, '_blank');
}

function toggleAccountBalances(){
  const accountBalancesSection = $('#accountBalancesSection');
  const balanceToggleText = $('#balanceToggleText');

  if (accountBalancesSection.css('display') === 'none') {
    accountBalancesSection.show();
    balanceToggleText.text('Hide Account Balances');
    // Load account balances when showing the section
    loadAccountBalances();
  } else {
    accountBalancesSection.hide();
    balanceToggleText.text('Show Account Balances');
  }
}

function loadAccountBalances(){
  const resetDate = localStorage.getItem('balanceResetDate') || '';
  
  $('#AccountBalancesTbl').html('<tr><td colspan="6" class="text-center">Loading account balances...</td></tr>');
  
  $.ajax({
    url: 'accountsReportController.php',
    method: 'POST',
    dataType: 'text',
    data: {
      action: 'getAccountBalances',
      resetDate: resetDate
    },
    success: function(response){
      const data = JSON.parse(response);
      $('#AccountBalancesTbl').html(data.html);
      
      // Initialize DataTable for account balances
      if ($.fn.DataTable.isDataTable('#accountBalancesTable')) {
        $('#accountBalancesTable').DataTable().destroy();
      }
      $('#accountBalancesTable').DataTable({
        "pageLength": 25,
        "order": [[ 4, "desc" ]], // Sort by balance descending
        "responsive": true
      });
    },
    error: function(){
      $('#AccountBalancesTbl').html('<tr><td colspan="6" class="text-center text-danger">Error loading account balances</td></tr>');
    }
  });
}

function resetBalancesFromToday(){
  const today = new Date().toISOString().split('T')[0];
  $('#resetFromDate').val(today);
  resetBalancesFromDate();
}

function resetBalancesFromDate(){
  const resetDate = $('#resetFromDate').val();
  
  if(!resetDate) {
    alert('Please select a reset date first!');
    return;
  }
  
  const confirmMessage = `⚠️ PERMANENT ACTION WARNING:\n\nThis will PERMANENTLY reset ALL account balances to ZERO and recalculate from ${resetDate} onwards.\n\nThis action:\n• Cannot be undone\n• Will remove this reset option forever\n• Will affect all future calculations\n• Ignores all transactions before ${resetDate}\n\nThis is a ONE-TIME permanent reset.\n\nAre you absolutely sure you want to proceed?`;
  
  if(confirm(confirmMessage)) {
    // Set the reset date permanently
    localStorage.setItem('balanceResetDate', resetDate);
    localStorage.setItem('resetCompleted', 'true');
    
    // Completely remove the reset section
    $('.row.mb-3.p-3').fadeOut(500, function() {
      $(this).remove();
    });
    
    // Show permanent success message
    showPermanentResetSuccessMessage(resetDate);
    
    // Refresh both views
    loadTransactions();
    if($('#accountBalancesSection').is(':visible')) {
      loadAccountBalances();
    }
  }
}

function showPermanentResetSuccessMessage(resetDate) {
  const alertHtml = `
    <div class="alert alert-success" id="permanentResetAlert">
      <i class="fa fa-check-circle"></i> <strong>PERMANENT RESET COMPLETED!</strong><br>
      All account balances have been permanently reset to ZERO.<br>
      System now calculates from <strong>${resetDate}</strong> onwards only.<br>
      <small><em>This reset option has been permanently removed.</em></small>
    </div>
  `;
  
  // Insert where the reset section was
  $('.row.mb-3').first().before(alertHtml);
}

$(document).ready(function(){
  // Check if reset was already completed
  const resetCompleted = localStorage.getItem('resetCompleted');
  const resetDate = localStorage.getItem('balanceResetDate');
  
  if(resetCompleted === 'true' && resetDate) {
    // Hide the reset section completely
    $('.row.mb-3.p-3').hide();
    
    // Show permanent status
    showPermanentResetStatus(resetDate);
  }
  
  // Load today's transactions by default
  loadTransactions();
});

function showPermanentResetStatus(resetDate) {
  const statusHtml = `
    <div class="alert alert-info" id="permanentResetStatusAlert">
      <i class="fa fa-info-circle"></i> 
      <strong>System Status:</strong> Account balances are calculated from <strong>${resetDate}</strong> onwards only.
      Previous transactions are permanently excluded from calculations.
    </div>
  `;
  
  // Insert at the top of transaction filters
  $('.row.mb-3').first().before(statusHtml);
}

</script>

<!-- Include DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

</body>
</html>
