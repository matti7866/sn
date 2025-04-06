<?php
  include 'header.php';
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<title>Customer Ledger</title>
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
                        <button class="btn btn-danger pull-right"  id="printButton" onclick="printLedger()">Print Ledger</button>
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
                                    <div class="col-md-6" >
                                     <h1 class="h1 text-danger float-start mt-5" id="logoname" style="font-family: Arizonia;font-size:50px; "><i><b>SN</b></i></h1>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-md-10" style="overflow-wrap: break-word;">
                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:20px; margin-top:3px;margin-bottom:0px"><b>Selab Nadiry Travel & Tourism</b></p>
                                       
                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Address: Frij Murar Shop# 15, Deira, Dubai</p>
                                        
                                        <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px">Contact:+971 4 298 4564,+971 58 514 0764</p>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="col-md-4 offset-md-2 ">
                                <h3 class="text-nowrap text-danger mt-5 h3" style="font-family: 'Montserrat', sans-serif; font-size:18px; margin-left:47px"><b>Customer Information</b></h3>
                                <hr  style="border: none; width:230px; color:black; border-bottom:2px solid black;">
                             
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Name: <span class="text-capitalize" id="name"></span></p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Email: <span id="email"></span></p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Phone: <span id="phone"></span></p>
                                <p  style="font-family: 'Montserrat', sans-serif; font-size:15px;margin-bottom:0px;margin-left:47px">Currency: <span id="currencyInfo"></span></p>
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
                                <th style="-webkit-print-color-adjust: exact;">Passenger Name</th>
                                <th style="-webkit-print-color-adjust: exact;">Establishment Name</th>
                                <th style="-webkit-print-color-adjust: exact;">Due Since</th>
                                <th style="-webkit-print-color-adjust: exact;" >Total Sale</th>
                                <th style="-webkit-print-color-adjust: exact;">Total Fine</th>
                                <th style="-webkit-print-color-adjust: exact;">Total Paid</th>
                                <th style="-webkit-print-color-adjust: exact;">Balance</th>
                            
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
    </div>
    

<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script src="Libraries\momentjs\moment.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
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
          url: "InvoiceController.php",  
          data: {
            GetCustomerInfo:getCustomerInfo,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#name').text(report[0].customer_name);
            
            if(report[0].customer_email == ""){
                
                $('#email').text('Nill');
            }else{
                $('#email').text(report[0].customer_email);
            }
            if(report[0].customer_phone == ''){
                $('#phone').text('Nill');
            }else{
                $('#phone').text(report[0].customer_phone);
            }
            
          },
      });
    }
    function getLedgerCurrency(id,total,customerTotalPaid,remaining){
      var getLedgerCurrency = "getLedgerCurrency";
      $.ajax({
          type: "POST",
          url: "InvoiceController.php",  
          data: {
            GetLedgerCurrency:getLedgerCurrency,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#total').text(numeral(total).format('0,0') + ' ' + report[0].currencyName );
            $('#total_paid').text(numeral(customerTotalPaid).format('0,0') + ' ' + report[0].currencyName);
            $('#outstanding_balance').text(numeral(remaining).format('0,0') + ' ' + report[0].currencyName);
            $('#currencyInfo').text(report[0].currencyName);

          },
      });
    }
function getTicketReport(){
    var urlFirstParam = location.search.split('&')[0];
    var id = urlFirstParam.split('=')[1];
    var curID = location.search.split('&curID=')[1];
    // var urlThiredParam = location.search.split('&fromdate=')[1];
    // var fromdate = urlThiredParam.split('&todate=')[0];
    // var todate = location.search.split('&todate=')[1];
    var GetResidenceReport = "GetResidenceReport";
      $.ajax({
          type: "POST",
          url: "residenceLedgerController.php",  
          data: {
            GetResidenceReport:GetResidenceReport,
            ID:id,
            CurID:curID,
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var balance = 0;
            let totalPaid = 0;
            var customerTotalPaid = 0;
            let totalCharges = 0;
            let totalBalance = 0;
            for(var i=0; i<report.length; i++){
                    let startDate = moment(report[i].dt);
                    let duration = moment.duration(moment().diff(startDate));
                    let elasped  = moment.duration(duration).humanize();
                    totalCharges += parseInt(report[i].sale_price) + parseInt(report[i].fine);
                    customerTotalPaid += parseInt(report[i].residencePayment) + parseInt(report[i].finePayment);  
                    totalPaid = parseInt(report[i].residencePayment) + parseInt(report[i].finePayment);
                    balance = (parseInt(report[i].sale_price) + parseInt(report[i].fine)) -  (parseInt(report[i].residencePayment) + parseInt(report[i].finePayment));
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].main_passenger +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].company_name +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ elasped +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].sale_price).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].fine).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(totalPaid).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(balance).format('0,0') +"</td></tr>";
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            }
            totalBalance = totalCharges - customerTotalPaid;
            getLedgerCurrency(curID,totalCharges,customerTotalPaid,totalBalance);
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
</body>
</html>
