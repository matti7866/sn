<?php
include 'header.php';
include 'nav.php';

// Define steps array
$steps = [
    ['name' => 'Pending Delivery', 'count' => 0, 'slug' => 'pending'],
    ['name' => 'Received', 'count' => 0, 'slug' => 'received'],
    ['name' => 'Delivered', 'count' => 0, 'slug' => 'delivered']
];

// Calculate step counts
foreach ($steps as &$step) {
    $whereCount = "";
    if ($step['slug'] == 'pending') $whereCount = "eid_received = 0 AND eid_delivered = 0";
    elseif ($step['slug'] == 'received') $whereCount = "eid_received = 1 AND eid_delivered = 0";
    elseif ($step['slug'] == 'delivered') $whereCount = "eid_delivered = 1 AND eid_received = 1";

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM (
            SELECT residenceID FROM residence WHERE completedStep >= 7 AND $whereCount
            UNION
            SELECT id FROM freezone WHERE completedSteps >= 5 AND $whereCount
        ) AS total");
    $stmt->execute();
    $step['count'] = $stmt->fetchColumn();
}
unset($step);

// Determine current step
$currentStep = isset($_GET['step']) && in_array($_GET['step'], array_column($steps, 'slug')) ? $_GET['step'] : 'pending';

// Retrieve data based on current step
$where = "";
if ($currentStep == 'pending') {
    $where .= " AND eid_received = 0 AND eid_delivered = 0 ";
} elseif ($currentStep == 'received') {
    $where .= " AND eid_received = 1 AND eid_delivered = 0 ";
} elseif ($currentStep == 'delivered') {
    $where .= " AND eid_delivered = 1 AND eid_received = 1 ";
}

// Initialize $files as empty array
$files = [];

try {
    $stmp = $pdo->prepare("
        SELECT residenceID, passenger_name, passportNumber, EmiratesIDNumber, completedStep, customer.customer_name as customer_name,
        IFNULL((sale_price - (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE PaymentFor = residence.residenceID)),0) as remaining_balance,
        'ML' as `type`
        FROM residence 
        LEFT JOIN customer ON customer.customer_id = residence.customer_id
        WHERE completedStep >= 7 $where
        UNION 
        SELECT id as residenceID, passangerName as passenger_name, passportNumber, eidNumber as EmiratesIDNumber, completedSteps as completedStep, customer.customer_name as customer_name, 0 as remaining_balance, 'FZ' as `type`
        FROM freezone
        LEFT JOIN customer ON customer.customer_id = freezone.customerID
        WHERE completedSteps >= 5 $where
        GROUP BY residenceID 
        ORDER BY remaining_balance DESC, completedStep ASC");
    
    if ($stmp->execute()) {
        $files = $stmp->fetchAll(PDO::FETCH_OBJ) ?: [];
    } else {
        error_log("Query execution failed: " . implode(", ", $stmp->errorInfo()));
    }
} catch (PDOException $e) {
    error_log("PDO Exception: " . $e->getMessage());
    $files = [];
}
?>

<title>Emirates ID Tasks</title>
<?php if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
} ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="d-flex">
                <h3>Emirates ID Tasks</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" id="message"></div>
    </div>

    <div class="row">
        <div class="col-md-12 btn-group mb-3">
            <?php foreach ($steps as $step): ?>
                <a href="eidTasks.php?step=<?= $step['slug'] ?>" class="btn <?php echo $step['slug'] == $currentStep ? 'btn-primary' : 'btn-white' ?>">
                    <?php echo $step['name'] ?>
                    <?php echo $step['count'] > 0 ? '<span class="badge bg-danger">' . $step['count'] . '</span>' : '' ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-title">Emirates ID Supplier</div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Customer Name</th>
                                    <th>Passenger Name</th>
                                    <th width="150">Passport Number</th>
                                    <th width="150">EID Number</th>
                                    <th width="150">Remaining Balance</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalRemainingBalances = 0;
                                if (!empty($files)) {
                                    foreach ($files as $file):
                                        $totalRemainingBalances += $file->remaining_balance;
                                ?>
                                        <tr id="row-<?= $file->residenceID ?>">
                                            <td><?= $file->residenceID ?></td>
                                            <td><?= $file->customer_name ?></td>
                                            <td><?= $file->passenger_name ?></td>
                                            <td><?= $file->passportNumber ?></td>
                                            <td><?= $file->EmiratesIDNumber ?></td>
                                            <td class="<?php echo $file->remaining_balance > 0 ? 'text-red' : '' ?>"><strong><?= number_format($file->remaining_balance, 2) ?></strong></td>
                                            <td><span class="badge bg-<?php echo $file->type == 'ML' ? 'success' : 'danger' ?>"><?= $file->type ?></span></td>
                                            <td>
                                                <?php
                                                if ($file->completedStep < 8) {
                                                    echo '<span class="badge bg-red">Waiting For Residency</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($currentStep == 'pending'): ?>
                                                    <button data-id="<?= $file->residenceID ?>" data-type="<?= $file->type ?>" class="btn btn-sm btn-success btn-setEidReceived">Mark as Received</button>
                                                <?php endif; ?>
                                                <?php if ($currentStep == 'received'): ?>
                                                    <button data-id="<?= $file->residenceID ?>" data-type="<?= $file->type ?>" class="btn btn-sm btn-info btn-setEidDelivered">Mark as Delivered</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                } else {
                                    echo '<tr><td colspan="9">No records found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="text-right">
                            <strong>Total Remaining Balance: <?= number_format($totalRemainingBalances, 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Mark as Received -->
<div class="modal fade" id="modalMarkReceived" role="dialog" aria-labelledby="modalMarkReceivedLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="eidTasksController.php" method="POST" id="frmEmiratesID" class="frmAjax" data-msg="#msgMarkReceived" data-popup="modalMarkReceived" data-delete-row="true">
                <input type="hidden" name="action" value="setMarkReceived">
                <input type="hidden" name="id" id="emiratesIDID" value="">
                <input type="hidden" name="type" id="emiratesIDType" value="">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white" id="modalMarkReceivedLabel"><b><i>Mark as Received</i></b></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="msgMarkReceived"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="eidNumber">EID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eidNumber" name="eidNumber" value="784-">
                            <div class="invalid-feedback eidNumber"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="eidExpiryDate">EID Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eidExpiryDate" name="eidExpiryDate" value="<?php echo date("Y-m-d", strtotime("+2 years")) ?>">
                            <div class="invalid-feedback eidExpiryDate"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="passenger_name">Passenger Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="passenger_name" name="passenger_name" value="">
                            <div class="invalid-feedback passenger_name"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <div class="invalid-feedback gender"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dob" name="dob" value="">
                            <div class="invalid-feedback dob"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    // DataTable initialization
    if ($('#datatable').length) {
        var table = $('#datatable').DataTable({
            responsive: false,
            "order": [],
            "dom": 'frtip'
        });
    }

    // Form validation
    $('.form-control, .form-select').on('change keyup', function() {
        var vl = $(this).val();
        if (vl == '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Mark as Received button - Use event delegation
    $(document).on('click', '.btn-setEidReceived', function() {
        console.log('Mark as Received button clicked');
        
        var btn = $(this);
        var id = btn.data('id');
        var type = btn.data('type');
        
        console.log('ID:', id, 'Type:', type);
        
        btn.prop('disabled', true);
        
        $.ajax({
            url: 'eidTasksController.php',
            type: 'POST',
            data: {
                action: 'getResidence',
                id: id,
                type: type
            },
            beforeSend: function() {
                console.log('AJAX request started');
                $('#message').html('<div class="alert alert-info">Loading data...</div>');
            },
            success: function(e) {
                console.log('AJAX Success:', e);
                btn.prop('disabled', false);
                
                if (e && typeof e === 'object' && e.residence) {
                    $('#emiratesIDID').val(id);
                    $('#emiratesIDType').val(type);
                    $("#passenger_name").val(e.residence.passenger_name || '');
                    $("#dob").val(e.residence.dob || '');
                    $("#gender").val(e.residence.gender || 'male');
                    
                    console.log('Showing modal');
                    $('#modalMarkReceived').modal('show');
                    $('#message').html('');
                } else {
                    let errorMsg = 'Invalid response from server';
                    if (e && e.message) {
                        errorMsg = e.message;
                    }
                    $('#message').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                btn.prop('disabled', false);
                $('#message').html('<div class="alert alert-danger">Error loading residence data: ' + error + '</div>');
            }
        });
    });

    // Mark as Delivered button - Use event delegation
    $(document).on('click', '.btn-setEidDelivered', function() {
        var btn = $(this);
        var id = btn.data('id');
        var type = btn.data('type');
        
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure you want to mark this Emirates ID as delivered?',
            buttons: {
                confirm: function() {
                    $.ajax({
                        url: 'eidTasksController.php',
                        type: 'POST',
                        data: {
                            action: 'setMarkDelivered',
                            id: id,
                            type: type
                        },
                        success: function(e) {
                            $('#row-' + id).fadeOut('slow', function() {
                                $(this).remove();
                            });
                            $("#message").html('<div class="alert alert-success">' + e.message + '</div>');
                        }
                    });
                },
                cancel: function() {}
            }
        });
    });

    // Form submission with debug logging
    $('.frmAjax').on('submit', function(e) {
        e.preventDefault();
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
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
                console.log('Raw Response:', e);
                btn.attr('disabled', false).html(btn.attr('data-temp-text'));
                if (e.status == 'success') {
                    console.log('Success detected');
                    if (frm.attr('data-delete-row') == 'true') {
                        $('#row-' + frm.attr('data-id')).fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }
                    $('#' + frm.data('popup')).modal('hide');
                    $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
                } else {
                    console.log('Error or invalid response');
                    if (e.message == 'form_errors') {
                        $.each(e.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value);
                        });
                    } else {
                        $('#' + frm.attr('data-msg')).html('<div class="alert alert-danger">' + (e.message || 'Unknown error') + '</div>');
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                btn.attr('disabled', false).html(btn.attr('data-temp-text'));
                $('#message').html('<div class="alert alert-danger">An error occurred while saving: ' + error + '</div>');
            }
        });
    });
});
</script>