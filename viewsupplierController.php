<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0){
    echo "<script>window.location.href='pageNotFound.php'</script>";
    }
     if(isset($_POST['GetReport'])){
            $selectQuery = $pdo->prepare("SELECT `supp_id`, `supp_name`, `supp_email`, `supp_add`, `supp_phone`, CASE
            WHEN supp_type_id = 1 THEN 'Travel' ELSE 'Exchange' END AS supp_type FROM `supplier` ORDER BY supp_name ASC ");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($supplier);
    }else if(isset($_POST['Delete'])){
        try{
                  if($delete == 1){
                        $sql = "DELETE FROM supplier WHERE supp_id = :supp_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':supp_id', $_POST['SuppID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "NoPermission";
                }  
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdSupplier'])){
            $selectQuery = $pdo->prepare("SELECT * FROM supplier WHERE supp_id=:supp_id");
            $selectQuery->bindParam(':supp_id', $_POST['SuppID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateSupplier'])){
        try{
            if($update == 1){
                         $sql = "UPDATE `supplier` SET supp_name =:supp_name, supp_email =:supp_email, supp_add=:supp_add,
                         supp_phone =:supp_phone,supp_type_id=:supp_type_id WHERE supp_id =:supp_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':supp_name', $_POST['Supplier_Name']);
                         $stmt->bindParam(':supp_email', $_POST['Supplier_Email']);
                         $stmt->bindParam(':supp_add', $_POST['Supplier_Address']);
                         $stmt->bindParam(':supp_phone', $_POST['Supplier_Phone']);
                         $stmt->bindParam(':supp_type_id', $_POST['Supplier_Type']);
                         $stmt->bindParam(':supp_id', $_POST['SupplierID']);
                         $stmt->execute();
                echo "Success";
            }else{
                echo "NoPermission";
            }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>