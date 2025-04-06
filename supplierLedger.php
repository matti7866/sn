<?php
  include 'header.php';
?>

<link href="bootstrap-4.3.1-dist/css/bootstrap.min.css" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<title>Supplier Ledger</title>
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
        <div class="card border-0" id="todaycard">
        <div class="row">
                        <div class="col-md-4 offset-md-8">
                        <button class="btn btn-danger"  id="printButton" onclick="printLedger()">Print Ledger</button>
                        </div>
                    </div>
        <div id="printThisArea">
        <div class="card-body">
                    
                    
                    <div class="row">
                            
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-4 ">
                                        <img src="logoselab.png"  style="height:180px;width:180px;">
                                    </div>
                                    <div class="col-md-6">
                                     <h1 class="h1 text-danger float-start mt-5" id="logoname" style="font-family: Arizonia;font-size:50px;"><i><b>SN</b></i></h1>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-10" style="overflow-wrap: break-word;">
                                    <p  style="font-family: 'Montserrat', sans-serif; font-size:20px; margin-top:3px;margin-bottom:0px"><b>Selab Nadiry Travel & Tourism</b></p>
                                <p style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Main: Haidari Market 3rd Floor Shop# 227, Kabul, Afghanistan</p>
                                <p style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Second Branch: Frij Murar Shop# 15, Deira, Dubai</p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Kabul Contact:+93(0)786130840,+93(0)744736969</p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Dubai Contact:+971 4 298 4564,+971 58 514 0764</p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4 offset-md-2 ">
                                <h3 class="text-nowrap text-danger mt-5 h3" style="font-family: 'Montserrat', sans-serif; font-size:18px; margin-left:47px"><b>Supplier Information</b></h3>
                                <hr  style="border: none; width:230px; color:black; border-bottom:2px solid black;">
                             
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Name: <span class="text-capitalize" id="name"></span></p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Email: <span id="email"></span></p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Phone: <span id="phone"></span></p>

                                <h3 class="text-nowrap text-danger mt-5 h3" style="font-family: 'Montserrat', sans-serif; font-size:18px;margin-left:47px"><b>Date</b></h3>
                                <hr  style="border: none; width:230px; color:black; border-bottom:2px solid black;">
                                <h3 class="text-nowrap h3" style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-left:47px"><?php  echo date("d-M-Y") ?></h3>
                                
                            </div>
                    </div>
                    <br/>
                    <div class="row">
                    <div class="table-responsive ">
                        <table id="myTable"  class="table  table-striped table-hover table-bordered ">
                          <thead >
                            <tr id="ad" class="bg-danger text-white">
                                <th style="-webkit-print-color-adjust: exact;">S#</th>
                                <th style="-webkit-print-color-adjust: exact;">Transaction Type</th>
                                <th style="-webkit-print-color-adjust: exact;">Passenger Name</th>
                                <th style="-webkit-print-color-adjust: exact;" >Date</th>
                                <th style="-webkit-print-color-adjust: exact;">Identification</th>
                                <th style="-webkit-print-color-adjust: exact;" >Orgin</th>
                                <th style="-webkit-print-color-adjust: exact;">Destination</th>
                                <th style="-webkit-print-color-adjust: exact;">Debit</th>
                                <th style="-webkit-print-color-adjust: exact;">Credit</th>
                                <th style="-webkit-print-color-adjust: exact;">Running Balance</th>
                            
                            </tr>
                        </thead>
                        <tbody id="TicketReportTbl">
          
                    
                         </tbody>
                        </table>
                        </div> 
                    </div>
                    
                    <!--- Beginning Of Total !-->
                    <div class="row">
                    <div class="col-md-12 mt-5">
                                <div class="col-md-8 offset-4">
                                    <p class="font-weight-bold text-right" style="font-size:20px">Total Charges: <span id="total"></span> </p>
                                    <hr/>
                                </div>
                                <div class="col-md-8 offset-4">
                                    <p class="font-weight-bold text-right" style="font-size:20px">Total Paid: <span id="total_paid"></span> </p>
                                    <p class="font-weight-bold text-right" style="font-size:20px">Total Refund: <span id="total_refund"></span> </p>
                                    <hr/>
                                </div>
                                <div class="col-md-8 offset-4">
                                    <p class="font-weight-bold text-right" style="font-size:30px"><b>Outstanding Balance: <span id="outstanding_balance">0</span> </b></p>
                                    <hr/>
                                </div>
                    </div>
                    </div>
                    <!-- End of Totaal !-->
                    </div>
                    <!-- End of This plce to be printed !-->

                    </div>
                    </div>
                    </div>
            </div>
        </div>
    
</body>
<?php include 'footer.php'; ?>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
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
          url: "supplierLedgerController.php",  
          data: {
            GetCustomerInfo:getCustomerInfo,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#name').text(report[0].supp_name);
            
            if(report[0].customer_email == ""){
                
                $('#email').text('Nill');
            }else{
                $('#email').text(report[0].supp_email);
            }
            if(report[0].customer_phone == ''){
                $('#phone').text('Nill');
            }else{
                $('#phone').text(report[0].supp_phone);
            }
            
          },
      });
    }
    function getLedgerCurrency(id,total,customerTotalPaid,remaining,refundAmount){
      var getLedgerCurrency = "getLedgerCurrency";
      $.ajax({
          type: "POST",
          url: "supplierLedgerController.php",  
          data: {
            GetLedgerCurrency:getLedgerCurrency,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#total').text(numeral(total).format('0,0') + ' ' + report[0].currencyName );
            $('#total_paid').text(numeral(customerTotalPaid).format('0,0') + ' ' + report[0].currencyName);
            $('#outstanding_balance').text(numeral(remaining).format('0,0') + ' ' + report[0].currencyName);
            $('#total_refund').text(numeral(refundAmount).format('0,0') + ' ' + report[0].currencyName);
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
          url: "supplierLedgerController.php",  
          data: {
            GetTicketReport:getTicketReport,
            ID:id,
            CurID:curID
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var remaining = 0;
            var total = 0;
            var refundAmount = 0;
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].TRANSACTION_Type == "Payment"){
                    remaining = remaining - parseInt(report[i].Credit);
                    customerTotalPaid += parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize customAbc' style='-webkit-print-color-adjust: exact;'>"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' style='-webkit-print-color-adjust: exact;' colspan='1'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>Payment Details : "+ report[i].remarks +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Ticket"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Hotel Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='2' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Car Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Date Extension"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Refund"){
                    total += parseInt(report[i].Debit);
                    refundAmount += parseInt(report[i].Credit);
                    remaining = remaining - parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa Fine"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Report"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Removal"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Offer Letter Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Insurance Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Labour Card Fee"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "E-Visa Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Change Status Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Medical Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Emirates ID Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa Stamping Cost"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else{
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].passenger_name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }
                
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            
              
              
            }
            getLedgerCurrency(curID,total,customerTotalPaid,remaining,refundAmount);
            
          
            

          },
      });
    }





 function printLedger(){
   //printJS({ printable: 'printThisArea', type: 'html', style: '.table th { background-color: #dc3545 !important;color: white; }.table-striped tbody tr:nth-of-type(odd) td {background-color: rgba(0, 0, 0, .05)!important;}.table tbody tr td.customAbc {background-color: grey !important;color: white;} .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,.col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {float: left;} .col-sm-12 {width: 100%;} .col-sm-11 {width: 91.66666666666666%;} .col-sm-10 {width: 83.33333333333334%;} .col-sm-9 {width: 75%;} .col-sm-8 {width: 66.66666666666666%;} .col-sm-7 {width: 58.333333333333336%;}.col-sm-6 {width: 50%;} .col-sm-5 {width: 41.66666666666667%;} .col-sm-4 {width: 33.33333333333333%;} .col-sm-3 {width: 25%;} .col-sm-2 {width: 16.666666666666664%;} .col-sm-1 {width: 8.333333333333332%;}' })
    printJS({ 
        printable: 'printThisArea', 
        type: 'html', 
       
             css: [
                'bootstrap-4.3.1-dist/css/bootstrap.min.css',
                'customBootstrap.css',
                'https://fonts.googleapis.com/css?family=Arizonia',
                'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
             ],
             targetStyles: ['*'],
             
             
    
    
    
    })
 }
 
</script>
</html>
