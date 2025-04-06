<?php
  include 'header.php';
?>
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
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
/* .dropdown-menu dropdown-menu-end show{
  transform:translate3d(-74.5px, -0px, 0px);
} */
.dropright-container{
  position:relative;
}
.dropright-menu{
  box-shadow:0 0.5rem 1rem rgb(0 0 0 / 15%);
    position: absolute;
    z-index: 1000;
    min-width: 10rem;
    padding: 0.5rem 0;
    top:-35px;
    left:-202px;
    margin: 0;
    font-size: .6875rem;
    color: #2d353c;
    text-align: left;
  
   
    background-color: #fff;
    background-clip: padding-box;
    border: 0 solid rgba(0, 0, 0, .15);
    border-radius: 4px;
}
.dropright-item-options{
  padding:.3125rem .9375rem;
}
.dropright-item-options:hover{ 
  background-color:#e9ecef;
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
        <h1 class="h1"><i>Residence Payments & Ledger <i class="fa fa-arrow-down text-red"></i></i></h1>
        <div class="card w3-responsive"  id="todaycard">
      
        <!-- Button trigger modal -->
          <div class="card-header text-white" style="background: #e52d27;  /* fallback for old browsers */
background: -webkit-linear-gradient(to left, #b31217, #e52d27);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to left, #b31217, #e52d27); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

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
              <div class="col-md-3 offset-4">
                <button type="button" class="btn btn-danger btn-sm w-80px rounded-pill pull-right " onclick="printResidneceRpt()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                <button type="button" class="btn btn-dark btn-sm w-80px rounded-pill pull-right mx-2" onclick="excelToTable()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel</button>
              </div>
            </div>
            <div id="printThisArea">
            <div class="table-responsive mt-4">
            <table class='table table-striped table-hover text-center' id="mainTable"  data-cols-width="10,40,20,20,40">
              <thead class='thead text-white' style='font-size:13px;background: #e52d27;  /* fallback for old browsers */
background: -webkit-linear-gradient(to left, #b31217, #e52d27);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to left, #b31217, #e52d27); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

'>
                <tr>
                  <th>#</th>
                  <th>Customer Name</th>
                  <th>Customer Email</th>
                  <th>Customer Phone</th>
                  <th>Pending Amount</th>
                  <th data-exclude="true" id="actionArea">Action</th>
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
<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script src="Libraries/tableToExcel/tableToExcel.js"></script>
<script src="Libraries\momentjs\moment.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script>
  function getCustomers(){

    var select_customer = "SELECT_Customer";
    $.ajax({
        type: "POST",
        url: "residenceRptController.php",  
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
        url: "residenceRptController.php",  
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
              finalTable = "<tr><td scope='row' class='p-3'>"+ j + "</td><td class='text-capitalize p-3'>"+ 
              pendingSupolierRpt[i].customer_name +"</td><td class='p-3'>"+ pendingSupolierRpt[i].customer_email +
              "</td><td class='p-3'>"+ pendingSupolierRpt[i].customer_phone +"</td><td class='p-3'>"+ 
              numeral(pendingSupolierRpt[i].total).format('0,0') +"</td>";
              finalTable += "<td data-exclude='true' id='hiddenPrintSection" +j + "'>";
              finalTable += "<a target='_blank' href='residenceLedger.php?id="+ pendingSupolierRpt[i].main_customer + "&curID=" + 
              currency_type + "' class='mt-2' style='color:#ED213A' ><i class='fa fa-eye'></i> View Ledger</a> ";
              finalTable +="</td>";  
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            }else{
              total += parseInt(pendingSupolierRpt.total);
              finalTable = "<tr><td scope='row' class='p-3'>"+ j + "</td><td class='text-capitalize p-3'>"+ 
              pendingSupolierRpt.customer_name +"</td><td class='p-3'>"+ pendingSupolierRpt.customer_email +"</td>"+
              "<td class='p-3'>"+ pendingSupolierRpt.customer_phone +"</td><td class='p-3'>"+ 
              numeral(pendingSupolierRpt.total).format('0,0') +"</td>";
              finalTable += "<td data-exclude='true' id='hiddenPrintSection" +j + "'>";
              finalTable += "<a target='_blank' href='residenceLedger.php?id="+ pendingSupolierRpt.main_customer + "&curID=" + 
              currency_type + "' class='mt-2' style='color:#ED213A'><i class='fa fa-file'></i> View Ledger</a>";
              finalTable +="</td>";
              finalTable += "</tr>";
              $('#VisaReportTbl').append(finalTable);
              j +=1;
            }
            $('#VisaReportTbl').append("<tr><td></td><td></td><td></td><td></td><td>"+ numeral(total).format('0,0') +"</td><td id='hiddenPrintSection" + j + "'></td></tr>"); 
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
    getCurrencies('all')
  });
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
        url: "residenceRptController.php",  
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
    function excelToTable(){
      let currency = $('#currency_type').select2('data');
      currency = currency[0].text;
      let sheetName = 'All Residence Report of ' + moment().format('DD-MMMM-YYYY, h:mm:ss') + '.xlsx';
      TableToExcel.convert(document.getElementById("mainTable"),{
        name: sheetName,
        sheet: {
          name: currency + " all residence report"
        }
      });
    }
    function printResidneceRpt(){
      let rowCount = $('#mainTable tr').length;
      let ignoredIDs = ['actionArea'];
      for(let i = 1; i<=rowCount -1; i++){
        ignoredIDs.push('hiddenPrintSection' + i);
      }
      let currency = $('#currency_type').select2('data');
      currency = currency[0].text;
   //printJS({ printable: 'printThisArea', type: 'html', style: '.table th { background-color: #dc3545 !important;color: white; }.table-striped tbody tr:nth-of-type(odd) td {background-color: rgba(0, 0, 0, .05)!important;}.table tbody tr td.customAbc {background-color: grey !important;color: white;} .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,.col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left;} .col-sm-12 {width: 100%;} .col-sm-11 {width: 91.66666666666666%;} .col-sm-10 {width: 83.33333333333334%;} .col-sm-9 {width: 75%;} .col-sm-8 {width: 66.66666666666666%;} .col-sm-7 {width: 58.333333333333336%;}.col-sm-6 {width: 50%;} .col-sm-5 {width: 41.66666666666667%;} .col-sm-4 {width: 33.33333333333333%;} .col-sm-3 {width: 25%;} .col-sm-2 {width: 16.666666666666664%;} .col-sm-1 {width: 8.333333333333332%;}' })
    printJS({ 
        printable: 'printThisArea', 
        type: 'html', 
        header: '<h1 class="text-center"><i class="fa fa-home" aria-hidden="true"></i> All Residence Report</h1>;<h5 class="text-center" style="margin-top:-10px">Curreny: '+ currency +'</h5>',
        ignoreElements:ignoredIDs,
             css: [
                'bootstrap-4.3.1-dist/css/bootstrap.min.css',
                'customBootstrap.css',
                'https://fonts.googleapis.com/css?family=Arizonia',
                'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
             ],
             targetStyles: ['*'],
             
             
    
    
    
    })
 }
</script>
</body>
</html>