<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location:../../../login.php');
    }
    require_once '../../../api/connection/index.php';
    require_once '../../helper/validator/validator.php';
    require_once '../../helper/fileUploader/uploadFile.php';
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
        http_response_code(500);
        echo json_encode(array('error' => 'All permission for customer payment report is denied for the user'));
        exit();
    }
// insert customer payment 
 if(isset($_POST['UploadFile']) && $_POST['UploadFile'] === "UploadFile" ){
    try{
        // create instance of CustomerPayment class
        $conn->beginTransaction();
        // if permission for selection of customer is restricted by database then it should return permission denied
        if ($select != 1) {
            throw new \Exception('The user has no permission to store customer payment receipt');
        }
        // Initailze validation class
        $validator = new Validator($conn);
        // we validate if the payment exists
        $CheckInvoiceID = intval($validator->InvoiceID(intval($_POST['FileID'])));
        if($CheckInvoiceID !== 1){
            throw new \Exception('Something went wrong! Please refresh the page and try again.');
        }  
        $image = '';
        $image = upload_Image('receipt');
        if($image === ''){
            $image = 'Error';
        }
        if($image === 'Error'){
            throw new \Exception('Image not upload. Please try again.');
        }else{
            require_once '../../../api/customers/receipt/receipt.php';
            $recipt = new Receipt($conn);
            $result = $recipt->SaveReceiptFile(intval($_POST['FileID']), $image , $_FILES['uploaderFile']['name']);
            if($result === 'Success'){
                $conn->commit(); 
                echo json_encode(array('message' =>  "Success" ));
            }
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