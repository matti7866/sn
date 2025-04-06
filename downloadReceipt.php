<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $PermissionSQL = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Visa' ";
    $PermissionStmt = $pdo->prepare($PermissionSQL);
    $PermissionStmt->bindParam(':role_id', $_SESSION['role_id']);
    $PermissionStmt->execute();
    $records = $PermissionStmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>";
    }
                            if($select == 1){
                                $fileExists = "SELECT documentName,orginalName FROM invoice WHERE invoiceID = :invoiceID";
                                $fileExistsStmt = $pdo->prepare($fileExists);
                                $fileExistsStmt->bindParam(':invoiceID',$_GET['id']);
                                $fileExistsStmt->execute();
                                $fileInDatabase =  $fileExistsStmt->fetchAll(\PDO::FETCH_ASSOC);
                                if($fileInDatabase[0]['documentName']){
                                    //define header
                                    header("Cache-Control: public");
                                    header("Content-Description: File Transfer");
                                    header("Content-Disposition: attachment; filename=".$fileInDatabase[0]['orginalName']);
                                    header("Content-Type: application/zip");
                                    header("Content-Transfer-Encoding: binary");
    
                                    //read file 
                                    readfile($fileInDatabase[0]['documentName']);
                                    exit;
                                }else{
                                    echo "Something went wrong";
                                    exit();
                                }
                        }  
  // Close connection
  unset($pdo); 
?>
  