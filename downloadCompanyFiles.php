<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $PermissionSQL = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Company Documents' ";
    $PermissionStmt = $pdo->prepare($PermissionSQL);
    $PermissionStmt->bindParam(':role_id', $_SESSION['role_id']);
    $PermissionStmt->execute();
    $records = $PermissionStmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>";
    }
                    $pdo->beginTransaction();
                    $sql = "SELECT directory_name FROM company_directories WHERE directory_id = :directory_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':directory_id', $_GET['ParentCustomID']);
                    $stmt->execute();
                    $directory =  $stmt->fetchColumn();
                    if($directory){
                        if(is_dir('company_files/'.$directory)){
                            $fileExists = "SELECT file_name FROM company_documents WHERE dir_id = :directory_id AND
                            document_id = :document_id";
                            $fileExistsStmt = $pdo->prepare($fileExists);
                            $fileExistsStmt->bindParam(':directory_id', $_GET['ParentCustomID']);
                            $fileExistsStmt->bindParam(':document_id',$_GET['CustomID']);
                            $fileExistsStmt->execute();
                            $fileInDatabase =  $fileExistsStmt->fetchColumn();
                            $pdo->commit();
                            if($fileInDatabase){
                                if(file_exists('company_files/'.$directory. '/'. $fileInDatabase)){
                                    //define header
                                    header("Cache-Control: public");
                                    header("Content-Description: File Transfer");
                                    header("Content-Disposition: attachment; filename=".$fileInDatabase);
                                    header("Content-Type: application/zip");
                                    header("Content-Transfer-Encoding: binary");
    
                                    //read file 
                                    readfile('company_files/'.$directory. '/'. $fileInDatabase);
                                    exit;
                                }else{
                                    echo "Something went wrong";
                                    exit();
                                }
                            }else{
                                echo "Something went wrong";
                                exit();
                            }
                        }else{
                            echo "Something went wrong";
                            exit();
                        }
                    }else{
                        echo "Something went wrong";
                        exit();
                    }
  // Close connection
  unset($pdo); 
?>
  