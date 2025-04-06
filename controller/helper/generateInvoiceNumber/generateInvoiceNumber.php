<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('location:../../../login.php');
    }
    function generateInvoiceNumber($conn){
        $random_number = rand(1000, 9999); // generate a random number between 1000 and 9999
        // generate the receipt number with today's date and the random number
        $invoiceNumber = 'SN-RPT-' . date('dmy') . '-' . $random_number;
        // check if the receipt number already exists in the database
        $UniqueInvSql = "SELECT COUNT(*) AS count FROM invoice WHERE invoiceNumber = :invoiceNumber";
        $UniqueInvStmt = $conn->prepare($UniqueInvSql);
        $UniqueInvStmt->bindParam(':invoiceNumber', $invoiceNumber);
        $UniqueInvStmt->execute();
        $count = $UniqueInvStmt->fetchColumn();
            while ($count > 0) {
                // if the receipt number already exists, generate a new random number and try again
                $random_number = rand(1000, 9999);
                $invoiceNumber = 'SN-RPT-' . date('dmy') . '-' . $random_number;
                
                $UniqueInvSql = "SELECT COUNT(*) AS count FROM invoice WHERE invoiceNumber = :invoiceNumber";
                $UniqueInvStmt = $conn->prepare($UniqueInvSql);
                $UniqueInvStmt->bindParam(':invoiceNumber', $invoiceNumber);
                $UniqueInvStmt->execute();
                $count = $UniqueInvStmt->fetchColumn();
            }
        return $invoiceNumber;
    }


?>