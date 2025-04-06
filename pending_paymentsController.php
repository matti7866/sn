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
    if(isset($_POST['SELECT_CUSTOMER'])){
        $selectQuery = $pdo->prepare("SELECT main_customer AS customer_id, customer_name FROM (SELECT customer_id as 
        main_customer,concat(customer_name,'--',customer_phone) AS customer_name, (SELECT IFNULL(SUM(ticket.sale),0)
        FROM ticket WHERE ticket.customer_id = main_customer) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id 
        = main_customer) + (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges INNER JOIN visa ON visa.visa_id
        = visaextracharges.visa_id WHERE visa.customer_id = main_customer) + (SELECT IFNULL(SUM(residence.sale_price),0) FROM 
        residence WHERE residence.customer_id = main_customer)+ (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine
        INNER JOIN residence ON residence.residenceID = residencefine.residenceID  WHERE residence.customer_id = main_customer) + 
        (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE servicedetails.customer_id = main_customer) +
        (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id
        WHERE ticket.customer_id = main_customer AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.sale_price),0) FROM
        hotel WHERE hotel.customer_id = main_customer) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE 
        car_rental.customer_id = main_customer) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id =
        main_customer) - (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket =
        datechange.ticket_id WHERE ticket.customer_id = main_customer AND datechange.ticketStatus = 2) - (SELECT 
        IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = main_customer) 
        AS total FROM customer) AS baseTable WHERE total !=0 ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['SELECT_PENDINGCUSTOMERS'])){
        $date="";

        if($_POST['Customer_ID']== ""){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT customer_id as main_customer,customer_name,
            IFNULL(customer_email,'') AS customer_email,customer_whatsapp,customer_phone, (SELECT IFNULL(SUM(ticket.sale),0)FROM 
            ticket WHERE ticket.customer_id = main_customer  AND ticket.currencyID = :currencyID) + (SELECT 
            IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = main_customer AND visa.saleCurrencyID = :currencyID) + 
            (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges INNER JOIN visa ON visa.visa_id= 
            visaextracharges.visa_id WHERE visa.customer_id = main_customer AND visaextracharges.saleCurrencyID = :currencyID) + 
            (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE residence.customer_id = main_customer AND 
            residence.saleCurID = :currencyID)+ (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID WHERE residence.customer_id = main_customer AND 
            residencefine.fineCurrencyID = :currencyID) +(SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE
            servicedetails.customer_id = main_customer AND servicedetails.saleCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE
            ticket.customer_id = main_customer AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID = :currencyID) + 
            (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = main_customer AND hotel.saleCurrencyID = 
            :currencyID) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = 
            main_customer AND car_rental.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE 
            loan.customer_id = main_customer AND loan.currencyID = :currencyID) - (SELECT IFNULL(SUM(datechange.sale_amount),0) 
            FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer 
            AND datechange.ticketStatus = 2 AND datechange.saleCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = 
            main_customer AND customer_payments.currencyID = :currencyID) AS total from customer) as baseTable WHERE total !=0 
            ORDER By customer_name ASC;");
            $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
            $selectQuery->execute();
            $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($customers);
        }else{
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT customer_id as main_customer,customer_name,
            IFNULL(customer_email,'') AS customer_email,customer_whatsapp,customer_phone, 
            (SELECT IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = main_customer  AND ticket.currencyID = 
            :currencyID) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = main_customer AND 
            visa.saleCurrencyID = :currencyID)+(SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges 
            INNER JOIN visa ON visa.visa_id= visaextracharges.visa_id WHERE visa.customer_id = main_customer AND 
            visaextracharges.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE 
            residence.customer_id = main_customer AND residence.saleCurID =:currencyID) + (SELECT 
            IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN residence ON residence.residenceID = 
            residencefine.residenceID WHERE residence.customer_id = main_customer AND residencefine.fineCurrencyID = :currencyID) 
            + (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE servicedetails.customer_id = main_customer
            AND servicedetails.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange 
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND 
            datechange.ticketStatus = 1 AND datechange.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(hotel.sale_price),0) 
            FROM hotel WHERE hotel.customer_id = main_customer AND hotel.saleCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = main_customer AND 
            car_rental.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id =
            main_customer AND loan.currencyID = :currencyID) -(SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange 
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND 
            datechange.ticketStatus = 2 AND datechange.saleCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = 
            main_customer AND customer_payments.currencyID = :currencyID) AS total from customer WHERE customer_id = :customer_id)
            AS baseTable WHERE total !=0 ORDER BY customer_name ASC ;");
            $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->execute();
            $customers = $selectQuery->fetch(\PDO::FETCH_ASSOC);
            echo json_encode($customers);
        }
        
        
    }else if(isset($_POST['CurrencyTypes'])){
        if($_POST['Type'] == 'all'){
            $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        }else{
            $selectQuery = $pdo->prepare("SELECT curID AS currencyID, curName AS currencyName FROM (SELECT curID, (SELECT 
            currencyName FROM currency WHERE currency.currencyID = curID) AS curName, (SELECT IFNULL(SUM(ticket.sale),0) FROM 
            ticket WHERE ticket.customer_id = :customer_id AND ticket.currencyID = curID) + (SELECT IFNULL(SUM(visa.sale),0) 
            FROM visa WHERE visa.customer_id = :customer_id AND visa.saleCurrencyID = curID) + (SELECT 
            IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges INNER JOIN visa ON visa.visa_id= 
            visaextracharges.visa_id WHERE visa.customer_id = :customer_id AND visaextracharges.saleCurrencyID = curID) +
            (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE residence.customer_id = :customer_id AND 
            residence.saleCurID =curID)+ (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID WHERE residence.customer_id = :customer_id AND 
            residencefine.fineCurrencyID = curID) + (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE 
            servicedetails.customer_id = :customer_id AND servicedetails.saleCurrencyID = curID) + (SELECT 
            IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id 
            WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID = curID) + 
            (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = :customer_id AND hotel.saleCurrencyID
            = curID) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = :customer_id
            AND car_rental.saleCurrencyID = curID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = 
            :customer_id AND loan.currencyID = curID) - (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN
            ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 2
            AND datechange.saleCurrencyID = curID) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments
            WHERE customer_payments.customer_id = :customer_id AND customer_payments.currencyID = curID)   AS total FROM (SELECT 
            ticket.currencyID AS curID FROM ticket  WHERE ticket.customer_id = :customer_id UNION SELECT visa.saleCurrencyID AS 
            curID FROM visa WHERE visa.customer_id = :customer_id UNION SELECT visaextracharges.saleCurrencyID AS curID FROM 
            visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id WHERE visa.customer_id = :customer_id 
            UNION SELECT residence.saleCurID AS curID FROM residence WHERE residence.customer_id = :customer_id UNION SELECT 
            residencefine.fineCurrencyID AS curID FROM residencefine INNER JOIN residence ON residence.residenceID = 
            residencefine.residenceID WHERE residence.customer_id = :customer_id  UNION SELECT servicedetails.saleCurrencyID AS 
            curID FROM servicedetails WHERE servicedetails.customer_id = :customer_id UNION SELECT datechange.saleCurrencyID AS
            curID FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = 
            :customer_id UNION SELECT loan.currencyID AS curID FROM loan WHERE loan.customer_id = :customer_id UNION SELECT 
            hotel.saleCurrencyID AS curID FROM hotel WHERE hotel.customer_id = :customer_id UNION SELECT car_rental.saleCurrencyID
            AS curID FROM car_rental WHERE car_rental.customer_id = :customer_id UNION SELECT customer_payments.currencyID AS 
            curID FROM customer_payments WHERE customer_payments.customer_id = :customer_id) AS baseTable  ) AS finalTable WHERE
            total !=0 ORDER BY curName ASC");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['Payments'])){
        $selectQuery = $pdo->prepare("SELECT 
        (SELECT IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = :customer_id  AND ticket.currencyID = 
        :currencyID) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = :customer_id AND visa.saleCurrencyID =
        :currencyID) + (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges INNER JOIN visa ON visa.visa_id
        = visaextracharges.visa_id WHERE visa.customer_id = :customer_id AND visaextracharges.saleCurrencyID=:currencyID) + 
        (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE residence.customer_id = :customer_id AND 
        residence.saleCurID =:currencyID)+ (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN 
        residence ON residence.residenceID = residencefine.residenceID WHERE residence.customer_id = :customer_id AND 
        residencefine.fineCurrencyID = :currencyID)+ (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE 
        servicedetails.customer_id = :customer_id AND servicedetails.saleCurrencyID = :currencyID)+(SELECT 
        IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE
        ticket.customer_id = :customer_id AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID = :currencyID) + (SELECT
        IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = :customer_id AND hotel.saleCurrencyID = :currencyID)
        + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = :customer_id AND 
        car_rental.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = 
        :customer_id AND loan.currencyID = :currencyID) -(SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN 
        ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 2 
        AND datechange.saleCurrencyID = :currencyID) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
        customer_payments WHERE customer_payments.customer_id = :customer_id AND customer_payments.currencyID = :currencyID)  AS 
        total");
        $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $paymetns = $selectQuery->fetch(PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($paymetns);
    }else if(isset($_POST['Insert_Payment'])){
        try{
                if($_POST['Payment'] > 0){
                    // create prepared statement
                    $sql = "INSERT INTO `customer_payments`(`customer_id`, `payment_amount`,`currencyID`, 
                    `staff_id`,`remarks`,accountID) VALUES (:customer_id, :payment_amount,:currencyID, :staff_id, :remarks,:accountID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':customer_id', $_POST['Customer_ID']);
                    $stmt->bindParam(':payment_amount', $_POST['Payment']);
                    $stmt->bindParam(':currencyID', $_POST['Currency_Type']);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->bindParam(':remarks', $_POST['Remarks']);
                    $stmt->bindParam(':accountID', $_POST['Addaccount_ID']);
                    // execute the prepared statement
                    $stmt->execute(); 
                    echo "Success";
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