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
  .bg-graident-lightcrimson{
    background: #CB356B;
    background: -webkit-linear-gradient(to right, #BD3F32, #CB356B);
    background: linear-gradient(to right, #BD3F32, #CB356B);
  }
  .text-graident-lightcrimson{
    color: #CB356B;
    color: -webkit-linear-gradient(to right, #BD3F32, #CB356B);
    color: linear-gradient(to right, #BD3F32, #CB356B);
  }
</style>
<title>Supplier Payment Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier Payment' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<style>
  #customBtn{ color:#33001b;border-color:#33001b; }
  #customBtn:hover{color:  #FFFFFF;background-color:#33001b;border-color:#33001b}
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-lg-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2  class="text-graident-lightcrimson" ><b><i class="fa fa-fw fa-money text-dark" ></i> <i>Supplier Payment Report</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
        <div class="col-lg-2" style="margin-top:38px">
        <input class="form-check-input" type="checkbox" id="dateSearch" name="dateSearch" value="option1">
                
        
        <label class="form-check-label" for="exampleCheck1">Search By Date</label>
          </div>
          <div class="col-lg-2">
            <label for="staticEmail" class="col-form-label">From:</label>
            <input type="text"  class="form-control" name="fromdate"  id="fromdate">
          </div>
          <div class="col-lg-2">
            <label for="staticEmail" class="col-form-label">To:</label>
            <input type="text"  class="form-control " name="todate"  id="todate">
          </div>
          <div class="col-lg-2">
            <label for="staticEmail" class="col-form-label">Supplier:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" name="supplier_id"  id="supplier_id"></select>
          </div>
          <div class="col-lg-2">
            <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" class="btn btn-block" style="width:100%" id="customBtn" onclick="getpaymentReport()">
             <i class="fa fa-search"> </i> Search 
            </button>
          </div>
          <div class="col-lg-2" style="margin-top:35px">
          
            <div>
              <?php if($update ==1 || $delete == 1){ ?>
            <button type="button" class="btn btn-block float-end" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Supplier Payment
            </button>
            <?php } ?>
          </div>
        </div>
      
      </div>
    
    <br/>
    <div class="row">
      <div class="col-lg-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-graident-lightcrimson">
            <tr >
              <th>S#</th>
              <th >Supplier Name</th>
              <th >Payment Detail</th>
              <th >Date Time</th>
              <th >Payment Amount</th>
              <th >Employee Name</th>
              <th >Account/Cash</th>
              <?php if($update ==1 || $delete == 1){ ?>
              <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="PaymentReportTbl">
                    
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

<!-- INSERT Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Supplier Payment</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
        <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Supplier Name:</label>
            <div class="col-sm-8">
            <select class="form-control  addSupplierSelect" onchange="getPayments()" style="width:100%" name="addsupplier_id" id="addsupplier_id"></select>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Total Charges:</label>
            <div class="col-sm-8">
            <div class="alert alert-primary" id="total_charge" style="max-height:20vh;overflow-y:scroll" role="alert">
              0
            </div>
            </div>
          </div>
          <div class="row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Amount:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="payment_amount" id="payment_amount" placeholder="Payment Amount">
            </div>
            <div class="col-sm-3">
              <select class=" form-control addSupplierSelect col-sm-4 "   style="width:100%" id="payment_currency_type" name="payment_currency_type" ></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Detail:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="payment_detail" id="payment_detail" placeholder="Payment Detail">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  addSupplierSelect" style="width:100%" name="addaccount_id" id="addaccount_id"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Expense Type</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="paymentID" name="paymentID" />
          <div class="row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Amount:</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="updpayment_amount" id="updpayment_amount" placeholder="Payment Amount">
            </div>
            <div class="col-sm-3">
              <select class=" form-control updCurrencySelect col-sm-4 "   style="width:100%" id="updpayment_currency_type" name="updpayment_currency_type" ></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Detail:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control"  name="updpayment_detail" id="updpayment_detail" placeholder="Payment Detail">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Supplier Name:</label>
            <div class="col-sm-8">
            <select class="form-control  updSupplierSelect" style="width:100%" name="updsupplier_id" id="updsupplier_id"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
            <select class="form-control  updSupplierSelect" style="width:100%" name="updaccount_id" id="updaccount_id"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

  
<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
  $(document).ready(function(){
    $('#fromdate').dateTimePicker();
      $('#todate').dateTimePicker();
      const date = new Date();
      month = date.getMonth() + 1;
      if(month == 1){
        month ="01";
      }else if(month == 2){
        month ="02";
      }else if(month == 3){
        month ="03";
      }else if(month == 4){
        month ="04";
      }else if(month == 5){
        month ="05";
      }else if(month == 6){
        month ="06";
      }else if(month == 7){
        month ="07";
      }else if(month == 8){
        month ="08";
      }else if(month == 9){
        month ="09";
      }
      var day = date.getDate();
      if(day == 1){
        day ="01";
      }else if(day == 2){
        day ="02";
      }else if(day == 3){
        day ="03";
      }else if(day == 4){
        day ="04";
      }else if(day == 5){
        day ="05";
      }else if(day == 6){
        day ="06";
      }else if(day == 7){
        day ="07";
      }else if(day == 8){
        day ="08";
      }else if(day == 9){
        day ="09";
      }
      $('#fromdate').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#todate').val(date.getFullYear() + '-' + month + '-'+ day);
    getSupplier('all',0);
    getAccounts('all',0);
   // $('.js-example-basic-single').select2();
    getCurrencies('addCurrency');
    $(".addSupplierSelect").select2({
      dropdownParent: $("#exampleModal")
    });
    $(".updSupplierSelect").select2({
      dropdownParent: $("#updexampleModal")
    });
    $(".updCurrencySelect").select2({
      dropdownParent: $("#updexampleModal")
    });
    getpaymentReport();
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this supplier payment',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "paymentController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getpaymentReport();
                }else{
                  notify('Opps!', response, 'error');
                }
              },
            });
            }
        },
        close: function () {
        }
    }
});
}
function GetDataForUpdate(id){
  var GetDataForUpdate = "GetDataForUpdate";
  $.ajax({
          type: "POST",
          url: "paymentController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#paymentID').val(id);
            $('#updpayment_amount').val(dataRpt[0].payment_amount);
            $('#updpayment_detail').val(dataRpt[0].payment_detail);
            getSupplier('byUpdate',dataRpt[0].supp_id);
            getAccounts('byUpdate',dataRpt[0].accountID);
            getCurrencies('updateCurrency', dataRpt[0].currencyID);
            $('#updexampleModal').modal('show');
        },
  });
}
function getpaymentReport(){
    searchTerm = '';
    var supplier_id = $('#supplier_id');
    var supplierID = -1;
    if(supplier_id.val() != null){
      supplierID = supplier_id.val();
    }
    var fromdate = $('#fromdate');
    var todate = $('#todate');
    var dateSearch = $('#dateSearch');
    if(dateSearch.is(':checked') && supplierID != -1  ){
      searchTerm = "DateAndSupWise";
    }else if(dateSearch.is(':checked') && supplierID == -1 ){
      searchTerm  = "DateWise";
    }else if(!dateSearch.is(':checked') && supplierID != -1 ){
      searchTerm  = "SupWise";
    }else if(!dateSearch.is(':checked') && supplierID == -1 ){
      searchTerm  = "AllWise";
    }
      var getpaymentReport = "getpaymentReport";
      $.ajax({
          type: "POST",
          url: "paymentController.php",  
          data: {
            GetpaymentReport:getpaymentReport,
            Supplier_ID:supplierID,
            SearchTerm:searchTerm,
            Fromdate:fromdate.val(),
            Todate:todate.val()
          },
          success: function (response) {  
            var suppPaymentRpt = JSON.parse(response);
            if(suppPaymentRpt.length == 0){
            $('#PaymentReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td>";
            <?php if($update ==1 || $delete == 1){ ?>
              finalTable +="<td></td>";
            <?php } ?>
            finalTable +="</tr>";
            $('#PaymentReportTbl').append(finalTable);
          }else{
            var total = 0;
            $('#PaymentReportTbl').empty();
            var j = 1;
            var finalTable = "";
            if(Array.isArray(suppPaymentRpt)){
              for(var i=0; i<suppPaymentRpt.length; i++){
              total += parseInt(suppPaymentRpt[i].payment_amount);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ suppPaymentRpt[i].supp_name
              +"</td><td class='text-capitalize'>"+ suppPaymentRpt[i].payment_detail + "</td><td >"+ 
              suppPaymentRpt[i].time_creation + "</td><td class='text-capitalize '>"+ 
              numeral(suppPaymentRpt[i].payment_amount).format('0,0')  + ' ' + suppPaymentRpt[i].currencyName +
              "</td><td>"+suppPaymentRpt[i].staff_name + "</td><td>"+suppPaymentRpt[i].account_Name + "</td>";
              <?php if($update ==1 || $delete == 1){ ?>
               finalTable += "<td class='text-center'>";
              <?php } ?>
              <?php if($update ==1){ ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              suppPaymentRpt[i].payment_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
                suppPaymentRpt[i].payment_id +")'" +
                "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update ==1 || $delete == 1){ ?>
             
                finalTable += "</td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#PaymentReportTbl').append(finalTable);
              j +=1;
            }
            }else{
              total += parseInt(suppPaymentRpt.payment_amount);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ suppPaymentRpt.supp_name
              +"</td><td class='text-capitalize'>"+ suppPaymentRpt.payment_detail + "</td><td >"+ 
              suppPaymentRpt.time_creation + "</td><td class='text-capitalize '>"+ 
              numeral(suppPaymentRpt[i].payment_amount).format('0,0')  + ' ' + suppPaymentRpt[i].currencyName + "</td><td>"+suppPaymentRpt.staff_name + "</td><td>"+
              suppPaymentRpt.account_Name + "</td>";
              <?php if($update ==1 || $delete == 1){ ?>
                finalTable += "<td class='text-center'>";
              <?php } ?>
              <?php if($update ==1){ ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              suppPaymentRpt.payment_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
                suppPaymentRpt.payment_id +")'" +
                "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update ==1 || $delete == 1){ ?>
                finalTable += "</td>";
              <?php } ?>
              finalTable += "</tr>";
              
              $('#PaymentReportTbl').append(finalTable);
              j +=1;
            }
            if(total >0){
              $('#PaymentReportTbl').append("<tr><td></td><td></td><td></td><td></td><td></td><td>"+ numeral(total).format('0,0') +"</td><td></td>");
              <?php if($update ==1 || $delete == 1){ ?>
                $('#PaymentReportTbl').append("<td></td>");
              <?php } ?>
              $('#PaymentReportTbl').append("</tr>");
            }
            

            }
          },
      });
    }
$(document).on('submit', '#CountryNameForm', function(event){
    event.preventDefault();
    var payment_amount = $('#payment_amount');
    if(payment_amount.val() == ""){
        notify('Validation Error!', 'Payment Amount is required', 'error');
        return;
    }
    var payment_detail = $('#payment_detail');
    if(payment_detail.val() == ""){
        notify('Validation Error!', 'Payment Detail is required', 'error');
        return;
    }
    var addsupplier_id = $('#addsupplier_id');
    if(addsupplier_id.val() == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
    }
    var addaccount_id = $('#addaccount_id');
    if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'account is required', 'error');
        return;
    }
    var payment_currency_type = $('#payment_currency_type');
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "paymentController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#CountryNameForm')[0].reset();
                    $('#addsupplier_id').val(-1).trigger('change.select2');
                    $('#addaccount_id').val(-1).trigger('change.select2');
                    $('#payment_currency_type').val(0).trigger('change.select2');
                    $('#exampleModal').modal('hide');
                    getpaymentReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var paymentID = $('#paymentID');
    if(paymentID.val() == ""){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
    }
    var updpayment_amount = $('#updpayment_amount');
    if(updpayment_amount.val() == ""){
        notify('Validation Error!', 'Payment Amount is required', 'error');
        return;
    }
    var updpayment_detail = $('#updpayment_detail');
    if(updpayment_detail.val() == ""){
        notify('Validation Error!', 'Payment Detail is required', 'error');
        return;
    }
    var updsupplier_id = $('#updsupplier_id');
    if(updsupplier_id.val() == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
    }
    var updaccount_id = $('#updaccount_id');
    if(updaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
    }
    var updpayment_currency_type = $('#updpayment_currency_type');
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "paymentController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updsupplier_id').val(-1).trigger('change.select2');
                    $('#updaccount_id').val(-1).trigger('change.select2');
                    $('#updpayment_currency_type').val(0).trigger('change.select2');
                    $('#updexampleModal').modal('hide');
                    getpaymentReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function getSupplier(type,id){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type == 'all'){
              $('#supplier_id').empty();
              $('#supplier_id').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                $('#supplier_id').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
              $('#addsupplier_id').empty();
              $('#addsupplier_id').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                $('#addsupplier_id').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }else{
              $('#updsupplier_id').empty();
              $('#updsupplier_id').append("<option value='-1'>--Supplier--</option>");              
              var selected = '';
               for(var i=0; i<supplier.length; i++){
                if(id == supplier[i].supp_id){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#updsupplier_id').append("<option "+ selected + "  value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
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
function getCurrencies(type,selected = 1 ){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            if(type == "addCurrency"){
              var selectedAttribute = '';
              $('#payment_currency_type').empty();
              for(var i=0; i<currencyType.length; i++){
                if(currencyType[0].currencyID == currencyType[i].currencyID){
                  selectedAttribute = 'selected';
                }else{
                  selectedAttribute = '';
                }
                $('#payment_currency_type').append("<option " + selectedAttribute + " value='"+ currencyType[i].currencyID +"'>"+ 
                currencyType[i].currencyName +"</option>");
              }
            }else if(type == "updateCurrency"){
              var selectedAttribute = '';
              $('#updpayment_currency_type').empty();
              for(var i=0; i<currencyType.length; i++){
                if(selected == currencyType[i].currencyID){
                  selectedAttribute = 'selected';
                }else{
                  selectedAttribute = '';
                }
                $('#updpayment_currency_type').append("<option " + selectedAttribute + " value='"+ currencyType[i].currencyID +"'>"+ 
                currencyType[i].currencyName +"</option>");
              }
            }
            
        },
    });
    }
    function getPayments(){
    var addsupplier_id = $("#addsupplier_id option:selected").val();
    var payments = 'Payments';
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
            Payments:payments,
            Addsupplier_ID:addsupplier_id
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
  }
</script>
</body>
</html>
