<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
if(isset($_POST['GetCustomerInfo'])){
        $sql = "SELECT `supp_name`, `supp_email`, `supp_phone` FROM `supplier` WHERE supp_id =:id";
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
            $sql = "SELECT * FROM (SELECT  'Ticket' AS TRANSACTION_Type,ticket.passenger_name AS passenger_name,ticket.datetime
            AS datetime,DATE_FORMAT(DATE(datetime),'%d-%b-%Y' ) as date,pnr AS Identification,airports.airport_code AS Orgin, 
            to_airports.airport_code AS Destination, net_price AS Debit, 0 AS Credit,'' AS remarks  FROM ticket INNER JOIN 
            airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=
            ticket.to_id where ticket.supp_id=:id AND ticket.net_CurrencyID = :CurID
            UNION ALL
            SELECT 'Visa'  AS TRANSACTION_Type,visa.passenger_name AS passenger_name,visa.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y' ) as date,country_names AS Identification, '' AS Orgin, '' AS Destination,
            net_price AS Debit, 0 AS Credit,'' AS remarks FROM visa INNER JOIN country_name ON country_name.country_id=
            visa.country_id where visa.supp_id=:id AND visa.netCurrencyID = :CurID
            UNION ALL 
            SELECT CASE WHEN typeID = 1 THEN 'Visa Fine' WHEN typeID = 2 THEN 'Escape Report' WHEN typeID = 3 THEN 
            'Escape Removal'END AS TRANSACTION_Type,visa.passenger_name AS passenger_name,visaextracharges.datetime AS datetime,
            DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y' )as date,country_names AS Identification, '' AS Orgin, '' AS
            Destination,visaextracharges.net_price AS Debit, 0 AS Credit,'' AS remarks FROM visaextracharges INNER JOIN visa ON 
            visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON country_name.country_id=visa.country_id  
            where visaextracharges.supplierID=:id AND visaextracharges.netCurrencyID = :CurID
            UNION ALL
            SELECT 'Offer Letter Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime 
            AS datetime, DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y' ) as date,country_names AS Identification, '' AS Orgin,
            '' AS Destination, residence.offerLetterCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN 
            country_name ON country_name.country_id= residence.VisaType where residence.offerLetterSupplier=:id AND 
            residence.offerLetterCostCur = :CurID
            UNION ALL
            SELECT 'Insurance Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS datetime
            ,DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') as date,country_names AS Identification, '' AS Orgin, '' AS
            Destination, residence.insuranceCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON 
            country_name.country_id= residence.VisaType where residence.insuranceSupplier=:id AND residence.insuranceCur = :CurID
            UNION ALL
            SELECT 'Labour Card Fee'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS datetime
            ,DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y')as date,country_names AS Identification, '' AS Orgin, '' AS 
            Destination, residence.laborCardFee AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON 
            country_name.country_id= residence.VisaType where residence.laborCardSupplier=:id AND residence.laborCardCur = :CurID
            UNION ALL
            SELECT 'E-Visa Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS datetime,
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') as date,country_names AS Identification, '' AS Orgin, '' AS 
            Destination, residence.eVisaCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON 
            country_name.country_id= residence.VisaType where residence.eVisaSupplier=:id AND residence.eVisaCur = :CurID
            UNION ALL
            SELECT 'Change Status Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS 
            datetime, DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y')as date,country_names AS Identification, '' AS Orgin, '' AS
            Destination, residence.changeStatusCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON
            country_name.country_id= residence.VisaType where residence.changeStatusSupplier=:id AND residence.changeStatusCur =
            :CurID
            UNION ALL
            SELECT 'Medical Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS datetime,
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y')as date,country_names AS Identification, '' AS Orgin, '' AS 
            Destination, residence.medicalTCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON 
            country_name.country_id= residence.VisaType where residence.medicalSupplier=:id AND residence.medicalTCur = :CurID
            UNION ALL
            SELECT 'Emirates ID Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS 
            datetime, DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') as date,country_names AS Identification, '' AS Orgin, ''
            AS Destination, residence.emiratesIDCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name ON
            country_name.country_id= residence.VisaType where residence.emiratesIDSupplier=:id AND residence.emiratesIDCur = :CurID
            UNION ALL
            SELECT 'Visa Stamping Cost'  AS TRANSACTION_Type,residence.passenger_name AS passenger_name,residence.datetime AS 
            datetime,DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y')as date,country_names AS Identification, '' AS Orgin, '' 
            AS Destination, residence.visaStampingCost AS Debit, 0 AS Credit,'' AS remarks FROM residence INNER JOIN country_name 
            ON country_name.country_id= residence.VisaType where residence.visaStampingSupplier=:id AND residence.visaStampingCur 
            = :CurID
            UNION ALL
            SELECT serviceName  AS TRANSACTION_Type,servicedetails.passenger_name AS passenger_name ,service_date AS datetime,
            DATE_FORMAT(DATE(service_date),'%d-%b-%Y')as date,service_details AS Identification, '' AS Orgin, '' AS Destination,
            netPrice AS Debit, 0 AS Credit,'' AS remarks FROM servicedetails INNER JOIN service ON service.serviceID= 
            servicedetails.serviceID WHERE servicedetails.Supplier_id=:id AND servicedetails.netCurrencyID = :CurID
            UNION ALL
            SELECT  'Payment' AS TRANSACTION_Type,'' AS passenger_name,payment.time_creation AS datetime,
            DATE_FORMAT(DATE(time_creation),'%d-%b-%Y')as date,'' AS Identification,'' AS Orgin, '' AS Destination, 0 AS Debit, 
            IFNULL(payment_amount,0) AS Credit, payment_detail AS remarks from payment where payment.supp_id=:id AND
            payment.currencyID = :CurID
            UNION ALL 
            SELECT  'Hotel Reservation' AS TRANSACTION_Type, passenger_name  AS passenger_name,hotel.datetime 
            AS datetime,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,CONCAT('Hotel: ',hotel_name) AS Identification,'' AS Orgin,
            country_names AS Destination, net_price AS Debit, 0 AS Credit, '' AS remarks from hotel INNER JOIN country_name ON 
            country_name.country_id = hotel.country_id where hotel.supplier_id=:id AND hotel.netCurrencyID = :CurID
            UNION ALL 
            SELECT  'Car Reservation' AS TRANSACTION_Type,passenger_name  AS passenger_name, car_rental.datetime AS datetime,
            DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,CONCAT('Car Description: ',car_description) AS Identification,'' AS
            Orgin, '' AS Destination, net_price AS Debit, 0 AS Credit, '' AS remarks from car_rental where supplier_id=:id AND 
            car_rental.netCurrencyID = :CurID
            UNION ALL 
            SELECT  'Date Extension' AS TRANSACTION_Type,ticket.passenger_name AS passenger_name,datechange.datetime AS datetime,
            DATE_FORMAT(extended_Date,'%d-%b-%Y') as date,pnr AS Identification,airports.airport_code AS Orgin,
            to_airports.airport_code AS Destination, datechange.net_amount AS Debit, 0 AS Credit, '' AS remarks from datechange
            INNER JOIN ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id
            INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where datechange.supplier=:id AND 
            ticketStatus = 1  AND datechange.netCurrencyID = :CurID
            UNION ALL 
            SELECT  'Refund' AS TRANSACTION_Type,ticket.passenger_name AS passenger_name,datechange.datetime AS datetime,
            DATE_FORMAT(extended_Date,'%d-%b-%Y') as date,pnr AS Identification,airports.airport_code AS Orgin, 
            to_airports.airport_code AS Destination, 0 AS Debit, net_amount AS Credit, '' AS remarks from datechange INNER JOIN 
            ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN
            airports AS to_airports ON to_airports.airport_id=ticket.to_id where datechange.supplier=:id AND ticketStatus = 2 AND
            datechange.netCurrencyID = :CurID) baseTable ORDER BY datetime ASC ";
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