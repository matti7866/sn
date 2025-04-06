<?php
  include 'header.php';
?>
<title>Change/Refund</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div class="container-fluid">
<div class="row">
<div class="col-md-12">
<div style="margin-left:30px; margin-right:30px; margin-top:10px" class="card" id="todaycard">
  <div class="card-header">
    <h2><b>Date Change/<span style="color:#C66">Refund</span></b></h2>
  </div>
  <div class="card-body">
    <div class="row">
       <div class="col-md-4">
          <form class="form-inline">
            <div class="form-group mb-2">
              <label for="staticEmail2" class="sr-only">PNR:</label>
              <input type="text" class="form-control" autofocus="autofocus" id="Pnr" placeholder="Enter Pnr">
            </div>
            <button type="button" onclick="getReport('Pnr')" class="btn btn-dark mb-2 ml-3">Search</button>
          </form>
       </div>
    </div>
      <br/>
      <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table table-striped table-hover ">
          <thead class="thead-dark">
            <tr>
              <th>S#</th>
              <th width="184">Customer Name</th>
              <th width="184">Passenger Name</th>
              <th >Issuance Date</th>
              <th>PNR</th>
              <th >Sale</th>
              <th >Supplier</th>
              <th >Origin</th>
              <th>Destination</th>
              <th>Transaction Type</th>
              <th class='text-center'>Action</th>
            </tr>
          </thead>
          <tbody id="datechangeTbl">
          
                    
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
<div class="modal fade" id="dateChangeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Date Change</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
        <input type="hidden"  class="form-control" id="ticketID">
        <div class="form-group row">
          <label for="staticEmail" class="col-sm-3 col-form-label">Extend Date:</label>
          <div class="col-sm-9">
            <input type="date"  class="form-control"  id="extendedDate">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" id="net_price" placeholder="Net Price">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" id="sale_price" placeholder="Sale Price">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier:</label>
          <div class="col-sm-9">
              <select class="form-control" name="supplier" id="supplier"></select>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Remarks:</label>
          <div class="col-sm-9">
            <textarea class="form-control" id="remarks" rows="3"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="SaveDateChange()">Change</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Date Refund Modal -->
<div class="modal fade" id="refundModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Refund</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
        <input type="hidden"  class="form-control" id="rfdticketID">
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price:</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" id="rfdnet_price" placeholder="Net Price">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price:</label>
          <div class="col-sm-9">
            <input type="number" class="form-control" id="rfdsale_price" placeholder="Sale Price">
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="SaveRefund()">Refund</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script>
    $(document).ready(function(){
      getReport('AllToday');
      getSupplier('All','');
    });
    function openChangeModel(ticketID,supplierID){
      event.preventDefault();
      $('#ticketID').val(ticketID);
      getSupplier('ByTicket',supplierID);
      $('#dateChangeModel').modal('show');
    }
    function openRefundModel(ticketID){
      event.preventDefault();
      $('#rfdticketID').val(ticketID);
      $('#refundModel').modal('show');
    }
    function SaveDateChange(){
      var extend_date = $('#extendedDate');
      if(extend_date.val() == ""){
        notify('Validation Error!', 'Date is required', 'error');
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
      var addextended_date = "addextended_date";
      var remarks = $('#remarks');
      var ticketID = $('#ticketID');
      var supplier_id = $('#supplier');
      if(supplier_id.val() == "-1"){
        notify('Validation Error!', 'Supplier is required', 'error');
        return;
      }
      $.ajax({
        type: "POST",
        url: "searchChangeController.php",  
        data: {
            AddExtendedDate:addextended_date,
            TicketID:ticketID.val(),
            Extend_Date:extend_date.val(),
            Net_Price:net_price.val(),
            Sale_Price:sale_price.val(),
            Remarks:remarks.val(),
            Supplier_ID:supplier_id.val()
        },
        beforeSend: function () {

            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Saving Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) { 
          var dateChangeRes = JSON.parse(response);
          if(dateChangeRes.msg == 'Success'){
            notify('Success!', dateChangeRes.msg, 'success');
            $('#dateChangeModel').modal('hide');
            ticketID.val('');
            extend_date.val('');
            net_price.val('');
            sale_price.val('');
            remarks.val('');
            getReport('Pnr');
          }else{
            notify('Opps!', dateChangeRes.msg, 'error');
          }
          
        },
      });
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
      var refund = "refund";
      var remarks = $('#rfdremarks');
      var ticketID = $('#rfdticketID');
      $.ajax({
        type: "POST",
        url: "searchChangeController.php",  
        data: {
            Refund:refund,
            TicketID:ticketID.val(),
            Net_Price:net_price.val(),
            Sale_Price:sale_price.val(),
            Remarks:remarks.val(),
        },
        beforeSend: function () {

            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Saving Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
          if(response == 'Success'){
            notify('Success!', response, 'success');
            $('#refundModel').modal('hide');
            ticketID.val('');
            net_price.val('');
            sale_price.val('');
            remarks.val('');
            getReport('Pnr');
          }else{
            notify('Opps!', response, 'error');
          }
          
        },
      });
    }
    function getReport(type){
      var getDateChgRefndRpt = "getDateChgRefndRpt";
      var searchBy = '';
      if(type == "AllToday"){
        searchBy = '';
      }else if(type == "Pnr"){
        searchBy = $('#Pnr').val();
        if(searchBy == ""){
        notify('Validation Error!', 'Pnr is required', 'error');
        return;
      }
      }
      $.ajax({
          type: "POST",
          url: "searchChangeController.php",  
          data: {
              GetDateChgRefndRpt:getDateChgRefndRpt,
              SearchBy:searchBy,
              Type:type
          },
          beforeSend: function () {
              HoldOn.open({
                  theme: 'sk-cube-grid',
                  message: "<h4>Loading Data...</h4>"
              });
          },
          complete: function () {
              HoldOn.close();
          },
          success: function (response) {  
            var dateChangeRpt = JSON.parse(response);
            $('#datechangeTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<dateChangeRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ dateChangeRpt[i].customer_name +"</td>"+
              "<td class='text-capitalize'>"+ dateChangeRpt[i].passenger_name +"</td><td>"+ dateChangeRpt[i].date +"</td><td>"+ 
              dateChangeRpt[i].pnr +"</td><td>"+ dateChangeRpt[i].sale +"</td><td>"+ dateChangeRpt[i].supp_name +"</td>"+
              "<td>"+ dateChangeRpt[i].from_code +"</td><td>"+ dateChangeRpt[i].to_code +"</td>";
              if(dateChangeRpt[i].TransType == 'Issued'){
                finalTable += "<td><button type='button' class='btn btn-success text-white'>" + 
                dateChangeRpt[i].TransType + "</button></td>";
              }else if(dateChangeRpt[i].TransType == 'Date Changed'){
                finalTable += "<td><button type='button' class='btn btn-info text-white'>" + 
                dateChangeRpt[i].TransType + "</button></td>";
              }else if(dateChangeRpt[i].TransType == 'Refund'){
                finalTable += "<td><button type='button' class='btn btn-danger text-white'>" + 
                dateChangeRpt[i].TransType + "</button></td>";
              }
              finalTable += "<td><button type='button'0 onclick='openChangeModel(" + dateChangeRpt[i].ticket + ","+ 
              dateChangeRpt[i].supp_id + ")'" +
              "class='btn btn-primary'>Date Change</button>&nbsp;&nbsp;<button type='button'0 onclick='openRefundModel(" + 
              dateChangeRpt[i].ticket  + ")'" +
              "class='btn btn-danger'>Refund</button> </td>";
              $('#datechangeTbl').append(finalTable);
              finalTable += "</tr>";
              j +=1;
            }
          },
      });
    }
    function getSupplier(type,supplierID){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "searchChangeController.php",  
        data: {
            SELECT_Supplier:select_supplier
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Loading Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            $('#supplier').empty();
            $('#supplier').append("<option value='-1'>--Supplier--</option>");
            var selected = "";
            for(var i=0; i<supplier.length; i++){
              if(type =='ByTicket'){
                if(supplier[i].supp_id == supplierID){
                  selected = "selected";
                }else{
                  selected = "";
                }
              }
              $('#supplier').append("<option " + selected + " value='"+ supplier[i].supp_id +"'>"+ 
              supplier[i].supp_name +"</option>");
            }
        },
    });
}
</script>
</html>

