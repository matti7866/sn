<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Hawala' ";
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
    if(isset($_POST['SELECT_CUSTOMER'])){
        if($_POST['Type'] == "byAll"){
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name FROM customer ORDER BY customer_name ASC");
            $selectQuery->execute();
        }else{
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name,(SELECT DISTINCT customer_id FROM customer WHERE customer_id =:customer_id) AS 
            selectedCustomer FROM customer ORDER BY customer_name ASC");
            $selectQuery->bindParam(':customer_id', $_POST['ID']);
            $selectQuery->execute();
        }
        
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['SearchHawala'])){
        if($_POST['SearchTerm'] == 'byAllTerms'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.customer_id = :customer_id AND hawala.sender_name 
             LIKE CONCAT('%',:sender_name,'%') AND hawala.receiver_name LIKE CONCAT('%',:receiver_name,'%') ORDER BY 
             hawala_id DESC");
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
             $selectQuery->bindParam(':sender_name', $_POST['Sender_Name']);
             $selectQuery->bindParam(':receiver_name', $_POST['Reciver_Name']);
        }else if($_POST['SearchTerm'] == 'byCusSdr'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.customer_id = :customer_id AND hawala.sender_name 
             LIKE CONCAT('%',:sender_name,'%') ORDER BY hawala_id DESC");
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
             $selectQuery->bindParam(':sender_name', $_POST['Sender_Name']);
        }else if($_POST['SearchTerm'] == 'byCusRcr'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.customer_id = :customer_id AND hawala.receiver_name LIKE 
             CONCAT('%',:receiver_name,'%') ORDER BY hawala_id DESC");
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
             $selectQuery->bindParam(':receiver_name', $_POST['Reciver_Name']);
        }else if($_POST['SearchTerm'] == 'bySenRec'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.sender_name LIKE CONCAT('%',:sender_name,'%') AND 
             hawala.receiver_name LIKE CONCAT('%',:receiver_name,'%') ORDER BY hawala_id DESC");
             $selectQuery->bindParam(':sender_name', $_POST['Sender_Name']);
             $selectQuery->bindParam(':receiver_name', $_POST['Reciver_Name']);
        }else if($_POST['SearchTerm'] == 'bySend'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.sender_name LIKE CONCAT('%',:sender_name,'%') ORDER BY 
             hawala_id DESC");
             $selectQuery->bindParam(':sender_name', $_POST['Sender_Name']);
        }else if($_POST['SearchTerm'] == 'byRec'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.receiver_name LIKE CONCAT('%',:receiver_name,'%') ORDER BY 
             hawala_id DESC");
             $selectQuery->bindParam(':receiver_name', $_POST['Reciver_Name']);
        }else if($_POST['SearchTerm'] == 'byCus'){
            $selectQuery = $pdo->prepare("SELECT hawala_id,customer.customer_name,supplier.supp_name,sender_name,
            receiver_name,net_amount,supp_comm,sale_amount,cust_comm, fromcountry.country_name AS fromcountry, 
            tocountry.country_name AS tocountry,datetime,staff_name FROM `hawala` INNER JOIN customer ON 
            customer.customer_id = hawala.customer_id INNER JOIN supplier ON supplier.supp_id = hawala.supplier_id INNER
             JOIN hawala_countries AS fromcountry ON fromcountry.country_id = hawala.country_id_from INNER JOIN 
             hawala_countries AS tocountry ON tocountry.country_id = hawala.country_id_to INNER JOIN staff ON 
             staff.staff_id = hawala.staffID WHERE hawala.customer_id = :customer_id ORDER BY hawala_id DESC");
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $hawala = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($hawala);
    }else if(isset($_POST['Delete'])){
        try{
                  if($delete == 1){
                        $sql = "DELETE FROM hawala WHERE hawala_id = :hawala_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':hawala_id', $_POST['Hawala_ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "NoPermission";
                }
               
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdHawala'])){
            $selectQuery = $pdo->prepare("SELECT * FROM hawala WHERE hawala_id=:hawala_id");
            $selectQuery->bindParam(':hawala_id', $_POST['Hawala_ID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateHawala'])){
        try{
            if($update == 1){
                         $sql = "UPDATE `hawala` SET `customer_id`=:updcustomer_id,`supplier_id`=:supplier_id,
                         `sender_name`=:sender_name,`receiver_name`=:receiver_name,`net_amount`=:net_amount,
                         `supp_comm`=:supp_comm,`sale_amount`=:sale_amount,`cust_comm`=:cust_comm,country_id_from =
                         :country_id_from, country_id_to =:country_id_to, `staffID`=:staffID
                         WHERE hawala_id =:hawala_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':updcustomer_id', $_POST['Updcustomer_id']);
                         $stmt->bindParam(':supplier_id', $_POST['Supplier_ID']);
                         $stmt->bindParam(':sender_name', $_POST['Updsender_Name']);
                         $stmt->bindParam(':receiver_name', $_POST['Updreciver_Name']);
                         $stmt->bindParam(':net_amount', $_POST['Net_Price']);
                         $stmt->bindParam(':supp_comm', $_POST['Supplier_Commission']);
                         $stmt->bindParam(':sale_amount', $_POST['Sale_Price']);
                         $stmt->bindParam(':cust_comm', $_POST['Customer_Commission']);
                         $stmt->bindParam(':country_id_from', $_POST['From_Country']);
                         $stmt->bindParam(':country_id_to', $_POST['To_Country']);
                         $stmt->bindParam(':staffID',$_SESSION['user_id']);
                         $stmt->bindParam(':hawala_id',$_POST['HawalaID']);
                         $stmt->execute();
                echo "Success";
            }else{
                echo "NoPermission";
            }
               
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>