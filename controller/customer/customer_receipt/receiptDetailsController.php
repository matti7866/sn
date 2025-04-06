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
// get receipt customer info
 if(isset($_POST['GetReceiptCustomerInfo']) && $_POST['GetReceiptCustomerInfo'] === "GetReceiptCustomerInfo" ){
    try{
         // if permission for selection of customer is restricted by database then it should return permission denied
         if ($select != 1) {
            throw new \Exception('The user has no permission to select receipt details');
            exit();
        }
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the payment exists
        $CheckInvoiceID = intval($validator->InvoiceID(intval($_POST['ID'])));
        if($CheckInvoiceID !== 1){
            throw new \Exception('The receipt ID you are looking for does not exists.');
        }  
        require_once '../../../api/customers/receipt/receipt.php';
        $recipt = new Receipt($conn);
        $getPaymentReceiptCusInfo = $recipt->getPaymentReceiptCusInfo(intval($_POST['ID']));
        // Return the results as JSON
        echo json_encode($getPaymentReceiptCusInfo);
    }catch (\Throwable $th) {
        //throw $th;
        http_response_code(500);
        echo json_encode(array('error' => $th->getMessage()));
    }
}
// get receipt details 
if(isset($_POST['GetReceiptPaymentReport']) && $_POST['GetReceiptPaymentReport'] === "GetReceiptPaymentReport" ){
    try{
         // if permission for selection of customer is restricted by database then it should return permission denied
         if ($select != 1) {
            throw new \Exception('The user has no permission to select receipt details');
            exit();
        }
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the payment exists
        $CheckInvoiceID = intval($validator->InvoiceID(intval($_POST['ID'])));
        if($CheckInvoiceID !== 1){
            throw new \Exception('The receipt ID you are looking for does not exists.');
        }  
        require_once '../../../api/customers/receipt/receipt.php';
        $recipt = new Receipt($conn);
        $getCusPaymentReceiptDetails = $recipt->getCusPaymentReceiptDetails(intval($_POST['ID']));
        // Return the results as JSON
        echo json_encode($getCusPaymentReceiptDetails);
    }catch (\Throwable $th) {
        //throw $th;
        http_response_code(500);
        echo json_encode(array('error' => $th->getMessage()));
    }
}
// get Pending payment customer by currency
if(isset($_POST['GetTotalRemainingPaymentCusAndCurWise']) && $_POST['GetTotalRemainingPaymentCusAndCurWise'] === "GetTotalRemainingPaymentCusAndCurWise" ){
    try{
        $conn->beginTransaction();
         // if permission for selection of customer is restricted by database then it should return permission denied
         if ($select != 1) {
            throw new \Exception('The user has no permission to select customer total pending payment');
            exit();
        }
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the customer  exists
        $validateCustomer = intval($validator->customer(intval($_POST['CustomerID'])));
        // validate currency
        $validateCurrency = intval($validator->currency(intval($_POST['CurID'])));
        if($validateCustomer !== 1){
            throw new \Exception('The customer does not exists.');
        }  
        if($validateCurrency !== 1){
            throw new \Exception('The currency does not exists.');
        }  
        require_once '../../../api/customers/receipt/receipt.php';
        $recipt = new Receipt($conn);
        // The function gets customer id and currency id as param and check if there is any record in statement table
        $getStatmentInfoForReceiptByCusAndCur = $recipt->getStatmentInfoForReceiptByCusAndCur($_POST['CustomerID'],$_POST['CurID']);
        $total = 0;
        $recordsToDisplayArr = array();
        // check if record exists or not
        if (empty($getStatmentInfoForReceiptByCusAndCur)) {
            // no record exists
            $getCustomerAllTransactions = $recipt->getCustomerAllTransactions($_POST['CustomerID'],$_POST['CurID']);
            foreach ($getCustomerAllTransactions as $record) {
                $total = $total + $record['Debit'] - $record['Credit'];
                if($total == 0){
                    $getStatmentInfoForReceiptByCusRefNdTrans = $recipt->getStatmentInfoForReceiptByCusRefNdTrans(
                    $_POST['CustomerID'],$record['refID'],$record['TRANSACTION_Type']);
                        if (!$getStatmentInfoForReceiptByCusRefNdTrans) {
                           $result =  $recipt->SaveStatementInfo($_POST['CustomerID'],$record['refID'],$record['nonFormatedDate']
                            ,$record['TRANSACTION_Type'],$_POST['CurID']);
                            if($result !== "Success"){
                                throw new \Exception('Something went wrong! Please referesh the page.');
                            }
                        }
                }
            }
        }else{
            $getCusTransactionsDateCusCurWise = $recipt->getCusTransactionsDateCusCurWise($_POST['CustomerID'],$_POST['CurID'],
            $getStatmentInfoForReceiptByCusAndCur[0]['referenceDate']);
            $flagDecision = 0;
            foreach ($getCusTransactionsDateCusCurWise as $record) {
                if(intval($record['refID']) !=  intval($getStatmentInfoForReceiptByCusAndCur[0]['referenceID'])){
                    if($flagDecision == 1){
                        $total = $total + intval($record['Debit']) - intval($record['Credit']);
                        if($total == 0){
                            $getStatmentInfoForReceiptByCusRefNdTrans = $recipt->getStatmentInfoForReceiptByCusRefNdTrans(
                            $_POST['CustomerID'],$record['refID'],$record['TRANSACTION_Type']);
                                if (!$getStatmentInfoForReceiptByCusRefNdTrans) {
                                    $result =  $recipt->SaveStatementInfo($_POST['CustomerID'],$record['refID'],
                                    $record['nonFormatedDate'],$record['TRANSACTION_Type'],$_POST['CurID']);
                                        if($result !== "Success"){
                                            throw new \Exception('Something went wrong! Please referesh the page.');
                                        }
                                }
                        }
                    }
                }else{
                    $flagDecision = 1;
                }
            }
        }
        $conn->commit(); 
        echo json_encode($total);
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