<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Payment' ";
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
     if(isset($_POST['Select_Customer'])){
        $selectQuery = $pdo->prepare("SELECT * FROM `customer` ORDER BY customer_name ASC ");
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
            if($_POST['SearchTerm'] == 'DateAndCusWise'){
                $selectQuery = $pdo->prepare("SELECT `pay_id`, `datetime`, `customer_name`, `payment_amount`,`currencyName`,
                `staff_name`,account_Name FROM `customer_payments` INNER JOIN customer ON customer.customer_id = 
                customer_payments.customer_id INNER JOIN staff ON staff.staff_id = customer_payments.staff_id INNER JOIN 
                accounts ON accounts.account_ID = customer_payments.accountID INNER JOIN currency ON currency.currencyID = 
                customer_payments.currencyID WHERE DATE(datetime) BETWEEN :fromdate AND 
                :todate AND customer_payments.customer_id = :customer_id ORDER BY pay_id DESC ");
                $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
                $selectQuery->bindParam(':todate', $_POST['Todate']);
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
            }else if($_POST['SearchTerm'] == 'DateWise'){
                $selectQuery = $pdo->prepare("SELECT `pay_id`, `datetime`, `customer_name`, `payment_amount`,`currencyName`,
                `staff_name`,account_Name FROM `customer_payments` INNER JOIN customer ON customer.customer_id = 
                customer_payments.customer_id INNER JOIN staff ON staff.staff_id = customer_payments.staff_id INNER JOIN 
                accounts ON accounts.account_ID = customer_payments.accountID INNER JOIN currency ON currency.currencyID = 
                customer_payments.currencyID WHERE DATE(datetime) BETWEEN :fromdate AND 
                :todate ORDER BY pay_id DESC ");
                $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
                $selectQuery->bindParam(':todate', $_POST['Todate']);
            }else if($_POST['SearchTerm'] == 'CusWise'){
                $selectQuery = $pdo->prepare("SELECT `pay_id`, `datetime`, `customer_name`, `payment_amount`,`currencyName`, 
                `staff_name`,account_Name FROM `customer_payments` INNER JOIN customer ON customer.customer_id = 
                customer_payments.customer_id INNER JOIN staff ON staff.staff_id = customer_payments.staff_id INNER JOIN 
                accounts ON accounts.account_ID = customer_payments.accountID INNER JOIN currency ON currency.currencyID = 
                customer_payments.currencyID WHERE customer_payments.customer_id = :customer_id ORDER BY pay_id DESC ");
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
            }else{
                $selectQuery = $pdo->prepare("SELECT `pay_id`, `datetime`, `customer_name`, `payment_amount`,`currencyName`,
                `staff_name`,account_Name FROM `customer_payments` INNER JOIN customer ON customer.customer_id = 
                customer_payments.customer_id INNER JOIN staff ON staff.staff_id = customer_payments.staff_id INNER JOIN 
                accounts ON accounts.account_ID = customer_payments.accountID INNER JOIN currency ON currency.currencyID = 
                customer_payments.currencyID ORDER BY pay_id DESC");
            }
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
            if($delete == 1){
                $sql = "DELETE FROM customer_payments WHERE pay_id = :pay_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':pay_id', $_POST['ID']);
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }        
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM customer_payments WHERE pay_id = :pay_id");
        $selectQuery->bindParam(':pay_id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_CountryName'])){
        try{
            if($update == 1){ 
                $sql = "UPDATE `customer_payments` SET customer_id = :customer_id,payment_amount=:payment_amount,
                currencyID=:currencyID,staff_id=:staff_id,accountID=:accountID WHERE pay_id = :pay_id";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['updcustomer_id']);
                $stmt->bindParam(':payment_amount', $_POST['updpayment_recieved']);
                $stmt->bindParam(':currencyID', $_POST['updpayment_currency_type']);
                $stmt->bindParam(':accountID', $_POST['updaccount_id']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':pay_id', $_POST['paymentID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }else{
            echo "<script>window.location.href='pageNotFound.php'</script>";
        }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['Payments'])){ 
        $selectQuery = $pdo->prepare("SELECT curID, (SELECT currencyName FROM currency WHERE currency.currencyID = curID) AS 
        curName, (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = 
        datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 2 AND datechange.saleCurrencyID
        = curID) + (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE 
        customer_payments.customer_id = :customer_id AND customer_payments.currencyID = curID) - ((SELECT 
        IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = :customer_id AND ticket.currencyID = curID) + 
        (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = :customer_id AND visa.saleCurrencyID = curID) + 
        (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id
        WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID
        = curID) + (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = :customer_id AND 
        hotel.saleCurrencyID = curID) + (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges INNER JOIN visa
        ON visa.visa_id = visaextracharges.visa_id WHERE visa.customer_id = :customer_id AND visaextracharges.saleCurrencyID=
        curID) + (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE residence.customer_id = :customer_id AND 
        residence.saleCurID =curID)+ (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE 
        servicedetails.customer_id = :customer_id AND servicedetails.saleCurrencyID = curID)+ (SELECT 
        IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = :customer_id AND
        car_rental.saleCurrencyID = curID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = :customer_id 
        AND loan.currencyID = curID))   AS total FROM (SELECT ticket.currencyID AS curID FROM ticket  WHERE ticket.customer_id =
        :customer_id UNION SELECT visa.saleCurrencyID AS curID FROM visa WHERE visa.customer_id = :customer_id UNION SELECT 
        visaextracharges.saleCurrencyID AS curID FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id 
        WHERE visa.customer_id = :customer_id UNION SELECT residence.saleCurID AS curID FROM residence WHERE residence.customer_id
        = :customer_id UNION SELECT servicedetails.saleCurrencyID AS curID FROM servicedetails WHERE servicedetails.customer_id = 
        :customer_id UNION SELECT datechange.saleCurrencyID AS curID FROM datechange INNER JOIN ticket ON ticket.ticket = 
        datechange.ticket_id WHERE ticket.customer_id = :customer_id UNION SELECT loan.currencyID AS curID FROM loan WHERE 
        loan.customer_id = :customer_id UNION SELECT hotel.saleCurrencyID AS curID FROM hotel WHERE hotel.customer_id = 
        :customer_id UNION SELECT car_rental.saleCurrencyID AS curID FROM car_rental WHERE car_rental.customer_id = :customer_id
        UNION SELECT customer_payments.currencyID AS curID FROM customer_payments WHERE customer_payments.customer_id = 
        :customer_id) AS baseTable HAVING total != 0 ORDER BY curName ASC ");
        $selectQuery->bindParam(':customer_id', $_POST['Addcustomer_ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['Insert_Payment'])){
        try{
                if($insert == 1){
                    // create prepared statement
                    $sql = "INSERT INTO `customer_payments`(`customer_id`, `payment_amount`,`currencyID`,
                    `staff_id`,accountID) VALUES (:customer_id, :payment_amount,:currencyID, :staff_id,:accountID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':customer_id', $_POST['Addcustomer_ID']);
                    $stmt->bindParam(':payment_amount', $_POST['Payment']);
                    $stmt->bindParam(':currencyID', $_POST['Payment_Currency_Type']);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->bindParam(':accountID',  $_POST['Addaccount_ID']);
                    // execute the prepared statement
                    $stmt->execute(); 
                    echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>