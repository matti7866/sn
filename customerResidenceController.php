<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
   $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Ticket' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$insert = $insert[0]['insert'];
if($insert == 0){
echo "<script>window.location.href='pageNotFound.php'</script>";
  
}

if(isset($_POST['INSERT'])){
        try{
            // create prepared statement
            $sql = "INSERT INTO customer (customer_name, customer_phone, customer_whatsapp, customer_address) 
            VALUES (:customer_name, :customer_phone, :customer_whatsapp, :customer_address)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':customer_name', $_POST['Cus_Name']);
            $stmt->bindParam(':customer_phone', $_POST['Cus_Phone']);
            $stmt->bindParam(':customer_whatsapp', $_POST['Cus_Whatsapp']);
            $stmt->bindParam(':customer_address', $_POST['Cus_Address']);
            // execute the prepared statement
            $stmt->execute();
            echo "Records inserted successfully.";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['SELECT_CUSTOMER'])){
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
    }else if(isset($_POST['SELECT_FROM'])){
        $selectQuery = $pdo->prepare("SELECT airport_id, airport_code FROM airports");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $from = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($from);
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['Payments'])){ 
        $selectQuery = $pdo->prepare("SELECT curID, (SELECT currencyName FROM currency WHERE currency.currencyID = curID) AS 
        curName, (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN
        ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 2
        AND datechange.saleCurrencyID = curID) + (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments
        WHERE customer_payments.customer_id = :customer_id AND customer_payments.currencyID = curID) - ((SELECT IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = :customer_id AND ticket.currencyID
        = curID) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = :customer_id AND visa.saleCurrencyID = 
        curID) + (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = 
        datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID
        = curID) + (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = :customer_id AND 
        hotel.saleCurrencyID = curID) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id
        = :customer_id AND car_rental.saleCurrencyID = curID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = 
        :customer_id AND loan.currencyID = curID))   AS total FROM (SELECT 
        ticket.currencyID AS curID FROM ticket  WHERE ticket.customer_id = :customer_id UNION SELECT visa.saleCurrencyID AS curID
        FROM visa WHERE visa.customer_id = :customer_id UNION SELECT datechange.saleCurrencyID AS curID FROM datechange INNER 
        JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id UNION SELECT loan.currencyID
        AS curID FROM loan WHERE loan.customer_id = :customer_id UNION SELECT hotel.saleCurrencyID AS curID FROM hotel WHERE 
        hotel.customer_id = :customer_id UNION SELECT car_rental.saleCurrencyID AS curID FROM car_rental WHERE 
        car_rental.customer_id = :customer_id UNION SELECT customer_payments.currencyID AS curID FROM customer_payments WHERE 
        customer_payments.customer_id = :customer_id) AS baseTable HAVING total != 0 ORDER BY curName ASC ");
        $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['GetTotal'])){
        $selectQuery = $pdo->prepare("SELECT IFNULL(SUM(sale),0) + (SELECT IFNULL(SUM(sale),0) FROM visa WHERE
        customer_id = :cust_name) + (SELECT IFNULL(SUM(sale_price),0) FROM hotel WHERE customer_id = :cust_name) +
        (SELECT IFNULL(SUM(sale_price),0) FROM car_rental WHERE customer_id = :cust_name) + (SELECT 
        IFNULL(SUM(sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE 
        ticket.customer_id = :cust_name AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(amount),0) FROM loan WHERE 
        customer_id = :cust_name) - (SELECT IFNULL(SUM(sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket
        = datechange.ticket_id WHERE ticket.customer_id = :cust_name AND datechange.ticketStatus=2) - (SELECT 
        IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE customer_id = :cust_name) AS Total FROM ticket WHERE 
        customer_id = :cust_name ");
        $selectQuery->bindParam(':cust_name', $_POST['Cust_Name']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $GetTotal = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($GetTotal);
    }if(isset($_POST['Insert_Ticket'])){
        try{
            $image = '';
            if($_FILES['uploadFile']['name'] !=''){
                $image = upload_Image($_FILES['uploadFile']['name']);
                if($image == ''){
                    $image = 'Error';
                }
            }
            $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
            // If Customer pays on the spot
            if($_POST['cus_payment']){
                $type =  1;
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }else{
                    $decodePassArr = json_decode($_POST['passArr']);
                    $decodeticketNumberArr = json_decode($_POST['ticketNumberArr']);
                    $decodenetAmountArr = json_decode($_POST['netAmountArr']);
                    $decodenetPriceCurrencyArr = json_decode($_POST['netPriceCurrencyArr']);
                    $decodesaleAmountArr = json_decode($_POST['saleAmountArr']);
                    $decodesalePriceCurrencyArr = json_decode($_POST['salePriceCurrencyArr']);
                for($i=0;$i < count($decodePassArr);$i++){
                if($image == ''){
                    $sql = "INSERT INTO `ticket` (`ticketNumber`,`Pnr`, `customer_id`, `passenger_name`, 
                    `date_of_travel`,`return_date`, `from_id`, `to_id`, `sale`,`currencyID`, `staff_id`, `supp_id`, `net_price`,`net_CurrencyID`,`branchID`)
                    VALUES (:ticketNumber,:pnr, :customer_id, :passenger_name,:date_of_travel,:return_date,
                    :from_id,:to_id,:sale,:currencyID,:staff_id,:supp_id,:net_price,:net_CurrencyID,:branchID)";
                }else{
                    $sql = "INSERT INTO `ticket` (`ticketNumber`,`Pnr`, `customer_id`, `passenger_name`, 
                    `date_of_travel`,`return_date`, `from_id`, `to_id`, `sale`,`currencyID`, `staff_id`, `supp_id`, `net_price`,`net_CurrencyID`,
                    `ticketCopy`,`branchID`)
                    VALUES (:ticketNumber,:pnr, :customer_id, :passenger_name,:date_of_travel,:return_date,
                    :from_id,:to_id,:sale,:currencyID,:staff_id,:supp_id,:net_price,:net_CurrencyID,:ticketCopy,:branchID)";
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':ticketNumber', $decodeticketNumberArr[$i]);
                $stmt->bindParam(':pnr', $_POST['pnr']);
                $stmt->bindParam(':customer_id', $_POST['cust_name']);
                $stmt->bindParam(':passenger_name', $decodePassArr[$i]);
                $stmt->bindParam(':date_of_travel', $_POST['date_of_travel']);
                $stmt->bindParam(':return_date', $_POST['return_date']);
                $stmt->bindParam(':from_id', $_POST['from']);
                $stmt->bindParam(':to_id', $_POST['to']);
                $stmt->bindParam(':sale', $decodesaleAmountArr[$i]);
                $stmt->bindParam(':currencyID', $decodesalePriceCurrencyArr[$i]);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':supp_id', $_POST['supplier']);
                $stmt->bindParam(':net_price', $decodenetAmountArr[$i]);
                $stmt->bindParam(':net_CurrencyID', $decodenetPriceCurrencyArr[$i]);
                $stmt->bindParam(':branchID', $branchID);
                if($image != ''){
                    $stmt->bindParam(':ticketCopy', $image);
                }
                // execute the prepared statement
                $stmt->execute();
                }
                // Now its time to handle the customer payment
                 // create prepared statement
                 $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID)
                 VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID)";
                 $stmt = $pdo->prepare($sql);
                 // bind parameters to statement
                 $stmt->bindParam(':customer_id',$_POST['cust_name']);
                 $stmt->bindParam(':payment_amount', $_POST['cus_payment']);
                 $stmt->bindParam(':currencyID', $_POST['payment_currency_type']);
                 $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                 $stmt->bindParam(':accountID', $_POST['addaccount_id']);
                 // execute the prepared statement
                 $stmt->execute();
                 $pdo->commit(); 
                }
                
            }else{
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }else{
                    $decodePassArr = json_decode($_POST['passArr']);
                    $decodeticketNumberArr = json_decode($_POST['ticketNumberArr']);
                    $decodenetAmountArr = json_decode($_POST['netAmountArr']);
                    $decodenetPriceCurrencyArr = json_decode($_POST['netPriceCurrencyArr']);
                    $decodesaleAmountArr = json_decode($_POST['saleAmountArr']);
                    $decodesalePriceCurrencyArr = json_decode($_POST['salePriceCurrencyArr']);
                for($i=0;$i < count($decodePassArr);$i++){
                    if($image == ''){
                        $sql = "INSERT INTO `ticket` (`ticketNumber`,`Pnr`, `customer_id`, `passenger_name`, 
                        `date_of_travel`,`return_date`, `from_id`, `to_id`, `sale`,`currencyID`, `staff_id`, `supp_id`, `net_price`,`net_CurrencyID`,`branchID`)
                        VALUES (:ticketNumber,:pnr, :customer_id, :passenger_name,:date_of_travel,:return_date,
                        :from_id,:to_id,:sale,:currencyID,:staff_id,:supp_id,:net_price,:net_CurrencyID,:branchID)";
                    }else{
                        $sql = "INSERT INTO `ticket` (`ticketNumber`,`Pnr`, `customer_id`, `passenger_name`, 
                        `date_of_travel`,`return_date`, `from_id`, `to_id`, `sale`,`currencyID`, `staff_id`, `supp_id`, `net_price`,`net_CurrencyID`,
                        ticketCopy,`branchID`)
                        VALUES (:ticketNumber,:pnr, :customer_id, :passenger_name,:date_of_travel,:return_date,
                        :from_id,:to_id,:sale,:currencyID,:staff_id,:supp_id,:net_price,:net_CurrencyID,:ticketCopy,:branchID)";
                    }
                    // create prepared statement
                    
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':ticketNumber', $decodeticketNumberArr[$i]);
                    $stmt->bindParam(':pnr', $_POST['pnr']);
                    $stmt->bindParam(':customer_id', $_POST['cust_name']);
                    $stmt->bindParam(':passenger_name', $decodePassArr[$i]);
                    $stmt->bindParam(':date_of_travel', $_POST['date_of_travel']);
                    $stmt->bindParam(':return_date', $_POST['return_date']);
                    $stmt->bindParam(':from_id', $_POST['from']);
                    $stmt->bindParam(':to_id', $_POST['to']);
                    $stmt->bindParam(':sale', $decodesaleAmountArr[$i]);
                    $stmt->bindParam(':currencyID', $decodesalePriceCurrencyArr[$i]);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->bindParam(':supp_id', $_POST['supplier']);
                    $stmt->bindParam(':net_price', $decodenetAmountArr[$i]);
                    $stmt->bindParam(':net_CurrencyID', $decodenetPriceCurrencyArr[$i]);
                    $stmt->bindParam(':branchID', $branchID);
                    if($image != ''){
                        $stmt->bindParam(':ticketCopy', $image);
                    }
                    // execute the prepared statement
                    $stmt->execute();
                }
                $pdo->commit(); 
                }
            }
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    function upload_Image($ticket){
        $new_image_name = '';
        if($_FILES['uploadFile']['size']<=2097152){
            $extension = explode(".", $_FILES['uploadFile']['name']);
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($extension[1]), $ext))
            {
                $new_image_name = $extension[0]. '_'. $ticket. "." . date("Y/m/d h:i:s") . $extension[1];
                $new_image_name = md5($new_image_name);
                $new_image_name = 'tickets/'. $new_image_name. '.' .$extension[1];
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploadFile']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
            
        }
        
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>