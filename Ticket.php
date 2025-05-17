<?php
  include 'header.php';
?>
<style>
  .select2-selection__rendered {
    line-height: 31px !important;
}

.select2-container .select2-selection--single {
    height: 35px !important;
}

.select2-selection__arrow {
    height: 34px !important;
}
.nav-tabs .nav-link.active{
    color:red;
}

.autofill-field {
    background-color: #ffe8e8 !important;
    border-left: 3px solid #e74a3b !important;
}

.autofill-indicator {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 18px;
    color: #e74a3b;
}

/* Professional Form Styling */
.form-section {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    padding: 15px 20px;
    border-top: 4px solid #e74a3b;
}

.section-header {
    border-bottom: 1px solid #e3e6f0;
    padding-bottom: 10px;
    margin-bottom: 15px;
    font-weight: 600;
    color: #333;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.section-header i {
    margin-right: 8px;
    color: #e74a3b;
}

.form-control, .select2-container--default .select2-selection--single {
    border-radius: 4px;
    border: 1px solid #d1d3e2;
    padding: 6px 10px;
    font-size: 14px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .select2-container--focus .select2-selection--single {
    border-color: #f3aeae;
    box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25);
}

.form-label {
    font-weight: 600;
    font-size: 13px;
    color: #444;
    margin-bottom: 4px;
}

.btn-primary {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-primary:hover {
    background-color: #c7271a;
    border-color: #c7271a;
}

.btn-danger {
    background-color: #e74a3b;
    border-color: #e74a3b;
}

.btn-danger:hover {
    background-color: #d52a1a;
    border-color: #d52a1a;
}

.badge {
    cursor: pointer;
}

input:disabled, select:disabled {
    background-color: #f8f9fc !important;
}

/* Compact form styling */
.form-group {
    margin-bottom: 0.75rem;
}

.compact-control {
    padding: 4px 8px;
    height: calc(1.5em + 0.5rem + 2px);
    font-size: 0.875rem;
}

.compact-label {
    margin-bottom: 0.25rem;
    font-size: 0.75rem;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    display: none;
}

.spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #e74a3b;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Add/Delete passenger buttons */
.passenger-action {
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-top: 28px;
}

.passenger-field-row {
    background-color: #f8f9fc;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 10px;
    border-left: 3px solid #e74a3b;
}

/* Cleaner table */
.table-ticket {
    font-size: 0.85rem;
}

.table-ticket thead {
    background-color: #e74a3b;
    color: white;
}
</style>
<title>Ticket Report</title>
<?php
  include 'nav.php';
  include 'connection.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }

  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Ticket' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$insert = $insert[0]['insert'];
if($insert == 0){
echo "<script>window.location.href='pageNotFound.php'</script>";
  
}
?>
<ul class="nav nav-tabs">
  <li class="nav-item">
    <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active"><i class="fa fa-ticket"></i> Ticket Entry Form</a>
  </li>
  <li class="nav-item">
    <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link"><i class="fa fa-user"></i> Customer Entry Form</a>
  </li>
</ul>
<div class="tab-content bg-white pt-4 px-4 rounded-bottom">
  <div class="tab-pane fade active show" id="default-tab-1">
    <div class="panel text-white">
    <div class="panel-heading bg-inverse ">
            <h4 class="panel-title"><i class="fa fa-info"></i> Ticket <code> Information <i class="fa fa-arrow-down"></i></code></h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" onClick="resetForm()" class="btn btn-xs btn-icon btn-success" ><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger " data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
            </div>
    </div>
    <div class="panel-body p-3">
        <!-- here -->
        <form method="post" enctype="multipart/form-data" id="addTicket">
           
            <!-- Customer & PNR Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fa fa-user-circle"></i> Customer Information
                </div>
                <div class="row mb-2">
                    <div class="col-lg-4">
                        <label class="form-label compact-label" for="cust_name"><i class="fa fa-user"></i> Customer Name</label>  
                        <select class="form-control compact-control js-example-basic-single" onchange="getPayments()" style="width:100%" id="cust_name" name="cust_name" spry:default="select one"></select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label compact-label" for="pnr"><i class="fa fa-archive"></i> PNR</label>
                        <input type="text" id="pnr" class="form-control compact-control" name="pnr" placeholder="Enter PNR" />
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="supplier"><i class="fa fa-building"></i> Supplier</label> 
                        <select class="form-control compact-control js-example-basic-single" style="width:100%" id="supplier" name="supplier" spry:default="select one"></select>
                    </div>
                    <div class="col-lg-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#bookingSegmentModal">
                            <i class="fa fa-paste"></i> Paste Booking Segment
                        </button>
                        <div id="duePaymentDiv" class="d-none ms-2">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-credit-card"></i> Pending
                                </button>
                                <ul class="dropdown-menu" id="total_charge"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal for Booking Segment -->
            <div class="modal fade" id="bookingSegmentModal" tabindex="-1" aria-labelledby="bookingSegmentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="bookingSegmentModalLabel"><i class="fa fa-file-text"></i> Booking Segment Parser</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="booking_segment" class="form-label">Paste the complete booking segment information below:</label>
                                <textarea class="form-control" id="booking_segment" rows="8" placeholder="Paste booking segment information here"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" onclick="parseBookingSegment()">
                                <i class="fa fa-magic"></i> Parse Booking Information
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trip Details Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fa fa-plane"></i> Trip Details
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3">
                        <label class="form-label compact-label"><i class="fa fa-exchange"></i> Trip Type</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" checked name="FT" id="o-w">
                                <label class="form-check-label text-inverse" for="o-w">One-way</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="FT" id="r_t">
                                <label class="form-check-label text-inverse" for="r_t">Round-trip</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label compact-label" for="from"><i class="fa fa-map-marker"></i> From</label>
                        <select class="form-control compact-control js-example-basic-single" style="width:100%" id="from" name="from" spry:default="select one"></select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label compact-label" for="to"><i class="fa fa-map-marker"></i> To</label>
                        <select class="form-control compact-control js-example-basic-single" style="width:100%" id="to" name="to" spry:default="select one"></select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label compact-label" for="flight_number"><i class="fa fa-plane"></i> Outbound Flight</label> 
                        <input type="text" id="flight_number" class="form-control compact-control" name="flight_number" placeholder="Enter Flight Number" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3" id="return_flight_div" style="display:none">
                        <label class="form-label compact-label" for="return_flight_number"><i class="fa fa-plane"></i> Return Flight</label> 
                        <input type="text" id="return_flight_number" class="form-control compact-control" name="return_flight_number" placeholder="Enter Return Flight" />
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="date_of_travel"><i class="fa fa-calendar"></i> Travel Date</label>
                        <input type="date" class="form-control compact-control" name="date_of_travel" id="date_of_travel">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="return_date"><i class="fa fa-calendar"></i> Return Date</label>
                        <input type="date" class="form-control compact-control" name="return_date" disabled id="return_date">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="departure_time"><i class="fa fa-clock-o"></i> Departure</label> 
                        <input type="time" class="form-control compact-control" name="departure_time" id="departure_time" placeholder="HH:MM" />
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="arrival_time"><i class="fa fa-clock-o"></i> Arrival</label> 
                        <input type="time" class="form-control compact-control" name="arrival_time" id="arrival_time" placeholder="HH:MM" />
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3" id="return_departure_div" style="display:none">
                        <label class="form-label compact-label" for="return_departure_time"><i class="fa fa-clock-o"></i> Return Departure</label> 
                        <input type="time" class="form-control compact-control" name="return_departure_time" id="return_departure_time" placeholder="HH:MM" />
                    </div>
                    <div class="col-lg-3" id="return_arrival_div" style="display:none">
                        <label class="form-label compact-label" for="return_arrival_time"><i class="fa fa-clock-o"></i> Return Arrival</label> 
                        <input type="time" class="form-control compact-control" name="return_arrival_time" id="return_arrival_time" placeholder="HH:MM" />
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label compact-label" for="remarks"><i class="fa fa-comment"></i> Remarks</label>
                        <textarea class="form-control compact-control" placeholder="Write remarks here" id="remarks" name="remarks" rows="1"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Passenger Section -->
            <div class="form-section">
                <div class="section-header d-flex justify-content-between align-items-center">
                    <div><i class="fa fa-users"></i> Passenger Details</div>
                    <button type="button" class="btn btn-sm btn-primary passenger-action" onclick="addElement()">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
                
                <div class="passenger-field-row">
                    <div class="row mb-2">
                        <div class="col-lg-3">
                            <label class="form-label compact-label" for="passenger_name1"><i class="fa fa-user"></i> Passenger Name</label>  
                            <input type="text" class="form-control compact-control controlCounter" id="passenger_name1" name="passenger_name1" placeholder="Passenger Name">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label compact-label" for="ticket_number1"><i class="fa fa-ticket"></i> Ticket Number</label>
                            <input type="text" id="ticket_number1" class="form-control compact-control" name="ticket_number1" placeholder="Ticket Number" />
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label compact-label" for="net_amount1"><i class="fa fa-dollar"></i> Net Amount</label> 
                            <input type="number" class="form-control compact-control" name="net_amount1" id="net_amount1" placeholder="Net Amount"  />
                        </div>
                        <div class="col-lg-1">
                            <label class="form-label compact-label" for="net_currency_type1"><i class="fa fa-dollar"></i> Currency</label> 
                            <select class="form-control compact-control js-example-basic-single" style="width:100%" id="net_currency_type1" name="net_currency_type1" spry:default="select one"></select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label compact-label" for="sale_amount1"><i class="fa fa-dollar"></i> Sale Amount</label> 
                            <input type="number" class="form-control compact-control" name="sale_amount1" id="sale_amount1" placeholder="Sale Amount"  />
                        </div>
                        <div class="col-lg-1">
                            <label class="form-label compact-label" for="currency_type1"><i class="fa fa-dollar"></i> Currency</label> 
                            <select class="form-control compact-control js-example-basic-single" style="width:100%" id="currency_type1" name="currency_type1" spry:default="select one"></select>
                        </div>
                    </div>
                </div>
                
                <div id="target"></div>
            </div>
            
            <!-- Payment Section -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fa fa-credit-card"></i> Payment Information
                </div>
                <div class="row mb-2">
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="cus_payment"><i class="fa fa-dollar"></i> Customer Payment</label> 
                        <input type="number" class="form-control compact-control" name="cus_payment" id="cus_payment" placeholder="Enter Amount"  />
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label compact-label" for="payment_currency_type"><i class="fa fa-dollar"></i> Currency</label>
                        <select class="form-control compact-control js-example-basic-single" style="width:100%" id="payment_currency_type" name="payment_currency_type" spry:default="select one"></select> 
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label compact-label" for="addaccount_id"><i class="fa fa-bank"></i> Account</label>
                        <select class="form-control compact-control js-example-basic-single" style="width:100%" name="addaccount_id" id="addaccount_id"></select>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label compact-label" for="uploadFile"><i class="fa fa-file"></i> Ticket Copy</label>
                        <input type="file" class="form-control compact-control" id="uploadFile" name="uploadFile">
                    </div>
                </div>
            </div>
            
            <div class="panel-footer mt-2 text-white d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-save"></i> Insert Record
                    </button>
                    <a class="btn btn-sm btn-secondary ms-2" href="view ticket.php">
                        <i class="fa fa-info text-white"></i> View Report
                    </a>
                    <a class="btn btn-sm btn-secondary ms-2" href="pendingticket.php">
                        <i class="fa fa-credit-card text-white"></i> Pending Payments
                    </a>
                </div>
            </div>
            
            <hr style="color:black">
            <h4 class="text-dark"><i class="fa fa-list"></i> Today's Ticket Report</h4>
            <hr style="color:black">
            
            <table class="table table-striped table-hover table-ticket">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Passenger Name</th>
                        <th scope="col">PNR</th>
                        <th scope="col">Ticket Number</th>
                        <th scope="col">Flight Number</th>
                        <th scope="col">From</th>
                        <th scope="col">To</th>
                        <th scope="col">Date of Travel</th>
                        <th scope="col">Sale Price</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Remarks</th>
                    </tr>
                </thead>
                <tbody id="ticketRptTblBody">
                </tbody>
            </table>
        </form>
        <!-- end here -->
    </div>
  </div>
 </div>
  <div class="tab-pane fade show" id="default-tab-2">
  <div class="panel panel-inverse">
        <div class="panel-heading bg-red-600 text-white">
            <h4 class="panel-title"><i class="fa fa-info"></i> Customer <code> Information <i class="fa fa-arrow-down"></i></code></h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
            </div>
        </div>
        <div class="panel-body">
            <form>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-1">
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-user text-red"></i> Customer Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="cus_name" class="form-control" placeholder="Customer Name" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-1">
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-phone text-red"></i> Customer Phone</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="cus_phone" class="form-control" placeholder="Customer Phone" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-1">
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-whatsapp text-red"></i> Customer Whatsapp</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="cus_whatsapp" class="form-control" placeholder="Customer Whatsapp" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-1">
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-address-book text-red"></i> Customer Address</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="cus_address" class="form-control" placeholder="Customer Address" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-md-3 offset-1">
                        <button type="button" onclick="addCustomer()" class="btn btn-inverse"><i class="fa fa-save"></i> Add Customer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>
<?php include 'footer.php'; ?>
<script>
    function addCustomer(){
    var insert ="INSERT";
    var cus_name = $('#cus_name');
    if(cus_name.val() == ""){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    var cus_phone = $('#cus_phone');
    var cus_whatsapp = $('#cus_whatsapp');
    var cus_address = $('#cus_address');
        $.ajax({
            type: "POST",
            url: "ticketController.php",  
            data: {
                INSERT:insert,
                Cus_Name: cus_name.val(),
                Cus_Phone : cus_phone.val(),
                Cus_Whatsapp: cus_whatsapp.val(),
                Cus_Address: cus_address.val(),
            },
            success: function (response) {
                if(response != ""){
                    notify('Success!', response, 'success');
                    cus_name.val('');
                    cus_phone.val('');
                    cus_whatsapp.val('');
                    cus_address.val('');
                    getCustomers();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    $(document).ready(function(){
        getCustomers();
        $('.js-example-basic-single').select2();
        $("#r_t").click(function(){
            $('#return_date').removeAttr('disabled');
            $('#return_flight_div').show();
            $('#return_departure_div').show();
            $('#return_arrival_div').show();
        });
        $("#o-w").click(function(){
            $("#return_date").attr('disabled','disabled');
            $('#return_flight_div').hide();
            $('#return_departure_div').hide();
            $('#return_arrival_div').hide();
        });
        getFrom();
        getSupplier();
        getCurrencies();
        getAccounts('all',0);
        getTodaysTickets();
    
    });
    function resetForm(){
        $('#cust_name').val(-1).trigger('change.select2');
        $('#duePaymentDiv').addClass('d-none');
        $('#supplier').val(-1).trigger('change.select2');
        $('#from').val(-1).trigger('change.select2');
        $('#to').val(-1).trigger('change.select2');
        $('#addaccount_id').val(-1).trigger('change.select2');
        $('#payment_currency_type option:eq(0)').prop('selected',true);
        $('#net_currency_type1 option:eq(0)').prop('selected',true);
        $('#currency_type1 option:eq(0)').prop('selected',true);
        $('#passenger_name1').val('');
        $('#ticket_number1').val('');
        $('#net_amount1').val('');
        $('#sale_amount1').val('');
        $('#pnr').val('');
        $('#date_of_travel').val('');
        $('#return_date').val('');
        $('#cus_payment').val(''); 
        $('#uploadFile').val('');
        $('#flight_number').val('');
        $('#return_flight_number').val('');
        $('#departure_time').val('');
        $('#arrival_time').val('');
        $('#return_departure_time').val('');
        $('#return_arrival_time').val('');
        $('#remarks').val('');
        $('#booking_segment').val('');
        
        // Reset to one-way
        $('#o-w').prop('checked', true);
        $('#r_t').prop('checked', false);
        $('#return_date').attr('disabled','disabled');
        $('#return_flight_div').hide();
        $('#return_departure_div').hide();
        $('#return_arrival_div').hide();
        
        // Clear additional passenger fields by removing the content in target div
        $('#target').empty();
    }
    function getCustomers(){
    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
        },
        success: function (response) {  
            var customer = JSON.parse(response);
            $('#cust_name').empty();
            $('#cust_name').append("<option value='-1'>--Customer--</option>");
            for(var i=0; i<customer.length; i++){
              $('#cust_name').append("<option value='"+ customer[i].customer_id +"'>"+ 
              customer[i].customer_name +"</option>");
            }
        },
    });
    }
    function getFrom(){
    var select_from = "SELECT_FROM";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            SELECT_FROM:select_from,
        },
        success: function (response) {  
            var from = JSON.parse(response);
            $('#from').empty();
            $('#to').empty();
            $('#to').append("<option value='-1'>--Arrival--</option>");
            $('#from').append("<option value='-1'>--Departure--</option>");
            for(var i=0; i<from.length; i++){
              $('#from').append("<option value='"+ from[i].airport_id +"'>"+ 
              from[i].airport_code +"</option>");
              $('#to').append("<option value='"+ from[i].airport_id +"'>"+ 
              from[i].airport_code +"</option>");
            }
        },
    });
    }
    function getSupplier(){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            $('#supplier').empty();
            $('#supplier').append("<option value='-1'>--Supplier--</option>");
            for(var i=0; i<supplier.length; i++){
              $('#supplier').append("<option value='"+ supplier[i].supp_id +"'>"+ 
              supplier[i].supp_name +"</option>");
            }
        },
    });
    }
    function getAccounts(type,id){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
            if(type == 'all'){
              $('#addaccount_id').empty();
              $('#addaccount_id').append("<option value='-1'>--Account--</option>");
              for(var i=0; i<account.length; i++){
                $('#addaccount_id').append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }else{
              $('#updaccount_id').empty();
              $('#updaccount_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#updaccount_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }
        },
    });
    }
    $(document).on('submit', '#addTicket', function(event){
    event.preventDefault();
    
    // Show loading overlay
    $('#loadingOverlay').show();
    
    var cust_name = $('#cust_name').select2('data');
    if(cust_name[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var controlCounter = $('.controlCounter').length;
    var passArr = [];
    var ticketNumberArr = [];
    var netAmountArr = [];
    var netPriceCurrencyArr = [];
    var saleAmountArr = [];
    var salePriceCurrencyArr = [];
    for(var i =1;i<=controlCounter; i++){
        passArr.push($('#passenger_name'+[i]).val());
        ticketNumberArr.push($('#ticket_number'+[i]).val());
        netAmountArr.push($('#net_amount'+[i]).val());
        netPriceCurrencyArr.push($('#net_currency_type'+[i]).select2('data')[0].id);
        saleAmountArr.push($('#sale_amount'+[i]).val());
        salePriceCurrencyArr.push($('#currency_type'+[i]).select2('data')[0].id);
    }
    if(passArr.includes("")){
        passArr = [];
        notify('Validation Error!', 'Passenger name is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    if(ticketNumberArr.includes("")){
        ticketNumberArr = [];
        notify('Validation Error!', 'Ticket number is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    if(netAmountArr.includes("")){
        netAmountArr = [];
        notify('Validation Error!', 'Net price is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    if(saleAmountArr.includes("")){
        saleAmountArr = [];
        notify('Validation Error!', 'Sale price is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var pnr = $('#pnr');
    if(pnr.val() == ''){
        notify('Validation Error!', 'Pnr is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var date_of_travel = $('#date_of_travel');
    if(date_of_travel.val() == ""){
        notify('Validation Error!', 'Date of travel is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }

    // Set the Flight_type field based on selected radio
    var flight_type = $('#r_t').is(':checked') ? 'RT' : 'OW';
    
    var return_date = $('#return_date');
    var from = $('#from').select2('data');
    if(from[0].id == "-1"){
        notify('Validation Error!', 'Departure is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var to = $('#to').select2('data');
    if(to[0].id == "-1"){
        notify('Validation Error!', 'Arrival is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var supplier = $('#supplier').select2('data');
    if(supplier[0].id == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        $('#loadingOverlay').hide();
        return;
    }
    var cus_payment = $('#cus_payment');
    var addaccount_id = $('#addaccount_id');
    var payment_currency_type = $('#payment_currency_type').select2('data');
    if(cus_payment.val() > 0){
      if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        $('#loadingOverlay').hide();
        return;
      } 
      if(payment_currency_type[0].id == "-1"){
        notify('Validation Error!', 'Payment currency type is required', 'error');
        $('#loadingOverlay').hide();
        return;
      } 
    }
    var ticket = $('#uploadFile').val();
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
        $('#loadingOverlay').hide();
        return;
      }
    }
      data = new FormData(this);
      data.append('Insert_Ticket','Insert_Ticket');
      data.append('passArr',JSON.stringify(passArr));
      data.append('ticketNumberArr',JSON.stringify(ticketNumberArr));
      data.append('netAmountArr',JSON.stringify(netAmountArr));
      data.append('netPriceCurrencyArr',JSON.stringify(netPriceCurrencyArr));
      data.append('saleAmountArr',JSON.stringify(saleAmountArr));
      data.append('salePriceCurrencyArr',JSON.stringify(salePriceCurrencyArr));
      data.append('Flight_type', flight_type);
        $.ajax({
            type: "POST",
            url: "ticketController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                getTodaysTickets();
                // Hide loading overlay
                $('#loadingOverlay').hide();
                
                if(response == "Success"){
                    notify('Success!', 'Ticket added successfully', 'success');
                    
                    // First clear any additional passenger fields
                    $('#target').empty();
                    
                    // Then reset all form fields
                    resetForm();
                    
                }else{
                    notify('Error!', response, 'error');
                }
            },
            error: function() {
                // Hide loading overlay
                $('#loadingOverlay').hide();
                notify('Error!', 'An error occurred during submission', 'error');
            }
        });
    });
    function addElement(){       
        $('#Passenger_control_margin').addClass("mb-3");
        var controlCounter = $('.controlCounter').length +1;
        
        // Create the new passenger row
        var newPassengerRow = $('<div class="passenger-field-row"></div>');
        var rowContent = $('<div class="row mb-2"></div>');
        
        // First column - Passenger name
        var passengerNameCol = $('<div class="col-lg-3"></div>');
        passengerNameCol.append('<label class="form-label compact-label" for="passenger_name' + controlCounter + '"><i class="fa fa-user"></i> Passenger Name</label>');
        passengerNameCol.append('<input type="text" class="form-control compact-control controlCounter" id="passenger_name' + controlCounter + '" name="passenger_name' + controlCounter + '" placeholder="Passenger Name">');
        
        // Second column - Ticket Number
        var ticketNumberCol = $('<div class="col-lg-3"></div>');
        ticketNumberCol.append('<label class="form-label compact-label" for="ticket_number' + controlCounter + '"><i class="fa fa-ticket"></i> Ticket Number</label>');
        ticketNumberCol.append('<input type="text" id="ticket_number' + controlCounter + '" class="form-control compact-control" name="ticket_number' + controlCounter + '" placeholder="Ticket Number" />');
        
        // Third column - Net Amount
        var netAmountCol = $('<div class="col-lg-2"></div>');
        netAmountCol.append('<label class="form-label compact-label" for="net_amount' + controlCounter + '"><i class="fa fa-dollar"></i> Net Amount</label>');
        netAmountCol.append('<input type="number" class="form-control compact-control" name="net_amount' + controlCounter + '" id="net_amount' + controlCounter + '" placeholder="Net Amount" />');
        
        // Fourth column - Net Currency
        var netCurrencyCol = $('<div class="col-lg-1"></div>');
        netCurrencyCol.append('<label class="form-label compact-label" for="net_currency_type' + controlCounter + '"><i class="fa fa-dollar"></i> Currency</label>');
        netCurrencyCol.append('<select class="form-control compact-control js-example-basic-single" style="width:100%" id="net_currency_type' + controlCounter + '" name="net_currency_type' + controlCounter + '" spry:default="select one"></select>');
        
        // Fifth column - Sale Amount
        var saleAmountCol = $('<div class="col-lg-2"></div>');
        saleAmountCol.append('<label class="form-label compact-label" for="sale_amount' + controlCounter + '"><i class="fa fa-dollar"></i> Sale Amount</label>');
        saleAmountCol.append('<input type="number" class="form-control compact-control" name="sale_amount' + controlCounter + '" id="sale_amount' + controlCounter + '" placeholder="Sale Amount" />');
        
        // Sixth column - Sale Currency
        var saleCurrencyCol = $('<div class="col-lg-1"></div>');
        saleCurrencyCol.append('<label class="form-label compact-label" for="currency_type' + controlCounter + '"><i class="fa fa-dollar"></i> Currency</label>');
        saleCurrencyCol.append('<select class="form-control compact-control js-example-basic-single" style="width:100%" id="currency_type' + controlCounter + '" name="currency_type' + controlCounter + '" spry:default="select one"></select>');
        
        // Delete button
        var deleteButtonCol = $('<div class="col-lg-0 d-flex align-items-center mt-4"></div>');
        var deleteButton = $('<button type="button" class="btn btn-sm btn-danger passenger-action" onclick="deleteElement(\'passenger_name' + controlCounter + '\')"><i class="fa fa-trash"></i></button>');
        deleteButtonCol.append(deleteButton);
        
        // Assemble row
        rowContent.append(passengerNameCol);
        rowContent.append(ticketNumberCol);
        rowContent.append(netAmountCol);
        rowContent.append(netCurrencyCol);
        rowContent.append(saleAmountCol);
        rowContent.append(saleCurrencyCol);
        rowContent.append(deleteButtonCol);
        
        newPassengerRow.append(rowContent);
        
        // Add new row to target
        $('#target').append(newPassengerRow);
        
        // Initialize select2 and populate currency dropdowns
        $('.js-example-basic-single').select2();
        getCurrencies();
    }
    function deleteElement(id) {
        // Find the passenger row container and remove it
        $('#' + id).closest('.passenger-field-row').remove();
    }
   
    function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
            var controlCounter = $('.controlCounter').length;
            for(var j = 0; j< controlCounter; j++ ){
                $('#currency_type'+controlCounter).empty();
                $('#payment_currency_type').empty();
                $('#net_currency_type'+controlCounter).empty();
                for(var i=0; i<currencyType.length; i++){
                    
                if(i==0){
                  selected = "selected";
                  $('#currency_type'+controlCounter).append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#payment_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#net_currency_type'+controlCounter).append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#currency_type'+controlCounter).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#payment_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#net_currency_type'+controlCounter).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
            }
        },
    });
    }
    function getPayments(){
    var customer_id = $("#cust_name option:selected").val();
    var payments = 'Payments';
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            Payments:payments,
            Customer_ID:customer_id
        },
        success: function (response) {  
            var payment = JSON.parse(response);
            $('#total_charge').empty();
            // <li><a class="dropdown-item" href="#">Action</a></li>
            //                 
            if(payment.length > 0){
              for(var i=0; i<payment.length; i++){
              $('#total_charge').append("<li><a class='dropdown-item' href='#'>"+ numeral(payment[i].total).format('0,0') + " " + payment[i].curName + "</a></li>");
              if(i != payment.length -1){
                $('#total_charge').append("<li><hr class='dropdown-divider'></li>");
              }
              
              }
            }else{
              $('#total_charge').append("<p style='font-size:15px'><b>Customer has no due payment <i style='font-size:15px' class='fa fa-smile-o' aria-hidden='true'></i></b></p>");
            }
        },
    });
    $('#duePaymentDiv').removeClass('d-none');
    }
    function getTodaysTickets(){
        var getTodaysTickets = 'getTodaysTickets';
        $.ajax({
            type: "POST",
            url: "ticketController.php",  
            data: {
                GetTodaysTickets:getTodaysTickets,
            },
            success: function (response) {  
                var todaysTicketRpt = JSON.parse(response);
                let finalTable ='';
                let j = 1;
                $('#ticketRptTblBody').empty();                 
                if(todaysTicketRpt.length > 0){
                    for(var i=0; i<todaysTicketRpt.length; i++){
                        finalTable += '<tr><th scope="row">' + j + '</th><td>' + todaysTicketRpt[i].customer_name + '</td><td>' +
                        todaysTicketRpt[i].passenger_name + '</td><td>' + todaysTicketRpt[i].Pnr + '</td><td>' + 
                        todaysTicketRpt[i].ticketNumber + '</td><td>' + (todaysTicketRpt[i].flight_number || '') + '</td><td>' + 
                        todaysTicketRpt[i].depature + '</td><td>' + todaysTicketRpt[i].arrival + '</td><td>' + 
                        todaysTicketRpt[i].dateofTravel + '</td><td>' + 
                        numeral(todaysTicketRpt[i].sale).format('0,0') + ' ' + todaysTicketRpt[i].currencyName  +'</td><td>' + 
                        todaysTicketRpt[i].supplierName + '</td><td>' + todaysTicketRpt[i].remarks + '</td></tr>';
                        j++;
                    }
                    $('#ticketRptTblBody').append(finalTable);
                }else{
                    $('#ticketRptTblBody').append("<tr><td></td><td></td><td></td><td></td><td></td><td></td><td><h5>No Data Entered!</h5></td><td></td><td></td><td></td><td></td><td></td></tr>");
                }
            },
        });
    }
    
    function parseBookingSegment() {
        const segmentText = $('#booking_segment').val().trim();
        
        if (!segmentText) {
            notify('Error!', 'Please paste booking segment information', 'error');
            return;
        }
        
        // Helper function to format passenger name
        function formatPassengerName(lastName, firstName) {
            // Extract title (MR, MRS, etc.) if present and remove it from the first name
            let firstNameClean = firstName;
            
            // Common titles to look for and remove
            const titles = ['MR', 'MRS', 'MS', 'DR', 'MISS', 'MSTR', 'PROF'];
            
            // Remove title regardless of whether it's at the end or beginning
            titles.forEach(t => {
                // Check for title at the end with space (e.g., "JOHN MR")
                if (firstNameClean.endsWith(' ' + t)) {
                    firstNameClean = firstNameClean.replace(' ' + t, '');
                }
                // Check for title at the beginning with space (e.g., "MR JOHN")
                if (firstNameClean.startsWith(t + ' ')) {
                    firstNameClean = firstNameClean.replace(t + ' ', '');
                }
                // Check for just the title
                if (firstNameClean === t) {
                    firstNameClean = '';
                }
            });
            
            // Trim any extra spaces
            firstNameClean = firstNameClean.trim();
            
            // Format as "FIRSTNAME LASTNAME"
            return firstNameClean ? `${firstNameClean} ${lastName}` : lastName;
        }
        
        // Track which fields are filled for visual feedback
        const filledFields = [];
        
        // Extract PNR (more flexible pattern to match formats like "C274L7/MN DXBOU")
        const pnrMatch = segmentText.match(/^([A-Z0-9]{6})\/[A-Z]{2}/) || segmentText.match(/^([A-Z0-9]{6})/) || segmentText.match(/([A-Z0-9]{6})\/[A-Z]{2}/);
        if (pnrMatch && pnrMatch[1]) {
            $('#pnr').val(pnrMatch[1]);
            filledFields.push('#pnr');
        }
        
        // Extract multiple passengers - find all patterns like "1.1LASTNAME/FIRSTNAME MR"
        let passengerRegex = /(\d+\.\d+)([A-Z]+)\/([A-Z\s]+)/gi;
        let passengerMatch;
        let passengers = [];
        
        while ((passengerMatch = passengerRegex.exec(segmentText)) !== null) {
            passengers.push({
                index: passengerMatch[1], // e.g., "1.1"
                lastName: passengerMatch[2].trim(),
                firstName: passengerMatch[3].trim()
            });
        }
        
        // If we found passengers, process them
        if (passengers.length > 0) {
            // Sort passengers by their index if needed
            passengers.sort((a, b) => {
                return a.index.localeCompare(b.index);
            });
            
            // First passenger goes into the existing field
            if (passengers.length >= 1) {
                $('#passenger_name1').val(formatPassengerName(passengers[0].lastName, passengers[0].firstName));
                filledFields.push('#passenger_name1');
            }
            
            // For passengers 2 and beyond, add new fields and fill them
            if (passengers.length > 1) {
                // Get current number of passenger fields
                let currentFields = $('.controlCounter').length;
                
                // Add enough new passenger fields
                for (let i = currentFields; i < passengers.length; i++) {
                    addElement(); // Call the function that adds a new passenger field
                }
                
                // Now fill in all the additional passenger fields
                for (let i = 1; i < passengers.length; i++) {
                    const fieldNum = i + 1; // passenger_name2, passenger_name3, etc.
                    const formattedName = formatPassengerName(passengers[i].lastName, passengers[i].firstName);
                    $(`#passenger_name${fieldNum}`).val(formattedName);
                    filledFields.push(`#passenger_name${fieldNum}`);
                }
            }
        } else {
            // Fallback to the old method if no passengers found with the new regex
            const singlePassengerMatch = segmentText.match(/\d+\.\d+([A-Z]+)\/([A-Z\s]+)/i);
            if (singlePassengerMatch) {
                const lastName = singlePassengerMatch[1].trim();
                const firstName = singlePassengerMatch[2].trim();
                
                // Format and set the passenger name
                $('#passenger_name1').val(formatPassengerName(lastName, firstName));
                filledFields.push('#passenger_name1');
            }
        }
        
        // Extract flight details with more flexible regex
        // Format: "1. PK  222 U  12MAY DXBMUX HK1  1745   2130  O*         MO"
        const flightMatch = segmentText.match(/\d+\.\s+([A-Z]{2})\s+(\d+)\s+([A-Z])\s+(\d+[A-Z]{3})\s+([A-Z]{3})([A-Z]{3})\s+([A-Z]{2}\d+)\s+(\d{4})\s+(\d{4})/i);
        
        // Try to find multiple flight segments for roundtrip detection
        const flightSegments = [];
        let flightSegmentRegex = /(\d+)\.\s+([A-Z]{2})\s+(\d+)\s+([A-Z])\s+(\d+[A-Z]{3})\s+([A-Z]{3})([A-Z]{3})\s+([A-Z]{2}\d+)\s+(\d{4})\s+(\d{4})/gi;
        let segmentMatch;
        
        while ((segmentMatch = flightSegmentRegex.exec(segmentText)) !== null) {
            flightSegments.push({
                segmentNumber: parseInt(segmentMatch[1]),
                airline: segmentMatch[2],
                flightNumber: segmentMatch[3],
                flightClass: segmentMatch[4],
                travelDate: segmentMatch[5],
                from: segmentMatch[6],
                to: segmentMatch[7],
                status: segmentMatch[8],
                departureTime: segmentMatch[9],
                arrivalTime: segmentMatch[10]
            });
        }
        
        // If we have multiple flight segments, check if it's a roundtrip
        if (flightSegments.length >= 2) {
            // Sort segments by segment number
            flightSegments.sort((a, b) => a.segmentNumber - b.segmentNumber);
            
            const firstSegment = flightSegments[0];
            const secondSegment = flightSegments[1];
            
            // Check if second segment is a return flight (reversed origin/destination)
            if (firstSegment.from === secondSegment.to && firstSegment.to === secondSegment.from) {
                // It's a roundtrip - select the roundtrip radio button
                $('#r_t').prop('checked', true);
                $('#o-w').prop('checked', false);
                
                // Show return flight fields
                $('#return_date').removeAttr('disabled');
                $('#return_flight_div').show();
                $('#return_departure_div').show();
                $('#return_arrival_div').show();
                
                // Set outbound flight number
                $('#flight_number').val(`${firstSegment.airline}${firstSegment.flightNumber}`);
                filledFields.push('#flight_number');
                
                // Set return flight number
                $('#return_flight_number').val(`${secondSegment.airline}${secondSegment.flightNumber}`);
                filledFields.push('#return_flight_number');
                
                // Format and set flight times
                // Convert 24-hour time format (HHMM) to HH:MM format
                const outboundDepartureTime = formatTimeString(firstSegment.departureTime);
                const outboundArrivalTime = formatTimeString(firstSegment.arrivalTime);
                const returnDepartureTime = formatTimeString(secondSegment.departureTime);
                const returnArrivalTime = formatTimeString(secondSegment.arrivalTime);
                
                // Set time fields
                $('#departure_time').val(outboundDepartureTime);
                $('#arrival_time').val(outboundArrivalTime);
                $('#return_departure_time').val(returnDepartureTime);
                $('#return_arrival_time').val(returnArrivalTime);
                
                filledFields.push('#departure_time');
                filledFields.push('#arrival_time');
                filledFields.push('#return_departure_time');
                filledFields.push('#return_arrival_time');
                
                // Format and set return date
                const monthMap = {
                    'JAN': '01', 'FEB': '02', 'MAR': '03', 'APR': '04', 'MAY': '05', 'JUN': '06',
                    'JUL': '07', 'AUG': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DEC': '12'
                };
                
                const returnTravelDate = secondSegment.travelDate;
                if (returnTravelDate) {
                    const day = returnTravelDate.substring(0, 2);
                    const monthText = returnTravelDate.substring(2, 5);
                    const month = monthMap[monthText.toUpperCase()];
                    const year = new Date().getFullYear();
                    
                    if (day && month) {
                        $('#return_date').val(`${year}-${month}-${day}`);
                        filledFields.push('#return_date');
                    }
                }
                
                // Add both flight details to remarks
                const flightDetails = `Outbound: ${firstSegment.airline} ${firstSegment.flightNumber} Class: ${firstSegment.flightClass} | Departure: ${firstSegment.departureTime} Arrival: ${firstSegment.arrivalTime} | Return: ${secondSegment.airline} ${secondSegment.flightNumber} Class: ${secondSegment.flightClass} | Departure: ${secondSegment.departureTime} Arrival: ${secondSegment.arrivalTime}`;
                $('#remarks').val(flightDetails);
                filledFields.push('#remarks');
            }
        }
        
        // Helper function to format time string
        function formatTimeString(timeStr) {
            if (!timeStr || timeStr.length !== 4) return '';
            return `${timeStr.substring(0, 2)}:${timeStr.substring(2, 4)}`;
        }
        
        if (flightMatch && !filledFields.includes('#flight_number')) {
            const airline = flightMatch[1]; // e.g., PK
            const flightNumber = flightMatch[2]; // e.g., 222
            const flightClass = flightMatch[3]; // e.g., U
            const travelDate = flightMatch[4]; // e.g., 12MAY
            const from = flightMatch[5]; // e.g., DXB
            const to = flightMatch[6]; // e.g., MUX
            const status = flightMatch[7]; // e.g., HK1
            const departureTime = flightMatch[8]; // e.g., 1745
            const arrivalTime = flightMatch[9]; // e.g., 2130
            
            // Set the flight number field (airline code + flight number)
            $('#flight_number').val(`${airline}${flightNumber}`);
            filledFields.push('#flight_number');
            
            // Format flight times to HH:MM format
            const departureTimeFormatted = formatTimeString(departureTime);
            const arrivalTimeFormatted = formatTimeString(arrivalTime);
            
            // Set time fields
            $('#departure_time').val(departureTimeFormatted);
            $('#arrival_time').val(arrivalTimeFormatted);
            filledFields.push('#departure_time');
            filledFields.push('#arrival_time');
            
            // Format and set travel date
            const monthMap = {
                'JAN': '01', 'FEB': '02', 'MAR': '03', 'APR': '04', 'MAY': '05', 'JUN': '06',
                'JUL': '07', 'AUG': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DEC': '12'
            };
            
            if (travelDate) {
                const day = travelDate.substring(0, 2);
                const monthText = travelDate.substring(2, 5);
                const month = monthMap[monthText.toUpperCase()];
                const year = new Date().getFullYear();
                
                if (day && month) {
                    $('#date_of_travel').val(`${year}-${month}-${day}`);
                    filledFields.push('#date_of_travel');
                }
            }
            
            // Set from and to airports
            // Find and select airport codes in dropdowns
            if (from) {
                $("#from option").each(function() {
                    if ($(this).text() === from) {
                        $("#from").val($(this).val()).trigger('change.select2');
                        filledFields.push('#from');
                    }
                });
            }
            
            if (to) {
                $("#to option").each(function() {
                    if ($(this).text() === to) {
                        $("#to").val($(this).val()).trigger('change.select2');
                        filledFields.push('#to');
                    }
                });
            }
            
            // Only add flight details to remarks if it wasn't already set for roundtrip
            if (!filledFields.includes('#remarks')) {
                const flightDetails = `Flight: ${airline} ${flightNumber} Class: ${flightClass} | Departure: ${departureTime} Arrival: ${arrivalTime} | Status: ${status}`;
                $('#remarks').val(flightDetails);
                filledFields.push('#remarks');
            }
        } else {
            // Try alternative regex for flight details if the first one didn't match
            const altFlightMatch = segmentText.match(/([A-Z]{2})\s+(\d+)\s+([A-Z])\s+(\d+[A-Z]{3})\s+([A-Z]{3})([A-Z]{3})/i);
            
            // Also try to find passenger name if we didn't find it earlier
            if (!filledFields.includes('#passenger_name1')) {
                const altPassengerMatch = segmentText.match(/([A-Z]+)\/([A-Z\s]+)/i);
                if (altPassengerMatch) {
                    const lastName = altPassengerMatch[1].trim();
                    const firstName = altPassengerMatch[2].trim();
                    
                    // Format and set the passenger name
                    $('#passenger_name1').val(formatPassengerName(lastName, firstName));
                    filledFields.push('#passenger_name1');
                }
            }
            
            if (altFlightMatch) {
                const airline = altFlightMatch[1];
                const flightNumber = altFlightMatch[2];
                const flightClass = altFlightMatch[3];
                const travelDate = altFlightMatch[4];
                const from = altFlightMatch[5];
                const to = altFlightMatch[6];
                
                // Set the flight number field (airline code + flight number)
                $('#flight_number').val(`${airline}${flightNumber}`);
                filledFields.push('#flight_number');
                
                // Process date
                const monthMap = {
                    'JAN': '01', 'FEB': '02', 'MAR': '03', 'APR': '04', 'MAY': '05', 'JUN': '06',
                    'JUL': '07', 'AUG': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DEC': '12'
                };
                
                if (travelDate) {
                    const day = travelDate.substring(0, 2);
                    const monthText = travelDate.substring(2, 5);
                    const month = monthMap[monthText.toUpperCase()];
                    const year = new Date().getFullYear();
                    
                    if (day && month) {
                        $('#date_of_travel').val(`${year}-${month}-${day}`);
                        filledFields.push('#date_of_travel');
                    }
                }
                
                // Set airport codes
                if (from) {
                    $("#from option").each(function() {
                        if ($(this).text() === from) {
                            $("#from").val($(this).val()).trigger('change.select2');
                            filledFields.push('#from');
                        }
                    });
                }
                
                if (to) {
                    $("#to option").each(function() {
                        if ($(this).text() === to) {
                            $("#to").val($(this).val()).trigger('change.select2');
                            filledFields.push('#to');
                        }
                    });
                }
                
                // Add flight details to remarks
                const flightDetails = `Flight: ${airline} ${flightNumber} Class: ${flightClass}`;
                $('#remarks').val(flightDetails);
                filledFields.push('#remarks');
            }
        }
        
        // Try to extract ticket number if present
        const ticketMatch = segmentText.match(/TICKET\s*:\s*(\d{13})/i) || segmentText.match(/TKT\s*:\s*(\d{13})/i) || segmentText.match(/E-TKT\s*:\s*(\d{13})/i);
        if (ticketMatch && ticketMatch[1]) {
            $('#ticket_number1').val(ticketMatch[1]);
            filledFields.push('#ticket_number1');
        }
        
        // Add visual feedback for filled fields
        filledFields.forEach(field => {
            $(field).css('transition', 'background-color 0.5s');
            $(field).css('background-color', '#d4edda');
            setTimeout(() => {
                $(field).css('background-color', '');
            }, 1000);
        });
        
        notify('Success!', `${filledFields.length} fields were auto-filled from booking segment`, 'success');
        
        // Close the modal after successful parsing
        $('#bookingSegmentModal').modal('hide');
    }
</script>
</body>
</html>