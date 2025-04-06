<?php include 'header.php' ?>
<title>New Transactions</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
// Update SQL query to fetch permissions for the new page  
$sql = "SELECT permission.select, permission.update, permission.delete, permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'NewTransactions' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();

// fetch single row  
$record = $stmt->fetch();

// get the customer  
$sql = "SELECT customer_id, customer_name FROM `customer`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$customer = $stmt->fetchAll();

// get the amer_types
$sql = "SELECT * FROM `amer_types`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll();
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-5 mb-2">
            <h3>New Transactions</h3>
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
                        <div class="row">
                            <div class="col-md-1">
                                <label for="start_date" class="form-label">From Date</label>
                                <input type="text" name="start_date" id="start_date" class="form-control" value="<?php echo date('Y-m-01') ?>" />
                            </div>
                            <div class="col-md-1">
                                <label for="end_date" class="form-label">To Date</label>
                                <input type="text" name="end_date" id="end_date" class="form-control" value="<?php echo date('Y-m-d') ?>" />
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="customer" class="form-label">Customer</label>
                                <select name="customer" id="customer" class="form-select">
                                    <option value="">Select</option>
                                    <?php foreach ($customer as $cust): ?>
                                        <option value="<?php echo $cust['customer_id'] ?>"><?php echo $cust['customer_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="customer" class="form-label">Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">All</option>
                                    <?php foreach ($types as $type): ?>
                                        <option value="<?php echo $type['id'] ?>"><?php echo $type['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="refunded">Refunded</option>
                                    <option value="visit_required">Visit Required</option>
                                </select>
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
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer / Passenger Name</th>
                            <th>Tranaction</th>
                            <th>Status</th>
                            <th>Net Cost</th>
                            <th>Sale Cost</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="transactions">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddTransaction" role="dialog" aria-labelledby="" aria-hidden="true">
    <form action="amerController.php" id="frmAdd" method="POST" class="frmAjax" data-message="msgAdd">
        <input type="hidden" name="action" id="modalAction" value="addTransaction">
        <input type="hidden" name="modalTrxID" id="id" value="">
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
                            <label for="customer" class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id" class="form-select">
                                <option value="">Select</option>
                                <?php foreach ($customer as $cust): ?>
                                    <option value="<?php echo $cust['customer_id'] ?>"><?php echo $cust['customer_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback customer"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="passenger_name" class="form-label">Passenger Name <span class="text-danger">*</span></label>
                            <input type="text" name="passenger_name" id="passenger_name" class="form-control" value="">
                            <div class="invalid-feedback passenger_name"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                            <select name="type_id" id="type_id" class="form-select">
                                <option value="">Select</option>
                                <?php foreach ($types as $type): ?>
                                    <option value="<?php echo $type['id'] ?>" data-cost="<?php echo $type['cost_price'] ?>" data-sale="<?php echo $type['sale_price'] ?>"><?php echo $type['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback type"></div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="application_number" class="form-label">Application Number <span class="text-danger">*</span></label>
                            <input type="text" name="application_number" id="application_number" class="form-control" value="">
                            <div class="invalid-feedback application_number"></div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="transaction_number" class="form-label">Transaction Number <span class="text-danger">*</span></label>
                            <input type="text" name="transaction_number" id="transaction_number" class="form-control" value="">
                            <div class="invalid-feedback transaction_number"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="text" name="payment_date" id="payment_date" class="form-control" value="<?php echo date('Y-m-d') ?>">
                            <div class="invalid-feedback payment_date"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="cost_price" class="form-label">Cost Price <span class="text-danger">*</span></label>
                            <input type="text" name="cost_price" id="cost_price" class="form-control" placeholder="Cost Price">
                            <div class="invalid-feedback cost_price"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="sale_price" class="form-label">Sale Price <span class="text-danger">*</span></label>
                            <input type="text" name="sale_price" id="sale_price" class="form-control" placeholder="Sale Price">
                            <div class="invalid-feedback sale_price"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="receipt" class="form-label">Upload Receipt</label>
                            <input type="file" name="receipt" id="receipt" class="form-control" placeholder="Upload Receipt">
                            <div class="invalid-feedback receipt"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="iban" class="form-label">IBAN Info <span class="text-danger">(optional)</span></label>
                            <input type="text" name="iban" id="iban" class="form-control" placeholder="IBAN Info">
                            <div class="invalid-feedback iban"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <div class="invalid-feedback status"></div>
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
    <form action="amerController.php" id="frmChangeStatus" method="POST" class="frmAjax" data-message="msgChangeStatus">
        <input type="hidden" name="action" id="modalAction" value="changeStatus">
        <input type="hidden" name="id" id="modalTrxID" value="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white" id="modalTitle">Change Transaction Status</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-2" id="msgChangeStatus"></div>
                        <div class="col-md-4 mb-2">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="modalStatus" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                                <option value="refunded">Refunded</option>
                                <option value="visit_required">Visit Required</option>
                            </select>
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

<!-- Include pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>

<script type="text/javascript">
function getTransactions() {
    var frm = $("#frmSearch");
    var btn = $("#btnSearch");
    btn.attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    $("#transactions").html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
    $.ajax({
        url: 'amerController.php',
        type: 'POST',
        data: frm.serialize(),
        success: function(e) {
            $("#transactions").html(e.html);
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

    // Set pdf.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

    // Function to extract specific data from PDF and fill form
    function extractAndFillForm(file) {
        const reader = new FileReader();
        reader.onload = function() {
            const arrayBuffer = this.result;
            pdfjsLib.getDocument(arrayBuffer).promise.then(function(pdf) {
                // Get all pages
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
                    console.log('Full Extracted Text (All Pages):', fullText);

                    // Updated patterns with IBAN
                    const patterns = {
                        passenger_name: /Name:\s+([A-Z][A-Z\s]+)(?=\s+[أ-ي])/i,
                        transaction_type: /(?:New Work Entry Permit - Employee|Transaction Type:?\s*([\w\s]+))/i,
                        application_number: /Application #:\s+(\d+)/i,
                        transaction_number: /Trans\. #:\s+(\d+)/i,
                        payment_date: /Payment Date:\s+(?:\d{2}:\d{2}:\d{2}\s+)?(\d{2}-\d{2}-\d{4})/i,
                        grand_total: /Grand Total\s+([\d,]+\.?\d*)/i,
                        iban: /IBAN #:\s+([A-Z0-9-\s]+|-)/i // Matches IBAN or '-'
                    };

                    const data = {};
                    for (let [field, pattern] of Object.entries(patterns)) {
                        const match = fullText.match(pattern);
                        if (match) {
                            data[field] = field === 'transaction_type' ? 
                                (match[1] ? match[1].trim() : 'New Work Entry Permit - Employee') : 
                                match[1].trim();
                            console.log(`Matched ${field}: "${data[field]}"`);
                        } else {
                            console.log(`No match for ${field} - Pattern: ${pattern}`);
                        }
                    }

                    // Convert DD-MM-YYYY to YYYY-MM-DD for payment_date
                    if (data.payment_date) {
                        const [day, month, year] = data.payment_date.split('-');
                        data.payment_date = `${year}-${month}-${day}`;
                        console.log(`Converted payment_date: "${data.payment_date}"`);
                    }

                    // Clear fields first
                    $('#passenger_name').val('');
                    $('#type_id').val('');
                    $('#application_number').val('');
                    $('#transaction_number').val('');
                    $('#payment_date').val('');
                    $('#cost_price').val('');
                    $('#iban').val('');

                    // Fill specific fields
                    if (data.passenger_name) {
                        $('#passenger_name').val(data.passenger_name);
                        console.log('Assigned passenger_name:', data.passenger_name);
                    } else {
                        console.log('Passenger_name not assigned - No match found');
                    }
                    if (data.transaction_type) {
                        let found = false;
                        $('#type_id option').each(function() {
                            if ($(this).text().trim().toLowerCase() === data.transaction_type.toLowerCase()) {
                                $(this).prop('selected', true);
                                $('#type_id').trigger('change');
                                found = true;
                                console.log('Assigned transaction_type:', data.transaction_type);
                            }
                        });
                        if (!found) console.log(`Transaction Type "${data.transaction_type}" not found in dropdown`);
                    } else {
                        console.log('Transaction_type not assigned - No match found');
                    }
                    if (data.application_number) {
                        $('#application_number').val(data.application_number);
                        console.log('Assigned application_number:', data.application_number);
                    } else {
                        console.log('Application_number not assigned - No match found');
                    }
                    if (data.transaction_number) {
                        $('#transaction_number').val(data.transaction_number);
                        console.log('Assigned transaction_number:', data.transaction_number);
                    } else {
                        console.log('Transaction_number not assigned - No match found');
                    }
                    if (data.payment_date) {
                        $('#payment_date').val(data.payment_date).datepicker('update');
                        console.log('Assigned payment_date:', data.payment_date);
                    } else {
                        console.log('Payment_date not assigned - No match found');
                    }
                    if (data.grand_total) {
                        $('#cost_price').val(data.grand_total.replace(',', ''));
                        console.log('Assigned cost_price:', data.grand_total.replace(',', ''));
                    } else {
                        console.log('Cost_price not assigned - No match found');
                    }
                    if (data.iban) {
                        $('#iban').val(data.iban === '-' ? '' : data.iban); // Empty if just '-'
                        console.log('Assigned iban:', data.iban === '-' ? '' : data.iban);
                    } else {
                        console.log('IBAN not assigned - No match found');
                    }

                    // Check for issues
                    if ($('#passenger_name').val() && !data.application_number && !data.transaction_number && !data.payment_date && !data.grand_total) {
                        $('#msgAdd').html('<div class="alert alert-warning">All data went to Passenger Name. See console logs for details.</div>');
                        console.log('Warning: Only passenger_name filled.');
                    } else if ($('#type_id').val() && !data.passenger_name && !data.application_number && !data.transaction_number && !data.payment_date && !data.grand_total) {
                        $('#msgAdd').html('<div class="alert alert-warning">Only Transaction Type filled. See console logs for details.</div>');
                        console.log('Warning: Only transaction_type filled.');
                    } else {
                        $('#msgAdd').html('<div class="alert alert-success">PDF data loaded successfully</div>');
                    }
                }).catch(function(error) {
                    console.error('Error processing pages:', error);
                    $('#msgAdd').html('<div class="alert alert-danger">Error processing PDF pages</div>');
                });
            }).catch(function(error) {
                console.error('Error parsing PDF:', error);
                $('#msgAdd').html('<div class="alert alert-danger">Error reading PDF file</div>');
            });
        };
        reader.readAsArrayBuffer(file);
    }

    // Handle file upload
    $('#receipt').on('change', function() {
        const file = this.files[0];
        if (file && file.type === 'application/pdf') {
            $('#msgAdd').html('<div class="alert alert-info">Processing PDF...</div>');
            extractAndFillForm(file);
        } else {
            $('#msgAdd').html('<div class="alert alert-danger">Please upload a valid PDF file</div>');
        }
    });

    getTransactions();

    $('#frmSearch').on('submit', function(e) {
        e.preventDefault();
        getTransactions();
    });

    $("#type_id").on('change', function() {
        var cost = $(this).find('option:selected').data('cost');
        var sale = $(this).find('option:selected').data('sale');
        // $("#cost_price").val(cost); // Commented to preserve PDF Grand Total
        $("#sale_price").val(sale);
    });

    // Handle Add Transaction button
    $("#btnAddNewTransaction").on('click', function() {
        console.log('Add Transaction button clicked');
        $("#modalTitle").text("New Transaction");
        $('#modalAction').val('addTransaction');
        $('#modalTrxID').val('');
        $("#modalAddTransaction").modal("show");
    });

    $("#payment_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
    $("#start_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
    $("#end_date").datepicker({
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
                    $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
                    frm[0].reset();
                    $('#modalAddTransaction').modal('hide');
                    $('#modalChangeStatus').modal('hide');
                    getTransactions();
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
        if (vl == '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    $('#transactions').on('click', '.btnChangeStatus', function() {
        var btn = $(this);
        var id = btn.data('id');
        var status = btn.data('status');
        $('#modalChangeStatus').modal('show');
        $('#modalStatus').val(status);
        $('#modalTrxID').val(id);
    });

    if ($('#btnAddNewTransaction').length) {
        console.log('Add Transaction button found in DOM');
    } else {
        console.error('Add Transaction button not found in DOM');
    }
});
</script>