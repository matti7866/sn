<?php
session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    try{
        include("connection.php");
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        echo "ERROR: Could not connect. " . $e->getMessage();
    }
    if(isset($_POST['Select_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT main_supp AS supp_id, supp_name FROM (SELECT supp_id AS main_supp,supp_name,(SELECT
        IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = main_supp) + (SELECT IFNULL(SUM(visa.net_price),0) FROM
        visa WHERE visa.supp_id = main_supp) + (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE 
        residence.offerLetterSupplier = main_supp) + (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
        residence.insuranceSupplier = main_supp) + (SELECT IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE 
        residence.laborCardSupplier = main_supp) + (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
        residence.eVisaSupplier = main_supp) + (SELECT IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE 
        residence.changeStatusSupplier = main_supp) + (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
        residence.medicalSupplier = main_supp) + (SELECT IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE
        residence.emiratesIDSupplier = main_supp) + (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
        residence.visaStampingSupplier = main_supp) + (SELECT IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE 
        servicedetails.Supplier_id = main_supp ) + (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges WHERE 
        visaextracharges.supplierID = main_supp) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
        datechange.supplier = main_supp AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM hotel WHERE
        hotel.supplier_id = main_supp ) + (SELECT IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id
        = main_supp) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = main_supp AND 
        datechange.ticketStatus = 2) - (SELECT IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id =
        main_supp) AS total FROM   supplier) AS baseTable WHERE total !=0 ORDER By supp_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $suppliers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($suppliers);
    }else if(isset($_POST['CurrencyTypes'])){
        if($_POST['Type'] == 'all'){
            $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        }else{
            $selectQuery = $pdo->prepare("SELECT curID AS currencyID, curName AS currencyName FROM (SELECT curID, (SELECT 
            currencyName FROM currency WHERE currency.currencyID = curID) AS curName, (SELECT IFNULL(SUM(ticket.net_price),0) FROM 
            ticket WHERE ticket.supp_id = :supp_id AND ticket.net_CurrencyID = curID) + (SELECT IFNULL(SUM(visa.net_price),0) 
            FROM visa WHERE visa.supp_id = :supp_id AND visa.netCurrencyID = curID) + (SELECT 
            IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = :supp_id AND 
            residence.offerLetterCostCur = curID) + (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
            residence.insuranceSupplier = :supp_id AND residence.insuranceCur = curID) + (SELECT 
            IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = :supp_id AND 
            residence.laborCardCur = curID) + (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = :supp_id AND residence.eVisaCur = curID) + (SELECT 
            IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = :supp_id AND 
            residence.changeStatusCur = curID) + (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = :supp_id AND residence.medicalTCur = curID) + (SELECT 
            IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = :supp_id AND 
            residence.emiratesIDCur = curID) + (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = :supp_id AND residence.visaStampingCur = curID)+ (SELECT 
             IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = :supp_id AND 
             servicedetails.netCurrencyID = curID ) +(SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges WHERE
            visaextracharges.supplierID = :supp_id AND visaextracharges.netCurrencyID = curID) + (SELECT 
            IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = :supp_id AND datechange.ticketStatus
            = 1 AND datechange.netCurrencyID = curID) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM hotel WHERE hotel.supplier_id
            = :supp_id AND hotel.netCurrencyID = curID) + (SELECT IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE 
            car_rental.supplier_id = :supp_id AND car_rental.netCurrencyID = curID) - (SELECT IFNULL(SUM(datechange.net_amount),0)
            FROM datechange WHERE datechange.supplier = :supp_id AND datechange.ticketStatus = 2 AND datechange.netCurrencyID =
            curID) - (SELECT IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = :supp_id AND 
            payment.currencyID = curID)   AS total FROM (SELECT ticket.net_CurrencyID AS curID FROM ticket  WHERE ticket.supp_id
            = :supp_id UNION SELECT visa.netCurrencyID AS curID FROM visa WHERE visa.supp_id = :supp_id UNION SELECT 
            residence.offerLetterCostCur AS curID FROM residence WHERE residence.offerLetterSupplier = :supp_id UNION SELECT 
            insuranceCur AS curID FROM residence WHERE residence.insuranceSupplier =:supp_id UNION SELECT residence.laborCardCur
            AS curID FROM residence WHERE residence.laborCardSupplier = :supp_id UNION SELECT residence.eVisaCur AS curID FROM 
            residence WHERE residence.eVisaSupplier = :supp_id UNION SELECT residence.changeStatusCur AS curID FROM residence
            WHERE residence.changeStatusSupplier = :supp_id UNION SELECT medicalTCur AS curID FROM residence WHERE 
            residence.medicalSupplier = :supp_id UNION SELECT residence.emiratesIDCur AS curID FROM residence WHERE 
            residence.emiratesIDSupplier = :supp_id UNION SELECT residence.visaStampingCur AS curID FROM residence WHERE 
            residence.visaStampingSupplier = :supp_id   UNION SELECT servicedetails.netCurrencyID  AS curID FROM servicedetails
            WHERE servicedetails.Supplier_id = :supp_id UNION SELECT visaextracharges.netCurrencyID AS curID FROM visaextracharges
            WHERE visaextracharges.supplierID = :supp_id UNION SELECT datechange.netCurrencyID AS curID FROM datechange WHERE 
            datechange.supplier = :supp_id UNION SELECT hotel.netCurrencyID AS curID FROM hotel WHERE hotel.supplier_id = :supp_id
            UNION SELECT car_rental.netCurrencyID AS curID FROM car_rental WHERE car_rental.supplier_id = :supp_id UNION SELECT 
            payment.currencyID AS curID FROM payment WHERE payment.supp_id = :supp_id) AS baseTable) AS finalTable WHERE total !=0
            ORDER BY curName ASC");
            $selectQuery->bindParam(':supp_id', $_POST['Supplier_ID_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['Select_PendingSuppliers'])){
        $date="";

        if($_POST['Supplier_ID']== ""){
            $selectQuery = $pdo->prepare("SELECT * FROM(SELECT supp_id as main_supp,supp_name,supp_email,supp_phone,(SELECT
            IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = main_supp AND ticket.net_CurrencyID = :currencyID)
            + (SELECT IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = main_supp AND visa.netCurrencyID = :currencyID)
            + (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = main_supp AND 
            residence.offerLetterCostCur = :currencyID) + (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
            residence.insuranceSupplier = main_supp AND residence.insuranceCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = main_supp AND 
            residence.laborCardCur = :currencyID) + (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = main_supp AND residence.eVisaCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = main_supp AND 
            residence.changeStatusCur =:currencyID) + (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = main_supp AND residence.medicalTCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = main_supp AND 
            residence.emiratesIDCur = :currencyID) + (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = main_supp AND residence.visaStampingCur = :currencyID) + (SELECT 
            IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = main_supp AND 
            servicedetails.netCurrencyID = :currencyID ) + (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges
            WHERE visaextracharges.supplierID = main_supp AND visaextracharges.netCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = main_supp AND 
            datechange.netCurrencyID =:currencyID  AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM
            hotel WHERE hotel.supplier_id = main_supp AND hotel.netCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = main_supp AND 
            car_rental.netCurrencyID = :currencyID) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = main_supp AND datechange.netCurrencyID = :currencyID AND datechange.ticketStatus = 2) - (SELECT
            IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = main_supp AND payment.currencyID = 
            :currencyID) AS Pending FROM supplier) AS Total WHERE Pending !=0 ORDER BY supp_name ASC ");
            $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
            $selectQuery->execute();
            $suppliers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($suppliers);
        }else{
            $selectQuery = $pdo->prepare("SELECT * FROM(SELECT supp_id as main_supp,supp_name,supp_email,supp_phone, (SELECT
            IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = main_supp AND ticket.net_CurrencyID = :currencyID)
            + (SELECT IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = main_supp AND visa.netCurrencyID = :currencyID)
            + (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = main_supp AND
            residence.offerLetterCostCur = :currencyID) + (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
            residence.insuranceSupplier = main_supp AND residence.insuranceCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = main_supp AND 
            residence.laborCardCur = :currencyID) + (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = main_supp AND residence.eVisaCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = main_supp AND 
            residence.changeStatusCur =:currencyID) + (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = main_supp AND residence.medicalTCur = :currencyID) + (SELECT 
            IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = main_supp AND 
            residence.emiratesIDCur = :currencyID) + (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = main_supp AND residence.visaStampingCur = :currencyID) + (SELECT 
            IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = main_supp AND 
            servicedetails.netCurrencyID = :currencyID ) + (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges
            WHERE visaextracharges.supplierID = main_supp AND visaextracharges.netCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = main_supp AND 
            datechange.netCurrencyID =:currencyID  AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM
            hotel WHERE hotel.supplier_id = main_supp AND hotel.netCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = main_supp AND 
            car_rental.netCurrencyID = :currencyID) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = main_supp AND datechange.netCurrencyID = :currencyID AND datechange.ticketStatus = 2) - (SELECT
            IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = main_supp AND payment.currencyID = 
            :currencyID) AS Pending FROM supplier WHERE supp_id = :supp_id) as baseTable WHERE Pending !=0 ORDER by supp_name");
            $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
            $selectQuery->bindParam(':supp_id', $_POST['Supplier_ID']);
            $selectQuery->execute();
            $customers = $selectQuery->fetch(\PDO::FETCH_ASSOC);
            echo json_encode($customers);
        }
        
        
    }else if(isset($_POST['Payments'])){
        $selectQuery = $pdo->prepare("SELECT ((SELECT
        IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = :supp_id AND ticket.net_CurrencyID = :currencyID)
        + (SELECT IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = :supp_id AND visa.netCurrencyID = :currencyID)
        + (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = :supp_id AND
        residence.offerLetterCostCur = :currencyID) + (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
        residence.insuranceSupplier = :supp_id AND residence.insuranceCur = :currencyID) + (SELECT 
        IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = :supp_id AND 
        residence.laborCardCur = :currencyID) + (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
        residence.eVisaSupplier = :supp_id AND residence.eVisaCur = :currencyID) + (SELECT 
        IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = :supp_id AND 
        residence.changeStatusCur =:currencyID) + (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
        residence.medicalSupplier = :supp_id AND residence.medicalTCur = :currencyID) + (SELECT 
        IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = :supp_id AND 
        residence.emiratesIDCur = :currencyID) + (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
        residence.visaStampingSupplier = :supp_id AND residence.visaStampingCur = :currencyID) + (SELECT 
        IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = :supp_id AND 
        servicedetails.netCurrencyID = :currencyID ) + (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges
        WHERE visaextracharges.supplierID = :supp_id AND visaextracharges.netCurrencyID = :currencyID) + (SELECT 
        IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = :supp_id AND 
        datechange.netCurrencyID =:currencyID  AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.net_price),0) FROM
        hotel WHERE hotel.supplier_id = :supp_id AND hotel.netCurrencyID = :currencyID) + (SELECT 
        IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = :supp_id AND 
        car_rental.netCurrencyID = :currencyID) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
        datechange.supplier = :supp_id AND datechange.netCurrencyID = :currencyID AND datechange.ticketStatus = 2) - (SELECT
        IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = :supp_id AND payment.currencyID = 
        :currencyID))  AS total");
        $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
        $selectQuery->bindParam(':supp_id', $_POST['Supp_ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetch(PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['Insert_Payment'])){
        try{
            if($_POST['Payment'] > 0){
                // create prepared statement
                $sql = "INSERT INTO `payment`(`supp_id`, `payment_amount`,`currencyID`, `payment_detail`, `staff_id`,accountID) 
                VALUES (:supp_id, :payment_amount,:currencyID, :payment_detail,:staff_id,:accountID)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':supp_id', $_POST['Supp_ID']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $_POST['Currency_Type']);
                $stmt->bindParam(':payment_detail', $_POST['Remarks']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Addaccount_ID']);
                 // execute the prepared statement
                 $stmt->execute(); 
                echo "Records inserted successfully.";
            }else{
                echo "Unsuccesful payment";
            }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>