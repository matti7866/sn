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
<title>Expense Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Expenses' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
    <div class="card-header bg-light">
      <h2><b>Expense<span style="color:#C66"> Report</span></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
        <div class="col-md-1" style="margin-top:39px">
        <input class="form-check-input" type="checkbox" id="dateSearch" name="dateSearch" value="option1">
                <label class="form-check-label" for="exampleCheck1">Search By Date</label>
          </div>
        
          <div class="col-md-2">
            <label for="staticEmail" class="col-form-label">From:</label>
            <input type="text"  class="form-control" name="fromdate"  id="fromdate">
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">To:</label>
            <input type="text"  class="form-control " name="todate"  id="todate">
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Employee:</label>
            <select class="form-control" style="width:100%" name="employee_id" id="employee_id"></select>
          </div>
          <div class="col-md-3">
          <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" onclick="searchExpense()" style="width:100%" class="btn btn-dark btn-block   text-white "><i class="fa fa-fw fa-search"></i> Search</button>
          </div>
       </div>
    </form>
        </div>
      </div>
    
    <br/>
    
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white" style="background-color:#C66">
            <tr>
              <th>S#</th>
              <th>Expense</th>
              <th>Expense Amount</th>
              <th>Account</th>
              <th>Date Time</th>
              <th>Remarks</th>
              <th>Expense By</th>
              <th>Expense Receipts</th>
              <?php if($update == 1 || $delete == 1) { ?>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="EmpReportTbl">
                    
              </tbody>
        </table>
      </div> 
      </div>
      </div>
  </div>
</div>
</div>
</div>

<div class="modal fade" id="updateModel" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="expenseID" name="expenseID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Expense Type:</label>
          <div class="col-sm-9">
            <select name="expense_type" id="expense_type" style="width:100%" class="col-md-3 form-control js-example-basic-single"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword1" class="col-lg-3 col-form-label">Expense Amount/Currency:</label>
          <div class="col-lg-6">
            <input type="text" class="form-control"  name="amount" id="amount" placeholder="Expense Amount">
          </div>
          <div class="col-lg-3"> 
            <select class="form-control js-example-basic-single"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
          <div class="col-sm-9">
            <select name="updaccount_id" id="updaccount_id" style="width:100%" class="col-md-3 form-control js-example-basic-single"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
          <textarea class="form-control" id="remarks" id="remarks" placeholder="Enter Remarks (Optional)" rows="3"></textarea>
          </div>
        </div>
      </div>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onclick="SaveUpdate()">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
<form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="upload" >
          <input type="file" id="uploadFile" name="uploadFile" />
          <input type="text" name="expid" id="expid" />
          <button type="submit" id="submitUploadForm" >Call</button>
    </form>
<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
function getEmployees(type,id){
    var select_employee = "select_employee";
    $.ajax({
        type: "POST",
        url: "viewexpenseController.php",  
        data: {
            Select_employee:select_employee
        },
        success: function (response) {  
            var employee = JSON.parse(response);
            if(type=="byAll"){
              $('#employee_id').empty();
              $('#employee_id').append("<option value='-1'>--Employee--</option>");
              for(var i=0; i<employee.length; i++){
                $('#employee_id').append("<option value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
              }
            }else if(type=="ByUpdate"){
              var selected ='';
              $('#updemployee_id').empty();
              $('#updemployee_id').append("<option value='-1'>--employee--</option>");
              for(var i=0; i<employee.length; i++){
                if(employee[i].selectedemployee == employee[i].staff_id){
                selected ="selected";
              }else{
                selected="";
              }
                $('#updemployee_id').append("<option "+ selected +" value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
              }
            }
            
        },
    });
}
function getExpenseType(type,id){
  var select_ExpType = "select_ExpType";
    $.ajax({
        type: "POST",
        url: "viewexpenseController.php",  
        data: {
          Select_ExpType:select_ExpType
        },
        success: function (response) {  
            var expenseType = JSON.parse(response);
            if(type=="byAll"){
              $('#expense_type').empty();
              $('#expense_type').append("<option value='-1'>--Employee--</option>");
              for(var i=0; i<expenseType.length; i++){
                $('#expense_type').append("<option value='"+ expenseType[i].staff_id +"'>"+ 
                expenseType[i].staff_name +"</option>");
              }
            }else if(type=="ByUpdate"){
              var selected ='';
              $('#expense_type').empty();
              $('#expense_type').append("<option value='-1'>--Expense Type--</option>");
              for(var i=0; i<expenseType.length; i++){
                if(id == expenseType[i].expense_type_id){
                selected ="selected";
              }else{
                selected="";
              }
                $('#expense_type').append("<option "+ selected +" value='"+ expenseType[i].expense_type_id +"'>"+ 
                expenseType[i].expense_type +"</option>");
              }
            }
            
        },
    });
}
  $(document).ready(function() {
      $('#fromdate').dateTimePicker();
      $('#todate').dateTimePicker();
      $(".js-example-basic-single").select2({
        dropdownParent: $("#updateModel")
      });
      $("#employee_id").select2();
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
      getEmployees('byAll','');
      
});
function searchExpense(){
  var searchExpense = "searchExpense";
  searchTerm = '';
  var employee_id = $('#employee_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  var dateSearch = $('#dateSearch');
  if(dateSearch.is(':checked') && employee_id.val() != -1 ){
    searchTerm = "DateAndEmpWise";
  }else if(dateSearch.is(':checked') && employee_id.val() == -1 ){
    searchTerm  = "DateWise";
  }else if(!dateSearch.is(':checked') && employee_id.val() != -1 ){
    searchTerm  = "EmpWise";
  }
  if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
  }
  $.ajax({
        type: "POST",
        url: "viewexpenseController.php",  
        data: {
            SearchExpense:searchExpense,
            SearchTerm:searchTerm,
            Employee_ID:employee_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
        },
        success: function (response) {
          var empRpt = JSON.parse(response);
          if(empRpt.length === 0){
            $('#EmpReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td>";
          
              finalTable += "<td></td>";
           
              finalTable +="</tr>";
            $('#EmpReportTbl').append(finalTable);
          }else{
            $('#EmpReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var total = 0;
            for(var i=0; i<empRpt.length; i++){
              total += parseInt(empRpt[i].expense_amount);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ empRpt[i].expense_type +"</td>"+
              "<td class='text-capitalize'>"+ numeral(empRpt[i].expense_amount).format('0,0') + ' ' + empRpt[i].currencyName +
              "</td><td>"+ empRpt[i].account_Name +"</td><td>"+  empRpt[i].time_creation +"</td><td>"+ empRpt[i].expense_remark 
              +"</td><td class='text-capitalize'>"+ empRpt[i].staff_name +"</td>";
              if(empRpt[i].expense_document == null || empRpt[i].expense_document == '' ){
                finalTable += "<td><button type='button' onclick='uploadFile("+ empRpt[i].expense_id +")' class='btn'><i class='fa fa-upload text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }else{
                finalTable += "<td style='width:130px'><a href='downloadExpenseDocuemts.php?file=" + empRpt[i].expense_document  +"&originalName=" + empRpt[i].original_name + "'><button type='button' class='btn'><i class='fa fa-download text-dark fa-2x' aria-hidden='true'></i>"+
                "</button></a><button type='button' onclick='deleteFile("+ empRpt[i].expense_id + ")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1) { ?>
              finalTable += "<button type='button'0 onclick='Update("+ empRpt[i].expense_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              empRpt[i].expense_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              
              
              finalTable += "</tr>";
              
              $('#EmpReportTbl').append(finalTable);
            
              j +=1;
            }
            getTotal(searchTerm);
            }
            
            
        },
    });
}
function Delete(expense_id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this expense',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewexpenseController.php",  
                data: {
                  Delete:Delete,
                  Expense_ID:expense_id,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchExpense();
                }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
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
function Update(expense_id){
  var GetUpdExpense = "GetUpdExpense";
  $.ajax({
          type: "POST",
          url: "viewexpenseController.php",  
          data: {
            GetUpdExpense:GetUpdExpense,
            Expense_ID:expense_id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#expenseID').val(expense_id);
            getExpenseType('ByUpdate',dataRpt[0].expense_type_id);
            $('#amount').val(dataRpt[0].expense_amount);
            getCurrencies(dataRpt[0].CurrencyID);
            getAccounts('ByUpdate',dataRpt[0].accountID);
            $('#remarks').val(dataRpt[0].expense_remark);
            $('#updateModel').modal('show');
        },
  });
}
  function SaveUpdate(){
     var expenseID = $('#expenseID');
     var expense_type = $('#expense_type');
     var amount =  $('#amount');
     var remarks = $('#remarks');
     if(expense_type.val() == "-1"){
        notify('Validation Error!', 'Expense type is required', 'error');
        return;
     }
     if(amount.val() == ""){
        notify('Validation Error!', 'Amount is required', 'error');
        return;
     }
     if(remarks.val() == ""){
        notify('Validation Error!', 'Remarks is required', 'error');
        return;
     }
     var updaccount_id = $('#updaccount_id');
     var currency_type = $('#currency_type');
    var saveUpdateExpense = "saveUpdateExpense";
  $.ajax({
        type: "POST",
        url: "viewexpenseController.php",  
        data: {
            SaveUpdateExpense:saveUpdateExpense,
            ExpenseID:expenseID.val(),
            Expense_type:expense_type.val(),
            Amount:amount.val(),
            Remarks:remarks.val(),
            Updaccount_ID:updaccount_id.val(),
            Currency_Type:currency_type.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            searchExpense();
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
function uploadFile(expid){
 
          $('#expid').val(expid);
          $('#uploadFile').click();
}
document.getElementById("uploadFile").onchange = function(event) {
      $('#submitUploadForm').click();
};
    $(document).on('submit', '#upload', function(event){
      event.preventDefault();
      var expid = $('#expid');
      var uploadFile = $('#uploadFile');
      if(expid.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if(uploadFile.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#uploadFile').val() != ''){
        if($('#uploadFile')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('uploadExpDocuments','uploadExpDocuments');
        $.ajax({
            type: "POST",
            url: "expensesController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    searchExpense();
                    expid.val('');
                    $('#uploadFile').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function deleteFile(id){
  var DeleteFile = "DeleteFile";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this file',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "expensesController.php",  
                data: {
                  DeleteFile:DeleteFile,
                  ID:id
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchExpense();
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

function getTotal(searchTerm){
  var getTotal = "getTotal";
  var employee_id = $('#employee_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  $.ajax({
          type: "POST",
          url: "viewexpenseController.php",  
          data: {
            GetTotal:getTotal,
            SearchTerm:searchTerm,
            Employee_ID:employee_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
        },
        success: function (response) {  
          var total = JSON.parse(response);
          if(total.length > 0){
           
            $('#EmpReportTbl').append('<tr><td>Total</td><td></td><td id="res"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>');
            for(var i = 0; i< total.length; i++){
              $('#res').append('<p class="text-dark">'+ numeral(total[i].amount).format('0,0') + ' ' +  total[i].currencyName  + '</p>');
            }
              $('#EmpReportTbl').append('<td></td>');
              $('#EmpReportTbl').append('</tr>');
          }
          
        },
  });
 }

 function getCurrencies(selectedParam){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewexpenseController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(selectedParam == currencyType[i].currencyID){
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
</body>
</html>
