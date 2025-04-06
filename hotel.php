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
@media (min-width: 992px) {
    .margin-lg-20{
        margin-top: -20px;
    }
}
</style>
<title>Hotel Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Hotel' ";
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
                <h1 class="text-danger text-center"><b> <i class="fa fa-building text-dark" aria-hidden="true"></i> <i> Hotel Entry Form </i></b></h1>
            </div>
            <div class="card-body">  
            <form id="hotel">
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Customer Name:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="customer_id" name="customer_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Passenger Name</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Passenger Name" class="form-control" name="passenger_name" id="passenger_name">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Hotel Name</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Hotel Name" class="form-control" name="hotel_name" id="hotel_name">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Supplier Name:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="supplier_id" name="supplier_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Checkin Date:</label>
                    <div class="col-sm-2">
                        <input type="text"  class="form-control" name="checkin_date"  id="checkin_date">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Checkout Date:</label>
                    <div class="col-sm-2">
                        <input type="text"  class="form-control" name="checkout_date"  id="checkout_date">
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputPassword3" class="col-lg-1 col-form-label">Net Price</label>
                    <div class="col-lg-2">
                        <input type="number" placeholder="Net Price" class="form-control" name="net_price" id="net_price">
                    </div>
                    <div class="col-lg-1  margin-lg-20" >
                        <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
                        <select class="form-control js-example-basic-single"   style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
                    </div>
                </div>
                <br />
                <div class="form-group row mb-2">
                    <label for="inputPassword3" class="col-lg-1 col-form-label">Sale Price</label>
                    <div class="col-lg-2">
                        <input type="number" placeholder="Net Price" class="form-control" name="sale_price" id="sale_price">
                    </div>
                    <div class="col-lg-1  margin-lg-20" >
                        <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
                        <select class="form-control js-example-basic-single"   style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Country Name:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="country_id" name="country_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Customer Payment</label>
                    <div class="col-sm-2">
                        <input type="number" placeholder="Customer Payment" class="form-control" name="cus_payment" id="cus_payment">
                    </div>
                    <div class="col-lg-1  margin-lg-20" >
                        <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span> 
                        <select class="form-control js-example-basic-single"   style="width:100%" id="cusPayment_currency_type" name="cusPayment_currency_type" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-2">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Account:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="addaccount_id" name="addaccount_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="card-footer text-light">
                    <button type="submit"  class=" col-sm-2 float-left btn btn-danger">Insert Record</button>
                    <a style="margin-left:10px; margin-top:20px" href="viewhotel.php"><i class="fa fa-info"></i>   View Report</a>
                </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script>
    function getCustomers(type,id){
    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
        },
        success: function (response) {  
            var customer = JSON.parse(response);
            if(type == 'all'){
            $('#customer_id').empty();
            $('#customer_id').append("<option value='-1'>--Customer--</option>");
            for(var i=0; i<customer.length; i++){
              $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
              customer[i].customer_name +"</option>");
            }
            }else{
                
            }
        },
    });
}
function getCountries(type,id){
    var select_country = "select_country";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            Select_Country:select_country,
        },
        success: function (response) {  
            var country = JSON.parse(response);
            if(type == 'all'){
                $('#country_id').empty();
                $('#country_id').append("<option value='-1'>--Country--</option>");
                for(var i=0; i<country.length; i++){
                    $('#country_id').append("<option value='"+ country[i].country_id +"'>"+ 
                    country[i].country_names +"</option>");
                }
            }else{

            }
        },
    });
}
function getSupplier(type,id){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type == 'all'){
                $('#supplier_id').empty();
                $('#supplier_id').append("<option value='-1'>--Supplier--</option>");
                for(var i=0; i<supplier.length; i++){
                    $('#supplier_id').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                    supplier[i].supp_name +"</option>");
                }
            }else{

            }
        },
    });
}
$(document).on('submit', '#hotel', function(event){
    event.preventDefault();
    var customer_id = $('#customer_id').select2('data');
    if(customer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    customer_id = customer_id[0].id;
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
    var cus_payment = $('#cus_payment');
    var addaccount_id = $('#addaccount_id');
    if(cus_payment.val() > 0){
      if(addaccount_id.val() == "-1"){
        notify('Validation Error!', 'Account is required', 'error');
        return;
      } 
    }
     var sale_currency_type = $('#sale_currency_type');
     var net_currency_type = $('#net_currency_type');
     var cusPayment_currency_type = $('#cusPayment_currency_type');
      data = new FormData(this);
      data.append('Insert_Hotel','Insert_Hotel');
        $.ajax({
            type: "POST",
            url: "hotelController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#hotel')[0].reset();
                    resetDate();
                    $('#customer_id').val(-1).trigger('change.select2');
                    $('#country_id').val(-1).trigger('change.select2');
                    $('#supplier_id').val(-1).trigger('change.select2');
                    $('#addaccount_id').val(-1).trigger('change.select2');
                    $('#net_currency_type option:eq(0)').prop('selected',true);
                    $('#sale_currency_type option:eq(0)').prop('selected',true);
                    $('#cusPayment_currency_type option:eq(0)').prop('selected',true);
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
$(document).ready(function(){
        resetDate();
        getCustomers('all',0);
        getSupplier('all',0);
        getCountries('all',0);
        getAccounts('all',0);
});
$(document).ready(function() {
    $('.js-example-basic-single').select2();
    getCurrencies();
});
function resetDate(){
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
function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "hotelController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#net_currency_type').empty();
                $('#sale_currency_type').empty();
                $('#cusPayment_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i==0){
                  selected = "selected";
                  $('#net_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#sale_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#cusPayment_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#net_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#sale_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#cusPayment_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
        },
    });
    }
</script>
</body>
</html>