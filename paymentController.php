<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier Payment' ";
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
    if(isset($_POST['Insert_CountryName'])){
        try{
              if($insert == 1){
                $sql = "INSERT INTO `payment`(`supp_id`, `payment_amount`,`currencyID`, `payment_detail`, `staff_id`,accountID) 
                VALUES(:supp_id,:payment_amount,:currencyID,:payment_detail,:staff_id,:accountID)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':supp_id', $_POST['addsupplier_id']);
                $stmt->bindParam(':payment_amount', $_POST['payment_amount']);
                $stmt->bindParam(':currencyID', $_POST['payment_currency_type']);
                $stmt->bindParam(':payment_detail', $_POST['payment_detail']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['addaccount_id']);
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
              }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
              }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER BY supp_name ASC ");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['Select_Accounts'])){
        $selectQuery = $pdo->prepare("SELECT `account_ID`, `account_Name` FROM `accounts`ORDER BY account_Name ASC ");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetpaymentReport'])){
            if($_POST['SearchTerm'] == 'DateAndSupWise'){
                $selectQuery = $pdo->prepare("SELECT payment_id, supp_name, `payment_amount`,`currencyName`,`payment_detail`
                ,staff_name,`time_creation`,account_Name FROM `payment` INNER JOIN supplier ON supplier.supp_id = payment.supp_id 
                INNER JOIN staff ON staff.staff_id = payment.staff_id INNER JOIN accounts ON accounts.account_ID =
                payment.accountID INNER JOIN currency ON currency.currencyID = payment.currencyID 
                WHERE DATE(time_creation) BETWEEN :fromdate AND :todate AND payment.supp_id = :SupplierID 
                ORDER BY payment_id DESC ");
                $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
                $selectQuery->bindParam(':todate', $_POST['Todate']);
                $selectQuery->bindParam(':SupplierID', $_POST['Supplier_ID']);
            }else if($_POST['SearchTerm'] == 'DateWise'){
                $selectQuery = $pdo->prepare("SELECT payment_id, supp_name, `payment_amount`,`currencyName`,`payment_detail`
                ,staff_name,`time_creation`,account_Name FROM `payment` INNER JOIN supplier ON supplier.supp_id = payment.supp_id
                INNER JOIN staff ON staff.staff_id = payment.staff_id INNER JOIN accounts ON accounts.account_ID =
                payment.accountID INNER JOIN currency ON currency.currencyID = payment.currencyID WHERE DATE(time_creation)
                BETWEEN :fromdate AND :todate ORDER BY payment_id DESC ");
                $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
                $selectQuery->bindParam(':todate', $_POST['Todate']);
            }else if($_POST['SearchTerm'] == 'SupWise'){
                $selectQuery = $pdo->prepare("SELECT payment_id, supp_name, `payment_amount`,`currencyName`,`payment_detail`
                ,staff_name,`time_creation`,account_Name FROM `payment` INNER JOIN supplier ON supplier.supp_id = payment.supp_id
                INNER JOIN staff ON staff.staff_id = payment.staff_id INNER JOIN accounts ON accounts.account_ID =
                payment.accountID INNER JOIN currency ON currency.currencyID = payment.currencyID
                WHERE payment.supp_id = :SupplierID ORDER BY payment_id DESC ");
                $selectQuery->bindParam(':SupplierID', $_POST['Supplier_ID']);
            }else{
                $selectQuery = $pdo->prepare("SELECT payment_id, supp_name, `payment_amount`,`currencyName`,`payment_detail`
                ,staff_name,`time_creation`,account_Name FROM `payment` INNER JOIN supplier ON supplier.supp_id = payment.supp_id 
                INNER JOIN staff ON staff.staff_id = payment.staff_id INNER JOIN accounts ON accounts.account_ID = 
                payment.accountID INNER JOIN currency ON currency.currencyID = payment.currencyID  ORDER BY payment_id DESC");
            }
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
            if($delete == 1){
                $sql = "DELETE FROM payment WHERE payment_id = :payment_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':payment_id', $_POST['ID']);
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }        
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM payment WHERE payment_id = :payment_id");
        $selectQuery->bindParam(':payment_id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Payments'])){ 
        $selectQuery = $pdo->prepare("SELECT curID, (SELECT currencyName FROM currency WHERE currency.currencyID = curID) AS 
        curName, (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = 
        datechange.ticket_id WHERE ticket.supp_id = :supplier_id AND datechange.ticketStatus = 2 AND datechange.netCurrencyID = 
        curID) + (SELECT IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = :supplier_id AND 
        payment.currencyID = curID) - ((SELECT IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = :supplier_id
        AND ticket.net_CurrencyID = curID) + (SELECT IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = :supplier_id 
        AND visa.netCurrencyID = curID) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange INNER JOIN ticket ON 
        ticket.ticket = datechange.ticket_id WHERE ticket.supp_id = :supplier_id AND datechange.ticketStatus = 1 AND 
        datechange.netCurrencyID = curID) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM hotel WHERE hotel.supplier_id = 
        :supplier_id AND hotel.netCurrencyID = curID) + (SELECT IFNULL(SUM(residence.net_price),0) FROM 
        residence WHERE residence.supplier = :supplier_id AND residence.netCurID = curID)+ (SELECT 
        IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = :supplier_id AND 
        servicedetails.netCurrencyID = curID ) +(SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges 
        WHERE visaextracharges.supplierID = :supplier_id AND visaextracharges.netCurrencyID = curID) + (SELECT 
        IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = :supplier_id AND 
        car_rental.netCurrencyID = curID))   AS total FROM (SELECT  ticket.net_CurrencyID AS curID FROM ticket  WHERE 
        ticket.supp_id = :supplier_id UNION SELECT visa.netCurrencyID AS curID FROM visa WHERE visa.supp_id = :supplier_id
        UNION SELECT residence.netCurID AS curID FROM residence WHERE residence.supplier = :supplier_id UNION SELECT 
        servicedetails.netCurrencyID  AS curID FROM servicedetails WHERE servicedetails.Supplier_id = :supplier_id UNION SELECT
        visaextracharges.netCurrencyID AS curID FROM visaextracharges WHERE visaextracharges.supplierID = :supplier_id UNION 
        SELECT datechange.netCurrencyID AS curID FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE 
        ticket.supp_id = :supplier_id UNION SELECT hotel.netCurrencyID AS    curID FROM hotel WHERE hotel.supplier_id = 
        :supplier_id UNION SELECT car_rental.netCurrencyID AS curID FROM car_rental WHERE car_rental.supplier_id = :supplier_id
        UNION SELECT payment.currencyID AS curID FROM payment WHERE payment.supp_id = :supplier_id) AS baseTable HAVING total != 0 ORDER BY curName ASC ");
        $selectQuery->bindParam(':supplier_id', $_POST['Addsupplier_ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['Update_CountryName'])){
        try{
            if($update == 1){ 
                $sql = "UPDATE `payment` SET supp_id = :supp_id,payment_amount=:payment_amount,currencyID = :currencyID,
                payment_detail=:payment_detail,staff_id=:staff_id WHERE payment_id = :payment_id";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':supp_id', $_POST['updsupplier_id']);
                $stmt->bindParam(':payment_amount', $_POST['updpayment_amount']);
                $stmt->bindParam(':currencyID', $_POST['updpayment_currency_type']);
                $stmt->bindParam(':payment_detail', $_POST['updpayment_detail']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':payment_id', $_POST['paymentID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }else{
            echo "<script>window.location.href='pageNotFound.php'</script>";
        }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>