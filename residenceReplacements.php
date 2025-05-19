<?php
include 'header.php';
include 'connection.php';
include 'nav.php';

// Fetch all replaced residences - In Process (where replacement_status is null or 'in_process')
$sqlInProcess = "SELECT res.residenceID,
               res.passenger_name,
               cus.customer_name,
               IFNULL(company.company_name,'')  AS company_name,
               res.sale_price,
               cur.currencyName,
               res.completedStep,
               DATE_FORMAT(res.datetime, '%d-%b-%Y') AS created_on,
               IFNULL(res.replacement_status, 'in_process') AS replacement_status
        FROM residence AS res
        INNER JOIN customer AS cus      ON cus.customer_id = res.customer_id
        LEFT  JOIN company  ON company.company_id = res.company
        LEFT  JOIN currency AS cur      ON cur.currencyID   = res.saleCurID
        WHERE res.current_status = 'replaced' 
        AND res.deleted = 0
        AND (res.replacement_status IS NULL OR res.replacement_status = 'in_process')
        ORDER BY res.residenceID DESC";
$stmtInProcess = $pdo->prepare($sqlInProcess);
$stmtInProcess->execute();
$recordsInProcess = $stmtInProcess->fetchAll(PDO::FETCH_ASSOC);

// Fetch all completed replaced residences
$sqlCompleted = "SELECT res.residenceID,
               res.passenger_name,
               cus.customer_name,
               IFNULL(company.company_name,'')  AS company_name,
               res.sale_price,
               cur.currencyName,
               res.completedStep,
               DATE_FORMAT(res.datetime, '%d-%b-%Y') AS created_on,
               DATE_FORMAT(res.replacement_completed_date, '%d-%b-%Y') AS completed_date
        FROM residence AS res
        INNER JOIN customer AS cus      ON cus.customer_id = res.customer_id
        LEFT  JOIN company  ON company.company_id = res.company
        LEFT  JOIN currency AS cur      ON cur.currencyID   = res.saleCurID
        WHERE res.current_status = 'replaced' 
        AND res.deleted = 0
        AND res.replacement_status = 'completed'
        ORDER BY res.replacement_completed_date DESC";
$stmtCompleted = $pdo->prepare($sqlCompleted);
$stmtCompleted->execute();
$recordsCompleted = $stmtCompleted->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
  /* White theme overrides */
  .panel-heading.bg-inverse {background:#333!important;}
  #replacedTableInProcess thead tr th, 
  #replacedTableCompleted thead tr th {
    background:#333!important;
    color:#fff;
    border-color: #444;
  }
  #replacedTableInProcess, 
  #replacedTableCompleted {
    background: #fff;
    color: #333;
  }
  #replacedTableInProcess tbody tr,
  #replacedTableCompleted tbody tr {
    background: #fff;
    color: #333;
  }
  #replacedTableInProcess tbody tr:hover,
  #replacedTableCompleted tbody tr:hover {
    background:#f5f5f5!important;
  }
  .nav-tabs .nav-link.active {
    font-weight: bold;
    background-color: #fff;
    border-bottom-color: #fff;
  }
  .tab-content {
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 15px;
  }
  .alert-confirmation {
    background-color: rgba(0, 0, 0, 0.7);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  .alert-confirmation-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    width: 400px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
  }
  .confirmation-buttons {
    text-align: right;
    margin-top: 15px;
  }
  .confirmation-buttons button {
    margin-left: 10px;
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel">
        <div class="panel-heading bg-inverse text-white">
          <h4 class="panel-title"><i class="fa fa-exchange"></i> Residence Replacements</h4>
        </div>
        <div class="panel-body p-0">
          <!-- Tabs navigation -->
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="inprocess-tab" data-bs-toggle="tab" data-bs-target="#inprocess" type="button" role="tab" aria-controls="inprocess" aria-selected="true">
                In Process <span class="badge bg-primary"><?= count($recordsInProcess) ?></span>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                Completed <span class="badge bg-success"><?= count($recordsCompleted) ?></span>
              </button>
            </li>
          </ul>
          
          <!-- Tab content -->
          <div class="tab-content" id="myTabContent">
            <!-- In Process Tab -->
            <div class="tab-pane fade show active" id="inprocess" role="tabpanel" aria-labelledby="inprocess-tab">
              <div class="table-responsive">
                <table id="replacedTableInProcess" class="table table-bordered align-middle mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Passenger</th>
                      <th>Customer</th>
                      <th>Company</th>
                      <th>Sale Price</th>
                      <th>Created On</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($recordsInProcess) === 0): ?>
                      <tr><td colspan="7" class="text-center">No in-process replacement records found.</td></tr>
                    <?php else: ?>
                      <?php $i = 1; foreach ($recordsInProcess as $row): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= htmlspecialchars($row['passenger_name']); ?></td>
                          <td><?= htmlspecialchars($row['customer_name']); ?></td>
                          <td><?= htmlspecialchars($row['company_name']); ?></td>
                          <td><?= number_format($row['sale_price'], 2) . ' ' . $row['currencyName']; ?></td>
                          <td><?= $row['created_on']; ?></td>
                          <td>
                            <div class="btn-group">
                              <button type="button" class="btn btn-sm btn-success" onclick="markAsComplete(<?= $row['residenceID']; ?>)">
                                <i class="fa fa-check"></i> Mark as Complete
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            
            <!-- Completed Tab -->
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
              <div class="table-responsive">
                <table id="replacedTableCompleted" class="table table-bordered align-middle mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Passenger</th>
                      <th>Customer</th>
                      <th>Company</th>
                      <th>Sale Price</th>
                      <th>Created On</th>
                      <th>Completed On</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($recordsCompleted) === 0): ?>
                      <tr><td colspan="7" class="text-center">No completed replacement records found.</td></tr>
                    <?php else: ?>
                      <?php $i = 1; foreach ($recordsCompleted as $row): ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= htmlspecialchars($row['passenger_name']); ?></td>
                          <td><?= htmlspecialchars($row['customer_name']); ?></td>
                          <td><?= htmlspecialchars($row['company_name']); ?></td>
                          <td><?= number_format($row['sale_price'], 2) . ' ' . $row['currencyName']; ?></td>
                          <td><?= $row['created_on']; ?></td>
                          <td><?= $row['completed_date']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Dialog -->
<div class="alert-confirmation" id="confirmationDialog">
  <div class="alert-confirmation-content">
    <h4>Confirm Completion</h4>
    <p>Are you sure you want to mark this replacement as completed?</p>
    <div class="confirmation-buttons">
      <button type="button" class="btn btn-secondary" onclick="closeConfirmation()">Cancel</button>
      <button type="button" class="btn btn-success" id="confirmButton">Confirm</button>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#replacedTableInProcess').DataTable();
    $('#replacedTableCompleted').DataTable();
  });
  
  function markAsComplete(id) {
    // Show confirmation dialog
    document.getElementById('confirmationDialog').style.display = 'flex';
    
    // Set the action for the confirm button
    document.getElementById('confirmButton').onclick = function() {
      // AJAX call to update status
      $.ajax({
        url: 'residenceReplacementsController.php',
        type: 'POST',
        data: {
          action: 'markAsComplete',
          id: id
        },
        success: function(response) {
          try {
            const result = JSON.parse(response);
            if (result.status === 'success') {
              // Success notification
              notify('Success', 'Record marked as completed', 'success');
              // Reload the page after a short delay
              setTimeout(function() {
                window.location.reload();
              }, 1000);
            } else {
              // Error notification
              notify('Error', result.message || 'Failed to update record', 'error');
            }
          } catch (e) {
            notify('Error', 'An unexpected error occurred', 'error');
          }
          closeConfirmation();
        },
        error: function() {
          notify('Error', 'Failed to communicate with the server', 'error');
          closeConfirmation();
        }
      });
    };
  }
  
  function closeConfirmation() {
    document.getElementById('confirmationDialog').style.display = 'none';
  }
</script> 