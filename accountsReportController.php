<?php 
// Start output buffering to prevent headers already sent errors
ob_start();

// Clean any previous output
if (ob_get_length()) {
    ob_clean();
}

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set content type for JSON responses only if headers haven't been sent
if (!headers_sent()) {
    header('Content-Type: application/json');
}

try {
    include 'connection.php';
} catch (Exception $e) {
    ob_clean();
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])){
  ob_clean();
  if (!headers_sent()) {
      http_response_code(401);
  }
  echo json_encode(['error' => 'Authentication required']);
  exit;
}

// Load the permission for the user
$rolId = $_SESSION['role_id'];
try {
    $result = $pdo->prepare("SELECT * FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ");
    $result->bindParam(':role_id', $rolId);
    $result->execute();
    $permission = $result->fetch(\PDO::FETCH_ASSOC);

    if( $permission['select'] != 1 ){
      ob_clean();
      if (!headers_sent()) {
          http_response_code(403);
      }
      echo json_encode(['error' => 'Access denied']);
      exit;
    }
} catch (Exception $e) {
    ob_clean();
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo json_encode(['error' => 'Permission check failed: ' . $e->getMessage()]);
    exit;
}

// Handle different actions
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'getDetailedTransactions');

try {
    if($action == 'exportExcel') {
      exportToExcel();
    } elseif($action == 'getAccountBalances') {
      $resetDate = isset($_POST['resetDate']) ? $_POST['resetDate'] : '';
      getAccountBalances($resetDate);
    } else {
      getDetailedTransactions();
    }
} catch (Exception $e) {
    ob_clean();
    if (!headers_sent()) {
        http_response_code(500);
    }
    echo json_encode(['error' => 'Operation failed: ' . $e->getMessage()]);
    exit;
}

function getDetailedTransactions() {
    global $pdo;
    
    $fromDate = isset($_POST['fromDate']) ? $_POST['fromDate'] : date('Y-m-01');
    $toDate = isset($_POST['toDate']) ? $_POST['toDate'] : date('Y-m-d');
    $accountFilter = isset($_POST['accountFilter']) ? $_POST['accountFilter'] : '';
    $typeFilter = isset($_POST['typeFilter']) ? $_POST['typeFilter'] : '';
    $resetDate = isset($_POST['resetDate']) ? $_POST['resetDate'] : '';
    
    // Build reset clause for residence transactions
    $resetClause = '';
    if($resetDate) {
      $resetClause = " AND DATE(r.datetime) >= '$resetDate'";
    }
    
    // If reset date is set, use it as the minimum date for all calculations
    if($resetDate && $resetDate > $fromDate) {
      $fromDate = $resetDate;
    }
    
    $transactions = [];
    
    // Get all account and currency info for lookup
    $accounts = [];
    $result = $pdo->query("SELECT account_ID, account_Name FROM accounts");
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $accounts[$row['account_ID']] = $row['account_Name'];
    }
    
    $currencies = [];
    $result = $pdo->query("SELECT currencyID, currencyName FROM currency");
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $currencies[$row['currencyID']] = $row['currencyName'];
    }
    
    // Build account filter condition
    $accountCondition = '';
    if($accountFilter) {
      $accountCondition = " AND accountID = " . intval($accountFilter);
    }
    
    // 1. CUSTOMER PAYMENTS (Credits - Money Coming In)
    if($typeFilter == '' || $typeFilter == 'credit') {
      $sql = "SELECT 
                cp.pay_id as id,
                cp.datetime as transaction_date,
                'Customer Payment' as transaction_type,
                'credit' as type_category,
                cp.accountID,
                cp.payment_amount as amount,
                cp.currencyID,
                cp.remarks,
                cp.customer_id as reference_id,
                CONCAT('Payment from ', COALESCE(c.customer_name, 'Unknown Customer'), ' (ID: ', cp.customer_id, ')') as description
              FROM customer_payments cp
              LEFT JOIN customer c ON cp.customer_id = c.customer_id
              WHERE DATE(cp.datetime) BETWEEN :fromDate AND :toDate" . $accountCondition;
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 2. DEPOSITS (Credits - Money Coming In)
    if($typeFilter == '' || $typeFilter == 'credit') {
      $sql = "SELECT 
                d.deposit_ID as id,
                d.datetime as transaction_date,
                'Deposit' as transaction_type,
                'credit' as type_category,
                d.accountID,
                d.deposit_amount as amount,
                d.currencyID,
                d.remarks,
                d.depositBy as reference_id,
                CONCAT('Deposited by ', COALESCE(s.staff_name, 'Unknown Staff'), ' (ID: ', d.depositBy, ')') as description
              FROM deposits d
              LEFT JOIN staff s ON d.depositBy = s.staff_id
              WHERE DATE(d.datetime) BETWEEN :fromDate AND :toDate" . $accountCondition;
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 3. LOANS (Debits - Money Going Out)
    if($typeFilter == '' || $typeFilter == 'debit') {
      $sql = "SELECT 
                l.loan_id as id,
                l.datetime as transaction_date,
                'Loan' as transaction_type,
                'debit' as type_category,
                l.accountID,
                l.amount,
                l.currencyID,
                l.remarks,
                l.customer_id as reference_id,
                CONCAT('Loan to ', COALESCE(c.customer_name, 'Unknown Customer'), ' (ID: ', l.customer_id, ')') as description
              FROM loan l
              LEFT JOIN customer c ON l.customer_id = c.customer_id
              WHERE DATE(l.datetime) BETWEEN :fromDate AND :toDate" . $accountCondition;
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 4. EXPENSES (Debits - Money Going Out)
    if($typeFilter == '' || $typeFilter == 'debit') {
      $sql = "SELECT 
                e.expense_id as id,
                e.time_creation as transaction_date,
                'Expense' as transaction_type,
                'debit' as type_category,
                e.accountID,
                e.expense_amount as amount,
                e.CurrencyID as currencyID,
                e.expense_remark as remarks,
                e.expense_type_id as reference_id,
                CONCAT('Expense: ', COALESCE(et.expense_type, 'Unknown Type'), ' (Type ID: ', e.expense_type_id, ')') as description
              FROM expense e
              LEFT JOIN expense_type et ON e.expense_type_id = et.expense_type_id
              WHERE DATE(e.time_creation) BETWEEN :fromDate AND :toDate" . str_replace('accountID', 'accountID', $accountCondition);
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 5. PAYMENTS (Debits - Money Going Out)
    if($typeFilter == '' || $typeFilter == 'debit') {
      $sql = "SELECT 
                p.payment_id as id,
                p.time_creation as transaction_date,
                'Supplier Payment' as transaction_type,
                'debit' as type_category,
                p.accountID,
                p.payment_amount as amount,
                p.currencyID,
                p.payment_detail as remarks,
                p.supp_id as reference_id,
                CONCAT('Payment to ', COALESCE(sp.supp_name, 'Unknown Supplier'), ' (ID: ', p.supp_id, ')') as description
              FROM payment p
              LEFT JOIN supplier sp ON p.supp_id = sp.supp_id
              WHERE DATE(p.time_creation) BETWEEN :fromDate AND :toDate" . $accountCondition;
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 6. SERVICE DETAILS (Debits - Money Going Out)
    if($typeFilter == '' || $typeFilter == 'debit') {
      $sql = "SELECT 
                sd.serviceDetailsID as id,
                sd.service_date as transaction_date,
                'Service Payment' as transaction_type,
                'debit' as type_category,
                sd.accoundID as accountID,
                sd.salePrice as amount,
                sd.saleCurrencyID as currencyID,
                sd.service_details as remarks,
                sd.customer_id as reference_id,
                CONCAT('Service for ', COALESCE(sd.passenger_name, COALESCE(c.customer_name, 'Unknown Customer')), ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
              FROM servicedetails sd
              LEFT JOIN customer c ON sd.customer_id = c.customer_id
              WHERE DATE(sd.service_date) BETWEEN :fromDate AND :toDate" . str_replace('accountID', 'accoundID', $accountCondition);
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 7. WITHDRAWALS (Debits - Money Going Out)
    if($typeFilter == '' || $typeFilter == 'debit') {
      $sql = "SELECT 
                w.withdrawal_ID as id,
                w.datetime as transaction_date,
                'Withdrawal' as transaction_type,
                'debit' as type_category,
                w.accountID,
                w.withdrawal_amount as amount,
                w.currencyID,
                w.remarks,
                w.withdrawalBy as reference_id,
                CONCAT('Withdrawal by ', COALESCE(s.staff_name, 'Unknown Staff'), ' (ID: ', w.withdrawalBy, ')') as description
              FROM withdrawals w
              LEFT JOIN staff s ON w.withdrawalBy = s.staff_id
              WHERE DATE(w.datetime) BETWEEN :fromDate AND :toDate" . $accountCondition;
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // 8. TRANSFERS (Special category - affects two accounts)
    if($typeFilter == '' || $typeFilter == 'transfer') {
      $sql = "SELECT 
                id,
                datetime as transaction_date,
                'Transfer Out' as transaction_type,
                'transfer' as type_category,
                from_account as accountID,
                amount,
                1 as currencyID,
                remarks,
                to_account as reference_id,
                CONCAT('Transfer to Account ID: ', to_account) as description
              FROM transfers 
              WHERE DATE(datetime) BETWEEN :fromDate AND :toDate";
      
      if($accountFilter) {
        $sql .= " AND (from_account = " . intval($accountFilter) . " OR to_account = " . intval($accountFilter) . ")";
      }
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':fromDate', $fromDate);
      $stmt->bindParam(':toDate', $toDate);
      $stmt->execute();
      $transfersOut = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // Add transfer in records
      foreach($transfersOut as $transfer) {
        $transferIn = $transfer;
        $transferIn['transaction_type'] = 'Transfer In';
        $transferIn['accountID'] = $transfer['reference_id'];
        $transferIn['reference_id'] = $transfer['accountID'];
        $transferIn['description'] = 'Transfer from Account ID: ' . $transfer['accountID'];
        $transactions[] = $transferIn;
      }
      
      $transactions = array_merge($transactions, $transfersOut);
    }
    
    // RESIDENCE TRANSACTIONS - Multiple types of deductions from accounts
    
    // Offer Letter Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.offerLetterDate as transaction_date,
                    'Residence - Offer Letter' as transaction_type,
                    'debit' as type_category,
                    r.offerLetterAccount as accountID,
                    r.offerLetterCost as amount,
                    r.offerLetterCostCur as currencyID,
                    CONCAT('MB#: ', r.mb_number) as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Offer Letter for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.offerLetterAccount IS NOT NULL 
                AND r.offerLetterCost > 0
                AND r.offerLetterDate IS NOT NULL
                AND DATE(r.offerLetterDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.offerLetterAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.offerLetterDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $offerLetterTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $transactions = array_merge($transactions, $offerLetterTransactions);
    }

    // Insurance Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.insuranceDate as transaction_date,
                    'Residence - Insurance' as transaction_type,
                    'debit' as type_category,
                    r.insuranceAccount as accountID,
                    r.insuranceCost as amount,
                    r.insuranceCur as currencyID,
                    'Insurance charge' as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Insurance for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.insuranceAccount IS NOT NULL 
                AND r.insuranceCost > 0
                AND r.insuranceDate IS NOT NULL
                AND DATE(r.insuranceDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.insuranceAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.insuranceDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Labour Card Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.laborCardDate as transaction_date,
                    'Residence - Labour Card' as transaction_type,
                    'debit' as type_category,
                    r.laborCardAccount as accountID,
                    r.laborCardFee as amount,
                    r.laborCardCur as currencyID,
                    CONCAT('Labour Card ID: ', r.laborCardID) as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Labour Card for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.laborCardAccount IS NOT NULL 
                AND r.laborCardFee > 0
                AND r.laborCardDate IS NOT NULL
                AND DATE(r.laborCardDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.laborCardAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.laborCardDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // E-Visa Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.eVisaDate as transaction_date,
                    'Residence - E-Visa' as transaction_type,
                    'debit' as type_category,
                    r.eVisaAccount as accountID,
                    r.eVisaCost as amount,
                    r.eVisaCur as currencyID,
                    'E-Visa processing' as remarks,
                    r.residenceID as reference_id,
                    CONCAT('E-Visa for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.eVisaAccount IS NOT NULL 
                AND r.eVisaCost > 0
                AND r.eVisaDate IS NOT NULL
                AND DATE(r.eVisaDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.eVisaAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.eVisaDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Change Status Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.changeStatusDate as transaction_date,
                    'Residence - Change Status' as transaction_type,
                    'debit' as type_category,
                    r.changeStatusAccount as accountID,
                    r.changeStatusCost as amount,
                    r.changeStatusCur as currencyID,
                    'Status change processing' as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Change Status for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.changeStatusAccount IS NOT NULL 
                AND r.changeStatusCost > 0
                AND r.changeStatusDate IS NOT NULL
                AND DATE(r.changeStatusDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.changeStatusAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.changeStatusDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Medical Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.medicalDate as transaction_date,
                    'Residence - Medical' as transaction_type,
                    'debit' as type_category,
                    r.medicalAccount as accountID,
                    r.medicalTCost as amount,
                    r.medicalTCur as currencyID,
                    'Medical test processing' as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Medical for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.medicalAccount IS NOT NULL 
                AND r.medicalTCost > 0
                AND r.medicalDate IS NOT NULL
                AND DATE(r.medicalDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.medicalAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.medicalDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Emirates ID Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.emiratesIDDate as transaction_date,
                    'Residence - Emirates ID' as transaction_type,
                    'debit' as type_category,
                    r.emiratesIDAccount as accountID,
                    r.emiratesIDCost as amount,
                    r.emiratesIDCur as currencyID,
                    CONCAT('Emirates ID: ', r.EmiratesIDNumber) as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Emirates ID for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.emiratesIDAccount IS NOT NULL 
                AND r.emiratesIDCost > 0
                AND r.emiratesIDDate IS NOT NULL
                AND DATE(r.emiratesIDDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.emiratesIDAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.emiratesIDDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Visa Stamping Costs (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    r.residenceID as id,
                    r.visaStampingDate as transaction_date,
                    'Residence - Visa Stamping' as transaction_type,
                    'debit' as type_category,
                    r.visaStampingAccount as accountID,
                    r.visaStampingCost as amount,
                    r.visaStampingCur as currencyID,
                    CONCAT('Expiry: ', r.expiry_date) as remarks,
                    r.residenceID as reference_id,
                    CONCAT('Visa Stamping for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residence r
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE r.visaStampingAccount IS NOT NULL 
                AND r.visaStampingCost > 0
                AND r.visaStampingDate IS NOT NULL
                AND DATE(r.visaStampingDate) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND r.visaStampingAccount = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(r.visaStampingDate) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Residence Fines (Debits)
    if ($typeFilter === '' || $typeFilter === 'debit') {
        $sql = "SELECT 
                    rf.residenceFineID as id,
                    rf.datetime as transaction_date,
                    'Residence - Fine' as transaction_type,
                    'debit' as type_category,
                    rf.accountID,
                    rf.fineAmount as amount,
                    rf.fineCurrencyID as currencyID,
                    'Residence fine imposed' as remarks,
                    rf.residenceFineID as reference_id,
                    CONCAT('Fine for ', r.passenger_name, ' (Customer: ', COALESCE(c.customer_name, 'Unknown'), ')') as description
                FROM residencefine rf
                LEFT JOIN residence r ON rf.residenceID = r.residenceID
                LEFT JOIN customer c ON r.customer_id = c.customer_id
                WHERE DATE(rf.datetime) BETWEEN :fromDate AND :toDate";
        
        if($accountFilter) {
            $sql .= " AND rf.accountID = " . intval($accountFilter);
        }
        if($resetDate) {
            $sql .= " AND DATE(rf.datetime) >= '$resetDate'";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // Sort transactions by date (newest first)
    usort($transactions, function($a, $b) {
      return strtotime($b['transaction_date']) - strtotime($a['transaction_date']);
    });
    
    // Calculate totals
    $totalCredits = 0;
    $totalDebits = 0;
    $totalTransfers = 0;
    
    foreach($transactions as $transaction) {
      if($transaction['type_category'] == 'credit') {
        $totalCredits += $transaction['amount'];
      } elseif($transaction['type_category'] == 'debit') {
        $totalDebits += $transaction['amount'];
      } elseif($transaction['type_category'] == 'transfer') {
        $totalTransfers += $transaction['amount'];
      }
    }
    
    $netBalance = $totalCredits - $totalDebits;
    
    // Generate HTML
    $html = '';
    if(count($transactions) == 0) {
      $html = '<tr><td colspan="9" class="text-center">No transactions found for the selected criteria</td></tr>';
    } else {
      foreach($transactions as $transaction) {
        $accountName = isset($accounts[$transaction['accountID']]) ? $accounts[$transaction['accountID']] : 'Unknown Account';
        $currencyName = isset($currencies[$transaction['currencyID']]) ? $currencies[$transaction['currencyID']] : 'N/A';
        
        $creditAmount = '';
        $debitAmount = '';
        $rowClass = '';
        
        if($transaction['type_category'] == 'credit') {
          $creditAmount = number_format($transaction['amount'], 2);
          $rowClass = 'table-success';
        } elseif($transaction['type_category'] == 'debit') {
          $debitAmount = number_format($transaction['amount'], 2);
          $rowClass = 'table-danger';
        } elseif($transaction['type_category'] == 'transfer') {
          if($transaction['transaction_type'] == 'Transfer In') {
            $creditAmount = number_format($transaction['amount'], 2);
            $rowClass = 'table-warning';
          } else {
            $debitAmount = number_format($transaction['amount'], 2);
            $rowClass = 'table-warning';
          }
        }
        
        $html .= '<tr class="' . $rowClass . '">';
        $html .= '<td>' . date('Y-m-d H:i', strtotime($transaction['transaction_date'])) . '</td>';
        $html .= '<td><span class="' . $transaction['type_category'] . '">' . $transaction['transaction_type'] . '</span></td>';
        $html .= '<td>' . $accountName . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['description']) . '</td>';
        $html .= '<td>' . $transaction['reference_id'] . '</td>';
        $html .= '<td class="credit">' . $creditAmount . '</td>';
        $html .= '<td class="debit">' . $debitAmount . '</td>';
        $html .= '<td>' . $currencyName . '</td>';
        $html .= '<td>' . htmlspecialchars($transaction['remarks'] ?? '') . '</td>';
        $html .= '</tr>';
      }
    }
    
    // Prepare response
    $response = [
      'html' => $html,
      'summary' => [
        'totalCredits' => number_format($totalCredits, 2),
        'totalDebits' => number_format($totalDebits, 2),
        'totalTransfers' => number_format($totalTransfers, 2),
        'netBalance' => number_format($netBalance, 2)
      ]
    ];
    
    // Clean output buffer and send JSON response
    ob_clean();
    echo json_encode($response);
    exit; // Ensure no additional output
  }

  function exportToExcel() {
    // Excel export functionality can be implemented here
    // For now, just redirect back or show a message
    echo "Excel export feature coming soon!";
  }

  function getAccountBalances($resetDate = '') {
    global $pdo;
    
    $sql = "SELECT 
                a.account_ID,
                a.account_Name,
                
                -- Total Credits
                COALESCE(credits.total_credits, 0) as total_credits,
                
                -- Total Debits (including all residence-related deductions)
                COALESCE(debits.total_debits, 0) as total_debits,
                
                -- Current Balance (Credits - Debits)
                (COALESCE(credits.total_credits, 0) - COALESCE(debits.total_debits, 0)) as current_balance
                
            FROM accounts a
            
            -- Credits Subquery
            LEFT JOIN (
                SELECT account_id, SUM(amount) as total_credits FROM (
                    -- Customer Payments
                    SELECT accountID as account_id, SUM(payment_amount) as amount
                    FROM customer_payments 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Deposits
                    SELECT accountID as account_id, SUM(deposit_amount) as amount
                    FROM deposits 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Transfer INs (Credits to destination account)
                    SELECT to_account as account_id, SUM(amount) as amount
                    FROM transfers 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY to_account
                ) credits_union
                GROUP BY account_id
            ) credits ON a.account_ID = credits.account_id
            
            -- Debits Subquery (including all residence-related transactions)
            LEFT JOIN (
                SELECT account_id, SUM(amount) as total_debits FROM (
                    -- Loans
                    SELECT accountID as account_id, SUM(amount) as amount
                    FROM loan 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Expenses
                    SELECT accountID as account_id, SUM(expense_amount) as amount
                    FROM expense 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(time_creation) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Payments
                    SELECT accountID as account_id, SUM(payment_amount) as amount
                    FROM payment 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(time_creation) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Service Details
                    SELECT accoundID as account_id, SUM(salePrice) as amount
                    FROM servicedetails 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(service_date) >= '$resetDate'" : "") . "
                    GROUP BY accoundID
                    
                    UNION ALL
                    
                    -- Withdrawals
                    SELECT accountID as account_id, SUM(withdrawal_amount) as amount
                    FROM withdrawals 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY accountID
                    
                    UNION ALL
                    
                    -- Transfer OUTs (Debits from source account)
                    SELECT from_account as account_id, SUM(amount) as amount
                    FROM transfers 
                    WHERE 1=1 " . ($resetDate ? " AND DATE(datetime) >= '$resetDate'" : "") . "
                    GROUP BY from_account
                    
                    UNION ALL
                    
                    -- RESIDENCE: Offer Letter Costs
                    SELECT offerLetterAccount as account_id, SUM(offerLetterCost) as amount
                    FROM residence 
                    WHERE offerLetterAccount IS NOT NULL AND offerLetterCost > 0 AND offerLetterDate IS NOT NULL " . ($resetDate ? " AND DATE(offerLetterDate) >= '$resetDate'" : "") . "
                    GROUP BY offerLetterAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Insurance Costs
                    SELECT insuranceAccount as account_id, SUM(insuranceCost) as amount
                    FROM residence 
                    WHERE insuranceAccount IS NOT NULL AND insuranceCost > 0 AND insuranceDate IS NOT NULL " . ($resetDate ? " AND DATE(insuranceDate) >= '$resetDate'" : "") . "
                    GROUP BY insuranceAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Labour Card Costs
                    SELECT laborCardAccount as account_id, SUM(laborCardFee) as amount
                    FROM residence 
                    WHERE laborCardAccount IS NOT NULL AND laborCardFee > 0 AND laborCardDate IS NOT NULL " . ($resetDate ? " AND DATE(laborCardDate) >= '$resetDate'" : "") . "
                    GROUP BY laborCardAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: E-Visa Costs
                    SELECT eVisaAccount as account_id, SUM(eVisaCost) as amount
                    FROM residence 
                    WHERE eVisaAccount IS NOT NULL AND eVisaCost > 0 AND eVisaDate IS NOT NULL " . ($resetDate ? " AND DATE(eVisaDate) >= '$resetDate'" : "") . "
                    GROUP BY eVisaAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Change Status Costs
                    SELECT changeStatusAccount as account_id, SUM(changeStatusCost) as amount
                    FROM residence 
                    WHERE changeStatusAccount IS NOT NULL AND changeStatusCost > 0 AND changeStatusDate IS NOT NULL " . ($resetDate ? " AND DATE(changeStatusDate) >= '$resetDate'" : "") . "
                    GROUP BY changeStatusAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Medical Costs
                    SELECT medicalAccount as account_id, SUM(medicalTCost) as amount
                    FROM residence 
                    WHERE medicalAccount IS NOT NULL AND medicalTCost > 0 AND medicalDate IS NOT NULL " . ($resetDate ? " AND DATE(medicalDate) >= '$resetDate'" : "") . "
                    GROUP BY medicalAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Emirates ID Costs
                    SELECT emiratesIDAccount as account_id, SUM(emiratesIDCost) as amount
                    FROM residence 
                    WHERE emiratesIDAccount IS NOT NULL AND emiratesIDCost > 0 AND emiratesIDDate IS NOT NULL " . ($resetDate ? " AND DATE(emiratesIDDate) >= '$resetDate'" : "") . "
                    GROUP BY emiratesIDAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Visa Stamping Costs
                    SELECT visaStampingAccount as account_id, SUM(visaStampingCost) as amount
                    FROM residence 
                    WHERE visaStampingAccount IS NOT NULL AND visaStampingCost > 0 AND visaStampingDate IS NOT NULL " . ($resetDate ? " AND DATE(visaStampingDate) >= '$resetDate'" : "") . "
                    GROUP BY visaStampingAccount
                    
                    UNION ALL
                    
                    -- RESIDENCE: Fines
                    SELECT rf.accountID as account_id, SUM(rf.fineAmount) as amount
                    FROM residencefine rf
                    WHERE 1=1 " . ($resetDate ? " AND DATE(rf.datetime) >= '$resetDate'" : "") . "
                    GROUP BY rf.accountID
                    
                ) debits_union
                GROUP BY account_id
            ) debits ON a.account_ID = debits.account_id
            
            ORDER BY a.account_Name";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '';
    foreach ($accounts as $account) {
        $balance = $account['current_balance'];
        $statusClass = '';
        $statusText = '';
        
        if ($balance > 0) {
            $statusClass = 'text-success';
            $statusText = 'Positive';
        } elseif ($balance < 0) {
            $statusClass = 'text-danger';
            $statusText = 'Negative';
        } else {
            $statusClass = 'text-secondary';
            $statusText = 'Zero';
        }
        
        $html .= "<tr>
                    <td>{$account['account_ID']}</td>
                    <td>{$account['account_Name']}</td>
                    <td class='credit'>" . number_format($account['total_credits'], 2) . "</td>
                    <td class='debit'>" . number_format($account['total_debits'], 2) . "</td>
                    <td class='$statusClass'><strong>" . number_format($balance, 2) . "</strong></td>
                    <td><span class='$statusClass'>$statusText</span></td>
                  </tr>";
    }
    
    // Clean output buffer and send JSON response
    ob_clean();
    echo json_encode(['html' => $html]);
    exit; // Ensure no additional output
  }
?>
