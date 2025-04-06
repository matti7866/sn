<?php
  if (session_status() === PHP_SESSION_NONE) {
     session_start();
   }
    if (!isset($_SESSION['user_id'])) {
     header('location:../../../login.php');
    }
require_once '../../../api/connection/index.php';
require_once '../../helper/validator/validator.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Payment' ";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select =  $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0 && $insert == 0 && $update == 0 && $delete == 0){
    header('location:../../../views/customer/error_pages/permissionDenied.php');
}
// insert customer payment 
 if(isset($_POST['GeneratePaymentReceipt']) && $_POST['GeneratePaymentReceipt'] === "GeneratePaymentReceipt" ){
    try{
        $conn->beginTransaction();
        // if permission for selection of customer is restricted by database then it should return permission denied
        if ($select != 1) {
            throw new \Exception('The user has no permission to select customer payment report');
        }
        // get the data from API
        require_once '../../../api/customers/customer-payment/customer-payment.php';
        // create instance of CustomerPayment class
        $CustomerPayment = new CustomerPayment($conn);
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the payment exists
        $paymentID = intval($validator->paymentID(intval($_POST['PaymentID'])));
        if($paymentID !== 1){
            throw new \Exception('Something went wrong! Please refresh the page and try again.');
        }  
        $getCustomerAndCurrencyOfReceipt = $CustomerPayment->getcustomerAndCurrencyForReceipt(intval($_POST['PaymentID']));
        // generate invoice number
        require_once '../../helper/generateInvoiceNumber/generateInvoiceNumber.php';
        $invoiceNumber = generateInvoiceNumber($conn);
        // insert receipt 
        require_once '../../../api/customers/receipt/receipt.php';
        $recipt = new Receipt($conn);
        $getInvoiceID = $recipt->SaveReceipt($getCustomerAndCurrencyOfReceipt['customer_id'],$invoiceNumber, 
        $getCustomerAndCurrencyOfReceipt['currencyID']);
        if($getInvoiceID === 0 || $getInvoiceID ==='undefined' || $getInvoiceID === null ){
            throw new \Exception('Something went wrong! Refresh the page.');
        }
         $transactionType = "Payment";
        $insertReceiptDetails =  $recipt->SaveReceiptDetails($getInvoiceID, intval($_POST['PaymentID']), $transactionType);
        if($insertReceiptDetails === 'Success'){
            $conn->commit(); 
            echo json_encode(array('message' =>  "Success&" . $getInvoiceID ));
        }
    }catch (\Throwable $th) {
        //throw $th;
        $conn->rollback();
        http_response_code(500);
        echo json_encode(array('error' => $th->getMessage()));
    }
}
 // Close connection
 unset($conn); 
?>