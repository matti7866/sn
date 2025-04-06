<?php
  include 'header.php';
?>
<title>Loan Form</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier' ";
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
      <h2 class="text-blue-600"> Supplier Entry  <span class="text-dark"><i>Form</i></span></h2>
    </div>
    <div  class="card-body">
    <form id="addSupplier" >
      <div class="row g-3 mb-2 align-items-center">
        <div class="col-md-1 text-inverse">
          <label for="inputPassword6" class="col-form-label"><i class="fa fa-user"></i> Supplier Name:</label>
        </div>
        <div class="col-md-3">
          <input type="text" id="supplier_name" class="col-sm-4 form-control" name="supplier_name"  placeholder="Enter Supplier Name"  />
        </div>
      </div>
      <div class="row g-3 mb-2 align-items-center">
        <div class="col-md-1 text-inverse">
          <label for="inputPassword6" class="col-form-label"><i class="fa fa-envelope"></i> Supplier Email:</label>
        </div>
        <div class="col-md-3">
          <input type="text" id="supplier_email" class="col-sm-4 form-control" name="supplier_email"  placeholder="Enter Supplier Email"  />
        </div>
      </div>
      <div class="row g-3 mb-2 align-items-center">
        <div class="col-md-1 text-inverse">
          <label for="inputPassword6" class="col-form-label"><i class="fa fa-envelope"></i> Supplier Address:</label>
        </div>
        <div class="col-md-3">
          <input type="text" id="supplier_address" class="col-sm-4 form-control" name="supplier_address"  placeholder="Enter Supplier Address"  />
        </div>
      </div>
      <div class="row g-3 mb-2 align-items-center">
        <div class="col-md-1 text-inverse">
          <label for="inputPassword6" class="col-form-label"><i class="fa fa-phone"></i> Supplier Phone:</label>
        </div>
        <div class="col-md-3">
          <input type="text" id="supplier_phone" class="col-sm-4 form-control" name="supplier_phone"  placeholder="Enter Supplier Phone"  />
        </div>
      </div>
      <div class="row g-3 mb-2 align-items-center">
        <div class="col-md-1 text-inverse">
          <label for="inputPassword6" class="col-form-label"><i class="fa fa-user"></i> Supplier Type:</label>
        </div>
        <div class="col-md-3">
          <select id="supplierTypeID" class="form-control col-md-2">
            <option value="-1">--Select Supplier Type --</option>
            <option value="1">Travel</option>
            <option value="2">Exchange</option>
          </select>
        </div>
      </div>
    </div>
  <div class="card-footer bg-light">
    <button  name="insert" type="button"  onclick="SaveSupplier()" class="btn btn-blue" ><i class="fa fa-fw fa-save"></i> Save Record</button>
    <a href="viewsupplier.php" class="text-blue"><i class="fa fa-fw fa-info"></i>View Report</a>
</form>
</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
<script>
function SaveSupplier(){
	var SaveSupplier = "SaveSupplier";
	var supplier_name = $('#supplier_name');
    if(supplier_name.val() == ""){
        notify('Error!', 'Supplier name is required', 'error');
        return;
    }
    var supplier_email = $('#supplier_email');
    if(supplier_email.val() == ""){
        notify('Error!', 'Supplier email is required', 'error');
        return;
    }
    var supplier_address = $('#supplier_address');
    if(supplier_address.val() == ""){
        notify('Error!', 'Supplier address is required', 'error');
        return;
    }
    var supplier_phone = $('#supplier_phone');
    if(supplier_phone.val() == ""){
        notify('Error!', 'Supplier phone is required', 'error');
        return;
    }
	var supplierTypeID = $('#supplierTypeID');
	if(supplierTypeID.val() == "-1"){
        notify('Error!', 'Supplier Type is required', 'error');
        return;
    }
	$.ajax({
        type: "POST",
        url: "supplierController.php",  
        data: {
            SaveSupplier:SaveSupplier,
            Supplier_Name:supplier_name.val(),
            Supplier_Email:supplier_email.val(),
            Supplier_Address:supplier_address.val(),
            Supplier_Phone:supplier_phone.val(),
            SupplierTypeID:supplierTypeID.val()
        },
        success: function (response) {
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#addSupplier')[0].reset();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
          }else{
            notify('Opps!', response, 'error');
          }
        },
      });
}
$(document).ready(function() {
    getCustomers();
    $('.js-example-basic-single').select2();

});
</script>
</body>
</html>