<?php
  include 'header.php';
?>
<title>Salary Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Salary' ";
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
  #customBtn{ color:#666600;border-color:#666600; }
  #customBtn:hover{color:  #FFFFFF;background-color:#999966;border-color:#999966}
  .bg-graident-lightcrimson{
    background: #666600;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #999966, #666600);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #999966, #666600); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
  .text-graident-lightcrimson{
    color: #666600;  /* fallback for old browsers */
    color: -webkit-linear-gradient(to right, #999966, #666600);  /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to right, #999966, #666600); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2 class="text-graident-lightcrimson" ><b><i class="fa fa-fw fa-dollar text-dark" ></i> <i>Add Salary Report</i> </b></h2>
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
            <label for="staticEmail" class="col-form-label">Employee Name:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" name="searchemployee_id" id="searchemployee_id"></select>
          </div>
          <div class="col-md-2">
          <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" onclick="getSalaryReport()" style="width:100%" class="btn btn-dark btn-block   text-white "><i class="fa fa-fw fa-search"></i> Search</button>
          </div>
          <div class="col-md-2" style="margin-top:35px">
            <?php if($insert == 1) { ?>
            <button type="button" class="btn " style="width:100%" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add salary
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
              <th>Employee Name</th>
              <th>Date Time</th>
              <th>Salary Paid</th>
              <th>Account</th>
              <th>Paid By</th>
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
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Salary</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Employee Name:</label>
            <div class="col-sm-9">
                <select class="form-control" style="width:100%" id="addemployee_id" name="addemployee_id" spry:default="select one"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Salary Amount:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="salary_amount" id="salary_amount" placeholder="Salary Amount">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9">
              <select class="form-control " style="width:100%" id="addaccount_id" name="addaccount_id" spry:default="select one"></select>
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
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Salary</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm"> 
          <input type="hidden" id="salaryID" name="salaryID" />
          
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Employee Name:</label>
            <div class="col-sm-9">
              <select class="form-control " style="width:100%" id="updemployee_id" name="updemployee_id" spry:default="select one"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Salary Amount:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updsalary_amount" id="updsalary_amount" placeholder="Salary Amount">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9">
              <select class="form-control " style="width:100%" id="updaccount_id" name="updaccount_id" spry:default="select one"></select>
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
    getEmployee('all',0);
    $("#addemployee_id").select2({
      dropdownParent: $("#exampleModal")
    });
    $("#updemployee_id").select2({
      dropdownParent: $("#updexampleModal")
    });
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
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this salary',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "salaryController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                beforeSend: function () {
                    HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Deleting Data...</h4>"
                    });
                },
                complete: function () {
                  HoldOn.close();
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
          url: "salaryController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Getting Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#salaryID').val(id);
            getEmployee('byUpdate',dataRpt[0].employee_id);
            $('#updsalary_amount').val(dataRpt[0].salary_amount);
            getAccounts('byUpdate',dataRpt[0].paymentType);
            $('#updexampleModal').modal('show');
        },
  });
}
function getSalaryReport(){
      var getSalaryReport = "getSalaryReport";
      var searchTerm = '';
      var searchemployee_id = $('#searchemployee_id');
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      var dateSearch = $('#dateSearch');
      if(dateSearch.is(':checked') && searchemployee_id.val() != -1 ){
        searchTerm = "DateAndEmpWise";
      }else if(dateSearch.is(':checked') && searchemployee_id.val() == -1 ){
        searchTerm  = "DateWise";
      }else if(!dateSearch.is(':checked') && searchemployee_id.val() != -1 ){
        searchTerm  = "EmpWise";
      }
      if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
      }
      $.ajax({
          type: "POST",
          url: "salaryController.php",  
          data: {
            GetSalaryReport:getSalaryReport,
            SearchTerm:searchTerm,
            Searchemployee_id:searchemployee_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val()
          },
          beforeSend: function () {
              HoldOn.open({
                  theme: 'sk-cube-grid',
                  message: "<h4>Loading Data...</h4>"
              });
          },
          complete: function () {
              HoldOn.close();
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
              total += parseInt(Rpt[i].salary_amount);
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+
              Rpt[i].paidToEmployee +"</td><td class='text-center'>"+Rpt[i].datetime+"</td><td class='text-center'>"+ numeral(Rpt[i].salary_amount).format('0,0') +"</td><td class='text-center'>"+Rpt[i].account_Name+"</td><td "+
              "class='text-capitalize text-center'>"+Rpt[i].paidbyEmployee+"</td>";
              <?php if($update == 1 || $delete == 1) { ?>
                finalTable +="<td class='text-center'>";
              <?php } ?>
              <?php if($update ==1) { ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              Rpt[i].salary_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if($delete ==1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ Rpt[i].salary_id +")'" +
                "class='btn'><i class='fa fa-trash text-graident-lightcrimson fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
            if(total > 0){
              finalTable = "<tr><td class='text-center'><b>Total</b></td><td></td><td></td><td class='text-center'><b>"+numeral(total).format('0,0')+"</b></td><td></td><td></td>";
              
              <?php if($update == 1 || $delete == 1) { ?>
                finalTable += "<td></td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
            }
          }
          },
      });
    }
    function getEmployee(type,id){
    var select_employee = "select_employee";
    $.ajax({
        type: "POST",
        url: "salaryController.php",  
        data: {
            Select_Employee:select_employee,
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Loading Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            var employee = JSON.parse(response);
            if(type=="all"){
                $('#addemployee_id').empty();
                $('#addemployee_id').append("<option value='-1'>-- Select Employee --</option>");
                for(var i=0; i<employee.length; i++){
                $('#addemployee_id').append("<option value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
                }
                $('#searchemployee_id').empty();
                $('#searchemployee_id').append("<option value='-1'>-- Select Employee --</option>");
                for(var i=0; i<employee.length; i++){
                $('#searchemployee_id').append("<option value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
                }
            }else if(type=="byUpdate"){
                $('#updemployee_id').empty();
                $('#updemployee_id').append("<option value='-1'>-- Select Employee --</option>");
                var selected = '';
                for(var i=0; i<employee.length; i++){
                if(employee[i].staff_id == id){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updemployee_id').append("<option "+ selected + " value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
                }
            }
        },
    });
    }
    function AddSalary(){
      var addemployee_id = $('#addemployee_id').select2('data');
      if(addemployee_id[0].id == "-1"){
        notify('Validation Error!', 'Employee name is required', 'error');
        return;
      }
      addemployee_id = addemployee_id[0].id;
      var salary_amount = $('#salary_amount');
      if(salary_amount.val() == ""){
        notify('Validation Error!', 'Salary amount is required', 'error');
        return;
      }
      var addSalary = "addSalary";
      var addaccount_id = $('#addaccount_id').select2('data');
      if(addaccount_id[0].id == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      }
      addaccount_id = addaccount_id[0].id;
      $.ajax({
        type: "POST",
        url: "salaryController.php",  
        data: {
            AddSalary:addSalary,
            Addemployee_ID:addemployee_id,
            Salary_Amount:salary_amount.val(),
            Addaccount_ID:addaccount_id
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Saving Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            if(response == "Success"){
              notify('Success!', response, 'success');
              $('#addemployee_id').val(-1).trigger('change.select2');
              $('#addaccount_id').val(-1).trigger('change.select2');
              $('#salary_amount').val('');
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
      var salaryID = $('#salaryID');
      var updemployee_id = $('#updemployee_id').select2('data');
      if(updemployee_id[0].id == "-1"){
        notify('Validation Error!', 'Employee name is required', 'error');
        return;
      }
      updemployee_id = updemployee_id[0].id;
      var updsalary_amount = $('#updsalary_amount');
      if(updsalary_amount.val() == ""){
        notify('Validation Error!', 'Salary amount is required', 'error');
        return;
      }
      var updSalary = "updSalary";
      var updaccount_id = $('#updaccount_id').select2('data');
      if(updaccount_id[0].id == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      }
      updaccount_id = updaccount_id[0].id;
      $.ajax({
        type: "POST",
        url: "salaryController.php",  
        data: {
            UpdSalary:updSalary,
            Updemployee_id:updemployee_id,
            Updsalary_Amount:updsalary_amount.val(),
            SalaryID:salaryID.val(),
            Updaccount_ID:updaccount_id
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Saving Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            if(response == "Success"){
              notify('Success!', response, 'success');
              $('#updemployee_id').val(-1).trigger('change.select2');
              $('#updaccount_id').val(-1).trigger('change.select2');
              $('#updsalary_amount').val('');
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
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Loading Data...</h4>"
            });
        },
        complete: function () {

            HoldOn.close();
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
</script>
</body>
</html>
