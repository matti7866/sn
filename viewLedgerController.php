<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
if(isset($_POST['GetCustomerInfo'])){
        $sql = "SELECT customer_name, customer_phone, customer_email FROM `customer` WHERE customer_id =:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $staffBranchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    echo json_encode($staffBranchID);
    }else if(isset($_POST['GetLedgerCurrency'])){
        $sql = "SELECT currencyName FROM currency WHERE currencyID =:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $currency = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    echo json_encode($currency);
    }else if(isset($_POST['GetTicketReport'])){
            $sql = "SELECT * FROM (SELECT  'Ticket' AS TRANSACTION_Type,passenger_name AS Passenger_Name,ticket.datetime 
            AS datetime,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS date,pnr AS Identification,airports.airport_code AS Orgin,
            to_airports.airport_code AS Destination,sale AS Debit, 0 AS Credit  FROM ticket INNER JOIN airports ON
            airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id
            WHERE ticket.customer_id=:id AND ticket.currencyID = :CurID
            UNION ALL
            SELECT 'Visa'  AS TRANSACTION_Type,passenger_name AS Passenger_Name,visa.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  AS date, country_names AS Identification, '' AS Orgin, '' AS Destination,
            sale AS Debit, 0 AS Credit FROM visa INNER JOIN country_name ON country_name.country_id=visa.country_id WHERE 
            visa.customer_id=:id AND visa.saleCurrencyID = :CurID
            UNION ALL 
            SELECT CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN visaextracharges.typeID = 2 THEN 'Escape Report' 
            WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS TRANSACTION_Type,visa.passenger_name AS Passenger_Name,
            visaextracharges.datetime AS datetime,DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y') AS date,country_names
            AS Identification, '' AS Orgin, '' AS Destination,visaextracharges.salePrice AS Debit, 0 AS Credit FROM 
            visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON
            country_name.country_id=visa.country_id WHERE visa.customer_id= :id AND visaextracharges.saleCurrencyID = :CurID
            UNION ALL
            SELECT 'Residence' AS TRANSACTION_Type,passenger_name AS Passenger_Name,residence.datetime AS datetime,
            DATE_FORMAT(DATE(datetime) ,'%d-%b-%Y') AS date,country_names AS Identification, '' AS Orgin, '' AS Destination, 
            sale_price AS Debit, 0 AS Credit FROM residence INNER JOIN country_name ON country_name.country_id= residence.VisaType
            WHERE residence.customer_id=:id AND residence.saleCurID = :CurID
            UNION ALL
            SELECT serviceName AS TRANSACTION_Type,passenger_name AS Passenger_Name,service_date AS datetime,
            DATE_FORMAT(DATE(service_date),'%d-%b-%Y') AS date,service_details AS Identification, '' AS Orgin, '' AS Destination,
            salePrice AS Debit, 0 AS Credit FROM servicedetails INNER JOIN service ON service.serviceID= servicedetails.serviceID
            WHERE servicedetails.customer_id=:id AND servicedetails.saleCurrencyID = :CurID
            UNION ALL
            SELECT  'Payment' AS TRANSACTION_Type,CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN CONCAT('Payment For ', 
            CONCAT((SELECT DISTINCT passenger_name FROM residence WHERE residence.residenceID = customer_payments.PaymentFor), 
            ' Residency')) ELSE remarks END AS Passenger_Name,customer_payments.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN
            (SELECT country_names FROM country_name WHERE country_id = (SELECT DISTINCT residence.VisaType FROM residence WHERE
            residence.residenceID = customer_payments.PaymentFor)) ELSE '' END AS Identification,'' AS Orgin,'' AS Destination,
            0 AS Debit, IFNULL(payment_amount,0) AS Credit from customer_payments WHERE customer_payments.customer_id=:id AND 
            customer_payments.currencyID = :CurID
            UNION ALL 
            SELECT  'Hotel Reservation' AS TRANSACTION_Type,hotel.passenger_name AS Passenger_Name,hotel.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,CONCAT('Hotel: ',hotel_name) AS Identification,'' AS Orgin, 
            country_names AS Destination, sale_price AS Debit, 0 AS Credit from hotel INNER JOIN country_name ON 
            country_name.country_id = hotel.country_id WHERE hotel.customer_id=:id AND hotel.saleCurrencyID = :CurID
            UNION ALL 
            SELECT  'Car Reservation' AS TRANSACTION_Type,car_rental.passenger_name AS Passenger_Name, car_rental.datetime as 
            datetime,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS date,CONCAT('Car Description: ',car_description) AS Identification,
            '' AS Orgin, '' AS Destination,sale_price AS Debit, 0 AS Credit from car_rental where car_rental.customer_id=:id AND
            car_rental.saleCurrencyID = :CurID
            UNION ALL 
            SELECT  'Date Extension' AS TRANSACTION_Type,passenger_name AS Passenger_Name,datechange.datetime as datetime,
            DATE_FORMAT(extended_Date,'%d-%b-%Y') as date,pnr AS Identification,airports.airport_code AS Orgin, 
            to_airports.airport_code AS Destination, datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN 
            ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN 
            airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.customer_id=:id AND ticketStatus = 1 AND
            datechange.saleCurrencyID = :CurID
            UNION ALL 
            SELECT  'Loan' AS TRANSACTION_Type,'' AS Passenger_Name,loan.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  as date,remarks AS Identification,'' AS Orgin,'' AS Destination, amount AS 
            Debit, 0 AS Credit From loan WHERE loan.customer_id=:id AND loan.currencyID = :CurID
            UNION ALL 
            SELECT  'Refund' AS TRANSACTION_Type,passenger_name AS Passenger_Name,datechange.datetime AS datetime,
            DATE_FORMAT(extended_Date,'%d-%b-%Y') as date,pnr AS Identification,airports.airport_code AS Orgin, 
            to_airports.airport_code AS Destination, 0 AS Debit, datechange.sale_amount AS Credit from datechange INNER JOIN
            ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN 
            airports AS to_airports ON to_airports.airport_id= ticket.to_id where ticket.customer_id=:id AND ticketStatus = 2 AND
            datechange.saleCurrencyID = :CurID ) baseTable ORDER BY datetime ASC ";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':id', $_POST['ID']);
            $stmt->bindParam(':CurID', $_POST['CurID']);
            // execute the prepared statement
            $stmt->execute();
            $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            echo json_encode($records);
    }
    // Close connection
    unset($pdo); 
?>