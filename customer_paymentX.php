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

  .bg-graident-lightcrimson {
    background: #1A2980;
    /* fallback for old browsers */
    background: -webkit-linear-gradient(to right, #26D0CE, #1A2980);
    /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to right, #26D0CE, #1A2980);
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }

  .text-graident-lightcrimson {
    color: #1A2980;
    /* fallback for old browsers */
    color: -webkit-linear-gradient(to right, #26D0CE, #1A2980);
    /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to right, #26D0CE, #1A2980);
    /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }
</style>
<title>Customer Payment Report</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Payment' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if ($select == 0) {
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<style>
  #customBtn {
    color: #33001b;
    border-color: #33001b;
  }

  #customBtn:hover {
    color: #FFFFFF;
    background-color: #33001b;
    border-color: #33001b
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
        <div class="card-header bg-light">
          <h2 class="text-graident-lightcrimson"><b><i class="fa fa-fw fa-money text-dark"></i> <i>Customer Payment Report</i> </b></h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-2" style="margin-top:38px;">
              <input class="form-check-input" type="checkbox" id="dateSearch" name="dateSearch" value="option1">
              <label class="form-check-label" for="exampleCheck1">Search By Date</label>
            </div>
            <div class="col-lg-2">
              <label for="staticEmail" class="col-form-label">From:</label>
              <input type="text" class="form-control" name="fromdate" id="fromdate">
            </div>
            <div class="col-lg-2">
              <label for="staticEmail" class="col-form-label">To:</label>
              <input type="text" class="form-control " name="todate" id="todate">
            </div>
            <div class="col-lg-2">
              <label for="staticEmail" class="col-form-label">Customer:</label>
              <select class="form-control  js-example-basic-single" style="width:100%" name="customer_id" id="customer_id"></select>
            </div>
            <div class="col-lg-2">
              <label for="staticEmail" class="col-form-label">Action:</label>
              <button type="button" style="width:100%" class="btn btn-block" id="customBtn" onclick="getpaymentReport()">
                <i class="fa fa-search"> </i> Search
              </button>
            </div>
            <div class="col-lg-2" style="margin-top:35px">


              <?php if ($insert == 1) { ?>
                <button type="button" class="btn btn-block float-end" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="fa fa-plus"> </i> Customer Payment
                </button>
              <?php } ?>

            </div>

          </div>

          <br />
          <div class="row">
            <div class="col-lg-12">
              <div class="table-responsive ">
                <table id="myTable" class="table  table-striped table-hover ">
                  <thead class="text-white bg-graident-lightcrimson">
                    <tr>
                      <th>S#</th>
                      <th>Customer Name</th>
                      <th>Date Time</th>
                      <th>Payment Amount</th>
                      <th>Employee Name</th>
                      <th>Account</th>
                      <?php if ($update == 1 && $delete == 1) { ?>
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

<!-- INSERT Modal -->
<div class="modal fade" id="exampleModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Customer Payment</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm">
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-8">
              <select class="form-control  addCustomerSelect" onchange="getPayments()" style="width:100%" name="addcustomer_id" id="addcustomer_id"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Total Charges:</label>
            <div class="col-sm-8">
              <div class="alert alert-primary" id="total_charge" style="max-height:20vh;overflow-y:scroll" role="alert">
                0
              </div>
            </div>
          </div>
          <div class="row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Recieved:</label>
            <div class="col-sm-5">
              <input type="number" class="form-control col-sm-4" name="payment_recieved" id="payment_recieved" placeholder="Payment Recieved">
            </div>
            <div class="col-sm-3">
              <select class=" form-control addCustomerSelect col-sm-4" style="width:100%" id="payment_currency_type" name="payment_currency_type"></select>
            </div>

          </div>

          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
              <select class="form-control  addCustomerSelect" style="width:100%" name="addaccount_id" id="addaccount_id"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="makePay()" id="mkCustomerPayBtn" type="button" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Customer Payment</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm">
          <input type="hidden" id="paymentID" name="paymentID" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-8">
              <select class="form-control  updCustomerSelect" style="width:100%" name="updcustomer_id" id="updcustomer_id"></select>
            </div>
          </div>
          <div class="row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-barcode"></i> Payment Recieved:</label>
            <div class="col-sm-5">
              <input type="number" class="form-control" name="updpayment_recieved" id="updpayment_recieved" placeholder="Payment Recieved">
            </div>
            <div class="col-sm-3">
              <select class=" form-control updCurrencySelect col-sm-4" style="width:100%" id="updpayment_currency_type" name="updpayment_currency_type"></select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-4 col-form-label"><i class="fa fa-user"></i> Account:</label>
            <div class="col-sm-8">
              <select class="form-control  updCustomerSelect" style="width:100%" name="updaccount_id" id="updaccount_id"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="upMkCustomerPayBtn" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
  $(document).ready(function() {
    $('#fromdate').dateTimePicker();
    $('#todate').dateTimePicker();
    const date = new Date();
    month = date.getMonth() + 1;
    if (month == 1) {
      month = "01";
    } else if (month == 2) {
      month = "02";
    } else if (month == 3) {
      month = "03";
    } else if (month == 4) {
      month = "04";
    } else if (month == 5) {
      month = "05";
    } else if (month == 6) {
      month = "06";
    } else if (month == 7) {
      month = "07";
    } else if (month == 8) {
      month = "08";
    } else if (month == 9) {
      month = "09";
    }
    var day = date.getDate();
    if (day == 1) {
      day = "01";
    } else if (day == 2) {
      day = "02";
    } else if (day == 3) {
      day = "03";
    } else if (day == 4) {
      day = "04";
    } else if (day == 5) {
      day = "05";
    } else if (day == 6) {
      day = "06";
    } else if (day == 7) {
      day = "07";
    } else if (day == 8) {
      day = "08";
    } else if (day == 9) {
      day = "09";
    }
    $('#fromdate').val(date.getFullYear() + '-' + month + '-' + day);
    $('#todate').val(date.getFullYear() + '-' + month + '-' + day);
    getCustomer('all', 0);
    getAccounts('all', 0);
    getCurrencies('addCurrency');
    $(".addCustomerSelect").select2({
      dropdownParent: $("#exampleModal")
    });
    $(".updCustomerSelect").select2({
      dropdownParent: $("#updexampleModal")
    });
    $(".updCurrencySelect").select2({
      dropdownParent: $("#updexampleModal")
    });

    $('.js-example-basic-single').select2();
    getpaymentReport();

  });

  function Delete(ID) {
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
          action: function() {
            $.ajax({
              type: "POST",
              url: "customer_paymentsController.php",
              data: {
                Delete: Delete,
                ID: ID,
              },
              success: function(response) {
                if (response == 'Success') {
                  notify('Success!', response, 'success');
                  getpaymentReport();
                } else {
                  notify('Opps!', response, 'error');
                }
              },
            });
          }
        },
        close: function() {}
      }
    });
  }

  function GetDataForUpdate(id) {
    var GetDataForUpdate = "GetDataForUpdate";
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        GetDataForUpdate: GetDataForUpdate,
        ID: id,
      },
      success: function(response) {
        var dataRpt = JSON.parse(response);
        $('#paymentID').val(id);
        getCustomer('byUpdate', dataRpt[0].customer_id);
        getAccounts('byUpdate', dataRpt[0].accountID);
        $('#updpayment_recieved').val(dataRpt[0].payment_amount);

        getCurrencies('updateCurrency', dataRpt[0].currencyID);
        $('#updexampleModal').modal('show');
      },
    });
  }

  function getpaymentReport() {
    searchTerm = '';
    var customer_id = $('#customer_id');
    var customerID = -1;
    if (customer_id.val() != null) {
      customerID = customer_id.val();
    }
    var fromdate = $('#fromdate');
    var todate = $('#todate');
    var dateSearch = $('#dateSearch');
    if (dateSearch.is(':checked') && customerID != -1) {
      searchTerm = "DateAndCusWise";
    } else if (dateSearch.is(':checked') && customerID == -1) {
      searchTerm = "DateWise";
    } else if (!dateSearch.is(':checked') && customerID != -1) {
      searchTerm = "CusWise";
    } else if (!dateSearch.is(':checked') && customerID == -1) {
      searchTerm = "AllWise";
    }
    var getpaymentReport = "getpaymentReport";
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        GetpaymentReport: getpaymentReport,
        CustomerID: customerID,
        SearchTerm: searchTerm,
        Fromdate: fromdate.val(),
        Todate: todate.val()
      },
      success: function(response) {
        var cusPaymentRpt = JSON.parse(response);
        if (cusPaymentRpt.length == 0) {
          $('#PaymentReportTbl').empty();
          var finalTable = "<tr><td></td><td></td><td></td>Record Not Found<td></td><td></td>";
          <?php if ($update == 1 && $delete == 1) { ?>
            finalTable += "<td></td>";
          <?php } ?>
          finalTable += "</tr>";
          $('#PaymentReportTbl').append(finalTable);
        } else {
          var total = 0;
          $('#PaymentReportTbl').empty();
          var j = 1;
          var finalTable = "";
          if (Array.isArray(cusPaymentRpt)) {
            for (var i = 0; i < cusPaymentRpt.length; i++) {
              total += parseInt(cusPaymentRpt[i].payment_amount);
              finalTable = "<tr><th scope='row'>" + j + "</th><td class='text-capitalize'>" + cusPaymentRpt[i].customer_name +
                "</td><td class='text-capitalize'>" + cusPaymentRpt[i].datetime + "</td><td>" +
                numeral(cusPaymentRpt[i].payment_amount).format('0,0') + ' ' + cusPaymentRpt[i].currencyName +
                "</td><td>" + cusPaymentRpt[i].staff_name + "</td><td>" + cusPaymentRpt[i].account_Name + "</td>";
              <?php if ($update == 1 && $delete == 1) { ?>
                finalTable += "<td>";
              <?php } ?>
              <?php if ($update == 1) { ?>
                finalTable += "<button  type='button'0 onclick='GetDataForUpdate(" +
                  cusPaymentRpt[i].pay_id + ")'" +
                  "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
              <?php } ?>
              <?php if ($delete == 1) { ?>
                finalTable += "<button type='button'0 onclick='Delete(" +
                  cusPaymentRpt[i].pay_id + ")'" +
                  "class='btn'><i class='fa fa-trash text-graident-lightcrimson fa-2x' aria-hidden='true'></i></button>";
              <?php } ?>
              <?php if ($update == 1 && $delete == 1) { ?>
                finalTable += "</td>";
              <?php } ?>
              finalTable += "</tr>";
              $('#PaymentReportTbl').append(finalTable);
              j += 1;
            }
          } else {
            total += parseInt(cusPaymentRpt.payment_amount);
            finalTable = "<tr><th scope='row'>" + j + "</th><td class='text-capitalize'>" + cusPaymentRpt.customer_name +
              "</td><td class='text-capitalize'>" + cusPaymentRpt.datetime + "</td><td>" +
              numeral(cusPaymentRpt.payment_amount).format('0,0') + ' ' + cusPaymentRpt[i].currencyName +
              "</td><td>" + cusPaymentRpt.staff_name + "</td><td>" + cusPaymentRpt.account_Name + "</td>";
            <?php if ($update == 1 && $delete == 1) { ?>
              finalTable += "<td>";
            <?php } ?>
            <?php if ($update == 1) { ?>
              finalTable += "<button  type='button'0 onclick='GetDataForUpdate(" +
                cusPaymentRpt.payment_id + ")'" +
                "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;";
            <?php } ?>
            <?php if ($delete == 1) { ?>
              finalTable += "<button type='button'0 onclick='Delete(" +
                cusPaymentRpt.payment_id + ")'" +
                "class='btn'><i class='fa fa-trash text-graident-lightcrimson fa-2x' aria-hidden='true'></i></button>";
            <?php } ?>
            <?php if ($update == 1 && $delete == 1) { ?>
              finalTable += "</td>";
            <?php } ?>
            finalTable += "</tr>";

            $('#PaymentReportTbl').append(finalTable);
            j += 1;
          }
          if (total > 0) {
            $('#PaymentReportTbl').append("<tr><td></td><td></td><td></td><td>" + numeral(total).format('0,0') + "</td><td></td><td></td>");
            <?php if ($update == 1 && $delete == 1) { ?>
              $('#PaymentReportTbl').append("<td></td>");
            <?php } ?>
            $('#PaymentReportTbl').append("</tr>");
          }


        }
      },
    });
  }

  // Update 
  $(document).on('submit', '#UpdCountryNameForm', function(event) {
    event.preventDefault();
    var paymentID = $('#paymentID');
    if (paymentID.val() == "") {
      notify('Validation Error!', 'Something went wrong', 'error');
      return;
    }
    var updpayment_recieved = $('#updpayment_recieved');
    if (updpayment_recieved.val() == "") {
      notify('Validation Error!', 'Payment Amount is required', 'error');
      return;
    }
    var updcustomer_id = $('#updcustomer_id');
    if (updcustomer_id.val() == "-1") {
      notify('Validation Error!', 'Customer name is required', 'error');
      return;
    }
    var updaccount_id = $('#updaccount_id');
    if (updaccount_id.val() == "-1") {
      notify('Validation Error!', 'account is required', 'error');
      return;
    }
    var updpayment_currency_type = $('#updpayment_currency_type');
    data = new FormData(this);
    data.append('Update_CountryName', 'Update_CountryName');
    $("#upMkCustomerPayBtn").attr("disabled", true);
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: data,
      contentType: false,
      cache: false,
      processData: false,
      success: function(response) {
        if (response == "Success") {
          notify('Success!', response, 'success');
          $('#UpdCountryNameForm')[0].reset();
          $('#updcustomer_id').val(-1).trigger('change.select2');
          $('#updaccount_id').val(-1).trigger('change.select2');
          $('#updpayment_currency_type option:eq(0)').prop('selected', true);
          $('#updexampleModal').modal('hide');
          getpaymentReport();
          $("#upMkCustomerPayBtn").attr("disabled", false);
        } else {
          notify('Error!', response, 'error');
          $("#upMkCustomerPayBtn").attr("disabled", false);
        }
      },
    });
  });

  function getCustomer(type, id) {
    var select_customer = "select_customer";
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        Select_Customer: select_customer,
      },
      success: function(response) {
        var customer = JSON.parse(response);
        if (type == 'all') {
          $('#customer_id').empty();
          $('#customer_id').append("<option value='-1'>--Customer--</option>");
          for (var i = 0; i < customer.length; i++) {
            $('#customer_id').append("<option value='" + customer[i].customer_id + "'>" +
              customer[i].customer_name + "</option>");
          }
          $('#addcustomer_id').empty();
          $('#addcustomer_id').append("<option value='-1'>--Customer--</option>");
          for (var i = 0; i < customer.length; i++) {
            $('#addcustomer_id').append("<option value='" + customer[i].customer_id + "'>" +
              customer[i].customer_name + "</option>");
          }
        } else {
          $('#updcustomer_id').empty();
          $('#updcustomer_id').append("<option value='-1'>--Customer--</option>");
          var selected = '';
          for (var i = 0; i < customer.length; i++) {
            if (id == customer[i].customer_id) {
              selected = 'selected';
            } else {
              selected = '';
            }
            $('#updcustomer_id').append("<option " + selected + "  value='" + customer[i].customer_id + "'>" +
              customer[i].customer_name + "</option>");
          }
        }
      },
    });
  }

  function getPayments() {
    var addcustomer_id = $("#addcustomer_id option:selected").val();
    var payments = 'Payments';
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        Payments: payments,
        Addcustomer_ID: addcustomer_id
      },
      success: function(response) {
        var payment = JSON.parse(response);
        $('#total_charge').empty();
        if (payment.length > 0) {
          for (var i = 0; i < payment.length; i++) {
            $('#total_charge').append("<p style='font-size:15px'><b>" + numeral(payment[i].total).format('0,0') + " " + payment[i].curName + "</b></p>");
          }
        } else {
          $('#total_charge').append("<p style='font-size:15px'><b>Customer has no due payment <i style='font-size:15px' class='fa fa-smile-o' aria-hidden='true'></i></b></p>");
        }
      },
    });
  }

  function makePay() {
    var insert_payment = "INSERT_Payment";
    var addcustomer_id = $("#addcustomer_id option:selected");
    if (addcustomer_id.val() == "-1") {
      notify('Validation Error!', "Customer is required", 'error');
      return;
    }
    var payment = $('#payment_recieved');
    if (payment.val() == "") {
      notify('Validation Error!', "Payment amount is required", 'error');
      return;
    }
    var addaccount_id = $('#addaccount_id');
    if (addaccount_id.val() == "-1") {
      notify('Validation Error!', 'account is required', 'error');
      return;
    }
    var payment_currency_type = $('#payment_currency_type');
    if (payment_currency_type.val() == "-1") {
      notify('Validation Error!', 'Payment currency type is required', 'error');
      return;
    }
    $("#mkCustomerPayBtn").attr("disabled", true);
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        Insert_Payment: insert_payment,
        Addcustomer_ID: addcustomer_id.val(),
        Addaccount_ID: addaccount_id.val(),
        Payment: payment.val(),
        Payment_Currency_Type: payment_currency_type.val()
      },
      success: function(response) {
        if (response == "Success") {
          notify('Success!', response, 'success');
          payment.val(0);
          $('#net_payable').val(0);
          $('#balance').val(0);
          $('#paid').val(0);
          $('#addcustomer_id').val(-1).trigger('change.select2');
          $('#addaccount_id').val(-1).trigger('change.select2');
          $('#payment_currency_type option:eq(0)').prop('selected', true);
          $('#exampleModal').modal('hide');
          getpaymentReport();
          $("#mkCustomerPayBtn").attr("disabled", false);
        } else {
          notify('Error!', response, 'error');
          $("#mkCustomerPayBtn").attr("disabled", false);
        }
      },
    });
  }

  function getAccounts(type, id) {
    var select_Accounts = "SELECT_Accounts";
    $.ajax({
      type: "POST",
      url: "paymentController.php",
      data: {
        Select_Accounts: select_Accounts,
      },
      success: function(response) {
        var account = JSON.parse(response);
        if (type == 'all') {
          $('#addaccount_id').empty();
          $('#addaccount_id').append("<option value='-1'>--Account--</option>");
          for (var i = 0; i < account.length; i++) {
            $('#addaccount_id').append("<option value='" + account[i].account_ID + "'>" +
              account[i].account_Name + "</option>");
          }
        } else {
          $('#updaccount_id').empty();
          $('#updaccount_id').append("<option value='-1'>--Account--</option>");
          var selected = '';
          for (var i = 0; i < account.length; i++) {
            if (id == account[i].account_ID) {
              selected = 'selected';
            } else {
              selected = '';
            }
            $('#updaccount_id').append("<option " + selected + "  value='" + account[i].account_ID + "'>" +
              account[i].account_Name + "</option>");
          }
        }
      },
    });
  }

  function getCurrencies(type, selected = 1) {
    var currencyTypes = "currencyTypes";
    $.ajax({
      type: "POST",
      url: "customer_paymentsController.php",
      data: {
        CurrencyTypes: currencyTypes,
      },
      success: function(response) {
        var currencyType = JSON.parse(response);
        if (type == "addCurrency") {
          var selectedAttribute = '';
          $('#payment_currency_type').empty();
          for (var i = 0; i < currencyType.length; i++) {
            if (currencyType[0].currencyID == currencyType[i].currencyID) {
              selectedAttribute = 'selected';
            } else {
              selectedAttribute = '';
            }
            $('#payment_currency_type').append("<option " + selectedAttribute + " value='" + currencyType[i].currencyID + "'>" +
              currencyType[i].currencyName + "</option>");
          }
        } else if (type == "updateCurrency") {
          var selectedAttribute = '';
          $('#updpayment_currency_type').empty();
          for (var i = 0; i < currencyType.length; i++) {
            if (selected == currencyType[i].currencyID) {
              selectedAttribute = 'selected';
            } else {
              selectedAttribute = '';
            }
            $('#updpayment_currency_type').append("<option " + selectedAttribute + " value='" + currencyType[i].currencyID + "'>" +
              currencyType[i].currencyName + "</option>");
          }
        }

      },
    });
  }
</script>
</body>

</html>