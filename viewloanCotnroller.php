<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Loan' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0){
    echo "<script>window.location.href='pageNotFound.php'</script>";
    }
    if(isset($_POST['SELECT_CUSTOMER'])){
        if($_POST['Type'] == "byAll"){
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name FROM customer ORDER BY customer_name ASC");
            $selectQuery->execute();
        }else{
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name,(SELECT DISTINCT customer_id FROM loan WHERE customer_id =:customer_id) AS 
            selectedCustomer FROM customer ORDER BY customer_name ASC");
            $selectQuery->bindParam(':customer_id', $_POST['ID']);
            $selectQuery->execute();
        }
        
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetTotal'])){
        if($_POST['SearchTerm'] == 'DateAndCusWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(amount) AS amount,currency.currencyName FROM `loan` INNER JOIN 
            currency ON currency.currencyID = loan.currencyID WHERE loan.customer_id = :customer_id AND DATE(datetime) BETWEEN 
            :fromdate AND :todate   GROUP BY currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(amount) AS amount,currency.currencyName FROM `loan` INNER JOIN 
            currency ON currency.currencyID = loan.currencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate   GROUP BY 
            currency.currencyName) AS baseTable WHERE amount !=0; ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'CustWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(amount) AS amount,currency.currencyName FROM `loan` INNER JOIN 
            currency ON currency.currencyID = loan.currencyID WHERE loan.customer_id = :customer_id GROUP BY currency.currencyName) AS baseTable WHERE amount !=0; ");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $total = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($total);
    }else if(isset($_POST['SearchLoan'])){
        if($_POST['SearchTerm'] == 'DateAndCusWise'){
            $selectQuery = $pdo->prepare("SELECT `loan_id`, customer.customer_name,`amount`, `currencyName`,
            `account_Name`,`datetime`, `remarks`,`staff_name` FROM `loan` INNER JOIN customer ON customer.customer_id = 
            loan.customer_id INNER JOIN staff ON staff.staff_id = loan.staffID INNER JOIN accounts ON accounts.account_ID =
            loan.accountID INNER JOIN currency ON currency.currencyID = loan.currencyID   WHERE loan.customer_id = :customer_id
            AND DATE(datetime) BETWEEN :fromdate AND :todate  ORDER BY loan_id DESC ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT `loan_id`, customer.customer_name,`amount`,`currencyName`,
            `accountID`, `datetime`, `remarks`,`staff_name`,account_Name FROM `loan` INNER JOIN customer ON customer.customer_id
            = loan.customer_id INNER JOIN staff ON staff.staff_id = loan.staffID INNER JOIN accounts ON accounts.account_ID = 
            loan.accountID INNER JOIN currency ON currency.currencyID = loan.currencyID
            WHERE DATE(datetime) BETWEEN :fromdate AND :todate  ORDER BY loan_id DESC ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'CustWise'){
            $selectQuery = $pdo->prepare("SELECT `loan_id`, customer.customer_name,amount,`currencyName`,
            `accountID`, `datetime`, `remarks`,`staff_name`,account_Name FROM `loan` INNER JOIN customer ON customer.customer_id 
            = loan.customer_id INNER JOIN staff ON staff.staff_id = loan.staffID INNER JOIN accounts ON accounts.account_ID = 
            loan.accountID INNER JOIN currency ON currency.currencyID = loan.currencyID
            WHERE loan.customer_id = :customer_id ORDER BY loan_id DESC ");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $loan = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($loan);
    }else if(isset($_POST['Delete'])){
        try{
                  if($delete == 1){
                        $sql = "DELETE FROM loan WHERE loan_id = :loan_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':loan_id', $_POST['LoanID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "NoPermission";
                }
               
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdLoan'])){
            $selectQuery = $pdo->prepare("SELECT * FROM loan WHERE loan_id=:loan_id");
            $selectQuery->bindParam(':loan_id', $_POST['Loan_ID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateLoan'])){
        try{
            if($update == 1){
                         $sql = "UPDATE `loan` SET customer_id =:customer_id, amount =:amount,currencyID = :currencyID, 
                         remarks=:remarks, staffID =:staffID,accountID=:accountID WHERE loan_id =:loan_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':customer_id', $_POST['Updcustomer_id']);
                         $stmt->bindParam(':amount', $_POST['Updamount']);
                         $stmt->bindParam(':currencyID', $_POST['Currency_Type']);
                         $stmt->bindParam(':remarks', $_POST['Updremarks']);
                         $stmt->bindParam(':staffID', $_SESSION['user_id']);
                         $stmt->bindParam(':accountID', $_POST['Updaccount_ID']);
                         $stmt->bindParam(':loan_id', $_POST['loanID']);
                         $stmt->execute();
                echo "Success";
            }else{
                echo "NoPermission";
            }
               
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>