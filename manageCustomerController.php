<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer' ";
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
    if(isset($_POST['Insert_CountryName'])){
        try{
            if($insert == 1){
                $defaultPass = 'abc';
                $supplier = '';
                    $sql = "INSERT INTO `customer`(`customer_name`, `customer_phone`, `customer_whatsapp`, 
                    `customer_address`, `customer_email`, `cust_password`, `status`,`affliate_supp_id`) VALUES(:customer_name,
                    :customer_phone,:customer_whatsapp,:customer_address,:customer_email,:cust_password,:status,:affliate_supp_id)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_name', $_POST['customer_name']);
                $stmt->bindParam(':customer_phone', $_POST['customer_phone']);
                $stmt->bindParam(':customer_whatsapp', $_POST['customer_whatsapp']);
                $stmt->bindParam(':customer_address', $_POST['customer_address']);
                $stmt->bindParam(':customer_email', $_POST['customer_email']);
                if($_POST['customer_password'] ==''){
                   
                    $stmt->bindParam(':cust_password', $defaultPass);
                }else{
                   
                    $stmt->bindParam(':cust_password', $_POST['customer_password']);
                }
                $stmt->bindParam(':status', $_POST['customer_status']);
                if($_POST['supplier'] == "-1"){
                    $supplier = NULL;
                }else{
                    $supplier = $_POST['supplier'];
                }
                $stmt->bindParam(':affliate_supp_id', $supplier);
                // execute the prepared statement
                $stmt->execute();

                if( $_POST['customer_whatsapp'] != '' ):
                    $status = sendWhatsappText($_POST['customer_whatsapp'],'Welcome to SN Travel & Tours. Your account has been created successfully.');
                endif;
                
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }   
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetCustomersReport'])){
            // Get pagination parameters
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
            $offset = ($page - 1) * $limit;
            
            // Get filter parameters
            $filterName = isset($_POST['filterName']) ? $_POST['filterName'] : '';
            $filterPhone = isset($_POST['filterPhone']) ? $_POST['filterPhone'] : '';
            $filterEmail = isset($_POST['filterEmail']) ? $_POST['filterEmail'] : '';
            $filterStatus = isset($_POST['filterStatus']) ? $_POST['filterStatus'] : '';
            $filterSupplier = isset($_POST['filterSupplier']) ? $_POST['filterSupplier'] : '';
            
            // Build WHERE clause for filters
            $whereClause = '';
            $params = [];
            
            if (!empty($filterName)) {
                $whereClause .= " AND customer_name LIKE :filterName";
                $params[':filterName'] = "%$filterName%";
            }
            
            if (!empty($filterPhone)) {
                $whereClause .= " AND customer_phone LIKE :filterPhone";
                $params[':filterPhone'] = "%$filterPhone%";
            }
            
            if (!empty($filterEmail)) {
                $whereClause .= " AND customer_email LIKE :filterEmail";
                $params[':filterEmail'] = "%$filterEmail%";
            }
            
            if (!empty($filterStatus)) {
                $whereClause .= " AND status = :filterStatus";
                $params[':filterStatus'] = $filterStatus;
            }
            
            if (!empty($filterSupplier)) {
                $whereClause .= " AND affliate_supp_id = :filterSupplier";
                $params[':filterSupplier'] = $filterSupplier;
            }
            
            // Count total records with filters
            $countQuery = "SELECT COUNT(*) as total FROM `customer` WHERE 1=1 $whereClause";
            $countStmt = $pdo->prepare($countQuery);
            
            // Bind params for count query
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            
            $countStmt->execute();
            $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get filtered and paginated data
            $query = "SELECT `customer_id`, `customer_name`, `customer_phone`, `customer_whatsapp`, 
                     `customer_address`, `customer_email`, `cust_password`, CASE WHEN `status` = 1 THEN 'Active' ELSE 'Inactive' 
                     END AS status, CASE WHEN affliate_supp_id IS NOT NULL THEN affliate_supp_id ELSE 0 END AS affliate_supp_id 
                     FROM `customer` 
                     WHERE 1=1 $whereClause
                     ORDER BY customer_name ASC
                     LIMIT :limit OFFSET :offset";
            
            $stmt = $pdo->prepare($query);
            
            // Bind params for main query
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Return data with pagination info
            $result = [
                'data' => $data,
                'total' => $totalRecords,
                'page' => $page,
                'limit' => $limit
            ];
            
            echo json_encode($result);
    }else if(isset($_POST['Delete'])){
        try{
                if($delete ==1){
                    $sql = "DELETE FROM customer WHERE customer_id = :customer_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':customer_id', $_POST['ID']);
                    $stmt->execute();
                    echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }     
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM customer WHERE customer_id = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_CountryName'])){
        try{
            if($update == 1){
                $updSupplier = "";
                if($_POST['updcustomer_password'] !=''){
                    $sql = "UPDATE `customer` SET customer_name = :customer_name,customer_phone=:customer_phone,
                    customer_whatsapp =:customer_whatsapp,customer_address=:customer_address,customer_email=:customer_email
                    ,cust_password=:cust_password,status=:status,affliate_supp_id=:affliate_supp_id WHERE customer_id = 
                    :customer_id";
                }else{
                    $sql = "UPDATE `customer` SET customer_name = :customer_name,customer_phone=:customer_phone,
                    customer_whatsapp =:customer_whatsapp,customer_address=:customer_address,customer_email=:customer_email
                    ,status=:status,affliate_supp_id=:affliate_supp_id WHERE customer_id = :customer_id";
                }
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_name', $_POST['updcustomer_name']);
                $stmt->bindParam(':customer_phone', $_POST['updcustomer_phone']);
                $stmt->bindParam(':customer_whatsapp', $_POST['updcustomer_whatsapp']);
                $stmt->bindParam(':customer_address', $_POST['updcustomer_address']);
                $stmt->bindParam(':customer_email', $_POST['updcustomer_email']);
                if($_POST['updcustomer_password'] !=''){
                    $stmt->bindParam(':cust_password', $_POST['updcustomer_password']);
                }
                $stmt->bindParam(':status', $_POST['updcustomer_status']);
                $stmt->bindParam(':customer_id', $_POST['customer_id']);
                if($_POST['updSupplier'] == "-1"){
                    $updSupplier = NULL;
                }else{
                    $updSupplier = $_POST['updSupplier'];
                }
                $stmt->bindParam(':affliate_supp_id', $updSupplier);
                
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>