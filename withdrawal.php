<?php
  include 'header.php';
?>
<title>Withdrawal Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Withdrawal' ";
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
  #customBtn{ color:#29323c;border-color:#29323c; }
  #customBtn:hover{color:  #FFFFFF;background-color:#485563;border-color:#485563}
  .bg-graident-lightcrimson{
    background: #485563;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }
  .text-graident-lightcrimson{
    color: #485563;  /* fallback for old browsers */
    color: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
  
</style>
<div class="container-fluid"  >
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2 class="text-graident-lightcrimson" ><b><i class="fa fa-fw fa-money text-dark" ></i> <i>Add Withdrawal Report</i> </b></h2>
      </div>
      <div class="card-body">
        
        <div class="row">
        <div class="col-md-1" style="margin-top:40px" >
        <input class="form-check-input" type="checkbox" id="dateSearch" name="dateSearch" value="option1">
                <label class="form-check-label" for="exampleCheck1">Search By Date</label>
          </div>
        
          <div class="col-md-2">
            <label for="staticEmail" class="col-form-label">From:</label>
            <input type="text"  class="form-control" name="fromdate"  id="fromdate">
          </div>
          <div class="col-md-2">
            <label for="staticEmail" class="col-form-label">To:</label>
            <input type="text"  class="form-control " name="todate"  id="todate">
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Account:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" name="searchaccount_id" id="searchaccount_id"></select>
          </div>
          <div class="col-md-2">
          <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" onclick="getSalaryReport()" style="width:100%" class="btn btn-dark btn-block   text-white "><i class="fa fa-fw fa-search"></i> Search</button>
          </div>
          <div class="col-md-2" style="margin-top:35px">
            <?php if($insert == 1) { ?>
            <button type="button" class="btn " style="width:100%" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Withdrawal Money
            </button>
            <?php } ?>
        </div>
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-graident-lightcrimson">
            <tr class="text-center" style="font-size:14px">
              <th>S#</th>
              <th>Account</th>
              <th>Date Time</th>
              <th>Withdrawal Amount</th>
              <th>Withdrawal By</th>
              <th>Remarks</th>
              <?php if($update == 1 || $delete == 1) {  ?>
              <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="StaffReportTbl">
                    
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
<div class="modal fade" id="exampleModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Withdrawal</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9">
              <select class="form-control " style="width:100%" id="addaccount_id" name="addaccount_id" spry:default="select one"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-lg-3 col-form-label">Withdrawal Amount:</label>
            <div class="col-lg-6">
              <input type="text" class="form-control"  name="withdrawal_amount" id="withdrawal_amount" placeholder="Withdrawal Amount">
            </div>
            <div class="col-lg-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
          </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
            <div class="col-sm-9">
              <textarea class="form-control"  name="remarks" id="remarks" placeholder="Remarks"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="AddSalary()" class="btn text-white bg-graident-lightcrimson">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Withdrawal</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm"> 
          <input type="hidden" id="withdrawalID" name="withdrawalID" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9">
              <select class="form-control " style="width:100%" id="updaccount_id" name="updaccount_id" spry:default="select one"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-lg-3 col-form-label">Withdrawal Amount:</label>
            <div class="col-lg-6">
              <input type="text" class="form-control"  name="updwithdrawal_amount" id="updwithdrawal_amount" placeholder="Withdrawal Amount">
            </div>
            <div class="col-lg-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updcurrency_type" name="updcurrency_type" spry:default="select one"></select>
          </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
            <div class="col-sm-9">
              <textarea class="form-control"  name="updremarks" id="updremarks" placeholder="Remarks"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateSalary()" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

  
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getAccounts('all',0);
    $("#addaccount_id").select2({
      dropdownParent: $("#exampleModal")
    });
    $("#updaccount_id").select2({
      dropdownParent: $("#updexampleModal")
    });
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
      $('.js-example-basic-single').select2();
      getCurrencies('addCurrency',null);
      $("#updcurrency_type").select2({
      dropdownParent: $("#updexampleModal")
    });
    $("#currency_type").select2({
      dropdownParent: $("#exampleModal")
    });
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this withdrawal',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "withdrawalController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getSalaryReport();
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
          url: "withdrawalController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#withdrawalID').val(id);
            $('#updwithdrawal_amount').val(dataRpt[0].withdrawal_amount);
            getAccounts('byUpdate',dataRpt[0].accountID);
            $('#updremarks').val(dataRpt[0].remarks);
            getCurrencies("updateCurrency",dataRpt[0].currencyID);
            $('#updexampleModal').modal('show');
        },
  });
}
function getSalaryReport(){
      var getSalaryReport = "getSalaryReport";
      var searchTerm = '';
      var searchaccount_id = $('#searchaccount_id');
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      var dateSearch = $('#dateSearch');
      if(dateSearch.is(':checked') && searchaccount_id.val() != -1 ){
        searchTerm = "DateAndAccWise";
      }else if(dateSearch.is(':checked') && searchaccount_id.val() == -1 ){
        searchTerm  = "DateWise";
      }else if(!dateSearch.is(':checked') && searchaccount_id.val() != -1 ){
        searchTerm  = "AccWise";
      }
      if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
      }
      $.ajax({
          type: "POST",
          url: "withdrawalController.php",  
          data: {
            GetSalaryReport:getSalaryReport,
            SearchTerm:searchTerm,
            Searchaccount_ID:searchaccount_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
          },
          success: function (response) {  
            var Rpt = JSON.parse(response);
            if(Rpt.length === 0){
            $('#StaffReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td class='text-center'>Record Not Found</td><td></td><td></td><td></td></tr>";
            $('#StaffReportTbl').append(finalTable);
          }else{
            $('#StaffReportTbl').empty();
            var j = 1;
            var total = 0;
            var finalTable = "";
            for(var i=0; i<Rpt.length; i++){
              total += parseInt(Rpt[i].withdrawal_amount);
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+
              Rpt[i].account_Name +"</td><td class='text-center'>"+Rpt[i].datetime+"</td><td class='text-center'>"+ numeral(Rpt[i].withdrawal_amount).format('0,0') + ' ' + Rpt[i].currencyName +"</td><td class='text-center'>"+Rpt[i].staff_name+"</td><td "+
              "class='text-capitalize text-center'>"+Rpt[i].remarks+"</td>";
              <?php if($update == 1 || $delete == 1) { ?>
                finalTable +="<td class='text-center'>";
              <?php } ?>
              <?php if($update ==1) { ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              Rpt[i].withdrawal_ID +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if($delete ==1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ Rpt[i].withdrawal_ID +")'" +
                "class='btn'><i class='fa fa-trash text-graident-lightcrimson fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
            getTotal(searchTerm);
          }
          },
      });
    }
    function AddSalary(){
      var withdrawal_amount = $('#withdrawal_amount');
      if(withdrawal_amount.val() == ""){
        notify('Validation Error!', 'Withdrawal amount is required', 'error');
        return;
      }
      var addSalary = "addSalary";
      var addaccount_id = $('#addaccount_id').select2('data');
      if(addaccount_id[0].id == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      }
      addaccount_id = addaccount_id[0].id;
      var remarks = $('#remarks');
      if(remarks.val() == ""){
        notify('Validation Error!', 'Remarks is required', 'error');
        return;
      }
      var currency_type = $('#currency_type').select2('data');
      $.ajax({
        type: "POST",
        url: "withdrawalController.php",  
        data: {
            AddSalary:addSalary,
            Withdrawal_Amount:withdrawal_amount.val(),
            Addaccount_ID:addaccount_id,
            Remarks:remarks.val(),
            Currency_Type:currency_type[0].id
        },
        success: function (response) {  
            if(response == "Success"){
              notify('Success!', response, 'success');
              $('#addaccount_id').val(-1).trigger('change.select2');
              $('#currency_type').val(1).trigger('change.select2');
              $('#withdrawal_amount').val('');
              $('#remarks').val('');
              $('#exampleModal').modal('hide');
              getSalaryReport();
            }else{
              notify('Error!', response, 'error');
            }
        },
    });
    }
    // Update
    function updateSalary(){
      var withdrawalID = $('#withdrawalID');
      var updwithdrawal_amount = $('#updwithdrawal_amount');
      if(updwithdrawal_amount.val() == ""){
        notify('Validation Error!', 'Withdrawal amount is required', 'error');
        return;
      }
      var updSalary = "updSalary";
      var updaccount_id = $('#updaccount_id').select2('data');
      if(updaccount_id[0].id == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      }
      updaccount_id = updaccount_id[0].id;
      var updremarks = $('#updremarks');
      
      if(updremarks.val() == ""){
        notify('Validation Error!', 'Remarks is required', 'error');
        return;
      }
      var updcurrency_type = $('#updcurrency_type').select2('data');
      $.ajax({
        type: "POST",
        url: "withdrawalController.php",  
        data: {
            UpdSalary:updSalary,
            Updwithdrawal_Amount:updwithdrawal_amount.val(),
            WithdrawalID:withdrawalID.val(),
            Updaccount_ID:updaccount_id,
            Updremarks:updremarks.val(),
            UpdCurrency_Type:updcurrency_type[0].id
        },
        success: function (response) {  
            if(response == "Success"){
              notify('Success!', response, 'success');
              $('#updaccount_id').val(-1).trigger('change.select2');
              $('#updwithdrawal_amount').val('');
              $('#updremarks').val('');
              $('#updexampleModal').modal('hide');
              getSalaryReport();
            }else{
              notify('Error!', response, 'error');
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
              $('#searchaccount_id').empty();
              $('#searchaccount_id').append("<option value='-1'>--Account--</option>");
              for(var i=0; i<account.length; i++){
                $('#searchaccount_id').append("<option value='"+ account[i].account_ID +"'>"+ 
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
function getTotal(searchTerm){
  var getTotal = "getTotal";
  var searchaccount_id = $('#searchaccount_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  $.ajax({
          type: "POST",
          url: "withdrawalController.php",  
          data: {
            GetTotal:getTotal,
            SearchTerm:searchTerm,
            SearchAccount_ID:searchaccount_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
        },
        success: function (response) {  
          var total = JSON.parse(response);
          if(total.length > 0){
           
            $('#StaffReportTbl').append('<tr><td class="text-center"><b>Total</b></td><td></td><td></td><td class="text-center" id="res"></td><td></td><td></td>');
            for(var i = 0; i< total.length; i++){
              $('#res').append('<p class="text-dark"><b>'+ numeral(total[i].amount).format('0,0') + ' ' +  total[i].currencyName  + '</b></p>');
            }
            
            <?php if($delete == 1  || $update == 1) { ?>
                $('#StaffReportTbl').append('<td></td>');
              //  console.log(totalCurrencyRes)
              <?php } ?>
              $('#StaffReportTbl').append('</tr>');
          }
          
        },
  });
 }
 function getCurrencies(type, selectedCurrencyID){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "staffController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            if(type == "addCurrency"){
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
            }else{
              var selected = "";
                $('#updcurrency_type').empty();
                console.log(selectedCurrencyID);
                for(var i=0; i<currencyType.length; i++){
                if(selectedCurrencyID == currencyType[i].currencyID){
                  
                  selected = "selected";
                  $('#updcurrency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#updcurrency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
            }
            
        },
    });
    }
</script>
</body>
</html>
