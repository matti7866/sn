<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    try{
        $pdo = new PDO('mysql:host=selabnadiry33026.domaincommysql.com;dbname=sntravel', 'sntravel', 'Afghan@786');
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "ERROR: Could not connect. " . $e->getMessage();
    }
    if(isset($_POST['SELECT_CUSTOMER'])){
        $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
        as customer_name FROM customer ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['Payments'])){
        $selectQuery = $pdo->prepare("SELECT IFNULL(SUM(sale),0) + (SELECT 
        IFNULL(SUM(sale),0) FROM visa WHERE customer_id = :customer_id ) AS net_payable,
        (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE 
        customer_id = :customer_id ) AS paid, IFNULL(SUM(sale),0) +
        (SELECT IFNULL(SUM(sale),0) FROM visa WHERE customer_id = :customer_id) - 
        (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE customer_id =
        :customer_id ) as balance FROM ticket WHERE customer_id =:customer_id");
        $selectQuery->bindParam(':customer_id', $_POST['customer_id']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetch(PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['Insert_Payment'])){
        try{
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`, `payment_amount`, 
                `staff_id`) VALUES (:customer_id, :payment_amount, :staff_id)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['Cus_Name']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                 // execute the prepared statement
                 $stmt->execute(); 
            echo "Records inserted successfully.";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>