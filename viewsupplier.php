<?php
  include 'header.php';
?>
<title>Supplier Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier' ";
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
      <h2><b>Supplier<span style="color:#C66"> Report</span></b></h2>
    </div>
    <div class="card-body">
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white" style="background-color:#C66">
            <tr>
              <th>S#</th>
              <th>Supplier Name</th>
              <th>Supplier Email</th>
              <th>Supplier Address</th>
              <th>Supplier Phone</th>
              <th>Supplier Type</th>
              <?php if($update == 1 || $delete == 1) { ?>
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
        <input type="hidden"  class="form-control" id="supplierID" name="supplierID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Name:</label>
          <div class="col-sm-9">
               <input type="text" class="form-control" id="supplier_name" name="supplier_name" placeholder="Supplier Name" />
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Email:</label>
          <div class="col-sm-9">
            <input type="email" class="form-control"  name="supplier_email" id="supplier_email" placeholder="Supplier Email">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Address:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control"  name="supplier_address" id="supplier_address" placeholder="Supplier Address">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Phone:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control"  name="supplier_phone" id="supplier_phone" placeholder="Supplier Phone">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Type:</label>
          <div class="col-sm-9">
            <select id="supplier_type" name="supplier_type" class="form-control">
                <option value="1">Travel</option>
                <option value="2">Exchange</option>
            </select>
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
$(document).ready(function() {
  getReport();
});
function getReport(){
  var getReport = "getReport";
  $.ajax({
        type: "POST",
        url: "viewsupplierController.php",  
        data: {
            GetReport:getReport
        },
        success: function (response) {
          var supplierRpt = JSON.parse(response);
          if(supplierRpt.length === 0){
            $('#LoanReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td>";
          
              finalTable += "<td></td>";
      
              finalTable +="</tr>";
            $('#LoanReportTbl').append(finalTable);
          }else{
            $('#LoanReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<supplierRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ supplierRpt[i].supp_name +"</td>"+
              "<td>"+ supplierRpt[i].supp_email +"</td><td>"+ supplierRpt[i].supp_add +"</td><td>"+ 
              supplierRpt[i].supp_phone +"</td><td class='text-capitalize'>"+ supplierRpt[i].supp_type +"</td>";
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1) { ?>
              finalTable += "<button type='button'0 onclick='Update("+ supplierRpt[i].supp_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
               <?php } ?>
               <?php if($delete == 1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              supplierRpt[i].supp_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable +="</td>";
              <?php } ?>
              
              
              finalTable += "</tr>";
              
              $('#LoanReportTbl').append(finalTable);
            
              j +=1;
            }
          }  
        },
    });
}
function Delete(suppID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this supplier',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewsupplierController.php",  
                data: {
                  Delete:Delete,
                  SuppID:suppID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getReport();
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
function Update(suppID){
  var GetUpdSupplier = "GetUpdSupplier";
  $.ajax({
          type: "POST",
          url: "viewsupplierController.php",  
          data: {
            GetUpdSupplier:GetUpdSupplier,
            SuppID:suppID,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#supplierID').val(suppID);
            $('#supplier_name').val(dataRpt[0].supp_name);
            $('#supplier_email').val(dataRpt[0].supp_email);
            $('#supplier_address').val(dataRpt[0].supp_add);
            $('#supplier_phone').val(dataRpt[0].supp_phone);
            $('#supplier_type').val(dataRpt[0].supp_type_id);
            $('#updateModel').modal('show');
        },
  });
}
  function SaveUpdate(){
     var supplierID = $('#supplierID');
     var supplier_name = $('#supplier_name');
     var supplier_email =  $('#supplier_email');
     var supplier_address = $('#supplier_address');
     var supplier_phone = $('#supplier_phone');
     var supplier_type = $('#supplier_type');
     if(supplier_name.val() == ""){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
     }
     if(supplier_type.val() == ""){
        notify('Validation Error!', 'Supplier type is required', 'error');
        return;
     }
    var saveUpdateSupplier = "saveUpdateSupplier";
  $.ajax({
        type: "POST",
        url: "viewsupplierController.php",  
        data: {
            SaveUpdateSupplier:saveUpdateSupplier,
            SupplierID:supplierID.val(),
            Supplier_Name:supplier_name.val(),
            Supplier_Email:supplier_email.val(),
            Supplier_Address:supplier_address.val(),
            Supplier_Phone:supplier_phone.val(),
            Supplier_Type:supplier_type.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            getReport();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
          }
          
        },
      });
}
</script>
</body>
</html>
