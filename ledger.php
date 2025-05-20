<?php
  include 'header.php';

  
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<title>Customer Ledger</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }

  require 'connection.php';

  $stmp = $pdo->prepare("SELECT * FROM accounts");
  $stmp->execute();
  $accounts = $stmp->fetchAll();

?>

<div class="container-fluid">
    <div class="row">
      <div class="col-md-12" id="msgPage"></div>
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                        <div class="col-md-4 offset-md-8">
                        <button class="btn btn-danger"  id="printButton" onclick="printLedger()">Print Ledger</button>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-success">Add Payment</button>
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
    </div>
    

<?php include 'footer.php'; ?>



<div class="modal fade" id="exampleModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Customer Payment</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form action="" id="frmAddPayment" method="POST">
          <input type="hidden" name="customerId" id="customerId" value="<?php echo $_GET['id'] ?>">
          <input type="hidden" name="action" id="action" value="addCustomerPayment">
          <div class="row">
            <div class="col-md-12" id="msgAddPayment"></div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="account">Account <span class="text-danger">*</span></label>
              <select name="account" id="account" class="form-select">
                <option value="">Select Account</option>
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID']; ?>"><?php echo $account['account_Name']; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback account"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
              <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
              <div class="invalid-feedback remarks"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" name="amount" id="amount" class="form-control">
              <div class="invalid-feedback amount"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="confirmAmount" class="form-label">Confirm Amount</label>
              <input type="number" name="confirmAmount" id="confirmAmount" class="form-control">
              <div class="invalid-feedback confirmAmount"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button id="mkCustomerPayBtn" type="button" class="btn text-white bg-danger">Save</button>
      </div>
      
    </div>
  </div>
</div>

<script src="Numeral-js-master/numeral.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script>
$(document).ready(function(){

  $('.form-select,input[type=file]').on('change',function(){
    var vl = $(this).val();
    if( vl == '' ){
      $(this).addClass('is-invalid');
    }else{
      $(this).removeClass('is-invalid');
    }
  });
  $('.form-control').on('keyup',function(){
    var vl = $(this).val();
    if( vl == '' ){
      $(this).addClass('is-invalid');
    }else{
      $(this).removeClass('is-invalid');
    }
  });

  $('#mkCustomerPayBtn').on('click',function(){
    var frm = $('#frmAddPayment');
    var btn = $(this);
    var msg = $('#msgAddPayment');
    var modal = $('#exampleModal');

    btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
    var formData = new FormData();
    frm.find('input,select,textarea').each(function(){
      var element = $(this);
      if( element.attr('type') == 'file' ){
        var file = element[0].files[0];
        formData.append(element.attr('name'),file);
      }else if(element.attr('type') == 'checkbox'){
        if( element.prop('checked') ){
          formData.append(element.attr('name'),element.val());
        }
      }else if( element.attr('type') == 'radio' ){
        if( element.prop('checked') ){
          formData.append(element.attr('name'),element.val());
        }
      }else{
        formData.append(element.attr('name'),element.val());
      }
    });
    $.ajax({
      url: 'ledgerController.php',
      method: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      error: function(){
        msg.html('<div class="alert alert-danger">An error occured while saving transaction</div>');
        btn.removeAttr('disabled').html(btn.attr('data-temp'));
      },
      success: function(res){
        btn.removeAttr('disabled').html(btn.attr('data-temp'));
        if(res.status == 'success'){
          $('#msgPage').html('<div class="alert alert-success">'+res.message+'</div>');
          modal.modal('hide');
        }else{
          if( res.message == 'form_errors' ){
            $.each(res.errors,function(key,value){
              $('#'+key).addClass('is-invalid').next('.invalid-feedback').html(value);
            });
          }else{
            msg.html('<div class="alert alert-danger">'+res.message+'</div>');
          }
        } 
      }
    });

  })


getCustomerInfo();
    getTicketReport();
});
function getCustomerInfo(){
    var urlFirstParam = location.search.split('&')[0];
    var id = urlFirstParam.split('=')[1];
    var getCustomerInfo = "getCustomerInfo";
      $.ajax({
          type: "POST",
          url: "viewLedgerController.php",  
          data: {
            GetCustomerInfo:getCustomerInfo,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#name').text(report[0].customer_name);

            $("title").text(report[0].customer_name + " Ledger");
            
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
    function getLedgerCurrency(id,total,customerTotalPaid,remaining,totalRefund){
      var getLedgerCurrency = "getLedgerCurrency";
      $.ajax({
          type: "POST",
          url: "viewLedgerController.php",  
          data: {
            GetLedgerCurrency:getLedgerCurrency,
            ID:id
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#total').text(numeral(total).format('0,0') + ' ' + report[0].currencyName );
            $('#total_paid').text(numeral(customerTotalPaid).format('0,0') + ' ' + report[0].currencyName);
            $('#outstanding_balance').text(numeral(remaining).format('0,0') + ' ' + report[0].currencyName);
            $('#total_refund').text(numeral(totalRefund).format('0,0') + ' ' + report[0].currencyName);
$('#currencyInfo').text(report[0].currencyName);

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
          url: "viewLedgerController.php",  
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
            var totalRefund = 0;
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].TRANSACTION_Type == "Payment"){
                    remaining = remaining - parseInt(report[i].Credit);
                    customerTotalPaid += parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize customAbc' style='-webkit-print-color-adjust: exact;'>"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' style='-webkit-print-color-adjust: exact;' colspan='1'>Remarks:"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+ report[i].Identification + "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Ticket"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Hotel Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='2' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Car Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Date Extension"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Refund"){
                    totalRefund +=  parseInt(report[i].Credit);
                    total += parseInt(report[i].Debit);
                    remaining = remaining - parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>:"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Loan"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa Fine"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Report"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Removal"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Residence"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }else{
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "<td style='-webkit-print-color-adjust: exact;'>"+ numeral(remaining).format('0,0') +"</td></tr>";
                }
                
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            
              
              
            }
            getLedgerCurrency(curID,total,customerTotalPaid,remaining,totalRefund);
            
          
            

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
