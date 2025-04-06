<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetTicketReport'])){
                $sql = "SELECT * FROM (SELECT ticket.ticket AS refID,'Ticket' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,ticket.datetime AS datetime,DATE(datetime) AS nonFormatedDate,
                DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS date,pnr AS Identification,airports.airport_code AS Orgin,
                to_airports.airport_code AS Destination,sale AS Debit, 0 AS Credit  FROM ticket INNER JOIN airports ON
                airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id
                WHERE ticket.customer_id=:id AND ticket.currencyID = :CurID AND DATE(ticket.datetime) >= DATE_SUB(NOW(), 
                INTERVAL 60 DAY) AND ticket.ticket != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails
                WHERE invoicedetails.transactionID = ticket.ticket AND invoicedetails.transactionType = 'Ticket'),0)
                UNION ALL
                SELECT visa.visa_id AS refID,'Visa'  AS TRANSACTION_Type,passenger_name AS Passenger_Name,visa.datetime 
                AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  AS date, country_names AS
                Identification, '' AS Orgin, '' AS Destination,sale AS Debit, 0 AS Credit FROM visa INNER JOIN country_name ON 
                country_name.country_id=visa.country_id WHERE visa.customer_id=:id AND visa.saleCurrencyID = :CurID AND
                DATE(visa.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND visa.visa_id != IFNULL((SELECT 
                IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE invoicedetails.transactionID = visa.visa_id AND
                invoicedetails.transactionType = 'Visa'),0)
                UNION ALL 
                SELECT visaextracharges.visaExtraChargesID AS refID, CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN
                visaextracharges.typeID = 2 THEN 'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS 
                TRANSACTION_Type,visa.passenger_name AS Passenger_Name,visaextracharges.datetime AS datetime,
                DATE(visaextracharges.datetime) AS nonFormatedDate,DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y') AS
                date,country_names AS Identification, '' AS Orgin, '' AS Destination,visaextracharges.salePrice AS Debit, 0 AS 
                Credit FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON
                country_name.country_id=visa.country_id WHERE visa.customer_id= :id AND visaextracharges.saleCurrencyID = :CurID
                AND DATE(visaextracharges.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND visaextracharges.visaExtraChargesID 
                != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE invoicedetails.transactionID = 
                visaextracharges.visaExtraChargesID AND invoicedetails.transactionType  
                IN('Visa Fine','Escape Report','Escape Removal')),0)
                UNION ALL
                SELECT residence.residenceID AS refID,'Residence' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                residence.datetime AS datetime,DATE(datetime) AS nonFormatedDate, DATE_FORMAT(DATE(datetime) ,'%d-%b-%Y') AS
                date,country_names AS Identification, '' AS Orgin, '' AS Destination,sale_price AS Debit, 0 AS Credit FROM 
                residence INNER JOIN country_name ON country_name.country_id= residence.VisaType WHERE residence.customer_id=:id
                AND residence.saleCurID = :CurID AND DATE(residence.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND 
                residence.residenceID != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE 
                invoicedetails.transactionID = residence.residenceID AND invoicedetails.transactionType = 'Residence'),0)
                UNION ALL
                SELECT residencefine.residenceFineID AS refID, 'Residence Fine' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,residencefine.datetime AS datetime, DATE(residencefine.datetime) AS nonFormatedDate, 
                DATE_FORMAT(DATE(residencefine.datetime) ,'%d-%b-%Y') AS date,country_names AS Identification, '' AS Orgin, ''
                AS Destination,residencefine.fineAmount AS Debit, 0 AS Credit FROM residencefine INNER JOIN residence ON 
                residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id = 
                residence.VisaType WHERE residence.customer_id = :id AND residencefine.fineCurrencyID = :CurID AND 
                DATE(residencefine.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND residencefine.residenceFineID != 
                IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE invoicedetails.transactionID 
                = residencefine.residenceFineID AND invoicedetails.transactionType = 'Residence Fine'),0)
                UNION ALL
                SELECT servicedetails.serviceDetailsID AS refID, serviceName AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,service_date AS datetime,DATE(service_date) AS nonFormatedDate, 
                DATE_FORMAT(DATE(service_date),'%d-%b-%Y') AS date,service_details AS Identification, '' AS Orgin, '' AS
                Destination,salePrice AS Debit, 0 AS Credit FROM servicedetails INNER JOIN service ON service.serviceID= 
                servicedetails.serviceID WHERE servicedetails.customer_id=:id AND servicedetails.saleCurrencyID = :CurID AND
                DATE(servicedetails.service_date) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND servicedetails.serviceDetailsID != 
                IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE invoicedetails.transactionID = 
                servicedetails.serviceDetailsID AND invoicedetails.transactionType IN (SELECT DISTINCT  service.serviceName FROM
                service INNER JOIN servicedetails ON service.serviceID = servicedetails.serviceID WHERE 
                servicedetails.serviceDetailsID = servicedetails.serviceDetailsID)),0)
                UNION ALL
                SELECT customer_payments.pay_id AS refID, 'Payment' AS TRANSACTION_Type,CASE WHEN customer_payments.PaymentFor
                IS NOT NULL THEN CONCAT('Payment For ', CONCAT((SELECT DISTINCT passenger_name FROM residence WHERE 
                residence.residenceID = customer_payments.PaymentFor), ' Residency')) WHEN customer_payments.residenceFinePayment
                IS NOT NULL THEN  CONCAT('Residence Fine Payment For ', CONCAT((SELECT DISTINCT passenger_name FROM residence 
                INNER JOIN residencefine ON residence.residenceID = residencefine.residenceID WHERE residencefine.residenceFineID
                = customer_payments.residenceFinePayment), ' Residency'))  ELSE remarks END AS Passenger_Name,
                customer_payments.datetime AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') 
                as date,CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN (SELECT country_names FROM country_name WHERE 
                country_id = (SELECT DISTINCT residence.VisaType FROM residence WHERE residence.residenceID = 
                customer_payments.PaymentFor))  WHEN customer_payments.residenceFinePayment IS NOT NULL THEN (SELECT country_names 
                FROM country_name WHERE country_id  = (SELECT DISTINCT residence.VisaType FROM residence INNER JOIN residencefine
                ON residence.residenceID = residencefine.residenceID WHERE customer_payments.residenceFinePayment  = 
                residencefine.residenceFineID)) ELSE '' END AS Identification,'' AS Orgin,'' AS Destination,0 AS Debit, 
                IFNULL(payment_amount,0) AS Credit from customer_payments WHERE customer_payments.customer_id= :id AND 
                customer_payments.currencyID = :CurID AND DATE(customer_payments.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) 
                AND customer_payments.pay_id != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE
                invoicedetails.transactionID = customer_payments.pay_id AND invoicedetails.transactionType = 'Payment'),0)
                UNION ALL 
                SELECT hotel.hotel_id AS refID, 'Hotel Reservation' AS TRANSACTION_Type,hotel.passenger_name AS Passenger_Name,
                hotel.datetime AS datetime, DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,
                CONCAT('Hotel: ',hotel_name) AS Identification,'' AS Orgin, country_names AS Destination, sale_price AS Debit,
                0 AS Credit from hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id WHERE 
                hotel.customer_id=:id AND hotel.saleCurrencyID = :CurID AND DATE(hotel.datetime) >= DATE_SUB(NOW(), 
                INTERVAL 60 DAY) AND hotel.hotel_id != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails
                WHERE invoicedetails.transactionID = hotel.hotel_id AND invoicedetails.transactionType = 'Hotel Reservation'),0)
                UNION ALL 
                SELECT  car_rental.car_id AS refID,'Car Reservation' AS TRANSACTION_Type,car_rental.passenger_name AS
                Passenger_Name, car_rental.datetime as datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),
                '%d-%b-%Y') AS date,CONCAT('Car Description: ',car_description) AS Identification, '' AS Orgin, '' AS 
                Destination,sale_price AS Debit, 0 AS Credit from car_rental where car_rental.customer_id=:id AND
                car_rental.saleCurrencyID = :CurID AND DATE(car_rental.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY)
                AND car_rental.car_id != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE 
                invoicedetails.transactionID = car_rental.car_id AND invoicedetails.transactionType = 'Car Reservation'),0)
                UNION ALL 
                SELECT  datechange.change_id AS refID,'Date Extension' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime as datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as
                date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination, 
                datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN ticket ON ticket.ticket = 
                datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports
                ON to_airports.airport_id=ticket.to_id where ticket.customer_id=:id AND ticketStatus = 1 AND 
                datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY)
                AND datechange.change_id != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE 
                invoicedetails.transactionID = datechange.change_id AND invoicedetails.transactionType = 'Date Extension'),0)
                UNION ALL 
                SELECT loan.loan_id AS refID, 'Loan' AS TRANSACTION_Type,'' AS Passenger_Name,loan.datetime AS datetime,
                DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  as date,remarks AS Identification,'' 
                AS Orgin,'' AS Destination, amount AS Debit, 0 AS Credit From loan WHERE loan.customer_id=:id AND loan.currencyID
                = :CurID AND DATE(loan.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND loan.loan_id != IFNULL((SELECT 
                IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE invoicedetails.transactionID = loan.loan_id AND
                invoicedetails.transactionType = 'Loan'),0)
                UNION ALL 
                SELECT datechange.change_id AS refID, 'Refund' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime AS datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as 
                date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination, 0 AS Debit,
                datechange.sale_amount AS Credit from datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id 
                INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON 
                to_airports.airport_id= ticket.to_id where ticket.customer_id=:id AND ticketStatus = 2 AND 
                datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime) >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND 
                datechange.change_id != IFNULL((SELECT IFNULL(invoicedetails.transactionID,0) FROM invoicedetails WHERE 
                invoicedetails.transactionID = datechange.change_id AND invoicedetails.transactionType = 'Refund'),0)) baseTable 
                ORDER BY datetime ASC ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['ID']);
                $stmt->bindParam(':CurID', $_POST['CurID']);
                 // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                echo json_encode($records);
    }else if(isset($_POST['SaveReceiptInfo'])){
        try{
            $pdo->beginTransaction();
            if($_POST['ID'] == "" || $_POST['ID'] == "undefined" || $_POST['ID'] == 0 || $_POST['ID'] ==null){
                $pdo->rollback();
                echo "Something went wrong! Please refresh the page and then continue. ";
            }
            $receiptArr = json_decode($_POST['ReceiptArr'],true);
            foreach($receiptArr as $receipt){
                if($receipt['id'] == "" || $receipt['id'] == 0 || $receipt['id'] == "undefined" || $receipt['id'] == null){
                    $pdo->rollback();
                    echo "Something went wrong! Please refresh the page and then continue. ";
                }
                
                if($receipt['type'] == "" || $receipt['type'] == "undefined" || $receipt['type'] == null){
                    $pdo->rollback();
                    echo "Something went wrong! Please refresh the page and then continue. ";
                }
            }
            // generate a random number
            $random_number = rand(1000, 9999); // generate a random number between 1000 and 9999
            // generate the receipt number with today's date and the random number
            $invoiceNumber = 'SN-RPT-' . date('dmy') . '-' . $random_number;
            // check if the receipt number already exists in the database
            $UniqueInvSql = "SELECT COUNT(*) AS count FROM invoice WHERE invoiceNumber = :invoiceNumber";
            $UniqueInvStmt = $pdo->prepare($UniqueInvSql);
            $UniqueInvStmt->bindParam(':invoiceNumber', $invoiceNumber);
            $UniqueInvStmt->execute();
            $count = $UniqueInvStmt->fetchColumn();
                while ($count > 0) {
                    // if the receipt number already exists, generate a new random number and try again
                    $random_number = rand(1000, 9999);
                    $invoiceNumber = 'SN-RPT-' . date('dmy') . '-' . $random_number;
                    
                    $UniqueInvSql = "SELECT COUNT(*) AS count FROM invoice WHERE invoiceNumber = :invoiceNumber";
                    $UniqueInvStmt = $pdo->prepare($UniqueInvSql);
                    $UniqueInvStmt->bindParam(':invoiceNumber', $invoiceNumber);
                    $UniqueInvStmt->execute();
                    $count = $UniqueInvStmt->fetchColumn();
                }
                // insert the receipt number into the database
                $InsertReceiptSql = "INSERT INTO `invoice`(`customerID`, `invoiceNumber`,`invoiceCurrency`) VALUES 
                (:customerID,:invoiceNumber,:invoiceCurrency)";
                $InsertReceiptStmt = $pdo->prepare($InsertReceiptSql);
                $InsertReceiptStmt->bindParam(':customerID', $_POST['ID']);
                $InsertReceiptStmt->bindParam(':invoiceNumber', $invoiceNumber);
                $InsertReceiptStmt->bindParam(':invoiceCurrency', $_POST['CurID'] );
                
                $InsertReceiptStmt->execute();
                // get the auto-generated ID of the new row
                $new_id = $pdo->lastInsertId();
                if($new_id == 0 ){
                    $pdo->rollback();
                    echo "Something went wrong! Refresh the page.";
                }
                for($j = 0; $j<count($receiptArr); $j++){
                    // insert the receipt number into the database
                    $InvDetailSql = "INSERT INTO `invoicedetails`(`invoiceID`, `transactionID`, `transactionType`) VALUES 
                    (:invoiceID,:transactionID,:transactionType)";
                    $InvDetailStmt = $pdo->prepare($InvDetailSql);
                    $InvDetailStmt->bindParam(':invoiceID', $new_id);
                    $InvDetailStmt->bindParam(':transactionID', $receiptArr[$j]['id']);
                    $InvDetailStmt->bindParam(':transactionType', $receiptArr[$j]['type']);
                    $InvDetailStmt->execute();
                }
                $pdo->commit(); 
                echo "Success&" . $new_id;
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>