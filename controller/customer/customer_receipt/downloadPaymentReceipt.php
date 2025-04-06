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
    try {
        $conn->beginTransaction();
        if($select == 1){
            // Initailze validation class
            $validator = new Validator($conn);
            // we validate if the payment exists
            $invoiceIDValidator = intval($validator->InvoiceID(intval($_GET['id'])));
            if($invoiceIDValidator !== 1){
                $conn->rollback();
                echo json_encode(array('error' => 'Receipt ID does not exists.'));
                exit();
            }  
            $fileExists = "SELECT documentName,orginalName FROM invoice WHERE invoiceID = :invoiceID";
            $fileExistsStmt = $conn->prepare($fileExists);
            $fileExistsStmt->bindParam(':invoiceID',$_GET['id']);
            $fileExistsStmt->execute();
            $conn->commit();
            $fileInDatabase =  $fileExistsStmt->fetchAll(\PDO::FETCH_ASSOC);
            if($fileInDatabase[0]['documentName']){
            //define header
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".$fileInDatabase[0]['orginalName']);
            header("Content-Type: application/pdf");
            header("Content-Transfer-Encoding: binary");
            //read file 
            error_reporting(0);
            ob_clean();
            readfile($fileInDatabase[0]['documentName']);
            exit;
            }else{
                throw new \Exception('File could not be downloaded');
                exit();
            }
        }else{
                throw new \Exception('No permission to download file');
                exit();
        }
    } catch (\Throwable $th) {
        //throw $th;
        http_response_code(500);
        echo json_encode(array('error' => $th->getMessage()));
    }
    
    
    
 // Close connection
 unset($conn); 
?>