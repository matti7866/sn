<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location:../../../login.php');
    }
    require_once '../../../api/connection/index.php';
    require_once '../../helper/date/dateformat.php';
    require_once '../../helper/validator/validator.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Payment' ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select =  $records[0]['select'];
    $insert = $records[0]['insert'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0 && $insert == 0 && $update == 0 && $delete == 0){
        header('location:../../../views/customer/error_pages/permissionDenied.php');
    }
    // get customer today's customer payments
    if(isset($_POST['SearchTerm']) && $_POST['SearchTerm'] === "TodaysPayment" ){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $todaysCustomerPayments = $CustomerPayment->TodaysPayment();
            // Return the results as JSON
            echo json_encode($todaysCustomerPayments);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    // get the customer payments date and customer wise
    }else if(isset($_POST['SearchTerm']) && $_POST['SearchTerm'] === "DateAndCusWise" ){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $PaymentsDateAndCusWise = $CustomerPayment->dateAndCusWise(formatToSQLDate($_POST['Fromdate']), 
            formatToSQLDate($_POST['Todate']), $_POST['CustomerID']);
            // Return the results as JSON
            echo json_encode($PaymentsDateAndCusWise);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // get the customer payments date wise
    else if(isset($_POST['SearchTerm']) && $_POST['SearchTerm'] === "DateWise" ){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $PaymentsDateWise = $CustomerPayment->dateWise(formatToSQLDate($_POST['Fromdate']), 
            formatToSQLDate($_POST['Todate']));
            // Return the results as JSON
            echo json_encode($PaymentsDateWise);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // get the customer payments customer wise
    else if(isset($_POST['SearchTerm']) && $_POST['SearchTerm'] === "CusWise" ){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $PaymentsCusWise = $CustomerPayment->CusWise($_POST['CustomerID']);
            // Return the results as JSON
            echo json_encode($PaymentsCusWise);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // get particular customer pending payment 
    else if(isset($_POST['GetCustomerPendingPayment']) && $_POST['GetCustomerPendingPayment'] === "GetCustomerPendingPayment" ){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $customerPendingPayment = $CustomerPayment->GetCustomerPendingPayment($_POST['CustomerID']);
            // Return the results as JSON
            echo json_encode($customerPendingPayment);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // insert customer payment 
    else if(isset($_POST['MakePayment']) && $_POST['MakePayment'] === "MakePayment" ){
        try{
            $conn->beginTransaction();
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($insert != 1) {
                throw new \Exception('The user has no permission to insert customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // Initailze validation class
            $validator = new Validator($conn);
            // the validation flag stores in customer and account variables
            $customer = intval($validator->customer(intval($_POST['CustomerID'])));
            $account = intval($validator->account(intval($_POST['AccountID'])));
            if($customer !== 1){
                throw new \Exception('Validation Error: Sorry, we could not find the selected customer.');
            }
            if($account !== 1){
                throw new \Exception('Validation Error: Sorry, we could not find the selected account.');
            }
            if (!is_numeric(intval($_POST['PaymentAmount']))) {
                throw new \Exception('Validation Error: Payment amount should be number.');
            }
            if (intval($_POST['PaymentAmount']) < 1) {
                throw new \Exception('Validation Error: Payment amount should be greater than 1.');
            }
            
            // now we get the account name to check if account is cash
            require_once '../../../api/accounts/account-management/index.php';
            // we create instance of account
            $acc = new Account($conn);
            $accountName = $acc->getAccountName(intval($_POST['AccountID']));
            // declare variable for storing the currency 
            $currency = null;
            // we check if the acc is cash so we can validate the currency
            if(strtolower($accountName) === 'cash'){
                // validate the currency
                $curValidator = intval($validator->currency(intval($_POST['CurrencyID'])));
                // we check if the validation failed then we exit the execution flow
                if($curValidator !== 1){
                    throw new \Exception('Validation Error: Sorry, we could not find the selected currency.');
                }
                $currency = intval($_POST['CurrencyID']);
            }else{
                // we get the currency of the account then validate again
                 $parsedAcoount = intval($_POST['AccountID']);
                 // prepare the statement
                 $selectQuery = $conn->prepare("SELECT DISTINCT curID FROM `accounts` WHERE 
                 account_ID = :account_ID ");
                 // bind the param
                 $selectQuery->bindParam(':account_ID', $parsedAcoount);
                 // execute the query
                 $selectQuery->execute();
                 // Fetch account name
                 $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                 // return the account name
                 $cur =  intval($result['curID']);
                // validate the currency
                $curValidator = intval($validator->currency($cur));
                // we check if the validation failed then we exit the execution flow
                if($curValidator !== 1){
                    throw new \Exception('Validation Error: Sorry, we could not find the selected currency.');
                }
                $currency = $cur;
            }
            // call the get TodaysPayment method
            $MakePayment = $CustomerPayment->MakePayment(intval($_POST['CustomerID']),intval($_POST['PaymentAmount'])
            ,intval($_POST['AccountID']), $currency, $_POST['Remarks']);
            // commit transaction 
            $conn->commit();
            // Return the results as JSON
            echo json_encode(array('message' => $MakePayment ));
        }catch (\Throwable $th) {
            //throw $th;
            $conn->rollback();
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    else if(isset($_POST['EditCustomerPayment']) && $_POST['EditCustomerPayment'] === "EditCustomerPayment"){
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($update != 1) {
                throw new \Exception('The user has no permission to update customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $editCustomerPayment = $CustomerPayment->EditCustomerPayment($_POST['ID']);
            // Return the results as JSON
            echo json_encode($editCustomerPayment);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    else if(isset($_POST['Delete']) && $_POST['Delete'] === "Delete"){
        try{
            $conn->beginTransaction();
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($delete != 1) {
                throw new \Exception('The user has no permission to delete customer payment report');
            }
            // get the data from API
            // Initailze validation class
            $validator = new Validator($conn);
            $paymentID = intval($validator->paymentID(intval($_POST['ID'])));
            if($paymentID !== 1){
                throw new \Exception('Validation Error: Something went wong try again.');
            }
            // get data from api
            require_once '../../../api/customers/receipt/receipt.php';
            $Receipt = new Receipt($conn);
            $receiptID = $Receipt->GetReceiptIDByPaymentID($_POST['ID']);
            // get data from api
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // call the get TodaysPayment method
            $deletePayment = $CustomerPayment->DeletePayment($_POST['ID']);
           
            
            if($receiptID !=null){
                $file = $Receipt->GetReceiptDocumentName($receiptID);
                $deleteInvoiceFlag = $Receipt->DeletePaymentReceipt($receiptID);
                if($deleteInvoiceFlag == "Success"){
                    if(file_exists($file)){
                        unlink($file);
                    }
                }
            }
                $conn->commit();
                // Return the results as JSON
                echo json_encode(array('message' => "Success" ));
        }catch (\Throwable $th) {
            //throw $th;
            $conn->rollback();
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // update customer payment 
    else if(isset($_POST['UpdatePayment']) && $_POST['UpdatePayment'] === "UpdatePayment" ){
        try{
            $conn->beginTransaction();
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($update != 1) {
                throw new \Exception('The user has no permission to update customer payment report');
            }
            // get the data from API
            require_once '../../../api/customers/customer-payment/customer-payment.php';
            // create instance of CustomerPayment class
            $CustomerPayment = new CustomerPayment($conn);
            // Initailze validation class
            $validator = new Validator($conn);
            // the validation flag stores in customer and account variables
            $customer = intval($validator->customer(intval($_POST['CustomerID'])));
            $account = intval($validator->account(intval($_POST['AccountID'])));
            $paymentID = intval($validator->paymentID(intval($_POST['PaymentID'])));
            if($customer !== 1){
                throw new \Exception('Validation Error: Sorry, we could not find the selected customer.');
            }
            if($account !== 1){
                throw new \Exception('Validation Error: Sorry, we could not find the selected account.');
            }
            if($paymentID !== 1){
                throw new \Exception('Something went wrong! Please refresh the page and try again.');
            }
            if (!is_numeric(intval($_POST['PaymentAmount']))) {
                throw new \Exception('Validation Error: Payment amount should be number.');
            }
            if (intval($_POST['PaymentAmount']) < 1) {
                throw new \Exception('Validation Error: Payment amount should be greater than 1.');
            }
            // now we get the account name to check if account is cash
            require_once '../../../api/accounts/account-management/index.php';
            // we create instance of account
            $acc = new Account($conn);
            $accountName = $acc->getAccountName(intval($_POST['AccountID']));
            // declare variable for storing the currency 
            $currency = null;
            // we check if the acc is cash so we can validate the currency
            if(strtolower($accountName) === 'cash'){
                // validate the currency
                $curValidator = intval($validator->currency(intval($_POST['CurrencyID'])));
                // we check if the validation failed then we exit the execution flow
                if($curValidator !== 1){
                    throw new \Exception('Validation Error: Sorry, we could not find the selected currency.');
                }
                $currency = intval($_POST['CurrencyID']);
            }else{
                // we get the currency of the account then validate again
                 $parsedAcoount = intval($_POST['AccountID']);
                 // prepare the statement
                 $selectQuery = $conn->prepare("SELECT DISTINCT curID FROM `accounts` WHERE 
                 account_ID = :account_ID ");
                 // bind the param
                 $selectQuery->bindParam(':account_ID', $parsedAcoount);
                 // execute the query
                 $selectQuery->execute();
                 // Fetch account name
                 $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                 // return the account name
                 $cur =  intval($result['curID']);
                // validate the currency
                $curValidator = intval($validator->currency($cur));
                // we check if the validation failed then we exit the execution flow
                if($curValidator !== 1){
                    throw new \Exception('Validation Error: Sorry, we could not find the selected currency.');
                }
                $currency = $cur;
            }
            // call the get TodaysPayment method
            $UpdatePayment = $CustomerPayment->UpdatePayment(intval($_POST['PaymentID']),intval($_POST['CustomerID']),
            intval($_POST['PaymentAmount']) ,intval($_POST['AccountID']), $currency, $_POST['Remarks']);
            // commit transaction 
            $conn->commit();
            // Return the results as JSON
            echo json_encode(array('message' => $UpdatePayment ));
        }catch (\Throwable $th) {
            //throw $th;
            $conn->rollback();
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    
    
    // Close connection
    unset($conn); 
?>