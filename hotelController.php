<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Hotel' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $insert = $insert[0]['insert'];
    if($insert == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>"; 
    }
    if(isset($_POST['SELECT_CUSTOMER'])){
        $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
        as customer_name FROM customer ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['Select_Country'])){
        $selectQuery = $pdo->prepare("SELECT * FROM country_name ORDER BY country_names ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $country = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($country);
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER BY supp_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }if(isset($_POST['Insert_Hotel'])){
        try{
                $pdo->beginTransaction();
                $sql = "INSERT INTO `hotel`(`customer_id`,`passenger_name`, `supplier_id`, `hotel_name`, `checkin_date`, 
                `checkout_date`,`net_price`,`netCurrencyID`, `sale_price`,`saleCurrencyID`, `country_id`, `staffID`) 
                VALUES(:customer_id,:passenger_name,:supplier_id,:hotel_name,:checkin_date,:checkout_date,:net_price,
                :netCurrencyID,:sale_price,:saleCurrencyID,:country_id,:staffID) ";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['customer_id']);
                $stmt->bindParam(':passenger_name', $_POST['passenger_name']);
                $stmt->bindParam(':supplier_id', $_POST['supplier_id']);
                $stmt->bindParam(':hotel_name', $_POST['hotel_name']);
                $stmt->bindParam(':checkin_date', $_POST['checkin_date']);
                $stmt->bindParam(':checkout_date', $_POST['checkout_date']);
                $stmt->bindParam(':net_price', $_POST['net_price']);
                $stmt->bindParam(':netCurrencyID', $_POST['net_currency_type']);
                $stmt->bindParam(':sale_price', $_POST['sale_price']);
                $stmt->bindParam(':saleCurrencyID', $_POST['sale_currency_type']);
                $stmt->bindParam(':country_id', $_POST['country_id']);
                $stmt->bindParam(':staffID',$_SESSION['user_id']);
                $stmt->execute();
                // Now its time to handle the customer payment
                 // create prepared statement
                 if($_POST['cus_payment'] !=''){
                    $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,`accountID`)
                    VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':customer_id',$_POST['customer_id']);
                    $stmt->bindParam(':payment_amount', $_POST['cus_payment']);
                    $stmt->bindParam(':currencyID', $_POST['cusPayment_currency_type']);
                    $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                    $stmt->bindParam(':accountID', $_POST['addaccount_id']);
                    // execute the prepared statement
                    $stmt->execute();
                 }
                 $pdo->commit();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>