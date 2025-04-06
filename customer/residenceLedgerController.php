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
                // SQL Query
                $sql = "SELECT residence.passenger_name as main_passenger,IFNULL((SELECT IFNULL(company_name,'') FROM company 
                        WHERE company.company_id = residence.company),'') AS company_name, DATE(residence.datetime) AS dt,  
                        IFNULL(SUM(residence.sale_price),0) AS sale_price, (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM 
                        residencefine INNER JOIN residence ON residence.residenceID = residencefine.residenceID WHERE 
                        residence.customer_id = :id AND residencefine.fineCurrencyID = :CurID AND residence.passenger_name = 
                        main_passenger AND residence.islocked !=1) AS fine, (SELECT 
                        IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN residence ON 
                        residence.residenceID = customer_payments.PaymentFor WHERE customer_payments.customer_id = :id AND 
                        customer_payments.currencyID = :CurID AND residence.passenger_name = main_passenger AND residence.islocked
                        != 1) AS residencePayment, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments 
                        INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment 
                        INNER JOIN residence ON residence.residenceID = residencefine.residenceID WHERE 
                        customer_payments.customer_id = :id AND customer_payments.currencyID = :CurID AND residence.passenger_name
                        = main_passenger AND residence.islocked !=1) AS finePayment  FROM residence  WHERE residence.customer_id 
                        = :id AND residence.saleCurID = :CurID AND residence.islocked != 1 GROUP BY residence.passenger_name, 
                        residence.VisaType ORDER BY residence.residenceID ASC;";
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