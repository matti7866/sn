<?php
  include 'header.php';
?>
<style>
  .bg-gradient-green1{
    background: #093028;
    background: -webkit-linear-gradient(to right, #237A57, #093028); 
    background: linear-gradient(to right, #237A57, #093028);
  }
  .text-gradient-green1{
    color: #093028;
    color: -webkit-linear-gradient(to right, #237A57, #093028); 
    color: linear-gradient(to right, #237A57, #093028);
  }
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
  @media (min-width: 992px) {
    .margin-lg-20{
        margin-top: -5px;
    }
  }
</style>
<title>Expense Form</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include "connection.php";
  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Expenses' ";
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
    <div class="card-header text-white bg-gradient-green1" >
      <h2 class="form-text text-white" style="font-size:20px"> <i class="fa fa-money"></i> Expense Entry <i>Form</i></span></h2>
    </div>
    <div  class="card-body">
  <form method="post" id="addExpenseForm"  enctype="multipart/form-data" >
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword1" class="col-form-label"><i class="fa fa-user"></i> Expense Type:</label>
      </div>
      <div class="col-md-3">
        <select name="expense_type" id="expense_type" style="width:100%" class="col-md-3 form-control js-example-basic-single"></select>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword1" class="col-form-label"><i class="fa fa-dollar"></i> Expense Amount:</label>
      </div>
      <div class="col-md-2">
        <input type="text" id="amount"  name="amount" placeholder="Expense Amount" class="form-control " >
      </div>
      <div class="col-lg-1  margin-lg-20" >
          <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
          <select class="form-control js-example-basic-single"   style="width:100%" id="expCurrencyType" name="expCurrencyType" spry:default="select one"></select>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword1" class="col-form-label"><i class="fa fa-paypal"></i> Account:</label>
      </div>
      <div class="col-md-3">
        <select name="addaccount_id" id="addaccount_id"  style="width:100%" class="col-md-3 form-control js-example-basic-single"></select>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword1" class="col-form-label"><i class="fa fa-info"></i> Remarks:</label>
      </div>
      <div class="col-md-3">
      <textarea class="col-md-3 form-control" id="remarks" name="remarks" placeholder="Enter Remarks (Optional)" rows="3"></textarea>
      </div>
    </div>
    <div class="row g-3 mb-2 align-items-center">
      <div class="col-md-1 text-inverse">
        <label for="inputPassword1" class="col-form-label"><i class="fa fa-file-o"></i> Expense document:</label>
      </div>
      <div class="col-md-3">
      <input type="file" class="form-group form-control col-md-4" id="uploadFile" name="uploadFile">
      </div>
    </div>
    
    </div>
  <div class="card-footer bg-light">
    <button  name="insert" type="submit"   class="btn bg-gradient-green1 text-white"><i class="fa fa-fw fa-save"></i> Save Record</button>
    <a href="viewexpense.php" class="text-gradient-green1"><i class="fa fa-fw fa-info"></i>View Report</a>
</form>
</div>
</div>
</div>
</div>
</body>
<?php include 'footer.php'; ?>
<script>
   function getExpenseTypes(){
    var getExpenseTypes = "getExpenseTypes";
    $.ajax({
        type: "POST",
        url: "expensesController.php",  
        data: {
          GetExpenseTypes:getExpenseTypes,
        },
        success: function (response) {  
            var expenseType = JSON.parse(response);
            $('#expense_type').empty();
            $('#expense_type').append("<option value='-1'>--Expense Type--</option>");
            for(var i=0; i<expenseType.length; i++){
              $('#expense_type').append("<option value='"+ expenseType[i].expense_type_id +"'>"+ 
              expenseType[i].expense_type +"</option>");
            }
        },
    });
}
$(document).on('submit', '#addExpenseForm', function(event){
  event.preventDefault();
	var expense_type = $('#expense_type');
    if(expense_type.val() == "-1"){
        notify('Error!', 'Expense Type is required', 'error');
        return;
    }
	  var amount = $('#amount');
	  if(amount.val() == ""){
        notify('Error!', 'Amount is required', 'error');
        return;
    }
	var remarks = $('#remarks');
  if(remarks.val() == ""){
        notify('Error!', 'Remarks is required', 'error');
        return;
    }
    var addaccount_id = $('#addaccount_id');
    if(addaccount_id.val() == "-1"){
        notify('Error!', 'Account is required', 'error');
        return;
    }
    var expense_documents = $('#uploadFile').val();
    if(expense_documents  == ''){
        notify('Error!', 'Please upload file for expense ', 'error');
        return;
    }
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 3495253){
        notify('Error!', 'File size is greater than 3 MB. Make Sure It should be less than 3 MB ', 'error');
        return;
      }
    }
    var expCurrencyType = $('#expCurrencyType');
    data = new FormData(this);
    data.append('SaveExpenseType','SaveExpenseType');
	$.ajax({
        type: "POST",
        url: "expensesController.php",  
        data: data,
        contentType: false,       
        cache: false,             
        processData:false, 
        success: function (response) {
          if(response == 'Success'){
            notify('Success!', response, 'success');
			      $('#expense_type').val(-1).trigger('change.select2');
            $('#addaccount_id').val(-1).trigger('change.select2');
            $('#expCurrencyType option:eq(0)').prop('selected',true);
            $('#addExpenseForm')[0].reset();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php';
          }else{
            notify('Opps!', response, 'error');
          }
        },
      });
});
$(document).ready(function() {
    getExpenseTypes();
    getAccounts('all',0);
    $('.js-example-basic-single').select2();
    getCurrencies();

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
function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#expCurrencyType').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i==0){
                  selected = "selected";
                  $('#expCurrencyType').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#expCurrencyType').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
        },
    });
    }
</script>
</html>