<?php
require_once '../../layout/header.php';
?>
<link href='https://fonts.googleapis.com/css?family=Arizonia' rel='stylesheet'>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
<link href="https://printjs-4de6.kxcdn.com/print.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="../../assets/css/receiptCustomBoostrap.css">

<title>Customer Receipt</title>
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
if ($select == 0 && $insert == 0 && $update == 0 && $delete == 0) {
    header('location:../../error_pages/permissionDenied.php');
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 offset-md-8">
                        <button class="btn btn-danger pull-right" id="printButton" onclick="printLedger()">Print Receipt</button>
                        <a target="_blank" href="/receipt/?id=<?php echo $_GET['rcptID'] ?>&hash=<?php echo md5($_GET['rcptID'] . '::::::' . $_GET['rcptID']) ?>" class="btn btn-danger pull-right" id="printButton">Print Receipt(New)</a>
                        <a target="_blank" href="/receipt/?id=<?php echo $_GET['rcptID'] ?>&hash=<?php echo md5($_GET['rcptID'] . '::::::' . $_GET['rcptID']) ?>&download=true" class="btn btn-danger pull-right" id="printButton">Download Receipt(New)</a>
                    </div>
                </div>
                <input type="hidden" id="customerID">
                <input type="hidden" id="currencyID">
                <div id="printThisArea">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-fixed-1">
                                <img src="../../assets/images/logo/logoselab.png" style="height:60px;width:60px;">
                            </div>
                            <div class="col-fixed-4 margin-left-40 margin-top-20 ">
                                <h1 class="companyName"><b>Selab Nadiry Travel & Tourism</b></h1>
                                <p class="companyInfo">Address: Frij Murar Shop# 15, Deira, Dubai</p>
                                <p class="companyInfo">Contact:+971 4 298 4564,+971 58 514 0764</p>
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
                        <hr id="headerLineBreak" />
                        <div class="table-responsive d ">
                            <table id="InfoTable" class="table table-sm table-striped table-hover table-bordered">
                                <thead>
                                    <tr id="ad" class="bg-danger text-white">
                                        <th style="-webkit-print-color-adjust: exact;">S#</th>
                                        <th style="-webkit-print-color-adjust: exact;">Transaction</th>
                                        <th style="-webkit-print-color-adjust: exact;">Service</th>
                                        <th style="-webkit-print-color-adjust: exact;">Passenger</th>
                                        <th style="-webkit-print-color-adjust: exact;">Date</th>
                                        <th style="-webkit-print-color-adjust: exact;">Sale Price</th>

                                    </tr>
                                </thead>
                                <tbody id="CusReceiptPaymentReportTbl">




                                </tbody>
                            </table>
                        </div>
                        <div id="paymentAndSign">
                            <div class="row">
                                <div class="col-md-8 offset-4">
                                    <p class="font-weight-bold text-right" style="font-size:10px">Total Paid: <span id="totalPayment"></span> </p>
                                    <hr />
                                </div>
                                <div class="col-md-8 offset-4">
                                    <p class="font-weight-bold text-right" style="font-size:12px"><b>Outstanding Balance: <span id="outstandingBalance"></span> </b></p>
                                    <hr />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4" style="position:relative">
                                    <div class="easerButton" style="position:absolute; top:0; left:63px"><button class="btn btn-info" type="button" onclick="clearSignature('company')"><i class="fa fa-eraser"></i></button></div>
                                    <canvas id="company-signature" width="200" height="50"></canvas>
                                    <hr class="signatureLine" />
                                    <p class="text-center company-signatureText">Company Signature</p>
                                </div>
                                <div class="col-md-4">
                                    <img src="../../assets/images/stamp/selabStamp.png" />
                                </div>
                                <div class="col-md-4" style="position:relative">
                                    <div class="easerButton" style="position:absolute; top:0; left:63px"><button class="btn btn-info" type="button" onclick="clearSignature('customer')"><i class="fa fa-eraser"></i></button></div>
                                    <canvas id="customer-signature" class="float-center" width="200" height="50"></canvas>
                                    <hr class="signatureLine" />
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

<?php require_once '../../layout/footer.php'; ?>
<script src="../../components/receipt/getReceiptCustomerInfo.js"></script>
<script src="../../plugins/Numeral-js-master/numeral.js"></script>
<script src="../../components/receipt/getReceiptPaymentReport.js"></script>
<script src="../../components/receipt/getPendingPaymentCusNdCurWise.js"></script>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script>
    $(document).ready(function() {
        CallPaymentReceiptCustomerInfo();
        getPaymentReceiptReport();
    });
    // get payment receipt customer information
    function CallPaymentReceiptCustomerInfo() {
        // Get the id from the url
        var urlFirstParam = location.search.split('&')[0];
        var id = urlFirstParam.split('=')[1];
        /*
            Call GetReceiptCustomerInfo function 
            first param is url
            second param is receipt id
            thired param  is the area where we show the receipt number
            fourth param is the area where we show customer name
            fifth param is the area where we show receipt date
            sexith param is receipt currency
            sevent param is the area where we show customer id
            eight param is the area where we show the currency id
        */
        GetReceiptCustomerInfo('../../../controller/customer/customer_receipt/receiptDetailsController.php', id,
                $('#receiptNumber'), $('#customer_name'), $('#receiptDate'), $('#receiptCur'), $('#customerID'), $('#currencyID')).then(function() {
                getTotalRemainingPaymentCusAndCurWise('../../../controller/customer/customer_receipt/receiptDetailsController.php',
                    $('#customerID'), $('#currencyID'), $('#outstandingBalance'));
            })
            .catch(function() {
                notify('error', 'Something went wrong! refresh the page', 'error');
            });
    }
    // get receipt details
    function getPaymentReceiptReport() {
        var urlFirstParam = location.search.split('&')[0];
        var id = urlFirstParam.split('=')[1];

        var curID = location.search.split('&curID=')[1];
        /*the function takes the url, the receipt id, the place to display the result and the total payment place to display
        the total payment, the customer ID and the currency ID for getting total pending payment for customer based on specific
        currency */
        getReceiptPaymentReport('../../../controller/customer/customer_receipt/receiptDetailsController.php', id,
            $('#CusReceiptPaymentReportTbl'), $('#totalPayment'));
    }










    // get canvas element and initialize signature pad
    var canvas = document.getElementById('company-signature');
    var signaturePad = new SignaturePad(canvas);
    var customercanvas = document.getElementById('customer-signature');
    var signaturePad2 = new SignaturePad(customercanvas);

    // clear signature
    function clearSignature(type) {
        if (type == "company") {
            signaturePad.clear();
        } else if (type == "customer") {
            signaturePad2.clear();
        }
    }

    // print function
    function printLedger() {
        printJS({
            printable: 'printThisArea',
            type: 'html',
            css: [
                '../../plugins/bootstrap-4.3.1-dist/css/bootstrap.min.css',
                '../../assets/css/receiptCustomBoostrap.css',
                'https://fonts.googleapis.com/css?family=Arizonia',
                'https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap',
            ],
            targetStyles: ['*'],
        })
    }
</script>
</body>

</html>