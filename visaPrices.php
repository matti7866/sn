<?php
  include 'header.php';
?>
<title>Supplier Visa Prices</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier Visa Prices' ";
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
      <h2 class="text-danger"><b>Supplier Visa Prices <i class="fa fa-fw fa-money text-dark"></i></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Supplier:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" onchange="getVisaPrices()" name="supplier_id" id="supplier_id"></select>
          </div>
          <div class="col-md-3">
              <label for="staticEmail" class="col-form-label">Currency:</label>
              <select class="form-control js-example-basic-single" onchange="getVisaPrices()" style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
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
              <th>Net Price</th>
              <th style="display:none">CID</th>
            </tr>
          </thead>
          <tbody id="VisaPriceTbl">
                    
              </tbody>
        </table>
      </div>
      </div>
      <?php  if($insert == 1) { ?>
      <div class="row">
        <div class="col-md-2 ">
            <button type="button" onclick="addVisaPrice();" id="btnSave"  class="btn btn-dark btn-block   text-white d-none "><i class="fa fa-fw fa-save"></i> Save</button>
          </div>  
      </div>
      <?php  } ?>
     
      </div>
  </div>
</div>
</div>
</div>
<?php include 'footer.php'; ?>
<script>
$(document).ready(function(){
    getSupplier('all',0);
    getCurrencies();
    $('.js-example-basic-single').select2();
});

function getSupplier(type,suppId){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "visaPricesController.php",  
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
              $('#updsupplier_id').empty();
              $('#updsupplier_id').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                if(supplier[i].supp_id ==suppId ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updsupplier_id').append("<option "+ selected +" value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }
            
        },
    });
  }
  function getCurrencies(){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "visaPricesController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#net_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i==0){
                  selected = "selected";
                  $('#net_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#net_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
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
    var supplier_id = $('#supplier_id').select2('data');
    if(supplier_id[0].id == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
    supplier_id = supplier_id[0].id;
    var net_currency_type = $('#net_currency_type').select2('data');
    net_currency_type = net_currency_type[0].id;    
    for(var i = 1; i<= x; i++){
      var country = $('#country'+ i);
      if(country.val() == ''){
        notify('Validation Error!', 'Country Name is required', 'error');
        finalArr = [];
        return;
      }
      var netPrice = $('#netPrice'+ i);
      if(netPrice.val() == ''){
        notify('Validation Error!', 'Net Price is required', 'error');
        finalArr = [];
        return;
      }
      
      finalArr.push(country.val(),supplier_id,netPrice.val(),net_currency_type);
    }
        $.ajax({
            type: "POST",
            url: "visaPricesController.php",  
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
      var supplier_id = $('#supplier_id');
      if(supplier_id == "-1"){
        notify('Error!', "Please select supplier", 'error');
        return;
      }
      var getVisaPrices = "getVisaPrices";
      var net_currency_type = $('#net_currency_type');
      $.ajax({
          type: "POST",
          url: "visaPricesController.php",  
          data: {
              GetVisaPrices:getVisaPrices,
              Supplier_ID:supplier_id.val(),
              Net_Currency_Type:net_currency_type.val()
          },
          success: function (response) { 
            $('#btnSave').removeClass('d-none');
            var visaPriceRpt = JSON.parse(response);
            $('#VisaPriceTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<visaPriceRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ visaPriceRpt[i].country_names +"</td>"+
              "<td><input type='number' value='"+ visaPriceRpt[i].netPrice +"' class='form-control' id='netPrice" 
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
