<?php include 'header.php' ?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/gh/dmhendricks/file-icon-vectors@1.0/dist/file-icon-vectors.min.css" />
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}

$companyID = $_GET['companyID'];
$stmt = $pdo->prepare("SELECT * FROM company WHERE company_id = :companyID");
$stmt->execute(['companyID' => $companyID]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
  header('Location: /');
}

$stmt = $pdo->prepare("
  SELECT residence.* , airports.countryName, airports.countryCode
  FROM residence 
  LEFT JOIN airports ON airports.airport_id = residence.Nationality
  WHERE company = :companyID AND residence.deleted = 0
  GROUP BY residence.residenceID
  ");
$stmt->execute(['companyID' => $companyID]);
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM banks ORDER BY bank_name ASC");
$stmt->execute();
$banks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmp = $pdo->prepare("
  SELECT * FROM residencedocuments WHERE ResID IN (SELECT residenceID FROM residence WHERE company = :companyID)
  ");
$stmp->execute(['companyID' => $companyID]);
$residenceDocuments = $stmp->fetchAll(PDO::FETCH_ASSOC);

$docs = array();
if (count($residenceDocuments)) {
  foreach ($residenceDocuments as $doc) {
    $docs[$doc['ResID']][$doc['fileType']] = $doc;
  }
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 mb-2">
      <h3>Establishment Employees (<?php echo $company['company_name'] ?>)</h3>
      <button id="exportPdf" class="btn btn-primary mb-2"><i class="fa fa-file-pdf"></i> Export to PDF</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" id="message"></div>
  </div>
  <div class="row">
    <?php if (count($employees) == 0): ?>
      <div class="col-md-12 text-center text-danger">
        No employees found
      </div>
    <?php else: ?>
      <?php foreach ($employees as $emp): ?>
        <div class="col-md-6 mb-3">
          <div class="card" <?php echo $emp['cancelled'] == 1 ? 'style="opacity:0.4"' : '' ?>>
            <div class="card-body">
              <div class="media d-flex">
                <img src="/color_admin_v5.0/admin/template/assets/img/user/user-13.jpg" class="me-3 rounded-circle" alt="<?php echo $emp['countryName'] ?>" height="70">
                <div class="media-body">
                  <h5 class="mb-1"><?php echo $emp['passenger_name'] ?></h5>
                  <p class="mb-1"><img height="14" class="border" src="https://flagpedia.net/data/flags/h24/<?php echo strtolower($emp['countryCode']) ?>.png" /> <?php echo $emp['countryName'] ?></p>
                  <?php echo $emp['cancelled'] ? '<span class="badge bg-danger mt-1">Cancelled</span>' : '' ?>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-3 mb-1">
                  <strong>Passport #</strong><br />n/a
                </div>
                <div class="col-md-3 mb-1">
                  <strong>Labour Card #</strong><br /><?php echo $emp['LabourCardNumber'] != '' ? $emp['LabourCardNumber'] : 'n/a' ?>
                </div>
                <div class="col-md-3 mb-1">
                  <strong>Emirates ID Number</strong><br /><?php echo $emp['EmiratesIDNumber'] != '' ? $emp['EmiratesIDNumber'] : 'n/a' ?>
                </div>
                <div class="col-md-3 mb-1">
                  <strong>Expiry Date</strong><br /><span class="<?php echo date('Y-m-d') > $emp['expiry_date'] ? 'text-danger' : 'text-success' ?>"><?php echo $emp['expiry_date'] ?></span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mt-2">
                  <div class="progress rounded-pill">
                    <?php $percentCompleted = $emp['completedStep'] / 10 * 100 ?>
                    <div class="progress-bar <?php echo $emp['completedStep'] == 10 ? 'bg-success' : 'bg-indigo' ?> progress-bar-striped <?php echo $emp['completedStep'] != 10 ? 'progress-bar-animated' : '' ?> rounded-pill fs-10px fw-bold" style="width:<?php echo $percentCompleted ?>%"><?php echo $emp['completedStep'] ?> / 10</div>
                  </div>
                </div>
              </div>
              <div class="row mt-4">
                <div class="col-md-12">
                  <?php if ($emp['cancelled'] != 1): ?>
                    <button class="btn btn-danger btn-cancel" data-id="<?php echo $emp['residenceID'] ?>" data-name="<?php echo $emp['passenger_name'] ?>">Cancel Employee</button>
                  <?php endif; ?>
                  <a target="_blank" class="btn btn-purple" href="residence.php?id=<?php echo $emp['residenceID'] ?>&stp=1">View Profile</a>
                  <button data-id="<?php echo $emp['residenceID'] ?>" class="btn btn-success btn-attachments"><i class="fa fa-file"></i> View Attachments</button>
                  <a class="btn btn-white" target="_blank" href="/printLetter.php?id=<?php echo $emp['residenceID'] ?>&type=noc"><i class="fa fa-print"></i> NOC</a>
                  <button data-id="<?php echo $emp['residenceID'] ?>" class="btn btn-white btn-print-salary"><i class="fa fa-print"></i> Salary Certificate</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?php require 'footer.php'; ?>

<div class="modal fade" id="modalSalaryCertificate" role="dialog" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Print Salary Certificate</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mb-2">
            <label for="bank_id">Bank</label>
            <select name="bank_id" id="bank_id" class="form-select">
              <option value="">Select Bank</option>
              <?php foreach ($banks as $bank) : ?>
                <option value="<?php echo $bank['id'] ?>"><?php echo $bank['bank_name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-12">
            <button disabled="disabled" id="btnPrintCertificate" class="btn btn-success btn-block"><i class="fa fa-print"></i> Print Now</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCancel" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmCancel" method="POST">
    <input type="hidden" name="action" value="cancelEmployee">
    <input type="hidden" name="employeeId" id="employeeId" value="">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Cancel Employee</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgCancel"></div>
            <div class="row">
              <div class="col-md-12 mb-2">
                <label class="form-label">Employee Name</label>
                <input type="text" name="employeeName" id="employeeName" class="form-control" value="" readonly>
              </div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="cancelDate" class="form-label">Cancel Date <span class="text-danger">*</span></label>
              <input type="text" name="cancelDate" id="cancelDate" class="form-control" value="">
              <div class="invalid-feedback expiryAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="remarks" class="form-label">Remarks</label>
              <textarea name="remarks" id="remarks" class="form-control" rows="2"></textarea>
              <div class="invalid-feedback remarks"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="cancelPaper" class="form-label">Upload Cancel Paper <span class="text-danger">*</span></label>
              <input type="file" value="0" name="cancelPaper" id="cancelPaper" class="form-control" value="">
              <div class="invalid-feedback cancelPaper"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveCancel" class="btn btn-success">Submit</button>
        </div>
      </div>
    </div>
  </form>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    var selectedEmployee = null;

    $('#bank_id').on('change', function() {
        var btnPrint = $('#btnPrintCertificate');
        var bank_id = $(this).val();
        if (bank_id == '' || !window.selectedEmployee) {
            btnPrint.attr('disabled', 'disabled');
        } else {
            btnPrint.removeAttr('disabled');
        }
    });

    $('#btnPrintCertificate').on('click', function() {
        var bank_id = $('#bank_id').val();
        window.open('/printLetter.php?id=' + window.selectedEmployee + '&type=salary_certificate&bank_id=' + bank_id);
    });

    $('.btn-print-salary').on('click', function() {
        var btn = $(this);
        window.selectedEmployee = btn.data('id');
        var modal = $('#modalSalaryCertificate');
        modal.modal('show');
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

    $("#cancelDate").datepicker({
        format: 'yyyy-mm-dd',
    });

    $('.btn-cancel').on('click', function() {
        var employeeId = $(this).data('id');
        var employeeName = $(this).data('name');
        $('#employeeName').val(employeeName);
        $('#employeeId').val(employeeId);
        $('#modalCancel').modal('show');
    });

    $('.btn-attachments').on('click', function() {
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
                } else {
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                }
                btn.removeAttr('disabled').html(btn.attr('data-temp'));
            }
        });
        modal.modal('show');
    });

    $('#frmCancel').on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = $('#btnSaveCancel');
        var msg = $('#message');
        var msgQuota = $("#msgCancel");

        msgQuota.html('');
        btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');

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
            url: '/manageEstablishmentsController.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            error: function() {
                msgQuota.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
                btn.removeAttr('disabled').html(btn.attr('data-temp'));
            },
            success: function(res) {
                if (res.status == 'success') {
                    frm[0].reset();
                    $('#modalCancel').modal('hide');
                    msg.html('<div class="alert alert-success">' + res.message + '</div>');
                } else {
                    if (res.message == 'form_errors') {
                        $.each(res.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
                        });
                    } else {
                        msgQuota.html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                }
                btn.removeAttr('disabled').html(btn.attr('data-temp'));
            }
        });
    });

    // Export to PDF functionality
    $('#exportPdf').on('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Set document properties
        doc.setFontSize(16);
        doc.text(`Establishment Employees - ${<?php echo json_encode($company['company_name']); ?>}`, 14, 20);
        
        // Prepare table data
        let tableData = [];
        $('.card').each(function() {
            let row = [];
            const $card = $(this);
            
            const name = $card.find('.media-body h5').text();
            const nationality = $card.find('.media-body p:eq(0)').text().trim();
            const labourCard = $card.find('.col-md-3:eq(1)').text().replace('Labour Card #', '').trim();
            const emiratesId = $card.find('.col-md-3:eq(2)').text().replace('Emirates ID Number', '').trim();
            const expiry = $card.find('.col-md-3:eq(3)').text().replace('Expiry Date', '').trim();
            const status = $card.find('.badge.bg-danger').length > 0 ? 'Cancelled' : 'Active';
            const progress = $card.find('.progress-bar').text();

            row.push(name, nationality, labourCard, emiratesId, expiry, status, progress);
            tableData.push(row);
        });

        // Define table columns
        const columns = [
            'Name', 
            'Nationality', 
            'Labour Card #', 
            'Emirates ID #', 
            'Expiry Date', 
            'Status', 
            'Progress'
        ];

        // Add table to PDF
        doc.autoTable({
            startY: 30,
            head: [columns],
            body: tableData,
            theme: 'grid',
            headStyles: {
                fillColor: [41, 128, 185],
                textColor: 255,
                fontSize: 10
            },
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            columnStyles: {
                0: { cellWidth: 40 },  // Name
                1: { cellWidth: 30 },  // Nationality
                2: { cellWidth: 25 },  // Labour Card
                3: { cellWidth: 30 },  // Emirates ID
                4: { cellWidth: 25 },  // Expiry
                5: { cellWidth: 20 },  // Status
                6: { cellWidth: 20 }   // Progress
            }
        });

        // Add footer with date
        const pageCount = doc.internal.getNumberOfPages();
        for(let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(8);
            doc.text(`Page ${i} of ${pageCount} | Generated on: ${new Date().toLocaleString()}`, 
                14, 
                doc.internal.pageSize.height - 10
            );
        }

        // Save the PDF
        doc.save(`employees_${<?php echo json_encode($company['company_name']); ?>}_${new Date().toISOString().split('T')[0]}.pdf`);
    });
});
</script>