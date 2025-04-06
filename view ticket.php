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
<title>Ticket Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Ticket' ";
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
    <h2><b>Ticket/<span style="color:#C66">Report</span></b></h2>
  </div>
  <div class="card-body">
  <div class="row">
       <div class="col-lg-12">
          <form>
            <div class="row">
            <div class="col-lg-1" style="margin-top:8px">
                <label for="staticEmail2">Issue Date: </label>&nbsp;&nbsp;<br/>
                <input class="form-check-input" type="checkbox" id="dateChk" name="dateChk" value="option1">
              </div>
              <div class="col-lg-1" style="margin-top:8px">
                <label for="staticEmail2">Travel Date: </label>&nbsp;&nbsp;<br/>
                <input class="form-check-input" type="checkbox" name="TravChk" id="TravChk" value="option2">
              </div>
              <div class="col-lg-1">
                <label for="staticEmail2">From: </label>&nbsp;&nbsp;<br/>
                <input type="text" class="form-control"  id="fromdate" >&nbsp;
              </div>
              <div class="col-lg-1">
                <label for="staticEmail2">To:</label>&nbsp;
                <input type="text" class="form-control"  id="todate" >&nbsp;
              </div>
              <div class="col-lg-2">
                <label for="supp_name">Customer:</label>
                <select class="form-control  js-example-basic-single js-states " style="width:100%" name="customer_id" id="customer_id"></select>
              </div>
              <div class="col-lg-2">
                <label for="staticEmail2">Passenger Name:</label>&nbsp;
                <input type="text" class="form-control"  id="passenger_name" placeholder="Passenger Name" >&nbsp;
              </div>
              <div class="col-lg-1">
                <label for="staticEmail2">Pnr:</label>&nbsp;
                <input type="text" class="form-control"  id="pnr" placeholder="Pnr" >&nbsp;
              </div>
              <div class="col-lg-1">
                <label for="staticEmail2">Ticket#:</label>&nbsp;
                <input type="text" class="form-control" placeholder="Ticket Number"  id="ticket_number" >&nbsp;
              </div>
              <div class="col-lg-2">
              <label for="staticEmail2">Action</label>
                <button type="button" onclick="getTicketReport()" style="width:100%" class="btn d-block btn-dark mb-2"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
              </div>
            </div>
          </form>
       </div>
    </div>
    <form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="upload" >
          <input type="file" name="uploader" id="uploader" />
          <input type="text" name="uploadTicketID" id="uploadTicketID" />
          <input type="text" name="uploadType" id="uploadType" />
          <button type="submit" id="submitUploadForm" >Call</button>
    </form>
      <br/>
      <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="thead-dark bg-dark text-white">
            <tr>
              <th>S#</th>
              <th width="184">Customer Name</th>
              <th width="184">Passenger Name</th>
              <th >Date</th>
              <th >Return Date</th>
              <th>PNR</th>
              <th>Ticket Number</th>
              <th >Sale</th>
              <th >Supplier</th>
              <th >Origin</th>
              <th>Destination</th>
              <th>Ticket</th>
              <th>Transaction Type</th>
              <th class='text-center'>Action</th>
            </tr>
          </thead>
          <tbody id="TicketReportTbl">
          
                    
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
<!-- Date Change Modal -->
<div class="modal fade" id="dateChangeModel"  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Date Change</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form method="post" enctype="multipart/form-data" id="dateChangeFrm">
        <input type="hidden"  class="form-control" id="ticketID" name="ticketID">
        <input type="hidden"  class="form-control" id="changeDateType" name="changeDateType">
        <div class="form-group row mb-2">
          <label for="staticEmail" class="col-sm-3 col-form-label">Extend Date:</label>
          <div class="col-sm-9">
            <input type="date"  class="form-control" name="extendedDate"  id="extendedDate">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net/Sale Price:</label>
          <div class="col-sm-9">
             <div class="row">
                <div class="col-sm-6 mb-2">
                  <input type="number" class="form-control" disabled name="extTicketNet" id="extTicketNet" placeholder="Net Price">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="dcNet_currency_type" name="dcNet_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-sm-6 mb-2">
                  <input type="number" class="form-control" disabled name="exsaleTk_price" id="exsaleTk_price" placeholder="Sale Price">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="dcSale_currency_type" name="dcSale_currency_type" spry:default="select one"></select>
                </div>
             </div>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-9 offset-3">
              <hr/>
            </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Date Change Charges:</label>
          <div class="col-sm-9">
             <div class="row">
                <div class="col-sm-6 mb-2">
                  <input type="number" class="form-control mb-2"  name="exnet_price" id="exnet_price" placeholder="Net Charge">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="dccNet_currency_type" name="dccNet_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-sm-6 mb-2">
                  <input type="number" class="form-control mb-2" name="exsale_price" id="exsale_price" placeholder="Sale Charge">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="dccSale_currency_type" name="dccSale_currency_type" spry:default="select one"></select>
                </div>
             </div>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
          <div class="col-sm-9">
              <select class="form-control js-example-basic-single js-states" style="width:100%" name="exsupplier" id="exsupplier"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
            <textarea class="form-control" id="exremarks" name="exremarks" rows="3"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Upload Ticket:</label>
          <div class="col-sm-9">
            <input type="file" id="extendedTicket" name="extendedTicket" class="form-control">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="dateChangeBtn" class="btn btn-danger">Change</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Date Refund Modal -->
<div class="modal fade" id="refundModel"  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Refund</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="rfdForm">
        <input type="hidden"  class="form-control" id="rfdticketID" name="rfdticketID">
        <input type="hidden"  class="form-control" id="rfdType" name="rfdType">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6 mb-2">
                  <input type="number" disabled class="form-control" id="rfdnet_priceTicket"  placeholder="Net Price">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdNet_currency_type" name="rfdNet_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-sm-6 mb-2">
                  <input type="number" class="form-control" onfocusout="refundCal('net_refund',document.getElementById('rfdnet_priceAmount').value)" id="rfdnet_priceAmount"  placeholder="Net Price">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdcNet_currency_type" name="rfdcNet_currency_type" spry:default="select one"></select>
                </div>
                <div class="col-sm-6 mb-2">
                  <input type="number" disabled class="form-control"  id="rfdnet_price" placeholder="Net Price">
                </div>
                <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdcANet_currency_type" name="rfdcANet_currency_type" spry:default="select one"></select>
                </div>

            </div>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-9">
            <div class="row">
              <div class="col-sm-6 mb-2">
                <input type="number" disabled class="form-control" id="rfdsale_priceTicket" placeholder="Sale Price">
              </div>
              <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdSale_currency_type" name="rfdSale_currency_type" spry:default="select one"></select>
              </div>
              <div class="col-sm-6 mb-2">
                <input type="number" class="form-control" onfocusout="refundCal('sale_refund',document.getElementById('rfdsale_priceAmount').value)"  id="rfdsale_priceAmount" placeholder="Sale Price">
              </div>
              <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdcSale_currency_type" name="rfdcSale_currency_type" spry:default="select one"></select>
                </div>
              <div class="col-sm-6 mb-2">
                <input type="number" disabled class="form-control" id="rfdsale_price" placeholder="Sale Price">
              </div>
              <div class="col-sm-6">
                    <select class="form-control js-example-basic-single"   style="width:100%" id="rfdcASale_currency_type" name="rfdcASale_currency_type" spry:default="select one"></select>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
            <textarea class="form-control" id="rfdremarks" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="rfdBtn" class="btn btn-dark" onclick="SaveRefund()">Refund</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Update Model -->
<div class="modal fade" id="updateModel" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="updticketID" name="updticketID">
        <input type="hidden"  class="form-control" id="updType" name="updType">
        <div id="ticketSection">
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Ticket Number:</label>
          <div class="col-sm-9 mb-2">
            <input type="text" class="form-control" name="updTicketNum" id="updTicketNum" placeholder="Ticket Num">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Pnr</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" name="updPnr" id="updPnr" placeholder="Pnr">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer:</label>
          <div class="col-sm-9">
          <select class="form-control js-example-basic-single" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Passenger Name:</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" style="width:100%" name="updPassengerName" id="updPassengerName" placeholder="Passenger Name">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Date of Travel:</label>
          <div class="col-sm-9">
            <input type="date" class="form-control"  name="upddateOftravel" id="upddateOftravel" placeholder="Date of Travel">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">From:</label>
          <div class="col-sm-9">
          <select class="form-control js-example-basic-single" style="width:100%" name="updfrom" id="updfrom"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">To:</label>
          <div class="col-sm-9">
          <select class="form-control js-example-basic-single" style="width:100%" name="updto" id="updto"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-6">
              <input type="number" class="form-control" name="updSale" id="updSale" placeholder="Sale Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updSale_currency_type" name="updSale_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
          <div class="col-sm-9">
          <select class="form-control js-example-basic-single" style="width:100%" name="updsupplier" id="updsupplier"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updNet" id="updNet" placeholder="Net Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updNet_currency_type" name="updNet_currency_type" spry:default="select one"></select>
          </div>
        </div>
        </div>
        <div id="dateExtendSection">
        <div class="form-group row mb-2">
          <label for="staticEmail" class="col-sm-3 col-form-label">Extend Date:</label>
          <div class="col-sm-9">
            <input type="date"  class="form-control" name="updextendedDate"  id="updextendedDate">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updexnet_price" id="updexnet_price" placeholder="Net Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updDcNet_currency_type" name="updDcNet_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updexsale_price" id="updexsale_price" placeholder="Sale Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updDcSale_currency_type" name="updDcSale_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
          <div class="col-sm-9">
              <select class="form-control js-example-basic-single" style="width:100%" name="updexsupplier" id="updexsupplier"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
            <textarea class="form-control" id="updexremarks" name="updexremarks" rows="3"></textarea>
          </div>
        </div>
        </div>
        <div id="refdSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updrfdnet_price" id="updrfdnet_price" placeholder="Net Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updRfdNet_currency_type" name="updRfdNet_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" name="updrfdsale_price" id="updrfdsale_price" placeholder="Sale Price">
          </div>
          <div class="col-sm-3">
              <select class="form-control js-example-basic-single"   style="width:100%" id="updRfdSale_currency_type" name="updRfdSale_currency_type" spry:default="select one"></select>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
            <textarea class="form-control" id="updrfdremarks" name="updrfdremarks" rows="3"></textarea>
          </div>
        </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="updBtn" class="btn btn-danger" onclick="SaveUpdate()">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>

<script>
    $(document).ready(function(){
      $('.js-example-basic-single').select2();
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
      getCustomers("byAll",'');
      $("#exsupplier").select2({
       dropdownParent: $("#dateChangeModel")
      });
      $("#dcNet_currency_type").select2({
       dropdownParent: $("#dateChangeModel")
      });
      $("#dcSale_currency_type").select2({
       dropdownParent: $("#dateChangeModel")
      });
      $("#dccNet_currency_type").select2({
       dropdownParent: $("#dateChangeModel")
      });
      $("#dccSale_currency_type").select2({
       dropdownParent: $("#dateChangeModel")
      });
      $("#rfdNet_currency_type").select2({
       dropdownParent: $("#refundModel")
      });
      $("#rfdcNet_currency_type").select2({
       dropdownParent: $("#refundModel")
      });
      $("#rfdcANet_currency_type").select2({
       dropdownParent: $("#refundModel")
      });
      //
      $("#rfdSale_currency_type").select2({
       dropdownParent: $("#refundModel")
      });
      $("#rfdcSale_currency_type").select2({
       dropdownParent: $("#refundModel")
      });
      $("#rfdcASale_currency_type").select2({
       dropdownParent: $("#refundModel")
      });

      //
      $("#updcustomer_id").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updfrom").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updto").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updsupplier").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updexsupplier").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updNet_currency_type").select2({
       dropdownParent: $("#updateModel")
      });
      $("#updSale_currency_type").select2({
       dropdownParent: $("#updateModel")
      });
$("#updDcNet_currency_type").select2({
       dropdownParent: $("#updateModel")
      });
$("#updDcSale_currency_type").select2({
       dropdownParent: $("#updateModel")
      });

    });
    function openChangeModel(ticketID,type){
      event.preventDefault();
      var getTicketInfo = "getTicketInfo";
      $('#ticketID').val(ticketID);
      $('#changeDateType').val(type);
      $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            GetTicketInfo:getTicketInfo,
            Type:type,
            TicketID:ticketID
        },
        success: function (response) {  
          var info = JSON.parse(response);
            $('#extTicketNet').val(info[0].net_price);
            $('#exsaleTk_price').val(info[0].sale);
            getCurrencies('datechange', info[0].currencyID,info[0].net_CurrencyID);
        },
    });
      $('#dateChangeModel').modal('show');
      getSupplier("addChangeDate",ticketID,type);

    }
    function openRefundModel(ticketID,type){
      event.preventDefault();
      $('#rfdticketID').val(ticketID);
      $('#rfdType').val(type);
      var getTicketInfo = "getTicketInfo";
      $('#ticketID').val(ticketID);
      $('#changeDateType').val(type);
      $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            GetTicketInfo:getTicketInfo,
            Type:type,
            TicketID:ticketID
        },
        success: function (response) {  
          var info = JSON.parse(response);
            $('#rfdnet_priceTicket').val(info[0].net_price);
            $('#rfdsale_priceTicket').val(info[0].sale);
            getCurrencies('refund', info[0].currencyID,info[0].net_CurrencyID);
        },
    });
      $('#refundModel').modal('show');
    }
    function UpdateTicket(ticketID,type){
      event.preventDefault();
      $('#updticketID').val(ticketID);
      $('#updType').val(type);
      var ticketSection =  $('#ticketSection');
      var dateExtendSection = $('#dateExtendSection');
      var refdSection = $('#refdSection');
      $('#genralUpdForm')[0].reset();
      if(type=="Issued"){
        ticketSection.show();
        dateExtendSection.hide();
        refdSection.hide();  
        var GetUpdTicket = "GetUpdTicket";
        $.ajax({
          type: "POST",
          url: "viewTicketController.php",  
          data: {
            GetUpdTicket:GetUpdTicket,
            TicketID:ticketID,
            Type:type
        },
        success: function (response) {  
          var dataRpt = JSON.parse(response);
            $('#updTicketNum').val(dataRpt[0].ticketNumber);
            $('#updPnr').val(dataRpt[0].Pnr);
            getCustomers("byCustomer",ticketID);
            $('#updPassengerName').val(dataRpt[0].passenger_name);
            $('#upddateOftravel').val(dataRpt[0].date_of_travel);
            getFrom(ticketID);
            $('#updSale').val(dataRpt[0].sale);
            getSupplier("getUpdSupplier",ticketID,type);
            $('#updNet').val(dataRpt[0].net_price);
            getCurrencies('Updticket', dataRpt[0].currencyID,dataRpt[0].net_CurrencyID);
            $('#updateModel').modal('show');

        },
        });
      }else  if(type=="Date Change"){
        ticketSection.hide();
        dateExtendSection.show();
        refdSection.hide();  
        var GetUpdTicket = "GetUpdTicket";
        $.ajax({
          type: "POST",
          url: "viewTicketController.php",  
          data: {
            GetUpdTicket:GetUpdTicket,
            TicketID:ticketID,
            Type:type
        },
        success: function (response) { 
          var dataRpt = JSON.parse(response);
            $('#updextendedDate').val(dataRpt[0].extended_Date);
            $('#updexnet_price').val(dataRpt[0].net_amount);
            $('#updexsale_price').val(dataRpt[0].sale_amount);
            $('#updexremarks').val(dataRpt[0].remarks);
            getSupplier("getUpdSupplier",ticketID,type);
            getCurrencies('updateDateChange', dataRpt[0].saleCurrencyID,dataRpt[0].netCurrencyID);
            $('#updateModel').modal('show');

        },
        });
      }else  if(type=="Refund"){
        ticketSection.hide();
        dateExtendSection.hide();
        refdSection.show();  
        var GetUpdTicket = "GetUpdTicket";
        $.ajax({
          type: "POST",
          url: "viewTicketController.php",  
          data: {
            GetUpdTicket:GetUpdTicket,
            TicketID:ticketID,
            Type:type
        },
        success: function (response) {  
          var dataRpt = JSON.parse(response);
            $('#updrfdnet_price').val(dataRpt[0].sale_amount);
            $('#updrfdsale_price').val(dataRpt[0].net_amount);
            $('#updrfdremarks').val(dataRpt[0].remarks);
            getCurrencies('updateRefund', dataRpt[0].saleCurrencyID,dataRpt[0].netCurrencyID);
            $('#updateModel').modal('show');

        },
        });
      }
    }
    function SaveRefund(){
      var net_price = $('#rfdnet_price');
      if(net_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
      }
      var sale_price = $('#rfdsale_price');
      if(sale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
      }
      var rfdcANet_currency_type = $('#rfdcANet_currency_type');
      var rfdcASale_currency_type = $('#rfdcASale_currency_type');
      var refund = "refund";
      var remarks = $('#rfdremarks');
      var ticketID = $('#rfdticketID');
      var rfdType = $('#rfdType');
      $("#rfdBtn").attr("disabled", true);
      $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            Refund:refund,
            TicketID:ticketID.val(),
            Net_Price:net_price.val(),
            RfdcANet_currency_type:rfdcANet_currency_type.val(),
            Sale_Price:sale_price.val(),
            RfdcASale_currency_type:rfdcASale_currency_type.val(),
            Remarks:remarks.val(),
            RfdType:rfdType.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#refundModel').modal('hide');
            $("#rfdForm")[0].reset();
            getTicketReport();
            $("#rfdBtn").attr("disabled", false);
          }else{
            notify('Opps!', response, 'error');
            $("#rfdBtn").attr("disabled", false);
          }
          
        },
      });
    }
    function getTicketReport(){
      var getTicketReport = "getTicketReport";
      var searchTerm = '';
      var dateChk =$('#dateChk');
      var TravChk = $('#TravChk');
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      var customer_id = $('#customer_id');
      var passenger_name = $('#passenger_name');
      var pnr = $('#pnr');
      var date_of_travel = $('#date_of_travel');
      var ticket_number = $('#ticket_number');
      if (dateChk.is(':checked')) {
        searchTerm = 'dateWise';
      }else if(TravChk.is(':checked')){
        searchTerm = 'dateOfTravel';
      }else if(customer_id.val() != -1 && passenger_name.val() !='' ){
        searchTerm = 'CustomerPassenger';
      }else if(customer_id.val() != -1 && passenger_name.val() =='' ){
        searchTerm = 'customer';
      }else if(customer_id.val() == -1 && passenger_name.val() !='' ){
        searchTerm = 'passengerName';
      }else if(pnr.val() != ''){
        searchTerm = 'pnr';
      }else if(ticket_number.val() != ''){
        searchTerm = 'ticketNumber';
      }else if(searchTerm == ''){
        notify('Validation Error!', 'You have to select one option at least to perform search ', 'error');
        return;
      }
      $.ajax({
          type: "POST",
          url: "viewTicketController.php",  
          data: {
              GetTicketReport:getTicketReport,
              SearchTerm:searchTerm,
              Fromdate:fromdate.val(),
              Todate:todate.val(),
              Customer_ID:customer_id.val(),
              Passenger_Name:passenger_name.val().toLowerCase(),
              Pnr:pnr.val().toLowerCase(),
              Date_Of_Travel:date_of_travel.val(),
              Ticket_Number:ticket_number.val()
          },
          success: function (response) {  
            var ticketRpt = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            
            for(var i=0; i<ticketRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ ticketRpt[i].customer_name +"</td>"+
              "<td class='text-capitalize'>"+ ticketRpt[i].passenger_name +"</td><td>"+ ticketRpt[i].date +"</td><td>"+ ticketRpt[i].return_date +"</td><td>"+ 
              ticketRpt[i].pnr +"</td><td>"+ ticketRpt[i].ticketNumb +"</td><td>"+ ticketRpt[i].sale + ' ' + ticketRpt[i].currenyName + "</td><td>"+ ticketRpt[i].supp_name +"</td>"+
              "<td>"+ ticketRpt[i].from_code +"</td><td>"+ ticketRpt[i].to_place +"</td>";
              if(ticketRpt[i].ticketCopy == null || ticketRpt[i].ticketCopy == '' ){
                finalTable += "<td><button type='button' onclick='uploadFile("+ ticketRpt[i].ticketID +",\"" + ticketRpt[i].TranType + "\")' class='btn'><i class='fa fa-upload text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }else{
                finalTable += "<td style='width:130px'><a href='" + ticketRpt[i].ticketCopy  +"' download><button type='button' class='btn'><i class='fa fa-download text-dark fa-2x' aria-hidden='true'></i>"+
                "</button></a><button type='button' onclick='deleteFile("+ ticketRpt[i].ticketID +",\"" + ticketRpt[i].TranType + "\")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
              }
              if(ticketRpt[i].TranType == 'Issued'){
                finalTable += "<td><button type='button' class='btn btn-success text-white'>" + 
                ticketRpt[i].TranType + "</button></td>";
              }else if(ticketRpt[i].TranType == 'Date Change'){
                finalTable += "<td><button type='button' class='btn btn-info text-white'>" + 
                ticketRpt[i].TranType + "</button></td>";
              }else if(ticketRpt[i].TranType == 'Refund'){
                finalTable += "<td><button type='button' class='btn btn-danger text-white'>" + 
                ticketRpt[i].TranType + "</button></td>";
              }
              finalTable += "<td style='width:280px'><button type='button'0 onclick='openChangeModel(" + ticketRpt[i].ticketID +  ",\"" + ticketRpt[i].TranType + "\")'" +
              "class='btn'><i class='fa fa-calendar text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;<button type='button'0 onclick='openRefundModel(" + 
              ticketRpt[i].ticketID  + ",\"" + ticketRpt[i].TranType + "\")'" +
              "class='btn'><i class='fa fa-money text-danger fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php if($update == 1){ ?>
                finalTable += "<button type='button'0 onclick='UpdateTicket("+ 
              ticketRpt[i].ticketID +",\"" + ticketRpt[i].TranType + "\")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              
              <?php } ?>
              <?php if($delete == 1){ ?>
                finalTable +="<button type='button'0 onclick='DeleteTicket("+ 
                ticketRpt[i].ticketID +",\"" + ticketRpt[i].TranType + "\")'" +
                "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              finalTable +="</td>";
              finalTable += "</tr>";
              $('#TicketReportTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
    function uploadFile(ticketID,type){
          $('#uploadTicketID').val(ticketID);
          $('#uploadType').val(type);
          $('#uploader').click();
    }
    document.getElementById("uploader").onchange = function(event) {
      $('#submitUploadForm').click();
    };
    $(document).on('submit', '#upload', function(event){
      event.preventDefault();
      var uploadTicketID = $('#uploadTicketID');
      var uploadType = $('#uploadType');
      var uploader = $('#uploader').val();
      if(uploadTicketID.val() == ""){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if(uploadType.val() == ""){
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
      data.append('Upload_TicketPhoto','Upload_TicketPhoto');
        $.ajax({
            type: "POST",
            url: "viewTicketController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
              console.log(response);
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getTicketReport();
                    uploadTicketID.val('');
                    uploadType.val(''); 
                    $('#uploader').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function getCustomers(type,ticketID){
    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
            Type:type,
            TicketID:ticketID
        },
        success: function (response) {  
            var customer = JSON.parse(response);
            if(type =="byAll"){
              $('#customer_id').empty();
              $('#customer_id').append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }else{
              var selected = '';
              $('#updcustomer_id').empty();
              $('#updcustomer_id').append("<option value='-1'>--Customer--</option>");
              for(var i=0; i<customer.length; i++){
                if(customer[i].customer_id == customer[i].selectedCustomer){
                  selected = 'selected';
                }else{
                  selected = '';
                }
                $('#updcustomer_id').append("<option "+ selected + " value='"+ customer[i].customer_id +"'>"+ 
                customer[i].customer_name +"</option>");
              }
            }
            
        },
    });
}
    function getSupplier(type,ticketID,transType){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            SELECT_Supplier:select_supplier,
            Type:type,
            TicketID:ticketID,
            TransType:transType
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type =="addChangeDate")
            { 
              $('#exsupplier').empty();
              $('#exsupplier').append("<option value='-1'>--Supplier--</option>");
              var selected = "";
              for(var i=0; i<supplier.length; i++){
                  if(supplier[i].supp_id == supplier[i].selectedSup){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
              $('#exsupplier').append("<option " + selected + " value='"+ supplier[i].supp_id +"'>"+ 
              supplier[i].supp_name +"</option>");
              }
            }else if(type =="getUpdSupplier"){
              if(transType == "Issued"){
                $('#updsupplier').empty();
                $('#updsupplier').append("<option value='-1'>--Supplier--</option>");
                var selected = "";
                for(var i=0; i<supplier.length; i++){
                  if(supplier[i].supp_id == supplier[i].selectedSup){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                $('#updsupplier').append("<option " + selected + " value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
                }
              }else{
                $('#updexsupplier').empty();
              $('#updexsupplier').append("<option value='-1'>--Supplier--</option>");
                var selected = "";
                for(var i=0; i<supplier.length; i++){
                  if(supplier[i].supp_id == supplier[i].selectedSup){
                    selected = "selected";
                  }else{
                    selected = "";
                  }
                $('#updexsupplier').append("<option " + selected + " value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
                }
              }
            }
        },
    });
    }
$(document).on('submit', '#dateChangeFrm', function(event){
      event.preventDefault();
      var extendedDate = $('#extendedDate');
      var changeDateType = $('#changeDateType');
      if(extendedDate.val() == ""){
        notify('Validation Error!', 'Date is required', 'error');
        return;
      }
      var exnet_price = $('#exnet_price');
      if(exnet_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
      }
      var exsale_price = $('#exsale_price');
      if(exsale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
      }
      var addextended_date = "addextended_date";
      var exremarks = $('#exremarks');
      var ticketID = $('#ticketID');
      var exsupplier = $('#exsupplier');
      if(exsupplier.val() == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
      }
      var extendedTicket = $('#extendedTicket').val();
      if($('#extendedTicket').val() != ''){
        if($('#extendedTicket')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      var dccNet_currency_type = $('#dccNet_currency_type').val();
      var dccSale_currency_type = $('#dccSale_currency_type').val();
      data = new FormData(this);
      data.append('addextended_date','addextended_date');
      $("#dateChangeBtn").attr("disabled", true);
        $.ajax({
            type: "POST",
            url: "viewTicketController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getTicketReport();
                    $('#dateChangeModel').modal('hide');
                    $("#dateChangeFrm")[0].reset();
                    $("#dateChangeBtn").attr("disabled", false);
                }else{
                    notify('Error!', response, 'error');
                    $("#dateChangeBtn").attr("disabled", false);
                }
            },
        });
    });

function deleteFile(ticketID,type){
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
                url: "viewTicketController.php",  
                data: {
                  DeleteFile:DeleteFile,
                  TicketID:ticketID,
                  Type:type
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getTicketReport();
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

function DeleteTicket(ticketID,type){
  var DeleteTicket = "DeleteTicket";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this ticket',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewTicketController.php",  
                data: {
                  DeleteTicket:DeleteTicket,
                  TicketID:ticketID,
                  Type:type
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getTicketReport();
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
function getFrom(ticketID){
    var select_from = "SELECT_FROM";
    $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            SELECT_FROM:select_from,
            TicketID:ticketID
        },
        success: function (response) {
            var from = JSON.parse(response);
            $('#updfrom').empty();
            $('#updto').empty();
            $('#updto').append("<option value='-1'>--Arrival--</option>");
            $('#updfrom').append("<option value='-1'>--Departure--</option>");
            var fromselected = "";
            var toselected = "";
            for(var i=0; i<from.length; i++){
              if(from[i].airport_id == from[i].selectedFromID){
                fromselected = 'selected';
              }else{
                fromselected ='';
              }
              $('#updfrom').append("<option "+ fromselected + " value='"+ from[i].airport_id +"'>"+ 
              from[i].airport_code +"</option>");
              if(from[i].airport_id == from[i].selectedToID){
                toselected = 'selected';
              }else{
                toselected ='';
              }
              $('#updto').append("<option "+ toselected + " value='"+ from[i].airport_id +"'>"+ 
              from[i].airport_code +"</option>");
            }
        },
    });
}
function SaveUpdate(){
  var ticketID = $('#updticketID').val();
  var type = $('#updType').val();
  var updTicketNum = $('#updTicketNum');
  var updPnr = $('#updPnr');
  var updcustomer_id = $('#updcustomer_id');
  var updPassengerName = $('#updPassengerName');
  var upddateOftravel = $('#upddateOftravel');
  var updfrom = $('#updfrom');
  var updto = $('#updto');
  var updSale = $('#updSale');
  var updsupplier = $('#updsupplier');
  var updNet = $('#updNet');
  var updextendedDate = $('#updextendedDate');
  var updexnet_price = $('#updexnet_price');
  var updexsale_price = $('#updexsale_price');
  var updexsupplier = $('#updexsupplier');
  var updexremarks = $('#updexremarks');
  var updrfdnet_price = $('#updrfdnet_price');
  var updrfdsale_price = $('#updrfdsale_price');
  var updrfdremarks = $('#updrfdremarks');
  var updNet_currency_type = $('#updNet_currency_type');
  var updSale_currency_type = $('#updSale_currency_type');
  var updDcNet_currency_type = $('#updDcNet_currency_type');
  var updDcSale_currency_type = $('#updDcSale_currency_type');
  var updRfdNet_currency_type = $('#updRfdNet_currency_type');
  var updRfdSale_currency_type = $('#updRfdSale_currency_type');
  if(type =="Issued"){
    if(updPnr.val() == ""){
        notify('Validation Error!', 'Pnr is required', 'error');
        return;
    }
    if(updcustomer_id.val() == "-1"){
        notify('Validation Error!', 'Customer is required', 'error');
        return;
    }
    if(updPassengerName.val() == ""){
        notify('Validation Error!', 'Passenger is required', 'error');
        return;
    }
    if(upddateOftravel.val() == ""){
        notify('Validation Error!', 'Date of travel is required', 'error');
        return;
    }
    if(updfrom.val() == "-1"){
        notify('Validation Error!', 'Departure is required', 'error');
        return;
    }
    if(updto.val() == "-1"){
        notify('Validation Error!', 'Arrival is required', 'error');
        return;
    }
    if(updSale.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
    }
    if(updsupplier.val() == ""){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
    if(updNet.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
    }
  }else if(type=="Date Change"){
    if(updextendedDate.val() == ""){
        notify('Validation Error!', 'Extended data is required', 'error');
        return;
    }
    if(updexnet_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
    }
    if(updexsale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
    }
    if(updexsupplier.val() == ""){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
    if(updexsupplier.val() == ""){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
    }
  }else if(type=="Refund"){
    if(updrfdnet_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
    }
    if(updrfdsale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
    }
  }
  var saveUpdateTicket = "saveUpdateTicket";
  $("#updBtn").attr("disabled", true);
  $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            SaveUpdateTicket:saveUpdateTicket,
            TicketID:ticketID,
            Type:type,
            UpdTicketNum:updTicketNum.val(),
            UpdPnr:updPnr.val(),
            Updcustomer_id:updcustomer_id.val(),
            UpdPassengerName:updPassengerName.val(),
            UpddateOftravel:upddateOftravel.val(),
            Updfrom:updfrom.val(),
            Updto:updto.val(),
            UpdSale:updSale.val(),
            Updsupplier:updsupplier.val(),
            UpdNet:updNet.val(),
            UpdextendedDate:updextendedDate.val(),
            Updexnet_price:updexnet_price.val(),
            Updexsale_price:updexsale_price.val(),
            Updexsupplier:updexsupplier.val(),
            Updexremarks:updexremarks.val(),
            Updrfdnet_price:updrfdnet_price.val(),
            Updrfdsale_price:updrfdsale_price.val(),
            Updrfdremarks:updrfdremarks.val(),
            UpdSale_Currency_Type:updSale_currency_type.val(),
            UpdNet_Currency_Type:updNet_currency_type.val(),
            UpdDcNet_Currency_Type:updDcNet_currency_type.val(),
            UpdDcSale_Currency_Type:updDcSale_currency_type.val(),
            UpdRfdNet_Currency_Type:updRfdNet_currency_type.val(),
            UpdRfdSale_Currency_Type:updRfdSale_currency_type.val()
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#updateModel').modal('hide');
            $("#genralUpdForm")[0].reset();
            getTicketReport();
            $("#updBtn").attr("disabled", false);
          }else if(response == "NoPermission"){
            window.location.href='pageNotFound.php'
          }else{
            notify('Opps!', response, 'error');
            $("#updBtn").attr("disabled", false);
          }
          
        },
      });
}

function refundCal(type,val){
  if(type == "net_refund"){
    $('#rfdnet_price').val($('#rfdnet_priceTicket').val() - val);
  }else if(type == "sale_refund"){
    $('#rfdsale_price').val($('#rfdsale_priceTicket').val() - val);
  }
}

function getCurrencies(type, saleCurrency, netCurrency ){
    var currencyTypes = "currencyTypes";
    $.ajax({
        type: "POST",
        url: "viewTicketController.php",  
        data: {
            CurrencyTypes:currencyTypes,
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var saleSelected = '';
            var netSelecte = '';
            if(type == 'datechange'){
                $('#dcNet_currency_type').empty();
                $('#dcSale_currency_type').empty();
                $('#dccNet_currency_type').empty();
                $('#dccSale_currency_type').empty();
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
                  $('#dcSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#dccSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");

                  $('#dcNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#dccNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type == 'refund'){
                $('#rfdNet_currency_type').empty();
                $('#rfdcNet_currency_type').empty();
                $('#rfdcANet_currency_type').empty();
                $('#rfdSale_currency_type').empty();
                $('#rfdcSale_currency_type').empty();
                $('#rfdcASale_currency_type').empty();
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
                  $('#rfdSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#rfdcSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#rfdcASale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");

                  $('#rfdNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#rfdcNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#rfdcANet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type == 'Updticket'){
                $('#updNet_currency_type').empty();
                $('#updSale_currency_type').empty();
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
                  $('#updNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#updSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type == 'updateDateChange'){
                $('#updDcNet_currency_type').empty();
                $('#updDcSale_currency_type').empty();
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
                  $('#updDcNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#updDcSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }else if(type == 'updateRefund'){
                $('#updRfdNet_currency_type').empty();
                $('#updRfdSale_currency_type').empty();
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
                  $('#updRfdNet_currency_type').append("<option " + netSelecte + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                  $('#updRfdSale_currency_type').append("<option " + saleSelected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
            }
        },
    });
    }
</script>
</html>
