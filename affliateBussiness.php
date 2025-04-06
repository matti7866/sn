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
<title>Customer Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
  <div class="container-fluid" >
    <div class="row mt-5">
      <div class="col-12">
        <h1 class="h1"><i>Affliate Bussiness Payments & Ledger <i class="fa fa-arrow-down text-red"></i></i></h1>
        <div class="card w3-responsive"  id="todaycard">
      
        <!-- Button trigger modal -->
          <div class="card-header text-white" style="background: #3C3B3F;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #605C3C, #3C3B3F);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #605C3C, #3C3B3F); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

">
              <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                  <a class="nav-link active"><i class="fa fa-money text-danger"></i> All</a>
                </li>
                
              </ul>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <select class="form-control js-example-basic-single" style="width:100%"  onchange="getCurrencies('customer')" id="customer_id" >
                </select>
              </div>
              <div class="col-md-2">
                <select class="form-control js-example-basic-single" onchange="getParticularCustomer()"   style="width:100%" id="currency_type" name="currency_type" spry:default="select one"></select>
              </div>
            </div>
            <div class="table-responsive mt-4">
            <table class='table table-striped table-hover text-center'>
              <thead class='thead text-white' style='font-size:13px;background: #3C3B3F;  /* fallback for old browsers */
background: -webkit-linear-gradient(to bottom, #605C3C, #3C3B3F);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to bottom, #605C3C, #3C3B3F); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


'>
                <tr>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Customer Email</th>
                  <th>Customer Phone</th>
                  <th>Pending Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="VisaReportTbl">
              </tbody>
            </table>
        </div>
      </div>
    </div>


<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
  function getCustomers(){

    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "affliateBussinessController.php",  
        data: {
            SELECT_CUSTOMER:select_customer,
        },
        success: function (response) { 
            var customer = JSON.parse(response);
            $('#customer_id').empty();
            $('#customer_id').append("<option value=''>--Select Customer--</option>");
            for(var i=0; i<customer.length; i++){
            $('#customer_id').append("<option value='"+ customer[i].customer_id +"'>"+ 
            customer[i].customer_name +"</option>");
            
            }
        },
    });
}
function getPendingCustomers(customer_id){
    var currency_type = $('#currency_type').val();
    var select_pendingCustomers = "SELECT_PendingCustomers";
    $.ajax({
        type: "POST",
        url: "affliateBussinessController.php",  
        data: {
            SELECT_PENDINGCUSTOMERS:select_pendingCustomers,
            Customer_ID: customer_id,
            Currency_Type:currency_type
        },
        success: function (response) { 
          var pendingSupolierRpt = JSON.parse(response);
          if(pendingSupolierRpt.length == 0){
            $('#VisaReportTbl').empty();
            var finalTable = "<tr><td></td><td></td><td>Record Not Found</td><td></td><td></td></tr>";
            $('#VisaReportTbl').append(finalTable);
          }else{
            var total = 0;
            $('#VisaReportTbl').empty();
            var j = 1;
            var finalTable = "";
            if(Array.isArray(pendingSupolierRpt)){
              for(var i=0; i<pendingSupolierRpt.length; i++){
                total += parseInt(pendingSupolierRpt[i].total);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ pendingSupolierRpt[i].customer_name +"</td>"+
              "<td>"+ pendingSupolierRpt[i].customer_email +"</td><td>"+ pendingSupolierRpt[i].customer_phone +"</td><td>"+ 
              numeral(pendingSupolierRpt[i].total).format('0,0') +"</td>";
              
              finalTable += "<td>";
             
              finalTable += "<a href='affliateLedger.php?id="+ pendingSupolierRpt[i].main_customer + "&curID=" + currency_type + "&affID=" + pendingSupolierRpt[i].affliate_supp_id +"'class='text-primary'><i class='fa fa-eye'></i> View Ledger</a> ";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            }else{
              total += parseInt(pendingSupolierRpt.total);
              finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ pendingSupolierRpt.customer_name +"</td>"+
              "<td>"+ pendingSupolierRpt.customer_email +"</td><td>"+ pendingSupolierRpt.customer_phone +"</td><td>"+ 
              numeral(pendingSupolierRpt.total).format('0,0') +"</td>";
              
              finalTable += "<td>";
             
              finalTable += "<a href='affliateLedger.php?id="+ pendingSupolierRpt.main_customer + "&curID=" + currency_type + "&affID=" + pendingSupolierRpt.affliate_supp_id + "' class='text-primary'><i class='fa fa-eye'></i> View Ledger</a> ";
              
                
            
              finalTable +="</td>";
              
              
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            $('#VisaReportTbl').append("<tr><td></td><td></td><td></td><td></td><td>"+ numeral(total).format('0,0') +"</td><td></td></tr>");
            }
            
          
        },
    });
}


function getParticularCustomer(){
  var cusID = $("#customer_id").val();
  getPendingCustomers(cusID);
}

  $(document).ready(function(){
    $('.js-example-basic-single').select2();
    getCustomers();
    getCurrencies('all');
  });
  function getAccounts(){
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
        type: "POST",
        url: "paymentController.php",  
        data: {
          Select_Accounts:select_Accounts,
        },
        success: function (response) {  
            var account = JSON.parse(response);
              $('#addaccount_id').empty();
              $('#addaccount_id').append("<option value='-1'>--Account--</option>");
              for(var i=0; i<account.length; i++){
                $('#addaccount_id').append("<option value='"+ account[i].account_ID +"'>"+ 
                account[i].account_Name +"</option>");
              }
        },
    });
}

function getCurrencies(type){
    var currencyTypes = "currencyTypes";
    var customer_id = $('#customer_id');
    if(type == "customer"){
      if(customer_id.val() == '-1' || customer_id.val() == '' || customer_id.val() == null){
        type = "all";
     }
    }
    $.ajax({
        type: "POST",
        url: "affliateBussinessController.php",  
        data: {
            CurrencyTypes:currencyTypes,
            Customer_ID:customer_id.val(),
            Type:type
        },
        success: function (response) {  
            var currencyType = JSON.parse(response);
            var selected = "";
                $('#currency_type').empty();
                for(var i=0; i<currencyType.length; i++){
                if(i == 0){
                  selected = "selected";
                  $('#currency_type').append("<option " + selected + " value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }else{
                  $('#currency_type').append("<option value='"+ currencyType[i].currencyID +"'>"+ 
                  currencyType[i].currencyName +"</option>");
                }
                
              }
              if(type =='all'){
                getParticularCustomer();
              }else{
                getParticularCustomer();
              }
        },
    });
    }

</script>
</body>
</html>