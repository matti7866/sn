<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Company Documents' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['insert'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>";
    }
    
    try {
        if($select == 1){
        $image = '';
            if($_FILES['uploadFile']['name'] !=''){
                $image = upload_Image($_FILES['uploadFile']['name']);
                if($image == ''){
                    $image = 'Error';
                }
            }
             // First of all, let's begin a transaction
             $pdo->beginTransaction();
             if($image == 'Error')
             {
                 $pdo->rollback();
                 echo "Record not added becuase of file uploader";
             }else{
                
                 $sql = "INSERT INTO `company_documents`(`document_name`, `document`,`file_name`,  `uploaded_by`)
                 VALUES (:document_name,:document, :file_name, :uploaded_by)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':document_name', $_POST['document_title']);
                $stmt->bindParam(':document', $image);
                $stmt->bindParam(':file_name', $_FILES['uploadFile']['name']);
                $stmt->bindParam(':uploaded_by', $_SESSION['user_id']);
                // execute the prepared statement
                $stmt->execute();
                $pdo->commit(); 
                echo "Success";
             }
            }

    } catch (\Throwable $th) {
        echo $th;
    }
    
    function upload_Image($companyDocument){
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
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'company_files/'. $new_image_name. '.' .$f_ext;
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
