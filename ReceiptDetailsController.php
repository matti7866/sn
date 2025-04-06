<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetCustomerInfo'])){
        $sql = "SELECT invoiceNumber,invoiceCurrency,invoice.customerID,customer_name,DATE_FORMAT(DATE(invoiceDate),'%d-%b-%Y') AS invoiceDate, 
        currencyName  FROM `invoice` INNER JOIN customer ON customer.customer_id = invoice.customerID INNER JOIN currency ON 
        currency.currencyID = invoice.invoiceCurrency WHERE invoiceID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_POST['ID']);
        $stmt->execute();
        $staffBranchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        echo json_encode($staffBranchID);
    }
    else if(isset($_POST['GetTicketReport'])){
                $sql = "SELECT * FROM (SELECT invoicedetails.transactionType AS transactionType, 
                CONCAT(CONCAT(CONCAT(CONCAT('From: ',airports.airport_code),' '),'To: ') , to_airports.airport_code) AS 
                serviceInfo,ticket.passenger_name AS PassengerName,DATE_FORMAT(DATE(ticket.datetime),'%d-%b-%Y') AS 
                formatedDate,ticket.datetime AS datetime, ticket.sale AS salePrice FROM invoicedetails INNER JOIN ticket ON 
                ticket.ticket = invoicedetails.transactionID INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN
                airports AS to_airports ON to_airports.airport_id=ticket.to_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Ticket'
                UNION ALL
                SELECT invoicedetails.transactionType AS transactionType,country_names AS serviceInfo,passenger_name AS 
                PassengerName,DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS formatedDate, visa.datetime AS datetime,visa.sale AS
                salePrice FROM visa INNER JOIN country_name ON country_name.country_id=visa.country_id INNER JOIN invoicedetails
                ON invoicedetails.transactionID = visa.visa_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Visa'
                UNION ALL
                SELECT  CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN visaextracharges.typeID = :id THEN 
                'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS transactionType,country_names AS 
                serviceInfo,visa.passenger_name AS PassengerName,DATE_FORMAT(DATE(visaextracharges.datetime),'%d-%b-%Y') AS 
                formatedDate,visaextracharges.datetime AS datetime,visaextracharges.salePrice AS salePrice FROM visaextracharges
                INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id INNER JOIN country_name ON country_name.country_id=
                visa.country_id INNER JOIN invoicedetails ON invoicedetails.transactionID = visaextracharges.visaExtraChargesID
                WHERE invoicedetails.invoiceID = :id AND invoicedetails.transactionType AND invoicedetails.transactionType 
                IN('Visa Fine','Escape Report','Escape Removal') 
                UNION ALL
                SELECT 'Residence' AS transactionType,country_names AS serviceInfo, passenger_name AS PassengerName,
                DATE_FORMAT(DATE(residence.datetime) ,'%d-%b-%Y') formatedDate, residence.datetime AS datetime, sale_price AS
                salePrice FROM residence INNER JOIN country_name ON country_name.country_id= residence.VisaType INNER JOIN 
                invoicedetails ON invoicedetails.transactionID = residence.residenceID WHERE invoicedetails.invoiceID = :id AND
                invoicedetails.transactionType = 'Residence'
                UNION ALL
                SELECT 'Residence Fine' AS transactionType,country_names AS serviceInfo,passenger_name AS PassengerName,
                DATE_FORMAT(DATE(residencefine.datetime) ,'%d-%b-%Y') AS formatedDate, residencefine.datetime AS datetime,
                residencefine.fineAmount AS salePrice FROM residencefine INNER JOIN residence ON residence.residenceID = 
                residencefine.residenceID INNER JOIN country_name ON country_name.country_id = residence.VisaType INNER JOIN 
                invoicedetails ON invoicedetails.transactionID = residencefine.residenceFineID WHERE invoicedetails.invoiceID =
                :id AND invoicedetails.transactionType = 'Residence Fine'
                UNION ALL
                SELECT serviceName AS transactionType, service_details AS serviceInfo,passenger_name AS  PassengerName,
                DATE_FORMAT(DATE(service_date),'%d-%b-%Y') AS formatedDate, service_date AS datetime, salePrice AS salePrice 
                FROM servicedetails INNER JOIN service ON service.serviceID= servicedetails.serviceID INNER JOIN invoicedetails
                ON invoicedetails.transactionID = servicedetails.serviceDetailsID WHERE invoicedetails.invoiceID = :id AND
                invoicedetails.transactionType = service.serviceName
                UNION ALL
                SELECT 'Payment' AS transactionType, CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN 'For Residence' 
                WHEN customer_payments.residenceFinePayment IS NOT NULL THEN 'For Residence Fine' ELSE customer_payments.remarks 
                END AS serviceInfo, CASE WHEN customer_payments.PaymentFor IS NOT NULL THEN (SELECT DISTINCT passenger_name FROM
                residence WHERE residence.residenceID = customer_payments.PaymentFor) WHEN customer_payments.residenceFinePayment
                IS NOT NULL THEN (SELECT DISTINCT passenger_name FROM residence INNER JOIN residencefine ON residence.residenceID
                = residencefine.residenceID WHERE residencefine.residenceFineID = customer_payments.residenceFinePayment)
                ELSE '' END AS PassengerName, DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS formatedDate, 
                customer_payments.datetime AS datetime, customer_payments.payment_amount AS salePrice FROM customer_payments 
                INNER JOIN invoicedetails ON invoicedetails.transactionID = customer_payments.pay_id WHERE
                invoicedetails.invoiceID = :id AND invoicedetails.transactionType = 'Payment' 
                UNION ALL 
                SELECT 'Hotel Reservation' AS transactionType,country_names AS serviceInfo,hotel.passenger_name AS PassengerName,
                DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS formatedDate,hotel.datetime AS datetime,sale_price AS salePrice FROM 
                hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id INNER JOIN invoicedetails ON 
                invoicedetails.transactionID = hotel.hotel_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Hotel Reservation'
                UNION ALL
                SELECT 'Car Reservation' AS transactionType,CONCAT('Car Description: ',car_description) AS serviceInfo, 
                car_rental.passenger_name AS PassengerName, DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS formatedDate, 
                car_rental.datetime as datetime,sale_price AS salePrice FROM car_rental INNER JOIN invoicedetails ON 
                invoicedetails.transactionID = car_rental.car_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Car Reservation'
                UNION ALL
                SELECT 'Date Extension' AS transactionType,
                CONCAT(CONCAT(CONCAT(CONCAT('From: ',airports.airport_code),' '),'To: ') , to_airports.airport_code) AS 
                serviceInfo,passenger_name AS PassengerName,DATE_FORMAT(extended_Date,'%d-%b-%Y') AS formatedDate,
                datechange.datetime as datetime, datechange.sale_amount AS salePrice From datechange INNER JOIN ticket ON 
                ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN 
                airports AS to_airports ON to_airports.airport_id=ticket.to_id INNER JOIN invoicedetails ON 
                invoicedetails.transactionID = datechange.change_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Date Extension' AND ticket.status = 1
                UNION ALL
                SELECT 'Loan' AS transactionType,remarks AS serviceInfo,'' AS PassengerName,
                DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS formatedDate, loan.datetime AS datetime,amount AS salePrice From loan 
                INNER JOIN invoicedetails ON invoicedetails.transactionID = loan.loan_id WHERE invoicedetails.invoiceID = :id 
                AND invoicedetails.transactionType = 'Loan'
                UNION ALL
                SELECT 'Refund' AS transactionType,
                CONCAT(CONCAT(CONCAT(CONCAT('From: ',airports.airport_code),' '),'To: ') , to_airports.airport_code) AS 
                serviceInfo,passenger_name AS PassengerName,DATE_FORMAT(extended_Date,'%d-%b-%Y') AS formatedDate,
                datechange.datetime as datetime, datechange.sale_amount AS salePrice From datechange INNER JOIN ticket ON 
                ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN 
                airports AS to_airports ON to_airports.airport_id=ticket.to_id INNER JOIN invoicedetails ON 
                invoicedetails.transactionID = datechange.change_id WHERE invoicedetails.invoiceID = :id AND 
                invoicedetails.transactionType = 'Refund' AND ticket.status = 2) AS baseTable ORDER BY datetime ASC
                 ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['ID']);
                 // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                echo json_encode($records);
    }else if(isset($_POST['SaveReceiptInfo'])){
        try{
            $pdo->beginTransaction();
            if($_POST['ID'] == "" || $_POST['ID'] == "undefined" || $_POST['ID'] == 0 || $_POST['ID'] ==null){
                $pdo->rollback();
                echo "Something went wrong! Please refresh the page and then continue 1. ";
            }
            $receiptArr = json_decode($_POST['ReceiptArr'],true);
            for($i = 0; $i<count($receiptArr); $i++){
                if($receiptArr[$i]['id'] == "" || $receiptArr[$i]['id'] == 0 || $receiptArr[$i]['id'] == "undefined" || $receiptArr[$i]['id'] == null){
                    $pdo->rollback();
                    echo "Something went wrong! Please refresh the page and then continue 2. ";
                }
                if($receiptArr[$i]['type'] == ""  || $receiptArr[$i]['type'] == "undefined" || $receiptArr[$i]['type'] == null){
                    $pdo->rollback();
                    echo "Something went wrong! Please refresh the page and then continue 3. ";
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
                $InsertReceiptSql = "INSERT INTO `invoice`(`customerID`, `invoiceNumber`) VALUES (:customerID,:invoiceNumber)";
                $InsertReceiptStmt = $pdo->prepare($InsertReceiptSql);
                $InsertReceiptStmt->bindParam(':customerID', $_POST['ID']);
                $InsertReceiptStmt->bindParam(':invoiceNumber', $invoiceNumber);
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
    }else if(isset($_POST['GetTotal'])){
        try{
                $pdo->beginTransaction();
                $checkCustomerRecordSQL = "SELECT `customer_id`,referenceDate,referenceID FROM `statement` WHERE  customer_id = :id
                AND referenceCurrencyID = :CurID ORDER BY DATE(statement.Entrydate), statementID DESC LIMIT 1";
                $checkCustomerRecorStmt = $pdo->prepare($checkCustomerRecordSQL);
                $checkCustomerRecorStmt->bindParam(':id', $_POST['CustomerID']);
                $checkCustomerRecorStmt->bindParam(':CurID', $_POST['CurID']);
                $checkCustomerRecorStmt->execute();
                $checkCustomerRecorStmt = $checkCustomerRecorStmt->fetchAll(\PDO::FETCH_ASSOC);
            // store total to track the records
            if (empty($checkCustomerRecorStmt)) {
                $total = 0;
                $recordsToDisplayArr = array();
                $sql = "SELECT * FROM (SELECT ticket.ticket AS refID, 'Ticket' AS TRANSACTION_Type,ticket.datetime AS datetime,
                sale AS Debit, 0 AS Credit FROM ticket WHERE ticket.customer_id=:id AND ticket.currencyID = :CurID 
                UNION ALL
                SELECT visa.visa_id AS refID,'Visa'  AS TRANSACTION_Type,visa.datetime AS datetime, sale AS Debit, 0 AS Credit FROM
                visa WHERE visa.customer_id=:id AND visa.saleCurrencyID = :CurID 
                UNION ALL 
                SELECT visaextracharges.visaExtraChargesID AS refID, CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN
                visaextracharges.typeID = 2 THEN 'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS 
                TRANSACTION_Type,visaextracharges.datetime AS datetime,visaextracharges.salePrice AS Debit, 0 AS Credit FROM 
                visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id WHERE visa.customer_id= :id AND 
                visaextracharges.saleCurrencyID = :CurID
                UNION ALL
                SELECT residence.residenceID AS refID,'Residence' AS TRANSACTION_Type,residence.datetime AS datetime, sale_price AS 
                Debit, 0 AS Credit FROM residence WHERE residence.customer_id=:id AND residence.saleCurID = :CurID 
                UNION ALL
                SELECT residenceFineID AS refID, 'Residence Fine' AS transactionType,residencefine.datetime AS datetime,
                residencefine.fineAmount AS Debit, 0 AS Credit FROM residencefine INNER JOIN residence ON residence.residenceID = 
                residencefine.residenceID WHERE residence.customer_id = :id AND fineCurrencyID = :CurID
                UNION ALL
                SELECT servicedetails.serviceDetailsID AS refID, serviceName AS TRANSACTION_Type,service_date AS datetime, salePrice 
                AS Debit, 0 AS Credit FROM servicedetails INNER JOIN service ON service.serviceID = servicedetails.serviceID
                WHERE servicedetails.customer_id=:id AND servicedetails.saleCurrencyID = :CurID 
                UNION ALL
                SELECT customer_payments.pay_id AS refID, 'Payment' AS TRANSACTION_Type,customer_payments.datetime AS datetime,
                0 AS Debit, IFNULL(payment_amount,0) AS Credit from customer_payments WHERE customer_payments.customer_id=:id AND 
                customer_payments.currencyID = :CurID
                UNION ALL 
                SELECT hotel.hotel_id AS refID, 'Hotel Reservation' AS TRANSACTION_Type,hotel.datetime AS datetime, sale_price AS 
                Debit, 0 AS Credit from hotel WHERE hotel.customer_id=:id AND hotel.saleCurrencyID = :CurID
                UNION ALL 
                SELECT  car_rental.car_id AS refID,'Car Reservation' AS TRANSACTION_Type, car_rental.datetime as datetime,sale_price 
                AS Debit, 0 AS Credit from car_rental WHERE car_rental.customer_id=:id AND car_rental.saleCurrencyID = :CurID 
                UNION ALL 
                SELECT  datechange.change_id AS refID,'Date Extension' AS TRANSACTION_Type,datechange.datetime as datetime,
                datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id
                WHERE ticket.customer_id=:id AND ticketStatus = 1 AND datechange.saleCurrencyID = :CurID
                UNION ALL 
                SELECT loan.loan_id AS refID, 'Loan' AS TRANSACTION_Type,loan.datetime AS datetime,amount AS Debit, 0 AS Credit From
                loan WHERE loan.customer_id=:id AND loan.currencyID = :CurID
                UNION ALL 
                SELECT datechange.change_id AS refID, 'Refund' AS TRANSACTION_Type,datechange.datetime AS datetime, 0 AS Debit,
                datechange.sale_amount AS Credit FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE
                ticket.customer_id=:id AND ticketStatus = 2 AND datechange.saleCurrencyID = :CurID) baseTable ORDER BY datetime ASC ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['CustomerID']);
                $stmt->bindParam(':CurID', $_POST['CurID']);
                // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($records as $record) {
                    $total = $total + $record['Debit'] - $record['Credit'];
                    if($total == 0){
                        // Set query
                        $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id = :cusID AND 
                        referenceID = :refID AND statementFor = :stateFor";
                        $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                        $CheckRecordStmt->bindParam(':cusID', $_POST['CustomerID']);
                        $CheckRecordStmt->bindParam(':refID', $record['refID']);
                        $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                        $CheckRecordStmt->execute();
                        // Fetch single row
                        $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                        if (!$row) {
                            // create prepared statement
                            $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                            `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                            :referenceCurrencyID)";
                            $stmt = $pdo->prepare($sql);
                            // bind parameters to statement
                            $stmt->bindParam(':customer_id',$_POST['CustomerID']);
                            $stmt->bindParam(':referenceID', $record['refID']);
                            $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                            $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                            $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                            // execute the prepared statement
                            $stmt->execute();
                        }   
                    }
                }
                $pdo->commit(); 
                echo json_encode($total);
            }else{
                // if there is record then fetch the record from that date on
                $total = 0;
                $recordsToDisplayArr = array();
                $sql = "SELECT * FROM (SELECT ticket.ticket AS refID, 'Ticket' AS TRANSACTION_Type,ticket.datetime AS datetime,
                sale AS Debit, 0 AS Credit FROM ticket WHERE ticket.customer_id=:id AND ticket.currencyID = :CurID AND 
                DATE(ticket.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT visa.visa_id AS refID,'Visa'  AS TRANSACTION_Type,visa.datetime AS datetime, sale AS Debit, 0 AS Credit FROM
                visa WHERE visa.customer_id=:id AND visa.saleCurrencyID = :CurID AND DATE(visa.datetime) BETWEEN :from_date AND
                CURDATE()
                UNION ALL 
                SELECT visaextracharges.visaExtraChargesID AS refID, CASE WHEN visaextracharges.typeID = 1 THEN 'Visa Fine' WHEN
                visaextracharges.typeID = 2 THEN 'Escape Report' WHEN visaextracharges.typeID = 3 THEN 'Escape Removal' END AS 
                TRANSACTION_Type,visaextracharges.datetime AS datetime,visaextracharges.salePrice AS Debit, 0 AS Credit FROM 
                visaextracharges INNER JOIN visa ON visa.visa_id = visaextracharges.visa_id WHERE visa.customer_id= :id AND 
                visaextracharges.saleCurrencyID = :CurID AND DATE(visaextracharges.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT residence.residenceID AS refID,'Residence' AS TRANSACTION_Type,residence.datetime AS datetime, sale_price AS 
                Debit, 0 AS Credit FROM residence WHERE residence.customer_id=:id AND residence.saleCurID = :CurID AND 
                DATE(residence.datetime) BETWEEN :from_date AND CURDATE() 
                UNION ALL
                SELECT residenceFineID AS refID, 'Residence Fine' AS transactionType,residencefine.datetime AS datetime,
                residencefine.fineAmount AS Debit, 0 AS Credit FROM residencefine INNER JOIN residence ON residence.residenceID = 
                residencefine.residenceID WHERE residence.customer_id = :id AND fineCurrencyID = :CurID AND 
                DATE(residencefine.datetime) BETWEEN :from_date AND CURDATE() 
                UNION ALL
                SELECT servicedetails.serviceDetailsID AS refID, serviceName AS TRANSACTION_Type,service_date AS datetime, salePrice 
                AS Debit, 0 AS Credit FROM servicedetails INNER JOIN service ON service.serviceID = servicedetails.serviceID
                WHERE servicedetails.customer_id=:id AND servicedetails.saleCurrencyID = :CurID AND DATE(servicedetails.service_date) 
                BETWEEN :from_date AND CURDATE()
                UNION ALL
                SELECT customer_payments.pay_id AS refID, 'Payment' AS TRANSACTION_Type,customer_payments.datetime AS datetime,
                0 AS Debit, IFNULL(payment_amount,0) AS Credit from customer_payments WHERE customer_payments.customer_id=:id AND 
                customer_payments.currencyID = :CurID AND DATE(customer_payments.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT hotel.hotel_id AS refID, 'Hotel Reservation' AS TRANSACTION_Type,hotel.datetime AS datetime, sale_price AS 
                Debit, 0 AS Credit from hotel WHERE hotel.customer_id=:id AND hotel.saleCurrencyID = :CurID AND DATE(hotel.datetime)
                BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT  car_rental.car_id AS refID,'Car Reservation' AS TRANSACTION_Type, car_rental.datetime as datetime,sale_price 
                AS Debit, 0 AS Credit from car_rental WHERE car_rental.customer_id=:id AND car_rental.saleCurrencyID = :CurID
                AND DATE(car_rental.datetime) BETWEEN :from_date AND CURDATE() 
                UNION ALL 
                SELECT  datechange.change_id AS refID,'Date Extension' AS TRANSACTION_Type,datechange.datetime as datetime,
                datechange.sale_amount AS Debit, 0 AS Credit From datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id
                WHERE ticket.customer_id=:id AND ticketStatus = 1 AND datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime)
                BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT loan.loan_id AS refID, 'Loan' AS TRANSACTION_Type,loan.datetime AS datetime,amount AS Debit, 0 AS Credit From
                loan WHERE loan.customer_id=:id AND loan.currencyID = :CurID AND DATE(loan.datetime) BETWEEN :from_date AND CURDATE()
                UNION ALL 
                SELECT datechange.change_id AS refID, 'Refund' AS TRANSACTION_Type,datechange.datetime AS datetime, 0 AS Debit,
                datechange.sale_amount AS Credit FROM datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id WHERE
                ticket.customer_id=:id AND ticketStatus = 2 AND datechange.saleCurrencyID = :CurID AND DATE(datechange.datetime)
                BETWEEN :from_date AND CURDATE()) baseTable ORDER BY datetime ASC ";
                // prepare statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':id', $_POST['CustomerID']);
                $stmt->bindParam(':CurID', $_POST['CurID']);
                $stmt->bindParam(':from_date', $checkCustomerRecorStmt[0]['referenceDate']);
                // execute the prepared statement
                $stmt->execute();
                $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $flagDecision = 0;
            
                foreach ($records as $record) {
                    if(intval($record['refID']) !=  intval($checkCustomerRecorStmt[0]['referenceID'])){
                        if($flagDecision == 1){
                            $total = $total + intval($record['Debit']) - intval($record['Credit']);
                                if($total == 0){
                                    // Set query
                                    $CheckRecordSql = "SELECT customer_id, referenceID, statementFor FROM statement WHERE customer_id
                                    = :cusID AND referenceID = :refID AND statementFor = :stateFor";
                                    $CheckRecordStmt = $pdo->prepare($CheckRecordSql);
                                    $CheckRecordStmt->bindParam(':cusID', $_POST['CustomerID']);
                                    $CheckRecordStmt->bindParam(':refID', $record['refID']);
                                    $CheckRecordStmt->bindParam(':stateFor', $record['TRANSACTION_Type']);
                                    $CheckRecordStmt->execute();
                                    // Fetch single row
                                    $getRecordedResult = $CheckRecordStmt->fetch(PDO::FETCH_ASSOC);
                                    if (!$row) {
                                            // create prepared statement
                                        $sql = "INSERT INTO `statement`(`customer_id`, `referenceID`, `referenceDate`, `statementFor`,
                                        `referenceCurrencyID`) VALUES (:customer_id, :referenceID,:referenceDate,:statementFor,
                                        :referenceCurrencyID)";
                                        $stmt = $pdo->prepare($sql);
                                        // bind parameters to statement
                                        $stmt->bindParam(':customer_id',$_POST['CustomerID']);
                                        $stmt->bindParam(':referenceID', $record['refID']);
                                        $stmt->bindParam(':referenceDate', $record['nonFormatedDate']);
                                        $stmt->bindParam(':statementFor',$record['TRANSACTION_Type']);
                                        $stmt->bindParam(':referenceCurrencyID', $_POST['CurID']);
                                        // execute the prepared statement
                                        $stmt->execute();
                                    }
                            }
                    }
                    
                        
                    }else{
                        $flagDecision =1;
                    }
                }
                $pdo->commit(); 
                echo json_encode($total);
            }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>