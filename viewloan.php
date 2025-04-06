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
<title>Loan Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Loan' ";
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
      <h2><b>Loan<span style="color:#C66"> Report</span></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
        <div class="col-md-1" style="margin-top:37px">
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
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control" style="width:100%" name="customer_id" id="customer_id"></select>
          </div>
          <div class="col-md-3">
          <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" onclick="searchLoan()" style="width:100%" class="btn btn-dark btn-block   text-white "><i class="fa fa-fw fa-search"></i> Search</button>
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
              <th>Customer Name</th>
              <th>Amount</th>
              <th>Account</th>
              <th>Date Time</th>
              <th>Remarks</th>
              <th>Lend By</th>
              <?php if($delete == 1  || $update == 1) { ?>
                <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="LoanReportTbl">
                    
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
        <input type="hidden"  class="form-control" id="loanID" name="loanID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer Name:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label mt-3">Amount:</label>
          <div class="col-lg-6 mt-3">
            <input type="text" class="form-control"  name="updamount" id="updamount" placeholder="Amount">
          </div>
          <div class="col-lg-3">
            <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
            <select class="form-control js-example-basic-single"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updaccount_id" id="updaccount_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
          <textarea class="form-control" id="updremarks" id="updremarks" placeholder="Enter Remarks (Optional)" rows="3"></textarea>
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
<?php include 'footer.php'; ?>
<script>
function getCustomers(type,id){
    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "viewloanCotnroller.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
            Type:type,
            ID:id
        },
        success: function (response) {  
            var customer = JSON.parse(response);
            if(type=="byAll"){
              $('#customer_id').empty();
              $('#customer_id').append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }else if(type=="ByUpdate"){
              var selected ='';
              $('#updcustomer_id').empty();
              $('#updcustomer_id').append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                if(customer[i].selectedCustomer == customer[i].customer_id){
                selected ="selected";
              }else{
                selected="";
              }
                $('#updcustomer_id').append("<option "+ selected +" value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }
            
        },
    });
}
  $(document).ready(function() {
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
      getCustomers('byAll','');
      $('.js-example-basic-single').select2({
        dropdownParent: $("#updateModel")
      });
      $('#customer_id').select2();
});
function searchLoan(){
  var searchLoan = "searchLoan";
  searchTerm = '';
  var customer_id = $('#customer_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  var dateSearch = $('#dateSearch');
  if(dateSearch.is(':checked') && customer_id.val() != -1 ){
    searchTerm = "DateAndCusWise";
  }else if(dateSearch.is(':checked') && customer_id.val() == -1 ){
    searchTerm  = "DateWise";
  }else if(!dateSearch.is(':checked') && customer_id.val() != -1 ){
    searchTerm  = "CustWise";
  }
  if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
  }
  $.ajax({
        type: "POST",
        url: "viewloanCotnroller.php",  
        data: {
            SearchLoan:searchLoan,
            SearchTerm:searchTerm,
            Customer_ID:customer_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
        },
        success: function (response) {
          var loanRpt = JSON.parse(response);
          if(loanRpt.length === 0){
            $('#LoanReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td>";
            <?php if($delete == 1  || $update == 1) { ?>
              finalTable += "<td></td>";
            <?php  } ?>
              finalTable +="</tr>";
            $('#LoanReportTbl').append(finalTable);
          }else{
            $('#LoanReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<loanRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ loanRpt[i].customer_name +"</td>"+
              "<td class='text-capitalize'>"+ numeral(loanRpt[i].amount).format('0,0') + ' ' + loanRpt[i].currencyName +"</td><td>"+ loanRpt[i].account_Name +"</td><td>"+ loanRpt[i].datetime +"</td><td>"+ 
              loanRpt[i].remarks +"</td><td class='text-capitalize'>"+ loanRpt[i].staff_name +"</td>";
              <?php if($delete == 1  || $update == 1) { ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1) { ?>
              finalTable += "<button type='button'0 onclick='Update("+ loanRpt[i].loan_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              loanRpt[i].loan_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1  || $update == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              
              
              finalTable += "</tr>";
              
              $('#LoanReportTbl').append(finalTable);
            
              j +=1;
            }
            
              getTotal(searchTerm);
              
              
            

          }
            
            
        },
    });
}
function Delete(loanID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this loan',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewloanCotnroller.php",  
                data: {
                  Delete:Delete,
                  LoanID:loanID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchLoan();
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
function Update(loan_id){
  var GetUpdLoan = "GetUpdLoan";
  $.ajax({
          type: "POST",
          url: "viewloanCotnroller.php",  
          data: {
            GetUpdLoan:GetUpdLoan,
            Loan_ID:loan_id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#loanID').val(loan_id);
            getCustomers('ByUpdate',dataRpt[0].customer_id);
            $('#updamount').val(dataRpt[0].amount);
            getAccounts('ByUpdate',dataRpt[0].accountID);
            $('#updremarks').val(dataRpt[0].remarks);
            getCurrencies(dataRpt[0].currencyID);
            $('#updateModel').modal('show');
        },
  });
}
 function getTotal(searchTerm){
  var getTotal = "getTotal";
  var customer_id = $('#customer_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  $.ajax({
          type: "POST",
          url: "viewloanCotnroller.php",  
          data: {
            GetTotal:getTotal,
            SearchTerm:searchTerm,
            Customer_ID:customer_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
        },
        success: function (response) {  
          var total = JSON.parse(response);
          if(total.length > 0){
           
            $('#LoanReportTbl').append('<tr><td>Total</td><td></td><td id="res"></td><td></td><td></td><td></td><td></td><td></td>');
            for(var i = 0; i< total.length; i++){
              $('#res').append('<p class="text-dark">'+ numeral(total[i].amount).format('0,0') + ' ' +  total[i].currencyName  + '</p>');
            }
            
            <?php if($delete == 1  || $update == 1) { ?>
                $('#LoanReportTbl').append('<td></td>');
              //  console.log(totalCurrencyRes)
              <?php } ?>
              $('#LoanReportTbl').append('</tr>');
          }
          
        },
  });
 }

  function SaveUpdate(){
     var loanID = $('#loanID');
     var updcustomer_id = $('#updcustomer_id');
     var updamount =  $('#updamount');
     var updremarks = $('#updremarks');
     var currency_type = $('#currency_type');
     if(updcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer is required', 'error');
        return;
     }
     if(updamount.val() == ""){
        notify('Validation Error!', 'Amount is required', 'error');
        return;
     }
    var saveUpdateLoan = "saveUpdateLoan";
    var updaccount_id = $('#updaccount_id');
    if(updaccount_id.val() == ""){
        notify('Validation Error!', 'Account is required', 'error');
        return;
     }
  $.ajax({
        type: "POST",
        url: "viewloanCotnroller.php",  
        data: {
            SaveUpdateLoan:saveUpdateLoan,
            loanID:loanID.val(),
            Updcustomer_id:updcustomer_id.val(),
            Updamount:updamount.val(),
            Updremarks:updremarks.val(),
            Updaccount_ID:updaccount_id.val(),
            Currency_Type:currency_type.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#currency_type option:eq(0)').prop('selected',true);
            $('#updateModel').modal('hide');
            searchLoan();
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
function getCurrencies(selectedParam){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewloanCotnroller.php",  
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
