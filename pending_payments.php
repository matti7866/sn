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
/* .dropdown-menu dropdown-menu-end show{
  transform:translate3d(-74.5px, -0px, 0px);
} */
.dropright-container{
  position:relative;
}
.dropright-menu{
  box-shadow:0 0.5rem 1rem rgb(0 0 0 / 15%);
    position: absolute;
    z-index: 1000;
    min-width: 10rem;
    padding: 0.5rem 0;
    top:-35px;
    left:-202px;
    margin: 0;
    font-size: .6875rem;
    color: #2d353c;
    text-align: left;
  
   
    background-color: #fff;
    background-clip: padding-box;
    border: 0 solid rgba(0, 0, 0, .15);
    border-radius: 4px;
}
.dropright-item-options{
  padding:.3125rem .9375rem;
}
.dropright-item-options:hover{ 
  background-color:#e9ecef;
}

.btn-customBlue {
  background: #000428;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #004e92, #000428);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #004e92, #000428);
}

</style>

<title>Customer Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
  <div class="container-fluid" >
    <div class="row mt-5">
      <div class="col-12">
        <h1 class="h1"><i>Customer Payments & Ledger <i class="fa fa-arrow-down text-red"></i></i></h1>
        <div class="card w3-responsive"  id="todaycard">
      
        <!-- Button trigger modal -->
          <div class="card-header text-white" style="background: #000428;  /* fallback for old browsers */
                      background: -webkit-linear-gradient(to right, #004e92, #000428);  /* Chrome 10-25, Safari 5.1-6 */
                      background: linear-gradient(to right, #004e92, #000428); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
              <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                  <a class="nav-link active"><i class="fa fa-money text-danger"></i> All</a>
                </li>
                
              </ul>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <select class="form-control js-example-basic-single" style="width:100%"  onchange="getCurrencies('customer')" id="customer_id" >
                </select>
              </div>
              <div class="col-md-2">
                <select class="form-control js-example-basic-single" onchange="getParticularCustomer()"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
              </div>
              <div class="col-md-3 offset-4">
                <button type="button" class="btn btn-danger btn-sm w-80px rounded-pill pull-right " onclick="printResidneceRpt()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <button type="button" class="btn btn-customBlue text-white btn-sm w-80px rounded-pill pull-right mx-2" onclick="excelToTable()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
              </div>
            </div>
            
            <div class="table-responsive mt-4">
            <div id="printThisArea">
            <table class='table table-striped table-hover text-center' id="mainTable" data-cols-width="10,40,20,20,40">
              <thead class='thead text-white' style='font-size:13px;background: #000428;  /* fallback for old browsers */
                      background: -webkit-linear-gradient(to right, #004e92, #000428);  /* Chrome 10-25, Safari 5.1-6 */
                      background: linear-gradient(to right, #004e92, #000428); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */'>
                <tr>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Customer Email</th>
                  <th>Customer Phone</th>
                  <th>Pending Amount</th>
                  <th data-exclude="true" id="actionArea">Action</th>
                </tr>
              </thead>
              <tbody id="VisaReportTbl">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="myModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white" style="background: #000428;  /* fallback for old browsers */
                      background: -webkit-linear-gradient(to right, #004e92, #000428);  /* Chrome 10-25, Safari 5.1-6 */
                      background: linear-gradient(to right, #004e92, #000428); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
        <h5 class="modal-title" id="exampleModalLongTitle">Customer Make Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form>
        <input type="hidden" id="supp_id">
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Total Charges</label>
            <div class="col-sm-8">
                <input type="text"  disabled="disabled" class="form-control" id="balance">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-user"></i>Payment Amount</label>
            <div class="col-sm-8">
                <input type="number" class="form-control" id="pay">
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="staticSale" class="col-sm-4 col-form-label"><i class="fa fa-fw fa-comment"></i>Remarks</label>
            <div class="col-sm-8">
               <textarea class="form-control" rows="5" id="remarks"> </textarea>
            </div>
        </div>
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  js-example-basic-single" style="width:100%" name="addaccount_id" id="addaccount_id"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" onclick="makePayAndEmail()"><i class="fa fa-envelope me-1"></i> Save & Send Email</button>
        <button type="button" class="btn btn-primary" onclick="makePay()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script src="Libraries/tableToExcel/tableToExcel.js"></script>
<script src="Libraries\momentjs\moment.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<script>
  function getCustomers(){

    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "pending_paymentsController.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
        },
        success: function (response) { 
            var customer = JSON.parse(response);
            $('#customer_id').empty();
            $('#customer_id').append("<option value=''>--Select Customer--</option>");
            for(var i=0; i<customer.length; i++){
            $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
            customer[i].customer_name +"</option>");
            
            }
        },
    });
}
function getPendingCustomers(customer_id){
    var currency_type = $('#currency_type').val();
    var select_pendingCustomers = "SELECT_PendingCustomers";
    $.ajax({
        type: "POST",
        url: "pending_paymentsController.php",  
        data: {
            SELECT_PENDINGCUSTOMERS:select_pendingCustomers,
            Customer_ID: customer_id,
            Currency_Type:currency_type
        },
        success: function (response) { 
          var pendingSupolierRpt = JSON.parse(response);
          if(pendingSupolierRpt.length == 0){
            $('#VisaReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td>Record Not Found</td><td></td><td></td></tr>";
            $('#VisaReportTbl').append(finalTable);
          }else{
            var total = 0;
            $('#VisaReportTbl').empty();
            var j = 1;
            var finalTable = "";
            if(Array.isArray(pendingSupolierRpt)){
              for(var i=0; i<pendingSupolierRpt.length; i++){
                total += parseInt(pendingSupolierRpt[i].total);
              finalTable = "<tr><td scope='row' class='p-3'>"+ j + "</td><td class='text-capitalize p-3'>"+ 
              pendingSupolierRpt[i].customer_name +"</td><td class='p-3'>"+ pendingSupolierRpt[i].customer_email +
              "</td><td class='p-3'>"+ pendingSupolierRpt[i].customer_phone +"</td><td class='p-3'>"+ 
              numeral(pendingSupolierRpt[i].total).format('0,0') +"</td>";
              
              finalTable += "<td class='d-flex' data-exclude='true' id='hiddenPrintSection" +j + "'>";
             
              finalTable += "<a target='_self' href='receipt.php?id="+ pendingSupolierRpt[i].main_customer + "&curID=" + 
              currency_type + "' class='text-warning mt-2' ><i class='fas fa-receipt'></i> Make Receipt</a> <span class='mt-2'>|</span><a target='_blank' href='Invoice.php?id="+ pendingSupolierRpt[i].main_customer + "&curID=" + 
              currency_type + "' class='text-dark mt-2' ><i class='fa fa-file'></i> View Ledger</a> <span class='mt-2'>|</span>"+
              "<a  href='ledger.php?id="+ pendingSupolierRpt[i].main_customer + "&curID=" + currency_type + "'class='text-primary"+
              " mt-2' id='ledger" + i +"'><i class='fa fa-eye'></i> View Statement</a>&nbsp;&nbsp;<div class='dropright-container'>"+
              "<button type='button' class='btn btn-info' onclick='toggleDropRight("+i+")'><i class='fa fa-caret-left'></i>"+
              "</button><div class='dropright-menu d-none'  id='dropright-menu-toggle"+ i +
              "'><div class='dropright-item-options'><input type='text' placeholder='Select start date' class='form-control "+
              "fromDatesControll' id='fromdate"+ i + "'></div><div class='dropright-item-options'><input type='text' "+
              "class='form-control' placeholder='Select end date'  id='todate"+ i + "'></div></div></div> <span class='mt-2'>"+
              "|</span>  <a  href='#' id='"+ pendingSupolierRpt[i].main_customer +"'  onclick='getPendingDetails("+ 
              pendingSupolierRpt[i].main_customer +")'  class='text-danger mt-2' ><i class='fa fa-cc-paypal'></i> Make Payment</a>";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            }else{
              total += parseInt(pendingSupolierRpt.total);
              finalTable = "<tr><td scope='row' class='p-3'>"+ j + "</td><td class='text-capitalize p-3'>"+ 
              pendingSupolierRpt.customer_name +"</td><td class='p-3'>"+ pendingSupolierRpt.customer_email +"</td>"+
              "<td class='p-3'>"+ pendingSupolierRpt.customer_phone +"</td><td class='p-3'>"+ 
              numeral(pendingSupolierRpt.total).format('0,0') +"</td>";
            
             
              finalTable += "<td class='d-flex' data-exclude='true' id='hiddenPrintSection" +j + "'>";
              finalTable += "<a target='_self' href='receipt.php?id="+ pendingSupolierRpt.main_customer + "&curID=" + 
              currency_type + "' class='text-warning mt-2' ><i class='fa fa-file'></i> Make Receipt</a> <span class='mt-2'>|</span><a target='_blank' href='Invoice.php?id="+ pendingSupolierRpt.main_customer + "&curID=" + 
              currency_type + "' class='text-dark mt-2' ><i class='fa fa-file'></i> View Ledger</a> <span class='mt-2'>|</span>"+
              "<a  href='ledger.php?id="+ pendingSupolierRpt.main_customer + "&curID=" + currency_type + "'class='text-primary"+
              " mt-2' id='ledger" + 0 +"'><i class='fa fa-eye'></i> View Statement</a>&nbsp;&nbsp;<div class='dropright-container'>"+
              "<button type='button' class='btn btn-info' onclick='toggleDropRight("+0+")'><i class='fa fa-caret-left'></i>"+
              "</button><div class='dropright-menu d-none'  id='dropright-menu-toggle"+ 0 +
              "'><div class='dropright-item-options'><input type='text' placeholder='Select start date' class='form-control "+
              "fromDatesControll' id='fromdate"+ 0 + "'></div><div class='dropright-item-options'><input type='text' "+
              "class='form-control' placeholder='Select end date'  id='todate"+ 0 + "'></div></div></div> <span class='mt-2'>"+
              "|</span>  <a  href='#' id='"+ pendingSupolierRpt.main_customer +"'  onclick='getPendingDetails("+ 
              pendingSupolierRpt.main_customer +")'  class='text-danger mt-2' ><i class='fa fa-cc-paypal'></i> Make Payment</a>";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            $('#VisaReportTbl').append("<tr><td></td><td></td><td></td><td></td><td>"+ numeral(total).format('0,0') +"</td><td id='hiddenPrintSection" + j + "'></td></tr>");
            
            // var totalRecord = $('.fromDatesControll');
            // for(var s = 0;s<totalRecord.length;s++){
            //   $('#fromdate'+s).datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true,
            //   });
            //   $('#todate'+s).datepicker({
            //     format: 'yyyy-mm-dd',
            //     autoclose: true,
            //   });
              // $('#todate'+s).on('changeDate', function(e) {
              //   var stringID = e.target.id.split("todate");
              //   var id = stringID[1];
              //   $('#dropright-menu-toggle' + id).addClass('d-none');
              //   var selectedFromDate = $('#fromdate'+id).val();
              //   var selectedToDate = $('#todate'+id).val();
              //   // if(selectedFromDate > selectedToDate ){
              //   //   notify('Validation Error!', 'Start date should not exceed end date', 'error');
              //   //   return;
              //   // }
              //   if(selectedFromDate == "" ){
              //     notify('Validation Error!', 'Start date is required', 'error');
              //     return;
              //   }
              //   if(selectedToDate == "" ){
              //     notify('Validation Error!', 'End date is required', 'error');
              //     return;
              //   }
              //   var url = $('#ledger'+id)[0].href;
              //   var test = "https://localhost/sntrips/ledger.php?id=505&curID=1&fromdate='2020-01-01'&todate='2020-01-02'";
              //   if(url.includes("fromdate")){
              //     var requiredUrlPart = url.split('&fromdate=')[0];
              //     $('#ledger'+id)[0].href = requiredUrlPart + "&fromdate=" + selectedFromDate + "&todate=" + selectedToDate; 
              //   }else{
              //     $('#ledger'+id)[0].href = url + "&fromdate=" + selectedFromDate + "&todate=" + selectedToDate; 
              //   }
        
              // });
               
              
             
            }
           

            // $('#fromdate0').datepicker('remove').datepicker({
            //   format: 'yyyy-mm-dd',
            //     autoclose: true,
            //   orientation: 'bottom'
            // });
            // $('#todate0').datepicker('remove').datepicker({
            //   format: 'yyyy-mm-dd',
            //   autoclose: true,
            //   orientation: 'bottom'
            // });

          
        },
    });
}
function getPendingDetails(customer_id){
  event.preventDefault();
  $('#myModel').modal('show');
  var currency_type = $('#currency_type').val();
  var payments = 'Payments';
    $.ajax({
        type: "POST",
        url: "pending_paymentsController.php",  
        data: {
            Payments:payments,
            Customer_ID:customer_id,
            Currency_Type:currency_type
        },
        success: function (response) {  
            var payment = JSON.parse(response);
            $('#customer_id').val(customer_id);
            $('#balance').val(numeral(payment.total).format('0,0'));
        },
    });
}
function makePay(){
    var insert_payment ="INSERT_Payment";
    var customer_id = $('#customer_id');
    if(customer_id.val() == "-1"){
        notify('Validation Error!', 'customer is required', 'error');
        return;
    }
    var payment = $('#pay');
    if(payment.val() == ""  ){
        notify('Validation Error!', 'payment is required', 'error');
        return;
    }
    if(payment.val() < 0 ){
        notify('Validation Error!', 'Incorrect payment amount', 'error');
        return;
    }
    var remarks= $('#remarks');
    var addaccount_id = $('#addaccount_id');
    if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var currency_type = $('#currency_type').val();
        $.ajax({
            type: "POST",
            url: "pending_paymentsController.php",  
            data: {
                Insert_Payment:insert_payment,
                Customer_ID:customer_id.val(),
                Payment : payment.val(),
                Currency_Type:currency_type,
                Remarks: remarks.val(),
                Addaccount_ID:addaccount_id.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', "Payment Successfully added.", 'success');
                    $('#myModel').modal('hide');
                    getPendingCustomers('');
                    getAccounts('all',0);
                    $('#customer_id').val('');
                    customer_id.val('');
                    payment.val('');
                    $('#remarks').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
}

function makePayAndEmail(){
    var insert_payment ="INSERT_Payment_Email";
    var customer_id = $('#customer_id');
    if(customer_id.val() == "-1"){
        notify('Validation Error!', 'customer is required', 'error');
        return;
    }
    var payment = $('#pay');
    if(payment.val() == ""  ){
        notify('Validation Error!', 'payment is required', 'error');
        return;
    }
    if(payment.val() < 0 ){
        notify('Validation Error!', 'Incorrect payment amount', 'error');
        return;
    }
    var remarks= $('#remarks');
    var addaccount_id = $('#addaccount_id');
    if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var currency_type = $('#currency_type').val();
    
    // Enhanced debugging
    console.log("Customer ID:", customer_id.val());
    console.log("Payment Amount:", payment.val());
    console.log("Currency Type:", currency_type);
    console.log("Currency Element:", $('#currency_type').prop('outerHTML'));
    console.log("Selected Currency Text:", $('#currency_type option:selected').text());
    
    // Show loading indicator
    var saveEmailBtn = $('button[onclick="makePayAndEmail()"]');
    var originalBtnText = saveEmailBtn.html();
    saveEmailBtn.html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    saveEmailBtn.prop('disabled', true);
    
    // Debug info
    console.log("Making payment with currency_type: " + currency_type);
    
    $.ajax({
        type: "POST",
        url: "pending_paymentsController.php",  
        data: {
            Insert_Payment_Email: insert_payment,
            Customer_ID: customer_id.val(),
            Payment: payment.val(),
            Currency_Type: currency_type,
            Remarks: remarks.val(),
            Addaccount_ID: addaccount_id.val(),
            SendEmail: true
        },
        success: function (response) {
            try {
                // Check if response is already a JSON object
                var result = typeof response === 'object' ? response : JSON.parse(response);
                
                if(result.status === "Success"){
                    notify('Success!', result.message || 'Payment saved and email sent successfully', 'success');
                    $('#myModel').modal('hide');
                    getPendingCustomers('');
                    getAccounts('all',0);
                    $('#customer_id').val('');
                    customer_id.val('');
                    payment.val('');
                    $('#remarks').val('');
                } else {
                    var errorMsg = result.message || 'Failed to process request';
                    console.error("Error details:", result);
                    notify('Error!', errorMsg, 'error');
                }
            } catch (e) {
                console.error("Error parsing response:", e, "Response:", response);
                
                // Try to handle non-JSON responses
                if(typeof response === 'string' && response.includes("Success")){
                    notify('Success!', 'Payment saved successfully. Note: Email notification may not have been sent.', 'success');
                    $('#myModel').modal('hide');
                    getPendingCustomers('');
                    getAccounts('all',0);
                    $('#customer_id').val('');
                    customer_id.val('');
                    payment.val('');
                    $('#remarks').val('');
                } else {
                    notify('Error!', 'Failed to process response: ' + response, 'error');
                }
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", xhr.responseText);
            var errorMessage = "Failed to communicate with server";
            
            try {
                var response = JSON.parse(xhr.responseText);
                if (response && response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                errorMessage += ": " + error;
            }
            
            notify('Error!', errorMessage, 'error');
        },
        complete: function() {
            // Restore button state
            saveEmailBtn.html(originalBtnText);
            saveEmailBtn.prop('disabled', false);
        }
    });
}

function getParticularCustomer(){
  var cusID = $("#customer_id").val();
  getPendingCustomers(cusID);
}

  $(document).ready(function(){
    $('.js-example-basic-single').select2();
    getCustomers();
    getCurrencies('all');
    getAccounts();
   
    
    
    $("#addaccount_id").select2({
      dropdownParent: $("#myModel")
    });
  });
  function getAccounts(){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
              $('#addaccount_id').empty();
              $('#addaccount_id').append("<option value='-1'>--Account--</option>");
              for(var i=0; i<account.length; i++){
                $('#addaccount_id').append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
        },
    });
}
function toggleDropRight(toolBoxPosition){
  if($('#dropright-menu-toggle' + toolBoxPosition).hasClass('d-none')){
    var totalRecord = $('.fromDatesControll');
    for(var i =0;i<totalRecord.length;i++){
      $('#dropright-menu-toggle' + i).addClass('d-none');
    }
    $('#dropright-menu-toggle' + toolBoxPosition).removeClass('d-none');

  }else{
    $('#dropright-menu-toggle' + toolBoxPosition).addClass('d-none');
  }
}
// $(document).mouseup(function(e){
//   var totalRecord = $('.fromDatesControll');
//   for(var i =0;i<totalRecord.length;i++){
//       $('#dropright-menu-toggle' + i).addClass('d-none');
//     }
// });

function getCurrencies(type){
    var currencyTypes = "currencyTypes";
    var customer_id = $('#customer_id');
    if(type == "customer"){
      if(customer_id.val() == '-1' || customer_id.val() == '' || customer_id.val() == null){
        type = "all";
     }
    }
    $.ajax({
        type: "POST",
        url: "pending_paymentsController.php",  
        data: {
            CurrencyTypes:currencyTypes,
            Customer_ID:customer_id.val(),
            Type:type
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i == 0){
                  selected = "selected";
                  $('#currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
              if(type =='all'){
                getParticularCustomer();
              }else{
                getParticularCustomer();
              }
        },
    });
    }
    function excelToTable(){
      let currency = $('#currency_type').select2('data');
      currency = currency[0].text;
      let sheetName = 'All Customers Ledger Report of ' + moment().format('DD-MMMM-YYYY, h:mm:ss') + '.xlsx';
      TableToExcel.convert(document.getElementById("mainTable"),{
        name: sheetName,
        sheet: {
          name: currency + " all customers ledger report"
        }
      });
    }
    function printResidneceRpt(){
      let rowCount = $('#mainTable tr').length;
      let ignoredIDs = ['actionArea'];
      for(let i = 1; i<=rowCount -1; i++){
        ignoredIDs.push('hiddenPrintSection' + i);
      }
      let currency = $('#currency_type').select2('data');
      currency = currency[0].text;
   //printJS({ printable: 'printThisArea', type: 'html', style: '.table th { background-color: #dc3545 !important;color: white; }.table-striped tbody tr:nth-of-type(odd) td {background-color: rgba(0, 0, 0, .05)!important;}.table tbody tr td.customAbc {background-color: grey !important;color: white;} .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,.col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left;} .col-sm-12 {width: 100%;} .col-sm-11 {width: 91.66666666666666%;} .col-sm-10 {width: 83.33333333333334%;} .col-sm-9 {width: 75%;} .col-sm-8 {width: 66.66666666666666%;} .col-sm-7 {width: 58.333333333333336%;}.col-sm-6 {width: 50%;} .col-sm-5 {width: 41.66666666666667%;} .col-sm-4 {width: 33.33333333333333%;} .col-sm-3 {width: 25%;} .col-sm-2 {width: 16.666666666666664%;} .col-sm-1 {width: 8.333333333333332%;}' })
    printJS({ 
        printable: 'printThisArea', 
        type: 'html', 
        header: '<h1 class="text-center"><i class="fa fa-home" aria-hidden="true"></i> All Customer Ledger Report</h1>;<h5 class="text-center" style="margin-top:-10px">Curreny: '+ currency +'</h5>',
        ignoreElements:ignoredIDs,
             css: [
                'bootstrap-4.3.1-dist/css/bootstrap.min.css',
                'customBootstrap.css',
                'https://fonts.googleapis.com/css?family=Arizonia',
                'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
             ],
             targetStyles: ['*'],
             
             
    
    
    
    })
 }
</script>
</body>
</html>