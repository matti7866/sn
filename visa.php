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
.panel .panel-footer {
    border-top: 1px solid #ff5b57!important;
    padding: 0.75rem 0.9375rem;
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
    <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active"><i class="fa fa-ticket"></i> Visa Entry Form</a>
  </li>
  <li class="nav-item">
    <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link"><i class="fa fa-user"></i> Customer Entry Form</a>
  </li>
</ul>
<div class="tab-content bg-white p-3 rounded-bottom">
  <div class="tab-pane fade active show" id="default-tab-1">
    <div class="panel text-white">
    <div class="panel-heading bg-red-500">
            <h4 class="panel-title"><i class="fa fa-cc-visa"></i> VISA <code> ENTRY FORM <i class="fa fa-arrow-down"></i></code></h4>
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-success" onclick="resetForm()"><i class="fa fa-redo"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-danger " data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
            </div>
    </div>
    <div class="panel-body p-3">
        <!-- here -->
        <form method="post" enctype="multipart/form-data" id="addVisa">
            <div class="row mb-3">
                <div class="col-lg-3">
                  <span class="form-label col-form-label text-red"><i class="fa fa-user"></i> Customer Name:</span>  <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="cust_name" name="cust_name" spry:default="select one"></select>
                </div>
                <div class="col-lg-3">
                  <span class="form-label col-form-label text-red"><i class="fa fa-flag"></i>  Nationality:</span>  
                  <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="nationality" name="nationality" spry:default="select one"></select>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-address-card"></i>  Address:</span>
                    <input type="text" class="form-group form-control" name="address" id="address" placeholder="Enter Address"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-ambulance"></i>  Gaurantee:</span>
                    <input type="text" class="form-group form-control " name="gaurantee" id="gaurantee" placeholder="Enter Gaurantee Person"  />
                </div>
            </div>
            <hr style="color:red" />
            <div class="row mb-3">
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-flag"></i>  Country & Visa Type:</span>
                    <select name="country_id" style="width:100%"  onchange="getSalePrice('byCustomer')" id="country_id" class="js-example-basic-single"></select>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-address-card"></i>  Supplier Name:</span>
                    <select name="supplier" id="supplier" style="width:100%" onchange="getNetPrice('byCustomer')" class="   js-example-basic-single"></select>
                </div>
            </div>
            <hr style="color:red" />
            <div class="row mb-3">
                <div class="col-lg-2" id="Passenger_control_margin">
                    <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Passenger Name:</span>
                    <input type="text" class="form-control controlCounter" id="passenger_name1" name="passenger_name1" placeholder="Passenger Name" >
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Passport:</span>
                    <input type="text" id="passportNum1" class="form-control" name="passportNum1"  placeholder="Passport Number"  />
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Net price:</span>
                    <input type="number" class="form-group form-control" name="net_amount1" id="net_amount1" placeholder="Enter Net Amount"  />
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class="form-control js-example-basic-single" onchange="getNetPrice('byCurrency')"  style="width:100%" id="net_currency_type1" name="net_currency_type1" spry:default="select one"></select>
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Sale price:</span>
                    <input type="number" class="form-group form-control" name="sale_amoun1" id="sale_amount1" placeholder="Enter Sale Amount"  />
                </div>
                <div class="col-lg-1">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class=" form-control js-example-basic-single" onchange="getSalePrice('byCurrency')"   style="width:100%" id="currency_type1" name="currency_type1" spry:default="select one"></select>
                </div>
                <div class="col-lg-1 mt-3 p-2">
                    <span class="badge bg-secondary" onclick="addElement()" style="font-size:13px"><i class="fa fa-plus" ></i></span>
                </div>
                <div id="target"></div>
            </div>
            <hr style="color:red" />
            <div class="row mb-3">
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Customer Payment:</span>
                    <input type="number" class="form-group form-control" name="cust_payment" id="cust_payment" placeholder="Enter Customer Payment"  />
                </div>
                <div class="col-lg-2">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span>
                    <select class="col-sm-3 form-control js-example-basic-single"   style="width:100%" id="payment_currency_type" name="payment_currency_type" spry:default="select one"></select> 
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-paypal"></i>  Account:</span>
                    <select class="form-group form-control  js-example-basic-single" style="width:100%"  name="addaccount_id" id="addaccount_id"></select>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-cc-visa"></i>  Visa:</span>
                    <input type="file" class="form-group form-control col-md-4" id="uploadFile" name="uploadFile">
                </div>
            </div>
            <input type="hidden" id="pendingvisa" name="pendingvisa" value="1" >
            <div class="panel-footer mt-4  text-white ">
                <button type="submit"  class=" col-sm-2 float-left btn btn-danger"><i class="fa fa-save"></i> Save Record</button>
                <a style="margin-left:10px; margin-top:20px"  href="view visa.php"><i class="fa fa-info text-red"></i>   View Report</a>
                <a style="margin-left:10px; margin-top:20px"  href="pendingvisa.php"><i class="fa fa-credit-card text-red"></i>  Pending Payments</a>
            </div>
        </form>
        <!-- end here -->
    </div>
  </div>
 </div>
  <div class="tab-pane fade show" id="default-tab-2">
  <div class="panel panel-inverse">
        <div class="panel-heading bg-black text-white">
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
    function resetForm(){
    $('#cust_name').val(-1).trigger('change.select2');
    $('#country_id').val(-1).trigger('change.select2');
    $('#supplier').val(-1).trigger('change.select2');
    $('#addaccount_id').val(-1).trigger('change.select2');
    $('#payment_currency_type option:eq(0)').prop('selected',true);
    $('#net_currency_type option:eq(0)').prop('selected',true);
    $('#nationality').val(1).trigger('change.select2');
    $('#currency_type option:eq(0)').prop('selected',true);
    $('#passportNum1').val('');
    $('#passenger_name1').val('');
    var controlCounter = $('.controlCounter').length +1;
    for(var i =2;i<controlCounter; i++){
        deleteElement('passenger_name'+[i]);
    }
    $('#net_amount1').val('');
    $('#sale_amount1').val('');
    $('#cust_payment').val('');
    $('#gaurantee').val('');
    $('#address').val(''); 
    $('#uploadFile').val('');
    }
    $(document).ready(function() {
        getCustomers();
        getFrom();
        getSupplier();
        getAccounts('all',0);
        getCurrencies('sale', null);
        getCurrencies('net', null);
        getCurrencies('payment',null);
        getNationalities();
        $('.js-example-basic-single').select2();
    });
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
    function addElement(){
        $('#Passenger_control_margin').addClass("mb-3");
        var controlCounter = $('.controlCounter').length +1;
        var rowDiv = document.createElement('div');
        rowDiv.setAttribute('class', 'row mb-3');
        var firstColDiv = document.createElement('div');
        firstColDiv.setAttribute('class', 'col-lg-2');
        var firstColSpan = document.createElement('span');
        firstColSpan.setAttribute('class', 'form-label col-form-label text-red');
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
        // beginning of passport column
        var secondColDiv = document.createElement('div');
        secondColDiv.setAttribute('class', 'col-lg-2');
        var secondColSpan = document.createElement('span');
        secondColSpan.setAttribute('class', 'form-label col-form-label text-red');
        var secondColIcon =  document.createElement('i');
        secondColIcon.setAttribute('class', 'fa fa-user');
        secondColIcon.innerHTML = "<b> Passport Number:</b>";
        rowDiv.appendChild(secondColDiv);
        secondColDiv.appendChild(secondColSpan);
        secondColSpan.appendChild(secondColIcon);
        
        var secondColInput = document.createElement('input');
        secondColInput.setAttribute('class', 'form-control');
        secondColInput.setAttribute('type', 'text');
        secondColInput.setAttribute('placeholder', 'Passport Number');
        secondColInput.setAttribute('id', 'passportNum'+ controlCounter);
        secondColInput.setAttribute('name', 'passportNum'+ controlCounter);
        secondColDiv.appendChild(secondColInput);
        // end of passport column
        // beginning of net column
        var netPriceColDiv = document.createElement('div');
        netPriceColDiv.setAttribute('class', 'col-lg-2');
        var netPriceColSpan = document.createElement('span');
        netPriceColSpan.setAttribute('class', 'form-label col-form-label text-red');
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
        netCurrencyColSpan.setAttribute('class', 'form-label col-form-label text-red');
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
        salePriceColSpan.setAttribute('class', 'form-label col-form-label text-red');
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
        saleCurrencyColSpan.setAttribute('class', 'form-label col-form-label text-red');
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
        getSalePrice('sale',null);
        getNetPrice('net', null);
    }
    function deleteElement(id){
        el = document.getElementById(id);
        el.parentNode.parentNode.remove();
    }
    function getNetPrice(type){
    var getNetPrice = "getNetPrice";
    var net_currency_type = $('#net_currency_type1').select2('data');
    var supplier = $('#supplier').select2('data');
    supplier = supplier[0].id;
    var country_id = $('#country_id').select2('data');
    country_id = country_id[0].id;
    $.ajax({
        type: "POST",
        url: "visaController.php",  
        data: {
            GetNetPrice:getNetPrice,
            Supplier:supplier,
            Country_ID:country_id,
            Net_Currency_Type:net_currency_type[0].id,
            Type:type

        },
        success: function (response) {  
            var getNetPrice = JSON.parse(response);
            var controlCounter = $('.controlCounter').length;
            for(var j = 1; j<=controlCounter; j++ ){
                $('#net_amount'+ j ).val(getNetPrice[0].netPrice);
            }
            getCurrencies('net',getNetPrice[0].CurrencyID);
        },
    });
    }
    function getSalePrice(type){
    var getSalePrice = "getSalePrice";
    var currencyID = $('#currency_type1').select2('data');
    var cust_name = $('#cust_name').select2('data');
    cust_name = cust_name[0].id;
    var country_id = $('#country_id').select2('data');
    country_id = country_id[0].id;
    $.ajax({
        type: "POST",
        url: "visaController.php",  
        data: {
            GetSalePrice:getSalePrice,
            Cust_Name:cust_name,
            Country_ID:country_id,
            CurrencyID:currencyID[0].id,
            Type: type
        },
        success: function (response) {  
            var getSalePrice = JSON.parse(response);
            var controlCounter = $('.controlCounter').length;
            for(var j = 1; j<=controlCounter; j++ ){
                $('#sale_amount'+ j  ).val(getSalePrice[0].salePrice);
            }
            getCurrencies('sale',getSalePrice[0].CurrencyID);
        },
    });
    }
    function getFrom(){
    var select_from = "SELECT_FROM";
    $.ajax({
        type: "POST",
        url: "visaController.php",  
        data: {
            SELECT_FROM:select_from,
        },
        success: function (response) {  
            var country_id = JSON.parse(response);
            $('#country_id').empty();
            $('#country_id').append("<option value='-1'>--Select visa type--</option>");
            for(var i=0; i<country_id.length; i++){
              $('#country_id').append("<option value='"+ country_id[i].country_id +"'>"+ 
              country_id[i].country_names +"</option>");
            }
        },
    });
    }
    function getSupplier(){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "visaController.php",  
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
    $(document).on('submit', '#addVisa', function(event){
    event.preventDefault();
    var cust_name = $('#cust_name');
    if(cust_name.val() == "-1"){
        notify('Error!', 'Customer name is required', 'error');
        return;
    }
    var controlCounter = $('.controlCounter').length;
    var passArr = [];
    var passportNumArr = [];
    var netAmountArr = [];
    var netPriceCurrencyArr = [];
    var saleAmountArr = [];
    var salePriceCurrencyArr = [];
    for(var i =1;i<=controlCounter; i++){
        passArr.push($('#passenger_name'+[i]).val());
        passportNumArr.push($('#passportNum'+[i]).val());
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
    if(passportNumArr.includes("")){
        passportNumArr = [];
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
    var country_id= $('#country_id');
    if(country_id.val() == "-1"){
        notify('Error!', 'Country or Type of visa is required', 'error');
        return;
    }
    var supplier = $('#supplier');
    if(supplier.val() == "-1"){
        notify('Error!', 'Supplier is required', 'error');
        return;
    }
    var address = $('#address');
    var cust_payment = $('#cust_payment');
    var addaccount_id = $('#addaccount_id');
    var payment_currency_type = $('#payment_currency_type').select2('data');
    if(cust_payment.val() > 0){
      if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      } 
      if(payment_currency_type[0].id == "-1"){
        notify('Validation Error!', 'Payment currency type is required', 'error');
        return;
      } 
    }
    var nationality = $('#nationality');
    var gaurantee = $('#gaurantee');
    var pendingvisa = $('#pendingvisa');
    var visa = $('#uploadFile').val();
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
        return;
      }
    }
      data = new FormData(this);
      data.append('Insert_Visa','Insert_Visa');
      data.append('passArr',JSON.stringify(passArr));
      data.append('passportNumArr',JSON.stringify(passportNumArr));
      data.append('netAmountArr',JSON.stringify(netAmountArr));
      data.append('netPriceCurrencyArr',JSON.stringify(netPriceCurrencyArr));
      data.append('saleAmountArr',JSON.stringify(saleAmountArr));
      data.append('salePriceCurrencyArr',JSON.stringify(salePriceCurrencyArr));
        $.ajax({
            type: "POST",
            url: "visaController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#passportNum1').val('');
                    $('#net_amount').val('');
                    $('#sale_amount').val('');
                    $('#passenger_name1').val('');
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
    function getNationalities(){
    var getNationalities = "getNationalities";
    $.ajax({
        type: "POST",
        url: "residenceController.php",  
        data: {
            GetNationalities:getNationalities,
        },
        success: function (response) {
            var nationality = JSON.parse(response);
              $('#nationality').empty();
              $('#nationality').append("<option id='-1'> --Select Nationality -- </option>");              
              var selected = '';
               for(var i=0; i<nationality.length; i++){
                if(i == 0){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#nationality').append("<option "+ selected + "  value='"+ nationality[i].airport_id +"'>"+ 
                nationality[i].mainCountryName +"</option>");
              }
        },
    });
    }
    $('#addNationalityModel').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });
    function getCurrencies(type,selectedCurrency){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "visaController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var saleSelected = "";
            var netSelected = "";
            var paymentSelected = "";
            var controlCounter = $('.controlCounter').length;
            for(var j = 1; j<= controlCounter; j++ ){
                if(type =="sale"){
                    $('#currency_type'+j).empty();
                    for(var i=0; i<currencyType.length; i++){
                        if(selectedCurrency == null || selectedCurrency == '' || selectedCurrency == 'undefined'){
                            if(i == 0){
                                saleSelected = "selected";
                                $('#currency_type'+j).append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }else{
                                $('#currency_type'+j).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }
                        }else{
                            if(selectedCurrency == currencyType[i].currencyID){
                                saleSelected = "selected";
                                $('#currency_type'+j).append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }else{
                                $('#currency_type'+j).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }
                        }
                    }
                }else if(type == "net"){
                    $('#net_currency_type'+j).empty();
                    for(var i=0; i<currencyType.length; i++){   
                        if(selectedCurrency == null || selectedCurrency == '' || selectedCurrency == 'undefined'){
                            if(i == 0){
                                netSelected = "selected";
                                $('#net_currency_type'+j).append("<option " + netSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }else{
                                $('#net_currency_type'+j).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }
                        }else{
                            if(selectedCurrency == currencyType[i].currencyID){
                                netSelected = "selected";
                                $('#net_currency_type'+j).append("<option " + netSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }else{
                                $('#net_currency_type'+j).append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                                currencyType[i].currencyName +"</option>");
                            }
                        }
                    }
                }else{
                    $('#payment_currency_type').empty();
                    for(var i=0; i<currencyType.length; i++){
                        if(i==0){
                            paymentSelected = "selected";
                            $('#payment_currency_type').append("<option " + paymentSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                            currencyType[i].currencyName +"</option>");
                        }else{
                            $('#payment_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                            currencyType[i].currencyName +"</option>");
                        }  
                    }
                }
                
            }
        },
    });
    }
</script>
</body>
</html>