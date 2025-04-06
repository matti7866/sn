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
<title>Visa Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete, permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Visa' ";
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
    <div  class="card" id="todaycard">
    <div class="card-header bg-light">
      <h2 class="text-danger"><b>Visa Sale Report <i class="fa fa-fw fa-cc-visa text-dark"></i></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-lg-12">
        <form id="serarchForm">
        <div class="row">
        <div class="col-lg-2" style="margin-top:40px;" >
        <input class="form-check-input" type="checkbox" id="dateSearch" name="dateSearch" value="option1">
                <label class="form-check-label" for="exampleCheck1">Search By Date</label>
          </div>
        
          <div class="col-lg-3">
            <label for="staticEmail" class="col-form-label">From:</label>
            <input type="text"  class="form-control" name="fromdate"  id="fromdate">
          </div>
          <div class="col-lg-3">
            <label for="staticEmail" class="col-form-label">To:</label>
            <input type="text"  class="form-control " name="todate"  id="todate">
          </div>
          <div class="col-lg-3">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control" style="width:100%" name="customer_id" id="customer_id"></select>
          </div>
          <div class="col-lg-2">
            <label for="staticEmail" class="col-form-label">Passport#:</label>
            <input type="text"  class="form-control " name="passportNum"  id="passportNum" placeholder="Passport Number">
          </div>
          <div class="col-lg-3">
            <label for="staticEmail" class="col-form-label">Passenger Name:</label>
            <input type="text"  class="form-control " name="passengerName"  id="passengerName" placeholder="Passenger Name">
          </div>
          <div class="col-lg-3">
            <label for="staticEmail" class="col-form-label">Visa Type  & Country:</label>
            <select class="form-control js-example-basic-single" style="width:100%" name="searchVisaType" id="searchVisaType"></select>
          </div>
          <?php if($select ==1){ ?>
          <div class="col-md-3">
          <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" onclick="searchVisa()"  id="searchVisaBtn" style="width:100%" class="btn btn-dark btn-block   text-white "><i class="fa fa-fw fa-search"></i> Search</button>
          </div>
          <?php } ?>
       </div>
    </form>
        </div>
      </div>
    
    <br/>
    <form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="upload" >
          <input type="file" name="uploader" id="uploader" />
          <input type="text" name="uploadVisaID" id="uploadVisaID" />
          <button type="submit" id="submitUploadForm" >Call</button>
    </form>
    
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="bg-danger text-white">
            <tr>
              <th>S#</th>
              <th >Customer Name</th>
              <th >Passenger Name</th>
              <th>Passport#</th>
              <th>Date Of Apply</th>
              <th>Visa Type & Country</th>
              <th >Net</th>
              <th >Sale</th>
              <th >Supplier</th>
              <th >Applied By</th>
              <th>Guarantee</th>
              <th >Address</th>
              <?php if($select ==1 && $insert == 1) { ?>
              <th >Additional Charges </th>
              <?php } ?>
              <th class="text-center" >Visa</th>
              <?php if($delete == 1 || $update == 1) { ?>
              <th  >Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="VisaReportTbl">
                    
              </tbody>
        </table>
      </div> 
      </div>
      </div>
  </div>
</div>
</div>
</div>
</body>
<div class="modal fade" id="updateModel" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="updvisaID" name="updvisaID">
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
            <input type="text" class="form-control"  name="updPassengerName" id="updPassengerName" placeholder="Passenger Name">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Passport#:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control"  name="updPassportNum" id="updPassportNum" placeholder="Passport#">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Country & visa type:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updcountry_id" id="updcountry_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-lg-3 col-form-label">Sale Price:</label>
          <div class="col-lg-6">
            <input type="number" class="form-control" name="updSale" id="updSale" placeholder="Sale Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updNet" id="updNet" placeholder="Net Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
          <div class="col-sm-9">
          <select class="form-control js-example-basic-single" style="width:100%" name="updsupplier" id="updsupplier"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Guarantee:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control"  name="updguarantee" id="updguarantee" placeholder="Guarantee">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Address:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control"  name="updaddress" id="updaddress" placeholder="Address">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="btnUpdate" onclick="SaveUpdate()">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Extra Charges Modal -->
<div class="modal fade" id="extraChargesModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="ExChargeHeader">Add Exta Charges</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="vID" name="vID">
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-3 col-form-label">Charge Type:</label>
            <div class="col-sm-9">
                <select class="form-control  chargesSelect2" onchange="getAttr()" style="width:100%" name="chargeType" id="chargeType">
                    <option value="1">Visa Fine</option>
                    <option value="2">Escape Report</option>
                    <option value="3">Escape Removal</option>
                </select>
            </div>
          </div>
          <hr />
        <div id="fineSection">
          <div class="form-group row mb-2 fSection">
            <label for="inputPassword" class="col-sm-3 col-form-label">Fine Amount:</label>
            <div class="col-sm-6" >
              <input type="number" class="form-control" name="fine_amount" id="fine_amount" placeholder="Fine Amount">
            </div>
            <div class="col-sm-3 ">
                <select class="form-control chargesSelect2"   style="width:100%" id="fine_currency_type" name="fine_currency_type" spry:default="select one"></select>
            </div>
          </div>
          <hr class="fSection"/>
          <h5 class="text-graident-Lawrencium helperLine chargeSction">((Service should be charged on supplier or account!)) <i class="fa fa-arrow-down"></i></h5>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
            <div class="col-sm-9">
              <select class="form-control  chargesSelect2" style="width:100%" name="chargeSupplier" id="chargeSupplier"></select>
            </div>
          </div>
          <div class="form-group row mb-2 chargeSction">
            <label for="inputPassword" class="col-sm-3 col-form-label">Account:</label>
            <div class="col-sm-9">
              <select class="form-control  chargesSelect2" style="width:100%" name="chargeAccount" id="chargeAccount"></select>
            </div>
          </div>
          <hr class="chargeSction d-none" />
        <div class="form-group row mb-2 d-none PriceSection">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="chargeNetPrice" id="chargeNetPrice" placeholder="Net Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control chargesSelect2"   style="width:100%" id="CharNet_currency_type" name="CharNet_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2 d-none PriceSection">
            <label for="inputPassword" class="col-lg-3 col-form-label">Sale Price:</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" name="chargeSalePrice" id="chargeSalePrice" placeholder="Sale Price">
            </div>
            <div class="col-sm-3">
                <select class="form-control chargesSelect2"   style="width:100%" id="charSale_currency_type" name="charSale_currency_type" spry:default="select one"></select>
            </div>
          </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addExChargesBtn" class="btn btn-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- View Extra Charges Modal -->
<div class="modal fade" id="ViewextraChargesModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">View Exta Charges</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
          <form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="chargeUpload" >
            <input type="file" name="Chargesuploader" id="Chargesuploader" />
            <input type="text" name="uploadChargesID" id="uploadChargesID" />
            <button type="submit" id="submitChargeUploadForm" >Call</button>
          </form>
            <div class="table-responsive ">
              <table   class="table  table-striped table-hover ">
                <thead class="bg-dark text-white">
                  <tr>
                    <th>S#</th>
                    <th >Type</th>
                    <th >Net Price</th>
                    <th >Sale Price</th>
                    <th >Charged ON Supplier\Account</th>
                    <th >Date</th>
                    <th >Charged By</th>
                    <th >Receipt</th>
                    <?php if($update == 1 || $delete == 1){ ?>
                    <th >Action</th>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody id="viewChargesTable">
                   
                </tbody>
              </table>
            </div> 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
        url: "viewVisaController.php",  
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
      $('#fromdate').dateTimePicker();
      $('#todate').dateTimePicker();
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
      $('#fromdate').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#todate').val(date.getFullYear() + '-' + month + '-'+ day);
      getCustomers('byAll','');
      $('.js-example-basic-single').select2({
        dropdownParent: $("#updateModel")
      });
      
      $('.chargesSelect2').select2({
        dropdownParent: $("#extraChargesModal")
      });
      getSupplier('getSuppliers',null);
      
      getCountries('forSearch',0);
      getAccounts('forCharges', null);
      $('#customer_id').select2();
      $('#searchVisaType').select2();
      getCurrencies('forCharges',null,null );
    

});
function searchVisa(){
  var SearchVisa = "SearchVisa";
  searchTerm = '';
  var customer_id = $('#customer_id');
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  var passportNum = $('#passportNum');
  var passengerName = $('#passengerName');
  var searchVisaType = $('#searchVisaType');
  var dateSearch = $('#dateSearch');
  if(dateSearch.is(':checked') && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() != -1){
    searchTerm = "DateNdAll";
  }else if(dateSearch.is(':checked') && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() == -1){
    searchTerm  = "DateNdAllExceptCountry";
  }else if(dateSearch.is(':checked') && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "DateNdCusPass";
  }else if(dateSearch.is(':checked') && customer_id.val() != -1 && passportNum.val() == ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "DateNdCus";
  }else if(dateSearch.is(':checked') && customer_id.val() == -1 && passportNum.val() == ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "DateWise";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() != -1){
    searchTerm  = "CusNdAll";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() == -1){
    searchTerm  = "CusNdAllExceptCountry";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() != -1 && passportNum.val() != ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "CusNdPass";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() != -1 && passportNum.val() == ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "Cus";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() != -1){
    searchTerm  = "PassPassengerCountry";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() != ''  &&  passengerName.val() != ''  && searchVisaType.val() == -1){
    searchTerm  = "PassPassenger";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() != ''  &&  passengerName.val() == ''  && searchVisaType.val() != -1){
    searchTerm  = "PassCountry";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() != ''  &&  passengerName.val() == ''  && searchVisaType.val() == -1){
    searchTerm  = "Pass";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() == ''  &&  passengerName.val() != ''  && searchVisaType.val() != -1){
    searchTerm  = "PassengerCountry";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() == ''  &&  passengerName.val() != ''  && searchVisaType.val() == -1){
    searchTerm  = "Passenger";
  }else if(!(dateSearch.is(':checked')) && customer_id.val() == -1 && passportNum.val() == ''  &&  passengerName.val() == ''  && searchVisaType.val() != -1){
    searchTerm  = "Country";
  }
  if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
  }
  $('#searchVisaBtn').attr('disabled', true);
  $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            SearchVisa:SearchVisa,
            SearchTerm:searchTerm,
            Customer_ID:customer_id.val(),
            Fromdate:fromdate.val(),
            Todate:todate.val(),
            PassportNum:passportNum.val(),
            PassengerName:passengerName.val(),
            SearchVisaType:searchVisaType.val()
        },
        success: function (response) {
          var visaRpt = JSON.parse(response);
          if(visaRpt.length === 0){
            $('#VisaReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
            $('#VisaReportTbl').append(finalTable);
            $('#searchVisaBtn').attr('disabled', false);
          }else{
            $('#VisaReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var byView = "byView";
            for(var i=0; i<visaRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ visaRpt[i].customer_name +"</td>"+
              "<td class='text-capitalize'>"+ visaRpt[i].passenger_name +"</td><td>"+ visaRpt[i].PassportNum +"</td><td>"+ 
              visaRpt[i].datetime +"</td><td>"+ visaRpt[i].country_names +"</td><td>"+ visaRpt[i].net_price + ' ' +
              visaRpt[i].netCurrency + "</td><td>"+ visaRpt[i].sale + ' ' + visaRpt[i].saleCurrency +"</td><td>"+ 
              visaRpt[i].supp_name +"</td><td>"+ visaRpt[i].staff_name +"</td><td>"+ visaRpt[i].gaurantee +"</td><td>"+ 
              visaRpt[i].address +"</td>";
              <?php if($insert == 1 || $select == 1){ ?>
              finalTable +="<td style='width:130px'>";
              <?php } ?>
              <?php if($insert == 1 ){ ?>
              finalTable += "<button type='button'0 onclick='openExtraCModal(\""+ byView +"\"," + visaRpt[i].visa_id+")'" +
              "class='btn'><i class='fab fa-amazon-pay text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($select == 1 ){ ?>
              if(visaRpt[i].extraCharge !="NoExists"){
                finalTable+="<button type='button'0 onclick='viewExtraCharges("+ visaRpt[i].visa_id+")'" +
              "class='btn'><i class='fas fa-vote-yea text-info fa-2x' aria-hidden='true'></i></button>";
              }
              <?php } ?>
              <?php if($insert == 1 || $select == 1){ ?>
              finalTable +"</td>";
              <?php } ?>
              if(visaRpt[i].visaCopy == null || visaRpt[i].visaCopy == '' ){
                finalTable += "<td><button type='button' onclick='uploadFile("+ visaRpt[i].visa_id +")' class='btn'><i class='fa fa-upload text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }else{
                finalTable += "<td style='width:110px'><a href='downloadVisaDocs.php?id=" + visaRpt[i].visa_id  +"&type=1'><button type='button' class='btn'><i class='fa fa-download text-dark fa-2x' aria-hidden='true'></i>"+
                "</button></a><button type='button' onclick='deleteFile(\""+ byView +"\"," + visaRpt[i].visa_id +")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }
              <?php if($update ==1 || $delete ==1){ ?>
                finalTable += "<td style='width:120px'>";
              <?php } ?>
              <?php if($update == 1){ ?>
              finalTable += "<button type='button'0 onclick='UpdateVisa("+ visaRpt[i].visa_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='DeleteVisa(\""+ byView +"\"," + visaRpt[i].visa_id +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update ==1 || $delete ==1){ ?>
                finalTable +="</td>";
              <?php } ?>
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
          }
          $('#searchVisaBtn').attr('disabled', false);
            
        },
    });
}

function uploadFile(VisaID){
          $('#uploadVisaID').val(VisaID);
          $('#uploader').click();
}

document.getElementById("uploader").onchange = function(event) {
      $('#submitUploadForm').click();
    };
    $(document).on('submit', '#upload', function(event){
      event.preventDefault();
      var uploadVisaID = $('#uploadVisaID');
      var uploader = $('#uploader').val();
      if(uploadVisaID.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#uploader').val() != ''){
        if($('#uploader')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('Upload_VisaPhoto','Upload_VisaPhoto');
        $.ajax({
            type: "POST",
            url: "viewVisaController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    searchVisa();
                    uploadVisaID.val('');
                    $('#uploader').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
function deleteFile(type,id){
  var DeleteFile = "DeleteFile";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this file',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewVisaController.php",  
                data: {
                  DeleteFile:DeleteFile,
                  ID:id,
                  Type:type
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  if(type=="byExCharge"){
                    $('#ViewextraChargesModal').modal('hide');
                  }
                    searchVisa();
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
function DeleteVisa(type,ID){
  var message ="";
  if(type =="byView"){
    message = "Do you want to delete this visa";
  }else{
    message = "Do you want to delete this extra charge";
  }
  var DeleteVisa = "DeleteVisa";
  $.confirm({
    title: 'Delete!',
    content: message,
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewVisaController.php",  
                data: {
                  DeleteVisa:DeleteVisa,
                  ID:ID,
                  Type:type
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  if(type=="byExCharge"){
                    $('#ViewextraChargesModal').modal('hide');
                  }
                  searchVisa();
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
function UpdateVisa(visa_id){
  var GetUpdVisa = "GetUpdVisa";
  $.ajax({
          type: "POST",
          url: "viewVisaController.php",  
          data: {
            GetUpdVisa:GetUpdVisa,
            VisaID:visa_id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#updvisaID').val(visa_id);
            getCustomers('ByUpdate',dataRpt[0].customer_id);
            $('#updPassengerName').val(dataRpt[0].passenger_name);
            $('#updPassportNum').val(dataRpt[0].PassportNum);
            getCountries('forUpdate',dataRpt[0].country_id);
            $('#updSale').val(dataRpt[0].sale);
            $('#updNet').val(dataRpt[0].net_price);
            getSupplier('forUpdate',dataRpt[0].supp_id);
            $('#updguarantee').val(dataRpt[0].gaurantee);
            $('#updaddress').val(dataRpt[0].address);
            getCurrencies('forUpdate',dataRpt[0].saleCurrencyID,dataRpt[0].netCurrencyID);
            $('#updateModel').modal('show');
        },
  });
}
function getCountries(type,countryid){
    var select_country = "select_country";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            Select_Country:select_country,
            CountryID:countryid,
        },
        success: function (response) {  
            var country = JSON.parse(response);
            if(type == "forUpdate"){
              var selected = '';
              $('#updcountry_id').empty();
              $('#updcountry_id').append("<option value='-1'>--Country & visa type--</option>");
              for(var i=0; i<country.length; i++){
                if(country[i].country_id ==countryid ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updcountry_id').append("<option "+ selected +" value='"+ country[i].country_id +"'>"+ 
                country[i].country_names +"</option>");
              }
            }else{
                $('#searchVisaType').empty();
                $('#searchVisaType').append("<option value='-1'>--Country & visa type--</option>");
                for(var i=0; i<country.length; i++){
                $('#searchVisaType').append("<option value='"+ country[i].country_id +"'>"+ 
                country[i].country_names +"</option>");
              }
            }
        },
    });
}
function getSupplier(type,suppId){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            SELECT_Supplier:select_supplier,
            Type:type,
            ID:suppId
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type =="getSuppliers"){
              $('#chargeSupplier').empty();
              $('#chargeSupplier').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                $('#chargeSupplier').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }
            if(type=="forUpdate"){
              $('#updsupplier').empty();
              $('#updsupplier').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                if(supplier[i].supp_id ==suppId ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#updsupplier').append("<option "+ selected +" value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }else if(type =="forCharges"){
              var selected = "";
              $('#chargeSupplier').empty();
              $('#chargeSupplier').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                if(supplier[i].supp_id == supplier[i].selectedSupplier ){
                  selected ='selected';
                }else{
                  selected = '';
                }
                $('#chargeSupplier').append("<option "+ selected +" value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }
            
        },
    });
  }
  function SaveUpdate(){
     var updvisaID = $('#updvisaID');
     var updcustomer_id = $('#updcustomer_id');
     var updPassengerName =  $('#updPassengerName');
     var updPassportNum = $('#updPassportNum');
     var updcountry_id = $('#updcountry_id'); 
     var updSale = $('#updSale');
     var updNet = $('#updNet');
     var updsupplier = $('#updsupplier');
     var updguarantee = $('#updguarantee');
     var updaddress = $('#updaddress');
     if(updcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer is required', 'error');
        return;
     }
     if(updPassengerName.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
     }
     if(updcountry_id.val() == ""){
        notify('Validation Error!', 'Country is required', 'error');
        return;
     }
     if(updSale.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
     }
     if(updNet.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
     }
     if(updsupplier.val() == ""){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
     }
     if(updaddress.val() == ""){
        notify('Validation Error!', 'Address is required', 'error');
        return;
     }
     var sale_currency_type = $('#sale_currency_type');
     var net_currency_type = $('#net_currency_type');
    var saveUpdateVisa = "saveUpdateVisa";
    $('#btnUpdate').attr('disabled', true);
  $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            SaveUpdateVisa:saveUpdateVisa,
            UpdvisaID:updvisaID.val(),
            Updcustomer_id:updcustomer_id.val(),
            UpdPassengerName:updPassengerName.val(),
            UpdPassportNum:updPassportNum.val(),
            Updcountry_ID:updcountry_id.val(),
            UpdSale:updSale.val(),
            UpdNet:updNet.val(),
            Updsupplier:updsupplier.val(),
            Updguarantee:updguarantee.val(),
            Updaddress:updaddress.val(),
            Sale_Currency_Type:sale_currency_type.val(),
            Net_Currency_Type:net_currency_type.val(),
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            $('#btnUpdate').attr('disabled', false);
            searchVisa();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
            $('#btnUpdate').attr('disabled', false);
          }
          
        },
      });
}
function getCurrencies(type,saleCurrency, netCurrency ){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            if(type == "forUpdate"){
              var saleSelected = '';
              var netSelecte = '';
                $('#sale_currency_type').empty();
                $('#net_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                  if(saleCurrency == currencyType[i].currencyID){
                      saleSelected = 'selected';
                  }else{
                    saleSelected = '';
                  } 
                  if(netCurrency == currencyType[i].currencyID){
                      netSelecte = 'selected';
                  }else{
                      netSelecte = '';
                  } 
                  $('#net_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#sale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type=="forCharges"){
              var selected = "";
                $('#fine_currency_type').empty();
                $('#charSale_currency_type').empty();
                $('#CharNet_currency_type').empty();
                for(var i=0; i<currencyType.length; i++){ 
                  if(i == 0){
                    selected = 'selected';
                  }else{
                    selected = '';
                  } 
                  $('#fine_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#charSale_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#CharNet_currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }
            
        },
    });
    }
    function getAccounts(type,id){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
            if(type == 'forCharges'){
              $('#chargeAccount').empty();
              $('#chargeAccount').append("<option value='-1'>--Select Account--</option>");
              for(var i=0; i<account.length; i++){
                $('#chargeAccount').append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }
        },
    });
}
    
    function openExtraCModal(type,id){
      $('#vID').val(id);
      if(type =="byView"){
        $('#fine_amount').val('');
        $('#chargeSalePrice').val('');
        $('#chargeNetPrice').val('');
        $('#charSale_currency_type').val(1).trigger('change.select2');
        $('#CharNet_currency_type').val(1).trigger('change.select2');
        $('#fine_currency_type').val(1).trigger('change.select2');
        $('#chargeSupplier').val(-1).trigger('change.select2');
        $('#chargeAccount').val(-1).trigger('change.select2');
        $('#ExChargeHeader').text('Add Extra Charges');
        $('#chargeType').attr('disabled', false);
        $('#chargeType').val(1).trigger('change.select2');
        $('#addExChargesBtn').attr('onclick','addExtraCharges(1)');
        getSupplier('forCharges',id);
      }else{
        $('#ViewextraChargesModal').modal('hide');
        $('#chargeType').attr('disabled', true);
        if(type == 1){
          $('#chargeType').val(1).trigger('change.select2');
        }else if(type == 2){
          $('#chargeType').val(2).trigger('change.select2');
        }else if(type ==3){
          $('#chargeType').val(3).trigger('change.select2');
        }
        
        $('#addExChargesBtn').attr('onclick','addExtraCharges(2)');
        $('#ExChargeHeader').text('Update Extra Charges');
        editExtraCharge(id);
      }
      $('#extraChargesModal').appendTo("body").modal('show');
      
      
    }
    function getAttr(){
      var chargeType = $('#chargeType');
      if(chargeType.val() == 1){
        $('.fSection').removeClass('d-none');
        $('.PriceSection').addClass('d-none');
        $('.chargeSction').removeClass('d-none');
      }else if(chargeType.val() == 2){
        $('.fSection').addClass('d-none');
        $('.PriceSection').removeClass('d-none');
        $('.chargeSction').addClass('d-none');
      }else if(chargeType.val() == 3){
        $('.fSection').addClass('d-none');
        $('.PriceSection').removeClass('d-none');
        $('.chargeSction').removeClass('d-none');
      }
    }
    function addExtraCharges(ActionType){
      var vID = $('#vID');
     var chargeType = $('#chargeType').select2('data');
     chargeType = chargeType[0].id;
     var fine_amount =  $('#fine_amount');
     var fine_currency_type = $('#fine_currency_type').select2('data');
     fine_currency_type = fine_currency_type[0].id;
     var chargeSupplier = $('#chargeSupplier').select2('data');
     chargeSupplier = chargeSupplier[0].id;
     var chargeAccount = $('#chargeAccount').select2('data');
     chargeAccount = chargeAccount[0].id;
     var chargeSalePrice = $('#chargeSalePrice');
     var charSale_currency_type = $('#charSale_currency_type').select2('data');
     charSale_currency_type = charSale_currency_type[0].id;
     var chargeNetPrice = $('#chargeNetPrice');
     var CharNet_currency_type = $('#CharNet_currency_type').select2('data');
     CharNet_currency_type = CharNet_currency_type[0].id;
     if(vID.val() == ""){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
     }
     if(chargeType == 1){
       if(fine_amount.val() == ""){
          notify('Validation Error!', 'Fine Amount is required', 'error');
          return;
       }
       if(chargeSupplier == "-1" && chargeAccount== "-1"){
          notify('Validation Error!', 'Fine should be charged on supplier or any account', 'error');
          return;
       }
       if(chargeSupplier != "-1" && chargeAccount != "-1"){
          notify('Validation Error!', 'Fine should be charged either on supplier or any account', 'error');
          return;
       }
     }else if(chargeType == 2){
      if(chargeSalePrice.val() == ""){
          notify('Validation Error!', 'Sale price is required', 'error');
          return;
       }
       if(chargeNetPrice.val() == ""){
          notify('Validation Error!', 'Net price is required', 'error');
          return;
       }
     }else if(chargeType == 3){
      if(chargeSalePrice.val() == ""){
          notify('Validation Error!', 'Sale price is required', 'error');
          return;
       }
       if(chargeNetPrice.val() == ""){
          notify('Validation Error!', 'Net price is required', 'error');
          return;
       }
       if(chargeSupplier == "-1" && chargeAccount== "-1"){
          notify('Validation Error!', 'Fine should be charged on supplier or any account', 'error');
          return;
       }
       if(chargeSupplier != "-1" && chargeAccount != "-1"){
          notify('Validation Error!', 'Fine should be charged either on supplier or any account', 'error');
          return;
       }
     }
    var addExtraCharges = "addExtraCharges";
    $('#addExChargesBtn').attr('disabled', true);
  $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            AddExtraCharges:addExtraCharges,
            VID:vID.val(),
            ChargeType:chargeType,
            Fine_Amount:fine_amount.val(),
            Fine_Currency_Type:fine_currency_type,
            ChargeSupplier:chargeSupplier,
            ChargeAccount:chargeAccount,
            ChargeSalePrice:chargeSalePrice.val(), 
            CharSale_Currency_Type:charSale_currency_type,
            ChargeNetPrice:chargeNetPrice.val(),
            CharNet_Currency_Type:CharNet_currency_type,
            ActionType:ActionType
        },
        success: function (response) {  
          if(response == 'Success'){
            if(ActionType == 1){
              notify('Success!', "Record added Successfully", 'success');
              $('#addExChargesBtn').attr('disabled', false);
            }else{
              notify('Success!', "Record updated Successfully", 'success');
              $('#addExChargesBtn').attr('disabled', false);
            }
            $('#extraChargesModal').modal('hide');
            searchVisa();
           
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
            $('#addExChargesBtn').attr('disabled', false);
          }
          
        },
      });
    }
    function viewExtraCharges(id){
      var getExtraChargesDetails = "getExtraChargesDetails";
      $('#ViewextraChargesModal').appendTo("body").modal('show');
      $.ajax({
        type: "POST",
        url: "viewVisaController.php",  
        data: {
            GetExtraChargesDetails:getExtraChargesDetails,
            ID:id
        },
        success: function (response) {
          var visaExraChargeRpt = JSON.parse(response);
          if(visaExraChargeRpt.length === 0){
            $('#viewChargesTable').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td></td>Record Not Found<td></td><td></td><td></td><td></td><td>></tr>";
            $('#viewChargesTable').append(finalTable);
          }else{
            $('#viewChargesTable').empty();
            var j = 1;
            var finalTable = "";
            var byExCharge = "byExCharge";
            for(var i=0; i<visaExraChargeRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th>";
              if(visaExraChargeRpt[i].typeID == 1){
                finalTable+="<td><button type='button' class='btn btn-warning'>"+ visaExraChargeRpt[i].type +"</button></td>";
              }else if(visaExraChargeRpt[i].typeID == 2){
                finalTable+="<td><button type='button' class='btn btn-danger'>"+ visaExraChargeRpt[i].type +"</button></td>";
              }else if(visaExraChargeRpt[i].typeID == 3){
                finalTable+="<td><button type='button' class='btn btn-primary'>"+ visaExraChargeRpt[i].type +"</button></td>";
              }
               finalTable+="<td>"+ numeral(visaExraChargeRpt[i].net_price).format('0,0') + ' ' + 
               visaExraChargeRpt[i].netCur +"</td><td>"+ numeral(visaExraChargeRpt[i].salePrice).format('0,0') + ' ' + 
               visaExraChargeRpt[i].saleCur+"</td>";
              if(visaExraChargeRpt[i].chargeFlag == 1){
                finalTable+= "<td><button type='button' class='btn btn-info'>"+ visaExraChargeRpt[i].chargedEntity +"</button></td>";
              }else{
                finalTable+= "<td><button type='button' class='btn btn-danger'>"+ visaExraChargeRpt[i].chargedEntity +"</button></td>";
              }
              finalTable+= "<td>"+ visaExraChargeRpt[i].date +"</td><td>"+ visaExraChargeRpt[i].staff_name + "</td>";
              if(visaExraChargeRpt[i].docName == null || visaExraChargeRpt[i].docName == '' ){
                finalTable += "<td><button type='button' onclick='uploadExraFile("+ visaExraChargeRpt[i].visaExtraChargesID +")' class='btn'><i class='fa fa-upload text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }else{
                finalTable += "<td style='width:110px'><a href='downloadVisaDocs.php?id=" + visaExraChargeRpt[i].visaExtraChargesID +"&type=2'><button type='button' class='btn'><i class='fa fa-download text-dark fa-2x' aria-hidden='true'></i>"+
                "</button></a><button type='button' onclick='deleteFile(\""+ byExCharge +"\"," + visaExraChargeRpt[i].visaExtraChargesID +")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }
              <?php if($update ==1 || $delete ==1){ ?>
                finalTable += "<td style='width:120px'>";
              <?php } ?>
              <?php if($update == 1){ ?>
              finalTable += "<button type='button'0 onclick='openExtraCModal("+ visaExraChargeRpt[i].typeID + "," + visaExraChargeRpt[i].visaExtraChargesID+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='DeleteVisa(\""+ byExCharge +"\"," + visaExraChargeRpt[i].visaExtraChargesID +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update ==1 || $delete ==1){ ?>
                finalTable +="</td>";
              <?php } ?>
              
              
              finalTable += "</tr>";
              $('#viewChargesTable').append(finalTable);
              j +=1;
            }
          }
            
            
        },
    });

    }
    function uploadExraFile(id){
      $('#uploadChargesID').val(id);
      $('#Chargesuploader').click();
    }
    document.getElementById("Chargesuploader").onchange = function(event) {
      $('#submitChargeUploadForm').click();
    };
    $(document).on('submit', '#chargeUpload', function(event){
      event.preventDefault();
      var uploadChargesID = $('#uploadChargesID');
      var Chargesuploader = $('#Chargesuploader').val();
      if(uploadChargesID.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#Chargesuploader').val() != ''){
        if($('#Chargesuploader')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('Upload_ExraChargeDoc','Upload_ExraChargeDoc');
        $.ajax({
            type: "POST",
            url: "viewVisaController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    uploadChargesID.val('');
                    $('#Chargesuploader').val('');
                    $('#ViewextraChargesModal').modal('hide');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function editExtraCharge(id){
      var chargeType = $('#chargeType').val();
      var editExtraCharges = "editExtraCharges";
        $.ajax({
                type: "POST",
                url: "viewVisaController.php",  
                data: {
                  EditExtraCharges:editExtraCharges,
                  ID:id,
                  ChargeType:chargeType
              },
              success: function (response) {  
                  var dataRpt = JSON.parse(response);
                  if(chargeType == 1){
                    $('#fine_amount').val(dataRpt[0].net_price);
                    $('#fine_currency_type').val(dataRpt[0].netCurrencyID).trigger('change.select2');
                    if(dataRpt[0].supplierID !=null){
                      $('#chargeSupplier').val(dataRpt[0].supplierID).trigger('change.select2');
                    }else{
                      $('#chargeSupplier').val(-1).trigger('change.select2');
                    }
                    if(dataRpt[0].accountID !=null){
                      $('#chargeAccount').val(dataRpt[0].accountID).trigger('change.select2');
                    }else{
                      $('#chargeAccount').val(-1).trigger('change.select2');
                    }
                  }else if(chargeType == 2){
                    $('#chargeSalePrice').val(dataRpt[0].salePrice);
                    $('#chargeNetPrice').val(dataRpt[0].net_price);
                    $('#charSale_currency_type').val(dataRpt[0].saleCurrencyID).trigger('change.select2');
                    $('#CharNet_currency_type').val(dataRpt[0].netCurrencyID).trigger('change.select2');
                  }else if(chargeType == 3){
                    $('#chargeSalePrice').val(dataRpt[0].salePrice);
                    $('#chargeNetPrice').val(dataRpt[0].net_price);
                    $('#charSale_currency_type').val(dataRpt[0].saleCurrencyID).trigger('change.select2');
                    $('#CharNet_currency_type').val(dataRpt[0].netCurrencyID).trigger('change.select2');
                    if(dataRpt[0].supplierID !=null){
                      $('#chargeSupplier').val(dataRpt[0].supplierID).trigger('change.select2');
                    }else{
                      $('#chargeSupplier').val(-1).trigger('change.select2');
                    }
                    if(dataRpt[0].accountID !=null){
                      $('#chargeAccount').val(dataRpt[0].accountID).trigger('change.select2');
                    }else{
                      $('#chargeAccount').val(-1).trigger('change.select2');
                    }
                  }
                  
              },
        });
    }
    

</script>
</html>
