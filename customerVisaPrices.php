<?php
  include 'header.php';
?>
<title>Customer Visa Prices</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Visa Prices' ";
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
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
    <div class="card-header bg-light">
      <h2 class="text-danger"><b>Customer Visa Prices <i class="fa fa-fw fa-money text-dark"></i></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control  js-example-basic-single" onchange="getVisaPrices()" style="width:100%" name="customer_id" id="customer_id"></select>
          </div>
          <div class="col-md-3">
              <label for="staticEmail" class="col-form-label">Currency:</label>
              <select class="form-control js-example-basic-single" onchange="getVisaPrices()" style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
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
          <thead class="bg-danger text-white">
            <tr>
              <th>S#</th>
              <th>Country & Visa Type</th>
              <th>Sale Price</th>
              <th style="display:none">CID</th>
            </tr>
          </thead>
          <tbody id="VisaPriceTbl">
                    
              </tbody>
        </table>
      </div>
      <?php if($insert == 1) {   ?>
      <div class="row">
        <div class="col-md-2">
            <button type="button" onclick="addVisaPrice();" style="width:100%" id="btnSave"  class="btn btn-dark btn-block  d-none text-white "><i class="fa fa-fw fa-save"></i> Save</button>
          </div>  
      </div>
      <?php  } ?>
      </div>
      </div>
  </div>
</div>
</div>
</div>
<?php include 'footer.php'; ?>
<script>
$(document).ready(function(){
    getCountries();
    getCurrencies();
    $('.js-example-basic-single').select2();
});
function getCountries(){
    var select_customer = "select_customer";
    $.ajax({
        type: "POST",
        url: "customerVisaPricesController.php",  
        data: {
          Select_Customer:select_customer,
        },
        success: function (response) {  
            var customer = JSON.parse(response);
                $('#customer_id').empty();
                $('#customer_id').append("<option value='-1'>--Customer--</option>");
                for(var i=0; i<customer.length; i++){
                    $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
                    customer[i].customer_name +"</option>");
                } 
        },
    });
}
function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "customerVisaPricesController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#sale_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i==0){
                  selected = "selected";
                  $('#sale_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#sale_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
        },
    });
    }

  function addVisaPrice(){
    var insert ="INSERT";
    var x = document.getElementById("myTable").rows.length -1;
    var finalArr = [];
    var customer_id = $('#customer_id').select2('data');
    if(customer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer is required', 'error');
        return;
    }
    customer_id = customer_id[0].id;
    var sale_currency_type = $('#sale_currency_type').select2('data');
    sale_currency_type = sale_currency_type[0].id;   
    for(var i = 1; i<= x; i++){
      var country = $('#country'+ i);
      if(country.val() == ''){
        notify('Validation Error!', 'Country Name is required', 'error');
        finalArr = [];
        return;
      }
      var salePrice = $('#salePrice'+ i);
      if(salePrice.val() == ''){
        notify('Validation Error!', 'Sale Price is required', 'error');
        finalArr = [];
        return;
      }
      finalArr.push(country.val(),customer_id,salePrice.val(),sale_currency_type);
    }
        $.ajax({
            type: "POST",
            url: "customerVisaPricesController.php",  
            data: {
                INSERT:insert,
                finalArr:finalArr
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    location.reload(true);
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }  
    function getVisaPrices(){
      var customer_id = $('#customer_id');
      if(customer_id == "-1"){
        notify('Error!', "Please select customer", 'error');
        return;
      }
      var getVisaPrices = "getVisaPrices";
      var sale_currency_type = $('#sale_currency_type');
      $.ajax({
          type: "POST",
          url: "customerVisaPricesController.php",  
          data: {
              GetVisaPrices:getVisaPrices,
              Customer_ID:customer_id.val(),
              Sale_Currency_Type:sale_currency_type.val()
          },
          success: function (response) { 
            $('#btnSave').removeClass('d-none');
            var visaPriceRpt = JSON.parse(response);
            $('#VisaPriceTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<visaPriceRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ visaPriceRpt[i].country_names +"</td>"+
              "<td><input type='number' value='"+ visaPriceRpt[i].salePrice +"' class='form-control' id='salePrice" 
              +j+ "' /></td><td style='display:none'><input type='hidden' id='country"+j+"' value='"+visaPriceRpt[i].country_id +"' /></td>";
              finalTable += "</tr>";
              $('#VisaPriceTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
</script>
</body>
</html>
