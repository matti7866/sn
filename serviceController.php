<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Service' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
    
     if(isset($_POST['Select_Customer'])){
        $selectQuery = $pdo->prepare("SELECT * FROM `customer` ORDER BY customer_name ASC ");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['GetServices'])){
        $selectQuery = $pdo->prepare("SELECT * FROM `service` ORDER BY serviceName ASC ");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $services = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($services);
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER by supp_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['Select_Accounts'])){
        $selectQuery = $pdo->prepare("SELECT account_ID, account_Name FROM accounts ORDER by account_Name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $account_Name = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($account_Name);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetUploadedFiles'])){
        $selectQuery = $pdo->prepare("SELECT * FROM `servicedocuments` WHERE detailServiceID = :detailServiceID ORDER BY
        document_id DESC");
        $selectQuery->bindParam(':detailServiceID', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $uploadedDocuments = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($uploadedDocuments);
    }else if(isset($_POST['GetServiceReport'])){
            if($_POST['SearchTerm'] == 'cusAndServicePassenger'){
                $passengerName = '%'.  strtolower($_POST['SearchPassenger_name']) . '%'; 
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, 
                DATE(`service_date`) AS service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN 
                'bySupplier' ELSE 'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier
                WHERE supplier.supp_id = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = 
                servicedetails.accoundID) END AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON 
                service.serviceID = servicedetails.serviceID INNER JOIN customer ON customer.customer_id = 
                servicedetails.customer_id INNER JOIN currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN
                staff ON staff.staff_id = servicedetails.uploadedBy WHERE servicedetails.customer_id = :customer_id  AND 
                servicedetails.serviceID = :serviceID AND LOWER(servicedetails.passenger_name) LIKE :passenger_name ORDER BY 
                servicedetails.serviceDetailsID DESC ");
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
                $selectQuery->bindParam(':serviceID', $_POST['SearchServiceType']);
                $selectQuery->bindParam(':passenger_name', $passengerName);
            }
            else if($_POST['SearchTerm'] == 'cusAndService'){
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, 
                DATE(`service_date`) AS service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN
                'bySupplier' ELSE 'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier 
                WHERE supplier.supp_id = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = 
                servicedetails.accoundID) END AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON 
                service.serviceID = servicedetails.serviceID INNER JOIN customer ON customer.customer_id = 
                servicedetails.customer_id INNER JOIN currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN
                staff ON staff.staff_id = servicedetails.uploadedBy WHERE servicedetails.customer_id = :customer_id  AND 
                servicedetails.serviceID = :serviceID  ORDER BY servicedetails.serviceDetailsID DESC ");
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
                $selectQuery->bindParam(':serviceID', $_POST['SearchServiceType']);
            }else if($_POST['SearchTerm'] == 'cus'){
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, DATE(`service_date`) AS
                service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN 'bySupplier' ELSE 
                'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier WHERE supplier.supp_id
                = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = servicedetails.accoundID) END 
                AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON service.serviceID = 
                servicedetails.serviceID INNER JOIN customer ON customer.customer_id = servicedetails.customer_id INNER JOIN 
                currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN staff ON staff.staff_id = 
                servicedetails.uploadedBy WHERE servicedetails.customer_id = :customer_id ORDER BY servicedetails.serviceDetailsID
                DESC ");
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
            }else if($_POST['SearchTerm'] == 'service'){
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, DATE(`service_date`) AS
                service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN 'bySupplier' ELSE 
                'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier WHERE supplier.supp_id
                = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = servicedetails.accoundID) END 
                AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON service.serviceID = 
                servicedetails.serviceID INNER JOIN customer ON customer.customer_id = servicedetails.customer_id INNER JOIN 
                currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN staff ON staff.staff_id = 
                servicedetails.uploadedBy WHERE servicedetails.serviceID = :serviceID  ORDER BY servicedetails.serviceDetailsID
                DESC ");
                $selectQuery->bindParam(':serviceID', $_POST['SearchServiceType']);
            }else if($_POST['SearchTerm'] == 'servicePassenger'){
                $passengerName = '%'.  strtolower($_POST['SearchPassenger_name']) . '%';
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, DATE(`service_date`) AS
                service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN 'bySupplier' ELSE 
                'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier WHERE supplier.supp_id
                = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = servicedetails.accoundID) END 
                AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON service.serviceID = 
                servicedetails.serviceID INNER JOIN customer ON customer.customer_id = servicedetails.customer_id INNER JOIN 
                currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN staff ON staff.staff_id = 
                servicedetails.uploadedBy WHERE servicedetails.serviceID = :serviceID  AND LOWER(servicedetails.passenger_name) 
                LIKE :passenger_name ORDER BY servicedetails.serviceDetailsID DESC ");
                $selectQuery->bindParam(':serviceID', $_POST['SearchServiceType']);
                $selectQuery->bindParam(':passenger_name', $passengerName);
            }else if($_POST['SearchTerm'] == 'customerPassenger'){
                $passengerName = '%'.  strtolower($_POST['SearchPassenger_name']) . '%';
                $selectQuery = $pdo->prepare("SELECT `serviceDetailsID`, `serviceName`, `customer_name`,passenger_name, DATE(`service_date`) AS
                service_date, `service_details`, `salePrice`, `currencyName`, CASE WHEN Supplier_id  THEN 'bySupplier' ELSE 
                'byAccount' END AS chargeFlag, CASE WHEN `Supplier_id` THEN (SELECT supp_name FROM supplier WHERE supplier.supp_id
                = Supplier_id) ELSE (SELECT account_Name FROM accounts WHERE accounts.account_ID = servicedetails.accoundID) END 
                AS ChargedEntity, staff_name FROM `servicedetails` INNER JOIN service ON service.serviceID = 
                servicedetails.serviceID INNER JOIN customer ON customer.customer_id = servicedetails.customer_id INNER JOIN 
                currency ON currency.currencyID = servicedetails.saleCurrencyID INNER JOIN staff ON staff.staff_id = 
                servicedetails.uploadedBy WHERE servicedetails.customer_id = :customer_id  AND LOWER(servicedetails.passenger_name) 
                LIKE :passenger_name ORDER BY servicedetails.serviceDetailsID DESC ");
                $selectQuery->bindParam(':customer_id', $_POST['CustomerID']);
                $selectQuery->bindParam(':passenger_name', $passengerName);
            }
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['DeleteFile'])){
        try{
                if($delete == 1){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                if($_POST['Type'] == "byview"){
                    $sql = "SELECT file_name FROM servicedocuments WHERE document_id = :document_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':document_id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['file_name'];
                    if(file_exists('service/'.$file)){
                        unlink('service/'.$file);
                    }else{

                    }
                    $deleteSelectedFileSql = "DELETE FROM servicedocuments WHERE document_id = :document_id";
                    $deleteSelectedFileStmt = $pdo->prepare($deleteSelectedFileSql);
                    $deleteSelectedFileStmt->bindParam(':document_id', $_POST['ID']);
                    $deleteSelectedFileStmt->execute();
                }else if($_POST['Type'] == "byRecord"){
                    $sql = "SELECT document_id ,file_name FROM servicedocuments WHERE detailServiceID = :detailServiceID";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':detailServiceID', $_POST['ID']);
                    $stmt->execute();
                    $AllFiles =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    for($i =0;  $i< count($AllFiles); $i++){
                        //var_dump($AllFiles[$i]['document_id']);
                      
                        if(file_exists('service/'.$AllFiles[$i]['file_name'])){
                            unlink('service/'.$AllFiles[$i]['file_name']);
                        }
                        $deleteSelectedFileSql = "DELETE FROM servicedocuments WHERE document_id  = :document_id ";
                        $deleteSelectedFileStmt = $pdo->prepare($deleteSelectedFileSql);
                        $deleteSelectedFileStmt->bindParam(':document_id', $AllFiles[$i]['document_id']);
                        $deleteSelectedFileStmt->execute();
                    }
                    $deleteSelectedRowSQL = "DELETE FROM `servicedetails` WHERE servicedetails.serviceDetailsID  = :serviceDetailsID";
                    $deleteSelectedRowStmt = $pdo->prepare($deleteSelectedRowSQL);
                    $deleteSelectedRowStmt->bindParam(':serviceDetailsID', $_POST['ID']);
                    $deleteSelectedRowStmt->execute();
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
    }else if(isset($_POST['EditData'])){
        $selectQuery = $pdo->prepare("SELECT * FROM servicedetails WHERE serviceDetailsID = :serviceDetailsID");
        $selectQuery->bindParam(':serviceDetailsID', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['InsertServiceName'])){
        try{
                $sql = "INSERT INTO `service`(`serviceName`) VALUES(:serviceName)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':serviceName', $_POST['ServiceName']);
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['AddService'])){
        try{
                if($insert == 1){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                        $sql="INSERT INTO `servicedetails`(`serviceID`, `customer_id`,`passenger_name`, `service_details`, 
                        `netPrice`,`netCurrencyID`, `salePrice`, `saleCurrencyID`, `Supplier_id`, `accoundID`, `uploadedBy`) 
                        VALUES(:serviceID,:customer_id,:passenger_name,:service_details,:netPrice,:netCurrencyID,:salePrice,
                        :saleCurrencyID,:Supplier_id,:accoundID,:uploadedBy)";
                        $stmt = $pdo->prepare($sql);
                        $supplier = $_POST['supplier'];
                        if($_POST['supplier'] == '-1'){
                            $supplier = null;
                        }
                        $charge_account_id = $_POST['charge_account_id'];
                        if($_POST['charge_account_id'] == '-1'){
                            $charge_account_id = null;
                        }
                        // bind parameters to statement
                        $stmt->bindParam(':serviceID', $_POST['addServiceType']);
                        $stmt->bindParam(':customer_id', $_POST['addcustomer_id']);
                        $stmt->bindParam(':passenger_name', $_POST['addPassenger_name']);
                        $stmt->bindParam(':service_details', $_POST['serviceDetail']);
                        $stmt->bindParam(':netPrice', $_POST['net_amount']);
                        $stmt->bindParam(':netCurrencyID', $_POST['net_currency_type']);
                        $stmt->bindParam(':salePrice', $_POST['sale_amount']);
                        $stmt->bindParam(':saleCurrencyID', $_POST['sale_currency_type']);
                        $stmt->bindParam(':Supplier_id', $supplier);
                        $stmt->bindParam(':accoundID', $charge_account_id);
                        $stmt->bindParam(':uploadedBy', $_SESSION['user_id']);
                        // execute the prepared statement
                        $stmt->execute();
                        $getSeviceDetailID = $pdo->lastInsertId();
                        // Now its time to handle the customer payment
                        // If Customer pays on the spot
                        if($_POST['cust_payment']){
                            $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID)
                            VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID)";
                            $stmt = $pdo->prepare($sql);
                            // bind parameters to statement
                            $stmt->bindParam(':customer_id',$_POST['addcustomer_id']);
                            $stmt->bindParam(':payment_amount', $_POST['cust_payment']);
                            $stmt->bindParam(':currencyID', $_POST['payment_currency_type']);
                            $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                            $stmt->bindParam(':accountID', $_POST['addaccount_id']);
                            // execute the prepared statement
                            $stmt->execute();
                        }
                        $pdo->commit(); 
                        
            echo $getSeviceDetailID;
            }else{
                echo "NoPermission";
            }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['UpdateService'])){
        try{
                if($update == 1){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                        $sql="UPDATE `servicedetails` SET `serviceID`=:serviceID,`customer_id`=:customer_id,passenger_name=
                        :passenger_name,`service_details`=:service_details,`netPrice`=:netPrice,`netCurrencyID`=:netCurrencyID,
                        `salePrice`=:salePrice,`saleCurrencyID`=:saleCurrencyID,`Supplier_id`=:Supplier_id,`accoundID`=:accoundID,
                        `uploadedBy`=:uploadedBy WHERE serviceDetailsID =:serviceDetailsID";
                        $stmt = $pdo->prepare($sql);
                        $updsupplier = $_POST['updsupplier'];
                        if($_POST['updsupplier'] == '-1'){
                            $updsupplier = null;
                        }
                        $updcharge_account_id = $_POST['updcharge_account_id'];
                        if($_POST['updcharge_account_id'] == '-1'){
                            $updcharge_account_id = null;
                        }
                        // bind parameters to statement
                        $stmt->bindParam(':serviceID', $_POST['updServiceType']);
                        $stmt->bindParam(':customer_id', $_POST['updcustomer_id']);
                        $stmt->bindParam(':passenger_name', $_POST['updPassenger_name']);
                        $stmt->bindParam(':service_details', $_POST['updserviceDetail']);
                        $stmt->bindParam(':netPrice', $_POST['updnet_amount']);
                        $stmt->bindParam(':netCurrencyID', $_POST['updnet_currency_type']);
                        $stmt->bindParam(':salePrice', $_POST['updsale_amount']);
                        $stmt->bindParam(':saleCurrencyID', $_POST['updsale_currency_type']);
                        $stmt->bindParam(':Supplier_id', $updsupplier);
                        $stmt->bindParam(':accoundID', $updcharge_account_id);
                        $stmt->bindParam(':uploadedBy', $_SESSION['user_id']);
                        $stmt->bindParam(':serviceDetailsID', $_POST['updateserviceDID']);
                        // execute the prepared statement
                        $stmt->execute();
                        $pdo->commit(); 
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