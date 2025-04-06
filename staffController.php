<?php
//ini_set('session.save_path', '/tmp');
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Staff' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}

    if(isset($_POST['GetBranch'])){
        $selectQuery = $pdo->prepare("SELECT * FROM branch ORDER BY Branch_ID ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $branch = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($branch);
    }else if(isset($_POST['GetRole'])){
        $selectQuery = $pdo->prepare("SELECT * FROM roles ORDER BY role_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $roles = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($roles);
    }else if(isset($_POST['Insert_Staff'])){
        try{
            if($insert == 1){
                $image = '';
            if($_FILES['uploadFile']['name'] !=''){
                $image = upload_Image($_FILES['uploadFile']['name']);
                if($image == ''){
                    $image = 'Error';
                }
            }
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }
                else if($image == ''){
                    $sql = "INSERT INTO `staff`(`staff_name`, `Password`, `staff_phone`, `staff_email`, `staff_address`,
                     `staff_branchID`, `role_id`, `status`, salary,currencyID) VALUES  (:staff_name, :Password, :staff_phone,
                     :staff_email,:staff_address,:staff_branchID,:role_id,:status,:salary,:currencyID)";
                }else{
                    $sql = "INSERT INTO `staff`(`staff_name`, `Password`, `staff_phone`, `staff_email`, `staff_address`,
                    `staff_pic`, `staff_branchID`, `role_id`, `status`,salary,currencyID) VALUES  (:staff_name, :Password, :staff_phone,
                    :staff_email,:staff_address,:staff_pic,:staff_branchID,:role_id,:status,:salary,:currencyID)";
                }
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':staff_name', $_POST['employee_name']);
                $stmt->bindParam(':Password', $_POST['employee_password']);
                $stmt->bindParam(':staff_phone', $_POST['employee_phone']);
                $stmt->bindParam(':staff_email', $_POST['employee_email']);
                $stmt->bindParam(':staff_address', $_POST['employee_address']);
                $stmt->bindParam(':staff_branchID', $_POST['employee_branch']);
                $stmt->bindParam(':role_id', $_POST['employee_role']);
                $stmt->bindParam(':status', $_POST['employee_status']);
                $stmt->bindParam(':salary', $_POST['employee_salary']);
                $stmt->bindParam(':currencyID', $_POST['currency_type']);
                
                if($image != ''){
                    $stmt->bindParam(':staff_pic', $image);
                }
                // execute the prepared statement
                $stmt->execute();
                $pdo->commit(); 
            echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }
            
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetStaffReport'])){
            $selectQuery = $pdo->prepare("SELECT `staff_id`, `staff_name`, `staff_phone`, `staff_email`, `staff_address`,
            `staff_pic`, branch.Branch_Name, roles.role_name, CASE WHEN status = 1 THEN 'Active' ELSE 'Deactive' END AS 
            status,salary,currencyName FROM `staff` INNER JOIN branch ON staff.staff_branchID = branch.Branch_ID INNER JOIN
            roles ON roles.role_id = staff.role_id INNER JOIN currency ON currency.currencyID = staff.currencyID  ORDER BY 
            staff_name ASC");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
                if($delete == 1){
                    // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                    $sql = "SELECT staff_pic FROM staff WHERE staff_id = :staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['staff_pic'];  
                    if(file_exists($file)){
                        unlink($file);
                    }else{

                    }           
                    if(!is_file($file)) {
                        $sql = "DELETE FROM staff WHERE staff_id = :staff_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':staff_id', $_POST['ID']);
                        $stmt->execute();
                    }else{
                        $pdo->rollback();
                    }
                $pdo->commit();
                echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
                
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT `staff_name`,`staff_phone`, `staff_email`, `staff_address`, 
        `staff_branchID`, `role_id`, `status`, `salary`, currencyID FROM `staff` WHERE staff_id = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_Staff'])){
        try{
            if($update == 1){
                $image = '';
            if($_FILES['upduploadFile']['name'] !=''){
                
                $image = updupload_Image($_FILES['upduploadFile']['name']);
                if($image == ''){
                    $image = 'Error';
                }
            }
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }
                else if($image == ''){
                    if($_POST['updemployee_password'] == ""){
                        $sql = "UPDATE `staff` SET staff_name = :staff_name, staff_phone=:staff_phone, staff_email=
                        :staff_email, staff_address =:staff_address,staff_branchID =:staff_branchID,role_id=:role_id,
                        status=:status, salary=:salary,currencyID = :currencyID WHERE staff_id = :staff_id";
                    }else{
                        $sql = "UPDATE `staff` SET staff_name = :staff_name,Password =:Password, staff_phone=:staff_phone,
                        staff_email=:staff_email, staff_address =:staff_address,staff_branchID =:staff_branchID,role_id=
                        :role_id,status=:status, salary=:salary, currencyID = :currencyID WHERE staff_id = :staff_id";
                    }
                    
                }else{
                    if($_POST['updemployee_password'] == ""){
                        $sql = "UPDATE `staff` SET staff_name = :staff_name, staff_phone=:staff_phone, staff_email=
                        :staff_email, staff_address =:staff_address,staff_branchID =:staff_branchID,role_id=:role_id,
                        status=:status, salary=:salary,currencyID=:currencyID, staff_pic =:staff_pic WHERE staff_id = :staff_id";
                    }else{
                        $sql = "UPDATE `staff` SET staff_name = :staff_name,Password =:Password, staff_phone=:staff_phone,
                        staff_email=:staff_email, staff_address =:staff_address,staff_branchID =:staff_branchID,role_id=
                        :role_id,status=:status, salary=:salary,currencyID=:currencyID, staff_pic=:staff_pic WHERE staff_id = :staff_id";
                    }
                }
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':staff_name', $_POST['updemployee_name']);
                if($_POST['updemployee_password'] != ""){
                    $stmt->bindParam(':Password', $_POST['updemployee_password']);
                }
                $stmt->bindParam(':staff_phone', $_POST['updemployee_phone']);
                $stmt->bindParam(':staff_email', $_POST['updemployee_email']);
                $stmt->bindParam(':staff_address', $_POST['updemployee_address']);
                $stmt->bindParam(':staff_branchID', $_POST['updemployee_branch']);
                $stmt->bindParam(':role_id', $_POST['updemployee_role']);
                $stmt->bindParam(':status', $_POST['updemployee_status']);
                $stmt->bindParam(':salary', $_POST['updemployee_salary']);
                $stmt->bindParam(':currencyID', $_POST['updcurrency_type']);
                if($image != ''){
                        $current_pictureDelete = "SELECT staff_pic FROM staff WHERE staff_id = :staff_id";
                        $deleteStatement = $pdo->prepare($current_pictureDelete);
                        $deleteStatement->bindParam(':staff_id', $_POST['employee_id']);
                        $deleteStatement->execute();
                        $file =  $deleteStatement->fetchAll(\PDO::FETCH_ASSOC);
                        $file =  $file[0]['staff_pic'];  
                        if(file_exists($file)){
                            unlink($file);
                        }
                    $stmt->bindParam(':staff_pic', $image);
                }
                $stmt->bindParam(':staff_id', $_POST['employee_id']);
                // execute the prepared statement
                $stmt->execute();
                $pdo->commit(); 
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }
            
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    function upload_Image($staff_photo){
        $new_image_name = '';
        if($_FILES['uploadFile']['size']<=2097152){
            $completeFile = '';
            $extension = explode(".", $staff_photo);
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            // check if file has more dots in the names
            if(count($extension) != 2){
                // we loop through the file name and append every portion of the file to the complete file variable except extension
                foreach ($extension as $key => $element) {
                    if ($key === array_key_last($extension)) {
                        $extension =  $element;
                    }else{
                        $completeFile .= '.'.$element;
                    }
                }
            }else{
                $extension = $extension[1];
            }
            if (in_array(strtolower($extension), $ext))
            {
                $new_image_name = $completeFile. "." . date("Y/m/d h:i:s") . $extension;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'staff/'. $new_image_name. '.' .$extension;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploadFile']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
            
        }
        return $new_image_name;
    }
    
    function updupload_Image($staff_photo){
        $new_image_name = '';
        if($_FILES['upduploadFile']['size']<=2097152){
            $completeFile = '';
            $extension = explode(".", $staff_photo);
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            // check if file has more dots in the names
            if(count($extension) != 2){
                // we loop through the file name and append every portion of the file to the complete file variable except extension
                foreach ($extension as $key => $element) {
                    if ($key === array_key_last($extension)) {
                        $extension =  $element;
                    }else{
                        $completeFile .= '.'.$element;
                    }
                }
            }else{
                $extension = $extension[1];
            }
            if (in_array(strtolower($extension), $ext))
            {
                $new_image_name = $completeFile. "." . date("Y/m/d h:i:s") . $extension;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'staff/'. $new_image_name. '.' .$extension;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['upduploadFile']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
            
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>