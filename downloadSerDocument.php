<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $PermissionSQL = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Service' ";
    $PermissionStmt = $pdo->prepare($PermissionSQL);
    $PermissionStmt->bindParam(':role_id', $_SESSION['role_id']);
    $PermissionStmt->execute();
    $records = $PermissionStmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>";
    }

                            if($select == 1){                  
                            $fileExists = "SELECT file_name,original_name FROM servicedocuments WHERE document_id = :document_id";
                            $fileExistsStmt = $pdo->prepare($fileExists);
                            $fileExistsStmt->bindParam(':document_id',$_GET['id']);
                            $fileExistsStmt->execute();
                            $fileInDatabase =  $fileExistsStmt->fetchAll(\PDO::FETCH_ASSOC);
                            if($fileInDatabase[0]['file_name']){
                                
                                    //define header
                                    header("Cache-Control: public");
                                    header("Content-Description: File Transfer");
                                    header("Content-Disposition: attachment; filename=".$fileInDatabase[0]['original_name']);
                                    header("Content-Type: application/zip");
                                    header("Content-Transfer-Encoding: binary");
    
                                    //read file 
                                    readfile('service/'. $fileInDatabase[0]['file_name']);
                                    exit;
                                }else{
                                    echo "Something went wrong";
                                    exit();
                                }
                            }

  // Close connection
  unset($pdo); 
?>
  