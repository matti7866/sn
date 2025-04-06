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
<title>Hawala Entry Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Hawala' ";
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
            <div class="card-header bg-dark">
                <h1 class="text-white text-center"><b> <i class="fa fa-exchange text-white" aria-hidden="true"></i> <i> Hawala Entry Form </i></b></h1>
            </div>
            <div class="card-body">  
            <form id="hawala">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Customer Name:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="customer_id" name="customer_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">Supplier Name:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="supplier_id" name="supplier_id" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Sender Name</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Sender Name" class="form-control" name="sender_name" id="sender_name" />
                    </div>
                </div>
                <br />
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Reciever Name</label>
                    <div class="col-sm-2">
                        <input type="text" placeholder="Reciever Name" class="form-control" name="reciver_name" id="reciver_name" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Net Price</label>
                    <div class="col-sm-2">
                        <input type="number" placeholder="Net Price" class="form-control" name="net_price" id="net_price">
                    </div>
                </div>
                <br />
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Sale Price</label>
                    <div class="col-sm-2">
                        <input type="number" placeholder="Sale Price" class="form-control" name="sale_price" id="sale_price">
                    </div>
                </div>
                <br />
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Supplier Commission</label>
                    <div class="col-sm-2">
                        <input type="number" placeholder="Supplier Commission" class="form-control" name="supplier_commission" id="supplier_commission">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-1 col-form-label">Customer Commission</label>
                    <div class="col-sm-2">
                        <input type="number" placeholder="Customer Commission" class="form-control" name="customer_commission" id="customer_commission">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">From Country:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="from_country" name="from_country" spry:default="select one"></select>
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="inputEmail3" class="col-sm-1 col-form-label">To Country:</label>
                    <div class="col-sm-2">
                        <select class="form-control  js-example-basic-single" style="width:100%" id="to_country" name="to_country" spry:default="select one"></select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit"  class=" col-sm-2 float-left btn btn-danger">Insert Record</button>
                    <a style="margin-left:10px; margin-top:20px" href="viewhawala.php"><i class="fa fa-info"></i>   View Report</a>
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
        url: "hawalaController.php",  
        data: {
            Select_Country:select_country,
        },
        success: function (response) { 
            var country = JSON.parse(response);
            if(type == 'all'){
                $('#from_country').empty();
                $('#from_country').append("<option value='-1'>--From Country--</option>");
                $('#to_country').empty();
                $('#to_country').append("<option value='-1'>--To Country--</option>");
                for(var i=0; i<country.length; i++){
                    $('#from_country').append("<option value='"+ country[i].country_id +"'>"+ 
                    country[i].country_name +"</option>");
                    $('#to_country').append("<option value='"+ country[i].country_id +"'>"+ 
                    country[i].country_name +"</option>");
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
$(document).on('submit', '#hawala', function(event){
    event.preventDefault();
    var customer_id = $('#customer_id').select2('data');
    if(customer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    customer_id = customer_id[0].id;
    var supplier_id = $('#supplier_id').select2('data');
    if(supplier_id[0].id == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
    }
    supplier_id = supplier_id[0].id;
    var sender_name = $('#sender_name');
    if(sender_name.val() == ""){
        notify('Validation Error!', 'Sender name is required', 'error');
        return;
    }
    var reciver_name = $('#reciver_name');
    if(reciver_name.val() == ""){
        notify('Validation Error!', 'Reciever name is required', 'error');
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
    var supplier_commission = $('#supplier_commission');
    if(supplier_commission.val() == ""){
        notify('Validation Error!', 'Supplier Commission is required', 'error');
        return;
    }
    var customer_commission = $('#customer_commission');
    if(customer_commission.val() == ""){
        notify('Validation Error!', 'Customer Commission is required', 'error');
        return;
    }
    var from_country = $('#from_country').select2('data');
    if(from_country[0].id == "-1"){
        notify('Validation Error!', 'From country is required', 'error');
        return;
    }
    from_country = from_country[0].id;
    var to_country = $('#to_country').select2('data');
    if(to_country[0].id == "-1"){
        notify('Validation Error!', 'To country is required', 'error');
        return;
    }
    to_country = to_country[0].id;
      data = new FormData(this);
      data.append('Insert_Hawala','Insert_Hawala');
        $.ajax({
            type: "POST",
            url: "hawalaController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#hawala')[0].reset();
                    $('#customer_id').val(-1).trigger('change.select2');
                    $('#from_country').val(-1).trigger('change.select2');
                    $('#to_country').val(-1).trigger('change.select2');
                    $('#supplier_id').val(-1).trigger('change.select2');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
$(document).ready(function(){
        $('.js-example-basic-single').select2();
        getCustomers('all',0);
        getSupplier('all',0);
        getCountries('all',0);
});
</script>
</body>
</html>