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
    <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active"><i class="fa fa-building"></i> Ticket Entry Form</a>
  </li>
  <li class="nav-item">
    <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link"><i class="fa fa-building"></i> Company Entry Form</a>
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
           
            <div class="row mb-3">
                <!-- <div class="form-label col-form-label col-md-2 text-inverse"><i class="fa fa-user"></i> Customer Name:</div> -->
                <div class="col-lg-3">
                  <span class="form-label col-form-label text-inverse"><i class="fa fa-user"></i> Customer Name:</span>  <select class="col-sm-4 form-control js-example-basic-single"  onchange="getPayments()"  style="width:100%" id="cust_name" name="cust_name" spry:default="select one"></select>
                </div>
                <div class="col-lg-6 d-none" id="duePaymentDiv">
                        <span class="form-label col-form-label text-inverse"><i class="fa fa-barcode"></i> Total Charges:</span>
                            <div class="alert alert-primary" id="total_charge" style="max-height:10vh;overflow:scroll" role="alert">
                             0
                            </div>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-archive"></i> PNR:</span>
                    <input type="text" id="pnr" class="form-control" name="pnr"  placeholder="Enter PNR"  /> 
                </div>
            </div>
            <hr style="color:black" />
            
            <div class="row mb-3">
                <div class="col-lg-2" id="Passenger_control_margin">
                  <span class="form-label col-form-label text-inverse"><i class="fa fa-user"></i> Passenger Name:</span>  
                  <input type="text" class="form-control controlCounter" id="passenger_name1" name="passenger_name1" placeholder="Passenger Name" >
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-ticket"></i> Ticket Number:</span>
                    <input type="text" id="ticket_number1" class="form-control" name="ticket_number1"  placeholder="Ticket Number"  /> 
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Net:</span> 
                    <input type="number" class="form-group form-control col-md-auto" name="net_amount1" id="net_amount1" placeholder="Enter Sale Amount"  />
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class="form-control js-example-basic-single"   style="width:100%" id="net_currency_type1" name="net_currency_type1" spry:default="select one"></select>
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Sale:</span> 
                    <input type="number" class="form-group form-control col-md-auto" name="sale_amount1" id="sale_amount1" placeholder="Enter Sale Amount"  />
                </div>
                <div class="col-lg-1">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class="col-sm-3 form-control js-example-basic-single"   style="width:100%" id="currency_type1" name="currency_typ1e" spry:default="select one"></select>
                </div>
                <div class="col-lg-1 mt-3 p-2">
                    <span class="badge bg-secondary " onclick="addElement()" style="font-size:13px"><i class="fa fa-plus" ></i></span>
                </div>
                <div id="target"></div>
                
            </div>
            <hr style="color:black" />
            <div class="row mb-3">
                <div class="col-lg-3">
                    <div>
                        <span class="form-label col-form-label text-inverse"><i class="fa fa-archive"></i> Trip Type:</span> 
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" checked name="FT" id="o-w" >
                        <label class="form-check-label text-inverse" for="o-w">One-way</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="FT" id="r_t">
                        <label class="form-check-label text-inverse" for="r_t">Round-trip</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-plane"></i> Date of Travel:</span> 
                    <input type="date" class=" form-control" name="date_of_travel" id="date_of_travel" placeholder="Enter Date of travel" >
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-plane"></i> Date of Return:</span> 
                    <input type="date" class=" form-control" name="return_date" disabled id="return_date" placeholder="Enter Date of Return" >
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-plane"></i> From:</span> 
                    <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="from" name="from" spry:default="select one"></select>
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-plane"></i> To:</span> 
                    <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="to" name="to" spry:default="select one"></select>
                </div>
            </div>
            <hr style="color:black" />
            <div class="row">
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-user"></i> Supplier Name:</span> 
                    <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="supplier" name="supplier" spry:default="select one"></select>
                </div>
            </div>
            <hr style="color:black" />
            <div class="row">
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Customer Payment:</span> 
                    <input type="number" class="form-group form-control col-md-4" name="cus_payment" id="cus_payment" placeholder="Enter Net Amount"  />
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span>
                    <select class="col-sm-3 form-control js-example-basic-single"   style="width:100%" id="payment_currency_type" name="payment_currency_type" spry:default="select one"></select> 
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-paypal"></i> Account:</span>
                    <select class="form-group form-control  js-example-basic-single col-md-4" style="width:100%"  name="addaccount_id" id="addaccount_id"></select>
                </div>
                <div class="col-lg-4">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-file"></i> Ticket:</span>
                    <input type="file" class="form-group form-control col-md-4" id="uploadFile" name="uploadFile">
                </div>
            </div>
            <div class="panel-footer mt-4  text-white ">
                <button type="submit"  class=" col-sm-2 float-left btn btn-danger">Insert Record</button>
                <a style="margin-left:10px; margin-top:20px"  href="view ticket.php"><i class="fa fa-info text-white"></i>   View Report</a>
                <a style="margin-left:10px; margin-top:20px"  href="pendingticket.php"><i class="fa fa-credit-card text-white"></i>  Pending Payments</a>
            </div>
        </form>
        <!-- end here -->
    </div>
  </div>
 </div>
  <div class="tab-pane fade show" id="default-tab-2">
  <div class="panel panel-inverse">
        <div class="panel-heading bg-red-600 text-white">
            <h4 class="panel-title"><i class="fa fa-info"></i> Company <code> Information <i class="fa fa-arrow-down"></i></code></h4>
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
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-building text-red"></i> Company Name</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="company_name" class="form-control" placeholder="Company Name" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-1">
                        <label for="inputPassword6" class="col-form-label"><i class="fa fa-address-card text-red"></i> Company Number</label>
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="companyNumber" class="form-control" placeholder="Company Number" aria-describedby="passwordHelpInline">
                    </div>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-md-3 offset-1">
                        <button type="button" onclick="addCompany()" class="btn btn-inverse"><i class="fa fa-save"></i> Add Company</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script>
    function addCompany(){
    var insert ="INSERT";
    var company_name = $('#company_name');
    if(company_name.val() == ""){
        notify('Validation Error!', 'Company name is required', 'error');
        return;
    }
    var companyNumber = $('#companyNumber');
    if(companyNumber.val() == ""){
        notify('Validation Error!', 'Company number is required', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "customerResidenceController.php",  
            data: {
                INSERT:insert,
                Company_Name: company_name.val(),
                CompanyNumber : companyNumber.val(),
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', "Record added successfully", 'success');
                    company_name.val('');
                    companyNumber.val('');    
                    getCompanies();
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
            
        });
        $("#o-w").click(function(){
            $("#return_date").attr('disabled','disabled');
        });
        getFrom();
        getSupplier();
        getCurrencies();
        getAccounts('all',0);
    
    });
    function getCompanies(){
    var getCompanies = "getCompanies";
    $.ajax({
        type: "POST",
        url: "customerResidenceController.php",  
        data: {
            GetCompanies:getCompanies
        },
        success: function (response) {  
            var company = JSON.parse(response);
            $('#company_name').empty();
            $('#company_name').append("<option value='-1'>--Company--</option>");
            for(var i=0; i<company.length; i++){
              $('#company_name').append("<option value='"+ company[i].company_id +"'>"+ 
              company[i].company_name +"</option>");
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
    var cust_name = $('#cust_name').select2('data');
    if(cust_name[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
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
        return;
    }
    if(ticketNumberArr.includes("")){
        ticketNumberArr = [];
        notify('Validation Error!', 'Ticket number is required', 'error');
        return;
    }
    if(netAmountArr.includes("")){
        netAmountArr = [];
        notify('Validation Error!', 'Net price is required', 'error');
        return;
    }
    if(saleAmountArr.includes("")){
        saleAmountArr = [];
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
    }
    var pnr = $('#pnr');
    if(pnr.val() == ''){
        notify('Validation Error!', 'Pnr is required', 'error');
        return;
    }
    var date_of_travel = $('#date_of_travel');
    if(date_of_travel.val() == ""){
        notify('Validation Error!', 'Date of travel is required', 'error');
        return;
    }
    var return_date = $('#return_date');
    var from = $('#from').select2('data');
    if(from[0].id == "-1"){
        notify('Validation Error!', 'Departure is required', 'error');
        return;
    }
    var to = $('#to').select2('data');
    if(to[0].id == "-1"){
        notify('Validation Error!', 'Arrival is required', 'error');
        return;
    }
    var supplier = $('#supplier').select2('data');
    if(supplier[0].id == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
    var cus_payment = $('#cus_payment');
    var addaccount_id = $('#addaccount_id');
    var payment_currency_type = $('#payment_currency_type').select2('data');
    if(cus_payment.val() > 0){
      if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      } 
      if(payment_currency_type[0].id == "-1"){
        notify('Validation Error!', 'Payment currency type is required', 'error');
        return;
      } 
    }
    var ticket = $('#uploadFile').val();
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
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
        $.ajax({
            type: "POST",
            url: "ticketController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#passenger_name1').val('');
                    $('#ticket_number1').val('');
                    $('#net_amount1').val('');
                    $('#sale_amount1').val('');
                    
                    for(var i =2;i<=controlCounter; i++){
                        deleteElement('passenger_name'+[i]);
                    }
                    $('#uploadFile').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function addElement(){       
        $('#Passenger_control_margin').addClass("mb-3");
        var controlCounter = $('.controlCounter').length +1;
        var rowDiv = document.createElement('div');
        rowDiv.setAttribute('class', 'row mb-3');
        var firstColDiv = document.createElement('div');
        firstColDiv.setAttribute('class', 'col-lg-2');
        var firstColSpan = document.createElement('span');
        firstColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var firstColIcon =  document.createElement('i');
        firstColIcon.setAttribute('class', 'fa fa-user');
        firstColIcon.innerHTML = "<b> Passenger Name:</b>";

        const target = document.getElementById('target');
        target.appendChild(rowDiv);
        rowDiv.appendChild(firstColDiv);
        firstColDiv.appendChild(firstColSpan);
        firstColSpan.appendChild(firstColIcon);
        
        var input = document.createElement('input');
        input.setAttribute('class', 'form-control controlCounter');
        input.setAttribute('type', 'text');
        input.setAttribute('placeholder', 'Passenger Name');
        input.setAttribute('id', 'passenger_name'+ controlCounter);
        input.setAttribute('name', 'passenger_name'+ controlCounter);
        firstColDiv.appendChild(input);
        // end of first column
        // beginning of ticket column
        var secondColDiv = document.createElement('div');
        secondColDiv.setAttribute('class', 'col-lg-2');
        var secondColSpan = document.createElement('span');
        secondColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var secondColIcon =  document.createElement('i');
        secondColIcon.setAttribute('class', 'fa fa-ticket');
        secondColIcon.innerHTML = "<b> Ticket Number:</b>";
        rowDiv.appendChild(secondColDiv);
        secondColDiv.appendChild(secondColSpan);
        secondColSpan.appendChild(secondColIcon);
        
        var secondColInput = document.createElement('input');
        secondColInput.setAttribute('class', 'form-control');
        secondColInput.setAttribute('type', 'text');
        secondColInput.setAttribute('placeholder', 'Ticket Number');
        secondColInput.setAttribute('id', 'ticket_number'+ controlCounter);
        secondColInput.setAttribute('name', 'ticket_number1'+ controlCounter);
        secondColDiv.appendChild(secondColInput);
        // end of ticket column
        // beginning of net column
        var netPriceColDiv = document.createElement('div');
        netPriceColDiv.setAttribute('class', 'col-lg-2');
        var netPriceColSpan = document.createElement('span');
        netPriceColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var netPriceColIcon =  document.createElement('i');
        netPriceColIcon.setAttribute('class', 'fa fa-dollar');
        netPriceColIcon.innerHTML = "<b>  Net:</b>";
        rowDiv.appendChild(netPriceColDiv);
        netPriceColDiv.appendChild(netPriceColSpan);
        netPriceColSpan.appendChild(netPriceColIcon);
        
        var netColInput = document.createElement('input');
        netColInput.setAttribute('class', 'form-group form-control col-md-auto');
        netColInput.setAttribute('type', 'number');
        netColInput.setAttribute('placeholder', 'Enter Net Amount');
        netColInput.setAttribute('id', 'net_amount'+ controlCounter);
        netColInput.setAttribute('name', 'net_amount'+ controlCounter);
        netPriceColDiv.appendChild(netColInput);
        // end of net column
        // beginning of net currency column
        var netCurrencyColDiv = document.createElement('div');
        netCurrencyColDiv.setAttribute('class', 'col-lg-2');
        var netCurrencyColSpan = document.createElement('span');
        netCurrencyColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var netCurrencyColIcon =  document.createElement('i');
        netCurrencyColIcon.setAttribute('class', 'fa fa-dollar');
        netCurrencyColIcon.innerHTML = "<b>  Currency:</b>";
        rowDiv.appendChild(netCurrencyColDiv);
        netCurrencyColDiv.appendChild(netCurrencyColSpan);
        netCurrencyColSpan.appendChild(netCurrencyColIcon);
        
        var netCurrencyColInput = document.createElement('select');
        netCurrencyColInput.setAttribute('class', 'form-control js-example-basic-single');
        netCurrencyColInput.setAttribute('style', 'width:100%');
        netCurrencyColInput.setAttribute('spry:default', 'select one');
        netCurrencyColInput.setAttribute('id', 'net_currency_type'+ controlCounter);
        netCurrencyColInput.setAttribute('name', 'net_currency_type'+ controlCounter);
        netCurrencyColDiv.appendChild(netCurrencyColInput);
        // end of net currency column
        // beginning of sale column
        var salePriceColDiv = document.createElement('div');
        salePriceColDiv.setAttribute('class', 'col-lg-2');
        var salePriceColSpan = document.createElement('span');
        salePriceColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var salePriceColIcon =  document.createElement('i');
        salePriceColIcon.setAttribute('class', 'fa fa-dollar');
        salePriceColIcon.innerHTML = "<b>  Sale:</b>";
        rowDiv.appendChild(salePriceColDiv);
        salePriceColDiv.appendChild(salePriceColSpan);
        salePriceColSpan.appendChild(salePriceColIcon);
        
        var saleColInput = document.createElement('input');
        saleColInput.setAttribute('class', 'form-group form-control col-md-auto');
        saleColInput.setAttribute('type', 'number');
        saleColInput.setAttribute('placeholder', 'Enter Sale Amount');
        saleColInput.setAttribute('id', 'sale_amount'+ controlCounter);
        saleColInput.setAttribute('name', 'sale_amount'+ controlCounter);
        salePriceColDiv.appendChild(saleColInput);
        // end of net column
        // beginning of sale currency column
        var saleCurrencyColDiv = document.createElement('div');
        saleCurrencyColDiv.setAttribute('class', 'col-lg-1');
        var saleCurrencyColSpan = document.createElement('span');
        saleCurrencyColSpan.setAttribute('class', 'form-label col-form-label text-inverse');
        var saleCurrencyColIcon =  document.createElement('i');
        saleCurrencyColIcon.setAttribute('class', 'fa fa-dollar');
        saleCurrencyColIcon.innerHTML = "<b>  Currency:</b>";
        rowDiv.appendChild(saleCurrencyColDiv);
        saleCurrencyColDiv.appendChild(saleCurrencyColSpan);
        saleCurrencyColSpan.appendChild(saleCurrencyColIcon);
        
        var saleCurrencyColInput = document.createElement('select');
        saleCurrencyColInput.setAttribute('class', 'col-sm-3 form-control js-example-basic-single');
        saleCurrencyColInput.setAttribute('style', 'width:100%');
        saleCurrencyColInput.setAttribute('spry:default', 'select one');
        saleCurrencyColInput.setAttribute('id', 'currency_type'+ controlCounter);
        saleCurrencyColInput.setAttribute('name', 'currency_type'+ controlCounter);
        saleCurrencyColDiv.appendChild(saleCurrencyColInput);
        // end of net currency column

        var buttonDiv = document.createElement('div');
        buttonDiv.setAttribute('class', 'col-lg-1 my-3 p-2');
        var spanButton = document.createElement('span');
        spanButton.setAttribute('class', 'badge bg-danger');
        spanButton.setAttribute('onclick', 'deleteElement("passenger_name'+ controlCounter + '")');
        spanButton.setAttribute('style', 'font-size:13px');
        var deleteicon = document.createElement('i');
        deleteicon.setAttribute('class', 'fa fa-trash text-white');
        rowDiv.appendChild(buttonDiv);
        buttonDiv.appendChild(spanButton);
        spanButton.appendChild(deleteicon);
        getCurrencies();
        $('.js-example-basic-single').select2();
    }
    function deleteElement(id){
        el = document.getElementById(id);
        el.parentNode.parentNode.remove();
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
            if(payment.length > 0){
              for(var i=0; i<payment.length; i++){
              $('#total_charge').append("<p style='font-size:15px'><b>"+ numeral(payment[i].total).format('0,0') + " " + payment[i].curName + "</b></p>");
              }
            }else{
              $('#total_charge').append("<p style='font-size:15px'><b>Customer has no due payment <i style='font-size:15px' class='fa fa-smile-o' aria-hidden='true'></i></b></p>");
            }
        },
    });
    $('#duePaymentDiv').removeClass('d-none');
    }
</script>
</body>
</html>