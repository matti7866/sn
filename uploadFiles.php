<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Service' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
    if($select == 1){
    $errorOutput = [];
    $successOutput = [];
    if($_FILES['file']['name'] != ''){
        $files_names = '';
        $total = count($_FILES['file']['name']);
        for($i = 0; $i< $total; $i++ ){
            $file_name = $_FILES['file']['name'][$i];
            $file_size = $_FILES['file']['size'][$i];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $valid_extensions = array('jpg','png', 'jpeg','doc','docx','pdf','gif','txt','csv','ppt','pptx','rar','xls','xlsx','zip');
            if(in_array($extension, $valid_extensions)){
                if($file_size <=10485760){
                    $new_name = rand() . '.'. $extension;
                    $path = "service/". $new_name;
                    move_uploaded_file($_FILES['file']['tmp_name'][$i],$path );
                    if(!$_FILES["file"]["error"][$i]){
                        $sql="INSERT INTO `servicedocuments`(`detailServiceID`, `file_name`, `original_name`) VALUES 
                        (:detailServiceID,:file_name,:original_name)"; 
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':detailServiceID', $_POST['userID']);
                        $stmt->bindParam(':file_name', $new_name);
                        $stmt->bindParam(':original_name', $file_name);
                        $stmt->execute();
                        array_push($successOutput, $file_name);
                    }else{
                        array_push($errorOutput, $file_name, 'Internal Server Error');
                    }
                }else{
                    array_push($errorOutput, $file_name,  'Exceed limit of ' . $file_size);
                }
            }else{
                array_push($errorOutput, $file_name, ' Wrong file extension');
            }
        }
        
    }else{
        array_push($errorOutput, '', ' No file to upload');
    }
    //return right HTTP code
    header( 'Content-Type: application/json; charset=utf-8' );
    if(count($errorOutput) > 0 && count($successOutput) > 0 ){
        http_response_code (200);
        echo json_encode( ['both',$errorOutput,$successOutput] );
    }
    else if( count($errorOutput) > 0 ){
        http_response_code (500);
        echo json_encode( $errorOutput );
    }
    else if(count($successOutput) > 0){
        http_response_code (200);
        echo json_encode( $successOutput);
    }
    }else{
       echo 'No permission to upload file';
    }

    //set Content-Type to JSON
    
    //echo error message as JSON
    
?>