<?php
  include 'header.php';
?>
<link rel="stylesheet" href="Libraries/dropzone.min.css" type="text/css" />
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
    .text-graident-Lawrencium {
        color: #EB5757;  
        color: -webkit-linear-gradient(to right, #000000, #EB5757); 
        color: linear-gradient(to right, #000000, #EB5757); 
    }
    .bg-graident-Lawrencium {
      background: #EB5757;  
      background: -webkit-linear-gradient(to right, #000000, #EB5757); 
      background: linear-gradient(to right, #000000, #EB5757); 
    }
  .bg-gradient-littleLeaf {
    background: #005C97;  
    background: -webkit-linear-gradient(to bottom, #363795, #005C97); 
    background: linear-gradient(to bottom, #363795, #005C97);
  }
  .text-gradient-littleLeaf{
    color: #005C97;  
    color: -webkit-linear-gradient(to bottom, #363795, #005C97);  
    color: linear-gradient(to bottom, #363795, #005C97); 
  }
  #customBtn{ color:#33001b;border-color:#33001b; }
  #customBtn:hover{color:  #FFFFFF;background-color:#33001b;border-color:#33001b}
</style>
<title>Service</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Service' ";
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

<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div  class="card" id="todaycard">
      <div class="card-header bg-graident-Lawrencium text-white" >
        <h2  class="text-graident-lightcrimson" ><b><i class="fab fa-servicestack"></i> <i>Services</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Service Type:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" name="searchServiceType"  id="searchServiceType"></select>
          </div>
          <div class="col-md-2">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control  js-example-basic-single" style="width:100%" name="customer_id"  id="customer_id"></select>
          </div>
          <div class="col-lg-3">
                    <label for="staticEmail" class="col-form-label">Passenger Name:</label>
                    <input type="text" class="form-group form-control" name="searchPassenger_name" id="searchPassenger_name" placeholder="Enter passenger name"  />
            </div>
          <div class="col-md-2">
            <label for="staticEmail" class="col-form-label">Action:</label>
            <button type="button" style="width:100%" class="btn btn-block" id="customBtn" onclick="getServiceReport()">
             <i class="fa fa-search"> </i> Search 
            </button>
          </div>
          <div class="col-md-2" style="margin-top:35px">
          
            
             <?php if($insert == 1 ) { ?>
            <button type="button" class="btn btn-block float-end" style="width:100%" id="customBtn" data-bs-toggle="modal" data-bs-target="#addServiceDetailModal">
             <i class="fa fa-plus"> </i> Add Service
            </button>
             <?php } ?>
             <div>
          </div>
        </div>
      
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  text-dark table-striped table-hover ">
          <thead class="text-white bg-graident-Lawrencium">
            <tr >
              <th>S#</th>
              <th >Service Type</th>
              <th >Customer Name</th>
              <th >Passenger Name</th>
              <th >Service Date</th>
              <th >Service Detail</th>
              <th >Sale Price</th>
              <th >Charged ON Supplier/ Account</th>
              <th >Provider</th>
              <th>Service Document</th>
              <?php if($update == 1 && $delete == 1) { ?>
              <th>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="serviceReportTbl">
                    
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
</body>

<!-- Insert Modal -->

<div class="modal fade bd-example-modal-lg" id="addServiceDetailModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable"  role="document">
    <div class="modal-content">
      <div class="modal-header bg-graident-Lawrencium" >
        <h5 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Service</i></b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="addServiceForm"> 
          <div class="row">
            <input type="hidden" id="serviceDID">
            <div class="col-lg-4">
                <span class="form-label col-form-label text-red"><i class="fab fa-servicestack"></i>Service Type  :</span>
                <select class="form-control   addSelect" style="width:100%" name="addServiceType"  id="addServiceType"></select>
              </div>
            <div class="col-lg-4">
                <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Customer Name:</span>
                <select class="form-control   addSelect" style="width:100%" name="addcustomer_id" id="addcustomer_id"></select>
            </div>
            <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Passenger Name:</span>
                    <input type="text" class="form-group form-control" name="addPassenger_name" id="addPassenger_name" placeholder="Enter passenger name"  />
            </div>
          </div>
          <hr class="text-graident-Lawrencium" />
             <h5 class="text-graident-Lawrencium helperLine">((Service should be charged on supplier or account!)) <i class="fa fa-arrow-down"></i></h5>
            <div class="row">
                <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-address-card"></i>  Supplier:</span>
                    <select name="supplier" id="supplier" style="width:100%"  class="form-control addSelect"></select>
                </div>
                <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-paypal"></i>  Account:</span>
                    <select class="form-control  addSelect" style="width:100%"  name="charge_account_id" id="charge_account_id"></select>
                </div>
            </div>
            <hr class="text-graident-Lawrencium" />
            <div class="row">
              <div class="col-lg-8">
                <span class="form-label col-form-label text-red"><i class="fa fa-arrow-down"></i>  Service Detail:</span>
                <textarea class="form-control" id="serviceDetail" name="serviceDetail" rows="3" placeholder="Service detail is must"></textarea>
              </div>
            </div>
            <hr class="text-graident-Lawrencium" />
          <div class="row">
          <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Net price:</span>
                    <input type="number" class="form-group form-control" name="net_amount" id="net_amount" placeholder="Enter Net Amount"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class="form-control addSelect"   style="width:100%" id="net_currency_type" name="net_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Sale price:</span>
                    <input type="number" class="form-group form-control" name="sale_amount" id="sale_amount" placeholder="Enter Sale Amount"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class=" form-control addSelect"    style="width:100%" id="sale_currency_type" name="sale_currency_type" spry:default="select one"></select>
                </div>
                
          </div>
          <hr class="text-graident-Lawrencium" />
          <div class="row">
            <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Customer Payment:</span>
                    <input type="number" class="form-group form-control" name="cust_payment" id="cust_payment" placeholder="Enter Customer Payment"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-inverse"><i class="fa fa-dollar"></i> Currency:</span>
                    <select class="col-sm-3 form-control addSelect"   style="width:100%" id="payment_currency_type" name="payment_currency_type" spry:default="select one"></select> 
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-paypal"></i>  Account:</span>
                    <select class="form-group form-control  addSelect" style="width:100%"  name="addaccount_id" id="addaccount_id"></select>
                </div>
          </div>
          <hr class="text-graident-Lawrencium" />
            <div class="row" >
                <div class="col-lg-12">
                  
                <div  class="dropzone" id="service-documents"></div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" id="addServiceBtn"  class="btn text-white bg-graident-Lawrencium">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--  Uploading File Modal -->
<div class="modal fade" id="uploadServiceFilesModel" tabindex="-1" aria-labelledby="UFLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-graident-Lawrencium  text-white">
        <h5 class="modal-title" id="UFLabel">Upload Files</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <input type="hidden" id="updServiceDID" />
          <div class="row" >
                <div class="col-lg-12">
                  <div  class="dropzone" id="upd-service-documents"></div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="uploadSFiles()" class="btn bg-graident-Lawrencium  text-white">Save</button>
      </div>
    </div>
  </div>
</div>


<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-graident-Lawrencium  text-white">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Add Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <div id="ticketSection">
        <div class="form-group row mb-3">
          <label for="inputPassword" class="col-sm-3 col-form-label">Service name:</label>
          <div class="col-sm-9">
               <input type="text" class="form-control" autofocus= "autofocus" id="serviceName" name="serviceName" placeholder="Type service name here" />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn bg-graident-Lawrencium  text-white"  onclick="addServiceFun()">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!--  view uploaded Files Modal -->
<div class="modal fade" id="viewUploadedFilesModal" tabindex="-1" aria-labelledby="VFLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-graident-Lawrencium  text-white">
        <h5 class="modal-title" id="VFLabel"><i class="fa fa-eye"></i> View Uploaded Files </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="table-responsive ">
        <table id="myTable"  class="table  text-dark table-striped table-hover ">
          <thead class="text-white bg-graident-Lawrencium">
            <tr >
              <th>S#</th>
              <th >Document</th>
              <?php if($delete == 1){ ?>
              <th >Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody id="viewFilesTbl">
                    
              </tbody>
        </table>
         </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade bd-example-modal-lg" id="updServiceDetailModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable"  role="document">
    <div class="modal-content">
      <div class="modal-header bg-graident-Lawrencium" >
        <h5 class="modal-title text-white" id="exampleModalLabel"><b><i>Update Service</i></b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="updServiceForm"> 
          <div class="row">
            <input type="hidden" name="updateserviceDID" id="updateserviceDID">
            <div class="col-lg-4">
                <span class="form-label col-form-label text-red"><i class="fab fa-servicestack"></i>Service Type  :</span>
                <select class="form-control updSelect" style="width:100%" name="updServiceType"  id="updServiceType"></select>
              </div>
            <div class="col-lg-4">
                <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Customer Name:</span>
                <select class="form-control updSelect" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
            </div>
            <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-user"></i>  Passenger Name:</span>
                    <input type="text" class="form-group form-control" name="updPassenger_name" id="updPassenger_name" placeholder="Enter passenger name"  />
            </div>
          </div>
          <hr class="text-graident-Lawrencium" />
             <h5 class="text-graident-Lawrencium helperLine">((Service should be charged on supplier or account!)) <i class="fa fa-arrow-down"></i></h5>
            <div class="row">
                <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-address-card"></i>  Supplier:</span>
                    <select name="updsupplier" id="updsupplier" style="width:100%"  class="form-control updSelect"></select>
                </div>
                <div class="col-lg-4">
                    <span class="form-label col-form-label text-red"><i class="fa fa-paypal"></i>  Account:</span>
                    <select class="form-control updSelect " style="width:100%"  name="updcharge_account_id" id="updcharge_account_id"></select>
                </div>
            </div>
            <hr class="text-graident-Lawrencium" />
            <div class="row">
              <div class="col-lg-8">
                <span class="form-label col-form-label text-red"><i class="fa fa-arrow-down"></i>  Service Detail:</span>
                <textarea class="form-control" id="updserviceDetail" name="updserviceDetail" rows="3" placeholder="Service detail is must"></textarea>
              </div>
            </div>
            <hr class="text-graident-Lawrencium" />
          <div class="row">
          <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Net price:</span>
                    <input type="number" class="form-group form-control" name="updnet_amount" id="updnet_amount" placeholder="Enter Net Amount"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class="form-control updSelect"   style="width:100%" id="updnet_currency_type" name="updnet_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i>  Sale price:</span>
                    <input type="number" class="form-group form-control" name="updsale_amount" id="updsale_amount" placeholder="Enter Sale Amount"  />
                </div>
                <div class="col-lg-3">
                    <span class="form-label col-form-label text-red"><i class="fa fa-dollar"></i> Currency:</span> 
                    <select class=" form-control updSelect"    style="width:100%" id="updsale_currency_type" name="updsale_currency_type" spry:default="select one"></select>
                </div>
                
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" id="updServiceBtn" class="btn text-white bg-graident-Lawrencium">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>




<?php include 'footer.php'; ?>
<script src="dropzone.min.js"></script>
<script src="Numeral-js-master/numeral.js"></script>
<script>
  $(document).ready(function(){
    getServices('search',0);
    getCustomer('all',0);
    getServices('add',0);
    getAccounts('all',0);
    getSupplier('add',0);
    getCurrencies('all',0);
    $(".addSelect").select2({
      dropdownParent: $("#addServiceDetailModal")
    });
    $(".updSelect").select2({
      dropdownParent: $("#updServiceDetailModal")
    });
    $('.js-example-basic-single').select2();
  });
  function getServiceReport(){
      searchTerm = '';
      var customer_id = $('#customer_id');
      var searchServiceType = $('#searchServiceType');
      var searchPassenger_name = $('#searchPassenger_name');
      if(customer_id.val() !='-1' && searchServiceType.val() !="-1" && searchPassenger_name.val() !=''){
        searchTerm = "cusAndServicePassenger";
      }
      if(customer_id.val() !='-1' && searchServiceType.val() !="-1" && searchPassenger_name.val() =='' ){
        searchTerm = "cusAndService";
      }else if(customer_id.val() !='-1' && searchServiceType.val() =="-1" && searchPassenger_name.val() ==''){
        searchTerm = "cus";
      }else if(customer_id.val() =='-1' && searchServiceType.val() !="-1" && searchPassenger_name.val() ==''){
        searchTerm = "service";
      }else if(customer_id.val() =='-1' && searchServiceType.val() !="-1" && searchPassenger_name.val() !=''){
        searchTerm = "servicePassenger";
      }else if(customer_id.val() !='-1' && searchServiceType.val() =="-1" && searchPassenger_name.val() !=''){
        searchTerm = "customerPassenger";
      }else if(customer_id.val() =='-1' && searchServiceType.val() =="-1" && searchPassenger_name.val() ==''){
        notify('Error!', "Please select any option for search"  , 'error');
        return;
      }
      var getServiceReport = "getServiceReport";
      $.ajax({
          type: "POST",
          url: "serviceController.php",  
          data: {
            GetServiceReport:getServiceReport,
            CustomerID:customer_id.val(),
            SearchPassenger_name:searchPassenger_name.val(),
            SearchTerm:searchTerm,
            SearchServiceType:searchServiceType.val(),
          },
          success: function (response) {  
            var serviceTypeRpt = JSON.parse(response);
            if(serviceTypeRpt.length == 0){
              $('#serviceReportTbl').empty();
              var finalTable = "<tr><td></td><td></td><td></td><td></td><td></td><td>Record Not Found </td><td></td><td></td><td></td>";
              <?php if($update == 1 && $delete == 1) { ?>
              finalTable +="<td></td>";
              <?php } ?>
              finalTable +="</tr>";
              $('#serviceReportTbl').append(finalTable);
            }
            else{
              $('#serviceReportTbl').empty();
              var j = 1;
              var finalTable = "";
              var chargeVar = '';
              var byRecord = "byRecord";
              for(var i=0; i<serviceTypeRpt.length; i++){
                if(serviceTypeRpt[i].chargeFlag == "bySupplier"){
                
                  chargeVar = "bg-gradient-littleLeaf";
                }else{
                  
                  chargeVar = "bg-graident-Lawrencium";
                }
                finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ serviceTypeRpt[i].serviceName +"</td>"+
                "<td class='text-capitalize'>"+ serviceTypeRpt[i].customer_name +"</td><td class='text-capitalize'>"+ serviceTypeRpt[i].passenger_name +"</td><td class='text-capitalize'>"+ 
                serviceTypeRpt[i].service_date +"</td><td class='text-capitalize'>"+ 
                serviceTypeRpt[i].service_details +"</td><td class='text-capitalize'>"+ 
                numeral(serviceTypeRpt[i].salePrice).format('0,0') + ' ' + serviceTypeRpt[i].currencyName +"</td><td><button style='width:100%' type='button' class='text-center btn-rounded btn " + chargeVar  + " btn-block text-white'>"+ 
                serviceTypeRpt[i].ChargedEntity +"</button></td><td>"+ serviceTypeRpt[i].staff_name +"</td>";

                finalTable += "<td><button type='button' onclick='uploadServiceFiles("+ serviceTypeRpt[i].serviceDetailsID+")'" +
                " class='btn'><i class='fa fa-upload text-graident-Lawrencium fa-2x' aria-hidden='true'></i></button> | "+ 
                "<button type='button' onclick='viewServiceFiles("+ serviceTypeRpt[i].serviceDetailsID+")'" +
                " class='btn'><i class='fa fa-eye  text-gradient-littleLeaf fa-2x' aria-hidden='true'></i></button></td>";
                <?php if($delete == 1  || $update == 1) { ?>
                finalTable += "<td style='width:130px'>";
                <?php } ?>
                <?php if($update == 1) { ?>
                finalTable += "<button type='button' onclick='edit("+ serviceTypeRpt[i].serviceDetailsID+")'" +
                "class='btn'><i class='fa fa-edit text-primary fa-2x' aria-hidden='true'></i></button>";
                <?php } ?>
                <?php if($delete == 1) { ?>
                  finalTable +="<button type='button' onclick='DeleteFile(\"" + byRecord  +"\"," + serviceTypeRpt[i].serviceDetailsID +")'" +
                "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
                <?php } ?>
                <?php if($delete == 1  || $update == 1) { ?>
                finalTable +="</td>";
                <?php } ?>
                finalTable += "</tr>";
                
                $('#serviceReportTbl').append(finalTable);
              
                j +=1;
              }
            }
          },
      });
  }
    function getCustomer(type,id){
    var select_customer = "select_customer";
    $.ajax({
        type: "POST",
        url: "serviceController.php",  
        data: {
            Select_Customer:select_customer,
        },
        success: function (response) {  
            var customer = JSON.parse(response);
            var customer_id = $('#customer_id');
            var addcustomer_id = $('#addcustomer_id');
            if(type == 'all'){
              customer_id.empty();
              customer_id.append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                customer_id.append("<option value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
              addcustomer_id.empty();
              addcustomer_id.append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                addcustomer_id.append("<option value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }else{
              $('#updcustomer_id').empty();
              $('#updcustomer_id').append("<option value='-1'>--Customer--</option>");              
              var selected = '';
               for(var i=0; i<customer.length; i++){
                if(id == customer[i].customer_id){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#updcustomer_id').append("<option "+ selected + "  value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }
        },
    });
    }
var m = 'undefined';
Dropzone.autoDiscover = false;
$('#addServiceDetailModal').on('shown.bs.modal', function (e) {
  if(m !='undefined'){
    m.destroy();
  }
  // Initialize Dropzone
  var myDropzone = new  Dropzone("#service-documents", {
    paramName: "file",
    url:"uploadFiles.php",
    addRemoveLinks: true,
    dictDefaultMessage: 'Drag & Drop file here. Remember at a time you can not upload more than 10 files',
    autoProcessQueue: false,
    maxFilesize: 10,
    acceptedFiles:'image/jpeg,image/png,image/gif,image/jpg,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.rar,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip',
    uploadMultiple: true,
    parallelUploads: 10,
    dictFileTooBig: 'File is too big, Please upload files which is less than 10 Mbs',
    dictInvalidFileType: 'You can not upload file of this type',
    dictMaxFilesExceeded: 'You can not upload anymore files',
    maxFiles: 10,
    // the setting for dropzone
    init: function(){
     m = this;
      this.on('sending', function(file, xhr,formData){
        var id =  $('#serviceDID').val();
        if(id ==''){
          notify('Error!', "Something went wrong", 'error');
          return;
        }
        formData.append('userID',id);
      });
      this.on('successmultiple', function(file,message,xhr) {
        if(message[0] == 'both'){
            var errorsFormat = '<ul>';
            var errors = message[1];
            for(var k = 0; k< errors.length; k= k+2 ){
              errorsFormat+= '<li>'+ errors[k] + ' ' + errors[k+1] +'</li>';
            }
            errorsFormat+= '</ul>'
            notify('Error Description!', errorsFormat  , 'error');
            var successFormat = '<ul>';
            var success = message[2];
            for(var j = 0; j< success.length; j++){
              successFormat+= '<li>'+ success[j] +'</li>';
            }
            successFormat+= '</ul>'
            notify('Successful uploaded files !', successFormat  , 'success');
        }else if(Array.isArray(message)){
          var successFormat = '<ul>';
          for(var i = 0; i< message.length; i++){
            successFormat+= '<li>'+ message[i] +'</li>';
          }
          successFormat+= '</ul>'
          notify('Successful uploaded files !', successFormat  , 'success');
        }else{
          notify('Successful uploaded files !', message  , 'success');
        }
      });
      
      this.on("errormultiple", function(file, message, xhr) {
        if(Array.isArray(message)){
        var errorsFormat = '<ul>';
        for(var i = 0; i< message.length; i= i+2 ){
          errorsFormat+= '<li>'+ message[i] + ' ' + message[i+1] +'</li>';
        }
        errorsFormat+= '</ul>'
        notify('Error Description!', errorsFormat  , 'error');
        }else{
          notify('Error Description!', message  , 'error');
        }
      });
      this.on('queuecomplete', function(){
          $('#serviceDID').val('');
          $('#addServiceDetailModal').modal('hide');
          $('#addServiceForm')[0].reset();
          $('#addServiceType').val(1).trigger('change.select2');
          $('#addcustomer_id').val(-1).trigger('change.select2');
          $('#addPassenger_name').val('');
          $('#supplier').val(-1).trigger('change.select2');
          $('#charge_account_id').val(-1).trigger('change.select2');
          $('#net_currency_type').val(1).trigger('change.select2');
          $('#sale_currency_type').val(1).trigger('change.select2');
          $('#payment_currency_type').val(1).trigger('change.select2');
          $('#addaccount_id').val(-1).trigger('change.select2');
          $('#addServiceBtn').attr('disabled', false);
          getServiceReport();
      });
      this.on("complete", function(file) {
         this.removeFile(file);
      });
    }
});
});
$(document).on('submit', '#addServiceForm', function(event){
    event.preventDefault();
    var addServiceType = $('#addServiceType');
    var addcustomer_id = $('#addcustomer_id');
    var addPassenger_name = $('#addPassenger_name');
    var supplier = $('#supplier');
    var charge_account_id = $('#charge_account_id');
    var serviceDetail = $('#serviceDetail');
    var net_amount = $('#net_amount');
    var net_currency_type = $('#net_currency_type');
    var sale_amount = $('#sale_amount');
    var sale_currency_type = $('#sale_currency_type');
    var cust_payment = $('#cust_payment');
    var payment_currency_type = $('#payment_currency_type');
    var addaccount_id = $('#addaccount_id');
    if(addServiceType.val() == "-1"){
        notify('Validation Error!', 'Service type is required', 'error');
        return;
    }
    if(addPassenger_name.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
    }
    if(addcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    if(supplier.val() == "-1" && charge_account_id.val() == '-1'){
        notify('Validation Error!', 'Service should be charged on Supplier or any account', 'error');
        return;
    }
    if(supplier.val() != "-1" && charge_account_id.val() != '-1'){
        notify('Validation Error!', 'Service should be charged either on supplier or any account', 'error');
        return;
    }

    if(serviceDetail.val() == "-1"){
        notify('Validation Error!', 'Service detail is required', 'error');
        return;
    }
    if(net_amount.val() == ""){
        notify('Validation Error!', 'Net amount is required', 'error');
        return;
    }
    if(sale_amount.val() == ""){
        notify('Validation Error!', 'Sale amount is required', 'error');
        return;
    }
    if(cust_payment.val() != "" && addaccount_id.val() == '-1'){
        notify('Validation Error!', 'Account type for which customer pays is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('AddService','AddService');
      $('#addServiceBtn').attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "serviceController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if($.isNumeric(response)){
                   if(m.files.length > 0){
                      $('#serviceDID').val(response);
                      notify('success!', "Record added successfully!. wait for files to be uploadd", 'success');
                      m.processQueue();
                   }else{
                    notify('success!', "Record added successfully!", 'success');
                    $('#serviceDID').val('');
                    $('#addServiceDetailModal').modal('hide');
                    $('#addServiceForm')[0].reset();
                    $('#addServiceType').val(1).trigger('change.select2');
                    $('#addcustomer_id').val(-1).trigger('change.select2');
                    $('#addPassenger_name').val('');
                    $('#supplier').val(-1).trigger('change.select2');
                    $('#charge_account_id').val(-1).trigger('change.select2');
                    $('#net_currency_type').val(1).trigger('change.select2');
                    $('#sale_currency_type').val(1).trigger('change.select2');
                    $('#payment_currency_type').val(1).trigger('change.select2');
                    $('#addaccount_id').val(-1).trigger('change.select2');
                    $('#addServiceBtn').attr('disabled', false);
                    getServiceReport();
                   }
                }else{
                    notify('Error!', "Something went wrong! Nothing recorded in database", 'error');
                    $('#addServiceBtn').attr('disabled', false);
                }
            },
        });
  });

function getAccounts(type,id){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "serviceController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
            var charge_account_id =  $('#charge_account_id');
            var addaccount_id = $('#addaccount_id');
            if(type == 'all'){
              charge_account_id.empty();
              charge_account_id.append("<option value='-1'>--Account--</option>");
              addaccount_id.empty();
              addaccount_id.append("<option value='-1'>--Account--</option>");
              for(var i=0; i<account.length; i++){
                charge_account_id.append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
                addaccount_id.append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
              
            }else{
              $('#updcharge_account_id').empty();
              $('#updcharge_account_id').append("<option value='-1'>--Account--</option>");              
              var selected = '';
               for(var i=0; i<account.length; i++){
                if(id == account[i].account_ID){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#updcharge_account_id').append("<option "+ selected + "  value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
            }
        },
    });
}
function getServices(type, selectedOp){
    var getServices = "getServices";
    $.ajax({
        type: "POST",
        url: "serviceController.php",  
        data: {
            GetServices:getServices,
        },
        success: function (response) {
            var serviceTypes = JSON.parse(response);
            var addServiceType = $('#addServiceType');
            var searchServiceType = $('#searchServiceType');
            if(type=="add"){
              $('#addServiceType').empty();
              $('#addServiceType').append("<option value='-1'> + Add Service </option>");   
            }
             if(type == "search"){
              $('#searchServiceType').empty();
              $('#searchServiceType').append("<option value='-1'> -- Select Service -- </option>");   
            }      
            if(type == "add" || type == "search"){
               var selected = '';
               for(var i=0; i<serviceTypes.length; i++){
                if(i == 0){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                if(type == "add"){
                  $('#addServiceType').append("<option "+ selected + "  value='"+ serviceTypes[i].serviceID +"'>"+ 
                  serviceTypes[i].serviceName +"</option>");
                }
                if(type =="search"){
                  selected = -1;
                  $('#searchServiceType').append("<option "+ selected + "  value='"+ serviceTypes[i].serviceID +"'>"+ 
                  serviceTypes[i].serviceName +"</option>");
                }
              }
            }
            if(type =="update"){
                $('#updServiceType').empty();
                $('#updServiceType').append("<option value='-1'> -- Select Service -- </option>");   
                for(var i=0; i<serviceTypes.length; i++){
                  if(serviceTypes[i].serviceID == selectedOp){
                    $('#updServiceType').append("<option selected value='"+ serviceTypes[i].serviceID +"'>"+ 
                    serviceTypes[i].serviceName +"</option>");
                  }else{
                    $('#updServiceType').append("<option value='"+ serviceTypes[i].serviceID +"'>"+ 
                    serviceTypes[i].serviceName +"</option>");
                  }
              }
            }
        },
    });
    }
    $('#addServiceType').on('select2:select', function (e) {
        var id = e.params.data.id;
        if(id== '-1'){
            $('#addServiceDetailModal').modal('hide');
            $('#addServiceModal').modal('show');
        }
    });
    function addServiceFun(){
       var InsertServiceName = "InsertServiceName"; 
       var serviceName =  $('#serviceName');
       if(serviceName.val() == ""){
            notify('Validation Error!', 'Service name is required', 'error');
            return;
       }
       $.ajax({
            type: "POST",
            url: "serviceController.php",  
            data: {
                InsertServiceName:InsertServiceName,
                ServiceName: serviceName.val()
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    serviceName.val('');
                    $('#addServiceModal').modal('hide');
                    $('#addServiceDetailModal').modal('show');
                    getServices('add',0);
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    $('#addServiceModal').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });
    function getSupplier(type, selectedOp){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "serviceController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            var addSupplier = $('#supplier');
            var updSupplier = $('#updsupplier');
            if(type=="add"){
              addSupplier.empty();
              addSupplier.append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                addSupplier.append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
            }else{
              updSupplier.empty();
              updSupplier.append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                if(selectedOp == supplier[i].supp_id){
                  updSupplier.append("<option selected value='"+ supplier[i].supp_id +"'>"+ 
                  supplier[i].supp_name +"</option>");
                }else{
                  updSupplier.append("<option value='"+ supplier[i].supp_id +"'>"+ 
                  supplier[i].supp_name +"</option>");
                }
              }
            }
            
        },
    });
    }
    function getCurrencies(type,selectedCurrency){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "visaController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var saleSelected = "";
            var netSelected = "";
            var defaultSelected = "";
            var net_currency_type = $('#net_currency_type');
            var sale_currency_type = $('#sale_currency_type');
            var payment_currency_type = $('#payment_currency_type');
                if(type =="sale"){
                    $('#updsale_currency_type').empty();
                    for(var i=0; i<currencyType.length; i++){
                            if(selectedCurrency == currencyType[i].currencyID ){
                              $('#updsale_currency_type').append("<option selected value='"+ currencyType[i].currencyID +"'>"+ 
                              currencyType[i].currencyName +"</option>");
                            }else{
                              $('#updsale_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                              currencyType[i].currencyName +"</option>");
                            }
                    }
                }else if(type=="net"){
                    $('#updnet_currency_type').empty();
                    for(var i=0; i<currencyType.length; i++){
                            if(selectedCurrency == currencyType[i].currencyID ){
                              $('#updnet_currency_type').append("<option selected value='"+ currencyType[i].currencyID +"'>"+ 
                              currencyType[i].currencyName +"</option>");
                            }else{
                              $('#updnet_currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                              currencyType[i].currencyName +"</option>");
                            }
                    }
                }else{
                    net_currency_type.empty();
                    sale_currency_type.empty();
                    payment_currency_type.empty();
                    for(var i=0; i<currencyType.length; i++){
                        if(i==0){
                            defaultSelected = "selected";
                           net_currency_type.append("<option " + defaultSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                           currencyType[i].currencyName +"</option>");
                           sale_currency_type.append("<option " + defaultSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                           currencyType[i].currencyName +"</option>");
                           payment_currency_type.append("<option " + defaultSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                           currencyType[i].currencyName +"</option>");
                        }else{
                            net_currency_type.append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                            currencyType[i].currencyName +"</option>");
                            sale_currency_type.append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                            currencyType[i].currencyName +"</option>");
                            payment_currency_type.append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                            currencyType[i].currencyName +"</option>");
                        }  
                    }
                }
                
            
        },
    });
    }
    function uploadServiceFiles(id){
       $('#updServiceDID').val(id);
       $('#uploadServiceFilesModel').modal('show');
    }
    function uploadSFiles(){
      u.processQueue();
    }
    var u = 'undefined';
Dropzone.autoDiscover = false;
$('#uploadServiceFilesModel').on('shown.bs.modal', function (e) {
  if(u !='undefined'){
    u.destroy();
  }
  // Initialize Dropzone
  var uploadDropzone = new  Dropzone("#upd-service-documents", {
    paramName: "file",
    url:"uploadFiles.php",
    addRemoveLinks: true,
    autoProcessQueue: false,
    maxFilesize: 10,
    dictDefaultMessage: 'Drag & Drop file here. Remember at a time you can not upload more than 10 files',
    acceptedFiles:'image/jpeg,image/png,image/gif,image/jpg,application/pdf,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.rar,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip',
    uploadMultiple: true,
    parallelUploads: 10,
    dictFileTooBig: 'File is too big, Please upload files which is less than 10 Mbs',
    dictInvalidFileType: 'You can not upload file of this type',
    dictMaxFilesExceeded: 'You can not upload anymore files',
    maxFiles: 10,
    // the setting for dropzone
    init: function(){
     u = this;
      this.on('sending', function(file, xhr,formData){
        var id =  $('#updServiceDID').val();
        if(id ==''){
          notify('Error!', "Something went wrong", 'error');
          return;
        }
        formData.append('userID',id);
      });
      this.on('successmultiple', function(file,message,xhr) {
        if(message[0] == 'both'){
            var errorsFormat = '<ul>';
            var errors = message[1];
            for(var k = 0; k< errors.length; k= k+2 ){
              errorsFormat+= '<li>'+ errors[k] + ' ' + errors[k+1] +'</li>';
            }
            errorsFormat+= '</ul>'
            notify('Error Description!', errorsFormat  , 'error');
            var successFormat = '<ul>';
            var success = message[2];
            for(var j = 0; j< success.length; j++){
              successFormat+= '<li>'+ success[j] +'</li>';
            }
            successFormat+= '</ul>'
            notify('Successful uploaded files !', successFormat  , 'success');
        }else if(Array.isArray(message)){
          var successFormat = '<ul>';
          for(var i = 0; i< message.length; i++){
            successFormat+= '<li>'+ message[i] +'</li>';
          }
          successFormat+= '</ul>'
          notify('Successful uploaded files !', successFormat  , 'success');
        }else{
          notify('Successful uploaded files !', message  , 'success');
        }
      });
      
      this.on("errormultiple", function(file, message, xhr) {
        if(Array.isArray(message)){
          var errorsFormat = '<ul>';
          for(var i = 0; i< message.length; i= i+2 ){
            errorsFormat+= '<li>'+ message[i] + ' ' + message[i+1] +'</li>';
          }
          errorsFormat+= '</ul>'
          notify('Error Description!', errorsFormat  , 'error');
        }else{
            notify('Error Description!', message  , 'error');
        }
        
      });
      this.on('queuecomplete', function(){
          $('#updServiceDID').val('');
          $('#uploadServiceFilesModel').modal('hide');
          getServiceReport();
      });
      this.on("complete", function(file) {
         this.removeFile(file);
      });
    }
});
});
 function viewServiceFiles(id){
        var getUploadedFiles = "getUploadedFiles";
        $('#viewUploadedFilesModal').appendTo("body").modal('show');
        $('#viewUploadedFilesModal').modal('show');
        $.ajax({
            type: "POST",
            url: "serviceController.php",  
            data: {
                GetUploadedFiles:getUploadedFiles,
                ID: id
            },
            success: function (response) {
              var uploadedDocumentsRpt = JSON.parse(response);
              if(uploadedDocumentsRpt.length == 0){
                $('#viewFilesTbl').empty();
                <?php if($delete == 1) { ?>
                    var finalTable = "<tr><td></td><td>No Files uploaded... </td><td></td></tr>";
                <?php }else{ ?>
                    var finalTable = "<tr><td class='text-center'> No Files uploaded...</td><td></td></tr>";
                <?php } ?>
                $('#viewFilesTbl').append(finalTable);
              }else{
                $('#viewFilesTbl').empty();
                var j = 1;
                var byview = "byview";
                var finalTable = "";
                  for(var i=0; i<uploadedDocumentsRpt.length; i++){
                    finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'><a href='downloadSerDocument.php?id=" +  uploadedDocumentsRpt[i].document_id +"'>"+ uploadedDocumentsRpt[i].original_name +"</a></td>";
                    <?php if($delete == 1){ ?>
                        finalTable +="<td><button type='button' onclick='DeleteFile(\"" + byview  +"\"," + uploadedDocumentsRpt[i].document_id +")'" +
                        "class='btn'><i class='fa fa-trash text-graident-Lawrencium  fa-2x'  aria-hidden='true'></i></button></td>";
                    <?php } ?>
                    finalTable += "</tr>";
                    $('#viewFilesTbl').append(finalTable);
                    j +=1;
                 }
              }
            },
        });
 }
function DeleteFile(type,id){

  var DeleteFile = "DeleteFile";
  var message = '';
  if(type == "byview"){
    message = "Do you want to delete this file?";
  }else if(type =="byRecord"){
    message = "Do you want to delete this row?";
  }
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
                url: "serviceController.php",  
                data: {
                  DeleteFile:DeleteFile,
                  Type:type,
                  ID:id
                },
                success: function (response) {  

                if(response == 'Success'){
                  if(type == "byview"){
                    notify('Success!', "File Successfully deleted", 'success');
                    $('#viewUploadedFilesModal').modal('hide');
                  }else{
                    notify('Success!', "Record Successfully deleted", 'success');
                    getServiceReport();
                  }
                 
                  
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
function edit(id){
  var editData = "editData";
  $.ajax({
          type: "POST",
          url: "serviceController.php",  
          data: {
            EditData:editData,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#updateserviceDID').val(id);
            $('#updServiceDetailModal').appendTo("body").modal('show');
            getServices('update',dataRpt[0].serviceID);
            getCustomer('ByUpdate',dataRpt[0].customer_id);
            $('#updPassenger_name').val(dataRpt[0].passenger_name);
            getSupplier('ByUpdate',dataRpt[0].Supplier_id);
            getAccounts('byUpdate',dataRpt[0].accoundID);
            $('#updserviceDetail').val(dataRpt[0].service_details);
            $('#updnet_amount').val(dataRpt[0].netPrice);
            getCurrencies('net',dataRpt[0].netCurrencyID);
            $('#updsale_amount').val(dataRpt[0].salePrice);
            getCurrencies('sale',dataRpt[0].saleCurrencyID);
           
        },
  });
}

$(document).on('submit', '#updServiceForm', function(event){
    event.preventDefault();
    var updateserviceDID = $('#updateserviceDID');
    var updServiceType = $('#updServiceType');
    var updcustomer_id = $('#updcustomer_id');
    var updsupplier = $('#updsupplier');
    var updcharge_account_id = $('#updcharge_account_id');
    var updserviceDetail = $('#updserviceDetail');
    var updnet_amount = $('#updnet_amount');
    var updnet_currency_type = $('#updnet_currency_type');
    var updsale_amount = $('#updsale_amount');
    var updsale_currency_type = $('#updsale_currency_type');
    var updPassenger_name = $('#updPassenger_name');
    if(updateserviceDID.val() == ""){
        notify('Validation Error!', 'Something went wrong.', 'error');
        return;
    }
    if(updPassenger_name.val() == ""){
        notify('Validation Error!', 'Passenger name is required', 'error');
        return;
    }
    if(updServiceType.val() == "-1"){
        notify('Validation Error!', 'Service type is required', 'error');
        return;
    }
    if(updcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    if(updsupplier.val() == "-1" && updcharge_account_id.val() == '-1'){
        notify('Validation Error!', 'Service should be charged on Supplier or any account', 'error');
        return;
    }
    if(updsupplier.val() != "-1" && updcharge_account_id.val() != '-1'){
        notify('Validation Error!', 'Service should be charged either on supplier or any account', 'error');
        return;
    }
    if(updserviceDetail.val() == "-1"){
        notify('Validation Error!', 'Service detail is required', 'error');
        return;
    }
    if(updnet_amount.val() == ""){
        notify('Validation Error!', 'Net amount is required', 'error');
        return;
    }
    if(updsale_amount.val() == ""){
        notify('Validation Error!', 'Sale amount is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('UpdateService','UpdateService');
      $('#updServiceBtn').attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "serviceController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('success!', "Record updated successfully!", 'success');
                    $('#updateserviceDID').val('');
                    $('#updServiceDetailModal').modal('hide');
                    $('#updServiceBtn').attr('disabled', false);
                    getServiceReport();
                }else{
                    notify('Error!', "Something went wrong! Nothing recorded in database", 'error');
                    $('#updServiceBtn').attr('disabled', false);
                }
            },
        });
  });
</script>

</html>
