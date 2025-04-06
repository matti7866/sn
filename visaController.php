<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
 $sql = "SELECT permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Visa' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$insert = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$insert = $insert[0]['insert'];
if($insert == 0){
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
        $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
        as customer_name FROM customer ORDER BY customer_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['GetNationalities'])){
        $selectQuery = $pdo->prepare("SELECT DISTINCT countryName AS mainCountryName, (SELECT airport_id FROM airports WHERE 
        countryName = mainCountryName LIMIT 1) AS airport_id FROM airports ORDER BY countryName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $nationality = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($nationality);
    }else if(isset($_POST['SELECT_FROM'])){
        $selectQuery = $pdo->prepare("SELECT country_id, country_names FROM country_name");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $from = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($from);
    }else if(isset($_POST['SELECT_Supplier'])){
        $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['GetSalePrice'])){
            if($_POST['Type'] == "byCurrency"){
                $selectQuery = $pdo->prepare("SELECT CASE WHEN count(*) > 0 THEN (SELECT customervisaprices.salePrice FROM 
                customervisaprices WHERE customervisaprices.countryID = :countryID AND customervisaprices.customerID = :customerID 
                AND customervisaprices.CurrencyID = :currencyID) ELSE 0 END AS salePrice, (IFNULL(CurrencyID, SELECT currencyID 
                FROM currency WHERE currencyID = :currencyID)) AS CurrencyID   FROM `customervisaprices` WHERE 
                customervisaprices.countryID = :countryID AND customervisaprices.customerID = :customerID AND 
                customervisaprices.CurrencyID = :currencyID ORDER BY date DESC limit 1 ");
                $selectQuery->bindParam(':countryID', $_POST['Country_ID']);
                $selectQuery->bindParam(':customerID', $_POST['Cust_Name']);
                $selectQuery->bindParam(':currencyID', $_POST['CurrencyID']);
            }else{
                $selectQuery = $pdo->prepare("SELECT CASE WHEN count(*) > 0 THEN (SELECT customervisaprices.salePrice FROM 
                customervisaprices WHERE customervisaprices.countryID = :countryID AND customervisaprices.customerID = :customerID 
                ORDER BY DATE DESC LIMIT 1) ELSE 0 END AS salePrice, CurrencyID  FROM `customervisaprices` WHERE 
                customervisaprices.countryID = :countryID AND customervisaprices.customerID = :customerID  ORDER BY date DESC limit 1 ");
                $selectQuery->bindParam(':countryID', $_POST['Country_ID']);
                $selectQuery->bindParam(':customerID', $_POST['Cust_Name']);
            }
            
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $GetPrices = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($GetPrices);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetNetPrice'])){
        if($_POST['Type'] == "byCurrency"){
                $selectQuery = $pdo->prepare("SELECT CASE WHEN count(*) > 0 THEN (SELECT suppliervisaprices.netPrice FROM 
                suppliervisaprices WHERE suppliervisaprices.countryID = :countryID AND suppliervisaprices.suppID = :suppID AND
                CurrencyID = :currencyID) ELSE 0 END AS netPrice, (IFNULL(CurrencyID, SELECT currencyID 
                FROM currency WHERE currencyID = :currencyID)) AS CurrencyID FROM `suppliervisaprices` WHERE 
                suppliervisaprices.countryID = :countryID AND suppliervisaprices.suppID = :suppID AND CurrencyID = :currencyID 
                ORDER BY DATE DESC LIMIT 1 ");
                $selectQuery->bindParam(':countryID', $_POST['Country_ID']);
                $selectQuery->bindParam(':suppID', $_POST['Supplier']);
                $selectQuery->bindParam(':currencyID', $_POST['Net_Currency_Type']);
        }else{
                $selectQuery = $pdo->prepare("SELECT CASE WHEN count(*) > 0 THEN (SELECT suppliervisaprices.netPrice FROM 
                suppliervisaprices WHERE suppliervisaprices.countryID = :countryID AND suppliervisaprices.suppID = :suppID
                ORDER BY DATE DESC LIMIT 1) ELSE 0 END AS netPrice, CurrencyID FROM `suppliervisaprices` WHERE 
                suppliervisaprices.countryID = :countryID AND suppliervisaprices.suppID = :suppID ORDER BY DATE DESC LIMIT 1 ");
                $selectQuery->bindParam(':countryID', $_POST['Country_ID']);
                $selectQuery->bindParam(':suppID', $_POST['Supplier']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $GetPrices = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($GetPrices);
    }else if(isset($_POST['Insert_Visa'])){
        try{
            $image = '';
            if($_FILES['uploadFile']['name'] !=''){
                $image = upload_Image();
                if($image == ''){
                    $image = 'Error';
                }
            }
            $sql = "SELECT staff_branchID FROM staff WHERE staff_id=:staff_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    $branchID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $branchID = $branchID[0]['staff_branchID'];
            // If Customer pays on the spot
            if($_POST['cust_payment']){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }else{
                    $decodePassArr = json_decode($_POST['passArr']);
                    $decodepassportNumArr = json_decode($_POST['passportNumArr']);
                    $decodenetAmountArr = json_decode($_POST['netAmountArr']);
                    $decodenetPriceCurrencyArr = json_decode($_POST['netPriceCurrencyArr']);
                    $decodesaleAmountArr = json_decode($_POST['saleAmountArr']);
                    $decodesalePriceCurrencyArr = json_decode($_POST['salePriceCurrencyArr']);
                for($i=0;$i < count($decodePassArr);$i++){
                    if($image == ''){
                    $sql="INSERT INTO `visa`(`customer_id`, `country_id`,`supp_id`, `sale`,saleCurrencyID,
                    `net_price`,netCurrencyID, `gaurantee`, `address`,`staff_id`,`passenger_name`,
                    `pendingvisa`,`branchID`,`PassportNum`,`nationalityID`) VALUES (:customer_id,:country_id,:supp_id,
                    :sale,:saleCurrencyID,:net_price,:netCurrencyID,:gaurantee,:address,:staff_id,:passenger_name,
                    :pendingvisa,:branchID,:PassportNum,:nationalityID)";
                    
                }else{
                    $sql="INSERT INTO `visa`(`customer_id`, `country_id`,`supp_id`, `sale`,saleCurrencyID,
                    `net_price`,netCurrencyID, `gaurantee`, `address`,`staff_id`,`passenger_name`,
                    `pendingvisa`,`visaCopy`,`branchID`,`PassportNum`,`nationalityID`) VALUES (:customer_id,:country_id,:supp_id,
                    :sale,:saleCurrencyID,:net_price,:netCurrencyID,:gaurantee,:address,:staff_id,:passenger_name,
                    :pendingvisa,:visaCopy,:branchID,:PassportNum,:nationalityID)";
                }
               
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['cust_name']);
                $stmt->bindParam(':country_id', $_POST['country_id']);
                $stmt->bindParam(':supp_id', $_POST['supplier']);
                $stmt->bindParam(':sale', $decodesaleAmountArr[$i]);
                $stmt->bindParam(':saleCurrencyID', $decodesalePriceCurrencyArr[$i]);
                $stmt->bindParam(':net_price', $decodenetAmountArr[$i]);
                $stmt->bindParam(':netCurrencyID', $decodenetPriceCurrencyArr[$i]);
                $stmt->bindParam(':gaurantee', $_POST['gaurantee']);
                $stmt->bindParam(':address', $_POST['address']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':passenger_name', $decodePassArr[$i]);
                $stmt->bindParam(':pendingvisa', $_POST['pendingvisa']);
                $stmt->bindParam(':branchID', $branchID);
                $stmt->bindParam(':PassportNum', $decodepassportNumArr[$i]);
                $stmt->bindParam(':nationalityID', $_POST['nationality']);
                if($image != ''){
                    $stmt->bindParam(':visaCopy', $image);
                }
                // execute the prepared statement
                $stmt->execute();
                }
                // Now its time to handle the customer payment
                 // create prepared statement
                 $sql = "INSERT INTO `customer_payments`(`customer_id`,`payment_amount`,`currencyID`, `staff_id`,accountID)
                 VALUES (:customer_id, :payment_amount,:currencyID,:staff_id,:accountID)";
                 $stmt = $pdo->prepare($sql);
                 // bind parameters to statement
                 $stmt->bindParam(':customer_id',$_POST['cust_name']);
                 $stmt->bindParam(':payment_amount', $_POST['cust_payment']);
                 $stmt->bindParam(':currencyID', $_POST['payment_currency_type']);
                 $stmt->bindParam(':staff_id',$_SESSION['user_id']);
                 $stmt->bindParam(':accountID', $_POST['addaccount_id']);
                 // execute the prepared statement
                 $stmt->execute();
                 $pdo->commit(); 
                }
            }else{
                $pdo->beginTransaction();
                if($image == 'Error')
                {
                    $pdo->rollback();
                    echo "Record not added becuase of file uploader";
                }else{
                    $decodePassArr = json_decode($_POST['passArr']);
                    $decodepassportNumArr = json_decode($_POST['passportNumArr']);
                    $decodenetAmountArr = json_decode($_POST['netAmountArr']);
                    $decodenetPriceCurrencyArr = json_decode($_POST['netPriceCurrencyArr']);
                    $decodesaleAmountArr = json_decode($_POST['saleAmountArr']);
                    $decodesalePriceCurrencyArr = json_decode($_POST['salePriceCurrencyArr']);
                for($i=0;$i < count($decodePassArr);$i++){
                if($image == ''){
                    $sql="INSERT INTO `visa`(`customer_id`, `country_id`,`supp_id`, `sale`,saleCurrencyID,
                    `net_price`,netCurrencyID, `gaurantee`, `address`,`staff_id`,`passenger_name`,
                    `pendingvisa`,`branchID`,`PassportNum`,`nationalityID`) VALUES (:customer_id,:country_id,:supp_id,
                    :sale,:saleCurrencyID,:net_price,:netCurrencyID,:gaurantee,:address,:staff_id,:passenger_name,
                    :pendingvisa,:branchID,:PassportNum,:nationalityID)";
                    
                }else{
                    $sql="INSERT INTO `visa`(`customer_id`, `country_id`,`supp_id`, `sale`,saleCurrencyID,
                    `net_price`,netCurrencyID, `gaurantee`, `address`,`staff_id`,`passenger_name`,
                    `pendingvisa`,`visaCopy`,`branchID`,`PassportNum`,`nationalityID`) VALUES (:customer_id,:country_id,:supp_id,
                    :sale,:saleCurrencyID,:net_price,:netCurrencyID,:gaurantee,:address,:staff_id,:passenger_name,
                    :pendingvisa,:visaCopy,:branchID,:PassportNum,:nationalityID)";
                }
               
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':customer_id', $_POST['cust_name']);
                $stmt->bindParam(':country_id', $_POST['country_id']);
                $stmt->bindParam(':supp_id', $_POST['supplier']);
                $stmt->bindParam(':sale', $decodesaleAmountArr[$i]);
                $stmt->bindParam(':saleCurrencyID', $decodesalePriceCurrencyArr[$i]);
                $stmt->bindParam(':net_price', $decodenetAmountArr[$i]);
                $stmt->bindParam(':netCurrencyID', $decodenetPriceCurrencyArr[$i]);
                $stmt->bindParam(':gaurantee', $_POST['gaurantee']);
                $stmt->bindParam(':address', $_POST['address']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':passenger_name', $decodePassArr[$i]);
                $stmt->bindParam(':pendingvisa', $_POST['pendingvisa']);
                $stmt->bindParam(':branchID', $branchID);
                $stmt->bindParam(':PassportNum', $decodepassportNumArr[$i]);
                $stmt->bindParam(':nationalityID', $_POST['nationality']);
                if($image != ''){
                    $stmt->bindParam(':visaCopy', $image);
                }
                // execute the prepared statement
                $stmt->execute();
                }
                $pdo->commit(); 
            }
            }
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    function upload_Image(){
        $new_image_name = '';
        if($_FILES['uploadFile']['size']<=2097152){
            $extension = explode(".", $_FILES['uploadFile']['name']);
            $f_name = '';
            $f_ext = '';
            if(count($extension) > 2){
                for($i = 0; $i< count($extension); $i++){
                    if(count($extension) == $extension[$i]){
                        $f_name  = $f_name . $extension[$i];
                    }else{
                        $f_ext = $extension[$i];
                    }
                }
               
            }else{
                $f_name =  $extension[0];
                $f_ext = $extension[1];
            }
            $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
            if (in_array(strtolower($extension[1]), $ext))
            {
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'visa/'. $new_image_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploadFile']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
            
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>