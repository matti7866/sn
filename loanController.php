<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Loan' ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':role_id', $_SESSION['role_id']);
	$stmt->execute();
	$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
	$insert = $insert[0]['insert'];
	if($insert == 0){
		echo "<script>window.location.href='pageNotFound.php'</script>";  
	}
if(isset($_POST['SaveLoan'])){
        try{
            // create prepared statement
            $sql = "INSERT INTO `loan`(`customer_id`, `amount`,`currencyID`, `remarks`,`staffID`,accountID) VALUES 
            (:customer_id,:amount,:currencyID,:remarks,:staffID,:accountID)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':customer_id', $_POST['Cust_Name']);
            $stmt->bindParam(':amount', $_POST['Amount']);
            $stmt->bindParam(':currencyID', $_POST['Currency_Type']);
            $stmt->bindParam(':remarks', $_POST['Remarks']);
            $stmt->bindParam(':staffID', $_SESSION['user_id']);
            $stmt->bindParam(':accountID', $_POST['Addaccount_ID']);
            // execute the prepared statement
            $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['SELECT_CUSTOMER'])){
        $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
        as customer_name FROM customer ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }
    // Close connection
    unset($pdo); 
?>