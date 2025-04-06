<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(!isset($_SESSION['user_id']))
    {
	  header('location:login.php');
    }
    $sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $select = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $select[0]['select'];
    if($select == 0){
        echo "<script>window.location.href='pageNotFound.php'</script>"; 
    }

    if(isset($_POST['GetPendingResidence'])){
        if($_POST['Search'] != ''){
            $search = '%'.  str_replace(' ', '',strtolower($_POST['Search'])) . '%';
            $selectQuery = $pdo->prepare("SELECT * FROM(SELECT residenceID AS main_residenceID,customer_name, passenger_name, deleted,
            airports.countryName,country_names, sale_price, currency.currencyName, completedStep,IFNULL((SELECT 
            IFNULL(company_name,'') FROM company WHERE company.company_id = residence.company ),'Not Updated Yet') AS 
            company_name,IFNULL((SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = 
            residence.company ),'Not Updated Yet') AS company_number, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
            FROM customer_payments WHERE customer_payments.PaymentFor = main_residenceID ) AS total,(SELECT 
            IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE residencefine.residenceID = main_residenceID) AS 
            total_Fine, (SELECT DISTINCT currency.currencyName FROM currency INNER JOIN residencefine ON 
            residencefine.fineCurrencyID = currency.currencyID  WHERE residencefine.residenceID = main_residenceID) AS 
            residenceFineCurrency, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE 
            customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM residencefine WHERE 
            residencefine.residenceID = main_residenceID)) AS totalFinePaid FROM `residence` INNER JOIN customer ON 
            customer.customer_id = residence.customer_id INNER JOIN airports ON airports.airport_id = residence.Nationality 
            INNER JOIN country_name ON country_name.country_id = residence.VisaType INNER JOIN currency ON currency.currencyID
            = residence.saleCurID  WHERE completedStep != 10) AS BaseTable  WHERE REPLACE(LOWER(customer_name),' ','') LIKE 
            :search OR REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search 
            OR REPLACE(LOWER(company_number),' ','') LIKE :search and `deleted` = 0
            ORDER BY main_residenceID DESC; ");
            
             $selectQuery->bindParam(':search', $search);
        }else{
            $selectQuery = $pdo->prepare("SELECT residenceID AS main_residenceID, customer_name, passenger_name, deleted,
            airports.countryName, country_names, sale_price, currency.currencyName, completedStep,IFNULL((SELECT 
            IFNULL(company_name,'') FROM company WHERE company.company_id = residence.company ),'Not Updated Yet') AS 
            company_name,IFNULL((SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = residence.company ),
            'Not Updated Yet') AS company_number, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM  customer_payments
            WHERE customer_payments.PaymentFor = main_residenceID) AS total, (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
            FROM residencefine WHERE residencefine.residenceID = main_residenceID) AS total_Fine, (SELECT DISTINCT 
            currency.currencyName FROM currency INNER JOIN residencefine ON residencefine.fineCurrencyID = currency.currencyID
            WHERE residencefine.residenceID = main_residenceID) AS residenceFineCurrency, (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.residenceFinePayment 
            IN (SELECT residencefine.residenceFineID FROM residencefine WHERE residencefine.residenceID = main_residenceID)) AS
            totalFinePaid FROM `residence` INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN 
            airports ON airports.airport_id = residence.Nationality INNER JOIN country_name ON country_name.country_id = 
            residence.VisaType INNER JOIN currency ON currency.currencyID = residence.saleCurID WHERE completedStep != 10 AND `deleted` = 0
            ORDER BY main_residenceID DESC");
        }

       
        $selectQuery->execute();



        /* Fetch all of the remaining rows in the result set */
        $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($rpt);
    }else if(isset($_POST['Select_Accounts'])){
        $selectQuery = $pdo->prepare("SELECT `account_ID`, `account_Name` FROM `accounts`ORDER BY account_Name ASC ");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['GetPendingResidencePayment'])){
        $selectQuery = $pdo->prepare("SELECT sale_price - IFNULL((SELECT SUM(customer_payments.payment_amount) FROM 
        customer_payments WHERE customer_payments.PaymentFor = :resID),0) AS remaining, currency.currencyName  FROM `residence`
        INNER JOIN currency ON currency.currencyID = residence.saleCurID WHERE residenceID = :resID");
        $selectQuery->bindParam(':resID', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Insert_Payment'])){
        try {
            // First of all, let's begin a transaction
                $pdo->beginTransaction();
                $decisionFlag = $pdo->prepare("SELECT customer_id,saleCurID FROM `residence` WHERE residenceID =:residenceID");
                $decisionFlag->bindParam(':residenceID', $_POST['ResID']);
                $decisionFlag->execute();
                /* Fetch all of the remaining rows in the result set */
                $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
                if(($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['saleCurID'] == '' || $rpt[0]['saleCurID'] == null)){
                    
                    $pdo->rollback();
                    echo "Something went wrong";
                    exit();
                }else{
                    $getAccCur = $pdo->prepare("SELECT account_Name,curID FROM `accounts` WHERE account_ID = :accountID");
                    $getAccCur->bindParam(':accountID', $_POST['Account_ID']);
                    $getAccCur->execute();
                    /* Fetch all of the remaining rows in the result set */
                    $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
                    if($accCur[0]['account_Name'] == "Cash"){
                        // create prepared statement
                        $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        PaymentFor) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,:PaymentFor)";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':customer_id',$rpt[0]['customer_id']);
                        $stmt->bindParam(':payment_amount', $_POST['Payment']);
                        $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                        $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                        $stmt->bindParam(':accountID', $_POST['Account_ID']);
                        $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                    }else if($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] ==  $rpt[0]['saleCurID']){
                        // create prepared statement
                        $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        PaymentFor) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,:PaymentFor)";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':customer_id',$rpt[0]['customer_id']);
                        $stmt->bindParam(':payment_amount', $_POST['Payment']);
                        $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                        $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                        $stmt->bindParam(':accountID', $_POST['Account_ID']);
                        $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                    }else{
                        $pdo->rollback();
                        echo "Currencies does not match! Please select account that its currency match with the sale price currency";
                        exit();
                    }
                    
                    // execute the prepared statement
                    $stmt->execute();
                    // create prepared statement
                    $checkTotal = "SELECT (IFNULL(SUM(residence.sale_price),0) + (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
                    FROM residencefine WHERE residencefine.residenceID = :resID)) - ((SELECT 
                    IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
                    :resID) + (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN 
                    residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment WHERE 
                    residencefine.residenceID = :resID)) AS total FROM residence WHERE residence.residenceID = :resID";
                    $checkTotalStmt = $pdo->prepare($checkTotal);
                    $checkTotalStmt->bindParam(':resID', $_POST['ResID']);
                    $checkTotalStmt->execute();
                    $total = $checkTotalStmt->fetchAll(\PDO::FETCH_ASSOC);
                    if($total[0]['total'] == 0){
                        $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                        $updateLockTranStmt = $pdo->prepare($updateLockTran);
                        $updateLockTranStmt->bindParam(':resID', $_POST['ResID']);
                        $updateLockTranStmt->execute();
                    }
                    $pdo->commit(); 
                    echo "Success";
                }
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
         
    }else if(isset($_POST['GetPendingPayForResidence'])){
        if($_POST['Search'] != ''){
            $page = (int)$_POST['Page'];
            $page = $page - 1;
            $offSet = $page  * 10 ;
            $search = '%'.  str_replace(' ', '',strtolower($_POST['Search'])) . '%'; 
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT residenceID AS main_residenceID, customer_name,passenger_name, 
            airports.countryName,country_names,sale_price,currency.currencyName,completedStep,company_name ,company_number,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            main_residenceID) AS total,(SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE 
            residencefine.residenceID = main_residenceID) AS total_Fine, (SELECT DISTINCT currency.currencyName FROM currency 
            INNER JOIN residencefine ON residencefine.fineCurrencyID = currency.currencyID  WHERE residencefine.residenceID = 
            main_residenceID) AS residenceFineCurrency, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = main_residenceID)) AS totalFinePaid,(SELECT
            COUNT(residence.residenceID) FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id 
            INNER JOIN company ON company.company_id = residence.company WHERE residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search) AND 
            ((residence.sale_price - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE 
            customer_payments.PaymentFor = residence.residenceID AND residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search))) !=0 OR 
            ((SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE  residencefine.residenceID = 
            residence.residenceID AND residence.completedStep = 10 AND (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR 
            REPLACE(LOWER(customer_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search OR  
            REPLACE(LOWER(company_number),' ','') LIKE :search)) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = residence.residenceID AND residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search)))) != 0)) AS
            totalRow FROM `residence` INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN airports ON 
            airports.airport_id = residence.Nationality INNER JOIN country_name ON country_name.country_id = residence.VisaType 
            INNER JOIN company ON company.company_id = residence.company INNER JOIN currency ON currency.currencyID = 
            residence.saleCurID WHERE completedStep = 10 AND (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR 
            REPLACE(LOWER(customer_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_number),' ','') LIKE :search)) AS baseTable WHERE sale_price - total != 0 OR total_Fine - 
            totalFinePaid !=0  ORDER BY main_residenceID DESC LIMIT 10 OFFSET :OffsetNumber;");
             $selectQuery->bindParam(':search', $search);
             $selectQuery->bindParam(":OffsetNumber", $offSet, PDO::PARAM_INT);
        }else{
            $page = (int)$_POST['Page'];
            $page = $page - 1;
            $offSet = $page  * 10 ;
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT residenceID AS main_residenceID, customer_name,passenger_name , 
            airports.countryName, country_names,sale_price, currency.currencyName, completedStep,(SELECT IFNULL(company_name,'')
            FROM company WHERE company.company_id = residence.company ) AS company_name,(SELECT IFNULL(company_number,'') FROM 
            company WHERE company.company_id = residence.company ) AS company_number,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            main_residenceID ) AS total,(SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE 
            residencefine.residenceID = main_residenceID) AS total_Fine, (SELECT DISTINCT currency.currencyName FROM currency 
            INNER JOIN residencefine ON residencefine.fineCurrencyID = currency.currencyID  WHERE residencefine.residenceID = 
            main_residenceID) AS residenceFineCurrency, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = main_residenceID)) AS totalFinePaid, (SELECT COUNT(DISTINCT 
            residence.residenceID) FROM residence WHERE residence.completedStep = 10 AND ((residence.sale_price - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            residence.residenceID AND residence.completedStep = 10)) !=0  OR ((SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM 
            residencefine WHERE residencefine.residenceID = residence.residenceID AND residence.completedStep = 10) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.residenceFinePayment
            IN (SELECT residencefine.residenceFineID FROM residencefine WHERE residencefine.residenceID = residence.residenceID
            AND residence.completedStep = 10)))  !=0 )) AS totalRow FROM `residence` INNER JOIN customer ON customer.customer_id 
            = residence.customer_id INNER JOIN airports ON airports.airport_id = residence.Nationality INNER JOIN country_name
            ON country_name.country_id = residence.VisaType INNER JOIN currency ON currency.currencyID = residence.saleCurID 
            WHERE completedStep = 10 ) AS baseTable WHERE sale_price - total != 0 OR total_Fine - totalFinePaid !=0  ORDER BY 
            main_residenceID DESC LIMIT 10 OFFSET :OffsetNumber;");
            $selectQuery->bindParam(":OffsetNumber", $offSet, PDO::PARAM_INT);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($rpt);
    }else if(isset($_POST['GetCompletedResidence'])){
        if($_POST['Search'] != ''){
            $page = (int)$_POST['Page'];
            $page = $page - 1;
            $offSet = $page  * 10 ;
            $search = '%'.  str_replace(' ', '',strtolower($_POST['Search'])) . '%'; 
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT residenceID AS main_residenceID, customer_name,passenger_name, 
            airports.countryName,country_names,sale_price,currency.currencyName,completedStep,company_name ,company_number,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            main_residenceID) AS total,(SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE 
            residencefine.residenceID = main_residenceID) AS total_Fine, (SELECT DISTINCT currency.currencyName FROM currency 
            INNER JOIN residencefine ON residencefine.fineCurrencyID = currency.currencyID  WHERE residencefine.residenceID = 
            main_residenceID) AS residenceFineCurrency, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = main_residenceID)) AS totalFinePaid,(SELECT COUNT(DISTINCT 
            residence.residenceID) FROM residence INNER JOIN customer ON customer.customer_id = residence.customer_id 
            INNER JOIN company ON company.company_id = residence.company WHERE residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search) AND 
            residence.sale_price - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE 
            customer_payments.PaymentFor = residence.residenceID AND residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search)) = 0 AND 
            (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE residencefine.residenceID = 
            residence.residenceID AND residence.completedStep = 10 AND (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR 
            REPLACE(LOWER(customer_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search OR  
            REPLACE(LOWER(company_number),' ','') LIKE :search)) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = residence.residenceID AND residence.completedStep = 10 AND 
            (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR REPLACE(LOWER(customer_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search))) =0) AS 
            totalRow FROM `residence` INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN airports ON
            airports.airport_id = residence.Nationality INNER JOIN country_name ON country_name.country_id = residence.VisaType 
            INNER JOIN company ON company.company_id = residence.company INNER JOIN currency ON currency.currencyID = 
            residence.saleCurID WHERE completedStep = 10 AND (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR 
            REPLACE(LOWER(customer_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_number),' ','') LIKE :search)) AS baseTable WHERE sale_price - total = 0 AND total_Fine - 
            totalFinePaid = 0  ORDER BY main_residenceID DESC LIMIT 10 OFFSET :OffsetNumber;");
             $selectQuery->bindParam(':search', $search);
             $selectQuery->bindParam(":OffsetNumber", $offSet, PDO::PARAM_INT);
        }else{
            $page = (int)$_POST['Page'];
            $page = $page - 1;
            $offSet = $page  * 10 ;
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT residenceID AS main_residenceID, customer_name,passenger_name , 
            airports.countryName, country_names,sale_price, currency.currencyName, completedStep,(SELECT IFNULL(company_name,'')
            FROM company WHERE company.company_id = residence.company ) AS company_name,(SELECT IFNULL(company_number,'') FROM 
            company WHERE company.company_id = residence.company ) AS company_number,(SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            main_residenceID ) AS total,(SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine WHERE 
            residencefine.residenceID = main_residenceID) AS total_Fine, (SELECT DISTINCT currency.currencyName FROM currency 
            INNER JOIN residencefine ON residencefine.fineCurrencyID = currency.currencyID  WHERE residencefine.residenceID = 
            main_residenceID) AS residenceFineCurrency, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments WHERE customer_payments.residenceFinePayment IN (SELECT residencefine.residenceFineID FROM 
            residencefine WHERE residencefine.residenceID = main_residenceID)) AS totalFinePaid, (SELECT COUNT(DISTINCT
            residence.residenceID) FROM residence WHERE residence.completedStep = 10 AND residence.sale_price - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
            residence.residenceID AND residence.completedStep = 10) =0   AND (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM 
            residencefine WHERE residencefine.residenceID = residence.residenceID AND residence.completedStep = 10) - (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.residenceFinePayment
            IN (SELECT residencefine.residenceFineID FROM residencefine WHERE residencefine.residenceID = residence.residenceID
            AND residence.completedStep = 10))=0) AS totalRow FROM `residence` INNER JOIN customer ON customer.customer_id 
            = residence.customer_id INNER JOIN airports ON airports.airport_id = residence.Nationality INNER JOIN country_name
            ON country_name.country_id = residence.VisaType INNER JOIN currency ON currency.currencyID = residence.saleCurID 
            WHERE completedStep = 10 ) AS baseTable WHERE sale_price - total = 0 AND total_Fine - totalFinePaid =0  ORDER BY 
            main_residenceID DESC LIMIT 10 OFFSET :OffsetNumber; ");
            $selectQuery->bindParam(":OffsetNumber", $offSet, PDO::PARAM_INT);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($rpt);
    }else if(isset($_POST['SaveResidenceFine'])){
        try {
            // First of all, let's begin a transaction
                $pdo->beginTransaction();
                $decisionFlag = $pdo->prepare("SELECT curID, account_Name FROM `accounts` WHERE account_ID = :AccID");
                $decisionFlag->bindParam(':AccID', $_POST['ChargeAccount']);
                $decisionFlag->execute();
                /* Fetch all of the remaining rows in the result set */
                $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
                if($rpt[0]['account_Name'] == "Cash"){
                    // create prepared statement
                    $sql = "INSERT INTO `residencefine`(`residenceID`,`fineAmount`, `fineCurrencyID`, `accountID`, `imposedBy`)
                    VALUES(:residenceID,:fineAmount,:fineCurrencyID,:accountID,:imposedBy)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':residenceID',$_POST['RID']);
                    $stmt->bindParam(':fineAmount', $_POST['Fine_Amount']);
                    $stmt->bindParam(':fineCurrencyID', $_POST['Fine_currency_type']);
                    $stmt->bindParam(':accountID', $_POST['ChargeAccount']);
                    $stmt->bindParam(':imposedBy',$_SESSION['user_id']);
                }else{
                    // create prepared statement
                    $sql = "INSERT INTO `residencefine`(`residenceID`,`fineAmount`, `fineCurrencyID`, `accountID`, `imposedBy`)
                    VALUES(:residenceID,:fineAmount,:fineCurrencyID,:accountID,:imposedBy)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':residenceID',$_POST['RID']);
                    $stmt->bindParam(':fineAmount', $_POST['Fine_Amount']);
                    $stmt->bindParam(':fineCurrencyID', $rpt[0]['curID']);
                    $stmt->bindParam(':accountID', $_POST['ChargeAccount']);
                    $stmt->bindParam(':imposedBy',$_SESSION['user_id']);
                }
                $stmt->execute();
                $pdo->commit(); 
                echo "Success";
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
         
    }else if(isset($_POST['ViewFine'])){
        $selectQuery = $pdo->prepare("SELECT `residenceFineID`, residenceID, DATE_FORMAT(DATE(datetime),'%d-%b-%Y') AS 
        residenceFineDate , `fineAmount`, currencyName, account_Name, staff_name, `docName`, `originalName` FROM `residencefine`
        INNER JOIN currency ON currency.currencyID = residencefine.fineCurrencyID INNER JOIN accounts ON accounts.account_ID =
        residencefine.accountID INNER JOIN staff ON staff.staff_id = residencefine.imposedBy WHERE residencefine.residenceID =
        :resID;");
        $selectQuery->bindParam(':resID', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Upload_ExraChargeDoc'])){
        try{
            $image = uploadExtraDocs();
            //If Customer pays on the spot
                if($image == '')
                {
                    echo "Record not added becuase of file uploader";
                }else{
                        $sql = "UPDATE residencefine SET docName =:docName, originalName=:originalName WHERE residenceFineID
                         =:residenceFineID";
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':docName', $image);
                $stmt->bindParam(':originalName', $_FILES['Chargesuploader']['name']);
                $stmt->bindParam(':residenceFineID', $_POST['uploadChargesID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['DeleteResidence'])){
        // try{
        //         // First of all, let's begin a transaction
        //         $pdo->beginTransaction();
        //         // Update status of ticket
        //             $sql = "SELECT residenceFineID,docName FROM residencefine WHERE residenceID  = :id";
        //             $stmt = $pdo->prepare($sql);
        //             $stmt->bindParam(':id', $_POST['ID']);
        //             $stmt->execute();
        //             $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        //             if(!empty($file)){
        //                 for($k =0; $k < count($file); $k++){
        //                     if($file[$k]['docName']){
        //                         if(file_exists($file[$k]['docName'])){
        //                             unlink($file[$k]['docName']);
        //                         }
        //                     }
        //                     // delete payment for fine
        //                     $RFPSql = "DELETE FROM `customer_payments` WHERE residenceFinePayment = :rfPID";
        //                     $RFPStmt = $pdo->prepare($RFPSql);
        //                     $RFPStmt->bindParam(':rfPID',$file[$k]['residenceFineID']);
        //                     $RFPStmt->execute();
        //                 } 
        //             }
        //             // delete the fine itself
        //             $ResidenceFineSql = "DELETE FROM `residencefine` WHERE residenceID = :residenceFineID";
        //             $ResidenceFineStmt = $pdo->prepare($ResidenceFineSql);
        //             $ResidenceFineStmt->bindParam(':residenceFineID', $_POST['ID']);
        //             $ResidenceFineStmt->execute();
        //             // get residence documents
        //             $ResidenceDocsSQL = "SELECT file_name FROM residencedocuments WHERE ResID  = :id";
        //             $ResidenceDocsStmt = $pdo->prepare($ResidenceDocsSQL);
        //             $ResidenceDocsStmt->bindParam(':id', $_POST['ID']);
        //             $ResidenceDocsStmt->execute();
        //             $ResidenceDocsfiles =  $ResidenceDocsStmt->fetchAll(\PDO::FETCH_ASSOC);
        //             for($j = 0; $j <count($ResidenceDocsfiles); $j++){
        //                 if($ResidenceDocsfiles[$j]['file_name']){
        //                     unlink('residence/'.$ResidenceDocsfiles[$j]['file_name']);
        //                 }
        //             }
        //             // delete payment related to this residence
        //             $deleteResidencePSQL = "DELETE FROM `customer_payments` WHERE PaymentFor = :residenceID";
        //             $deleteResidencePStmt = $pdo->prepare($deleteResidencePSQL);
        //             $deleteResidencePStmt->bindParam(':residenceID', $_POST['ID']);
        //             $deleteResidencePStmt->execute();
        //             // delete residence docs
        //             $ResidenceDocsSql = "DELETE FROM `residencedocuments` WHERE ResID = :residenceID";
        //             $ResidenceDocStmt = $pdo->prepare($ResidenceDocsSql);
        //             $ResidenceDocStmt->bindParam(':residenceID', $_POST['ID']);
        //             $ResidenceDocStmt->execute();
        //             // delete the residence itself
        //             $deleteResidenceSQL = "DELETE FROM `residence` WHERE residenceID = :residenceID";
        //             $deleteResidenceStmt = $pdo->prepare($deleteResidenceSQL);
        //             $deleteResidenceStmt->bindParam(':residenceID', $_POST['ID']);
        //             $deleteResidenceStmt->execute();
                    

        //     $pdo->commit();
        //     echo "Success";
        // }catch(PDOException $e){
        //     $pdo->rollback();
        //     echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        // }


        /// get resicence from the id
        $statement = $pdo->prepare("SELECT * FROM `residence` WHERE residenceID = :residenceID");
        $statement->bindParam(':residenceID', $_POST['ID']);
        $statement->execute();

        $residence = $statement->fetch(\PDO::FETCH_ASSOC);

        $statement = $pdo->prepare("
        INSERT INTO  `delete_requests` 
        SET 
            `type` = 'residence',
            `datetime` = :datetime,
            `added_by` = :added_by,
            `unique_id` = :unique_id,
            `metadata` = :metadata
        ");


        $statement->bindParam(':added_by', $_SESSION['user_id']);
        $statement->bindParam(':unique_id', $_POST['ID']);
        $statement->bindParam(':metadata', json_encode($residence));
        $statement->bindParam(':datetime', date('Y-m-d H:i:s'));
        $statement->execute();  


        // update residence status
        $statement = $pdo->prepare("UPDATE `residence` SET `deleted` = 1 WHERE residenceID = :residenceID");
        $statement->bindParam(':residenceID', $_POST['ID']);
        $statement->execute();

        echo "Success";


    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT `fineAmount`, `fineCurrencyID`, accounts.account_ID, account_Name FROM 
        `residencefine` INNER JOIN accounts ON accounts.account_ID = residencefine.accountID WHERE residenceFineID = 
        :residenceFineID");
             $selectQuery->bindParam(':residenceFineID', $_POST['ID']);
             $selectQuery->execute();
             /* Fetch all of the remaining rows in the result set */
             $ExChVisaRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
             // encoding array to json format
             echo json_encode($ExChVisaRpt);
    }else if(isset($_POST['UpdSaveResidenceFine'])){
        try {
            // First of all, let's begin a transaction
                $pdo->beginTransaction();
                $decisionFlag = $pdo->prepare("SELECT curID, account_Name FROM `accounts` WHERE account_ID = :AccID");
                $decisionFlag->bindParam(':AccID', $_POST['UpdchargeAccount']);
                $decisionFlag->execute();
                /* Fetch all of the remaining rows in the result set */
                $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
                if($rpt[0]['account_Name'] == "Cash"){
                    // create prepared statement
                    $sql = "UPDATE `residencefine` SET `fineAmount`=:fineAmount,
                    `fineCurrencyID`=:fineCurrencyID,`accountID`=:accountID WHERE residencefine.residenceFineID = :residenceID";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':residenceID',$_POST['UpdrID']);
                    $stmt->bindParam(':fineAmount', $_POST['Updfine_Amount']);
                    $stmt->bindParam(':fineCurrencyID', $_POST['Updfine_Currency_type']);
                    $stmt->bindParam(':accountID', $_POST['UpdchargeAccount']);
                }else{
                    // create prepared statement
                    $sql = "UPDATE `residencefine` SET  `fineAmount`=:fineAmount,
                    `fineCurrencyID`=:fineCurrencyID,`accountID`=:accountID WHERE residencefine.residenceFineID = :residenceID";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':residenceID',$_POST['UpdrID']);
                    $stmt->bindParam(':fineAmount', $_POST['Updfine_Amount']);
                    $stmt->bindParam(':fineCurrencyID', $rpt[0]['curID']);
                    $stmt->bindParam(':accountID', $_POST['UpdchargeAccount']);
                }
                $stmt->execute();
                $pdo->commit(); 
                echo "Success";
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
         
    }else if(isset($_POST['DeleteFine'])){
        try{
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                    $sql = "SELECT docName FROM residencefine WHERE residenceFineID  = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['docName'];
                    if(file_exists($file)){
                        unlink($file);
                    }
                    if(!is_file($file)) {
                        // delete payment for fine
                        $RFPSql = "DELETE FROM `customer_payments` WHERE residenceFinePayment = :rfPID";
                        $RFPStmt = $pdo->prepare($RFPSql);
                        $RFPStmt->bindParam(':rfPID',$_POST['ID']);
                        $RFPStmt->execute();
                        // delete the fine
                        $sql = "DELETE FROM `residencefine` WHERE residenceFineID = :residenceFineID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':residenceFineID', $_POST['ID']);
                        $stmt->execute();
                    }
            $pdo->commit();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetTotalFine'])){
        $selectQuery = $pdo->prepare("SELECT  IFNULL(fineAmount,0) - 
        IFNULL((SELECT SUM(customer_payments.payment_amount) FROM customer_payments WHERE customer_payments.residenceFinePayment =
        :residenceFineID ),0) AS fineAmount, IFNULL(currencyName,'') AS currencyName FROM residencefine INNER JOIN currency ON 
        currency.currencyID = residencefine.fineCurrencyID WHERE residenceFineID = :residenceFineID ");
             $selectQuery->bindParam(':residenceFineID', $_POST['ID']);
             $selectQuery->execute();
             /* Fetch all of the remaining rows in the result set */
             $ExChVisaRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
             // encoding array to json format
             echo json_encode($ExChVisaRpt);
    }else if(isset($_POST['INSERT_FINE_PAYMENT'])){
        try {
            // First of all, let's begin a transaction
                $pdo->beginTransaction();
                $decisionFlag = $pdo->prepare("SELECT residence.residenceID,residence.customer_id,residencefine.fineCurrencyID FROM residence INNER JOIN
                residencefine ON residence.residenceID = residencefine.residenceID WHERE residencefine.residenceFineID = 
                :residenceFineID");
                $decisionFlag->bindParam(':residenceFineID', $_POST['ResFPaymentID']);
                $decisionFlag->execute();
                /* Fetch all of the remaining rows in the result set */
                $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
                if(($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['fineCurrencyID'] == '' || $rpt[0]['fineCurrencyID'] == null)){
                    
                    $pdo->rollback();
                    echo "Something went wrong";
                    exit();
                }else{
                    $getAccCur = $pdo->prepare("SELECT account_Name,curID FROM `accounts` WHERE account_ID = :accountID");
                    $getAccCur->bindParam(':accountID', $_POST['Fine_account_id']);
                    $getAccCur->execute();
                    /* Fetch all of the remaining rows in the result set */
                    $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
                    if($accCur[0]['account_Name'] == "Cash"){
                        // create prepared statement
                        $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment)";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':customer_id',$rpt[0]['customer_id']);
                        $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                        $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                        $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                        $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                        $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                    }else if($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] ==  $rpt[0]['fineCurrencyID']){
                        // create prepared statement
                        $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment)";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':customer_id',$rpt[0]['customer_id']);
                        $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                        $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                        $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                        $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                        $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                    }else{
                        $pdo->rollback();
                        echo "Currencies does not match! Please select account that its currency match with the sale price currency";
                        exit();
                    }
                    
                    // execute the prepared statement
                    $stmt->execute();
                    // create prepared statement
                    $checkTotal = "SELECT (IFNULL(SUM(residence.sale_price),0) + (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
                    FROM residencefine WHERE residencefine.residenceID = :resID)) - ((SELECT 
                    IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
                    :resID) + (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN 
                    residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment WHERE 
                    residencefine.residenceID = :resID)) AS total FROM residence WHERE residence.residenceID = :resID";
                    $checkTotalStmt = $pdo->prepare($checkTotal);
                    $checkTotalStmt->bindParam(':resID', $rpt[0]['residenceID']);
                    $checkTotalStmt->execute();
                    $total = $checkTotalStmt->fetchAll(\PDO::FETCH_ASSOC);
                    if($total[0]['total'] == 0){
                        $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                        $updateLockTranStmt = $pdo->prepare($updateLockTran);
                        $updateLockTranStmt->bindParam(':resID', $rpt[0]['residenceID']);
                        $updateLockTranStmt->execute();
                    }
                    $pdo->commit(); 
                    echo "Success";
                }
        } catch (PDOException $e) {
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
         
    }else if(isset($_POST['GetFineTotal'])){
        $selectQuery = $pdo->prepare("SELECT currencyName,IFNULL(SUM(Remaining_Fine),0) AS RF FROM(SELECT 
        currency.currencyName ,IFNULL(SUM(residencefine.fineAmount),0) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
        FROM customer_payments WHERE customer_payments.residenceFinePayment = residencefine.residenceFineID) AS Remaining_Fine 
        FROM residencefine INNER JOIN currency ON currency.currencyID = residencefine.fineCurrencyID WHERE 
        residencefine.residenceID = :resID GROUP BY residencefine.residenceFineID) AS baseTable GROUP BY currencyName HAVING RF 
        !=0 ORDER BY currencyName ASC");
        $selectQuery->bindParam(':resID', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['GetTotalResidencePendingP'])){
        $selectQuery = $pdo->prepare("SELECT currencyName,IFNULL(SUM(total),0) AS TotalBalance FROM (SELECT currencyName,
        IFNULL(SUM(residence.sale_price),0) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE
        customer_payments.PaymentFor IS NOT NULL AND customer_payments.currencyID = residence.saleCurID) AS total FROM residence
        INNER JOIN currency ON currency.currencyID = residence.saleCurID GROUP BY residence.saleCurID UNION ALL SELECT 
        currencyName,IFNULL(SUM(residencefine.fineAmount),0) - (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
        customer_payments WHERE customer_payments.residenceFinePayment IS NOT NULL AND customer_payments.currencyID = 
        residencefine.fineCurrencyID) AS total FROM residencefine INNER JOIN currency ON currency.currencyID = 
        residencefine.fineCurrencyID GROUP BY residencefine.fineCurrencyID) AS baseTable GROUP BY currencyName HAVING TotalBalance
        != 0 ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['GetSearchResult'])){
        $searchTerm = '%'.  str_replace(' ', '',strtolower($_POST['SearchTerm'])) . '%'; 
        $selectQuery = $pdo->prepare("SELECT 1 AS identifier, customer_id AS customer_id, customer_name AS customer_name, '' AS
        passenger_name FROM customer WHERE REPLACE(LOWER(customer_name), ' ', '') LIKE :searchTerm UNION ALL SELECT DISTINCT 2 AS
        identifier, customer.customer_id AS customer_id, customer_name AS customer_name, passenger_name AS passenger_name FROM 
        residence INNER JOIN customer ON customer.customer_id = residence.customer_id WHERE REPLACE(LOWER(passenger_name), ' ','')
        LIKE :searchTerm");
        $selectQuery->bindParam(':searchTerm', $searchTerm);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['GetCustomerCurrencyForSearch'])){
        $passengerName =  str_replace(' ', '',strtolower($_POST['PassengerName'])); 
        if($_POST['PassengerName'] != 'null'){
            $selectQuery = $pdo->prepare("SELECT DISTINCT currency.currencyID, currency.currencyName FROM currency INNER JOIN 
            residence ON residence.saleCurID = currency.currencyID WHERE residence.customer_id = :customerID AND 
            REPLACE(LOWER(passenger_name),' ','') = :passengerName ORDER BY currency.currencyName ASC");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
            $selectQuery->bindParam(':passengerName', $passengerName);
        }else{
            $selectQuery = $pdo->prepare("SELECT DISTINCT currency.currencyID, currency.currencyName FROM currency INNER JOIN 
            residence ON residence.saleCurID = currency.currencyID WHERE residence.customer_id = :customerID ORDER BY 
            currency.currencyName ASC");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['GetAbstrictView'])){
        $passengerName =  str_replace(' ', '',strtolower($_POST['PassengerName'])); 
        if($_POST['PassengerName'] != 'null'){
            $selectQuery = $pdo->prepare("SELECT currency.currencyName,IFNULL(SUM(residence.sale_price),0) AS total_residenceCost,
            (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN residence ON residence.residenceID = 
            residencefine.residenceID WHERE residence.customer_id = :customerID AND REPLACE(LOWER(passenger_name),' ','') = 
            :passengerName AND residencefine.fineCurrencyID = :currencyID AND residence.islocked = 0) AS residenceFine, (SELECT 
            IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN residence ON residence.residenceID
            = customer_payments.PaymentFor WHERE residence.customer_id = :customerID AND REPLACE(LOWER(passenger_name),' ','') =
            :passengerName AND customer_payments.currencyID = :currencyID AND residence.islocked = 0) AS total_residency_payment,
            (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN residencefine ON 
            residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN residence ON residence.residenceID
            = residencefine.residenceID WHERE residence.customer_id =:customerID  AND REPLACE(LOWER(passenger_name),' ','') = 
            :passengerName AND customer_payments.currencyID = :currencyID AND islocked = 0) AS total_fine_payment FROM residence 
            INNER JOIN currency ON currency.currencyID = residence.saleCurID WHERE residence.customer_id = :customerID AND 
            REPLACE(LOWER(passenger_name),' ','') = :passengerName AND residence.saleCurID = :currencyID AND residence.islocked = 
            0");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
            $selectQuery->bindParam(':passengerName', $passengerName);
            $selectQuery->bindParam(':currencyID', $_POST['ResidenceCurrency']);
        }else{
            $selectQuery = $pdo->prepare("SELECT currency.currencyName,IFNULL(SUM(residence.sale_price),0) AS total_residenceCost,
            (SELECT IFNULL(SUM(residencefine.fineAmount),0) FROM residencefine INNER JOIN residence ON residence.residenceID = 
            residencefine.residenceID WHERE residence.customer_id = :customerID AND residencefine.fineCurrencyID = :currencyID AND
            residence.islocked = 0) AS residenceFine, (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM 
            customer_payments INNER JOIN residence ON residence.residenceID = customer_payments.PaymentFor WHERE 
            residence.customer_id = :customerID AND customer_payments.currencyID = :currencyID AND residence.islocked = 0) AS 
            total_residency_payment,(SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN 
            residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN residence ON 
            residence.residenceID = residencefine.residenceID WHERE residence.customer_id =:customerID AND 
            customer_payments.currencyID = :currencyID AND residence.islocked = 0) AS total_fine_payment FROM residence INNER JOIN
            currency ON currency.currencyID = residence.saleCurID WHERE residence.customer_id = :customerID AND residence.saleCurID
            = :currencyID AND residence.islocked = 0");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
            $selectQuery->bindParam(':currencyID', $_POST['ResidenceCurrency']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['GetResidenceLedger'])){
        $passengerName =  str_replace(' ', '',strtolower($_POST['PassengerName'])); 
        if($_POST['PassengerName'] != 'null'){
            $selectQuery = $pdo->prepare("SELECT 'Residence application' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') AS dt,DATE(residence.datetime) AS OrderDate, 
            country_name.country_names AS visaType, residence.sale_price AS debit, 0 AS credit FROM residence INNER JOIN 
            country_name ON residence.VisaType = country_name.country_id WHERE residence.customer_id = :customerID  AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND residence.saleCurID = :currencyID AND 
            residence.islocked = 0 UNION ALL SELECT 'Residence Fine' AS transactionType, residence.passenger_name AS 
            passenger_name, DATE_FORMAT(DATE(residencefine.datetime),'%d-%b-%Y') AS dt,DATE(residencefine.datetime) AS orderDate,
            country_name.country_names AS visaType, residencefine.fineAmount AS debit, 0 AS credit FROM residencefine INNER JOIN
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE residence.customer_id = :customerID AND REPLACE(LOWER(residence.passenger_name), ' ','') = 
            :passengerName AND residencefine.fineCurrencyID = :currencyID AND residence.islocked = 0 UNION ALL SELECT 
            'Residence Payment' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType,0 AS debit,customer_payments.payment_amount AS credit FROM customer_payments 
            INNER JOIN residence ON residence.residenceID = customer_payments.PaymentFor INNER JOIN country_name ON 
            country_name.country_id = residence.VisaType WHERE customer_payments.customer_id = :customerID AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND customer_payments.currencyID = :currencyID AND
            residence.islocked = 0 UNION ALL SELECT 'Residence Fine Payment' AS transactionType, passenger_name AS passenger_name,
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType, 0 AS debit, customer_payments.payment_amount AS credit FROM customer_payments
            INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE customer_payments.customer_id = :customerID AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND customer_payments.currencyID = :currencyID AND
            residence.islocked = 0 ORDER BY orderDate, passenger_name, transactionType");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
            $selectQuery->bindParam(':passengerName', $passengerName);
            $selectQuery->bindParam(':currencyID', $_POST['CurID']);
        }else{
            $selectQuery = $pdo->prepare("SELECT 'Residence application' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') AS dt,DATE(residence.datetime) AS OrderDate, 
            country_name.country_names AS visaType, residence.sale_price AS debit, 0 AS credit FROM residence INNER JOIN 
            country_name ON residence.VisaType = country_name.country_id WHERE residence.customer_id = :customerID AND 
            residence.saleCurID = :currencyID AND residence.islocked = 0 UNION ALL SELECT 'Residence Fine' AS transactionType,
            residence.passenger_name AS passenger_name, DATE_FORMAT(DATE(residencefine.datetime),'%d-%b-%Y') AS dt,
            DATE(residencefine.datetime) AS orderDate,country_name.country_names AS visaType, residencefine.fineAmount AS debit,
            0 AS credit FROM residencefine INNER JOIN residence ON residence.residenceID = residencefine.residenceID INNER JOIN
            country_name ON country_name.country_id = residence.VisaType WHERE residence.customer_id = :customerID AND  
            residencefine.fineCurrencyID = :currencyID AND residence.islocked = 0 UNION ALL SELECT 'Residence Payment' AS 
            transactionType, passenger_name AS passenger_name, DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,
            DATE(customer_payments.datetime) AS orderDate, country_name.country_names AS VisaType,0 AS debit,
            customer_payments.payment_amount AS credit FROM customer_payments INNER JOIN residence ON residence.residenceID = 
            customer_payments.PaymentFor INNER JOIN country_name ON country_name.country_id = residence.VisaType WHERE 
            customer_payments.customer_id = :customerID AND customer_payments.currencyID = :currencyID AND residence.islocked = 0
            UNION ALL SELECT 'Residence Fine Payment' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType, 0 AS debit, customer_payments.payment_amount AS credit FROM customer_payments
            INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE customer_payments.customer_id = :customerID AND customer_payments.currencyID = :currencyID 
            AND residence.islocked = 0 ORDER BY orderDate, passenger_name, transactionType");
            $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
            $selectQuery->bindParam(':currencyID', $_POST['CurID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }
    function uploadExtraDocs(){
        $new_image_name = '';
        if($_FILES['Chargesuploader']['size']<=2097152){
            $extension = explode(".", $_FILES['Chargesuploader']['name']);
            $f_name = '';
            $f_ext = '';
            if(count($extension) > 2){
                for($i = 0; $i< count($extension); $i++){
                    if(count($extension) == $extension[$i]){
                        $f_name  = $f_name . $extension[$i];
                    }else{
                        $f_ext = $extension[$i];
                    }
                }
               
            }else{
                $f_name =  $extension[0];
                $f_ext = $extension[1];
            }
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($f_ext), $ext))
            {
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'residence/'. $new_image_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['Chargesuploader']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>