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
            $checkCustomerRecordSQL = "SELECT `customer_id`,referenceDate,referenceID,statementFor FROM `statement` WHERE  
            customer_id = :id AND referenceCurrencyID = :CurID ORDER BY DATE(statement.Entrydate), statementID DESC LIMIT 1";
            $checkCustomerRecorStmt = $pdo->prepare($checkCustomerRecordSQL);
            $checkCustomerRecorStmt->bindParam(':id', $_POST['ID']);
            $checkCustomerRecorStmt->bindParam(':CurID', $_POST['CurID']);
            $checkCustomerRecorStmt->execute();
            $checkCustomerRecorStmt = $checkCustomerRecorStmt->fetchAll(\PDO::FETCH_ASSOC);
            // store total to track the records
            if (empty($checkCustomerRecorStmt)) {
                $startingBalance = 0;
                $total = 0;
                $controllingLoopForAddingPreviousBalance = 1;
                $recordsToDisplayArr = array();
                $sql = "SELECT * FROM (SELECT ticket.ticket AS refID,'Ticket' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,ticket.datetime AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')
                AS date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination,sale AS Debit
                , 0 AS Credit  FROM ticket INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS 
                to_airports ON to_airports.airport_id=ticket.to_id WHERE ticket.customer_id=:id AND ticket.currencyID = :CurID 
                UNION ALL
                SELECT visa.visa_id AS refID,'Visa' AS TRANSACTION_Type,passenger_name AS Passenger_Name,visa.datetime AS datetime
                ,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  AS date, country_names AS 
                Identification, '' AS Orgin, '' AS Destination, sale AS Debit, 0 AS Credit FROM visa INNER JOIN country_name ON 
                country_name.country_id=visa.country_id WHERE visa.customer_id=:id AND visa.saleCurrencyID = :CurID 
                UNION ALL 
                SELECT visaextracharges.visaExtraChargesID AS refID, CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN 
                visaextracharges.typeID = 2 THEN 'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS
                TRANSACTION_Type,visa.passenger_name AS Passenger_Name, visaextracharges.datetime AS datetime,
                DATE(visaextracharges.datetime) AS nonFormatedDate,DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y') AS date
                ,country_names AS Identification, '' AS Orgin, '' AS Destination,visaextracharges.salePrice AS Debit, 0 AS Credit 
                FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON
                country_name.country_id=visa.country_id WHERE visa.customer_id= :id AND visaextracharges.saleCurrencyID = :CurID
                UNION ALL
                SELECT residence.residenceID AS refID,'Residence' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                residence.datetime AS datetime,DATE(datetime) AS nonFormatedDate, DATE_FORMAT(DATE(datetime) ,'%d-%b-%Y') AS date
                ,country_names AS Identification, '' AS Orgin, '' AS Destination, sale_price AS Debit, 0 AS Credit FROM residence
                INNER JOIN country_name ON country_name.country_id= residence.VisaType WHERE residence.customer_id=:id AND 
                residence.saleCurID = :CurID 
                UNION ALL
                SELECT residencefine.residenceFineID AS refID, 'Residence Fine' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,residencefine.datetime AS datetime, DATE(residencefine.datetime) AS nonFormatedDate, 
                DATE_FORMAT(DATE(residencefine.datetime) ,'%d-%b-%Y') AS date,country_names AS Identification, '' AS Orgin, ''
                AS Destination,residencefine.fineAmount AS Debit, 0 AS Credit FROM residencefine INNER JOIN residence ON 
                residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id = 
                residence.VisaType WHERE residence.customer_id = :id AND residencefine.fineCurrencyID = :CurID 
                UNION ALL
                SELECT servicedetails.serviceDetailsID AS refID, serviceName AS TRANSACTION_Type,passenger_name AS Passenger_Name
                ,service_date AS datetime,DATE(service_date) AS nonFormatedDate, DATE_FORMAT(DATE(service_date),'%d-%b-%Y') AS 
                date,service_details AS Identification, '' AS Orgin, '' AS Destination, salePrice AS Debit, 0 AS Credit FROM 
                servicedetails INNER JOIN service ON service.serviceID= servicedetails.serviceID WHERE servicedetails.customer_id
                =:id AND servicedetails.saleCurrencyID = :CurID 
                UNION ALL
                SELECT customer_payments.pay_id AS refID, 'Payment' AS TRANSACTION_Type,CASE WHEN customer_payments.PaymentFor
                IS NOT NULL THEN CONCAT('Payment For ', CONCAT((SELECT DISTINCT passenger_name FROM residence WHERE 
                residence.residenceID = customer_payments.PaymentFor), ' Residency')) WHEN customer_payments.residenceFinePayment
                IS NOT NULL THEN CONCAT('Residence Fine Payment For ', CONCAT((SELECT DISTINCT passenger_name FROM residence 
                INNER JOIN residencefine ON residence.residenceID = residencefine.residenceID WHERE residencefine.residenceFineID
                = customer_payments.residenceFinePayment),' Residency ')) ELSE remarks END AS Passenger_Name,
                customer_payments.datetime AS datetime,DATE(datetime) AS nonFormatedDate, DATE_FORMAT(DATE(datetime),'%d-%b-%Y')
                AS date,CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN (SELECT country_names FROM country_name WHERE
                country_id = (SELECT DISTINCT residence.VisaType FROM residence WHERE residence.residenceID = 
                customer_payments.PaymentFor)) WHEN customer_payments.residenceFinePayment IS NOT NULL THEN (SELECT country_names
                FROM country_name WHERE country_id  = (SELECT DISTINCT residence.VisaType FROM residence INNER JOIN residencefine
                ON residence.residenceID = residencefine.residenceID WHERE customer_payments.residenceFinePayment  = 
                residencefine.residenceFineID)) ELSE '' END AS Identification,'' AS Orgin,'' AS Destination,0 AS Debit, 
                IFNULL(payment_amount,0) AS Credit from customer_payments WHERE customer_payments.customer_id=:id AND 
                customer_payments.currencyID = :CurID 
                UNION ALL 
                SELECT hotel.hotel_id AS refID, 'Hotel Reservation' AS TRANSACTION_Type,hotel.passenger_name AS Passenger_Name,
                hotel.datetime AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,
                CONCAT('Hotel: ',hotel_name) AS Identification,'' AS Orgin, country_names AS Destination, sale_price AS Debit,
                0 AS Credit from hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id WHERE 
                hotel.customer_id=:id AND hotel.saleCurrencyID = :CurID
                UNION ALL 
                SELECT  car_rental.car_id AS refID,'Car Reservation' AS TRANSACTION_Type,car_rental.passenger_name AS 
                Passenger_Name, car_rental.datetime as datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),
                '%d-%b-%Y') AS date,CONCAT('Car Description: ',car_description) AS Identification, '' AS Orgin, '' AS 
                Destination,sale_price AS Debit, 0 AS Credit from car_rental where car_rental.customer_id=:id AND 
                car_rental.saleCurrencyID = :CurID 
                UNION ALL 
                SELECT  datechange.change_id AS refID,'Date Extension' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime as datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as 
                date,pnr AS Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, 
                datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN ticket ON ticket.ticket = 
                datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports
                ON to_airports.airport_id=ticket.to_id where ticket.customer_id=:id AND ticketStatus = 1 AND 
                datechange.saleCurrencyID = :CurID
                UNION ALL 
                SELECT loan.loan_id AS refID, 'Loan' AS TRANSACTION_Type,'' AS Passenger_Name,loan.datetime AS datetime,
                DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  as date,remarks AS Identification,'' AS
                Orgin,'' AS Destination, amount AS Debit, 0 AS Credit From loan WHERE loan.customer_id=:id AND loan.currencyID =
                :CurID
                UNION ALL 
                SELECT datechange.change_id AS refID, 'Refund' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime AS datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as
                date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination, 0 AS Debit,
                datechange.sale_amount AS Credit from datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id 
                INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON 
                to_airports.airport_id= ticket.to_id where ticket.customer_id=:id AND ticketStatus = 2 AND 
                datechange.saleCurrencyID = :CurID) baseTable ORDER BY datetime ASC ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['ID']);
                $stmt->bindParam(':CurID', $_POST['CurID']);
                 // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($records as $record) {
                    if(date('Y', strtotime($record['date'])) < date('Y')) {
                        $startingBalance  = $startingBalance + intval($record['Debit']) - intval($record['Credit']);
                        if($startingBalance == 0){
                            // Set query
                            $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id
                            = :cusID AND referenceID = :refID AND statementFor = :stateFor";
                            $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                            $CheckRecordStmt->bindParam(':cusID', $_POST['ID']);
                            $CheckRecordStmt->bindParam(':refID', $record['refID']);
                            $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                            $CheckRecordStmt->execute();
                            // Fetch single row
                            $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                            if (!$getRecordedResult) {
                                // create prepared statement
                                $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                                `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                                :referenceCurrencyID)";
                                $stmt = $pdo->prepare($sql);
                                // bind parameters to statement
                                $stmt->bindParam(':customer_id',$_POST['ID']);
                                $stmt->bindParam(':referenceID', $record['refID']);
                                $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                                $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                                $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                                // execute the prepared statement
                                $stmt->execute();
                            }
                       }
                    }
                    else{
                        if($controllingLoopForAddingPreviousBalance == 1){
                            if($startingBalance !=0){
                                if ($startingBalance < 0) {
                                    array_push($recordsToDisplayArr,array('TRANSACTION_Type' => 'Starting Balance',
                                    'Passenger_Name' => '', 'date' => '','Identification' =>'','Orgin' => '','Destination'=>
                                     '','Debit' => 0,'Credit'=> $startingBalance));
                                }else if ($startingBalance > 0) {
                                    array_push($recordsToDisplayArr,array('TRANSACTION_Type' => 'Starting Balance',
                                    'Passenger_Name' => '', 'date' => '','Identification' =>'','Orgin' => '','Destination'=>
                                     '','Debit' => $startingBalance,'Credit'=> 0));
                                }
                            }
                            $controllingLoopForAddingPreviousBalance  = 0;
                        }
                        array_push($recordsToDisplayArr,array('TRANSACTION_Type' => $record['TRANSACTION_Type'],
                        'Passenger_Name' => $record['Passenger_Name'], 'date' => $record['date'],
                        'Identification' =>$record['Identification'],'Orgin' => $record['Orgin'],'Destination'=>
                        $record['Destination'],'Debit' => $record['Debit'],'Credit'=> $record['Credit']));
                        $total = $total + intval($record['Debit']) - $startingBalance - intval($record['Credit']);
                        if($total == 0){
                             // Set query
                             $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id
                             = :cusID AND referenceID = :refID AND statementFor = :stateFor";
                             $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                             $CheckRecordStmt->bindParam(':cusID', $_POST['ID']);
                             $CheckRecordStmt->bindParam(':refID', $record['refID']);
                             $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                             $CheckRecordStmt->execute();
                             // Fetch single row
                             $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                             if (!$getRecordedResult) {
                                // create prepared statement
                                $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                                `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                                :referenceCurrencyID)";
                                $stmt = $pdo->prepare($sql);
                                // bind parameters to statement
                                $stmt->bindParam(':customer_id',$_POST['ID']);
                                $stmt->bindParam(':referenceID', $record['refID']);
                                $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                                $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                                $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                                // execute the prepared statement
                                $stmt->execute();
                             }
                             $recordsToDisplayArr = [];
                       }

                    }
                    
                    
                    
                }
                echo json_encode($recordsToDisplayArr);
            }else{
                // if there is record then fetch the record from that date on
                $total = 0;
                $recordsToDisplayArr = array();
                $startingBalance = 0;
                $controllingLoopForAddingPreviousBalance = 1;
                $sql = "SELECT * FROM (SELECT ticket.ticket AS refID,'Ticket' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,ticket.datetime AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),
                '%d-%b-%Y') AS date,pnr AS Identification,airports.airport_code AS Orgin, to_airports.airport_code AS 
                Destination,sale AS Debit, 0 AS Credit  FROM ticket INNER JOIN airports ON airports.airport_id=ticket.from_id
                INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id WHERE ticket.customer_id=:id AND 
                ticket.currencyID = :CurID AND DATE(ticket.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT visa.visa_id AS refID,'Visa'  AS TRANSACTION_Type,passenger_name AS Passenger_Name,visa.datetime AS 
                datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  AS date, country_names AS 
                Identification, '' AS Orgin, '' AS Destination, sale AS Debit, 0 AS Credit FROM visa INNER JOIN country_name ON 
                country_name.country_id=visa.country_id WHERE visa.customer_id=:id AND visa.saleCurrencyID = :CurID  AND 
                DATE(visa.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT visaextracharges.visaExtraChargesID AS refID, CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN 
                visaextracharges.typeID = 2 THEN 'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS 
                TRANSACTION_Type,visa.passenger_name AS Passenger_Name,visaextracharges.datetime AS datetime,
                DATE(visaextracharges.datetime) AS nonFormatedDate,DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y') AS 
                date,country_names AS Identification, '' AS Orgin, '' AS Destination,visaextracharges.salePrice AS Debit, 0 AS 
                Credit FROM visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON
                country_name.country_id=visa.country_id WHERE visa.customer_id= :id AND visaextracharges.saleCurrencyID = :CurID
                AND DATE(visaextracharges.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT residence.residenceID AS refID,'Residence' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                residence.datetime AS datetime,DATE(datetime) AS nonFormatedDate, DATE_FORMAT(DATE(datetime) ,'%d-%b-%Y') AS 
                date,country_names AS Identification, '' AS Orgin, '' AS Destination, sale_price AS Debit, 0 AS Credit FROM 
                residence INNER JOIN country_name ON country_name.country_id= residence.VisaType WHERE residence.customer_id=:id
                AND residence.saleCurID = :CurID AND DATE(residence.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT residencefine.residenceFineID AS refID, 'Residence Fine' AS TRANSACTION_Type,passenger_name AS 
                Passenger_Name,residencefine.datetime AS datetime, DATE(residencefine.datetime) AS nonFormatedDate, 
                DATE_FORMAT(DATE(residencefine.datetime) ,'%d-%b-%Y') AS date,country_names AS Identification, '' AS Orgin, ''
                AS Destination,residencefine.fineAmount AS Debit, 0 AS Credit FROM residencefine INNER JOIN residence ON 
                residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id = 
                residence.VisaType WHERE residence.customer_id = :id AND residencefine.fineCurrencyID = :CurID AND
                DATE(residencefine.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT servicedetails.serviceDetailsID AS refID, serviceName AS TRANSACTION_Type,passenger_name AS Passenger_Name
                ,service_date AS datetime,DATE(service_date) AS nonFormatedDate, DATE_FORMAT(DATE(service_date),'%d-%b-%Y') AS 
                date,service_details AS Identification, '' AS Orgin, '' AS Destination,salePrice AS Debit, 0 AS Credit FROM 
                servicedetails INNER JOIN service ON service.serviceID= servicedetails.serviceID WHERE servicedetails.customer_id
                =:id AND servicedetails.saleCurrencyID = :CurID AND DATE(servicedetails.service_date) BETWEEN :from_date AND 
                CURDATE()
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
                customer_payments.currencyID = :CurID AND DATE(customer_payments.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT hotel.hotel_id AS refID, 'Hotel Reservation' AS TRANSACTION_Type,hotel.passenger_name AS Passenger_Name,
                hotel.datetime AS datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') as date,
                CONCAT('Hotel: ',hotel_name) AS Identification,'' AS Orgin, country_names AS Destination, sale_price AS Debit, 0 
                AS Credit from hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id WHERE hotel.customer_id
                =:id AND hotel.saleCurrencyID = :CurID AND DATE(hotel.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT  car_rental.car_id AS refID,'Car Reservation' AS TRANSACTION_Type,car_rental.passenger_name AS 
                Passenger_Name, car_rental.datetime as datetime,DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),
                '%d-%b-%Y') AS date,CONCAT('Car Description: ',car_description) AS Identification,'' AS Orgin, '' AS Destination,
                sale_price AS Debit, 0 AS Credit from car_rental where car_rental.customer_id=:id AND car_rental.saleCurrencyID =
                :CurID AND DATE(car_rental.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT  datechange.change_id AS refID,'Date Extension' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime as datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as 
                date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination, 
                datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN ticket ON ticket.ticket = 
                datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports
                ON to_airports.airport_id=ticket.to_id where ticket.customer_id=:id AND ticketStatus = 1 AND 
                datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT loan.loan_id AS refID, 'Loan' AS TRANSACTION_Type,'' AS Passenger_Name,loan.datetime AS datetime,
                DATE(datetime) AS nonFormatedDate,DATE_FORMAT(DATE(datetime),'%d-%b-%Y')  as date,remarks AS Identification,'' AS
                Orgin,'' AS Destination, amount AS Debit, 0 AS Credit From loan WHERE loan.customer_id=:id AND loan.currencyID =
                :CurID AND DATE(loan.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT datechange.change_id AS refID, 'Refund' AS TRANSACTION_Type,passenger_name AS Passenger_Name,
                datechange.datetime AS datetime,DATE(extended_Date) AS nonFormatedDate,DATE_FORMAT(extended_Date,'%d-%b-%Y') as
                date,pnr AS Identification,airports.airport_code AS Orgin,to_airports.airport_code AS Destination, 0 AS Debit,
                datechange.sale_amount AS Credit from datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id 
                INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON 
                to_airports.airport_id= ticket.to_id where ticket.customer_id=:id AND ticketStatus = 2 AND 
                datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime) BETWEEN :from_date AND CURDATE()) baseTable 
                ORDER BY datetime ASC ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['ID']);
                $stmt->bindParam(':CurID', $_POST['CurID']);
                $stmt->bindParam(':from_date', $checkCustomerRecorStmt[0]['referenceDate']);
                 // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $flagDecision = 0;
            
                foreach ($records as $record) {
                    if(intval($record['refID']) !=  intval($checkCustomerRecorStmt[0]['referenceID']) ){
                        if($flagDecision == 1){
                            if (date('Y', strtotime($record['nonFormatedDate'])) < date('Y')) {
                                $startingBalance  = $startingBalance + intval($record['Debit']) - intval($record['Credit']);
                                if($startingBalance == 0){
                                    // Set query
                                    $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id
                                    = :cusID AND referenceID = :refID AND statementFor = :stateFor";
                                    $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                                    $CheckRecordStmt->bindParam(':cusID', $_POST['ID']);
                                    $CheckRecordStmt->bindParam(':refID', $record['refID']);
                                    $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                                    $CheckRecordStmt->execute();
                                    // Fetch single row
                                    $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                                    if (!$getRecordedResult) {
                                        // create prepared statement
                                        $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                                        `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                                        :referenceCurrencyID)";
                                        $stmt = $pdo->prepare($sql);
                                        // bind parameters to statement
                                        $stmt->bindParam(':customer_id',$_POST['ID']);
                                        $stmt->bindParam(':referenceID', $record['refID']);
                                        $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                                        $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                                        $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                                        // execute the prepared statement
                                        $stmt->execute();
                                    }
                                }
                            }else{
                                if($controllingLoopForAddingPreviousBalance == 1){
                                    if($startingBalance !=0){
                                        if ($startingBalance < 0) {
                                            array_push($recordsToDisplayArr,array('TRANSACTION_Type' => 'Starting Balance',
                                            'Passenger_Name' => '', 'date' => '','Identification' =>'','Orgin' => '','Destination'=>
                                            '','Debit' => 0,'Credit'=> $startingBalance));
                                        }else if ($startingBalance > 0) {
                                            array_push($recordsToDisplayArr,array('TRANSACTION_Type' => 'Starting Balance',
                                            'Passenger_Name' => '', 'date' => '','Identification' =>'','Orgin' => '','Destination'=>
                                            '','Debit' => $startingBalance,'Credit'=> 0));
                                        }
                                    }
                                    $controllingLoopForAddingPreviousBalance  = 0;
                                }
                                array_push($recordsToDisplayArr,array('TRANSACTION_Type' => $record['TRANSACTION_Type'],
                                'Passenger_Name' => $record['Passenger_Name'], 'date' => $record['date'],
                                'Identification' =>$record['Identification'],'Orgin' => $record['Orgin'],'Destination'=>
                                $record['Destination'],'Debit' => $record['Debit'],'Credit'=> $record['Credit']));
                                $total = $total + intval($record['Debit']) - $startingBalance - intval($record['Credit']);
                                if($total == 0){
                                    // Set query
                                    $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id
                                    = :cusID AND referenceID = :refID AND statementFor = :stateFor";
                                    $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                                    $CheckRecordStmt->bindParam(':cusID', $_POST['ID']);
                                    $CheckRecordStmt->bindParam(':refID', $record['refID']);
                                    $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                                    $CheckRecordStmt->execute();
                                    // Fetch single row
                                    $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                                    if (!$getRecordedResult) {
                                        // create prepared statement
                                        $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                                        `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                                        :referenceCurrencyID)";
                                        $stmt = $pdo->prepare($sql);
                                        // bind parameters to statement
                                        $stmt->bindParam(':customer_id',$_POST['ID']);
                                        $stmt->bindParam(':referenceID', $record['refID']);
                                        $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                                        $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                                        $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                                        // execute the prepared statement
                                        $stmt->execute();
                                    }
                                    $recordsToDisplayArr = [];
                                }
                            } 
                        }
                    }else{
                        if($record['TRANSACTION_Type'] == $checkCustomerRecorStmt[0]['statementFor']){
                            $flagDecision =1;
                        }else{
                            $flagDecision = 0;
                        }
                    }
                }
                echo json_encode($recordsToDisplayArr);
            }
    }
    // Close connection
    unset($pdo); 
?>