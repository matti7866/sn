<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location:../../../login.php');
    }
    require_once '../../../api/connection/index.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select =  $records[0]['select'];
    $insert = $records[0]['insert'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0 && $insert == 0 && $update == 0 && $delete == 0){
        http_response_code(500);
        echo json_encode(array('error' => 'All permission for accounts are denied for the user'));
        exit();
    }
    // populate account dropdown function
    if (isset($_GET['serverLabel']) && $_GET['serverLabel'] === 'Populate_Account_Dropdown') {
        try{
            // if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select accounts');
            }
            /* Get the search term from the q parameter, converter it to lower case and append % at start and end of it for SQL 
            format */
            $q  = $_GET['q'];
            // get the data from API
            require_once '../../../api/accounts/account-management/index.php';
            // create instance of customer
            $account = new Account($conn);
            // call getAccountDropdownOptions to get the list of accounts
            $accounts = $account->getAccountDropdownOptions($q);
            // Return the results as JSON
            http_response_code(200);
            echo json_encode($accounts);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
    // Get Currency by Account ID
    else if (isset($_POST['GetCurrencyByAccID']) && $_POST['GetCurrencyByAccID'] === 'GetCurrencyByAccID') {
        try{
            //if permission for selection of customer is restricted by database then it should return permission denied
            if ($select != 1) {
                throw new \Exception('The user has no permission to select accounts');
                exit();
            }
            // get the data from API
            require_once '../../../api/accounts/account-management/index.php';
            // create instance of customer
            $account = new Account($conn);
            // call getAccCurrencyByAccID to get the list of accounts
            $accounts = $account->getAccCurrencyByAccID($_POST['AccountID']);
            // Return the results as JSON
            http_response_code(200);
            echo json_encode($accounts);
        }catch (\Throwable $th) {
            //throw $th;
            http_response_code(500);
            echo json_encode(array('error' => $th->getMessage()));
        }
    }
   
    // Close connection
    unset($conn); 
?>