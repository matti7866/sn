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
<title>Loan Form</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include "connection.php";
  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Loan' ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':role_id', $_SESSION['role_id']);
	$stmt->execute();
	$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
	$insert = $insert[0]['insert'];
	if($insert == 0){
		echo "<script>window.location.href='pageNotFound.php'</script>";  
	}
?>
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
  <div class="card w3-card-24 " id="todaycard">
    <div class="card-header bg-light">
      <h2 class="form-text text-black" style="font-size:30px"> Loan Entry  <span style="color:#e83e8c"><i>Form</i></span></h2>
    </div>
    <div  class="card-body">
  <form id="addLoan" >
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword6" class="col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
      </div>
      <div class="col-md-3">
        <select name="cust_name" id="cust_name" style="width:100%" class="col-md-2 form-control js-example-basic-single"></select>
      </div>
    </div>
    <div class="row  mb-2">
      <div class="col-lg-1 text-inverse">
        <label for="inputPassword6" class="col-form-label mt-3"><i class="fa fa-dollar"></i> Amount:</label>
      </div>
      <div class="col-lg-2 mt-3">
        <input type="number" class="col-md-2  form-control" id="amount" placeholder="Amount" name="amount" >
      </div>
      <div class="col-lg-1">
        <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
        <select class="form-control js-example-basic-single"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword6" class="col-form-label"><i class="fa fa-paypal"></i> Account:</label>
      </div>
      <div class="col-md-3">
        <select name="addaccount_id" id="addaccount_id" style="width:100%" class="col-md-2 form-control js-example-basic-single"></select>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword6" class="col-form-label"><i class="fa fa-info"></i> Remarks:</label>
      </div>
      <div class="col-md-3">
        <textarea class="col-md-2 form-control" id="remarks" id="remarks" placeholder="Enter Remarks (Optional)" rows="3"></textarea>
      </div>
    </div>
  </div>
  <div class="card-footer bg-light">
    <button  name="insert" type="button"  onclick="SaveLoan()" class="btn" style="background-color:#e83e8c;color:white"><i class="fa fa-fw fa-save"></i> Save Record</button>
    <a href="viewloan.php" style="color:#e83e8c;"><i class="fa fa-fw fa-info"></i>View Report</a>
</form>
</div>
</div>
</div>
</div>
</body>
<?php include 'footer.php'; ?>
<script>
   function getCustomers(){
    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "loanController.php",  
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
function SaveLoan(){
	var SaveLoan = "SaveLoan";
	var cust_name = $('#cust_name');
    if(cust_name.val() == "-1"){
        notify('Error!', 'Customer name is required', 'error');
        return;
    }
	var amount = $('#amount');
	if(amount.val() == ""){
        notify('Error!', 'Amount is required', 'error');
        return;
    }
  var currency_type = $('#currency_type');
	var remarks = $('#remarks');
  var addaccount_id = $('#addaccount_id');
  if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
  } 
	$.ajax({
        type: "POST",
        url: "loanController.php",  
        data: {
            SaveLoan:SaveLoan,
            Cust_Name:cust_name.val(),
            Amount:amount.val(),
            Remarks:remarks.val(),
            Addaccount_ID:addaccount_id.val(),
            Currency_Type:currency_type.val()
        },
        success: function (response) {
          if(response == 'Success'){
            notify('Success!', response, 'success');
			      $('#cust_name').val(-1).trigger('change.select2');
            $('#addaccount_id').val(-1).trigger('change.select2');
            $('#currency_type option:eq(0)').prop('selected',true);
            $('#addLoan')[0].reset();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
          }else{
            notify('Opps!', response, 'error');
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

$(document).ready(function() {
    getCustomers();
    getAccounts('all',0);
    getCurrencies();
    $('.js-example-basic-single').select2();

});

function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "loanController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i==0){
                  selected = "selected";
                  $('#currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
        },
    });
    }
</script>
</html>