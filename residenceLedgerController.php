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
    }else if(isset($_POST['GetResidenceReport'])){
                // SQL Query - Include locked records with fines
                $sql = "SELECT
                    main_passenger,
                    company_name,
                    dt,
                    sale_price,
                    fine,
                    residencePayment,
                    finePayment
                FROM (
                    -- First query gets unlocked residence records with corresponding currency
                    SELECT 
                        r.passenger_name as main_passenger,
                        IFNULL((SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = r.company),'') AS company_name, 
                        DATE(r.datetime) AS dt,  
                        r.sale_price AS sale_price, 
                        (SELECT IFNULL(SUM(rf.fineAmount),0) FROM residencefine rf 
                            WHERE rf.residenceID = r.residenceID AND rf.fineCurrencyID = :CurID) AS fine, 
                        (SELECT IFNULL(SUM(cp.payment_amount),0) FROM customer_payments cp 
                            WHERE cp.PaymentFor = r.residenceID AND cp.customer_id = :id AND cp.currencyID = :CurID) AS residencePayment, 
                        (SELECT IFNULL(SUM(cp.payment_amount),0) FROM customer_payments cp 
                            JOIN residencefine rf ON rf.residenceFineID = cp.residenceFinePayment
                            WHERE rf.residenceID = r.residenceID AND cp.customer_id = :id AND cp.currencyID = :CurID) AS finePayment
                    FROM residence r
                    WHERE r.customer_id = :id AND r.islocked != 1 
                    AND r.saleCurID = :CurID
                    
                    UNION
                    
                    -- Second query gets unlocked residences with fines in the selected currency
                    SELECT 
                        r.passenger_name as main_passenger,
                        IFNULL((SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = r.company),'') AS company_name, 
                        DATE(r.datetime) AS dt,  
                        0 AS sale_price, -- No sale price for residence with different currency
                        (SELECT IFNULL(SUM(rf.fineAmount),0) FROM residencefine rf 
                            WHERE rf.residenceID = r.residenceID AND rf.fineCurrencyID = :CurID) AS fine, 
                        0 AS residencePayment, -- No residence payments for this currency
                        (SELECT IFNULL(SUM(cp.payment_amount),0) FROM customer_payments cp 
                            JOIN residencefine rf ON rf.residenceFineID = cp.residenceFinePayment
                            WHERE rf.residenceID = r.residenceID AND cp.customer_id = :id AND cp.currencyID = :CurID) AS finePayment
                    FROM residence r
                    JOIN residencefine rf ON r.residenceID = rf.residenceID
                    WHERE r.customer_id = :id AND r.islocked != 1
                    AND r.saleCurID != :CurID -- Different currency than selected
                    AND rf.fineCurrencyID = :CurID -- But fine in selected currency
                    
                    UNION
                    
                    -- Third query gets LOCKED residences with fines in any currency
                    SELECT 
                        r.passenger_name as main_passenger,
                        IFNULL((SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = r.company),'') AS company_name, 
                        DATE(r.datetime) AS dt,  
                        0 AS sale_price, -- No sale price for locked residences
                        (SELECT IFNULL(SUM(rf.fineAmount),0) FROM residencefine rf 
                            WHERE rf.residenceID = r.residenceID AND rf.fineCurrencyID = :CurID) AS fine, 
                        0 AS residencePayment, -- No residence payments for locked records
                        (SELECT IFNULL(SUM(cp.payment_amount),0) FROM customer_payments cp 
                            JOIN residencefine rf ON rf.residenceFineID = cp.residenceFinePayment
                            WHERE rf.residenceID = r.residenceID AND cp.customer_id = :id AND cp.currencyID = :CurID) AS finePayment
                    FROM residence r
                    JOIN residencefine rf ON r.residenceID = rf.residenceID 
                    WHERE r.customer_id = :id 
                    AND r.islocked = 1 -- Specifically LOCKED records
                    AND rf.fineCurrencyID = :CurID -- With fines in selected currency
                ) AS combined_results
                -- Ensure we only show records with either sales or fines
                WHERE fine > 0 OR sale_price > 0
                ORDER BY dt ASC;";
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