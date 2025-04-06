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
<title>Hotel Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Hotel' ";
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
    <div  class="card" id="todaycard">
    <div class="card-header bg-light">
      <h2><b>Hotel<span style="color:#C66"> Report</span></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control" onchange="searchHotel()" style="width:100%" name="customer_id" id="customer_id"></select>
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
              <th>Passenger Name</th>
              <th>Hotel Name</th>
              <th>Supplier name</th>
              <th>CheckIn Date</th>
              <th>CheckOut Date</th>
              <th>Country Name</th>
              <th>Date Time </th>
              <th>Net Price</th>
              <th>Sale Price</th>
              <th>Reserved By</th>
              <?php if($update == 1 || $delete == 1){ ?>
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
        <input type="hidden"  class="form-control" id="hotelID" name="hotelID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer Name:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Passenger Name:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Passenger Name" class="form-control" name="passenger_name" id="passenger_name">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Hotel Name:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Hotel Name" class="form-control" name="hotel_name" id="hotel_name">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Name:</label>
          <div class="col-sm-9">
            <select class="form-control  js-example-basic-single" style="width:100%" id="supplier_id" name="supplier_id" ></select>
          </div>
        </div>
        <div class="form-group row mb-2" >
          <label for="inputPassword" class="col-sm-3 col-form-label">Checkin Date:</label>
          <div class="col-sm-9">
            <input type="text"  class="form-control" name="checkin_date"  id="checkin_date">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Checkout Date:</label>
          <div class="col-sm-9">
            <input type="text"  class="form-control" name="checkout_date"  id="checkout_date">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Net Price/Currency</label>
          <div class="col-lg-6">
            <input type="number" placeholder="Net Price" class="form-control" name="net_price" id="net_price">
          </div>
          <div class="col-lg-3 " >
            <select class="form-control js-example-basic-single"   style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Sale Price/Currency</label>
          <div class="col-lg-6">
            <input type="number" placeholder="Sale Price" class="form-control" name="sale_price" id="sale_price">
          </div>
          <div class="col-lg-3" > 
            <select class="form-control js-example-basic-single"   style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Country Name:</label>
          <div class="col-sm-9">
            <select class="form-control  js-example-basic-single" id="country_id" style="width:100%" name="country_id" spry:default="select one"></select>
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
      $('#checkin_date').dateTimePicker();
      $('#checkout_date').dateTimePicker();
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
      $('#checkin_date').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#checkout_date').val(date.getFullYear() + '-' + month + '-'+ day);

});
function searchHotel(){
  var searchHotel = "searchHotel";
  var customer_id = $('#customer_id');
  if(customer_id.val() == '-1'){
        notify('Validation Error!', 'Please Select Customer ', 'error');
        return;
  }
  $.ajax({
        type: "POST",
        url: "viewhotelController.php",  
        data: {
            SearchHotel:searchHotel,
            Customer_ID:customer_id.val(),
        },
        success: function (response) {
          var hotelRpt = JSON.parse(response);
          if(hotelRpt.length === 0){
            $('#LoanReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td><td></td><td></td><td></td>";
        
              finalTable += "<td></td>";
           
              finalTable +="</tr>";
            $('#LoanReportTbl').append(finalTable);
          }else{
            $('#LoanReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var total = 0;
            for(var i=0; i<hotelRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ hotelRpt[i].customer_name +
              "</td><td class='text-capitalize'>"+ hotelRpt[i].passenger_name +"</td>"+
              "<td class='text-capitalize'>"+ hotelRpt[i].hotel_name +"</td><td>"+ hotelRpt[i].supp_name +"</td><td>"+ 
              hotelRpt[i].checkin_date +"</td><td class='text-capitalize'>"+ hotelRpt[i].checkout_date +
              "</td><td class='text-capitalize'>"+ hotelRpt[i].country_names +"</td><td class='text-capitalize'>"+ 
              hotelRpt[i].datetime +"</td><td class='text-capitalize'>"+ hotelRpt[i].net_price + ' ' + hotelRpt[i].netCurrency + 
              "</td><td class='text-capitalize'>"+ hotelRpt[i].sale_price + ' ' + hotelRpt[i].saleCurrency +
              "</td><td class='text-capitalize'>"+ hotelRpt[i].staff_name +"</td>";
              <?php if($update == 1 || $delete ==1){ ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1){ ?>
              finalTable += "<button type='button'0 onclick='Update("+ hotelRpt[i].hotel_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              hotelRpt[i].hotel_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete ==1){ ?>
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
function Delete(hotel_id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this reservation for hotel',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewhotelController.php",  
                data: {
                  Delete:Delete,
                  Hotel_ID:hotel_id,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchHotel();
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
function Update(hotel_id){
  var GetUpdHotel = "GetUpdHotel";
  $.ajax({
          type: "POST",
          url: "viewhotelController.php",  
          data: {
            GetUpdHotel:GetUpdHotel,
            Hotel_ID:hotel_id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#hotelID').val(hotel_id);
            getCustomers('ByUpdate',dataRpt[0].customer_id);
            $('#passenger_name').val(dataRpt[0].passenger_name);
            $('#hotel_name').val(dataRpt[0].hotel_name);
            getSupplier(dataRpt[0].supplier_id);
            $('#checkin_date').val(dataRpt[0].checkin_date);
            $('#checkout_date').val(dataRpt[0].checkout_date);
            $('#net_price').val(dataRpt[0].net_price);
            $('#sale_price').val(dataRpt[0].sale_price);
            getCountries(dataRpt[0].country_id);
            getCurrencies(dataRpt[0].saleCurrencyID,dataRpt[0].netCurrencyID);
            $('#updateModel').modal('show');
        },
  });
}
  function SaveUpdate(){
     var hotelID = $('#hotelID');
     var updcustomer_id = $('#updcustomer_id').select2('data');
     if(updcustomer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
     }
     updcustomer_id = updcustomer_id[0].id;
     var hotel_name = $('#hotel_name');
     if(hotel_name.val() == ""){
        notify('Validation Error!', 'Hotel name is required', 'error');
        return;
     }
     var passenger_name = $('#passenger_name');
     if(passenger_name.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
     }
     var supplier_id = $('#supplier_id').select2('data');
     if(supplier_id[0].id == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
     }
     supplier_id = supplier_id[0].id;
     var checkin_date= $('#checkin_date');
     if(checkin_date.val() == ""){
        notify('Validation Error!', 'Check in time is required', 'error');
        return;
     }
     var checkout_date= $('#checkout_date');
     if(checkout_date.val() == ""){
        notify('Validation Error!', 'Check out time is required', 'error');
        return;
     }
     var net_price = $('#net_price');
     if(net_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
     }
     var sale_price = $('#sale_price');
     if(sale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
     }
     var country_id = $('#country_id').select2('data');
     if(country_id[0].id == "-1"){
        notify('Validation Error!', 'Country name is required', 'error');
        return;
     }
     country_id = country_id[0].id;
    var saveUpdateHotel = "saveUpdateHotel";
    var sale_currency_type = $('#sale_currency_type').select2('data');
    var net_currency_type = $('#net_currency_type').select2('data');
  $.ajax({
        type: "POST",
        url: "viewhotelController.php",  
        data: {
            SaveUpdateHotel:saveUpdateHotel,
            HotelID:hotelID.val(),
            Updcustomer_id:updcustomer_id,
            Hotel_Name:hotel_name.val(),
            Supplier_ID:supplier_id,
            Checkin_Date:checkin_date.val(),
            Checkout_Date:checkout_date.val(),
            Net_Price:net_price.val(),
            Net_Currency_Type:net_currency_type[0].id,
            Sale_Price:sale_price.val(),
            Sale_Currency_Type:sale_currency_type[0].id,
            Country_ID:country_id,
            Passenger_Name:passenger_name.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            $('#genralUpdForm')[0].reset();
            $('#updcustomer_id').val(-1).trigger('change.select2');
            $('#country_id').val(-1).trigger('change.select2');
            $('#supplier_id').val(-1).trigger('change.select2');
            searchHotel();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
          }
          
        },
      });
}
function getCountries(id){
    var select_country = "select_country";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            Select_Country:select_country,
        }, 
        success: function (response) {  
            var country = JSON.parse(response);
                $('#country_id').empty();
                $('#country_id').append("<option value='-1'>--Country--</option>");
                var selected = '';
                for(var i=0; i<country.length; i++){
                    if(country[i].country_id == id){
                      selected = 'selected';
                    }else{
                      selected = '';
                    }
                    $('#country_id').append("<option " + selected + " value='"+ country[i].country_id +"'>"+ 
                    country[i].country_names +"</option>");
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
        url: "viewhotelController.php",  
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
