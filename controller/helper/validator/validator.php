<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location:../../../login.php');
    }
    class Validator{
        // Intailzing connection inside class constructor
        public function __construct($conn){
            try {
                $this->conn = $conn;
            } catch (\Throwable $th) {
               return 0;
            }
        }
        // The function checks if cusotmer with the given ID exists in database
        public function customer($customerID){
            try {
                // prepare the query 
                $selectQuery = $this->conn->prepare("SELECT DISTINCT customer_id FROM customer WHERE customer_id
                = :customer_id");
                // bind Params 
                $selectQuery->bindParam(':customer_id', $customerID);
                // execute the query
                $selectQuery->execute();
                /* Fetch all rows in the result set */
                $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    // A row was returned, so the customer ID exists in the database
                    if ($result['customer_id'] == $customerID) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    // No rows were returned, so the customer ID doesn't exist in the database
                    return 0;
                }
            } catch (\Throwable $th) {
               return 0;
            }
        }
        // The function checks if account with the given ID exists in database
        public function account($accountID){
            try {
                // prepare the query 
                $selectQuery = $this->conn->prepare("SELECT DISTINCT account_ID FROM accounts WHERE account_ID
                = :account_ID");
                // bind Params 
                $selectQuery->bindParam(':account_ID', $accountID);
                // execute the query
                $selectQuery->execute();
                /* Fetch all rows in the result set */
                $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    // A row was returned, so the customer ID exists in the database
                    if ($result['account_ID'] == $accountID) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    // No rows were returned, so the customer ID doesn't exist in the database
                    return 0;
                }
            } catch (\Throwable $th) {
               return 0;
            }
        }
        // The function checks if currency with the given ID exists in database
        public function currency($currencyID){
            try {
                // prepare the query 
                $selectQuery = $this->conn->prepare("SELECT DISTINCT currencyID FROM currency WHERE currencyID
                = :currencyID");
                // bind Params 
                $selectQuery->bindParam(':currencyID', $currencyID);
                // execute the query
                $selectQuery->execute();
                /* Fetch all rows in the result set */
                $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    // A row was returned, so the customer ID exists in the database
                    if ($result['currencyID'] == $currencyID) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    // No rows were returned, so the customer ID doesn't exist in the database
                    return 0;
                }
            } catch (\Throwable $th) {
                return 0;
            }
        }
        // The function checks if payment  with the given ID exists in database
        public function paymentID($paymentID){
            try {
                // prepare the query 
                $selectQuery = $this->conn->prepare("SELECT DISTINCT pay_id FROM customer_payments WHERE pay_id
                = :pay_id");
                // bind Params 
                $selectQuery->bindParam(':pay_id', $paymentID);
                // execute the query
                $selectQuery->execute();
                /* Fetch all rows in the result set */
                $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    // A row was returned, so the customer ID exists in the database
                    if ($result['pay_id'] == $paymentID) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    // No rows were returned, so the customer ID doesn't exist in the database
                    return 0;
                }
            } catch (\Throwable $th) {
                return 0;
            }
        }
          // The function checks if invoice  with the given ID exists in database
          public function InvoiceID($InvoiceID){
            try {
                // prepare the query 
                $selectQuery = $this->conn->prepare("SELECT DISTINCT invoiceID FROM invoice WHERE invoiceID
                = :invoiceID");
                // bind Params 
                $selectQuery->bindParam(':invoiceID', $InvoiceID);
                // execute the query
                $selectQuery->execute();
                /* Fetch all rows in the result set */
                $result = $selectQuery->fetch(\PDO::FETCH_ASSOC);
                if ($result) {
                    // A row was returned, so the customer ID exists in the database
                    if ($result['invoiceID'] == $InvoiceID) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    // No rows were returned, so the customer ID doesn't exist in the database
                    return 0;
                }
            } catch (\Throwable $th) {
                return 0;
            }
        }

      
      
    }


?>