<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier Visa Prices' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
if(isset($_POST['INSERT'])){
        try{
            if($insert == 1){
            $pdo->beginTransaction();
            $selectQuery = $pdo->prepare("SELECT COUNT(*) AS Total FROM suppliervisaprices WHERE suppID =:SupplierID
            AND CurrencyID= :CurrencyID");
            $selectQuery->bindParam(':SupplierID', $_POST['finalArr'][1]);
            $selectQuery->bindParam(':CurrencyID', $_POST['finalArr'][3]);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $visaPrice = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            if($visaPrice[0]['Total'] == 0){
                for($i=0;$i< count($_POST['finalArr']); $i++){
                     $sql = "INSERT INTO `suppliervisaprices`(`countryID`, `suppID`, `netPrice`, `CurrencyID`) VALUES 
                    (:countryID,:suppID,:netPrice,:CurrencyID)";
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':countryID', $_POST['finalArr'][$i]);
                    $stmt->bindParam(':suppID', $_POST['finalArr'][$i+1]);
                    $stmt->bindParam(':netPrice', $_POST['finalArr'][$i+2]);
                    $stmt->bindParam(':CurrencyID', $_POST['finalArr'][$i+3]);
                    // execute the prepared statement
                    $stmt->execute();
                    $i +=3;
                }
            }else{
                $sql = "DELETE FROM suppliervisaprices WHERE suppID =:SupplierID AND CurrencyID= :CurrencyID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':SupplierID', $_POST['finalArr'][1]);
                $stmt->bindParam(':CurrencyID', $_POST['finalArr'][3]);
                $stmt->execute();
                for($i=0;$i< count($_POST['finalArr']); $i++){
                    $sql = "INSERT INTO `suppliervisaprices`(`countryID`, `suppID`,`netPrice`,`CurrencyID`) VALUES 
                   (:countryID,:suppID,:netPrice,:CurrencyID)";
                   $stmt = $pdo->prepare($sql);
                   // bind parameters to statement
                   $stmt->bindParam(':countryID', $_POST['finalArr'][$i]);
                   $stmt->bindParam(':suppID', $_POST['finalArr'][$i+1]);
                   $stmt->bindParam(':netPrice', $_POST['finalArr'][$i+2]);
                   $stmt->bindParam(':CurrencyID', $_POST['finalArr'][$i+3]);
                   // execute the prepared statement
                   $stmt->execute();
                   $i +=3;
               }
            }
            // create prepared statement
            
            $pdo->commit(); 
            echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            }
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
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetVisaPrices'])){
        $selectQuery = $pdo->prepare("SELECT COUNT(*) AS Total FROM suppliervisaprices WHERE suppID =:SupplierID AND
        CurrencyID= :CurrencyID");
        $selectQuery->bindParam(':SupplierID', $_POST['Supplier_ID']);
        $selectQuery->bindParam(':CurrencyID', $_POST['Net_Currency_Type']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $visaPrice = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        if($visaPrice[0]['Total'] == 0){
            $selectQuery = $pdo->prepare("SELECT country_name.country_id, country_name.country_names,0 
            netPrice FROM `country_name` ORDER BY country_names ASC");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $visaPriceRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($visaPriceRpt);
        }else{
            $selectQuery = $pdo->prepare("SELECT country_name.country_id,country_name.country_names,
            suppliervisaprices.netPrice FROM suppliervisaprices INNER JOIN country_name ON country_name.country_id = 
            suppliervisaprices.countryID WHERE suppliervisaprices.suppID = :SupplierID AND CurrencyID= :CurrencyID ORDER BY
            country_names ASC");
            $selectQuery->bindParam(':SupplierID', $_POST['Supplier_ID']);
            $selectQuery->bindParam(':CurrencyID', $_POST['Net_Currency_Type']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $visaPriceRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($visaPriceRpt);
        }
        
    }
    // Close connection
    unset($pdo); 
?>