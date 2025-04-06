<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Ticket' ";
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
if(isset($_POST['INSERT'])){
        try{
            // create prepared statement
            $sql = "INSERT INTO customer (customer_name, customer_phone, customer_whatsapp, customer_address) 
            VALUES (:customer_name, :customer_phone, :customer_whatsapp, :customer_address)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':customer_name', $_POST['Cus_Name']);
            $stmt->bindParam(':customer_phone', $_POST['Cus_Phone']);
            $stmt->bindParam(':customer_whatsapp', $_POST['Cus_Whatsapp']);
            $stmt->bindParam(':customer_address', $_POST['Cus_Address']);
            // execute the prepared statement
            $stmt->execute();
            echo "Records inserted successfully.";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['SELECT_CUSTOMER'])){
        if($_POST['Type'] == "byAll"){
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name FROM customer ORDER BY customer_name ASC");
            $selectQuery->execute();
        }else{
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name,(SELECT customer_id FROM ticket WHERE ticket =:ticketID) AS 
            selectedCustomer FROM customer ORDER BY customer_name ASC");
            $selectQuery->bindParam(':ticketID', $_POST['TicketID']);
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
    }else if(isset($_POST['GetTicketReport'])){
                    $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
        $sql = 'CALL masterSearch(:searchTerm ,:fromdate,:todate,:Inp_customer_id,:PassengerName,:Inp_pnr,:dateOfTravel,
        :Inp_ticketNumber,:BranchID)';
        // prepare for execution of the stored procedure
        $stmt = $pdo->prepare($sql);
        // pass value to the command
        $stmt->bindParam(':searchTerm', $_POST['SearchTerm'], PDO::PARAM_STR);
        $stmt->bindParam(':fromdate', $_POST['Fromdate'], PDO::PARAM_STR);
        $stmt->bindParam(':todate', $_POST['Todate'], PDO::PARAM_STR);
        $stmt->bindParam(':Inp_customer_id', $_POST['Customer_ID'], PDO::PARAM_INT);
        $stmt->bindParam(':PassengerName', $_POST['Passenger_Name'], PDO::PARAM_STR);
        $stmt->bindParam(':Inp_pnr', $_POST['Pnr'], PDO::PARAM_STR);
        $stmt->bindParam(':dateOfTravel', $_POST['Date_Of_Travel'], PDO::PARAM_STR);
        $stmt->bindParam(':Inp_ticketNumber', $_POST['Ticket_Number'], PDO::PARAM_STR);
        $stmt->bindParam(':BranchID', $branchID, PDO::PARAM_INT);
        $stmt->execute();
        /* Fetch all of the remaining rows in the result set */
        $ticketRpt = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($ticketRpt);
    }else if(isset($_POST['SELECT_Supplier'])){
        if($_POST['Type'] == 'addChangeDate'){
            if($_POST['TransType'] == "Issued"){
                $selectQuery = $pdo->prepare("SELECT supp_id, supp_name,(SELECT ticket.supp_id FROM ticket WHERE ticket = 
                :TicketID) AS selectedSup FROM supplier");
            }else{
                $selectQuery = $pdo->prepare("SELECT supp_id, supp_name,(SELECT supplier FROM datechange WHERE change_id = 
                :TicketID) AS selectedSup FROM supplier");
            }
           
            $selectQuery->bindParam(':TicketID', $_POST['TicketID']);
        }else if($_POST['Type'] == 'getUpdSupplier'){
            if($_POST['TransType'] == "Issued"){
                $selectQuery = $pdo->prepare("SELECT supp_id, supp_name,(SELECT ticket.supp_id FROM ticket WHERE ticket = 
                :TicketID) AS selectedSup FROM supplier");
            }else{
                $selectQuery = $pdo->prepare("SELECT supp_id, supp_name,(SELECT datechange.supplier FROM datechange WHERE change_id = 
                :TicketID) AS selectedSup FROM supplier");
            }
            $selectQuery->bindParam(':TicketID', $_POST['TicketID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['SELECT_FROM'])){
        $selectQuery = $pdo->prepare("SELECT airport_id, airport_code,(SELECT from_id FROM ticket
        WHERE ticket=:ticketID) AS selectedFromID,(SELECT to_id FROM ticket WHERE ticket=:ticketID)
        AS selectedToID FROM airports");
        $selectQuery->bindParam(':ticketID', $_POST['TicketID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $from = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($from);
    }else if(isset($_POST['Upload_TicketPhoto'])){
        try{
            $image = upload_Image();
            //If Customer pays on the spot
                if($image == '')
                {
                    echo "Record not added becuase of file uploader";
                }else{
                    if($_POST['uploadType'] == "Issued"){
                        $sql = "UPDATE ticket SET ticketCopy =:ticketCopy WHERE ticket =:ticketID";
                    }else{
                        $sql = "UPDATE datechange SET changedTicket =:ticketCopy WHERE change_id =:ticketID";
                    }
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':ticketCopy', $image);
                $stmt->bindParam(':ticketID', $_POST['uploadTicketID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['addextended_date'])){
        $datetime = date("Y-m-d h:i:s");
        try{
            $ticketID = '';
            if($_POST['changeDateType'] =="Issued"){
                $ticketID  = $_POST['ticketID'];
            }else{
                $sql = "SELECT ticket_id FROM datechange WHERE  change_id = :change_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':change_id', $_POST['ticketID']);
                $stmt->execute();
                $ticketID =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $ticketID =  $ticketID[0]['ticket_id'];
            }
            $sql = "SELECT status FROM ticket WHERE ticket = :ticket_id ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ticket_id', $ticketID);
            $stmt->execute();
            $chkStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $chkStatus = $chkStatus[0]['status'];
            if($chkStatus != 3){
                $image = '';
                if($_FILES['extendedTicket']['name'] !=''){
                    $image = dateChangeupload_Image();
                    if($image == ''){
                        $image = 'Error';
                    }
                }
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }
                $Status = 2;
                $sql = "UPDATE `ticket` SET `status`= :Status WHERE ticket = :TicketID ";
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':Status', $Status);
                $stmt->bindParam(':TicketID', $_POST['ticketID']);
                $stmt->execute();
                $Status = 1;
                    $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
                sleep(1);
                if($image == ''){
                    $sql = "INSERT INTO `datechange`(`ticket_id`,`supplier`, `net_amount`,`netCurrencyID`, `sale_amount`,
                       `saleCurrencyID`,`remarks`, `extended_Date`,`ticketStatus`,`branchID`) VALUES (:ticket_id,:supplier,
                       :net_amount,:netCurrencyID,:sale_amount,:saleCurrencyID,:remarks,:extended_Date,:ticketStatus,:branchID)";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':ticket_id', $ticketID);
                        $stmt->bindParam(':supplier', $_POST['exsupplier']);
                        $stmt->bindParam(':net_amount', $_POST['exnet_price']);
                        $stmt->bindParam(':netCurrencyID', $_POST['dccNet_currency_type']);
                        $stmt->bindParam(':sale_amount', $_POST['exsale_price']);
                        $stmt->bindParam(':saleCurrencyID', $_POST['dccSale_currency_type']);
                        $stmt->bindParam(':remarks', $_POST['exremarks']);
                        $stmt->bindParam(':extended_Date', $_POST['extendedDate']);
                        $stmt->bindParam(':ticketStatus', $Status);
                       
                        $stmt->bindParam(':branchID', $branchID);
                }else{
                    $sql = "INSERT INTO `datechange`(`ticket_id`,`supplier`, `net_amount`, `sale_amount`, `remarks`, `extended_Date`,
                       `ticketStatus`,`changedTicket`,`branchID`) VALUES (:ticket_id,:supplier,:net_amount,:sale_amount,:remarks,
                       :extended_Date,:ticketStatus,:changedTicket,:branchID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                        $stmt->bindParam(':ticket_id', $ticketID);
                        $stmt->bindParam(':supplier', $_POST['exsupplier']);
                        $stmt->bindParam(':net_amount', $_POST['exnet_price']);
                        $stmt->bindParam(':sale_amount', $_POST['exsale_price']);
                        $stmt->bindParam(':remarks', $_POST['exremarks']);
                        $stmt->bindParam(':extended_Date', $_POST['extendedDate']);
                        $stmt->bindParam(':ticketStatus', $Status);
                        
                        $stmt->bindParam(':changedTicket', $image);
                        $stmt->bindParam(':branchID', $branchID);
                }
                // execute the prepared statement
                $stmt->execute();
            }else{
                $pdo->rollback();
            }

                
                $pdo->commit();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['DeleteFile'])){
        try{
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                if($_POST['Type'] == "Issued"){
                    $sql = "SELECT ticketCopy FROM ticket WHERE ticket.ticket = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['ticketCopy'];
                    if(file_exists($file)){
                        unlink($file);
                    }else{

                    }
                    if(!is_file($file)) {
                        $sql = "UPDATE ticket SET ticketCopy = NULL WHERE ticket.ticket = :ticket_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                        $stmt->execute();
                    }else{
                        $pdo->rollback();
                    }
                }else{
                    $sql = "SELECT changedTicket FROM datechange WHERE change_id = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['changedTicket'];
                    if(file_exists($file)){
                        unlink($file);
                    }else{

                    }
                    if(!is_file($file)) {
                    $sql = "UPDATE datechange SET changedTicket = NULL WHERE change_id = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    }else{
                        $pdo->rollback();
                    }
                }
            $pdo->commit();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['DeleteTicket'])){
        try{
                if($delete == 1)
                {
                     // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                if($_POST['Type'] == "Issued"){
                    $sql = "SELECT ticketCopy FROM ticket WHERE ticket.ticket = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['ticketCopy'];  
                    if(file_exists($file)){
                        unlink($file);
                    }else{

                    }            
                    if(!is_file($file)) {
                        $sql = "DELETE FROM ticket WHERE ticket.ticket = :ticket_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                        $stmt->execute();
                    }else{
                        $pdo->rollback();
                    }
                }else{
                    $sql = "SELECT changedTicket FROM datechange WHERE change_id = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['changedTicket'];
                    if(file_exists($file)){
                        unlink($file);
                    }else{

                    }
                    if(!is_file($file)) {
                        $sql = "SELECT ticket_id FROM datechange WHERE change_id  = :change_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':change_id', $_POST['TicketID']);
                        $stmt->execute();
                        $ticketID =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        $ticketID =  $ticketID[0]['ticket_id'];
                        // Delete from server
                        $sql = "DELETE FROM datechange WHERE change_id = :ticket_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                        $stmt->execute();
                        // Get One Status back for update
                        $sql = "SELECT ticketStatus FROM datechange WHERE  ticket_id = :ticketID ORDER BY ticket_id DESC LIMIT 1";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':ticketID', $ticketID);
                        $stmt->execute();
                        $ticketStatus =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        if($ticketStatus){
                            $ticketStatus =  $ticketStatus[0]['ticketStatus'];
                        }else{
                            $ticketStatus = '';
                        }
                        if($ticketStatus !=''){
                            sleep(1);
                            if($ticketStatus == 1){
                                $ticketStatus = 2;
                            }else if($ticketStatus ==2){
                                $ticketStatus = 3;
                            }
                            $sql = "UPDATE ticket SET status =:ticketStatus WHERE ticket = :ticketID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':ticketStatus', $ticketStatus);
                            $stmt->bindParam(':ticketID', $ticketID);
                            $stmt->execute();
                        }else{
                            sleep(1);
                            $sql = "UPDATE ticket SET status =1 WHERE ticket = :ticketID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':ticketID', $ticketID);
                            $stmt->execute();
                        }
                    }else{
                        $pdo->rollback();
                    }
                }
                $pdo->commit();
                echo "Success";
                }else{
                    echo "NoPermission";
                }
               
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['Refund'])){
        try{
                $datetime = date("Y-m-d h:i:s");
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                $Status = 3;
                $chkStatus = '';
                $ticketID = '';
                if($_POST['RfdType'] == "Issued"){
                    $ticketID = $_POST['TicketID'];
                }else{
                    $sql = "SELECT ticket_id FROM datechange WHERE change_id=:ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $_POST['TicketID']);
                    $stmt->execute();
                    $ticketID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $ticketID = $ticketID[0]['ticket_id'];
                }
                $sql = "SELECT status FROM ticket WHERE ticket = :ticket_id ";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ticket_id', $ticketID);
                $stmt->execute();
                $chkStatus = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $chkStatus = $chkStatus[0]['status'];
                if($chkStatus != 3){
                    $sql = "UPDATE `ticket` SET `status`= :Status WHERE ticket = :TicketID ";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':Status', $Status);
                    $stmt->bindParam(':TicketID', $ticketID);
                    $stmt->execute();
                    $Status = 2;
                    $curDate = date("Y-m-d");
                    $sql = "SELECT CASE WHEN (SELECT DISTINCT ticket_id FROM datechange WHERE ticket_id = :ticket_id) THEN 
                    (SELECT supplier FROM datechange WHERE ticket_id = :ticket_id ORDER BY datechange.change_id DESC LIMIT 1)
                    ELSE (SELECT ticket.supp_id FROM ticket WHERE ticket.ticket = :ticket_id) END AS supp_id FROM ticket 
                    WHERE ticket.ticket = :ticket_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':ticket_id', $ticketID);
                    $stmt->execute();
                    $suppID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $suppID = $suppID[0]['supp_id'];
                    // Insert in the dateChange 
                    sleep(1);
                    $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
                    $sql = "INSERT INTO `datechange`(`ticket_id`,`supplier`, `net_amount`,`netCurrencyID`, `sale_amount`,
                    `saleCurrencyID`, `remarks`, `extended_Date`,`ticketStatus`,`branchID`) VALUES (:ticket_id,:supplier,
                    :net_amount,:netCurrencyID,:sale_amount,:saleCurrencyID,:remarks,:extended_Date,:ticketStatus,:branchID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':ticket_id', $ticketID);
                    $stmt->bindParam(':supplier', $suppID);
                    $stmt->bindParam(':net_amount', $_POST['Net_Price']);
                    $stmt->bindParam(':netCurrencyID', $_POST['RfdcANet_currency_type']);
                    $stmt->bindParam(':sale_amount', $_POST['Sale_Price']);
                    $stmt->bindParam(':saleCurrencyID', $_POST['RfdcASale_currency_type']);
                    $stmt->bindParam(':remarks', $_POST['Remarks']);
                    $stmt->bindParam(':extended_Date', $curDate);
                    $stmt->bindParam(':ticketStatus', $Status);
                  
                    $stmt->bindParam(':branchID', $branchID);
                    // execute the prepared statement
                    $stmt->execute();
                }else{
                    $pdo->rollback();
                    echo "Something went wrong";
                }
                
                $pdo->commit(); 
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdTicket'])){
        if($_POST['Type'] == "Issued"){
            $selectQuery = $pdo->prepare("SELECT * FROM ticket WHERE ticket=:ticketID");
            $selectQuery->bindParam(':ticketID', $_POST['TicketID']);
            $selectQuery->execute();
        }else{
            $selectQuery = $pdo->prepare("SELECT * FROM datechange WHERE change_id=:ticketID");
            $selectQuery->bindParam(':ticketID', $_POST['TicketID']);
            $selectQuery->execute();
        }
        
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateTicket'])){
        try{
            if($update == 1){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                    $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $staffBranchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $staffBranchID = $staffBranchID[0]['staff_branchID'];
                    if($_POST['Type'] == "Issued"){
                        $sql = "UPDATE `ticket` SET ticketNumber=:ticketNum,Pnr=:pnr,customer_id=:customer_Id,
                        passenger_name=:passenger_name,date_of_travel=:date_of_travel,from_id=:from_id,to_id=:to_id,
                        sale=:sale,currencyID = :currencyID, staff_id=:staff_id,supp_id=:supp_id,net_price=:net_price,
                        net_CurrencyID=:net_CurrencyID,branchID=:branchID WHERE ticket=:ticketID ";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':ticketNum', $_POST['UpdTicketNum']);
                        $stmt->bindParam(':pnr', $_POST['UpdPnr']);
                        $stmt->bindParam(':customer_Id', $_POST['Updcustomer_id']);
                        $stmt->bindParam(':passenger_name', $_POST['UpdPassengerName']);
                        $stmt->bindParam(':date_of_travel', $_POST['UpddateOftravel']);
                        $stmt->bindParam(':from_id', $_POST['Updfrom']);
                        $stmt->bindParam(':to_id', $_POST['Updto']);
                        $stmt->bindParam(':sale', $_POST['UpdSale']);
                        $stmt->bindParam(':currencyID', $_POST['UpdSale_Currency_Type']);
                        $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                        $stmt->bindParam(':supp_id', $_POST['Updsupplier']);
                        $stmt->bindParam(':net_price', $_POST['UpdNet']);
                        $stmt->bindParam(':net_CurrencyID', $_POST['UpdNet_Currency_Type']);
                        $stmt->bindParam(':branchID', $staffBranchID);
                        $stmt->bindParam(':ticketID', $_POST['TicketID']);
                        $stmt->execute();
                    }else if($_POST['Type'] == "Date Change"){
                        $sql = "UPDATE `datechange` SET supplier=:supplier,net_amount=:net_amount,netCurrencyID = :netCurrencyID,
                        sale_amount=:sale_amount,saleCurrencyID = :saleCurrencyID,remarks=:remarks,extended_Date=:extended_Date,
                        branchID=:branchID WHERE change_id = :ticketID ";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':supplier', $_POST['Updexsupplier']);
                        $stmt->bindParam(':net_amount', $_POST['Updexnet_price']);
                        $stmt->bindParam(':netCurrencyID', $_POST['UpdDcNet_Currency_Type']);
                        $stmt->bindParam(':sale_amount', $_POST['Updexsale_price']);
                        $stmt->bindParam(':saleCurrencyID', $_POST['UpdDcSale_Currency_Type']);
                        $stmt->bindParam(':remarks', $_POST['Updexremarks']);
                        $stmt->bindParam(':extended_Date', $_POST['UpdextendedDate']);
                        $stmt->bindParam(':branchID', $staffBranchID);
                        $stmt->bindParam(':ticketID', $_POST['TicketID']);
                        $stmt->execute();
                    }else if($_POST['Type'] == "Refund"){
                        $sql = "UPDATE `datechange` SET net_amount=:net_amount,netCurrencyID = :netCurrencyID, 
                        sale_amount=:sale_amount,saleCurrencyID = :saleCurrencyID,remarks=:remarks,branchID=:branchID
                        WHERE change_id = :ticketID ";
                        $stmt = $pdo->prepare($sql);
                        // bind parameters to statement
                        $stmt->bindParam(':net_amount', $_POST['Updrfdnet_price']);
                        $stmt->bindParam(':netCurrencyID', $_POST['UpdRfdNet_Currency_Type']);
                        $stmt->bindParam(':sale_amount', $_POST['Updrfdsale_price']);
                        $stmt->bindParam(':saleCurrencyID', $_POST['UpdRfdSale_Currency_Type']);
                        $stmt->bindParam(':remarks', $_POST['Updrfdremarks']);
                        $stmt->bindParam(':branchID', $staffBranchID);
                        $stmt->bindParam(':ticketID', $_POST['TicketID']);
                        $stmt->execute();
                    } 
                $pdo->commit(); 
            echo "Success";
            }else{
                echo "NoPermission";
            }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetTicketInfo'])){
        if($_POST['Type'] == "Issued"){
            $selectQuery = $pdo->prepare("SELECT DISTINCT sale,net_price,currencyID,net_CurrencyID FROM ticket 
            WHERE ticket=:ticket_id");
            $selectQuery->bindParam(':ticket_id', $_POST['TicketID']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $info = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        }else{
                $sql = "SELECT ticket_id FROM datechange WHERE  change_id = :change_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':change_id', $_POST['TicketID']);
                $stmt->execute();
                $ticketID =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $ticketID =  $ticketID[0]['ticket_id'];
                $selectQuery = $pdo->prepare("SELECT DISTINCT sale,net_price,currencyID,net_CurrencyID FROM ticket 
                WHERE ticket=:ticket_id");
                $selectQuery->bindParam(':ticket_id', $ticketID);
                $selectQuery->execute();
                /* Fetch all of the remaining rows in the result set */
                $info = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        }
        
        // encoding array to json format
        echo json_encode($info);
    }
    function upload_Image(){
        $new_image_name = '';
        if($_FILES['uploader']['size']<=2097152){
            $extension = explode(".", $_FILES['uploader']['name']);
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($extension[1]), $ext))
            {
                $new_image_name = $extension[0] . '_' . date("Y/m/d h:i:s");
                $new_image_name = md5($new_image_name);
                $new_image_name = 'tickets/'. $new_image_name. '.' .$extension[1];
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploader']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    function dateChangeupload_Image(){
        $new_image_name = '';
        if($_FILES['extendedTicket']['size']<=2097152){
            $extension = explode(".", $_FILES['extendedTicket']['name']);
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($extension[1]), $ext))
            {
                $new_image_name = $extension[0] . '_' . date("Y/m/d h:i:s");
                $new_image_name = md5($new_image_name);
                $new_image_name = 'tickets/'. $new_image_name. '.' .$extension[1];
                $destination = $new_image_name;
                move_uploaded_file($_FILES['extendedTicket']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>