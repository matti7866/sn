<?php
  include 'header.php';
?>
<title>Accounts Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ";
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
  .bg-graident-lightcrimson{
    background: #005C97;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #363795, #005C97);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #363795, #005C97); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
  .text-graident-lightcrimson{
    color: #005C97;  /* fallback for old browsers */
    color: -webkit-linear-gradient(to right, #363795, #005C97);  /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to right, #363795, #005C97); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2 class="text-graident-lightcrimson" ><b><i class="fa fa-fw fa-paypal text-dark" ></i> <i>Add Accounts Report</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-md-8">
            <?php if($insert == 1) { ?>
            <button type="button" class="btn float-end" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Accounts
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
              <th>Account Name</th>
              <th>Account Number</th>
              <th>Account Type</th>
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Accounts</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="account_name" id="account_name" placeholder="Account Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Number:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="account_number" id="account_number" placeholder="Account Number">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Type:</label>
            <div class="col-sm-9">
              <select class="form-control" id="accountType" name="accountType">
                <option value="-1">--Select Account Type--</option>
                <option value="1">Personal</option>
                <option value="2">Bussiness</option>
                <option value="3">Cash</option>
              </select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white bg-graident-lightcrimson">Save</button>
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
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Accounts</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="accountID" name="accountID" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updaccount_name" id="updaccount_name" placeholder="Account Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Number:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updaccount_number" id="updaccount_number" placeholder="Account Number">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account Type:</label>
            <div class="col-sm-9">
              <select class="form-control" id="updaccountType" name="updaccountType">
                <option value="-1">--Select Account Type--</option>
                <option value="1">Personal</option>
                <option value="2">Bussiness</option>
                <option value="3">Cash</option>
              </select>
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

  
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getAccountsReport();
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this account',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "accountsController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getAccountsReport();
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
          url: "accountsController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#accountID').val(id);
            $('#updaccount_name').val(dataRpt[0].account_Name);
            $('#updaccount_number').val(dataRpt[0].accountNum);
            $('#updaccountType').val(dataRpt[0].accountType);
            $('#updexampleModal').modal('show');
        },
  });
}
function getAccountsReport(){
      var getAccountsReport = "getAccountsReport";
      $.ajax({
          type: "POST",
          url: "accountsController.php",  
          data: {
                GetAccountsReport:getAccountsReport
          },
          success: function (response) {  
            var Rpt = JSON.parse(response);
            $('#StaffReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<Rpt.length; i++){
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+
              Rpt[i].account_Name +"</td><td class='text-capitalize text-center'>"+Rpt[i].accountNum+"</td><td class='text-capitalize text-center'>"+Rpt[i].accountType+"</td>";
              <?php if($update == 1 || $delete == 1) { ?>
                finalTable +="<td class='text-center'>";
              <?php } ?>
              <?php if($update ==1) { ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              Rpt[i].account_ID +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if($delete ==1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ Rpt[i].account_ID +")'" +
                "class='btn'><i class='fa fa-trash text-graident-lightcrimson fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
$(document).on('submit', '#CountryNameForm', function(event){
    event.preventDefault();
    var account_name = $('#account_name');
    var account_number = $('#account_number');
    var accountType = $('#accountType');
    if(account_name.val() == ""){
        notify('Validation Error!', 'Account name is required', 'error');
        return;
    }
    if(accountType.val() == "-1"){
        notify('Validation Error!', 'Account type is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "accountsController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#CountryNameForm')[0].reset();
                    $('#exampleModal').modal('hide');
                    getAccountsReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var accountID = $('#accountID');
    var updaccount_name = $('#updaccount_name');
    var updaccount_number = $('#updaccount_number');
    var updaccountType = $('#updaccountType');
    if(updaccount_name.val() == ""){
        notify('Validation Error!', 'Account name is required', 'error');
        return;
    }
    if(updaccountType.val() == "-1"){
        notify('Validation Error!', 'Account type is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "accountsController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updexampleModal').modal('hide');
                    getAccountsReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
</script>
</body>
</html>
