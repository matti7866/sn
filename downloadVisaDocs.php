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
                            if($_GET['type'] == 1){
                                echo $_GET['type'];
                                $fileExists = "SELECT visaCopy,orginalName FROM visa WHERE visa_id = :visa_id";
                                $fileExistsStmt = $pdo->prepare($fileExists);
                                $fileExistsStmt->bindParam(':visa_id',$_GET['id']);
                                $fileExistsStmt->execute();
                                $fileInDatabase =  $fileExistsStmt->fetchAll(\PDO::FETCH_ASSOC);
                                if($fileInDatabase[0]['visaCopy']){
                                        //define header
                                        header("Cache-Control: public");
                                        header("Content-Description: File Transfer");
                                        header("Content-Disposition: attachment; filename=".$fileInDatabase[0]['orginalName']);
                                        header("Content-Type: application/zip");
                                        header("Content-Transfer-Encoding: binary");
        
                                        //read file 
                                        readfile($fileInDatabase[0]['visaCopy']);
                                        exit;
                                    }else{
                                        echo "Something went wrong";
                                        exit();
                                    }
                            }else{
                                $fileExists = "SELECT docName,orginalName FROM visaextracharges WHERE visaExtraChargesID = :visaExtraChargesID";
                                $fileExistsStmt = $pdo->prepare($fileExists);
                                $fileExistsStmt->bindParam(':visaExtraChargesID',$_GET['id']);
                                $fileExistsStmt->execute();
                                $fileInDatabase =  $fileExistsStmt->fetchAll(\PDO::FETCH_ASSOC);
                                if($fileInDatabase[0]['docName']){
                                    //define header
                                    header("Cache-Control: public");
                                    header("Content-Description: File Transfer");
                                    header("Content-Disposition: attachment; filename=".$fileInDatabase[0]['orginalName']);
                                    header("Content-Type: application/zip");
                                    header("Content-Transfer-Encoding: binary");
    
                                    //read file 
                                    readfile($fileInDatabase[0]['docName']);
                                    exit;
                                }else{
                                    echo "Something went wrong";
                                    exit();
                                }
                            }
                        }
                      
                            

  // Close connection
  unset($pdo); 
?>
  