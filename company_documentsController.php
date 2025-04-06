<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.insert,permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Company Documents' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $insert = $records[0]['insert'];
    $delete = $records[0]['delete'];
    if($select == 0 && $insert == 0 ){
    echo "<script>window.location.href='pageNotFound.php'</script>";
    }
    if(isset($_POST['CreateFolder'])){
        try{
                       if($insert == 1){
                            // First of all, let's begin a transaction
                        $pdo->beginTransaction();
                        $sql = "SELECT directory_name FROM company_directories WHERE directory_name = :directory_name";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':directory_name', $_POST['Foler_Name']);
                        $stmt->execute();
                        $directory =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        if($directory){
                            $directory =  $directory[0]['directory_name'];
                            if(is_dir('company_files/'. $directory)){
                                $pdo->rollback();
                                echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Directory already exists with the name " . $_POST['Foler_Name']]);
                                
                            }else{
                                $pdo->rollback();
                                echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Directory already exists with the name " . $_POST['Foler_Name']]);
                                
                            }
                        }else{
                            mkdir('company_files/'.$_POST['Foler_Name'] , 0777, true);
                            $sql = "INSERT INTO `company_directories` (`directory_name`) VALUES (:directoryName)  ";
                            $stmt = $pdo->prepare($sql);
                            // bind parameters to statement
                            $stmt->bindParam(':directoryName', $_POST['Foler_Name']);
                            $stmt->execute();
                            $pdo->commit();
                            $sql = "SELECT directory_id FROM company_directories WHERE directory_name = :directory_name";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':directory_name', $_POST['Foler_Name']);
                            $stmt->execute();
                            $id = $stmt->fetchColumn();
                            echo json_encode((Object)['msg' =>"success", "id" => $id  ]);
                        }
                       }
        }catch(PDOException $e){
            $pdo->rollback();
            echo  json_encode((Object)['msg' =>"error", 'msgDetails' =>  $e->getMessage() ]);
        }
    }else if(isset($_POST['uploadCompanyFiles'])){
        try{
            if($insert){
                // First of all, let's begin a transaction
            $pdo->beginTransaction();
            $sql = "SELECT directory_name FROM company_directories WHERE directory_id = :directory_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':directory_id', $_POST['DID']);
            $stmt->execute();
            $directory =  $stmt->fetchColumn();
                if($directory)
                {
                    
                        if(is_dir('company_files/'.$directory)){
                            $image = 'Error';
                            if($_FILES['uploadFile']['name'] !='')
                            {
                                $fileExists = "SELECT file_name FROM company_documents WHERE dir_id = :directory_id AND
                                file_name = :fname";
                                $fileExistsStmt = $pdo->prepare($fileExists);
                                $fileExistsStmt->bindParam(':directory_id', $_POST['DID']);
                                $fileExistsStmt->bindParam(':fname',$_FILES['uploadFile']['name']);
                                $fileExistsStmt->execute();
                                $fileInDatabase =  $fileExistsStmt->fetchColumn();
                                if($fileInDatabase){
                                    if($_POST['Agree'] == 1){
                                        $deleteFile = "DELETE FROM company_documents WHERE dir_id = :directory_id AND
                                        file_name = :fname";
                                        $deleteFileStmt = $pdo->prepare($deleteFile);
                                        $deleteFileStmt->bindParam(':directory_id', $_POST['DID']);
                                        $deleteFileStmt->bindParam(':fname', $fileInDatabase);
                                        $deleteFileStmt->execute();
                                        if(file_exists('company_files/'.$directory. '/'. $fileInDatabase)){
                                            unlink('company_files/'.$directory. '/'. $fileInDatabase);
                                        }
                                        $image = upload_Image($_FILES['uploadFile']['name'],$directory );
                                        if($image == '')
                                        {
                                            $image = 'Error';
                                        }

                                    }else{
                                        echo json_encode((Object)['msg' =>"info", 'msgDetails' => "file with the name". $fileInDatabase . " exists inside " . $directory. " directory. Do you want to replace it or you can rename the file name"]);   
                                        exit();
                                    }
                                }else{
                                    $image = upload_Image($_FILES['uploadFile']['name'],$directory );
                                        if($image == '')
                                        {
                                            $image = 'Error';
                                        }
                                }
                                
                            }
                            if($image == 'Error')
                            {
                                $pdo->rollback();
                                echo  json_encode((Object)['msg' =>"error", 'msgDetails' =>  'File not uploaded.' ]);
                            }
                            else
                            {
                                
                                $sql = "INSERT INTO `company_documents`(`file_name`,  `uploaded_by`,`dir_id`)
                                VALUES (:file_name, :uploaded_by,:dir_id)";
                                $stmt = $pdo->prepare($sql);
                                // bind parameters to statement
                                $stmt->bindParam(':file_name', $_FILES['uploadFile']['name']);
                                $stmt->bindParam(':uploaded_by', $_SESSION['user_id']);
                                $stmt->bindParam(':dir_id', $_POST['DID']);
                                // execute the prepared statement
                                $stmt->execute();
                                $pdo->commit(); 
                                echo json_encode((Object)['msg' =>"success", 'msgDetails' => 'File Uploaded Succssfully'   ]);
                            
                        
                            }
                        }
                        else
                        {
                            $pdo->rollback();
                            echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Directory does not exists with the name " . $directory . '. Better to create it or make a new directory to upload the files']);   
                        }
                }
                else
                {
                    $pdo->rollback();
                    echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Directory does not exists with the name " . $directory . '. Better to create it or make a new directory to upload the files']);   
                }
            }
            
        }catch(PDOException $e){
            $pdo->rollback();
            echo  json_encode((Object)['msg' =>"error", 'msgDetails' =>  $e->getMessage() ]);
        }
    }else if(isset($_POST["DELETE_VAR"])){
        try{
            if($delete == 1){
                // First of all, let's begin a transaction
            $pdo->beginTransaction();
            if($_POST['IsFile'] == 'true'){
                $sql = "SELECT directory_name FROM company_directories WHERE directory_id = :directory_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':directory_id', $_POST['ParentCustomID']);
                $stmt->execute();
                $directory =  $stmt->fetchColumn();
                if($directory){
                    if(is_dir('company_files/'.$directory)){
                        $fileExists = "SELECT file_name FROM company_documents WHERE dir_id = :directory_id AND
                        document_id = :document_id";
                        $fileExistsStmt = $pdo->prepare($fileExists);
                        $fileExistsStmt->bindParam(':directory_id', $_POST['ParentCustomID']);
                        $fileExistsStmt->bindParam(':document_id',$_POST['CustomID']);
                        $fileExistsStmt->execute();
                        $fileInDatabase =  $fileExistsStmt->fetchColumn();
                        $deleteFile = "DELETE FROM company_documents WHERE dir_id = :directory_id AND
                        document_id = :document_id";
                        $deleteFileStmt = $pdo->prepare($deleteFile);
                        $deleteFileStmt->bindParam(':directory_id', $_POST['ParentCustomID']);
                        $deleteFileStmt->bindParam(':document_id', $_POST['CustomID']);
                        $deleteFileStmt->execute();
                        if(file_exists('company_files/'.$directory. '/'. $fileInDatabase)){
                            unlink('company_files/'.$directory. '/'. $fileInDatabase);
                        }
                        $pdo->commit();
                        echo  json_encode((Object)['msg' =>"success", 'msgDetails' => 'File with the name '.$fileInDatabase. ' deleted successfully']);
                    }else{
                        $pdo->rollback();
                        echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Something went wrong! contact technical team"]);   
                    }
                }else{
                    $pdo->rollback();
                    echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Something went wrong! contact technical team"]);   
                }
            }else if($_POST['IsFile'] == 'false'){
                $sql = "SELECT directory_name FROM company_directories WHERE directory_id = :directory_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':directory_id', $_POST['CustomID']);
                $stmt->execute();
                $directory =  $stmt->fetchColumn();
                if($directory){
                    if(is_dir('company_files/'.$directory)){
                        // delete all files of given directory from database
                        $deleteFile = "DELETE FROM company_documents WHERE dir_id = :directory_id";
                        $deleteFileStmt = $pdo->prepare($deleteFile);
                        $deleteFileStmt->bindParam(':directory_id', $_POST['CustomID']);
                        $deleteFileStmt->execute();
                        // delete the directory from database
                        $deleteFolder = "DELETE FROM company_directories WHERE directory_id = :directory_id";
                        $deleteFolderStmt = $pdo->prepare($deleteFolder);
                        $deleteFolderStmt->bindParam(':directory_id', $_POST['CustomID']);
                        $deleteFolderStmt->execute();
                        if(file_exists('company_files/'.$directory)){
                            array_map('unlink', glob("company_files/".$directory . "/*.*"));
                            rmdir('company_files/'.$directory);
                        }
                        $pdo->commit();
                        echo  json_encode((Object)['msg' =>"success", 'msgDetails' => 'Directory deleted successfully!']);
                    }else{
                        $pdo->rollback();
                        echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Something went wrong! contact technical team"]);   
                    }
                }else{
                    $pdo->rollback();
                    echo json_encode((Object)['msg' =>"error", 'msgDetails' => "Something went wrong! contact technical team"]);   
                }
            }
           
            }         
        }catch(PDOException $e){
            $pdo->rollback();
            echo  json_encode((Object)['msg' =>"error", 'msgDetails' =>  $e->getMessage() ]);
        }
    }else if(isset($_POST['GetDocuments'])){
        if($select == 1){
            $folder = [];
        $folderFiles =  [];
        $finalArr = [];
        $selectQuery = $pdo->prepare("SELECT * FROM company_directories ORDER BY company_directories.directory_id DESC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $directories = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        
        for($i=0; $i< count($directories); $i++){

            $documentsQuery = $pdo->prepare("SELECT * FROM company_documents WHERE company_documents.dir_id = :dirID ORDER BY 
            company_documents.document_id DESC");
            $documentsQuery->bindParam(':dirID', $directories[$i]['directory_id']);
            $documentsQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $files = $documentsQuery->fetchAll(\PDO::FETCH_ASSOC);
            $folderFiles = [];
            if(count($files) > 0 ){
                for($j=0; $j< count($files); $j++){
                   array_push($folderFiles,
                        array(
                            'text' => $files[$j]['file_name'], 
                            'customID' =>  $files[$j]['document_id'],
                            'isFile' => 'true',
                            'parentCustomID' => $directories[$i]['directory_id'],
                            'type' => 'file',
                        )
                    );
                }
                array_push($finalArr,   
                        array(
                            'text' => $directories[$i]['directory_name'],
                            'parent' => '#',
                            'isFile' => 'false',
                            'customID' => $directories[$i]['directory_id'],
                            'children' => $folderFiles,
                        )
                );
            }else{
                array_push($finalArr,   
                        array(
                            'text' => $directories[$i]['directory_name'],
                            'parent' => '#',
                            'isFile' => 'false',
                            'customID' => $directories[$i]['directory_id'],
                        )
                );
            }
            
            
           
        }
        echo json_encode($finalArr);
        }
        
    }
    
    function upload_Image($companyDocument,$directory){
        $new_image_name = '';
        if($_FILES['uploadFile']['size']<=10485760){
            $extension = explode(".", $_FILES['uploadFile']['name']);
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
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt",'zip');
            if (in_array(strtolower($f_ext), $ext))
            {
                $new_image_name = 'company_files/'. $directory. '/'. $f_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploadFile']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
            
        }
        
        return $new_image_name;
    }
    
   
    // Close connection
    unset($pdo); 
?>
