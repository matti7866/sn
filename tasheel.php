<?php include 'header.php' ?>
<title>Tasheel Transactions</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
// Update SQL query to fetch permissions for the new page  
$sql = "SELECT permission.select, permission.update, permission.delete, permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'TasheelTransactions' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();

// fetch single row  
$record = $stmt->fetch();

// get the company  
$sql = "SELECT company_id, company_name FROM `company`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$companies = $stmt->fetchAll();

// get the transaction types
$sql = "SELECT * FROM `transaction_type`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5 mb-2">
            <h3>Tasheel Transactions</h3>
        </div>
        <div class="col-md-7 text-end mb-2">
            <button type="button" id="btnAddNewTransaction" class="btn btn-success">Add Transaction</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Transactions</h3>
                </div>
                <div class="panel-body">
                    <form action="" method="POST" id="frmSearch">
                        <input type="hidden" name="action" value="searchTransactions" />
                        <input type="hidden" name="status_filter" id="status_filter" value="in_process" />
                        <div class="row">
                            <div class="col-md-2">
                                <label for="company" class="form-label">Company</label>
                                <select name="company" id="company" class="form-select">
                                    <option value="">Select</option>
                                    <?php foreach ($companies as $company): ?>
                                        <option value="<?php echo $company['company_id'] ?>"><?php echo $company['company_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Transaction Type</label>
                                <div class="input-group">
                                    <select name="type" id="type" class="form-select">
                                        <option value="">All</option>
                                        <?php foreach ($types as $type): ?>
                                            <option value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-success" id="addTypeBtn2"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                            </div>
                            <div class="col-md-2">
                                <label for="mohrestatus" class="form-label">Moher Status</label>
                                <input type="text" name="mohrestatus" id="mohrestatus" class="form-control" placeholder="Moher Status">
                            </div>
                            <div class="col-md-1 mb-2">
                                <label for="" class="form-label"> </label>
                                <button id="btnSearch" class="btn btn-primary btn-block w-100"><i class="fa fa-filter"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="message"></div>
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Transactions</h4>
                </div>
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="transactionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="in-process-tab" data-bs-toggle="tab" data-bs-target="#in-process" type="button" role="tab" aria-controls="in-process" aria-selected="true">In Process</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="transactionTabsContent">
                    <div class="tab-pane fade show active" id="in-process" role="tabpanel" aria-labelledby="in-process-tab">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Transaction</th>
                                    <th>Moher Status</th>
                                    <th>Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="in-process-transactions">
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="in-process-pagination" class="pagination-container">
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <span id="in-process-info" class="pagination-info"></span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Transaction</th>
                                    <th>Moher Status</th>
                                    <th>Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="completed-transactions">
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="completed-pagination" class="pagination-container">
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <span id="completed-info" class="pagination-info"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddTransaction" role="dialog" aria-labelledby="" aria-hidden="true">
    <form action="tasheelController.php" id="frmAdd" method="POST" class="frmAjax" data-message="msgAdd">
        <input type="hidden" name="action" id="modalAction" value="addTransaction">
        <input type="hidden" name="id" id="id" value="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white" id="modalTitle">New Transaction</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2" id="msgAdd"></div>
                        <div class="col-md-12 mb-2">
                            <label for="company_id" class="form-label">Company Name</label>
                            <select name="company_id" id="company_id" class="form-select">
                                <option value="">Select</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?php echo $company['company_id'] ?>"><?php echo $company['company_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback company_id"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="transaction_type_id" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="transaction_type_id" id="transaction_type_id" class="form-select">
                                    <option value="">Select</option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="button" class="btn btn-success" id="addTypeBtn"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="invalid-feedback transaction_type_id"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="attachment" class="form-label">Upload PDF/Image (to extract application number)</label>
                            <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="invalid-feedback attachment"></div>
                            <div id="extractionStatus" class="mt-2"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="transaction_number" class="form-label">Transaction Number <span class="text-danger">*</span></label>
                            <input type="text" name="transaction_number" id="transaction_number" class="form-control" value="">
                            <div class="invalid-feedback transaction_number"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="cost" class="form-label">Cost</label>
                            <input type="text" name="cost" id="cost" class="form-control" placeholder="Cost">
                            <div class="invalid-feedback cost"></div>
                        </div>
                        <div class="col-md-12 mb-2" style="display:none;">
                            <input type="hidden" name="mohrestatus" id="mohrestatus_edit" class="form-control">
                        </div>
                        <div class="col-md-12 mb-2" style="display:none;">
                            <input type="hidden" name="status" id="status" class="form-control" value="in_process">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnSaveAdd" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalChangeStatus" role="dialog" aria-labelledby="" aria-hidden="true">
    <form action="tasheelController.php" id="frmChangeStatus" method="POST" class="frmAjax" data-message="msgChangeStatus">
        <input type="hidden" name="action" id="modalAction" value="changeStatus">
        <input type="hidden" name="id" id="modalTrxID" value="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white" id="modalTitle">Change Moher Status</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2" id="msgChangeStatus"></div>
                        <div class="col-md-12 mb-2">
                            <label for="mohrestatus" class="form-label">Moher Status</label>
                            <input type="text" name="mohrestatus" id="modalStatus" class="form-control" placeholder="Moher Status">
                            <div class="invalid-feedback modalStatus"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnSaveAdd" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modalMarkComplete" role="dialog" aria-labelledby="" aria-hidden="true">
    <form action="tasheelController.php" id="frmMarkComplete" method="POST" class="frmAjax" data-message="msgMarkComplete">
        <input type="hidden" name="action" value="markAsCompleted">
        <input type="hidden" name="id" id="completeTransactionId" value="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white">Mark Transaction as Completed</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2" id="msgMarkComplete"></div>
                        <div class="col-md-12 mb-2">
                            <p>Are you sure you want to mark this transaction as completed?</p>
                            <p>Once marked as completed, this transaction will not be updated by automatic processes.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h3 class="modal-title text-white" id="addTypeModalLabel">Add Transaction Type</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="addTypeMessage"></div>
                <div class="mb-3">
                    <label for="typeName" class="form-label">Type Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="typeName" required>
                    <div class="invalid-feedback">Type name is required</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveTypeBtn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Include pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>

<script type="text/javascript">
function getTransactions(page = 1) {
    var frm = $("#frmSearch");
    var btn = $("#btnSearch");
    var statusFilter = $("#status_filter").val();
    var targetTabId = statusFilter === 'in_process' ? 'in-process-transactions' : 'completed-transactions';
    var paginationId = statusFilter === 'in_process' ? 'in-process-pagination' : 'completed-pagination';
    var infoId = statusFilter === 'in_process' ? 'in-process-info' : 'completed-info';
    
    btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $("#" + targetTabId).html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');
    
    var formData = new FormData(frm[0]);
    formData.append('page', page);
    
    $.ajax({
        url: 'tasheelController.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $("#" + targetTabId).html(response.html);
            $("#" + paginationId).html(response.pagination);
            $("#" + infoId).text(response.info);
            btn.attr('disabled', false).html('<i class="fa fa-filter"></i>');
        }
    });
}

$(document).ready(function() {
    // Verify jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
        return;
    }

    // Initial load for in-process transactions
    getTransactions(1);

    // Tab click handlers
    $('#in-process-tab').on('click', function() {
        $("#status_filter").val("in_process");
        getTransactions(1);
    });
    
    $('#completed-tab').on('click', function() {
        $("#status_filter").val("completed");
        getTransactions(1);
    });

    // Pagination click handler
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        getTransactions(page);
    });

    $('#frmSearch').on('submit', function(e) {
        e.preventDefault();
        getTransactions(1);
    });

    // Handle Add Transaction button
    $("#btnAddNewTransaction").on('click', function() {
        console.log('Add Transaction button clicked');
        $("#modalTitle").text("New Transaction");
        $('#modalAction').val('addTransaction');
        $('#modalTrxID').val('');
        $('#status').val('in_process'); // Default status
        $("#modalAddTransaction").modal("show");
    });

    $('.frmAjax').on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
        frm.find('.invalid-feedback').html('');
        frm.find('.is-invalid').removeClass('is-invalid');
        
        // Validate only required fields before submission
        var isValid = true;
        
        // Only validate transaction_type and transaction_number if this is the add/edit form
        if (frm.attr('id') === 'frmAdd') {
            var transaction_type = frm.find('#transaction_type_id').val();
            var transaction_number = frm.find('#transaction_number').val();
            
            if (!transaction_type) {
                frm.find('#transaction_type_id').addClass('is-invalid');
                frm.find('#transaction_type_id').siblings('.invalid-feedback').html('Transaction Type is required');
                isValid = false;
            }
            
            if (!transaction_number) {
                frm.find('#transaction_number').addClass('is-invalid');
                frm.find('#transaction_number').siblings('.invalid-feedback').html('Transaction Number is required');
                isValid = false;
            }
        }
        
        if (!isValid) {
            return false;
        }
        
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
                    $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
                    frm[0].reset();
                    $('#modalAddTransaction').modal('hide');
                    $('#modalChangeStatus').modal('hide');
                    $('#modalMarkComplete').modal('hide');
                    
                    // Refresh the current active tab
                    var currentTab = $("#status_filter").val();
                    getTransactions(1);
                    
                    // If a transaction was marked as completed, switch to the completed tab
                    if (frm.attr('id') === 'frmMarkComplete') {
                        $('#completed-tab').tab('show');
                        $("#status_filter").val("completed");
                        getTransactions(1);
                    }
                } else {
                    if (e.message == 'form_errors') {
                        $.each(e.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value);
                        });
                    } else {
                        $(frm.attr('data-message')).html('<div class="alert alert-danger">' + e.message + '</div>');
                    }
                }
            },
            error: function(resp) {
                btn.attr('disabled', false).html(btn.attr('data-temp-text'));
            }
        });
    });

    $('.form-select,input[type=file],input[type=text],input[type=number]').on('change keyup', function() {
        var vl = $(this).val();
        var field_id = $(this).attr('id');
        
        // Only validate transaction_type_id and transaction_number as required
        if ((field_id === 'transaction_type_id' || field_id === 'transaction_number') && vl === '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Button to mark a transaction as complete
    $(document).on('click', '.btn-mark-complete', function() {
        var id = $(this).data('id');
        $('#completeTransactionId').val(id);
        $('#modalMarkComplete').modal('show');
    });

    // Button to change moher status
    $(document).on('click', '.btnChangeStatus', function() {
        var btn = $(this);
        var id = btn.data('id');
        var status = btn.data('status');
        $('#modalChangeStatus').modal('show');
        $('#modalStatus').val(status);
        $('#modalTrxID').val(id);
    });

    // Edit transaction button handler
    $(document).on('click', '.btn-edit', function() {
        var btn = $(this);
        var id = btn.data('id');
        
        // Reset the form and clear previous errors
        $('#frmAdd')[0].reset();
        $('#frmAdd .invalid-feedback').html('');
        $('#frmAdd .is-invalid').removeClass('is-invalid');
        $('#msgAdd').html('');
        
        // Set the modal title and action
        $("#modalTitle").text("Edit Transaction");
        $('#modalAction').val('updateTransaction');
        $('#id').val(id);
        
        // Fetch transaction data
        $.ajax({
            url: 'tasheelController.php',
            type: 'POST',
            data: {
                action: 'getTransaction',
                id: id
            },
            success: function(response) {
                if (response.status === 'success') {
                    var data = response.data;
                    
                    // Populate form fields
                    $('#company_id').val(data.company_id);
                    $('#transaction_type_id').val(data.transaction_type_id);
                    $('#transaction_number').val(data.transaction_number);
                    $('#cost').val(data.cost);
                    $('#mohrestatus_edit').val(data.mohrestatus);
                    $('#status').val(data.status || 'in_process');
                    
                    // Show the modal
                    $("#modalAddTransaction").modal("show");
                } else {
                    $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#message').html('<div class="alert alert-danger">Failed to fetch transaction data</div>');
            }
        });
    });

    // Delete transaction button handler
    $(document).on('click', '.btn-delete', function() {
        var btn = $(this);
        var id = btn.data('id');
        
        if (confirm('Are you sure you want to delete this transaction?')) {
            $.ajax({
                url: 'tasheelController.php',
                type: 'POST',
                data: {
                    action: 'deleteTransaction',
                    id: id
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
                        getTransactions(1);
                    } else {
                        $('#message').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#message').html('<div class="alert alert-danger">Failed to delete transaction</div>');
                }
            });
        }
    });

    if ($('#btnAddNewTransaction').length) {
        console.log('Add Transaction button found in DOM');
    } else {
        console.error('Add Transaction button not found in DOM');
    }

    // Add Transaction Type Button
    $('#addTypeBtn').on('click', function() {
        $('#typeName').val('');
        $('#addTypeMessage').html('');
        $('#addTypeModal').modal('show');
    });
    
    // Add Transaction Type Button (also from search form)
    $('#addTypeBtn, #addTypeBtn2').on('click', function() {
        $('#typeName').val('');
        $('#addTypeMessage').html('');
        $('#addTypeModal').modal('show');
    });
    
    // Save Transaction Type
    $('#saveTypeBtn').on('click', function() {
        const typeName = $('#typeName').val().trim();
        
        if (!typeName) {
            $('#typeName').addClass('is-invalid');
            return;
        }
        
        $('#typeName').removeClass('is-invalid');
        const saveBtn = $(this);
        saveBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: 'tasheelController.php',
            type: 'POST',
            data: {
                action: 'addTransactionType',
                name: typeName
            },
            success: function(response) {
                saveBtn.prop('disabled', false).html('Save');
                
                if (response.status === 'success') {
                    $('#addTypeMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                    
                    // Update both dropdowns
                    updateTypeDropdowns(response.typeId, typeName);
                    
                    // Close modal after a short delay
                    setTimeout(function() {
                        $('#addTypeModal').modal('hide');
                    }, 1500);
                } else {
                    $('#addTypeMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                saveBtn.prop('disabled', false).html('Save');
                $('#addTypeMessage').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
        });
    });
    
    // Clear validation on input
    $('#typeName').on('input', function() {
        $(this).removeClass('is-invalid');
    });

    // Function to update type dropdowns after adding a new type
    function updateTypeDropdowns(typeId, typeName) {
        // Update transaction modal dropdown
        const newOption1 = new Option(typeName, typeId);
        $('#transaction_type_id').append(newOption1);
        
        // Update search form dropdown
        const newOption2 = new Option(typeName, typeId);
        $('#type').append(newOption2);
        
        // Select the new type in the transaction modal dropdown if it's open
        if ($('#modalAddTransaction').hasClass('show')) {
            $('#transaction_type_id').val(typeId);
        }
    }

    // Set pdf.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';
    
    // Handle file upload for application number extraction
    $('#attachment').on('change', function() {
        const file = this.files[0];
        if (!file) return;
        
        $('#extractionStatus').html('<div class="alert alert-info">Processing file...</div>');
        
        if (file.type === 'application/pdf') {
            extractFromPDF(file);
        } else if (file.type.startsWith('image/')) {
            extractFromImage(file);
        } else {
            $('#extractionStatus').html('<div class="alert alert-danger">Unsupported file type. Please upload a PDF or image.</div>');
        }
    });
    
    // Function to extract application number from PDF
    function extractFromPDF(file) {
        const reader = new FileReader();
        reader.onload = function() {
            const arrayBuffer = this.result;
            pdfjsLib.getDocument(arrayBuffer).promise.then(function(pdf) {
                const numPages = pdf.numPages;
                let fullText = '';
                const pagePromises = [];
                
                // Collect text from all pages
                for (let i = 1; i <= numPages; i++) {
                    pagePromises.push(pdf.getPage(i).then(function(page) {
                        return page.getTextContent().then(function(textContent) {
                            return textContent.items.map(item => item.str).join(' ');
                        });
                    }));
                }
                
                Promise.all(pagePromises).then(function(pageTexts) {
                    fullText = pageTexts.join(' ');
                    console.log('Extracted text:', fullText);
                    extractApplicationNumber(fullText);
                });
            }).catch(function(error) {
                console.error('Error parsing PDF:', error);
                $('#extractionStatus').html('<div class="alert alert-danger">Error reading PDF file</div>');
            });
        };
        reader.readAsArrayBuffer(file);
    }
    
    // Function to extract application number from image (via server-side API)
    function extractFromImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        
        $.ajax({
            url: 'extract_text.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $("#loading").hide();
                console.log("Response:", response);
                
                if (response.status === 'success') {
                    // Get text content from either the old or new format
                    var textContent = response.text || response.extracted_text || '';
                    
                    // Check if response already contains an MB number
                    if (response.mb_number) {
                        // Directly use the MB number returned by the server
                        $("#transaction_number").val(response.mb_number);
                        showAlert('success', 'Application number found: ' + response.mb_number);
                    } else {
                        // Try to extract it from text content
                        extractApplicationNumber(textContent);
                    }
                } else {
                    // Show error message
                    showAlert('danger', 'Error: ' + response.message);
                }
            },
            error: function() {
                $('#extractionStatus').html('<div class="alert alert-danger">Error processing image</div>');
            }
        });
    }
    
    // Function to extract application number from text
    function extractApplicationNumber(text) {
        if (!text || text.trim() === '') {
            showAlert('warning', 'No text was extracted from the file. Please enter the application number manually.');
            return;
        }
        
        console.log("Extracted text:", text);
        // Updated regex pattern to also match MB numbers without AE at the end
        var pattern = /\bMB[A-Za-z0-9]+(?:AE)?\b/;
        var match = text.match(pattern);
        
        if (match) {
            var mbNumber = match[0];
            $("#transaction_number").val(mbNumber);
            showAlert('success', 'Application number found: ' + mbNumber);
        } else {
            showAlert('info', 'No application number found. Please enter it manually.');
        }
    }
    
    // Helper function to show alerts in extraction status
    function showAlert(type, message) {
        $('#extractionStatus').html('<div class="alert alert-' + type + '">' + message + '</div>');
    }
});
</script> 