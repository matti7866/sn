<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['GetDateChgRefndRpt'])){
        try{
        if($_POST['Type'] == 'AllToday'){
            $selectQuery = $pdo->prepare("SELECT datechange.ticket_id as ticket,datechange.change_id AS PK,pnr,
            passenger_name,customer_name,datetime as date,datechange.sale_amount as sale,supplier.supp_id,supp_name,
            airports.airport_code AS from_code, to_airports.airport_code AS to_code,(CASE WHEN status = 2 THEN 
            'Date Changed' WHEN status = 3 THEN 'Refund' END) AS TransType FROM customer INNER JOIN ticket ON 
            customer.customer_id=ticket.customer_id INNER JOIN datechange ON datechange.ticket_id = ticket.ticket
            INNER JOIN supplier ON supplier.supp_id= datechange.supplier INNER JOIN airports ON airports.airport_id=
            ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id WHERE 
            datechange.extended_Date = CURRENT_DATE() AND CURRENT_DATE =  (SELECT datechange.extended_Date FROM datechange
            WHERE ticket = ticket ORDER BY datechange.extended_Date DESC LIMIT 1) ORDER BY datechange.ticket_id DESC LIMIT
            1");
        }else if($_POST['Type'] == 'Pnr'){
            $selectQuery = $pdo->prepare("SELECT DISTINCT ticket, CASE WHEN status = 1 THEN ticket.ticket ELSE (SELECT 
            datechange.change_id FROM datechange WHERE datechange.ticket_id = ticket ORDER BY change_id DESC limit 1) END
            AS PK, datetime as date,pnr,passenger_name,customer_name,(CASE WHEN status = 1 THEN (SELECT sale FROM ticket
            WHERE ticket.pnr LIKE CONCAT('%',:Pnr,'%') ) WHEN status = 2 THEN (SELECT sale_amount FROM datechange WHERE
            ticket_id = ticket.ticket AND ticketStatus = 1 ORDER BY change_id DESC LIMIT 1) WHEN status = 3 THEN (SELECT
            sale_amount FROM datechange WHERE ticket_id = ticket.ticket AND ticketStatus = 2 ORDER BY change_id DESC LIMIT
            1) END) AS sale,CASE WHEN status = 1 THEN (SELECT supp_name FROM supplier WHERE supplier.supp_id = 
            ticket.supp_id) ELSE (SELECT supp_name FROM supplier WHERE supplier.supp_id = (SELECT datechange.supplier FROM
            datechange WHERE ticket_id = ticket ORDER BY change_id DESC LIMIT 1)) END as supp_name,CASE WHEN status = 1 
            THEN (SELECT supp_id FROM supplier WHERE supplier.supp_id = ticket.supp_id) ELSE (SELECT supp_id FROM 
            supplier WHERE supplier.supp_id = (SELECT datechange.supplier FROM datechange WHERE ticket_id = ticket ORDER
            BY change_id DESC LIMIT 1)) END as supp_id,airports.airport_code AS from_code,to_airports.airport_code AS 
            to_code,(CASE WHEN status = 1 THEN 'Issued' WHEN status = 2 THEN 'Date Changed' WHEN status = 3 THEN 'Refund'
            END) AS TransType  FROM customer INNER JOIN ticket ON customer.customer_id=ticket.customer_id INNER JOIN 
            airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=
            ticket.to_id  WHERE ticket.pnr LIKE CONCAT('%',:Pnr,'%')");
            $selectQuery->bindParam(':Pnr', $_POST['SearchBy']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $datechange = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($datechange);
    }catch(PDOException $e){
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER BY supp_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['AddExtendedDate'])){
        $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
                    $datetime = date("Y-m-d h:i:s");
        try{
               
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                $sql = "SELECT status FROM ticket WHERE ticket = :ticket_id ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                $stmt->execute();
                $chkStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $chkStatus = $chkStatus[0]['status'];
                if($chkStatus != 3){
                    // Update status of ticket
                $Status = 2;
                $sql = "UPDATE `ticket` SET `status`= :Status WHERE ticket = :TicketID ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':Status', $Status);
                $stmt->bindParam(':TicketID', $_POST['TicketID']);
                $stmt->execute();
                $Status = 1;
                // Insert in the dateChange 
                $sql = "INSERT INTO `datechange`(`ticket_id`,`supplier`, `net_amount`, `sale_amount`, `remarks`, `extended_Date`,
                       `ticketStatus`, `branchID`) VALUES (:ticket_id,:supplier,:net_amount,:sale_amount,
                       :remarks,:extended_Date,:ticketStatus,:branchID)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                $stmt->bindParam(':supplier', $_POST['Supplier_ID']);
                $stmt->bindParam(':net_amount', $_POST['Net_Price']);
                $stmt->bindParam(':sale_amount', $_POST['Sale_Price']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
                $stmt->bindParam(':extended_Date', $_POST['Extend_Date']);
                $stmt->bindParam(':ticketStatus', $Status);
                $stmt->bindParam(':branchID', $branchID);
                // execute the prepared statement
                $stmt->execute();
                $pdo->commit(); 
                $mesg = array("msg"=> "Success");
                echo json_encode($mesg);
                }else{
                    $pdo->rollback();
                    $mesg = array("msg"=> "Something went wrong");
                    echo json_encode($mesg);
                }
                
        }catch(PDOException $e){
            $pdo->rollback();
            $mesg = array("msg"=> $sql." " . $e->getMessage());
            echo json_encode($mesg);
        }
    }else if(isset($_POST['Refund'])){
        $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
                    $datetime = date("Y-m-d h:i:s");
        try{
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                $sql = "SELECT status FROM ticket WHERE ticket = :ticket_id ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                $stmt->execute();
                $chkStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $chkStatus = $chkStatus[0]['status'];
                if($chkStatus != 3){
                    $Status = 3;
                $sql = "UPDATE `ticket` SET `status`= :Status WHERE ticket = :TicketID ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':Status', $Status);
                $stmt->bindParam(':TicketID', $_POST['TicketID']);
                $stmt->execute();
                $Status = 2;
                $curDate = date("Y-m-d");
                $sql = "SELECT CASE WHEN (SELECT DISTINCT ticket_id FROM datechange WHERE ticket_id = :ticket_id) THEN 
                (SELECT supplier FROM datechange WHERE ticket_id = :ticket_id ORDER BY datechange.change_id DESC LIMIT 1)
                ELSE (SELECT ticket.supp_id FROM ticket WHERE ticket.ticket = :ticket_id) END AS supp_id FROM ticket 
                WHERE ticket.ticket = :ticket_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                $stmt->execute();
                $suppID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $suppID = $suppID[0]['supp_id'];
                // Insert in the dateChange 
                $sql = "INSERT INTO `datechange`(`ticket_id`,`supplier`, `net_amount`, `sale_amount`, `remarks`, `extended_Date`,
                       `ticketStatus`,`branchID`) VALUES (:ticket_id,:supplier,:net_amount,:sale_amount,
                       :remarks,:extended_Date,:ticketStatus,:branchID)";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                $stmt->bindParam(':supplier', $suppID);
                $stmt->bindParam(':net_amount', $_POST['Net_Price']);
                $stmt->bindParam(':sale_amount', $_POST['Sale_Price']);
                $stmt->bindParam(':remarks', $_POST['Remarks']);
                $stmt->bindParam(':extended_Date', $curDate);
                $stmt->bindParam(':ticketStatus', $Status);
                $stmt->bindParam(':branchID', $branchID);
                // execute the prepared statement
                $stmt->execute();
                $pdo->commit(); 
                echo "Success";
                }else{
                    $pdo->rollback();
                    echo "Something went wrong";
                }
                
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>