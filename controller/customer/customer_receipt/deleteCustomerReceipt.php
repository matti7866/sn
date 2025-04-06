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
 if(isset($_POST['deletePaymentReceiptFile']) && $_POST['deletePaymentReceiptFile'] === "deletePaymentReceiptFile" ){
    try{
        $conn->beginTransaction();
         // if permission for selection of customer is restricted by database then it should return permission denied
         if ($delete != 1) {
            throw new \Exception('The user has no permission to delete receipt');
            exit();
        }
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the payment exists
        $CheckInvoiceID = intval($validator->InvoiceID(intval($_POST['ID'])));
        if($CheckInvoiceID !== 1){
            throw new \Exception('Something went wrong! Please refresh the page and try again.');
        }  
        require_once '../../../api/customers/receipt/receipt.php';
        $recipt = new Receipt($conn);
        $file = $recipt->GetReceiptDocumentName(intval($_POST['ID']));
        if(file_exists($file)){
            unlink($file);
        }
        $result = $recipt->DeletePaymentReceiptFile(intval($_POST['ID']));
        if($result === 'Success'){
            $conn->commit(); 
            echo json_encode(array('message' =>  "Success" ));
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