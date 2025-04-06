<?php
  include 'header.php';
?>
<title>Staff Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Staff' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
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
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2 style="color: #33001b;"><b><i class="fa fa-fw fa-user" style="color: #33001b;"></i> <i>Staff Report </i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-md-8"> 
            <?php  if($insert == 1) { ?>
            <button type="button" class="btn float-end" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Employee
            </button>
            <?php    } ?>
        </div>
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white" style="background: #33001b;">
            <tr>
              <th>S#</th>
              <th>Name</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Address</th>
              <th>Salary</th>
              <th>Role</th>
              <th>Status</th>
              <th>Branch</th>
              <th>Photo</th>
                <?php  if($update == 1 || $delete ==1) { ?>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#33001b">
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Staff</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="StaffForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Employee Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="employee_name" id="employee_name" placeholder="Employee Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Phone:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="employee_phone" id="employee_phone" placeholder="Employee Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Email:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="employee_email" id="employee_email" placeholder="Employee Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Address:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="employee_address" id="employee_address" placeholder="Employee Address">
            </div>
          </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Branch:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="employee_branch" id="employee_branch"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Salary:</label>
          <div class="col-lg-6">
            <input type="number" class="form-control"  name="employee_salary" id="employee_salary" placeholder="Employee Salary">
          </div>
          <div class="col-lg-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Role:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="employee_role" id="employee_role"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Status:</label>
          <div class="col-sm-9">
              <select id="employee_status" name="employee_status" class="form-control">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Password:</label>
          <div class="col-sm-9">
          <input type="password" class="form-control" name="employee_password" id="employee_password" placeholder="Employee Password" autocomplete="off">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Photo:</label>
          <div class="col-sm-9">
            <input type="file" class="form-control" id="uploadFile" name="uploadFile">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white" style="background-color:#33001b">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#33001b">
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Staff</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="updStaffForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="employee_id" name="employee_id" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Employee Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updemployee_name" id="updemployee_name" placeholder="Employee Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Phone:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updemployee_phone" id="updemployee_phone" placeholder="Employee Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Email:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updemployee_email" id="updemployee_email" placeholder="Employee Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Address:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updemployee_address" id="updemployee_address" placeholder="Employee Address">
            </div>
          </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Branch:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single1" style="width:100%" name="updemployee_branch" id="updemployee_branch"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Salary:</label>
          <div class="col-lg-6">
            <input type="number" class="form-control"  name="updemployee_salary" id="updemployee_salary" placeholder="Employee Salary">
          </div>
          <div class="col-lg-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updcurrency_type" name="updcurrency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Role:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single1" style="width:100%" name="updemployee_role" id="updemployee_role"></select>
          </div>
        </div> 
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Status:</label>
          <div class="col-sm-9">
              <select id="updemployee_status" name="updemployee_status" class="form-control">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
          </div>
        </div> 
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Password:</label>
          <div class="col-sm-9">
          <input type="password" class="form-control" name="updemployee_password" id="updemployee_password" placeholder="Employee Password">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Photo:</label>
          <div class="col-sm-9">
            <input type="file" class="form-control" id="upduploadFile" name="upduploadFile">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white" style="background-color:#33001b">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

  
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getBranch('all',0);
    getRole('all',0);
    getCurrencies('addCurrency',null);
    $(".js-example-basic-single").select2({
      dropdownParent: $("#exampleModal")
    });
    $(".js-example-basic-single1").select2({
      dropdownParent: $("#updexampleModal")
    });
    $("#updcurrency_type").select2({
      dropdownParent: $("#updexampleModal")
    });
    
    getStaffReport();
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this employee',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "staffController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getStaffReport();
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
          url: "staffController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#employee_id').val(id);
            $('#updemployee_name').val(dataRpt[0].staff_name);
            $('#updemployee_phone').val(dataRpt[0].staff_phone);
            $('#updemployee_email').val(dataRpt[0].staff_email);
            $('#updemployee_address').val(dataRpt[0].staff_address);
            getBranch('byStaff',dataRpt[0].staff_branchID);
            getRole('byStaff',dataRpt[0].role_id);
            $('#updemployee_salary').val(dataRpt[0].salary);
            $('#updemployee_status').val(dataRpt[0].status);
            getCurrencies("updateCurrency",dataRpt[0].currencyID);
            $('#updexampleModal').modal('show');
        },
  });
}

function getBranch(type,id){
    var getBranch = "getBranch";
    $.ajax({
        type: "POST",
        url: "staffController.php",  
        data: {
          GetBranch:getBranch,
        },
        success: function (response) {  
            var branch = JSON.parse(response);
            if(type == "all"){
              $('#employee_branch').empty();
              $('#employee_branch').append("<option value='-1'>--Select Branch--</option>");
              for(var i=0; i<branch.length; i++){
                $('#employee_branch').append("<option value='"+ branch[i].Branch_ID +"'>"+ 
                branch[i].Branch_Name +"</option>");
              }
            }else{
              $('#updemployee_branch').empty();
              $('#updemployee_branch').append("<option value='-1'>--Select Branch--</option>");
              for(var i=0; i<branch.length; i++){
                if(branch[i].Branch_ID ==id ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updemployee_branch').append("<option "+ selected +" value='"+ branch[i].Branch_ID +"'>"+ 
                branch[i].Branch_Name +"</option>");
              }
            }
        },
    });
  }
  function getRole(type,id){
    var getRole = "getRole";
    $.ajax({
        type: "POST",
        url: "staffController.php",  
        data: {
          GetRole:getRole,
        },
        success: function (response) {  
            var roles = JSON.parse(response);
            if(type == "all"){
              $('#employee_role').empty();
              $('#employee_role').append("<option value='-1'>--Select Role--</option>");
              for(var i=0; i<roles.length; i++){
                $('#employee_role').append("<option value='"+ roles[i].role_id +"'>"+ 
                roles[i].role_name +"</option>");
              }
            }else{
              $('#updemployee_role').empty();
              $('#updemployee_role').append("<option value='-1'>--Select Role--</option>");
              for(var i=0; i<roles.length; i++){
                if(roles[i].role_id ==id ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updemployee_role').append("<option "+ selected +" value='"+ roles[i].role_id +"'>"+ 
                roles[i].role_name +"</option>");
              }
            }
        },
    });
  }
  function SaveUpdate(){
     var updvisaID = $('#updvisaID');
     var updcustomer_id = $('#updcustomer_id');
     var updPassengerName =  $('#updPassengerName');
     var updPassportNum = $('#updPassportNum');
     var updcountry_id = $('#updcountry_id'); 
     var updSale = $('#updSale');
     var updNet = $('#updNet');
     var updsupplier = $('#updsupplier');
     var updguarantee = $('#updguarantee');
     var updaddress = $('#updaddress');
     var updcurrency_type = $('#updcurrency_type');
     if(updcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer is required', 'error');
        return;
     }
     if(updPassengerName.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
     }
     if(updcountry_id.val() == ""){
        notify('Validation Error!', 'Country is required', 'error');
        return;
     }
     if(updSale.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
     }
     if(updNet.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
     }
     if(updsupplier.val() == ""){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
     }
     if(updaddress.val() == ""){
        notify('Validation Error!', 'Address is required', 'error');
        return;
     }
    var saveUpdateVisa = "saveUpdateVisa";
  $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            SaveUpdateVisa:saveUpdateVisa,
            UpdvisaID:updvisaID.val(),
            Updcustomer_id:updcustomer_id.val(),
            UpdPassengerName:updPassengerName.val(),
            UpdPassportNum:updPassportNum.val(),
            Updcountry_ID:updcountry_id.val(),
            UpdSale:updSale.val(),
            UpdNet:updNet.val(),
            Updsupplier:updsupplier.val(),
            Updguarantee:updguarantee.val(),
            Updaddress:updaddress.val(),
            Currency_Type:currency_type.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            searchVisa();
          }else{
            notify('Opps!', response, 'error');
          }
          
        },
      });
}
function getStaffReport(){
      var getStaffReport = "getStaffReport";
      $.ajax({
          type: "POST",
          url: "staffController.php",  
          data: {
              GetStaffReport:getStaffReport
          },
          success: function (response) {  
            var staffRpt = JSON.parse(response);
            $('#StaffReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<staffRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ staffRpt[i].staff_name +"</td>"+
              "<td class='text-capitalize'>"+ staffRpt[i].staff_phone +"</td><td>"+ staffRpt[i].staff_email +"</td><td>"+ 
              staffRpt[i].staff_address +"</td><td>"+ staffRpt[i].salary + ' '+ staffRpt[i].currencyName + "</td><td>"+ staffRpt[i].role_name +"</td><td>"+
              staffRpt[i].status +"</td>"+ "<td>"+ staffRpt[i].Branch_Name +"</td><td><img style='width:70px;height:70px' "+
              " src='"+ staffRpt[i].staff_pic +"' alt='No Image'> </td>";
               <?php if($update == 1 || $delete == 1){  ?>
                        finalTable += "<td>";
                <?php    }  ?>
                <?php if($update == 1) { ?>
              finalTable += "<button type='button'0 onclick='GetDataForUpdate("+ 
              staffRpt[i].staff_id +")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
             <?php  } ?>
             <?php if($delete == 1) { ?>
                  finalTable += "<button type='button'0 onclick='Delete("+ 
              staffRpt[i].staff_id +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button> ";
              <?php } ?> 
             <?php if($update == 1 || $delete == 1){  ?>
                        finalTable += "</td>";
                <?php    }  ?>
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
$(document).on('submit', '#StaffForm', function(event){
    event.preventDefault();
    var employee_name = $('#employee_name');
    if(employee_name.val() == ""){
        notify('Validation Error!', 'Employee name is required', 'error');
        return;
    }
    var employee_phone = $('#employee_phone');
    if(employee_phone.val() == ""){
        notify('Validation Error!', 'Employee phone is required', 'error');
        return;
    }
    var employee_email = $('#employee_email');
    if(employee_email.val() == ""){
        notify('Validation Error!', 'Employee email is required', 'error');
        return;
    }
    var employee_address = $('#employee_address');
    if(employee_address.val() == ""){
        notify('Validation Error!', 'Employee address is required', 'error');
        return;
    }
    var employee_branch = $('#employee_branch').select2('data');
    if(employee_branch[0].id == "-1"){
        notify('Validation Error!', 'Branch is required', 'error');
        return;
    }
    var employee_role = $('#employee_role').select2('data');
    if(employee_role[0].id == "-1"){
        notify('Validation Error!', 'Role is required', 'error');
        return;
    }
    var employee_salary = $('#employee_salary');
    if(employee_salary.val() == ""){
        notify('Validation Error!', 'Employee salary is required', 'error');
        return;
    }
    var employee_status = $('#employee_status');
    var employee_password= $('#employee_password');
    if(employee_password.val() == ""){
        notify('Validation Error!', 'Password is required', 'error');
        return;
    }
    var employee_photo = $('#uploadFile').val();
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
        return;
      }
    }
    var currency_type = $('#currency_type');
      data = new FormData(this);
      data.append('Insert_Staff','Insert_Staff');
      console.log(data);
        $.ajax({
            type: "POST",
            url: "staffController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#StaffForm')[0].reset();
                    $('#currency_type').val(1).trigger('change.select2');
                    $('#employee_role').val(-1).trigger('change.select2');
                    $('#employee_branch').val(-1).trigger('change.select2');
                    $('#exampleModal').modal('hide');
                    getStaffReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#updStaffForm', function(event){
    event.preventDefault();
    var updemployee_name = $('#updemployee_name');
    if(updemployee_name.val() == ""){
        notify('Validation Error!', 'Employee name is required', 'error');
        return;
    }
    var updemployee_phone = $('#updemployee_phone');
    if(updemployee_phone.val() == ""){
        notify('Validation Error!', 'Employee phone is required', 'error');
        return;
    }
    var updemployee_email = $('#updemployee_email');
    if(updemployee_email.val() == ""){
        notify('Validation Error!', 'Employee email is required', 'error');
        return;
    }
    var updemployee_address = $('#updemployee_address');
    if(updemployee_address.val() == ""){
        notify('Validation Error!', 'Employee address is required', 'error');
        return;
    }
    var updemployee_branch = $('#updemployee_branch').select2('data');
    if(updemployee_branch[0].id == "-1"){
        notify('Validation Error!', 'Branch is required', 'error');
        return;
    }
    var updemployee_role = $('#updemployee_role').select2('data');
    if(updemployee_role[0].id == "-1"){
        notify('Validation Error!', 'Role is required', 'error');
        return;
    }
    var updemployee_salary = $('#updemployee_salary');
    if(updemployee_salary.val() == ""){
        notify('Validation Error!', 'Employee salary is required', 'error');
        return;
    }
    var updemployee_status = $('#updemployee_status');
    var updemployee_password= $('#updemployee_password');
    var updemployee_photo = $('#upduploadFile').val();
    if($('#upduploadFile').val() != ''){
    if($('#upduploadFile')[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
        return;
      }
    }
    var updcurrency_type = $('#updcurrency_type');
      data = new FormData(this);
      data.append('Update_Staff','Update_Staff');
        $.ajax({
            type: "POST",
            url: "staffController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#updStaffForm')[0].reset();
                    $('#updemployee_role').val(-1).trigger('change.select2');
                    $('#updemployee_branch').val(-1).trigger('change.select2');
                    
                    $('#updexampleModal').modal('hide');
                    getStaffReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
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
