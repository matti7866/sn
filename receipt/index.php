<?php


// hide php warnings
error_reporting(E_ERROR | E_PARSE);

require_once '../api/connection/index.php';
require_once '../api/customers/receipt/receipt-public.php';


$id = isset($_GET['id']) ? $_GET['id'] : '';
$QR = "https://app.sntrips.com/receipt/?id=$id&hash=" . md5($id . '::::::' . $id) . "&download=true";

$hash = md5($id . '::::::' . $id);
if ($hash != $_GET['hash']) {
  die('Invalid Request');
}

$download = isset($_GET['download']) ? $_GET['download'] : false;

$recipt = new Receipt($conn);
$resp = $recipt->getPaymentReceiptCusInfo($id);
$payment = $recipt->getCusPaymentReceiptDetails($id);


if (isset($resp) && count($resp)) {
  $resp = $resp[0];
}
if (isset($payment) && count($payment)) {
  $payment = $payment[0];
}


$getStatmentInfoForReceiptByCusAndCur = $recipt->getStatmentInfoForReceiptByCusAndCur($resp['customerID'], $resp['invoiceCurrency']);
$total = 0;
$recordsToDisplayArr = array();
if (empty($getStatmentInfoForReceiptByCusAndCur)) {
  $getCustomerAllTransactions = $recipt->getCustomerAllTransactions($resp['customerID'], $resp['invoiceCurrency']);
  foreach ($getCustomerAllTransactions as $record) {
    $total = $total + $record['Debit'] - $record['Credit'];
    if ($total == 0) {
      $getStatmentInfoForReceiptByCusRefNdTrans = $recipt->getStatmentInfoForReceiptByCusRefNdTrans(
        $_POST['CustomerID'],
        $record['refID'],
        $record['TRANSACTION_Type']
      );
      if (!$getStatmentInfoForReceiptByCusRefNdTrans) {
        $result =  $recipt->SaveStatementInfo(
          $resp['customerID'],
          $record['refID'],
          $record['nonFormatedDate'],
          $record['TRANSACTION_Type'],
          $resp['invoiceCurrency']
        );
        if ($result !== "Success") {
          throw new \Exception('Something went wrong! Please referesh the page.');
        }
      }
    }
  }
} else {
  $getCusTransactionsDateCusCurWise = $recipt->getCusTransactionsDateCusCurWise(
    $resp['customerID'],
    $resp['invoiceCurrency'],
    $getStatmentInfoForReceiptByCusAndCur[0]['referenceDate']
  );
  $flagDecision = 0;
  foreach ($getCusTransactionsDateCusCurWise as $record) {
    if (intval($record['refID']) !=  intval($getStatmentInfoForReceiptByCusAndCur[0]['referenceID'])) {
      if ($flagDecision == 1) {
        $total = $total + intval($record['Debit']) - intval($record['Credit']);
        if ($total == 0) {
          $getStatmentInfoForReceiptByCusRefNdTrans = $recipt->getStatmentInfoForReceiptByCusRefNdTrans(
            $_POST['CustomerID'],
            $record['refID'],
            $record['TRANSACTION_Type']
          );
          if (!$getStatmentInfoForReceiptByCusRefNdTrans) {
            $result =  $recipt->SaveStatementInfo(
              $resp['customerID'],
              $record['refID'],
              $record['nonFormatedDate'],
              $record['TRANSACTION_Type'],
              $resp['invoiceCurrency']
            );
            if ($result !== "Success") {
              throw new \Exception('Something went wrong! Please referesh the page.');
            }
          }
        }
      }
    } else {
      $flagDecision = 1;
    }
  }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Receipt <?php echo $resp['invoiceNumber'] ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="style.css?v=<?php echo time() ?>" rel="stylesheet" />

  <?php if ($download == true) { ?>
    <script>
      window.print();
    </script>
  <?php } ?>
</head>

<body>
  <div class="page<?php echo $download == false ? ' no-print' : '' ?>">
    <div class="header">
      <div class="logo<?php echo $download == false ? ' no-print' : '' ?>">
        <img src="logo.png" alt="">
      </div>
      <div class="heading"><span>PAYMENT RECEIPT</span></div>
      <div class="qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=<?php echo urlencode($QR) ?>" alt="">
      </div>
    </div>
    <div class="data">
      <div class="row">
        <div class="col">
          <div class="label">Receipt #:</div>
          <div class="value"><?= $resp['invoiceNumber'] ?></div>
        </div>
        <div class="col">
          <div class="label">Date:</div>
          <div class="value"><?= $resp['invoiceDate'] ?></div>
        </div>
        <div class="col">
          <div class="label">Currency #:</div>
          <div class="value"><?= $resp['currencyName'] ?></div>
        </div>
      </div>
      <div class="row">
        <div class="col col-full">
          <div class="label">Customer Name</div>
          <div class="value"><?= $resp['customer_name'] ?></div>
        </div>
      </div>
      <table border="1" width="100%" style="border-collapse:collapse;" cellpadding="4" cellspacing="0">
        <thead>
          <tr>
            <th>SR#</th>
            <th>Transaction</th>
            <th>Service</th>
            <th>Passenger</th>
            <th>Date</th>
            <th>Sale Price</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><?php echo $payment['transactionType'] ?></td>
            <td><?php echo $payment['serviceInfo'] ?></td>
            <td><?php echo $payment['PassengerName'] ?></td>
            <td><?php echo $payment['formatedDate'] ?></td>
            <td><?php echo number_format($payment['salePrice']) ?></td>
          </tr>
          <tr>
            <td colspan="5" align="right" class="text-right"><strong>Total Paid: </strong></td>
            <td><?php echo number_format($payment['salePrice']) ?> <?= $resp['currencyName'] ?></td>
          </tr>
          <tr>
            <td colspan="5" align="right" class="text-right"><strong>Outstanding Balance: </strong></td>
            <td id="balance"><?php echo number_format($total)  ?> <?= $resp['currencyName'] ?></td>
          </tr>
        </tbody>
      </table>
      <div>
        <div class="english">This is a computer generated receipt and does not require any signature.</div>
        <div class="arabic">هذه إيصال مولد بواسطة الكمبيوتر ولا يتطلب أي توقيع.</div>

      </div>
    </div>
  </div>
</body>

</html>