<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $insert = $insert[0]['insert'];
    if($insert == 0){
      echo "<script>window.location.href='pageNotFound.php'</script>"; 
    }
    if(isset($_POST['SaveSupplier'])){
      $sql = "INSERT INTO `supplier`(`supp_name`, `supp_email`, `supp_add`, `supp_phone`, `supp_type_id`) VALUES
      (:supp_name,:supp_email,:supp_add,:supp_phone,:supp_type_id)";
       $stmt = $pdo->prepare($sql);
       // bind parameters to statement
       $stmt->bindParam(':supp_name', $_POST['Supplier_Name']);
       $stmt->bindParam(':supp_email', $_POST['Supplier_Email']);
       $stmt->bindParam(':supp_add', $_POST['Supplier_Address']);
       $stmt->bindParam(':supp_phone', $_POST['Supplier_Phone']);
       $stmt->bindParam(':supp_type_id', $_POST['SupplierTypeID']);
       $stmt->execute();
       echo "Success";
    }
    // Close connection
    unset($pdo); 
?>
