<?php
  include 'header.php';
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="receiptCustomBoostrap.css" >

<title>Customer Receipt</title>
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
                <div class="card">
                    <div class="row">
                            <div class="col-md-4 offset-md-8">
                                <button class="btn btn-danger pull-right"  id="printButton" onclick="printLedger()">Print Receipt</button>
                            </div>
                    </div>
                    <input type="hidden" id="customerID">
                    <input type="hidden" id="currencyID">
                    <div id="printThisArea">
                        <div class="card-body">
                                
                                    <div class="row">
                                        <div class="col-fixed-1">
                                            <img src="logoselab.png"  style="height:60px;width:60px;">
                                        </div>
                                        <div class="col-fixed-4 margin-left-40 margin-top-20 ">
                                            <h1  class="companyName"><b>Selab Nadiry Travel & Tourism</b></h1>
                                            <p class="companyInfo">Address: Frij Murar Shop# 15, Deira, Dubai</p>
                                            <p  class="companyInfo">Contact:+971 4 298 4564,+971 58 514 0764</p>
                                        </div> 
                                        <div class="col-fixed-7">
                                            <table id="ReceiptInformation" class="table table-sm table-striped table-hover table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td class="ReceiptInfoColumn"> Receipt #</td>
                                                        <td colspan="2" id="receiptNumber"> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ReceiptInfoColumn"> Customer Name</td>
                                                        <td colspan="2" id="customer_name"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="ReceiptInfoColumn"> Date</td>
                                                        <td id="receiptDate"> </td>
                                                        <td> Currency: <span id="receiptCur"></span></td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>    
                                    </div>
                                    <hr  id="headerLineBreak"/>
                                    <div class="table-responsive d ">
                                        <table id="InfoTable" class="table table-sm table-striped table-hover table-bordered">
                                            <thead >
                                                <tr id="ad" class="bg-danger text-white">
                                                    <th style="-webkit-print-color-adjust: exact;">S#</th>
                                                    <th style="-webkit-print-color-adjust: exact;">Transaction</th>
                                                    <th style="-webkit-print-color-adjust: exact;">Service</th>
                                                    <th style="-webkit-print-color-adjust: exact;">Passenger</th>
                                                    <th style="-webkit-print-color-adjust: exact;">Date</th>
                                                    <th style="-webkit-print-color-adjust: exact;">Sale Price</th>
                                                
                                                </tr>
                                            </thead>
                                            <tbody id="TicketReportTbl">
                                               
                                               
                                                
                                        
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="paymentAndSign">
                                    <div class="row">
                                        <div class="col-md-8 offset-4">
                                            <p class="font-weight-bold text-right" style="font-size:10px">Total Paid: <span id="totalPayment"></span> </p>
                                            <hr/>
                                        </div>
                                        <div class="col-md-8 offset-4">
                                            <p class="font-weight-bold text-right" style="font-size:12px"><b>Outstanding Balance: <span id="outstandingBalance"></span> </b></p>
                                            <hr/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" style="position:relative">
                                                <div class="easerButton" style="position:absolute; top:0; left:63px"><button class="btn btn-info" type="button" onclick="clearSignature('company')"><i class="fa fa-eraser"></i></button></div>
                                                <canvas id="company-signature" width="200" height="50"></canvas>
                                                <hr class="signatureLine"/>
                                                <p class="text-center company-signatureText">Company Signature</p>
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-4" style="position:relative">
                                        <div class="easerButton" style="position:absolute; top:0; left:63px"><button class="btn btn-info" type="button" onclick="clearSignature('customer')"><i class="fa fa-eraser"></i></button></div>
                                                <canvas id="customer-signature" class="float-center" width="200" height="50"></canvas>
                                                <hr class="signatureLine"/>
                                                <p class="text-center company-signatureText">Customer Signature</p>
                                        </div>
                                    </div>
                                    </div>
                                    
                          
                        </div>     
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script>
$(document).ready(function(){
    getCustomerInfo();
    getTicketReport();
});
    function getCustomerInfo(){
        var urlFirstParam = location.search.split('&')[0];
        var id = urlFirstParam.split('=')[1];
        var getCustomerInfo = "getCustomerInfo";
        $.ajax({
            type: "POST",
            url: "ReceiptDetailsController.php",  
            data: {
                GetCustomerInfo:getCustomerInfo,
                ID:id
            },
            success: function (response) {  
                var report = JSON.parse(response);
                $('#receiptNumber').text(report[0].invoiceNumber);
                $('#customer_name').text(report[0].customer_name);
                $('#receiptDate').text(report[0].invoiceDate);
                $('#receiptCur').text(report[0].currencyName);
                $('#customerID').val(report[0].customerID);
                $('#currencyID').val(report[0].invoiceCurrency);
                
            },
        });
    }
    function getTicketReport(){
    var urlFirstParam = location.search.split('&')[0];
    var id = urlFirstParam.split('=')[1];
    var curID = location.search.split('&curID=')[1];
    var getTicketReport = "getTicketReport";
      $.ajax({
          type: "POST",
          url: "ReceiptDetailsController.php",  
          data: {
            GetTicketReport:getTicketReport,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].transactionType == "Payment"){
                    customerTotalPaid += parseInt(report[i].salePrice);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='style='-webkit-print-color-adjust: exact;'>"+ report[i].serviceInfo +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].PassengerName +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].formatedDate+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].salePrice).format('0,0') +"</td></tr>";
                }else{
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].transactionType +"</td>"+
                    "<td class='style='-webkit-print-color-adjust: exact;'>"+ report[i].serviceInfo +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].PassengerName +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].formatedDate+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].salePrice).format('0,0') +"</td></tr>";
                }
                $('#TicketReportTbl').append(finalTable);
                j +=1; 
            }
            $('#totalPayment').text(numeral(customerTotalPaid).format('0,0'));
            getTotal();

          },
      });
    }
    function getTotal(){
    var customer_id = $('#customerID').val();
    var curID = $('#currencyID').val();
    if(customer_id < 1  || customer_id== "" || customer_id== null || customer_id == "undefined" ||
    curID < 1  || curID == "" ||  curID == null ||  curID== "undefined" ){
        notify('Error', 'Something went wrong. Please refresh page', 'error');
        return;
    }
    var getTotal = "getTotal";
      $.ajax({
          type: "POST",
          url: "ReceiptDetailsController.php",  
          data: {
            GetTotal:getTotal,
            CurID: curID,
            CustomerID: customer_id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#outstandingBalance').text(numeral(report).format('0,0'));
          },
      });
    }
    // get canvas element and initialize signature pad
    var canvas = document.getElementById('company-signature');
    var signaturePad = new SignaturePad(canvas);
    var customercanvas = document.getElementById('customer-signature');
    var signaturePad2 = new SignaturePad(customercanvas);
    function clearSignature(type){
        if(type == "company"){
            signaturePad.clear();
        }else if(type == "customer"){
            signaturePad2.clear();
        }
    }

 function printLedger(){
   //printJS({ printable: 'printThisArea', type: 'html', style: '.table th { background-color: #dc3545 !important;color: white; }.table-striped tbody tr:nth-of-type(odd) td {background-color: rgba(0, 0, 0, .05)!important;}.table tbody tr td.customAbc {background-color: grey !important;color: white;} .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,.col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left;} .col-sm-12 {width: 100%;} .col-sm-11 {width: 91.66666666666666%;} .col-sm-10 {width: 83.33333333333334%;} .col-sm-9 {width: 75%;} .col-sm-8 {width: 66.66666666666666%;} .col-sm-7 {width: 58.333333333333336%;}.col-sm-6 {width: 50%;} .col-sm-5 {width: 41.66666666666667%;} .col-sm-4 {width: 33.33333333333333%;} .col-sm-3 {width: 25%;} .col-sm-2 {width: 16.666666666666664%;} .col-sm-1 {width: 8.333333333333332%;}' })
    printJS({ 
        printable: 'printThisArea', 
        type: 'html', 
       
             css: [
                'bootstrap-4.3.1-dist/css/bootstrap.min.css',
                'receiptCustomBoostrap.css',
                'https://fonts.googleapis.com/css?family=Arizonia',
                'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
             ],
             targetStyles: ['*'],
    })
 }
 
</script>
</body>
</html>
