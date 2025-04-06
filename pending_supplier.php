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
</style>
<title>Supplier Report</title>
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
      <h1 class="h1"><i>Supplier Payments & Ledger <i class="fa fa-arrow-down text-red"></i></i></h1>
        <div class="card"  id="todaycard">
      
        <!-- Button trigger modal -->
          <div class="card-header text-white" style="background: #cb2d3e;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #ef473a, #cb2d3e);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #ef473a, #cb2d3e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
              <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                  <a class="nav-link active"><i class="fa fa-money text-danger"></i> All</a>
                </li>
              </ul>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <select class="form-control js-example-basic-single" style="width:100%"  onchange="getCurrencies('supplier')" id="supp_name" >
                </select>
              </div>
              <div class="col-md-2">
                <select class="form-control js-example-basic-single" onchange="getParticularSupplier()"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
              </div>
            </div>
            <div class="table-responsive mt-4">
            <table class='table table-striped table-hover text-center'>
              <thead class='thead text-white' style='font-size:13px;background: #cb2d3e;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #ef473a, #cb2d3e);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #ef473a, #cb2d3e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */'>
                <tr>
                  <th>#</th>
                  <th>Supplier Name</th>
                  <th>Supplier Email</th>
                  <th>Supplier Phone</th>
                  <th>Pending Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="VisaReportTbl">
              </tbody>
            </table>
        </div>
      </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="myModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-white" style="background: #cb2d3e;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #ef473a, #cb2d3e);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #ef473a, #cb2d3e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */">
        <h5 class="modal-title" id="exampleModalLongTitle">Supplier Make Payment</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-hidden="true"></button>
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
        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="makePay()">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>

<script>
  function getSuppliers(){
    var select_supplier = "select_supplier";
    $.ajax({
        type: "POST",
        url: "pending_supplier_controller.php",  
        data: {
          Select_Supplier:select_supplier,
        },
        success: function (response) { 
            var supplier = JSON.parse(response);
            $('#supp_name').empty();
            $('#supp_name').append("<option value=''>--Select Supplier--</option>");
            for(var i=0; i<supplier.length; i++){
            $('#supp_name').append("<option value='"+ supplier[i].supp_id +"'>"+ 
            supplier[i].supp_name +"</option>");
            
            }
        },
    });
}
function getPendingSuppliers(supplier_id){
    var currency_type = $('#currency_type').val();
    var select_pendingSuppliers = "select_pendingSuppliers";
    $.ajax({
        type: "POST",
        url: "pending_supplier_controller.php",  
        data: {
            Select_PendingSuppliers:select_pendingSuppliers,
            Supplier_ID: supplier_id,
            Currency_Type:currency_type
        },
        success: function (response) { 
         
          var pendingSupplierRpt = JSON.parse(response);
          
          if(pendingSupplierRpt.length == 0){
            $('#VisaReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td>Record Not Found</td><td></td><td></td></tr>";
            $('#VisaReportTbl').append(finalTable);
          }else{
            var total = 0;
            $('#VisaReportTbl').empty();
            var j = 1;
            var finalTable = "";
            if(Array.isArray(pendingSupplierRpt)){
              for(var i=0; i<pendingSupplierRpt.length; i++){
                total += parseInt(pendingSupplierRpt[i].Pending);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ pendingSupplierRpt[i].supp_name +"</td>"+
              "<td>"+ pendingSupplierRpt[i].supp_email +"</td><td>"+ pendingSupplierRpt[i].supp_phone +"</td><td>"+ 
              numeral(pendingSupplierRpt[i].Pending).format('0,0') +"</td>";
              
              finalTable += "<td>";
             
              finalTable += "<a href='supplierLedger.php?id="+ pendingSupplierRpt[i].main_supp + "&curID=" + currency_type + "' class='text-primary'><i class='fa fa-eye'></i> View Ledger</a> |<a  href='#' id='"+ pendingSupplierRpt[i].main_supp +"'  onclick='getPendingDetails("+ pendingSupplierRpt[i].main_supp +")'  class='text-danger' ><i class='fa fa-cc-paypal'></i> Make Payment</a>";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            }else{
              total += parseInt(pendingSupplierRpt.Pending);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ pendingSupplierRpt.supp_name +"</td>"+
              "<td>"+ pendingSupplierRpt.supp_email +"</td><td>"+ pendingSupplierRpt.supp_phone +"</td><td>"+ 
              numeral(pendingSupplierRpt.Pending).format('0,0') +"</td>";
              
              finalTable += "<td>";
             
              finalTable += "<a href='supplierLedger.php?id="+ pendingSupplierRpt.main_supp + "&curID=" + currency_type + "' class='text-primary'><i class='fa fa-eye'></i> View Ledger</a> |<a  href='#' id='"+ pendingSupplierRpt.main_supp +"'  onclick='getPendingDetails("+ pendingSupplierRpt.main_supp +")'  class='text-danger' ><i class='fa fa-cc-paypal'></i> Make Payment</a>";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            $('#VisaReportTbl').append("<tr><td></td><td></td><td></td><td></td><td>"+ numeral(total).format('0,0') +"</td><td></td></tr>");
            }
            
          
        },
    });
}
function getPendingDetails(supp_id){
  event.preventDefault();
  $('#myModel').modal('show');
  var currency_type = $('#currency_type').val();
  var payments = 'Payments';
    $.ajax({
        type: "POST",
        url: "pending_supplier_controller.php",  
        data: {
            Payments:payments,
            Supp_ID:supp_id,
            Currency_Type:currency_type
        },
        success: function (response) {  
            var payment = JSON.parse(response);
            $('#supp_id').val(supp_id);
            $('#balance').val(numeral(payment.total).format('0,0'));
        },
    });
}
function makePay(){
    var insert_payment ="INSERT_Payment";
    var supp_id = $('#supp_id');
    if(supp_id.val() == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
    var payment = $('#pay');
    if(payment.val() == ""){
        notify('Validation Error!', 'Payment is required', 'error');
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
            url: "pending_supplier_controller.php",  
            data: {
                Insert_Payment:insert_payment,
                Supp_ID:supp_id.val(),
                Payment : payment.val(),
                Currency_Type:currency_type,
                Remarks: remarks.val(),
                Addaccount_ID:addaccount_id.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', "Payment successfully added.", 'success');
                    $('#myModel').modal('hide');
                    getPendingSuppliers('');
                    getAccounts('all',0);
                    $('#supp_id').val('');
                    supp_id.val('');
                    payment.val('');
                    $('#remarks').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
}
function getParticularSupplier(){
  var supp_id = $("#supp_name").val();
  getPendingSuppliers(supp_id);
}

  $(document).ready(function(){
    $('.js-example-basic-single').select2();
    getSuppliers();
    getCurrencies('all');
    getAccounts('all',0);
  // $('.js-example-basic-single').select2();

$("#addaccount_id").select2({
      dropdownParent: $("#myModel")
    });
  });
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

function getCurrencies(type){
    var currencyTypes = "currencyTypes";
    var supp_id = $('#supp_name');
    if(type == "supplier"){
      if(supp_id.val() == -1 || supp_id.val() == '' || supp_id.val() == null){
        type = "all";
     }
    }
    $.ajax({
        type: "POST",
        url: "pending_supplier_controller.php",  
        data: {
            CurrencyTypes:currencyTypes,
            Supplier_ID_ID:supp_id.val(),
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
              getParticularSupplier();
        },
    });
    }
</script>
</body>
</html>