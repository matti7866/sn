<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetReport'])){
                $sql = "SELECT * FROM (SELECT invoiceID,documentName,invoiceNumber, DATE(invoiceDate) AS invoiceDate,
                DATE_FORMAT(DATE(invoiceDate),'%d-%b-%Y') AS formatedDate FROM `invoice` WHERE customerID = :id AND 
                invoiceCurrency = :curID AND YEAR(DATE(invoiceDate)) = YEAR(CURRENT_DATE) - 1 AND MONTH(DATE(invoiceDate)) IN 
                (11, 12)
                UNION ALL
                SELECT invoiceID,documentName,invoiceNumber, DATE(invoiceDate) AS invoiceDate,
                DATE_FORMAT(DATE(invoiceDate),'%d-%b-%Y') AS formatedDate FROM `invoice` WHERE customerID = :id AND 
                invoiceCurrency = :curID AND YEAR(DATE(invoiceDate)) = YEAR(CURRENT_DATE)) AS baseTable ORDER by invoiceDate DESC
                ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['ID']);
                $stmt->bindParam(':curID', $_POST['CurID']);
                 // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                echo json_encode($records);
    }else if(isset($_POST['UploadReceipt'])){
        try{
            $image = uploadReceiptPdf();
            //If Customer pays on the spot
                if($image == '')
                {
                    echo "Record not added becuase of file uploader";
                }else{
                        $sql = "UPDATE invoice SET documentName =:documentName, orginalName=:orginalName WHERE invoiceID
                         =:invoiceID";
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':documentName', $image);
                $stmt->bindParam(':orginalName', $_FILES['uploader']['name']);
                $stmt->bindParam(':invoiceID', $_POST['receiptID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['DeleteFile'])){
        try{
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of receipt
                    $sql = "SELECT documentName FROM invoice WHERE invoiceID = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['documentName'];
                    if(file_exists($file)){
                        unlink($file);
                    }
                    if(!is_file($file)) {
                        $sql = "UPDATE invoice SET documentName = NULL,  orginalName = NULL WHERE invoiceID = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $_POST['ID']);
                        $stmt->execute();
                    }
            $pdo->commit();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    function uploadReceiptPdf(){
        $new_image_name = '';
        if($_FILES['uploader']['size']<=2097152){
            $extension = explode(".", $_FILES['uploader']['name']);
            $f_name = '';
            $f_ext = '';
            if(count($extension) > 2){
                for($i = 0; $i< count($extension); $i++){
                    if(count($extension) == $extension[$i]){
                        $f_name  = $f_name . $extension[$i];
                    }else{
                        $f_ext = $extension[$i];
                    }
                }
               
            }else{
                $f_name =  $extension[0];
                $f_ext = $extension[1];
            }
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($f_ext), $ext))
            {
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'receipt/'. $new_image_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploader']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>