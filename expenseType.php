<?php
  include 'header.php';
?>
<title>Expense Type Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
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
        <h2 class="text-danger" ><b><i class="fa fa-fw fa-money text-dark" ></i> <i>Expense Type Report</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10 ">
            <button type="button" class="btn float-end" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Expense Type
            </button>
        </div>
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-dark">
            <tr class="text-center" style="font-size:15px">
              <th>S#</th>
              <th>Expense Type</th>
              <th>Action</th>
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
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Expense Type</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-3 col-form-label">Expense Type:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="expense_type" id="expense_type" placeholder="Expense Type">
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
          <input type="hidden" id="expenseTID" name="expenseTID" />
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-3 col-form-label">Expense Type:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updexpense_type" id="updexpense_type" placeholder="Expense Type">
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
    getExpensesTypeReport();
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this expense type',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "expenseTypeController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getExpensesTypeReport();
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
          url: "expenseTypeController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#expenseTID').val(id);
            $('#updexpense_type').val(dataRpt[0].expense_type);
            $('#updexampleModal').modal('show');
        },
  });
}
function getExpensesTypeReport(){
      var getExpensesTypeReport = "getExpensesTypeReport";
      $.ajax({
          type: "POST",
          url: "expenseTypeController.php",  
          data: {
            GetExpensesTypeReport:getExpensesTypeReport
          },
          success: function (response) {  
            var expenseTypeRpt = JSON.parse(response);
            $('#StaffReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<expenseTypeRpt.length; i++){
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+ expenseTypeRpt[i].expense_type
              +"</td>";

              finalTable += "<td class='text-center'><button  type='button'0 onclick='GetDataForUpdate("+ 
              expenseTypeRpt[i].expense_type_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;<button type='button'0 onclick='Delete("+ 
              expenseTypeRpt[i].expense_type_id +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button> </td>";
              
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
$(document).on('submit', '#CountryNameForm', function(event){
    event.preventDefault();
    var expense_type = $('#expense_type');
    if(expense_type.val() == ""){
        notify('Validation Error!', 'Expense Type is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "expenseTypeController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#CountryNameForm')[0].reset();
                    $('#exampleModal').modal('hide');
                    getExpensesTypeReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var expenseTID = $('#expenseTID');
    if(expenseTID.val() == ""){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
    }
    var updexpense_type = $('#updexpense_type');
    if(updexpense_type.val() == ""){
        notify('Validation Error!', 'Expense type is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "expenseTypeController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updexampleModal').modal('hide');
                    getExpensesTypeReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
</script>
</body>
</html>
