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
        main_customer,concat(customer_name,'--',customer_phone) AS customer_name,affliate_supp_id,(SELECT 
        IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = main_customer) + (SELECT IFNULL(SUM(visa.sale),0) FROM 
        visa WHERE visa.customer_id = main_customer) + (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges 
        INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id WHERE visa.customer_id = main_customer) + (SELECT 
        IFNULL(SUM(residence.sale_price),0) FROM residence WHERE residence.customer_id = main_customer)+ (SELECT 
        IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE servicedetails.customer_id = main_customer) +(SELECT
        IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE 
        ticket.customer_id = main_customer AND datechange.ticketStatus = 1) + (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel 
        WHERE hotel.customer_id = main_customer) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE 
        car_rental.customer_id = main_customer) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = 
        main_customer) - (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = 
        datechange.ticket_id WHERE ticket.customer_id = main_customer AND datechange.ticketStatus = 2) - (SELECT 
        IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = main_customer) 
        -(SELECT IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = affliate_supp_id) - (SELECT 
        IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = affliate_supp_id) - (SELECT 
        IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = affliate_supp_id) - 
        (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE residence.insuranceSupplier = affliate_supp_id) -
        (SELECT IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = affliate_supp_id) -
        (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE residence.eVisaSupplier = affliate_supp_id) - (SELECT
        IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = affliate_supp_id) -
        (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE residence.medicalSupplier = affliate_supp_id) - (SELECT
        IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = affliate_supp_id) - (SELECT
        IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE residence.visaStampingSupplier = affliate_supp_id) -
        (SELECT IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = affliate_supp_id ) -
        (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges WHERE visaextracharges.supplierID = 
        affliate_supp_id) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = 
        affliate_supp_id AND datechange.ticketStatus = 1) - (SELECT IFNULL(SUM(hotel.net_price),0) FROM hotel WHERE
        hotel.supplier_id = affliate_supp_id ) - (SELECT IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE 
        car_rental.supplier_id = affliate_supp_id) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
        datechange.supplier = affliate_supp_id  AND datechange.ticketStatus = 2) + (SELECT IFNULL(SUM(payment.payment_amount),0) 
        FROM payment WHERE payment.supp_id = affliate_supp_id) AS total FROM customer WHERE affliate_supp_id IS NOT NULL) AS 
        baseTable WHERE total !=0 ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['SELECT_PENDINGCUSTOMERS'])){
        $date="";

        if($_POST['Customer_ID']== ""){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT customer_id as main_customer,customer_name,
            IFNULL(customer_email,'') AS customer_email,customer_whatsapp,customer_phone, affliate_supp_id,
            (SELECT 
            IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = main_customer  AND ticket.currencyID = 
            :currencyID) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = main_customer AND 
            visa.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges 
            INNER JOIN visa ON visa.visa_id= visaextracharges.visa_id WHERE visa.customer_id = main_customer AND 
            visaextracharges.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE 
            residence.customer_id = main_customer AND residence.saleCurID =:currencyID)+ (SELECT 
            IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE servicedetails.customer_id = main_customer AND 
            servicedetails.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange 
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND 
            datechange.ticketStatus = 1 AND datechange.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(hotel.sale_price),0) 
            FROM hotel WHERE hotel.customer_id = main_customer AND hotel.saleCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = main_customer AND 
            car_rental.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id =
            main_customer AND loan.currencyID = :currencyID) - (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND 
            datechange.ticketStatus = 2 AND datechange.saleCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = 
            main_customer AND customer_payments.currencyID = :currencyID) - 
            (SELECT IFNULL(SUM(ticket.net_price),0) FROM ticket 
            WHERE ticket.supp_id = affliate_supp_id AND ticket.net_CurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = affliate_supp_id AND visa.netCurrencyID = :currencyID)
            -(SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = 
            affliate_supp_id AND residence.offerLetterCostCur = :currencyID) - (SELECT IFNULL(SUM(residence.insuranceCost),0) 
            FROM residence WHERE residence.insuranceSupplier = affliate_supp_id AND residence.insuranceCur = :currencyID) - 
            (SELECT IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = affliate_supp_id AND 
            residence.laborCardCur = :currencyID) -(SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = affliate_supp_id AND residence.eVisaCur = :currencyID) - (SELECT 
            IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = affliate_supp_id AND 
            residence.changeStatusCur =:currencyID) - (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = affliate_supp_id AND residence.medicalTCur = :currencyID) - (SELECT 
            IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = affliate_supp_id AND 
            residence.emiratesIDCur = :currencyID) - (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = affliate_supp_id AND residence.visaStampingCur = :currencyID) - (SELECT 
            IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = affliate_supp_id AND 
            servicedetails.netCurrencyID = :currencyID ) - (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges
            WHERE visaextracharges.supplierID = affliate_supp_id AND visaextracharges.netCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = affliate_supp_id AND 
            datechange.netCurrencyID =:currencyID  AND datechange.ticketStatus = 1) - (SELECT IFNULL(SUM(hotel.net_price),0) FROM
            hotel WHERE hotel.supplier_id = affliate_supp_id AND hotel.netCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = affliate_supp_id AND 
            car_rental.netCurrencyID = :currencyID) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = affliate_supp_id AND datechange.netCurrencyID = :currencyID AND datechange.ticketStatus = 2) +
            (SELECT IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = affliate_supp_id AND 
            payment.currencyID = :currencyID)  AS total from customer WHERE affliate_supp_id IS NOT NULL ) as baseTable WHERE total
            !=0 ORDER By customer_name ASC;");
            $selectQuery->bindParam(':currencyID', $_POST['Currency_Type']);
            $selectQuery->execute();
            $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($customers);
        }else{
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT customer_id as main_customer,customer_name,
            IFNULL(customer_email,'') AS customer_email,customer_whatsapp,customer_phone, affliate_supp_id,
            (SELECT IFNULL(SUM(ticket.sale),0) FROM ticket WHERE ticket.customer_id = main_customer  AND ticket.currencyID = 
            :currencyID) + (SELECT IFNULL(SUM(visa.sale),0) FROM visa WHERE visa.customer_id = main_customer AND 
            visa.saleCurrencyID = :currencyID)+(SELECT IFNULL(SUM(visaextracharges.salePrice),0) FROM visaextracharges 
            INNER JOIN visa ON visa.visa_id= visaextracharges.visa_id WHERE visa.customer_id = main_customer AND 
            visaextracharges.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(residence.sale_price),0) FROM residence WHERE 
            residence.customer_id = main_customer AND residence.saleCurID =:currencyID)+ (SELECT 
            IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE servicedetails.customer_id = main_customer AND 
            servicedetails.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN
            ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND datechange.ticketStatus = 
            1 AND datechange.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE 
            hotel.customer_id = main_customer AND hotel.saleCurrencyID = :currencyID) + (SELECT 
            IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = main_customer AND 
            car_rental.saleCurrencyID = :currencyID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id =
            main_customer AND loan.currencyID = :currencyID) -(SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange 
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = main_customer AND 
            datechange.ticketStatus = 2 AND datechange.saleCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.customer_id = 
            main_customer AND customer_payments.currencyID = :currencyID) -(SELECT IFNULL(SUM(ticket.net_price),0) FROM ticket
            WHERE ticket.supp_id = affliate_supp_id AND ticket.net_CurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(visa.net_price),0) FROM visa WHERE visa.supp_id = affliate_supp_id AND visa.netCurrencyID = :currencyID)
            - (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE residence.offerLetterSupplier = 
            affliate_supp_id AND residence.offerLetterCostCur = :currencyID) - (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM
            residence WHERE residence.insuranceSupplier = affliate_supp_id AND residence.insuranceCur = :currencyID) - (SELECT 
            IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE residence.laborCardSupplier = affliate_supp_id AND 
            residence.laborCardCur = :currencyID) - (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = affliate_supp_id AND residence.eVisaCur = :currencyID) - (SELECT 
            IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE residence.changeStatusSupplier = affliate_supp_id AND 
            residence.changeStatusCur =:currencyID) - (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = affliate_supp_id AND residence.medicalTCur = :currencyID) - (SELECT 
            IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE residence.emiratesIDSupplier = affliate_supp_id AND 
            residence.emiratesIDCur = :currencyID) - (SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = affliate_supp_id AND residence.visaStampingCur = :currencyID) - (SELECT 
            IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE servicedetails.Supplier_id = affliate_supp_id AND 
            servicedetails.netCurrencyID = :currencyID ) - (SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges
            WHERE visaextracharges.supplierID = affliate_supp_id AND visaextracharges.netCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE datechange.supplier = affliate_supp_id AND 
            datechange.netCurrencyID =:currencyID  AND datechange.ticketStatus = 1) - (SELECT IFNULL(SUM(hotel.net_price),0) FROM
            hotel WHERE hotel.supplier_id = affliate_supp_id AND hotel.netCurrencyID = :currencyID) - (SELECT 
            IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE car_rental.supplier_id = affliate_supp_id AND 
            car_rental.netCurrencyID = :currencyID) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = affliate_supp_id AND datechange.netCurrencyID = :currencyID AND datechange.ticketStatus = 2) +
            (SELECT IFNULL(SUM(payment.payment_amount),0) FROM payment WHERE payment.supp_id = affliate_supp_id AND 
            payment.currencyID = :currencyID)AS total from customer WHERE customer_id = :customer_id) AS baseTable WHERE total 
            !=0 ORDER BY customer_name ASC ;");
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
            residence.saleCurID =curID)+ (SELECT IFNULL(SUM(servicedetails.salePrice),0) FROM servicedetails WHERE 
            servicedetails.customer_id = :customer_id AND servicedetails.saleCurrencyID = curID) + (SELECT 
            IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id 
            WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 1 AND datechange.saleCurrencyID = curID) + 
            (SELECT IFNULL(SUM(hotel.sale_price),0) FROM hotel WHERE hotel.customer_id = :customer_id AND hotel.saleCurrencyID
            = curID) + (SELECT IFNULL(SUM(car_rental.sale_price),0) FROM car_rental WHERE car_rental.customer_id = :customer_id
            AND car_rental.saleCurrencyID = curID) + (SELECT IFNULL(SUM(loan.amount),0) FROM loan WHERE loan.customer_id = 
            :customer_id AND loan.currencyID = curID) - (SELECT IFNULL(SUM(datechange.sale_amount),0) FROM datechange INNER JOIN
            ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id AND datechange.ticketStatus = 2
            AND datechange.saleCurrencyID = curID) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments
            WHERE customer_payments.customer_id = :customer_id AND customer_payments.currencyID = curID) -(SELECT 
            IFNULL(SUM(ticket.net_price),0) FROM ticket WHERE ticket.supp_id = (SELECT affliate_supp_id FROM customer WHERE 
            customer.customer_id = :customer_id) AND ticket.net_CurrencyID = curID) - (SELECT IFNULL(SUM(visa.net_price),0) 
            FROM visa WHERE visa.supp_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND visa.netCurrencyID = curID) - (SELECT IFNULL(SUM(residence.offerLetterCost),0) FROM residence WHERE 
            residence.offerLetterSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND residence.offerLetterCostCur = curID) - (SELECT IFNULL(SUM(residence.insuranceCost),0) FROM residence WHERE 
            residence.insuranceSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND residence.insuranceCur = curID) - (SELECT IFNULL(SUM(residence.laborCardFee),0) FROM residence WHERE 
            residence.laborCardSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) AND 
            residence.laborCardCur = curID) - (SELECT IFNULL(SUM(residence.eVisaCost),0) FROM residence WHERE 
            residence.eVisaSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) AND 
            residence.eVisaCur = curID) -(SELECT IFNULL(SUM(residence.changeStatusCost),0) FROM residence WHERE
            residence.changeStatusSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) 
            AND residence.changeStatusCur = curID) - (SELECT IFNULL(SUM(residence.medicalTCost),0) FROM residence WHERE 
            residence.medicalSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND residence.medicalTCur = curID) - (SELECT IFNULL(SUM(residence.emiratesIDCost),0) FROM residence WHERE 
            residence.emiratesIDSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND residence.emiratesIDCur = curID) -(SELECT IFNULL(SUM(residence.visaStampingCost),0) FROM residence WHERE 
            residence.visaStampingSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND residence.visaStampingCur = curID)- (SELECT IFNULL(SUM(servicedetails.netPrice),0) FROM servicedetails WHERE 
            servicedetails.Supplier_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) AND 
            servicedetails.netCurrencyID = curID ) -(SELECT IFNULL(SUM(visaextracharges.net_price),0) FROM visaextracharges WHERE
            visaextracharges.supplierID = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND visaextracharges.netCurrencyID = curID) - (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) AND 
            datechange.ticketStatus = 1 AND datechange.netCurrencyID = curID) - (SELECT IFNULL(SUM(hotel.net_price),0) FROM hotel
            WHERE hotel.supplier_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND hotel.netCurrencyID = curID)- (SELECT IFNULL(SUM(car_rental.net_price),0) FROM car_rental WHERE 
            car_rental.supplier_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND car_rental.netCurrencyID = curID) + (SELECT IFNULL(SUM(datechange.net_amount),0) FROM datechange WHERE 
            datechange.supplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) AND 
            datechange.ticketStatus = 2 AND datechange.netCurrencyID = curID) + (SELECT IFNULL(SUM(payment.payment_amount),0) 
            FROM payment WHERE payment.supp_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            AND payment.currencyID = curID) AS total FROM (SELECT ticket.currencyID AS curID FROM ticket  WHERE ticket.customer_id
            = :customer_id UNION SELECT visa.saleCurrencyID AS curID FROM visa WHERE visa.customer_id = :customer_id UNION SELECT
            visaextracharges.saleCurrencyID AS curID FROM visaextracharges INNER JOIN visa ON visa.visa_id = 
            visaextracharges.visa_id WHERE visa.customer_id = :customer_id UNION SELECT residence.saleCurID AS curID FROM 
            residence WHERE residence.customer_id = :customer_id UNION SELECT servicedetails.saleCurrencyID AS curID FROM 
            servicedetails WHERE servicedetails.customer_id = :customer_id UNION SELECT datechange.saleCurrencyID AS curID FROM 
            datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE ticket.customer_id = :customer_id UNION
            SELECT loan.currencyID AS curID FROM loan WHERE loan.customer_id = :customer_id UNION SELECT hotel.saleCurrencyID AS
            curID FROM hotel WHERE hotel.customer_id = :customer_id UNION SELECT car_rental.saleCurrencyID AS curID FROM 
            car_rental WHERE car_rental.customer_id = :customer_id UNION SELECT customer_payments.currencyID AS curID FROM 
            customer_payments WHERE customer_payments.customer_id = :customer_id UNION SELECT ticket.net_CurrencyID AS curID FROM
            ticket  WHERE ticket.supp_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) UNION
            SELECT visa.netCurrencyID AS curID FROM visa WHERE visa.supp_id = (SELECT affliate_supp_id FROM customer WHERE 
            customer.customer_id = :customer_id) UNION SELECT residence.offerLetterCostCur AS curID FROM residence WHERE 
            residence.offerLetterSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) 
            UNION SELECT insuranceCur AS curID FROM residence WHERE residence.insuranceSupplier =(SELECT affliate_supp_id FROM 
            customer WHERE customer.customer_id = :customer_id) UNION SELECT residence.laborCardCur AS curID FROM residence WHERE
            residence.laborCardSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            UNION SELECT residence.eVisaCur AS curID FROM residence WHERE residence.eVisaSupplier = (SELECT affliate_supp_id FROM
            customer WHERE customer.customer_id = :customer_id) UNION SELECT residence.changeStatusCur AS curID FROM residence
            WHERE residence.changeStatusSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = 
            :customer_id) UNION SELECT medicalTCur AS curID FROM residence WHERE residence.medicalSupplier = (SELECT 
            affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) UNION SELECT residence.emiratesIDCur AS 
            curID FROM residence WHERE residence.emiratesIDSupplier = (SELECT affliate_supp_id FROM customer WHERE 
            customer.customer_id = :customer_id) UNION SELECT residence.visaStampingCur AS curID FROM residence WHERE 
            residence.visaStampingSupplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id)
            UNION SELECT servicedetails.netCurrencyID  AS curID FROM servicedetails WHERE servicedetails.Supplier_id = (SELECT 
            affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) UNION SELECT visaextracharges.netCurrencyID
            AS curID FROM visaextracharges WHERE visaextracharges.supplierID = (SELECT affliate_supp_id FROM customer WHERE 
            customer.customer_id = :customer_id) UNION SELECT datechange.netCurrencyID AS curID FROM datechange WHERE 
            datechange.supplier = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) UNION SELECT
            hotel.netCurrencyID AS curID FROM hotel WHERE hotel.supplier_id = (SELECT affliate_supp_id FROM customer WHERE 
            customer.customer_id = :customer_id) UNION SELECT car_rental.netCurrencyID AS curID FROM car_rental WHERE 
            car_rental.supplier_id = (SELECT affliate_supp_id FROM customer WHERE customer.customer_id = :customer_id) UNION 
            SELECT payment.currencyID AS curID FROM payment WHERE payment.supp_id = (SELECT affliate_supp_id FROM customer WHERE
            customer.customer_id = :customer_id)) AS baseTable) AS finalTable WHERE total !=0 ORDER BY curName ASC");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }
    // Close connection
    unset($pdo); 
?>