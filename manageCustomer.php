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
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer' ";
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
    .bg-blue{
        background: #000046;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .text-blue{
        color: #000046;  /* fallback for old browsers */
        color: -webkit-linear-gradient(to right, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
        color: linear-gradient(to right, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
  #customBtn{ color:#1CB5E0;border-color:#000046; }
  #customBtn:hover{color:  #1CB5E0;background-color:#000046;border-color:#000046}
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div  class="card" id="todaycard">
      <div class="card-header bg-blue" >
        <h2 class="text-white" ><b><i class="fa fa-fw fa-user text-dark" ></i> <i>Add Customers Report</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-md-8">
            <?php if($insert ==1) { ?>
            <button type="button" class="btn float-end"  id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Customers
            </button>
          <?php } ?>
        </div>
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-blue">
            <tr class="text-center" >
              <th>S#</th>
              <th>Customer Name</th>
              <th>Customer Phone</th>
              <th>Customer Whatsapp</th>
              <th>Customer Address</th>
              <th>Customer Email</th>
              <th>Customer Password</th>
              <th>Customer Status</th>
              <th></th>
              <?php if($update == 1 || $delete == 1) { ?>
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
      <div class="modal-header bg-blue" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Customer</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_name" id="customer_name" placeholder="Customer Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-phone"></i> Customer Phone:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_phone" id="customer_phone" placeholder="Customer Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-whatsapp"></i> Customer Whatsapp:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_whatsapp" id="customer_whatsapp" placeholder="Customer Whatsapp">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-address-card"></i> Customer Address:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_address" id="customer_address" placeholder="Customer Address">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-envelope"></i> Customer Email:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_email" id="customer_email" placeholder="Customer Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Password:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_password" id="customer_password" placeholder="Customer Password">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Status:</label>
            <div class="col-sm-7">
              <select class="form-control" id="customer_status" name="customer_status">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Affliate Supplier:</label>
            <div class="col-sm-7">
            <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="supplier" name="supplier" spry:default="select one"></select>
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
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Customer</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="customer_id" name="customer_id" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_name" id="updcustomer_name" placeholder="Customer Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-phone"></i> Customer Phone:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_phone" id="updcustomer_phone" placeholder="Customer Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-whatsapp"></i> Customer Whatsapp:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_whatsapp" id="updcustomer_whatsapp" placeholder="Customer Whatsapp">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-address-card"></i> Customer Address:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_address" id="updcustomer_address" placeholder="Customer Address">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-envelope"></i> Customer Email:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_email" id="updcustomer_email" placeholder="Customer Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Password:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_password" id="updcustomer_password" placeholder="Customer Password">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Status:</label>
            <div class="col-sm-7">
              <select class="form-control" id="updcustomer_status" name="updcustomer_status">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i>Affliate Supplier:</label>
            <div class="col-sm-7">
            <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="updSupplier" name="updSupplier" spry:default="select one"></select>
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
   
    $("#supplier").select2({
       dropdownParent: $("#exampleModal")
    });
    $("#updSupplier").select2({
       dropdownParent: $("#updexampleModal")
    });
    
    getCustomersReport();
    getSupplier('byAdd',null);
   
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this customer',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "manageCustomerController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getCustomersReport();
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
          url: "manageCustomerController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#customer_id').val(id);
            $('#updcustomer_name').val(dataRpt[0].customer_name);
            $('#updcustomer_phone').val(dataRpt[0].customer_phone);
            $('#updcustomer_whatsapp').val(dataRpt[0].customer_whatsapp);
            $('#updcustomer_address').val(dataRpt[0].customer_address);
            $('#updcustomer_email').val(dataRpt[0].customer_email);
            $('#updcustomer_status').val(dataRpt[0].status);
            getSupplier('byUpdate', dataRpt[0].affliate_supp_id);
            $('#updexampleModal').modal('show');
        },
  });
}
function getCustomersReport(){
      var getCustomersReport = "getCustomersReport";
      $.ajax({
          type: "POST",
          url: "manageCustomerController.php",  
          data: {
            GetCustomersReport:getCustomersReport
          },
          success: function (response) {  
            console.log(response);
            var CustomerRpt = JSON.parse(response);
            
            
            $('#StaffReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<CustomerRpt.length; i++){
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+ CustomerRpt[i].customer_name
              +"</td><td class='text-center'>"+CustomerRpt[i].customer_phone + "</td><td class='text-center'>"+CustomerRpt[i].customer_whatsapp + 
              "</td><td class='text-capitalize text-center'>"+CustomerRpt[i].customer_address + "</td><td class='text-center'>"+
              CustomerRpt[i].customer_email + "</td><td class='text-center'>"+CustomerRpt[i].cust_password+"</td><td class='text-center'>"+CustomerRpt[i].status+"</td>";
              if(CustomerRpt[i].affliate_supp_id == 1){
                finalTable += "<td><i class='fas fa-medal text-info text-center' style='font-size:24px' ></i></td>" ;
              }else{
                finalTable += "<td></td>";
              }
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td style='width:180px' class='text-center'>";
              <?php } ?>
              <?php if($update == 1) { ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
              CustomerRpt[i].customer_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>  ";
              <?php } ?>
              <?php if($delete == 1) { ?>
              finalTable += "<button type='button'0 onclick='Delete("+ 
              CustomerRpt[i].customer_id +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "</td>";
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
    var customer_id = $('#customer_id');
    var customer_name = $('#customer_name');
    if(customer_name.val() == ""){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    var customer_phone = $('#customer_phone');
    var customer_whatsapp = $('#customer_whatsapp');
    var customer_address = $('#customer_address');
    var customer_email = $('#customer_email');
    var customer_password = $('#customer_password');
    var customer_status = $('#customer_status');
    var supplier = $('#supplier');
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "manageCustomerController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#CountryNameForm')[0].reset();
                    $('#supplier').val(-1).trigger('change.select2');
                    $('#exampleModal').modal('hide');
                    getCustomersReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var updcustomer_name = $('#updcustomer_name');
    if(updcustomer_name.val() == ""){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    var updcustomer_phone = $('#updcustomer_phone');
    var updcustomer_whatsapp = $('#updcustomer_whatsapp');
    var updcustomer_address = $('#updcustomer_address');
    var updcustomer_email = $('#updcustomer_email');
    var updcustomer_password = $('#updcustomer_password');
    var updcustomer_status = $('#updcustomer_status');
    var updSupplier = $('#updSupplier');
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "manageCustomerController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updexampleModal').modal('hide');
                    getCustomersReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function getSupplier(type,id){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type =="byAdd"){
              $('#supplier').empty();
              $('#supplier').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                $('#supplier').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
          }else{
            $('#updSupplier').empty();
              $('#updSupplier').append("<option value='-1'>--Supplier--</option>");
              var selected = "";
              for(var i=0; i<supplier.length; i++){
                if(supplier[i].supp_id == id){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updSupplier').append("<option "+ selected + " value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
          }
        },
    });
    }
</script>
</body>
</html>