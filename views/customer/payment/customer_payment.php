<?php
  require_once '../../layout/header.php';
?>
 <link href="./customer_payment.css" rel="stylesheet">
 <style>
    .dropdownModal .select2-container {
      z-index: 1050;
    }
  </style>
<title>Customer Payment Report</title>
<?php
  require_once __DIR__ . '/../../layout/nav.php';
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  if (!isset($_SESSION['user_id'])) {
    header('location:../../../login.php');
  }
  require_once '../../../api/connection/index.php';
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = 
  :role_id AND page_name = 'Customer Payment' ";
  $stmt = $conn->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select =  $records[0]['select'];
    $insert = $records[0]['insert'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0 && $insert == 0 && $update == 0 && $delete == 0){
      header('location:../../error_pages/permissionDenied.php');
    }
?>
  <div class="container-fluid">
    <div class="ui-sortable">
        <div class="panel panel-inverse" data-sortable-id="table-basic-7">
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">Customer Payments <span class="badge bg-success ms-5px"><i class="fa fa-arrow-down"></i></span></h4>
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="panel-body">
            <div class="row">
                        <div class="col-lg-2" style="margin-top:38px;">
                            <input class="form-check-input" type="checkbox"  id="dateSearch" name="dateSearch" value="option1">
                            <label class="form-check-label" for="exampleCheck1">Search By Date</label>
                        </div>
                        <div class="col-lg-2 ">
                            <label for="staticEmail"  class="col-form-label lg-ml-minus-70">From:</label>
                            <input type="text"  class="form-control lg-ml-minus-70" name="fromdate"  id="fromdate">
                        </div>
                        <div class="col-lg-2 ">
                            <label for="staticEmail" class="col-form-label lg-ml-minus-70">To:</label>
                            <input type="text"  class="form-control lg-ml-minus-70 " name="todate"  id="todate">
                        </div>
                        <div class="col-lg-2 lg-ml-minus-70">
                            <label for="staticEmail" class="col-form-label ">Customer:</label>
                            <select class="form-control  js-example-basic-single" style="width:100%;" name="customer_id"  id="customer_id"></select>
                        </div>
                        <div class="col-lg-2">
                            <label for="staticEmail" class="col-form-label">Action:</label>
                            <button type="button" style="width:100%" class="btn btn-block btn-info"  onclick="getpaymentReport()">
                                <i class="fa fa-search"> </i> Search 
                            </button>
                        </div>
                        <div class="col-lg-2" style="margin-top:35px">
                            <?php if($insert == 1 ) { ?>
                                <button type="button" class="btn btn-block btn-danger"  style="width:100%;" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    <i class="fa fa-plus"> </i> Customer Payment
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive ">
                                <table id="myTable"  class="table  table-striped table-hover ">
                                    <thead >
                                        <tr class="text-center" >
                                            <th>S#</th>
                                            <th >Customer Name</th>
                                            <th >Date Time</th>
                                            <th >Payment Amount</th>
                                            <th >Remarks</th>
                                            <th >Employee Name</th>
                                            <th >Account</th>
                                            <?php if($update == 1 || $delete == 1) { ?>
                                                <th>Action</th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody id="PaymentReportTbl">
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

<!-- Insert Payment Modal -->
<div id="customer-payment-modal"></div>
<div id="uploaderArea"></div>
<!-- Update Modal -->
<div class="modal dropdownModal fade" id="updexampleModal"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color:#20252a;" >
            <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fa fa-money" aria-hidden="true"></i> <i>Update Customer Payment</i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <div class="modal-body">
            <form id="CountryNameForm"> 
              <input type="hidden" id="paymentID" name="paymentID" />
             <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
                <div class="col-sm-8">
                <select class="form-control" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Recieved:</label>
                <div class="col-sm-8">
                  <input type="number" class="form-control col-sm-"  name="updpayment_recieved" id="updpayment_recieved" placeholder="Payment Recieved">
                </div>
              </div>
              <div class="row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-sticky-note-o"></i> Remarks:</label>
                <div class="col-sm-8">
                  <textarea class="form-control col-sm-" rows="3"  name="updRemarks" id="updRemarks" placeholder="Remarks..."></textarea>
                </div>
              </div>
              <div class="form-group row mb-2">
                <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
                <div class="col-sm-5" id="updaccountSection">
                  <select class="form-control" style="width:100%" onchange="getConditionalCur('byUpdate')" name="updaccount_id" id="updaccount_id"></select>
                </div>
                <div class="col-sm-3 " id="updcurrencySection">
                  <select class=" form-control col-sm-4"  style="width:100%"  id="updpayment_currency_type" name="updpayment_currency_type" ></select>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
            <button onclick="updateCustomerPayment()" id="updCustomerPayBtn"  type="button"  class="btn text-white bg-danger">Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>`;

  
<?php require_once '../../layout/footer.php'; ?>
<script src="../../components/date/dateFormat.js"></script>
<script src="../../components/select2/search.js"></script>
<script src="../../components/payment/pendingCustomerPayment.js"></script>
<script src="../../components/payment/makePayment.js"></script>
<script src="../../components/payment/showingDefaultCurrency.js"></script>
<script src="../../components/payment/getInsertCustomerPaymentModal.js"></script>
<script src="../../components/payment/generateCustomerPaymentReceipt.js"></script>
<script src="../../components/payment/uploaderForm.js"></script>
<script src="../../helper/file_validator.js"></script>
<script>
    $(document).ready(function(){
      drawCustomerPaymentModal();
      // get the date range
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      // the format date function initailze and set the default value of bootstrap datepicker
      formatDate(fromdate);
      formatDate(todate);
      /* Initalizing dropdown for search functionality
        -- The search function get the dropdown for initalization
        -- Second Argument is placeholder 
        -- Thired argument is the url for calling the function to get the search data
      */
      search($('#customer_id'),'Type customer name', '../../../controller/customer/customer-management/index.php','Populate_Customer_Dropdown', null);
      search($('#addcustomer_id'),'Type customer name', '../../../controller/customer/customer-management/index.php','Populate_Customer_Dropdown', $('#exampleModal'));
      search($('#addaccount_id'), 'Type account name', '../../../controller/accounts/account-management/account-management.php', 'Populate_Account_Dropdown',$('#exampleModal'));
      getpaymentReport();
      UploaderComponent();
      document.getElementById("uploaderFile").onchange = function(event) {
        $('#submitUploadForm').click();
      };
    });
    /* As the name of the function suggests this function conditionally show the currencies only if account is cash because we have 
    to know that if account is cash then what is the currency of the account
    */
    function getConditionalCur(type){
      if(type === "byInsert"){
        var accountName = $("#addaccount_id").find('option:selected').text();
        var accountID = $("#addaccount_id").find('option:selected').val();
        $('#accountSection').removeClass('col-sm-8');
        $('#accountSection').addClass('col-sm-5');
        $('#currencySection').removeClass('d-none');
        if(accountName === "Cash"){
          $('#payment_currency_type').empty();
          $('#payment_currency_type').attr('disabled', false);
          search($('#payment_currency_type'), 'Type currency name', '../../../controller/currencies/currency-management/currency-management.php', 'Populate_Currency_Dropdown',$('#exampleModal'));
        }else{
          getCurrencyByAccountID(accountID,'../../../controller/accounts/account-management/account-management.php',$('#payment_currency_type'),$('#exampleModal'));
        }
      }else if(type === "byUpdate"){
        var accountName = $("#updaccount_id").find('option:selected').text();
        var accountID = $("#updaccount_id").find('option:selected').val();
        if(accountName === "Cash"){
          $('#updpayment_currency_type').empty();
          $('#updpayment_currency_type').attr('disabled', false);
          search($('#updpayment_currency_type'), 'Type currency name', '../../../controller/currencies/currency-management/currency-management.php', 'Populate_Currency_Dropdown',$('#updexampleModal'));
        }else{
          getCurrencyByAccountID(accountID,'../../../controller/accounts/account-management/account-management.php',$('#updpayment_currency_type'),$('#updexampleModal'));
        }
        
      }  
    }
    // delete customer payment
    function Delete(ID){
      var Delete = "Delete";
      $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this customer payment',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "../../../controller/customer/customer-payment/customer-payment.php",  
                    data: {
                      Delete:Delete,
                      ID:ID,
                    },
                    success: function (response) {
                    var data = JSON.parse(response);  
                    if(data.message == 'Success'){
                      notify('Success!', 'Record deleted successfully', 'success');
                      getpaymentReport();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    var response = JSON.parse(jqXHR.responseText);
                    notify('Error!', response.error, 'error');
                    $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
                  }
                });
                }
            },
            close: function () {
            }
        }
      });
    }
    // edit customer payment
    function GetDataForUpdate(id){
      $.ajax({
              type: "POST",
              url: "../../../controller/customer/customer-payment/customer-payment.php",  
              data: {
                EditCustomerPayment:'EditCustomerPayment',
                ID:id,
            },
            success: function (response) {  
                var data = JSON.parse(response);
                $('#paymentID').val(id);
                $('#updRemarks').val(data[0].remarks);
                search($('#updcustomer_id'),'Type customer name', '../../../controller/customer/customer-management/index.php','Populate_Customer_Dropdown', $('#updexampleModal'));
                $('#updcustomer_id').append("<option value='"+ data[0].customer_id +"'>" + data[0].customer_name +"</option>");
                $('#updpayment_recieved').val(data[0].payment_amount);
                search($('#updaccount_id'), 'Type account name', '../../../controller/accounts/account-management/account-management.php', 'Populate_Account_Dropdown',$('#updexampleModal'));
                $('#updaccount_id').append("<option value='"+ data[0].account_ID +"'>" + data[0].account_Name +"</option>");
                if(data[0].account_Name !== "Cash"){
                  $('#updpayment_currency_type').empty();
                  $('#updpayment_currency_type').attr('disabled', true);
                  $('#updpayment_currency_type').append("<option value='"+ data[0].currencyID +"'>" + data[0].currencyName +"</option>");
                  $('#updpayment_currency_type').select2({
                    dropdownParent: '#updexampleModal'
                  })
                }else{
                  search($('#updpayment_currency_type'), 'Type currency name', '../../../controller/currencies/currency-management/currency-management.php', 'Populate_Currency_Dropdown',$('#updexampleModal'));
                  $('#updpayment_currency_type').append("<option value='"+ data[0].currencyID +"'>" + data[0].currencyName +"</option>");
                }
                $('#updexampleModal').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
              var response = JSON.parse(jqXHR.responseText);
              notify('Error!', response.error, 'error');
              $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
            }
      });
    }
    // get payment report
    function getpaymentReport(){
      searchTerm = 'TodaysPayment';
      var customer_id = $('#customer_id');
      var fromdate = $('#fromdate');
      var todate = $('#todate');
      var dateSearch = $('#dateSearch');
      if(dateSearch.is(':checked') && customer_id.val() != null){
        searchTerm = "DateAndCusWise";
      }else if(dateSearch.is(':checked') && customer_id.val() == null){
        searchTerm  = "DateWise";
      }else if(!dateSearch.is(':checked') && customer_id.val() != null ){
        searchTerm  = "CusWise";
      }
      $.ajax({
          type: "POST",
          url: "../../../controller/customer/customer-payment/customer-payment.php",  
          data: {
            CustomerID:customer_id.val(),
            SearchTerm:searchTerm,
            Fromdate:fromdate.val(),
            Todate:todate.val()
          },
          success: function (response) {  
            customer_id.val(-1).trigger('change.select2');
            var cusPaymentRpt = JSON.parse(response);
              if(cusPaymentRpt.length == 0){
                // empty the table
                $('#PaymentReportTbl').empty();
                // numbers of column to span accross
                var colspan = 7;
                // check if either update or delete allowance is given
                <?php if($update == 1 || $delete == 1) { ?>
                    colspan = 8;
                <?php } ?>
                var finalTable = "<tr><td colspan='"+ colspan +"'><h5 class='text-center'>No data found...</h5></td></tr>";
                $('#PaymentReportTbl').append(finalTable);
              }else{
                $('#PaymentReportTbl').empty();
                var j = 1;
                var finalTable = "";
                var total = {};
                if(Array.isArray(cusPaymentRpt)){
                  for(var i=0; i<cusPaymentRpt.length; i++){
                    if(total.hasOwnProperty(cusPaymentRpt[i].currencyName)) {
                      total[cusPaymentRpt[i].currencyName] += parseInt(cusPaymentRpt[i].payment_amount);
                    } else {
                      total[cusPaymentRpt[i].currencyName] = parseInt(cusPaymentRpt[i].payment_amount);
                    }
                    finalTable += "<tr class='text-center'><th scope='row'>" + j + "</th><td class='text-capitalize'>"+
                    cusPaymentRpt[i].customer_name +"</td><td class='text-capitalize'>"+ cusPaymentRpt[i].datetime + "</td><td>"+ 
                    numeral(cusPaymentRpt[i].payment_amount).format('0,0') + ' ' + cusPaymentRpt[i].currencyName + 
                    "</td><td>"+cusPaymentRpt[i].remarks + "</td><td>"+cusPaymentRpt[i].staff_name + "</td><td>" +
                    cusPaymentRpt[i].account_Name + "</td>";
                    <?php if($update == 1 || $delete == 1) { ?>
                      finalTable += "<td style='width:220px;'>";
                    <?php } ?>
                    if(cusPaymentRpt[i].invoiceFile === '' && parseInt(cusPaymentRpt[i].invoiceDecision) !== 0){
                      finalTable +="<button style='position:relative'  type='button'0 onclick='uploadFile("+ cusPaymentRpt[i].invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-upload text-success fa-2x' aria-hidden='true'></i></button><button type='button'0 onclick='redirectToReceiptDetails("+ cusPaymentRpt[i].invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-eye text-info fa-2x' aria-hidden='true'></i></button>";
                    }
                    else if(cusPaymentRpt[i].invoiceFile !== '' && parseInt(cusPaymentRpt[i].invoiceDecision) !== 0){
                      finalTable +="<div style='position:absolute;right:230px;cursor:pointer; ' ><i  onclick='deletePaymentReceiptFile("+ 
                      cusPaymentRpt[i].invoiceDecision +")' class='fa fa-trash-o  text-danger fa-1x'></i></div><button style='position:relative; padding:0' type='button'  onclick='downloadPaymentReceipt("+ cusPaymentRpt[i].invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-download text-success fa-2x' aria-hidden='true'></i></button>";
                    }
                   
                    else{
                      finalTable +="<button type='button'0 id='reportCustomerPaymentBtn"+ cusPaymentRpt[i].pay_id +"' onclick='storeCusPaymetReceipt("+ 
                      cusPaymentRpt[i].pay_id +")'" +
                      "class='btn'><i class='fa fa-bar-chart text-info fa-2x' aria-hidden='true'></i></button>";
                    }
                    <?php if($update == 1) { ?>
                    finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
                    cusPaymentRpt[i].pay_id +")'" +
                    "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
                    <?php } ?>
                    <?php if($delete == 1) { ?>
                      finalTable +="<button type='button'0 onclick='Delete("+ 
                      cusPaymentRpt[i].pay_id +")'" +
                      "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
                    <?php } ?>
                     
                    <?php if($update == 1 || $delete == 1) { ?>
                      finalTable += "</td>";
                    <?php } ?>
                    finalTable += "</tr>";
                    j +=1;
                  }
                }else{
                  if(total.hasOwnProperty(cusPaymentRpt.currencyName)) {
                      total[cusPaymentRpt.currencyName] += parseInt(cusPaymentRpt.payment_amount);
                  } else {
                      total[cusPaymentRpt.currencyName] = parseInt(cusPaymentRpt.payment_amount);
                  }
                  finalTable += "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ cusPaymentRpt.customer_name
                  +"</td><td class='text-capitalize'>"+ cusPaymentRpt.datetime + "</td><td>"+ 
                  numeral(cusPaymentRpt.payment_amount).format('0,0') + ' ' + cusPaymentRpt.currencyName + 
                  "</td><td>"+ cusPaymentRpt.remarks + "</td><td>"+cusPaymentRpt.staff_name + "</td><td>"+ 
                  cusPaymentRpt.account_Name + "</td>";
                  <?php if($update == 1 || $delete == 1) { ?>
                    finalTable += "<td style='width:220px;'>";
                  <?php } ?>
                  if(cusPaymentRpt.invoiceFile === '' && parseInt(cusPaymentRpt.invoiceDecision) !== 0){
                      finalTable +="<button style='position:relative' type='button'0 onclick='uploadFile("+ cusPaymentRpt.invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-upload text-success fa-2x' aria-hidden='true'></i></button><button type='button'0 onclick='redirectToReceiptDetails("+ cusPaymentRpt.invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-eye text-info fa-2x' aria-hidden='true'></i></button>";
                    }else if(cusPaymentRpt.invoiceFile !== '' && parseInt(cusPaymentRpt.invoiceDecision) !== 0){
                      finalTable +="<div style='position:absolute;right:230px;cursor:pointer; ' ><i onclick='deletePaymentReceiptFile("+ cusPaymentRpt.invoiceDecision +")' class='fa fa-trash-o text-danger fa-1x'></i></div><button style='position:relative; padding:0' type='button'  onclick='downloadPaymentReceipt("+ cusPaymentRpt.invoiceDecision +")'" +
                      "class='btn'><i class='fa fa-download text-success fa-2x' aria-hidden='true'></i></button>";
                    }else{
                      finalTable +="<button type='button'0 id='reportCustomerPaymentBtn"+ cusPaymentRpt.pay_id +"' onclick='storeCusPaymetReceipt("+ 
                      cusPaymentRpt.pay_id +")'" +
                      "class='btn'><i class='fa fa-bar-chart text-info fa-2x' aria-hidden='true'></i></button>";
                    }
                  <?php if($update == 1) { ?>
                    finalTable += "<button  type='button'0 onclick='GetDataForUpdate("+ 
                    cusPaymentRpt.payment_id +")'" +
                    "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
                  <?php } ?>
                  <?php if($delete == 1) { ?>
                    finalTable +="<button type='button'0 onclick='Delete("+ 
                    cusPaymentRpt.payment_id +")'" +
                    "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button>";
                  <?php } ?>
                  <?php if($update == 1 || $delete == 1) { ?>
                    finalTable += "</td>";
                  <?php } ?>
                  finalTable += "</tr>";
                  j +=1;
                }
                let colspan = 3;
                if (<?php echo $update; ?> == 1 || <?php echo $delete; ?> == 1) {
                    colspan = 5;
                }
                for (let key in total) {
                  let value = total[key];
                  finalTable +='<tr><td></td><td></td><td></td><td style="padding-left:55px" colspan="'+ colspan +'"><b>'+  numeral(value).format('0,0')  +  
                  ' ' + key + '</b></td></tr>';
                }
                $('#PaymentReportTbl').append(finalTable);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            var response = JSON.parse(jqXHR.responseText);
            notify('Error!', response.error, 'error');
            $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
          }
      });
    }
    // Update customer payment
    function updateCustomerPayment(){ 
        var paymentID = $('#paymentID'); 
        var updcustomerID =  $("#updcustomer_id");
        var updpaymentAmount = $('#updpayment_recieved');
        var updaccountID = $('#updaccount_id');
        var updcurrencyID = $('#updpayment_currency_type');
        var updCustomerPayBtn = $("#updCustomerPayBtn");
        if(paymentID.val() === '' || paymentID.val() === null || paymentID.val() === 'undefined'){
            notify('Validation Error!', "Something went wrong! Please refresh the page and try again", 'error');
            return;
        }
        if(updcustomerID.val()  === null){
            notify('Validation Error!', "Customer is required", 'error');
            return;
        }
        if(isNaN(parseInt(updpaymentAmount.val())) || parseInt(updpaymentAmount.val()) < 1) {
            notify('Validation Error!', "Payment amount must be a valid number", 'error');
            return;
        }
        if(updaccountID.val() === null){
            notify('Validation Error!', "Account is required", 'error');
            return;
        }
        if(updaccountID.select2('data')[0].text === "Cash" && updcurrencyID.val() === null){
            notify('Validation Error!', "Currency is required", 'error');
            return;
        }
        var  updRemkars = $('#updRemarks');
        $("#updCustomerPayBtn").attr("disabled", true);
        $.ajax({
            type: "POST",
            url:  '../../../controller/customer/customer-payment/customer-payment.php',
            data: {
                UpdatePayment:'UpdatePayment',
                PaymentID: paymentID.val(),
                CustomerID: updcustomerID.val(),
                AccountID: updaccountID.val(),
                PaymentAmount: updpaymentAmount.val(),
                CurrencyID: updcurrencyID.val(),
                Remarks:updRemkars.val()
            },
            success: function (response) {
                var data = JSON.parse(response);
                if(data.message == "Success"){
                    notify('Success!', 'Record successfully updated.', 'success');
                    paymentID.val('');
                    $('#updexampleModal').modal('hide');
                    getpaymentReport();
                    $("#updCustomerPayBtn").attr("disabled", false);
                }else{
                    notify('Error!', data.error, 'error');
                    $("#updCustomerPayBtn").attr("disabled", false);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              var response = JSON.parse(jqXHR.responseText);
              notify('Error!', response.error, 'error');
              $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
            }
        });
    }
    // get the pending payment of customer
    function getCustomerPendingPayment(){
        var customerID = $("#addcustomer_id option:selected").val();
        $('.totalChargeDiv').removeClass('d-none');
        getPendingCustomerPayment(customerID, '../../../controller/customer/customer-payment/customer-payment.php', $('#pendingPaymentSection'))
    }
    // payment
    function makePay(){
        var customerID =  $("#addcustomer_id");
        var paymentAmount = $('#payment_recieved');
        var accountID = $('#addaccount_id');
        var currencyID = $('#payment_currency_type');
        var remarks = $('#remarks');
        makePayment(customerID,paymentAmount,accountID,currencyID, $("#mkCustomerPayBtn"),
        '../../../controller/customer/customer-payment/customer-payment.php',$('#exampleModal'), getpaymentReport, $('.totalChargeDiv'),remarks)    
    }

    function storeCusPaymetReceipt(paymentID){
     
      generateCustomerPaymentReceipt(paymentID,'../../../controller/customer/customer_receipt/generatePaymentReceipt.php');
    }
    function redirectToReceiptDetails(receiptID){
      window.location.href = '../receipt/receiptDetails.php?rcptID=' + receiptID;
    }
    // upload receipt file
    $(document).on('submit', '#uploaderFrm', function(event){
      event.preventDefault();
      var FileID = $('#FileID');
      var uploaderFile = $('#uploaderFile');
      // function that validate if file Id is correct
      checkFileID(FileID);
      // function that validate whether uploader is empty or not
      checkFileUploader(uploaderFile);
      // function that validate the file size
      checkFileSize(uploaderFile);
      // function that check file extension
      checkFileExtension(uploaderFile);
      data = new FormData(this);
      data.append('UploadFile','UploadFile');
        $.ajax({
            type: "POST",
            url: "../../../controller/customer/customer_receipt/storePaymentReceiptFile.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
              var data = JSON.parse(response);
                if(data.message == "Success"){
                    notify('Success!', 'File uploaded successfully', 'success');
                    getpaymentReport();
                    FileID.val('');
                    uploaderFile.val('');
                }else{
                    notify('Error!', data.error, 'error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              var response = JSON.parse(jqXHR.responseText);
              notify('Error!', response.error, 'error');
              $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
            } 
        });
    });
    function downloadPaymentReceipt(id){
      window.location.href = '../../../controller/customer/customer_receipt/downloadPaymentReceipt.php?id='+ id +'';
    }
    function deletePaymentReceiptFile(id){
      $.confirm({
        title: 'Delete!',
        content: 'Do you want to delete this receipt',
        type: 'red',
        typeAnimated: true,
        buttons: {
            tryAgain: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function(){
                  $.ajax({
                    type: "POST",
                    url: "../../../controller/customer/customer_receipt/deleteCustomerReceipt.php",  
                    data: {
                      deletePaymentReceiptFile:"deletePaymentReceiptFile",
                      ID:id,
                    },
                    success: function (response) {
                    var data = JSON.parse(response);  
                    if(data.message == 'Success'){
                      notify('Success!', 'Record deleted successfully', 'success');
                      getpaymentReport();
                    }else{
                      notify('Opps!', response, 'error');
                    }
                  },
                  error: function(jqXHR, textStatus, errorThrown) {
                    var response = JSON.parse(jqXHR.responseText);
                    notify('Error!', response.error, 'error');
                    $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
                  }
                });
                }
            },
            close: function () {
            }
        }
      });
    }

</script>
</body>
</html>
