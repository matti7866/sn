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
<title>Hawala Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Hawala' ";
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
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
    <div class="card-header">
      <h2><b><i class="fa fa-money"></i> Hawala<span style="color:#C66"> Report</span></b></h2>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
        <form id="serarchForm">
        <div class="row">
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Customer:</label>
            <select class="form-control js-example-basic-single" style="width:100%" name="customer_id" id="customer_id"></select>
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Sender Name:</label>
            <input type="text" placeholder="Sender Name" class="form-control" name="sender_name" id="sender_name">
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Reciever Name:</label>
            <input type="text" placeholder="Reciever Name" class="form-control" name="reciver_name" id="reciver_name">
          </div>
          <div class="col-md-3">
            <label for="staticEmail" class="col-form-label">Action:</label>
            <input type="button" value="Search" class="form-control btn btn-danger" onclick="searchHawala()">
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
              <th>Supplier name</th>
              <th>Sender Name</th>
              <th>Reciever Name</th>
              <th>Date Time </th>
              <th>Net Price</th>
              <th>Supplier Commission</th>
              <th>Sale Price</th>
              <th>Customer Commission</th>
              <th>Send From</th>
              <th>Send To</th>
              <th>Recieved By</th>
              <?php if($update == 1 || $delete == 1) { ?>
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
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="hawalaID" name="hawalaID">
        <div id="ticketSection">
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer Name:</label>
          <div class="col-sm-9">
          <select class="form-control  js-example-basic-single" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Name:</label>
          <div class="col-sm-9">
            <select class="form-control  js-example-basic-single" style="width:100%" id="supplier_id" name="supplier_id" ></select>
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sender Name:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Sender Name" class="form-control" name="updsender_name" id="updsender_name">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Reciever Name:</label>
          <div class="col-sm-9">
            <input type="text" placeholder="Reciever Name" class="form-control" name="updreciver_name" id="updreciver_name">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Net Price</label>
          <div class="col-sm-9">
            <input type="number" placeholder="Net Price" class="form-control" name="net_price" id="net_price">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Supplier Commission</label>
          <div class="col-sm-9">
            <input type="number" placeholder="Supplier Commission" class="form-control" name="supplier_commission" id="supplier_commission">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Sale Price</label>
          <div class="col-sm-9">
            <input type="number" placeholder="Sale Price" class="form-control" name="sale_price" id="sale_price">
          </div>
        </div>
        <div class="form-group row">
          <label for="inputPassword" class="col-sm-3 col-form-label">Customer Commission</label>
          <div class="col-sm-9">
            <input type="number" placeholder="Customer Commission" class="form-control" name="customer_commission" id="customer_commission">
          </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">From Country:</label>
            <div class="col-sm-9">
                <select class="form-control  js-example-basic-single" style="width:100%" id="from_country" name="from_country" spry:default="select one"></select>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-3 col-form-label">To Country:</label>
            <div class="col-sm-9">
                <select class="form-control  js-example-basic-single" style="width:100%" id="to_country" name="to_country" spry:default="select one"></select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
      $('.js-example-basic-single').select2();
  });
function searchHawala(){
  var searchHawala = "searchHawala";
  var customer_id = $('#customer_id');
  var searchTerm = '';
  var sender_name = $('#sender_name');
  var reciver_name = $('#reciver_name');
  if(customer_id.val() != '-1' && sender_name.val() !='' && reciver_name.val() !=''){
      searchTerm = 'byAllTerms'; 
  }else if(customer_id.val() != '-1' && sender_name.val() !='' && reciver_name.val() ==''){
    searchTerm = 'byCusSdr'; 
  }else if(customer_id.val() != '-1' && sender_name.val() =='' && reciver_name.val() !=''){
    searchTerm = 'byCusRcr'; 
  }else if(customer_id.val() == '-1' && sender_name.val() !='' && reciver_name.val() !=''){
    searchTerm = 'bySenRec'; 
  }else if(customer_id.val() == '-1' && sender_name.val() !='' && reciver_name.val() ==''){
    searchTerm = 'bySend'; 
  }else if(customer_id.val() == '-1' && sender_name.val() =='' && reciver_name.val() !=''){
    searchTerm = 'byRec'; 
  }else if(customer_id.val() != '-1' && sender_name.val() =='' && reciver_name.val() ==''){
    searchTerm = 'byCus'; 
  }else{
        notify('Validation Error!', 'Please select at least one search option ', 'error');
        return;
  }
  $.ajax({
        type: "POST",
        url: "viewhawalaController.php",  
        data: {
            SearchHawala:searchHawala,
            Customer_ID:customer_id.val(),
            SearchTerm:searchTerm,
            Sender_Name:sender_name.val(),
            Reciver_Name:reciver_name.val()
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
            console.log(response);
          var hawalaRpt = JSON.parse(response);
          if(hawalaRpt.length === 0){
            $('#LoanReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td>Record Not Found</td><td></td><td></td><td></td><td></td><td></td><td></td>";
            <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td></td>";
            <?php } ?>
              finalTable +="</tr>";
            $('#LoanReportTbl').append(finalTable);
          }else{
            $('#LoanReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var total = 0;
            for(var i=0; i<hawalaRpt.length; i++){
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ hawalaRpt[i].customer_name +
              "</td>"+"<td class='text-capitalize'>"+ hawalaRpt[i].supp_name +"</td><td class='text-capitalize'>"+ 
              hawalaRpt[i].sender_name +"</td><td class='text-capitalize'>"+ hawalaRpt[i].receiver_name + "</td>"+
              "<td>"+ hawalaRpt[i].datetime +"</td><td>"+ hawalaRpt[i].net_amount +"</td><td>"+ hawalaRpt[i].supp_comm +
              "</td><td>"+ hawalaRpt[i].sale_amount +"</td><td>"+ hawalaRpt[i].cust_comm +"</td>"+
              "<td class='text-capitalize'>"+ hawalaRpt[i].fromcountry +"</td><td class='text-capitalize'>"+ 
              hawalaRpt[i].tocountry +"</td><td class='text-capitalize'>"+ hawalaRpt[i].staff_name +"</td>";
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td style='width:130px'>";
              <?php } ?>
              <?php if($update == 1) { ?>
              finalTable += "<button type='button'0 onclick='Update("+ hawalaRpt[i].hawala_id+")'" +
              "class='btn'><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($delete == 1) { ?>
                finalTable +="<button type='button'0 onclick='Delete("+ 
              hawalaRpt[i].hawala_id +")'" +
              "class='btn'><i class='fa fa-trash fa-2x' style='color:#C66' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if($update == 1 || $delete == 1) { ?>
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
function Delete(hawala_id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this reservation for hawala',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "viewhawalaController.php",  
                data: {
                  Delete:Delete,
                  Hawala_ID:hawala_id,
                },
                beforeSend: function () {
                    HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Deleting Data...</h4>"
                    });
                },
                complete: function () {
                  HoldOn.close();
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  searchHawala();
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
function Update(hawala_id){
  var GetUpdHawala = "GetUpdHawala";
  $.ajax({
          type: "POST",
          url: "viewhawalaController.php",  
          data: {
            GetUpdHawala:GetUpdHawala,
            Hawala_ID:hawala_id
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Getting Data...</h4>"
            });
        },
        complete: function () {
            HoldOn.close();
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#hawalaID').val(hawala_id);
            getCustomers('ByUpdate',dataRpt[0].customer_id);
            getSupplier(dataRpt[0].supplier_id);
            $('#updsender_name').val(dataRpt[0].sender_name);
            $('#updreciver_name').val(dataRpt[0].receiver_name);
            $('#net_price').val(dataRpt[0].net_amount);
            $('#supplier_commission').val(dataRpt[0].supp_comm);
            $('#sale_price').val(dataRpt[0].sale_amount);
            $('#customer_commission').val(dataRpt[0].cust_comm);
            getCountries('byFrom',dataRpt[0].country_id_from);
            getCountries('byTo',dataRpt[0].country_id_to);
            $('#updateModel').modal('show');
        },
  });
}
  function SaveUpdate(){
     var hawalaID = $('#hawalaID');
     var updcustomer_id = $('#updcustomer_id').select2('data');
     if(updcustomer_id[0].id == "-1"){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
     }
     updcustomer_id = updcustomer_id[0].id;
     var supplier_id = $('#supplier_id').select2('data');
     if(supplier_id[0].id == "-1"){
        notify('Validation Error!', 'Supplier name is required', 'error');
        return;
     }
     supplier_id = supplier_id[0].id;
     var updsender_name = $('#updsender_name');
     if(updsender_name.val() == ""){
        notify('Validation Error!', 'Sender name is required', 'error');
        return;
     }
     var updreciver_name= $('#updreciver_name');
     if(updreciver_name.val() == ""){
        notify('Validation Error!', 'Reciever name is required', 'error');
        return;
     }
     var net_price = $('#net_price');
     if(net_price.val() == ""){
        notify('Validation Error!', 'Net price is required', 'error');
        return;
     }
     var supplier_commission = $('#supplier_commission');
     if(supplier_commission.val() == ""){
        notify('Validation Error!', 'Supplier commission is required', 'error');
        return;
     }
     var sale_price = $('#sale_price');
     if(sale_price.val() == ""){
        notify('Validation Error!', 'Sale price is required', 'error');
        return;
     }
     var customer_commission = $('#customer_commission');
     if(customer_commission.val() == ""){
        notify('Validation Error!', 'Customer commission is required', 'error');
        return;
     }
     var from_country = $('#from_country').select2('data');
     if(from_country[0].id == "-1"){
        notify('Validation Error!', 'Sender Country is required', 'error');
        return;
     }
     from_country = from_country[0].id;
     var to_country = $('#to_country').select2('data');
     if(to_country[0].id == "-1"){
        notify('Validation Error!', 'Reciever Country is required', 'error');
        return;
     }
     to_country = to_country[0].id;
     var saveUpdateHawala = "saveUpdateHawala";
  $.ajax({
        type: "POST",
        url: "viewhawalaController.php",  
        data: {
            SaveUpdateHawala:saveUpdateHawala,
            HawalaID:hawalaID.val(),
            Updcustomer_id:updcustomer_id,
            Supplier_ID:supplier_id,
            Updsender_Name:updsender_name.val(),
            Updreciver_Name:updreciver_name.val(),
            Net_Price:net_price.val(),
            Supplier_Commission:supplier_commission.val(),
            Sale_Price:sale_price.val(),
            Customer_Commission:customer_commission.val(),
            From_Country:from_country,
            To_Country:to_country
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
            $('#updateModel').modal('hide');
            $('#genralUpdForm')[0].reset();
            $('#updcustomer_id').val(-1).trigger('change.select2');
            $('#from_country').val(-1).trigger('change.select2');
            $('#to_country').val(-1).trigger('change.select2');
            $('#supplier_id').val(-1).trigger('change.select2');
            searchHawala();
          }else if(response == "NoPermission"){
                  window.location.href='pageNotFound.php'
                }else{
            notify('Opps!', response, 'error');
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
            var country = JSON.parse(response);
            if(type == 'byFrom'){
                $('#from_country').empty();
                $('#from_country').append("<option value='-1'>--From Country--</option>");
                var selected = '';
                for(var i=0; i<country.length; i++){
                    if(country[i].country_id == id){
                      selected = 'selected';
                    }else{
                      selected = '';
                    }
                    $('#from_country').append("<option " + selected + " value='"+ country[i].country_id +"'>"+ 
                    country[i].country_name +"</option>");
                }
            }else if(type == 'byTo'){
                $('#to_country').empty();
                $('#to_country').append("<option value='-1'>--To Country--</option>");
                var selected = '';
                for(var i=0; i<country.length; i++){
                    if(country[i].country_id == id){
                      selected = 'selected';
                    }else{
                      selected = '';
                    }
                    $('#to_country').append("<option " + selected + " value='"+ country[i].country_id +"'>"+ 
                    country[i].country_name +"</option>");
                }
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
</script>
</body>
</html>
