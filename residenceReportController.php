<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';

// Set PDO to throw exceptions on error
if (isset($pdo)) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
$sql = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$select = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $select[0]['select'];
if ($select == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
}

if (isset($_POST['GetPendingResidence'])) {
    // Log all relevant POST data for debugging
    error_log("POST data received: " . json_encode($_POST));

    try {
        $page = isset($_POST['Page']) ? (int)$_POST['Page'] : 1;
        $recordsPerPage = 10;
        $offset = ($page - 1) * $recordsPerPage;

        if (isset($_POST['Search']) && $_POST['Search'] !== '') {
            $search = '%' .  str_replace(' ', '', strtolower($_POST['Search'])) . '%';

            // Log the search parameter for debugging
            error_log("Search parameter: " . $_POST['Search']);
            error_log("Formatted search: " . $search);

            // Get total count for pagination - FIXED to include needed columns
            $countQuery = $pdo->prepare("SELECT COUNT(*) as total FROM(SELECT residenceID AS main_residenceID, 
                customer.customer_name, residence.passenger_name,
                (SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = residence.company) AS company_name,
                (SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = residence.company) AS company_number,
                residence.deleted
                FROM `residence`
                INNER JOIN customer ON customer.customer_id = residence.customer_id) AS BaseTable  
                WHERE (REPLACE(LOWER(customer_name),' ','') LIKE :search 
                OR REPLACE(LOWER(passenger_name),' ','') LIKE :search 
                OR REPLACE(LOWER(company_name),' ','') LIKE :search 
                OR REPLACE(LOWER(company_number),' ','') LIKE :search)
                AND deleted = 0");
            $countQuery->bindParam(':search', $search);
            $countQuery->execute();
            $totalRecords = $countQuery->fetch(\PDO::FETCH_ASSOC)['total'];

            // Get paginated data - now retrieving all residence records regardless of status
            $query = $pdo->prepare("SELECT res.residenceID AS main_residenceID,res.customer_id, 
                res.passenger_name, res.completedStep, res.deleted, res.sale_price,
                res.saleCurID, res.current_status AS current_status, cus.customer_name, 
                IF(res.expiry_date < CURDATE(), 1, 0) as isExpired,
                cus.customer_email, cus.customer_phone, cus.customer_Address,
                (SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = res.company) AS company_name,
                (SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = res.company) AS company_number,
                (SELECT country_names FROM country_name 
                WHERE country_name.country_id = res.VisaType) AS country_names, 
                (SELECT SUM(payment_amount) FROM customer_payments 
                WHERE customer_payments.PaymentFor = res.residenceID) AS total,
                (SELECT currencyName FROM currency 
                WHERE currency.currencyID = res.saleCurID) AS currencyName,
                (SELECT countryName FROM airports 
                WHERE airports.airport_id = res.Nationality) AS countryName,
                (SELECT IFNULL(SUM(fineAmount),0) FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID) AS total_Fine,
                (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments 
                WHERE customer_payments.residenceFinePayment IN 
                (SELECT residencefine.residenceFineID FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID)) AS totalFinePaid,
                (SELECT currencyName FROM currency 
                WHERE currency.currencyID = (SELECT fineCurrencyID FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID LIMIT 1)) AS residenceFineCurrency
                FROM residence as res 
                INNER JOIN customer as cus ON cus.customer_id = res.customer_id 
                WHERE (REPLACE(LOWER(cus.customer_name),' ','') LIKE :search 
                OR REPLACE(LOWER(res.passenger_name),' ','') LIKE :search 
                OR REPLACE(LOWER((SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = res.company)),' ','') LIKE :search 
                OR REPLACE(LOWER((SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = res.company)),' ','') LIKE :search) 
                AND res.deleted = 0 
                ORDER BY res.residenceID DESC LIMIT :offset, :limit");

            $query->bindParam(':search', $search);
            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll(\PDO::FETCH_ASSOC);

            // Create response with pagination info
            $response = array(
                'totalRecords' => $totalRecords,
                'currentPage' => $page,
                'recordsPerPage' => $recordsPerPage,
                'records' => $results
            );

            echo json_encode($response);
        } else {
            // Default query without search - now retrieving all residence records
            $countQuery = $pdo->prepare("SELECT COUNT(*) as total FROM `residence` WHERE deleted = 0");
            $countQuery->execute();
            $totalRecords = $countQuery->fetch(\PDO::FETCH_ASSOC)['total'];

            $query = $pdo->prepare("SELECT res.residenceID AS main_residenceID,res.customer_id, 
                res.passenger_name, res.completedStep, res.deleted, res.sale_price,
                res.saleCurID, res.current_status AS current_status, cus.customer_name, 
                cus.customer_email, cus.customer_phone, cus.customer_Address,
                IF(res.expiry_date < CURDATE(), 1, 0) as isExpired,
                (SELECT IFNULL(company_name,'') FROM company WHERE company.company_id = res.company) AS company_name,
                (SELECT IFNULL(company_number,'') FROM company WHERE company.company_id = res.company) AS company_number,
                (SELECT country_names FROM country_name 
                WHERE country_name.country_id = res.VisaType) AS country_names, 
                (SELECT SUM(payment_amount) FROM customer_payments 
                WHERE customer_payments.PaymentFor = res.residenceID) AS total,
                (SELECT currencyName FROM currency 
                WHERE currency.currencyID = res.saleCurID) AS currencyName,
                (SELECT countryName FROM airports 
                WHERE airports.airport_id = res.Nationality) AS countryName,
                (SELECT IFNULL(SUM(fineAmount),0) FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID) AS total_Fine,
                (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments 
                WHERE customer_payments.residenceFinePayment IN 
                (SELECT residencefine.residenceFineID FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID)) AS totalFinePaid,
                (SELECT currencyName FROM currency 
                WHERE currency.currencyID = (SELECT fineCurrencyID FROM residencefine 
                WHERE residencefine.residenceID = res.residenceID LIMIT 1)) AS residenceFineCurrency
                
                FROM residence as res 
                INNER JOIN customer as cus ON cus.customer_id = res.customer_id 
                WHERE res.deleted = 0 
                ORDER BY res.residenceID DESC LIMIT :offset, :limit");

            $query->bindParam(':offset', $offset, PDO::PARAM_INT);
            $query->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);
            $query->execute();
            $results = $query->fetchAll(\PDO::FETCH_ASSOC);

            // Create response with pagination info
            $response = array(
                'totalRecords' => $totalRecords,
                'currentPage' => $page,
                'recordsPerPage' => $recordsPerPage,
                'records' => $results
            );

            echo json_encode($response);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => true, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        echo json_encode(['error' => true, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else if (isset($_POST['Select_Accounts'])) {
    $selectQuery = $pdo->prepare("SELECT `account_ID`, `account_Name` FROM `accounts`ORDER BY account_Name ASC ");
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($supplier);
} else if (isset($_POST['GenerateReceipt'])) {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Get payment details
        $paymentID = $_POST['PaymentID'];
        $query = $pdo->prepare("SELECT cp.pay_id, cp.customer_id, cp.payment_amount, cp.datetime, 
                                    cp.currencyID, cp.staff_id, cp.accountID, cp.PaymentFor, cp.remarks,
                                    c.customer_name, c.customer_email, c.customer_phone,
                                    cr.currencyName, a.account_Name, s.staff_name
                                    FROM customer_payments cp
                                    INNER JOIN customer c ON c.customer_id = cp.customer_id
                                    INNER JOIN currency cr ON cr.currencyID = cp.currencyID
                                    INNER JOIN accounts a ON a.account_ID = cp.accountID
                                    INNER JOIN staff s ON s.staff_id = cp.staff_id
                                    WHERE cp.pay_id = :paymentID");
        $query->bindParam(':paymentID', $paymentID);
        $query->execute();
        $payment = $query->fetch(PDO::FETCH_ASSOC);

        if (!$payment) {
            // Payment not found
            echo json_encode(['message' => 'Error', 'error' => 'Payment not found']);
            exit;
        }

        // Generate invoice number
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        // Get max invoice number
        $invQuery = $pdo->prepare("SELECT MAX(CAST(SUBSTRING(invoiceNumber, 12) AS UNSIGNED)) as max_num FROM invoice 
                                       WHERE invoiceNumber LIKE :prefix");
        $prefix = "INV-$year$month$day-%";
        $invQuery->bindParam(':prefix', $prefix);
        $invQuery->execute();
        $result = $invQuery->fetch(PDO::FETCH_ASSOC);

        $nextNum = 1;
        if ($result && $result['max_num']) {
            $nextNum = $result['max_num'] + 1;
        }

        $invoiceNumber = "INV-$year$month$day-$nextNum";

        // Insert into invoice table
        $insertInvoice = $pdo->prepare("INSERT INTO invoice (customerID, invoiceNumber, invoiceCurrency) 
                                           VALUES (:customerID, :invoiceNumber, :invoiceCurrency)");

        $insertInvoice->bindParam(':customerID', $payment['customer_id']);
        $insertInvoice->bindParam(':invoiceNumber', $invoiceNumber);
        $insertInvoice->bindParam(':invoiceCurrency', $payment['currencyID']);
        $insertInvoice->execute();

        // Get the invoice ID
        $invoiceID = $pdo->lastInsertId();

        // Insert into invoicedetails
        $transactionType = "Payment";
        $insertInvoiceDetails = $pdo->prepare("INSERT INTO invoicedetails (invoiceID, transactionID, transactionType) 
                                                 VALUES (:invoiceID, :transactionID, :transactionType)");

        $insertInvoiceDetails->bindParam(':invoiceID', $invoiceID);
        $insertInvoiceDetails->bindParam(':transactionID', $payment['pay_id']);
        $insertInvoiceDetails->bindParam(':transactionType', $transactionType);
        $insertInvoiceDetails->execute();

        // Commit transaction
        $pdo->commit();

        // Success response
        echo json_encode(['message' => 'Success', 'receiptID' => $invoiceID]);
    } catch (PDOException $e) {
        // Roll back if there's an error
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['message' => 'Error', 'error' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("General error: " . $e->getMessage());
        echo json_encode(['message' => 'Error', 'error' => 'Error: ' . $e->getMessage()]);
    }
} else if (isset($_POST['GetPendingResidencePayment'])) {
    $selectQuery = $pdo->prepare("SELECT sale_price - IFNULL((SELECT SUM(customer_payments.payment_amount) FROM 
        customer_payments WHERE customer_payments.PaymentFor = :resID),0) AS remaining, currency.currencyName  FROM `residence`
        INNER JOIN currency ON currency.currencyID = residence.saleCurID WHERE residenceID = :resID");
    $selectQuery->bindParam(':resID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($data);
} else if (isset($_POST['INSERT_PAYMENT_EMAIL'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        $decisionFlag = $pdo->prepare("SELECT customer_id,saleCurID FROM `residence` WHERE residenceID =:residenceID");
        $decisionFlag->bindParam(':residenceID', $_POST['ResID']);
        $decisionFlag->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
        if (($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['saleCurID'] == '' || $rpt[0]['saleCurID'] == null)) {

            $pdo->rollback();
            echo "Something went wrong";
            exit();
        } else {
            $getAccCur = $pdo->prepare("SELECT account_Name,curID FROM `accounts` WHERE account_ID = :accountID");
            $getAccCur->bindParam(':accountID', $_POST['Account_ID']);
            $getAccCur->execute();
            /* Fetch all of the remaining rows in the result set */
            $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
            if ($accCur[0]['account_Name'] == "Cash") {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        PaymentFor) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,:PaymentFor)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
            } else if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] ==  $rpt[0]['saleCurID']) {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        PaymentFor) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,:PaymentFor)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
            } else {
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
            if ($total[0]['total'] == 0) {
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
} else if (isset($_POST['GetPendingPayForResidence'])) {
    if ($_POST['Search'] != '') {
        $page = (int)$_POST['Page'];
        $page = $page - 1;
        $offSet = $page  * 10;
        $search = '%' .  str_replace(' ', '', strtolower($_POST['Search'])) . '%';
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
    } else {
        $page = (int)$_POST['Page'];
        $page = $page - 1;
        $offSet = $page  * 10;
        $selectQuery = $pdo->prepare("SELECT * FROM (SELECT residenceID AS main_residenceID, customer_name,passenger_name , 
            airports.countryName, country_names,sale_price, currency.currencyName, current_status, completedStep,(SELECT IFNULL(company_name,'')
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
} else if (isset($_POST['GetCompletedResidence'])) {
    if ($_POST['Search'] != '') {
        $page = (int)$_POST['Page'];
        $page = $page - 1;
        $offSet = $page  * 10;
        $search = '%' .  str_replace(' ', '', strtolower($_POST['Search'])) . '%';
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
            REPLACE(LOWER(company_name),' ','')  LIKE :search OR REPLACE(LOWER(company_number),' ','') LIKE :search)))) =0) AS 
            totalRow FROM `residence` INNER JOIN customer ON customer.customer_id = residence.customer_id INNER JOIN airports ON
            airports.airport_id = residence.Nationality INNER JOIN country_name ON country_name.country_id = residence.VisaType 
            INNER JOIN company ON company.company_id = residence.company INNER JOIN currency ON currency.currencyID = 
            residence.saleCurID WHERE completedStep = 10 AND (REPLACE(LOWER(passenger_name),' ','') LIKE :search OR 
            REPLACE(LOWER(customer_name),' ','') LIKE :search OR REPLACE(LOWER(company_name),' ','') LIKE :search OR 
            REPLACE(LOWER(company_number),' ','') LIKE :search)) AS baseTable WHERE sale_price - total = 0 AND total_Fine - 
            totalFinePaid = 0  ORDER BY main_residenceID DESC LIMIT 10 OFFSET :OffsetNumber;");
        $selectQuery->bindParam(':search', $search);
        $selectQuery->bindParam(":OffsetNumber", $offSet, PDO::PARAM_INT);
    } else {
        $page = (int)$_POST['Page'];
        $page = $page - 1;
        $offSet = $page  * 10;
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
} else if (isset($_POST['SaveResidenceFine'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        $decisionFlag = $pdo->prepare("SELECT curID, account_Name FROM `accounts` WHERE account_ID = :AccID");
        $decisionFlag->bindParam(':AccID', $_POST['ChargeAccount']);
        $decisionFlag->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
        if ($rpt[0]['account_Name'] == "Cash") {
            // create prepared statement
            $sql = "INSERT INTO `residencefine`(`residenceID`,`fineAmount`, `fineCurrencyID`, `accountID`, `imposedBy`)
                    VALUES(:residenceID,:fineAmount,:fineCurrencyID,:accountID,:imposedBy)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':residenceID', $_POST['RID']);
            $stmt->bindParam(':fineAmount', $_POST['Fine_Amount']);
            $stmt->bindParam(':fineCurrencyID', $_POST['Fine_currency_type']);
            $stmt->bindParam(':accountID', $_POST['ChargeAccount']);
            $stmt->bindParam(':imposedBy', $_SESSION['user_id']);
        } else {
            // create prepared statement
            $sql = "INSERT INTO `residencefine`(`residenceID`,`fineAmount`, `fineCurrencyID`, `accountID`, `imposedBy`)
                    VALUES(:residenceID,:fineAmount,:fineCurrencyID,:accountID,:imposedBy)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':residenceID', $_POST['RID']);
            $stmt->bindParam(':fineAmount', $_POST['Fine_Amount']);
            $stmt->bindParam(':fineCurrencyID', $rpt[0]['curID']);
            $stmt->bindParam(':accountID', $_POST['ChargeAccount']);
            $stmt->bindParam(':imposedBy', $_SESSION['user_id']);
        }
        $stmt->execute();
        $pdo->commit();
        echo "Success";
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['ViewFine'])) {
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
} else if (isset($_POST['Upload_ExraChargeDoc'])) {
    try {
        $image = uploadExtraDocs();
        //If Customer pays on the spot
        if ($image == '') {
            echo "Record not added becuase of file uploader";
        } else {
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
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['DeleteResidence'])) {
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
} else if (isset($_POST['GetDataForUpdate'])) {
    $selectQuery = $pdo->prepare("SELECT `fineAmount`, `fineCurrencyID`, accounts.account_ID, account_Name FROM 
        `residencefine` INNER JOIN accounts ON accounts.account_ID = residencefine.accountID WHERE residenceFineID = 
        :residenceFineID");
    $selectQuery->bindParam(':residenceFineID', $_POST['ID']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $ExChVisaRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($ExChVisaRpt);
} else if (isset($_POST['UpdSaveResidenceFine'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        $decisionFlag = $pdo->prepare("SELECT curID, account_Name FROM `accounts` WHERE account_ID = :AccID");
        $decisionFlag->bindParam(':AccID', $_POST['UpdchargeAccount']);
        $decisionFlag->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
        if ($rpt[0]['account_Name'] == "Cash") {
            // create prepared statement
            $sql = "UPDATE `residencefine` SET `fineAmount`=:fineAmount,
                    `fineCurrencyID`=:fineCurrencyID,`accountID`=:accountID WHERE residencefine.residenceFineID = :residenceID";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':residenceID', $_POST['UpdrID']);
            $stmt->bindParam(':fineAmount', $_POST['Updfine_Amount']);
            $stmt->bindParam(':fineCurrencyID', $_POST['Updfine_Currency_type']);
            $stmt->bindParam(':accountID', $_POST['UpdchargeAccount']);
        } else {
            // create prepared statement
            $sql = "UPDATE `residencefine` SET  `fineAmount`=:fineAmount,
                    `fineCurrencyID`=:fineCurrencyID,`accountID`=:accountID WHERE residencefine.residenceFineID = :residenceID";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':residenceID', $_POST['UpdrID']);
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
} else if (isset($_POST['DeleteFine'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        // Update status of ticket
        $sql = "SELECT docName FROM residencefine WHERE residenceFineID  = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_POST['ID']);
        $stmt->execute();
        $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $file =  $file[0]['docName'];
        if (file_exists($file)) {
            unlink($file);
        }
        if (!is_file($file)) {
            // delete payment for fine
            $RFPSql = "DELETE FROM `customer_payments` WHERE residenceFinePayment = :rfPID";
            $RFPStmt = $pdo->prepare($RFPSql);
            $RFPStmt->bindParam(':rfPID', $_POST['ID']);
            $RFPStmt->execute();
            // delete the fine
            $sql = "DELETE FROM `residencefine` WHERE residenceFineID = :residenceFineID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':residenceFineID', $_POST['ID']);
            $stmt->execute();
        }
        $pdo->commit();
        echo "Success";
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['GetTotalFine'])) {
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
} else if (isset($_POST['INSERT_FINE_PAYMENT'])) {
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
        if (($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['fineCurrencyID'] == '' || $rpt[0]['fineCurrencyID'] == null)) {

            $pdo->rollback();
            echo "Something went wrong";
            exit();
        } else {
            $getAccCur = $pdo->prepare("SELECT account_Name,curID FROM `accounts` WHERE account_ID = :accountID");
            $getAccCur->bindParam(':accountID', $_POST['Fine_account_id']);
            $getAccCur->execute();
            /* Fetch all of the remaining rows in the result set */
            $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
            if ($accCur[0]['account_Name'] == "Cash") {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment, remarks) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                $stmt->bindParam(':remarks', $_POST['Fine_remarks']);
            } else if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] ==  $rpt[0]['fineCurrencyID']) {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment, remarks) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                $stmt->bindParam(':remarks', $_POST['Fine_remarks']);
            } else {
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
            if ($total[0]['total'] == 0) {
                $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                $updateLockTranStmt = $pdo->prepare($updateLockTran);
                $updateLockTranStmt->bindParam(':resID', $rpt[0]['residenceID']);
                $updateLockTranStmt->execute();
            }
            $pdo->commit();
            
            // Always return JSON for consistent response handling
            header('Content-Type: application/json');
            if (isset($_POST['SendEmail']) && $_POST['SendEmail'] === 'true') {
                echo json_encode([
                    'status' => 'Success',
                    'message' => 'Payment saved successfully. Note: Email functionality is not yet available for fine payments.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Success',
                    'message' => 'Payment saved successfully.'
                ]);
            }
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['INSERT_FINE_PAYMENT_EMAIL'])) {
    try {
        // Begin transaction
        $pdo->beginTransaction();
        
        $Fine_payAmount = $_POST['Fine_payAmount'];
        $resFPaymentID = $_POST['ResFPaymentID'];
        $fine_account_id = $_POST['Fine_account_id'];
        $fine_remarks = $_POST['Fine_remarks'];
        
        // First, get the fine, residence and customer info to include in email
        $getInfoQuery = "SELECT 
                        customer.customer_id, 
                        customer.customer_name, 
                        customer.customer_email, 
                        residence.passenger_name,
                        residence.residenceID,
                        residencefine.fineAmount,
                        residencefine.fineCurrencyID,
                        currency.currencyName,
                        customer.customer_phone
                    FROM residencefine
                    INNER JOIN residence ON residence.residenceID = residencefine.residenceID
                    INNER JOIN customer ON customer.customer_id = residence.customer_id
                    INNER JOIN currency ON currency.currencyID = residencefine.fineCurrencyID
                    WHERE residencefine.residenceFineID = :resFPaymentID";
        
        $getInfoStmt = $pdo->prepare($getInfoQuery);
        $getInfoStmt->bindParam(':resFPaymentID', $resFPaymentID);
        $getInfoStmt->execute();
        $customerInfo = $getInfoStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$customerInfo) {
            throw new Exception("Customer information not found");
        }
        
        // Check account currency compatibility
        $getAccCur = $pdo->prepare("SELECT account_Name, curID FROM `accounts` WHERE account_ID = :accountID");
        $getAccCur->bindParam(':accountID', $fine_account_id);
        $getAccCur->execute();
        $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] != $customerInfo['fineCurrencyID']) {
            throw new Exception("Currencies do not match! Please select an account with matching currency.");
        }
        
        // Continue with payment insertion
        $insertPaymentQuery = "INSERT INTO `customer_payments`(
                            `customer_id`,
                            `payment_amount`,
                            `currencyID`, 
                            `staff_id`,
                            `accountID`,
                            `residenceFinePayment`, 
                            `remarks`) 
                        VALUES (
                            :customer_id, 
                            :payment_amount,
                            :currencyID,
                            :staff_id,
                            :accountID,
                            :residenceFinePayment,
                            :remarks)";
                            
        $insertStmt = $pdo->prepare($insertPaymentQuery);
        $insertStmt->bindParam(':customer_id', $customerInfo['customer_id']);
        $insertStmt->bindParam(':payment_amount', $Fine_payAmount);
        $insertStmt->bindParam(':currencyID', $customerInfo['fineCurrencyID']);
        $insertStmt->bindParam(':staff_id', $_SESSION['user_id']);
        $insertStmt->bindParam(':accountID', $fine_account_id);
        $insertStmt->bindParam(':residenceFinePayment', $resFPaymentID);
        $insertStmt->bindParam(':remarks', $fine_remarks);
        $insertStmt->execute();
        
        // Check if total payment equals total residence + fine cost, if so update islocked
        $checkTotal = "SELECT (IFNULL(SUM(residence.sale_price),0) + (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
                FROM residencefine WHERE residencefine.residenceID = :resID)) - ((SELECT 
                IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments WHERE customer_payments.PaymentFor = 
                :resID) + (SELECT IFNULL(SUM(customer_payments.payment_amount),0) FROM customer_payments INNER JOIN 
                residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment WHERE 
                residencefine.residenceID = :resID)) AS total FROM residence WHERE residence.residenceID = :resID";
                
        $checkTotalStmt = $pdo->prepare($checkTotal);
        $checkTotalStmt->bindParam(':resID', $customerInfo['residenceID']);
        $checkTotalStmt->execute();
        $total = $checkTotalStmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($total[0]['total'] == 0) {
            $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
            $updateLockTranStmt = $pdo->prepare($updateLockTran);
            $updateLockTranStmt->bindParam(':resID', $customerInfo['residenceID']);
            $updateLockTranStmt->execute();
        }
        
        // Send email notification
        $success = true;
        $emailMsg = "";
        
        if (!empty($customerInfo['customer_email'])) {
            // Use PHPMailer to send email
            require 'vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'selabnadirydxb@gmail.com';
                $mail->Password = 'qyzuznoxbrfmjvxa';
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                // Format current date/time for email
                $paymentDate = date('d M Y, h:i A');
                
                // Sender and recipient
                $mail->setFrom('selabnadirydxb@gmail.com', 'SN Travels');
                $mail->addAddress($customerInfo['customer_email'], $customerInfo['customer_name']);
                
                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Fine Payment Confirmation - SN Travels';
                
                // Build email body with payment details
                $emailBody = "
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset='utf-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1'>
                        <title>Fine Payment Confirmation</title>
                        <style>
                            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
                            
                            body { 
                                font-family: 'Poppins', Arial, sans-serif; 
                                line-height: 1.6; 
                                color: #444; 
                                margin: 0;
                                padding: 0;
                                background-color: #f9f9f9;
                            }
                            
                            .email-container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                border-radius: 8px;
                                overflow: hidden;
                                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                            }
                            
                            .email-header {
                                background: #000000;
                                color: white;
                                padding: 30px 20px;
                                text-align: center;
                            }
                            
                            .email-header h2 {
                                margin: 0;
                                font-weight: 600;
                                font-size: 24px;
                                letter-spacing: 0.5px;
                            }
                            
                            .logo {
                                margin-bottom: 15px;
                                font-weight: 700;
                                font-size: 28px;
                                color: white;
                            }
                            
                            .email-content {
                                padding: 30px;
                            }
                            
                            .greeting {
                                font-size: 18px;
                                margin-bottom: 15px;
                                color: #333;
                            }
                            
                            .message {
                                margin-bottom: 25px;
                                color: #555;
                            }
                            
                            .section-title {
                                font-size: 18px;
                                font-weight: 600;
                                margin-bottom: 15px;
                                color: #ff423e;
                                border-bottom: 2px solid #ff423e;
                                padding-bottom: 5px;
                                display: inline-block;
                            }
                            
                            .details-table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 30px;
                                border-radius: 6px;
                                overflow: hidden;
                                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                            }
                            
                            .details-table th {
                                background-color: #f2f2f2;
                                padding: 12px 15px;
                                text-align: left;
                                font-weight: 600;
                                color: #333;
                                border-bottom: 1px solid #ddd;
                            }
                            
                            .details-table td {
                                padding: 12px 15px;
                                text-align: left;
                                border-bottom: 1px solid #eee;
                            }
                            
                            .details-table tr:last-child td {
                                border-bottom: none;
                            }
                            
                            .highlight {
                                font-weight: 600;
                                color: #ff423e;
                                font-size: 16px;
                            }
                            
                            .contact-info {
                                background-color: #f9f9f9;
                                padding: 20px;
                                border-radius: 6px;
                                margin-bottom: 25px;
                            }
                            
                            .contact-info p {
                                margin: 5px 0;
                            }
                            
                            .contact-label {
                                font-weight: 600;
                                color: #666;
                                width: 50px;
                                display: inline-block;
                            }
                            
                            .thank-you {
                                margin: 25px 0;
                                font-weight: 500;
                            }
                            
                            .signature {
                                margin-top: 15px;
                                color: #555;
                            }
                            
                            .email-footer {
                                background-color: #333;
                                color: white;
                                text-align: center;
                                padding: 20px;
                                font-size: 12px;
                            }
                            
                            .email-footer p {
                                margin: 5px 0;
                                color: #ccc;
                            }
                            
                            @media only screen and (max-width: 600px) {
                                .email-container {
                                    width: 100% !important;
                                    border-radius: 0;
                                }
                                
                                .email-content {
                                    padding: 20px 15px;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='email-header'>
                                <div class='logo'>SN TRAVELS</div>
                                <h2>Fine Payment Confirmation</h2>
                            </div>
                            
                            <div class='email-content'>
                                <p class='greeting'>Dear {$customerInfo['customer_name']},</p>
                                
                                <p class='message'>Thank you for your fine payment. We are pleased to confirm that we have received your payment successfully.</p>
                                
                                <h3 class='section-title'>Payment Details</h3>
                                
                                <table class='details-table'>
                                    <tr>
                                        <th>Payment Date</th>
                                        <td>{$paymentDate}</td>
                                    </tr>
                                    <tr>
                                        <th>Passenger Name</th>
                                        <td>{$customerInfo['passenger_name']}</td>
                                    </tr>
                                    <tr>
                                        <th>Amount Paid</th>
                                        <td class='highlight'>{$Fine_payAmount} {$customerInfo['currencyName']}</td>
                                    </tr>
                                    <tr>
                                        <th>Payment Type</th>
                                        <td>Residence Fine Payment</td>
                                    </tr>";
                
                // Add remarks to email if provided
                if(!empty($fine_remarks)) {
                    $emailBody .= "
                                    <tr>
                                        <th>Remarks</th>
                                        <td>{$fine_remarks}</td>
                                    </tr>";
                }
                
                $emailBody .= "
                                </table>
                                
                                <div class='contact-info'>
                                    <p>If you have any questions or need further assistance, please contact us:</p>
                                    <p><span class='contact-label'>Phone:</span> +97143237879</p>
                                    <p><span class='contact-label'>Email:</span> info@sntrips.com</p>
                                </div>
                                
                                <p class='thank-you'>Thank you for choosing SN Travels.</p>
                                
                                <div class='signature'>
                                    Best regards,<br>
                                    SN Travels Team
                                </div>
                            </div>
                            
                            <div class='email-footer'>
                                <p>This is an automated email. Please do not reply to this message.</p>
                                <p>&copy; " . date('Y') . " SN Travels & Tourism L.L.C. All rights reserved.</p>
                            </div>
                        </div>
                    </body>
                    </html>";

                $mail->Body = $emailBody;
                $mail->AltBody = "Fine payment confirmation for {$customerInfo['passenger_name']}. Amount: {$Fine_payAmount} {$customerInfo['currencyName']}. Date: {$paymentDate}. Contact us at +97143237879 or info@sntrips.com";

                $mail->send();
                $emailMsg = "Email sent successfully to {$customerInfo['customer_email']}";
            } catch (Exception $e) {
                $success = false;
                $emailMsg = "Failed to send email: " . $mail->ErrorInfo;
            }
        } else {
            $success = false;
            $emailMsg = "Customer email not available";
        }
        
        $pdo->commit();
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'Success',
            'message' => 'Fine payment saved successfully. ' . ($success ? $emailMsg : 'Note: ' . $emailMsg),
            'email_sent' => $success
        ]);
        
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'Error',
            'message' => "Failed to process payment: " . $e->getMessage()
        ]);
    }
} else if (isset($_POST['GetFineTotal'])) {
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
} else if (isset($_POST['GetTotalResidencePendingP'])) {
    getTotalResidencePendingP();
} else if (isset($_POST['GetSearchResult'])) {
    $searchTerm = '%' .  str_replace(' ', '', strtolower($_POST['SearchTerm'])) . '%';
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
} else if (isset($_POST['GetCustomerCurrencyForSearch'])) {
    $passengerName =  str_replace(' ', '', strtolower($_POST['PassengerName']));
    if ($_POST['PassengerName'] != 'null') {
        $selectQuery = $pdo->prepare("SELECT DISTINCT currency.currencyID, currency.currencyName FROM currency INNER JOIN 
            residence ON residence.saleCurID = currency.currencyID WHERE residence.customer_id = :customerID AND 
            REPLACE(LOWER(passenger_name),' ','') = :passengerName ORDER BY currency.currencyName ASC");
        $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
        $selectQuery->bindParam(':passengerName', $passengerName);
    } else {
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
} else if (isset($_POST['GetAbstrictView'])) {
    $passengerName =  str_replace(' ', '', strtolower($_POST['PassengerName']));
    if ($_POST['PassengerName'] != 'null') {
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
    } else {
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
} else if (isset($_POST['GetResidenceLedger'])) {
    $passengerName =  str_replace(' ', '', strtolower($_POST['PassengerName']));
    if ($_POST['PassengerName'] != 'null') {
        $selectQuery = $pdo->prepare("SELECT 'Residence application' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') AS dt,DATE(residence.datetime) AS OrderDate, 
            country_name.country_names AS visaType, residence.sale_price AS debit, 0 AS credit FROM residence INNER JOIN 
            country_name ON residence.VisaType = country_name.country_id WHERE residence.customer_id = :customerID  AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND residence.saleCurID = :currencyID AND 
            residence.islocked = 0 AND residence.current_status = 'Active' UNION ALL SELECT 'Residence Fine' AS transactionType, residence.passenger_name AS 
            passenger_name, DATE_FORMAT(DATE(residencefine.datetime),'%d-%b-%Y') AS dt,DATE(residencefine.datetime) AS orderDate,
            country_name.country_names AS visaType, residencefine.fineAmount AS debit, 0 AS credit FROM residencefine INNER JOIN
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE residence.customer_id = :customerID AND REPLACE(LOWER(residence.passenger_name), ' ','') = 
            :passengerName AND residencefine.fineCurrencyID = :currencyID AND residence.islocked = 0 AND residence.current_status = 'Active' UNION ALL SELECT 
            'Residence Payment' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType,0 AS debit,customer_payments.payment_amount AS credit FROM customer_payments 
            INNER JOIN residence ON residence.residenceID = customer_payments.PaymentFor INNER JOIN country_name ON 
            country_name.country_id = residence.VisaType WHERE customer_payments.customer_id = :customerID AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND customer_payments.currencyID = :currencyID AND
            residence.islocked = 0 AND residence.current_status = 'Active' UNION ALL SELECT 'Residence Fine Payment' AS transactionType, passenger_name AS passenger_name,
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType, 0 AS debit, customer_payments.payment_amount AS credit FROM customer_payments
            INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE customer_payments.customer_id = :customerID AND 
            REPLACE(LOWER(residence.passenger_name), ' ','') = :passengerName AND customer_payments.currencyID = :currencyID AND
            residence.islocked = 0 AND residence.current_status = 'Active' ORDER BY orderDate, passenger_name, transactionType");
        $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
        $selectQuery->bindParam(':passengerName', $passengerName);
        $selectQuery->bindParam(':currencyID', $_POST['CurID']);
    } else {
        $selectQuery = $pdo->prepare("SELECT 'Residence application' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(residence.datetime),'%d-%b-%Y') AS dt,DATE(residence.datetime) AS OrderDate, 
            country_name.country_names AS visaType, residence.sale_price AS debit, 0 AS credit FROM residence INNER JOIN 
            country_name ON residence.VisaType = country_name.country_id WHERE residence.customer_id = :customerID AND 
            residence.saleCurID = :currencyID AND residence.islocked = 0 AND residence.current_status = 'Active' UNION ALL SELECT 'Residence Fine' AS transactionType,
            residence.passenger_name AS passenger_name, DATE_FORMAT(DATE(residencefine.datetime),'%d-%b-%Y') AS dt,
            DATE(residencefine.datetime) AS orderDate,country_name.country_names AS visaType, residencefine.fineAmount AS debit,
            0 AS credit FROM residencefine INNER JOIN residence ON residence.residenceID = residencefine.residenceID INNER JOIN
            country_name ON country_name.country_id = residence.VisaType WHERE residence.customer_id = :customerID AND  
            residencefine.fineCurrencyID = :currencyID AND residence.islocked = 0 AND residence.current_status = 'Active' UNION ALL SELECT 'Residence Payment' AS 
            transactionType, passenger_name AS passenger_name, DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,
            DATE(customer_payments.datetime) AS orderDate, country_name.country_names AS VisaType,0 AS debit,
            customer_payments.payment_amount AS credit FROM customer_payments INNER JOIN residence ON residence.residenceID = 
            customer_payments.PaymentFor INNER JOIN country_name ON country_name.country_id = residence.VisaType WHERE 
            customer_payments.customer_id = :customerID AND customer_payments.currencyID = :currencyID AND residence.islocked = 0
            AND residence.current_status = 'Active' UNION ALL SELECT 'Residence Fine Payment' AS transactionType, passenger_name AS passenger_name, 
            DATE_FORMAT(DATE(customer_payments.datetime),'%d-%b-%Y') AS dt,DATE(customer_payments.datetime) AS orderDate,
            country_name.country_names AS VisaType, 0 AS debit, customer_payments.payment_amount AS credit FROM customer_payments
            INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment INNER JOIN 
            residence ON residence.residenceID = residencefine.residenceID INNER JOIN country_name ON country_name.country_id =
            residence.VisaType WHERE customer_payments.customer_id = :customerID AND customer_payments.currencyID = :currencyID 
            AND residence.islocked = 0 AND residence.current_status = 'Active' ORDER BY orderDate, passenger_name, transactionType");
        $selectQuery->bindParam(':customerID', $_POST['CustomerID']);
        $selectQuery->bindParam(':currencyID', $_POST['CurID']);
    }
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // encoding array to json format
    echo json_encode($data);
} else if (isset($_POST['GetPaymentHistory'])) {
    $selectQuery = $pdo->prepare("SELECT 
            customer_payments.pay_id as paymentID,
            DATE_FORMAT(customer_payments.datetime, '%d-%b-%Y') as payment_date,
            customer_payments.payment_amount as amount,
            accounts.account_Name as account_name,
            currency.currencyName as currency_name,
            customer_payments.remarks,
            staff.staff_name as staff_name,
            CASE 
                WHEN customer_payments.residenceFinePayment IS NOT NULL THEN 'Fine Payment'
                ELSE 'Residence Payment'
            END as payment_type
        FROM customer_payments 
        INNER JOIN accounts ON accounts.account_ID = customer_payments.accountID
        INNER JOIN currency ON currency.currencyID = customer_payments.currencyID
        INNER JOIN staff ON staff.staff_id = customer_payments.staff_id
        WHERE customer_payments.PaymentFor = :resID
        ORDER BY customer_payments.datetime DESC");

    $selectQuery->bindParam(':resID', $_POST['ID']);
    $selectQuery->execute();
    $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($data);
} else if (isset($_POST['GetBanks'])) {
    try {
        $query = $pdo->prepare("SELECT id, bank_name FROM banks ORDER BY bank_name ASC");
        $query->execute();
        $banks = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($banks);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
} else if (isset($_POST['CancelResidence'])) {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Get residence ID and validate it
        $residenceID = $_POST['ResidenceID'];
        $charges = $_POST['CancellationCharges'];
        $remarks = $_POST['Remarks'];

        // Log inputs for debugging
        error_log("CancelResidence called with: ID=$residenceID, Charges=$charges, Remarks=$remarks");

        // Validate inputs
        if (empty($residenceID) || !is_numeric($residenceID)) {
            throw new Exception("Invalid residence ID");
        }

        if (empty($charges) || !is_numeric($charges) || $charges <= 0) {
            throw new Exception("Invalid cancellation charges");
        }

        if (empty($remarks)) {
            throw new Exception("Remarks cannot be empty");
        }

        // Get customer ID for the residence
        $customerQuery = $pdo->prepare("SELECT customer_id FROM residence WHERE residenceID = :residenceID");
        $customerQuery->bindParam(':residenceID', $residenceID);
        $customerQuery->execute();
        $customerData = $customerQuery->fetch(PDO::FETCH_ASSOC);

        if (!$customerData) {
            throw new Exception("Residence not found");
        }

        $customerID = $customerData['customer_id'];
        error_log("Found customer ID: $customerID for residence: $residenceID");

        // Insert into residence_cancellation table
        $insertQuery = $pdo->prepare("INSERT INTO residence_cancellation (residence, cancellation_charges, remarks, customer_id) 
                                      VALUES (:residenceID, :charges, :remarks, :customerID)");
        $insertQuery->bindParam(':residenceID', $residenceID);
        $insertQuery->bindParam(':charges', $charges);
        $insertQuery->bindParam(':remarks', $remarks);
        $insertQuery->bindParam(':customerID', $customerID);
        $insertResult = $insertQuery->execute();
        
        if (!$insertResult) {
            $errorInfo = $insertQuery->errorInfo();
            error_log("Error inserting cancellation record: " . json_encode($errorInfo));
            throw new Exception("Failed to insert cancellation record: " . $errorInfo[2]);
        }
        
        $cancellationId = $pdo->lastInsertId();
        error_log("Inserted cancellation record with ID: $cancellationId");

        // Update residence status to cancelled
        $updateQuery = $pdo->prepare("UPDATE residence SET current_status = 'Cancelled' WHERE residenceID = :residenceID");
        $updateQuery->bindParam(':residenceID', $residenceID);
        $updateResult = $updateQuery->execute();
        
        if (!$updateResult) {
            $errorInfo = $updateQuery->errorInfo();
            error_log("Error updating residence status: " . json_encode($errorInfo));
            throw new Exception("Failed to update residence status: " . $errorInfo[2]);
        }
        
        error_log("Updated residence status to Cancelled for ID: $residenceID");

        // Commit transaction
        $pdo->commit();
        error_log("Cancellation completed successfully for residence: $residenceID");

        echo json_encode(['status' => 'Success', 'message' => 'Residence cancelled successfully']);
    } catch (PDOException $e) {
        // Roll back if there's an error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Database error in cancellation: " . $e->getMessage());
        echo json_encode(['status' => 'Error', 'message' => 'Database error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("General error in cancellation: " . $e->getMessage());
        echo json_encode(['status' => 'Error', 'message' => $e->getMessage()]);
    }
} else if (isset($_POST['INSERT_PAYMENT_EMAIL'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        $decisionFlag = $pdo->prepare("SELECT customer_id, saleCurID FROM residence WHERE residenceID = :residenceID");
        $decisionFlag->bindParam(':residenceID', $_POST['ResID']);
        $decisionFlag->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
        if (($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['saleCurID'] == '' || $rpt[0]['saleCurID'] == null)) {
            $pdo->rollback();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Error',
                'message' => 'Invalid residence data. Please try again.'
            ]);
            exit();
        } else {
            $getAccCur = $pdo->prepare("SELECT account_Name, curID FROM `accounts` WHERE account_ID = :accountID");
            $getAccCur->bindParam(':accountID', $_POST['Account_ID']);
            $getAccCur->execute();
            /* Fetch all of the remaining rows in the result set */
            $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
            
            // Get customer email for later use
            $getCustomerInfo = $pdo->prepare("SELECT c.customer_email, c.customer_name, r.passenger_name, 
                                                    cr.currencyName, r.sale_price 
                                               FROM customer c 
                                               JOIN residence r ON c.customer_id = r.customer_id 
                                               JOIN currency cr ON cr.currencyID = r.saleCurID 
                                              WHERE r.residenceID = :resID");
            $getCustomerInfo->bindParam(':resID', $_POST['ResID']);
            $getCustomerInfo->execute();
            $customerInfo = $getCustomerInfo->fetch(PDO::FETCH_ASSOC);
            
            if ($accCur[0]['account_Name'] == "Cash") {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,`accountID`,
                        `PaymentFor`, `remarks`) VALUES (:customer_id, :payment_amount, :currencyID, :staff_id, :accountID,
                        :PaymentFor, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
            } else if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] == $rpt[0]['saleCurID']) {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,`accountID`,
                        `PaymentFor`, `remarks`) VALUES (:customer_id, :payment_amount, :currencyID, :staff_id, :accountID,
                        :PaymentFor, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
            } else {
                $pdo->rollback();
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'Error',
                    'message' => 'Currencies do not match! Please select an account with matching currency.'
                ]);
                exit();
            }

            // Execute the prepared statement to save payment
            $stmt->execute();
            
            // Check if payment has completed the residence total amount
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
            
            if ($total[0]['total'] == 0) {
                $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                $updateLockTranStmt = $pdo->prepare($updateLockTran);
                $updateLockTranStmt->bindParam(':resID', $_POST['ResID']);
                $updateLockTranStmt->execute();
            }
            
            // Send email notification if customer has email
            $emailSuccess = false;
            $emailMsg = 'Payment saved successfully.';
            
            if (!empty($customerInfo['customer_email'])) {
                try {
                    require 'vendor/autoload.php';
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'selabnadirydxb@gmail.com';
                    $mail->Password = 'qyzuznoxbrfmjvxa';
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    // Format current date/time for email
                    $paymentDate = date('d M Y, h:i A');
                    
                    // Sender and recipient 
                    $mail->setFrom('selabnadirydxb@gmail.com', 'SN Travels');
                    $mail->addAddress($customerInfo['customer_email'], $customerInfo['customer_name']);
                    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Residence Payment Confirmation - SN Travels';
                    
                    // Build email body with payment details
                    $emailBody = "
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset='utf-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1'>
                            <title>Payment Confirmation</title>
                            <style>
                                body { 
                                    font-family: 'Arial', sans-serif; 
                                    line-height: 1.6; 
                                    color: #444; 
                                    margin: 0;
                                    padding: 0;
                                    background-color: #f9f9f9;
                                }
                                
                                .email-container {
                                    max-width: 600px;
                                    margin: 0 auto;
                                    background-color: #ffffff;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                                }
                                
                                .email-header {
                                    background: #000000;
                                    color: white;
                                    padding: 30px 20px;
                                    text-align: center;
                                }
                                
                                .email-header h2 {
                                    margin: 0;
                                    font-weight: 600;
                                    font-size: 24px;
                                    letter-spacing: 0.5px;
                                }
                                
                                .email-content {
                                    padding: 30px;
                                }
                                
                                .details-table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-bottom: 30px;
                                    border-radius: 6px;
                                    overflow: hidden;
                                    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                                }
                                
                                .details-table th {
                                    background-color: #f2f2f2;
                                    padding: 12px 15px;
                                    text-align: left;
                                    font-weight: 600;
                                    color: #333;
                                    border-bottom: 1px solid #ddd;
                                }
                                
                                .details-table td {
                                    padding: 12px 15px;
                                    text-align: left;
                                    border-bottom: 1px solid #eee;
                                }
                            </style>
                        </head>
                        <body>
                            <div class='email-container'>
                                <div class='email-header'>
                                    <div class='logo'>SN TRAVELS</div>
                                    <h2>Payment Confirmation</h2>
                                </div>
                                
                                <div class='email-content'>
                                    <p>Dear {$customerInfo['customer_name']},</p>
                                    
                                    <p>Thank you for your payment. We are pleased to confirm that we have received your payment successfully.</p>
                                    
                                    <h3>Payment Details</h3>
                                    
                                    <table class='details-table'>
                                        <tr>
                                            <th>Payment Date</th>
                                            <td>{$paymentDate}</td>
                                        </tr>
                                        <tr>
                                            <th>Passenger Name</th>
                                            <td>{$customerInfo['passenger_name']}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount Paid</th>
                                            <td>{$_POST['Payment']} {$customerInfo['currencyName']}</td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>{$_POST['Remarks']}</td>
                                        </tr>
                                    </table>
                                    
                                    <p>If you have any questions or need further assistance, please contact us at +97143237879 or info@sntrips.com</p>
                                    
                                    <p>Thank you for choosing SN Travels.</p>
                                    
                                    <div>
                                        Best regards,<br>
                                        SN Travels Team
                                    </div>
                                </div>
                            </div>
                        </body>
                        </html>";

                    $mail->Body = $emailBody;
                    $mail->AltBody = "Payment confirmation for {$customerInfo['passenger_name']}. Amount: {$_POST['Payment']} {$customerInfo['currencyName']}. Date: {$paymentDate}. Contact us at +97143237879 or info@sntrips.com";

                    $mail->send();
                    $emailSuccess = true;
                    $emailMsg = 'Payment saved and email sent successfully.';
                } catch (Exception $e) {
                    $emailMsg = 'Payment saved successfully. Note: Failed to send email: ' . $mail->ErrorInfo;
                }
            } else {
                $emailMsg = 'Payment saved successfully. Note: Customer email not available.';
            }
            
            // Commit database transaction
            $pdo->commit();
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Success',
                'message' => $emailMsg,
                'email_sent' => $emailSuccess
            ]);
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'Error',
            'message' => "Failed to process payment: " . $e->getMessage()
        ]);
    }
} else if (isset($_POST['INSERT_FINE_PAYMENT'])) {
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
        if (($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['fineCurrencyID'] == '' || $rpt[0]['fineCurrencyID'] == null)) {

            $pdo->rollback();
            echo "Something went wrong";
            exit();
        } else {
            $getAccCur = $pdo->prepare("SELECT account_Name,curID FROM `accounts` WHERE account_ID = :accountID");
            $getAccCur->bindParam(':accountID', $_POST['Fine_account_id']);
            $getAccCur->execute();
            /* Fetch all of the remaining rows in the result set */
            $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
            if ($accCur[0]['account_Name'] == "Cash") {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment, remarks) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                $stmt->bindParam(':remarks', $_POST['Fine_remarks']);
            } else if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] ==  $rpt[0]['fineCurrencyID']) {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID,
                        residenceFinePayment, remarks) VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID,
                        :residenceFinePayment, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Fine_payAmount']);
                $stmt->bindParam(':currencyID', $rpt[0]['fineCurrencyID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Fine_account_id']);
                $stmt->bindParam(':residenceFinePayment', $_POST['ResFPaymentID']);
                $stmt->bindParam(':remarks', $_POST['Fine_remarks']);
            } else {
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
            if ($total[0]['total'] == 0) {
                $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                $updateLockTranStmt = $pdo->prepare($updateLockTran);
                $updateLockTranStmt->bindParam(':resID', $rpt[0]['residenceID']);
                $updateLockTranStmt->execute();
            }
            $pdo->commit();
            
            // Always return JSON for consistent response handling
            header('Content-Type: application/json');
            if (isset($_POST['SendEmail']) && $_POST['SendEmail'] === 'true') {
                echo json_encode([
                    'status' => 'Success',
                    'message' => 'Payment saved successfully. Note: Email functionality is not yet available for fine payments.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Success',
                    'message' => 'Payment saved successfully.'
                ]);
            }
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} else if (isset($_POST['Insert_Payment'])) {
    try {
        // First of all, let's begin a transaction
        $pdo->beginTransaction();
        $decisionFlag = $pdo->prepare("SELECT customer_id, saleCurID FROM residence WHERE residenceID = :residenceID");
        $decisionFlag->bindParam(':residenceID', $_POST['ResID']);
        $decisionFlag->execute();
        /* Fetch all of the remaining rows in the result set */
        $rpt = $decisionFlag->fetchAll(\PDO::FETCH_ASSOC);
        if (($rpt[0]['customer_id'] == '' || $rpt[0]['customer_id'] == null) && ($rpt[0]['saleCurID'] == '' || $rpt[0]['saleCurID'] == null)) {
            $pdo->rollback();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Error',
                'message' => 'Invalid residence data. Please try again.'
            ]);
            exit();
        } else {
            $getAccCur = $pdo->prepare("SELECT account_Name, curID FROM `accounts` WHERE account_ID = :accountID");
            $getAccCur->bindParam(':accountID', $_POST['Account_ID']);
            $getAccCur->execute();
            /* Fetch all of the remaining rows in the result set */
            $accCur = $getAccCur->fetchAll(\PDO::FETCH_ASSOC);
            
            // Get customer email for later use
            $getCustomerInfo = $pdo->prepare("SELECT c.customer_email, c.customer_name, r.passenger_name, 
                                                    cr.currencyName, r.sale_price 
                                               FROM customer c 
                                               JOIN residence r ON c.customer_id = r.customer_id 
                                               JOIN currency cr ON cr.currencyID = r.saleCurID 
                                              WHERE r.residenceID = :resID");
            $getCustomerInfo->bindParam(':resID', $_POST['ResID']);
            $getCustomerInfo->execute();
            $customerInfo = $getCustomerInfo->fetch(PDO::FETCH_ASSOC);
            
            if ($accCur[0]['account_Name'] == "Cash") {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,`accountID`,
                        `PaymentFor`, `remarks`) VALUES (:customer_id, :payment_amount, :currencyID, :staff_id, :accountID,
                        :PaymentFor, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
            } else if ($accCur[0]['account_Name'] != "Cash" && $accCur[0]['curID'] == $rpt[0]['saleCurID']) {
                // create prepared statement
                $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,`accountID`,
                        `PaymentFor`, `remarks`) VALUES (:customer_id, :payment_amount, :currencyID, :staff_id, :accountID,
                        :PaymentFor, :remarks)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $rpt[0]['customer_id']);
                $stmt->bindParam(':payment_amount', $_POST['Payment']);
                $stmt->bindParam(':currencyID', $rpt[0]['saleCurID']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':accountID', $_POST['Account_ID']);
                $stmt->bindParam(':PaymentFor', $_POST['ResID']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
            } else {
                $pdo->rollback();
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'Error',
                    'message' => 'Currencies do not match! Please select an account with matching currency.'
                ]);
                exit();
            }

            // Execute the prepared statement to save payment
            $stmt->execute();
            
            // Check if payment has completed the residence total amount
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
            
            if ($total[0]['total'] == 0) {
                $updateLockTran = "UPDATE residence SET residence.islocked = 1 WHERE residence.residenceID = :resID";
                $updateLockTranStmt = $pdo->prepare($updateLockTran);
                $updateLockTranStmt->bindParam(':resID', $_POST['ResID']);
                $updateLockTranStmt->execute();
            }
            
            // Send email notification if customer has email
            $emailSuccess = false;
            $emailMsg = 'Payment saved successfully.';
            
            if (!empty($customerInfo['customer_email'])) {
                try {
                    require 'vendor/autoload.php';
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'selabnadirydxb@gmail.com';
                    $mail->Password = 'qyzuznoxbrfmjvxa';
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    
                    // Format current date/time for email
                    $paymentDate = date('d M Y, h:i A');
                    
                    // Sender and recipient 
                    $mail->setFrom('selabnadirydxb@gmail.com', 'SN Travels');
                    $mail->addAddress($customerInfo['customer_email'], $customerInfo['customer_name']);
                    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Residence Payment Confirmation - SN Travels';
                    
                    // Build email body with payment details
                    $emailBody = "
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <meta charset='utf-8'>
                            <meta name='viewport' content='width=device-width, initial-scale=1'>
                            <title>Payment Confirmation</title>
                            <style>
                                body { 
                                    font-family: 'Arial', sans-serif; 
                                    line-height: 1.6; 
                                    color: #444; 
                                    margin: 0;
                                    padding: 0;
                                    background-color: #f9f9f9;
                                }
                                
                                .email-container {
                                    max-width: 600px;
                                    margin: 0 auto;
                                    background-color: #ffffff;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                                }
                                
                                .email-header {
                                    background: #000000;
                                    color: white;
                                    padding: 30px 20px;
                                    text-align: center;
                                }
                                
                                .email-header h2 {
                                    margin: 0;
                                    font-weight: 600;
                                    font-size: 24px;
                                    letter-spacing: 0.5px;
                                }
                                
                                .email-content {
                                    padding: 30px;
                                }
                                
                                .details-table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-bottom: 30px;
                                    border-radius: 6px;
                                    overflow: hidden;
                                    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                                }
                                
                                .details-table th {
                                    background-color: #f2f2f2;
                                    padding: 12px 15px;
                                    text-align: left;
                                    font-weight: 600;
                                    color: #333;
                                    border-bottom: 1px solid #ddd;
                                }
                                
                                .details-table td {
                                    padding: 12px 15px;
                                    text-align: left;
                                    border-bottom: 1px solid #eee;
                                }
                            </style>
                        </head>
                        <body>
                            <div class='email-container'>
                                <div class='email-header'>
                                    <div class='logo'>SN TRAVELS</div>
                                    <h2>Payment Confirmation</h2>
                                </div>
                                
                                <div class='email-content'>
                                    <p>Dear {$customerInfo['customer_name']},</p>
                                    
                                    <p>Thank you for your payment. We are pleased to confirm that we have received your payment successfully.</p>
                                    
                                    <h3>Payment Details</h3>
                                    
                                    <table class='details-table'>
                                        <tr>
                                            <th>Payment Date</th>
                                            <td>{$paymentDate}</td>
                                        </tr>
                                        <tr>
                                            <th>Passenger Name</th>
                                            <td>{$customerInfo['passenger_name']}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount Paid</th>
                                            <td>{$_POST['Payment']} {$customerInfo['currencyName']}</td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td>{$_POST['Remarks']}</td>
                                        </tr>
                                    </table>
                                    
                                    <p>If you have any questions or need further assistance, please contact us at +97143237879 or info@sntrips.com</p>
                                    
                                    <p>Thank you for choosing SN Travels.</p>
                                    
                                    <div>
                                        Best regards,<br>
                                        SN Travels Team
                                    </div>
                                </div>
                            </div>
                        </body>
                        </html>";

                    $mail->Body = $emailBody;
                    $mail->AltBody = "Payment confirmation for {$customerInfo['passenger_name']}. Amount: {$_POST['Payment']} {$customerInfo['currencyName']}. Date: {$paymentDate}. Contact us at +97143237879 or info@sntrips.com";

                    $mail->send();
                    $emailSuccess = true;
                    $emailMsg = 'Payment saved and email sent successfully.';
                } catch (Exception $e) {
                    $emailMsg = 'Payment saved successfully. Note: Failed to send email: ' . $mail->ErrorInfo;
                }
            } else {
                $emailMsg = 'Payment saved successfully. Note: Customer email not available.';
            }
            
            // Commit database transaction
            $pdo->commit();
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'Success',
                'message' => $emailMsg,
                'email_sent' => $emailSuccess
            ]);
        }
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollback();
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'Error',
            'message' => "Failed to process payment: " . $e->getMessage()
        ]);
    }
}
?>
