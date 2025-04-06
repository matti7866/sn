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
<title>Rental Car Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Rental Car' ";
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
    <div class="card" id="todaycard">
    <div class="card-header bg-light">
      <h2><b><i class="fa fa-car" style="color: #EB3349;  /* fallback for old browsers */
color: -webkit-linear-gradient(to right, #F45C43, #EB3349);  /* Chrome 10-25, Safari 5.1-6 */
color: linear-gradient(to right, #F45C43, #EB3349); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
"></i> Rental Car<span style="color: #EB3349;  /* fallback for old browsers */
color: -webkit-linear-gradient(to right, #F45C43, #EB3349);  /* Chrome 10-25, Safari 5.1-6 */
color: linear-gradient(to right, #F45C43, #EB3349); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
"> Report</span></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control" onchange="searchCar()" style="width:100%" name="customer_id" id="customer_id"></select>
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
          <thead class="text-white" style="background: #EB3349;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #F45C43, #EB3349);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #F45C43, #EB3349); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
">
            <tr>
              <th>S#</th>
              <th>Customer Name</th>
              <th>Passenger Name</th>
              <th>Car Description</th>
              <th>Supplier name</th>
              <th>Pick Date</th>
              <th>Drop Date</th>
              <th>Date Time </th>
              <th>Net Price</th>
              <th>Sale Price</th>
              <th>Reserved By</th>
              <?php if($update == 1 || $delete ==1 ) {  ?>
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
        <input type="hidden"  class="form-control" id="carID" name="carID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer Name:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Car Description:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Hotel Name" class="form-control" name="car_desc" id="car_desc">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Passenger Name:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Passenger Name" class="form-control" name="passenger_name" id="passenger_name">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Name:</label>
          <div class="col-sm-9">
            <select class="form-control  js-example-basic-single" style="width:100%" id="supplier_id" name="supplier_id" ></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Pick Date:</label>
          <div class="col-sm-9">
            <input type="text"  class="form-control" name="pick_date"  id="pick_date">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Checkout Date:</label>
          <div class="col-sm-9">
            <input type="text"  class="form-control" name="drop_date"  id="drop_date">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Net Price/Currency</label>
          <div class="col-lg-6">
            <input type="number" placeholder="Net Price" class="form-control" name="net_price" id="net_price">
          </div>
          <div class="col-lg-3 ">
            <select class="form-control js-example-basic-single"   style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Sale Price/Currency</label>
          <div class="col-lg-6">
            <input type="number" placeholder="Sale Price" class="form-control" name="sale_price" id="sale_price">
          </div>
          <div class="col-lg-3"> 
            <select class="form-control js-example-basic-single"   style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
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
        url: "viewhotelController.php",  
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
      getCustomers('byAll','');
      $('#customer_id').select2();
      $(".js-example-basic-single").select2({
        dropdownParent: $("#updateModel")
      });
      $('#pick_date').dateTimePicker();
      $('#drop_date').dateTimePicker();
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
      $('#pick_date').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#drop_date').val(date.getFullYear() + '-' + month + '-'+ day);

});
function searchCar(){
  var searchCar = "searchCar";
  var customer_id = $('#customer_id');
  if(customer_id.val() == '-1'){
        notify('Validation Error!', 'Please Select Customer ', 'error');
        return;
  }
  $.ajax({
        type: "POST",
        url: "viewrental_carController.php",  
        data: {
            SearchCar:searchCar,
            Customer_ID:customer_id.val()
        },
        success: function (response) {
            console.log(response);
          var carRpt = JSON.parse(response);
          if(carRpt.length === 0){
            $('#LoanReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td><td></td><td></td>";
        
              finalTable += "<td></td>";
           
              finalTable +="</tr>";
            $('#LoanReportTbl').append(finalTable);
          }else{
            $('#LoanReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var total = 0;
            for(var i=0; i<carRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ carRpt[i].customer_name +
              "</td><td class='text-capitalize'>"+ carRpt[i].passenger_name +"</td>"+
              "<td class='text-capitalize'>"+ carRpt[i].car_description +"</td><td>"+ carRpt[i].supp_name +"</td><td>"+ 
              carRpt[i].pick_date +"</td><td class='text-capitalize'>"+ carRpt[i].drop_date +
              "</td><td class='text-capitalize'>"+ carRpt[i].datetime +"</td><td class='text-capitalize'>"+ 
              carRpt[i].net_price + ' ' + carRpt[i].netCurrency + "</td><td class='text-capitalize'>"+ carRpt[i].sale_price
              + ' ' + carRpt[i].saleCurrency + "</td><td class='text-capitalize'>"+ carRpt[i].staff_name +"</td>";
              <?php if($update == 1 || $delete ==1 ) {  ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1) {  ?>
              finalTable += "<button type='button'0 onclick='Update("+ carRpt[i].car_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete ==1 ) {  ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              carRpt[i].car_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color: #EB3349; color: -webkit-linear-gradient(to right, #F45C43, #EB3349); color: linear-gradient(to right, #F45C43, #EB3349);' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete ==1 ) {  ?>
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
function Delete(car_id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this reservation for car',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewrental_carController.php",  
                data: {
                  Delete:Delete,
                  Car_ID:car_id,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchCar();
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
function Update(car_id){
  var GetUpdCar = "GetUpdCar";
  $.ajax({
          type: "POST",
          url: "viewrental_carController.php",  
          data: {
            GetUpdCar:GetUpdCar,
            Car_ID:car_id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#carID').val(car_id);
            getCustomers('ByUpdate',dataRpt[0].customer_id);
            $('#passenger_name').val(dataRpt[0].passenger_name);
            $('#car_desc').val(dataRpt[0].car_description);
            getSupplier(dataRpt[0].supplier_id);
            $('#pick_date').val(dataRpt[0].pick_date);
            $('#drop_date').val(dataRpt[0].drop_date);
            $('#net_price').val(dataRpt[0].net_price);
            $('#sale_price').val(dataRpt[0].sale_price);
            getCurrencies(dataRpt[0].saleCurrencyID,dataRpt[0].netCurrencyID);
            $('#updateModel').modal('show');
        },
  });
}
  function SaveUpdate(){
     var carID = $('#carID');
     var updcustomer_id = $('#updcustomer_id').select2('data');
     if(updcustomer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
     }
     updcustomer_id = updcustomer_id[0].id;
     var car_desc = $('#car_desc');
     if(car_desc.val() == ""){
        notify('Validation Error!', 'Car description is required', 'error');
        return;
     }
     var supplier_id = $('#supplier_id').select2('data');
     if(supplier_id[0].id == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
     }
     supplier_id = supplier_id[0].id;
     var pick_date= $('#pick_date');
     if(pick_date.val() == ""){
        notify('Validation Error!', 'Pick date is required', 'error');
        return;
     }
     var drop_date= $('#drop_date');
     if(drop_date.val() == ""){
        notify('Validation Error!', 'Drop date is required', 'error');
        return;
     }
     var net_price = $('#net_price');
     if(net_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
     }
     var passenger_name = $('#passenger_name');
     if(passenger_name.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
     }
     var sale_price = $('#sale_price');
     if(sale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
     }
    var saveUpdateCar = "saveUpdateCar";
    var sale_currency_type = $('#sale_currency_type').select2('data');
    var net_currency_type = $('#net_currency_type').select2('data');
  $.ajax({
        type: "POST",
        url: "viewrental_carController.php",  
        data: {
            SaveUpdateCar:saveUpdateCar,
            CarID:carID.val(),
            Updcustomer_id:updcustomer_id,
            Car_Desc:car_desc.val(),
            Supplier_ID:supplier_id,
            Pick_Date:pick_date.val(),
            Drop_Date:drop_date.val(),
            Net_Price:net_price.val(),
            Net_Currency_Type:net_currency_type[0].id,
            Sale_Price:sale_price.val(),
            Sale_Currency_Type:sale_currency_type[0].id,
            Passenger_Name:passenger_name.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            $('#genralUpdForm')[0].reset();
            $('#updcustomer_id').val(-1).trigger('change.select2');
            $('#supplier_id').val(-1).trigger('change.select2');
            searchCar();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
          }
          
        },
      });
}
function getSupplier(id){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
                $('#supplier_id').empty();
                $('#supplier_id').append("<option value='-1'>--Supplier--</option>");
                var selected = "";
                for(var i=0; i<supplier.length; i++){
                    if(supplier[i].supp_id == id){
                      selected = 'selected';
                    }else{
                      selected = '';
                    }
                    $('#supplier_id').append("<option " + selected + " value='"+ supplier[i].supp_id +"'>"+ 
                    supplier[i].supp_name +"</option>");
                }
        },
    });
}
function getCurrencies(saleSelectedParam, netSelectedParam){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewrental_carController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
                var currencyType = JSON.parse(response);
                var saleSelected  = '';
                var netSelected = '';
                $('#sale_currency_type').empty();
                $('#net_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(saleSelectedParam == currencyType[i].currencyID){
                  saleSelected = "selected";
                  $('#sale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#sale_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                if(netSelectedParam == currencyType[i].currencyID){
                  netSelected = "selected";
                  $('#net_currency_type').append("<option " + netSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#net_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
              }
        },
    });
  }
</script>
</body>
</html>
