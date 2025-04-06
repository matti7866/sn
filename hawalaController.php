<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Hawala' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $insert = $insert[0]['insert'];
    if($insert == 0){
    echo "<script>window.location.href='pageNotFound.php'</script>";
      
    }
    if(isset($_POST['Select_Country'])){
        $selectQuery = $pdo->prepare("SELECT * FROM hawala_countries ORDER BY country_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $country = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($country);
    }else if(isset($_POST['Insert_Hawala'])){
        try{
                $sql = "INSERT INTO `hawala`(`customer_id`, `supplier_id`, `sender_name`, `receiver_name`, `net_amount`,
                `supp_comm`, `sale_amount`, `cust_comm`, `country_id_from`, `country_id_to`, `staffID`) VALUES
                (:customer_id,:supplier_id,:sender_name,:receiver_name,:net_price,:supp_comm,:sale_price,:cust_comm,
                :country_id_from,:country_id_to,:staffID) ";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['customer_id']);
                $stmt->bindParam(':supplier_id', $_POST['supplier_id']);
                $stmt->bindParam(':sender_name', $_POST['sender_name']);
                $stmt->bindParam(':receiver_name', $_POST['reciver_name']);
                $stmt->bindParam(':net_price', $_POST['net_price']);
                $stmt->bindParam(':supp_comm', $_POST['supplier_commission']);
                $stmt->bindParam(':sale_price', $_POST['sale_price']);
                $stmt->bindParam(':cust_comm', $_POST['customer_commission']);
                $stmt->bindParam(':country_id_from', $_POST['from_country']);
                $stmt->bindParam(':country_id_to', $_POST['to_country']);
                $stmt->bindParam(':staffID',$_SESSION['user_id']);
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>