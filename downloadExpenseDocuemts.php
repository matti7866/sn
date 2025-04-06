<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Expenses' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>";
    }
    if($select == 1){
    if(!empty($_GET['file'])){
        $fileName  = basename($_GET['file']);
        $filePath  = "expense_documents/".$fileName;
        $originalName = basename($_GET['originalName']);
        if(!empty($fileName) && file_exists($filePath)){
            //define header
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$originalName");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            
            //read file 
            readfile($filePath);
            exit;
        }
        else{
            echo "file not exit";
        }
    }
}
  // Close connection
  unset($pdo); 
?>
  