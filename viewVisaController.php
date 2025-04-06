<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete, permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Visa' ";
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
            as customer_name,(SELECT DISTINCT customer_id FROM visa WHERE customer_id =:customer_id) AS 
            selectedCustomer FROM customer ORDER BY customer_name ASC");
            $selectQuery->bindParam(':customer_id', $_POST['ID']);
            $selectQuery->execute();
        }
        
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['Select_Country'])){
            $selectQuery = $pdo->prepare("SELECT * FROM country_name ORDER BY country_names ASC");
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $countries = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($countries);
    }else if(isset($_POST['Select_Accounts'])){
            $selectQuery = $pdo->prepare("SELECT account_ID,account_Name FROM accounts ORDER BY account_Name ASC");
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $accounts = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($accounts);
    }else if(isset($_POST['SearchVisa'])){
        if($_POST['SearchTerm'] == 'DateNdAll'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate AND visa.customer_id =
            :customer_id AND LOWER(visa.PassportNum) LIKE :PassportNum AND LOWER(visa.passenger_name) LIKE  :passenger_name  AND 
            visa.country_id = :country_id ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':passenger_name', $passenger_name);
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }else if($_POST['SearchTerm'] == 'DateNdAllExceptCountry'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id 
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate AND visa.customer_id =
            :customer_id AND LOWER(visa.PassportNum) LIKE :PassportNum AND LOWER(visa.passenger_name) LIKE  :passenger_name ORDER BY
            visa_id DESC ");
             $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
             $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
             $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
             $selectQuery->bindParam(':todate', $_POST['Todate']);
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
             $selectQuery->bindParam(':PassportNum', $passportNum );
             $selectQuery->bindParam(':passenger_name', $passenger_name);
        }else if($_POST['SearchTerm'] == 'DateNdCusPass'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate AND visa.customer_id =
            :customer_id AND LOWER(visa.PassportNum) LIKE :PassportNum ORDER BY visa_id DESC ");
             $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
             $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
             $selectQuery->bindParam(':todate', $_POST['Todate']);
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
             $selectQuery->bindParam(':PassportNum', $passportNum );
        }else if($_POST['SearchTerm'] == 'DateNdCus'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id 
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate AND visa.customer_id =
            :customer_id  ORDER BY visa_id DESC ");
             $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
             $selectQuery->bindParam(':todate', $_POST['Todate']);
             $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa` 
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE DATE(datetime) BETWEEN :fromdate AND :todate ORDER BY visa_id DESC ");
             $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
             $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'CusNdAll'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE visa.customer_id = :customer_id AND LOWER(visa.PassportNum) LIKE 
            :PassportNum AND LOWER(visa.passenger_name) LIKE :passenger_name  AND visa.country_id = :country_id ORDER BY visa_id
            DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':passenger_name', $passenger_name);
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }else if($_POST['SearchTerm'] == 'CusNdAllExceptCountry'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE visa.customer_id = :customer_id AND LOWER(visa.PassportNum) LIKE
            :PassportNum AND LOWER(visa.passenger_name) LIKE :passenger_name ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':passenger_name', $passenger_name);
        }else if($_POST['SearchTerm'] == 'CusNdPass'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE visa.customer_id = :customer_id AND LOWER(visa.PassportNum) LIKE
            :PassportNum  ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->bindParam(':PassportNum', $passportNum );
        }else if($_POST['SearchTerm'] == 'Cus'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE visa.customer_id = :customer_id  ORDER BY visa_id DESC ");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
        }else if($_POST['SearchTerm'] == 'PassPassengerCountry'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum,CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.PassportNum) LIKE :PassportNum AND LOWER(visa.passenger_name)
            LIKE :passenger_name  AND visa.country_id = :country_id ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':passenger_name', $passenger_name);
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }else if($_POST['SearchTerm'] == 'PassPassenger'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum,  CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id 
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.PassportNum) LIKE :PassportNum AND LOWER(visa.passenger_name)
            LIKE :passenger_name  ORDER BY visa_id DESC ");
             $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':passenger_name', $passenger_name);
        }else if($_POST['SearchTerm'] == 'PassPassengerCountry'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum,  CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.PassportNum) LIKE :PassportNum AND visa.country_id = 
            :country_id ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $selectQuery->bindParam(':PassportNum', $passportNum );
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }else if($_POST['SearchTerm'] == 'Pass'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.PassportNum) LIKE :PassportNum ORDER BY visa_id DESC ");
            $passportNum =  "%". strtolower($_POST['PassportNum']) . "%";
            $selectQuery->bindParam(':PassportNum', $passportNum );
        }else if($_POST['SearchTerm'] == 'PassengerCountry'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum, CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge FROM `visa` 
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.passenger_name) LIKE :passenger_name  AND visa.country_id
            = :country_id ORDER BY visa_id DESC ");
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':passenger_name', $passenger_name);
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }else if($_POST['SearchTerm'] == 'Passenger'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum,CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge FROM `visa` 
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id = 
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE LOWER(visa.passenger_name) LIKE :passenger_name  ORDER BY visa_id
            DESC ");
            $passenger_name =  "%". strtolower($_POST['PassengerName']) . "%" ;
            $selectQuery->bindParam(':passenger_name', $passenger_name);
        }else if($_POST['SearchTerm'] == 'Country'){
            $selectQuery = $pdo->prepare("SELECT visa_id,customer_name, passenger_name, datetime,country_names,net_price,
            netCur.currencyName AS netCurrency,sale,saleCur.currencyName AS saleCurrency,supp_name,staff_name, gaurantee,
            address,visaCopy,IFNULL(PassportNum,'') AS PassportNum ,CASE WHEN (SELECT DISTINCT visaextracharges.visa_id FROM 
            visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) != '' THEN (SELECT DISTINCT visaextracharges.visa_id 
            FROM visaextracharges WHERE visaextracharges.visa_id = visa.visa_id) ELSE 'NoExists' END AS extraCharge  FROM `visa`
            INNER JOIN customer ON customer.customer_id = visa.customer_id  INNER JOIN country_name ON country_name.country_id =
            visa.country_id INNER JOIN supplier ON supplier.supp_id = visa.supp_id INNER JOIN staff ON staff.staff_id = 
            visa.staff_id INNER JOIN currency AS netCur ON netCur.currencyID = visa.netCurrencyID INNER JOIN currency AS saleCur 
            ON saleCur.currencyID = visa.saleCurrencyID WHERE  visa.country_id = :country_id ORDER BY visa_id DESC ");
            $selectQuery->bindParam(':country_id', $_POST['SearchVisaType']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $visa = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($visa);
    }else if(isset($_POST['SELECT_Supplier'])){
        if($_POST['Type'] == "forCharges"){
            $selectQuery = $pdo->prepare("SELECT supp_id,supp_name,(SELECT DISTINCT visa.supp_id FROM visa WHERE visa_id = :visaID
            ) AS selectedSupplier  FROM supplier ORDER BY supplier.supp_name ASC");
            $selectQuery->bindParam(':visaID', $_POST['ID']);
        }else{
            $selectQuery = $pdo->prepare("SELECT supp_id, supp_name FROM supplier ORDER BY supp_name ASC");
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $supplier = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($supplier);
    }else if(isset($_POST['Upload_VisaPhoto'])){
        try{
            $image = upload_Image();
            //If Customer pays on the spot
                if($image == '')
                {
                    echo "Record not added becuase of file uploader";
                }else{
                        $sql = "UPDATE visa SET visaCopy =:visaCopy, orginalName = :orginalName WHERE visa_id =:visaID";
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':visaCopy', $image);
                $stmt->bindParam(':orginalName', $_FILES['uploader']['name']);
                $stmt->bindParam(':visaID', $_POST['uploadVisaID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['DeleteFile'])){
        try{
            if($delete == 1){
                // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                if($_POST['Type'] =="byView"){
                    $sql = "SELECT visaCopy FROM visa WHERE visa_id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['visaCopy'];
                    if(file_exists($file)){
                        unlink($file);
                    }
                    if(!is_file($file)) {
                        $sql = "UPDATE visa SET visaCopy = NULL,  orginalName = NULL WHERE visa_id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $_POST['ID']);
                        $stmt->execute();
                    }
                }else if($_POST['Type']=="byExCharge"){
                    $sql = "SELECT docName FROM visaextracharges WHERE visaExtraChargesID  = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['docName'];
                    if(file_exists($file)){
                        unlink($file);
                    }
                    if(!is_file($file)) {
                        $sql = "UPDATE visaextracharges SET docName = NULL, orginalName = NULL WHERE visaExtraChargesID = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $_POST['ID']);
                        $stmt->execute();
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
    }else if(isset($_POST['DeleteVisa'])){
        try{
                if($delete == 1){
                     // First of all, let's begin a transaction
                $pdo->beginTransaction();
                // Update status of ticket
                if($_POST['Type'] == "byView"){
                    $sql = "SELECT visaCopy FROM visa WHERE visa_id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['visaCopy'];  
                    if(file_exists($file)){
                        unlink($file);
                    }          
                    if(!is_file($file)) {
                        $sql = "DELETE FROM visa WHERE visa_id = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $_POST['ID']);
                        $stmt->execute();
                    }
                }else if($_POST['Type'] == "byExCharge"){
                    $sql = "SELECT docName FROM visaextracharges WHERE visaExtraChargesID = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $_POST['ID']);
                    $stmt->execute();
                    $file =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    $file =  $file[0]['docName'];  
                    if(file_exists($file)){
                        unlink($file);
                    }          
                    if(!is_file($file)) {
                        $sql = "DELETE FROM visaextracharges WHERE visaExtraChargesID = :id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $_POST['ID']);
                        $stmt->execute();
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
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetUpdVisa'])){
            $selectQuery = $pdo->prepare("SELECT * FROM visa WHERE visa_id=:visa_id");
            $selectQuery->bindParam(':visa_id', $_POST['VisaID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateVisa'])){
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
                         $sql = "UPDATE `visa` SET  customer_id=:customer_id,passenger_name=:passenger_name,supp_id=:supp_id,
                         country_id=:country_id,staff_id=:staff_id,net_price=:net_price,netCurrencyID =:netCurrencyID,sale=:sale,
                         saleCurrencyID = :saleCurrencyID,gaurantee=:gaurantee,address=:address,branchID=:branchID,
                         PassportNum=:PassportNum WHERE visa_id=:visa_id ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':customer_id', $_POST['Updcustomer_id']);
                         $stmt->bindParam(':passenger_name', $_POST['UpdPassengerName']);
                         $stmt->bindParam(':supp_id', $_POST['Updsupplier']);
                         $stmt->bindParam(':country_id', $_POST['Updcountry_ID']);
                         $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                         $stmt->bindParam(':net_price', $_POST['UpdNet']);
                         $stmt->bindParam(':netCurrencyID', $_POST['Net_Currency_Type']);
                         $stmt->bindParam(':sale', $_POST['UpdSale']);
                         $stmt->bindParam(':saleCurrencyID', $_POST['Sale_Currency_Type']);
                         $stmt->bindParam(':gaurantee', $_POST['Updguarantee']);
                         $stmt->bindParam(':address', $_POST['Updaddress']);
                         $stmt->bindParam(':branchID', $staffBranchID);
                         $stmt->bindParam(':PassportNum', $_POST['UpdPassportNum']);
                         $stmt->bindParam(':visa_id', $_POST['UpdvisaID']);
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
    }else if(isset($_POST['AddExtraCharges'])){
        try{
                if($insert == 1){
                 // First of all, let's begin a transaction
                 $pdo->beginTransaction();
                 // Update status of visa extra charges
                 if($_POST['ChargeType'] == 1){
                        $Supplier = '';
                        $accountID = '';
                        if($_POST['ActionType'] == 1){
                            $sql = "INSERT INTO `visaextracharges`(`visa_id`, `net_price`, `netCurrencyID`, `salePrice`, 
                            `saleCurrencyID`, `supplierID`, `accountID`, `typeID`,`uploadedBy`) VALUES (:id,:net_price,
                            :netCurrencyID,:salePrice,:saleCurrencyID,:supplierID,:accountID,:typeID,:uploadedBy) ";
                        }else if($_POST['ActionType'] == 2){
                            $sql = "UPDATE `visaextracharges` SET net_price = :net_price, netCurrencyID = :netCurrencyID,
                            salePrice = :salePrice,saleCurrencyID = :saleCurrencyID, supplierID = :supplierID, accountID = 
                            :accountID,typeID = :typeID,uploadedBy = :uploadedBy WHERE visaExtraChargesID =:id  ";
                        }
                         $stmt = $pdo->prepare($sql);
                         if($_POST['ChargeSupplier'] != '-1'){
                            $Supplier = $_POST['ChargeSupplier'];
                         }else{
                            $Supplier = NULL;
                         }
                         if($_POST['ChargeAccount'] != '-1'){
                            $accountID = $_POST['ChargeAccount'];
                         }else{
                            $accountID = NULL;
                         }
                         // bind parameters to statement
                         $stmt->bindParam(':id', $_POST['VID']);
                         $stmt->bindParam(':net_price', $_POST['Fine_Amount']);
                         $stmt->bindParam(':netCurrencyID', $_POST['Fine_Currency_Type']);
                         $stmt->bindParam(':salePrice', $_POST['Fine_Amount']);
                         $stmt->bindParam(':saleCurrencyID', $_POST['Fine_Currency_Type']);
                         $stmt->bindParam(':supplierID', $Supplier);
                         $stmt->bindParam(':accountID', $accountID);
                         $stmt->bindParam(':typeID', $_POST['ChargeType']);
                         $stmt->bindParam(':uploadedBy', $_SESSION['user_id']);
                 }else if($_POST['ChargeType'] == 2){
                        $accountID = NULL;
                        if($_POST['ActionType'] == 1){
                            $sql = "SELECT DISTINCT visa.supp_id FROM `visa` WHERE visa.visa_id = :visaID";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':visaID', $_POST['VID']);
                            $stmt->execute();
                            $Supplier =  $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            $Supplier =  $Supplier[0]['supp_id'];
                        }
                        // insert query
                        if($_POST['ActionType'] == 1){
                        $sql = "INSERT INTO `visaextracharges`(`visa_id`, `net_price`, `netCurrencyID`, `salePrice`, 
                        `saleCurrencyID`, `supplierID`, `accountID`, `typeID`,`uploadedBy`) VALUES (:id,:net_price,:netCurrencyID,
                        :salePrice,:saleCurrencyID,:supplierID,:accountID,:typeID,:uploadedBy) ";
                        }else if($_POST['ActionType'] == 2){
                            $sql = "UPDATE `visaextracharges` SET net_price = :net_price, netCurrencyID = :netCurrencyID,
                            salePrice = :salePrice,saleCurrencyID = :saleCurrencyID,typeID = :typeID,uploadedBy = :uploadedBy 
                            WHERE visaExtraChargesID = :id ";
                         }
                         // bind parameters to statement
                         $stmt = $pdo->prepare($sql);
                         $stmt->bindParam(':id', $_POST['VID']);
                         $stmt->bindParam(':net_price', $_POST['ChargeNetPrice']);
                         $stmt->bindParam(':netCurrencyID', $_POST['CharNet_Currency_Type']);
                         $stmt->bindParam(':salePrice', $_POST['ChargeSalePrice']);
                         $stmt->bindParam(':saleCurrencyID', $_POST['CharSale_Currency_Type']);
                         if($_POST['ActionType'] == 1){
                            $stmt->bindParam(':supplierID', $Supplier);
                            $stmt->bindParam(':accountID', $accountID);
                         }
                         $stmt->bindParam(':typeID', $_POST['ChargeType']);
                         $stmt->bindParam(':uploadedBy', $_SESSION['user_id']);
                 }else if($_POST['ChargeType'] == 3){
                    $Supplier = '';
                    $accountID = '';
                    if($_POST['ActionType'] == 1){
                        $sql = "INSERT INTO `visaextracharges`(`visa_id`, `net_price`, `netCurrencyID`, `salePrice`, 
                        `saleCurrencyID`, `supplierID`, `accountID`, `typeID`,`uploadedBy`) VALUES (:id,:net_price,:netCurrencyID,
                        :salePrice,:saleCurrencyID,:supplierID,:accountID,:typeID,:uploadedBy) ";
                    }else if($_POST['ActionType'] == 2){
                            $sql = "UPDATE `visaextracharges` SET net_price = :net_price, netCurrencyID = :netCurrencyID,
                            salePrice = :salePrice,saleCurrencyID = :saleCurrencyID, supplierID = :supplierID, accountID = 
                            :accountID,typeID = :typeID,uploadedBy = :uploadedBy wHERE visaExtraChargesID = :id ";
                    }
                     $stmt = $pdo->prepare($sql);
                     if($_POST['ChargeSupplier'] != '-1'){
                        $Supplier = $_POST['ChargeSupplier'];
                     }else{
                        $Supplier = NULL;
                     }
                     if($_POST['ChargeAccount'] != '-1'){
                        $accountID = $_POST['ChargeAccount'];
                     }else{
                        $accountID = NULL;
                     }
                     // bind parameters to statement
                     $stmt->bindParam(':id', $_POST['VID']);
                     $stmt->bindParam(':net_price', $_POST['ChargeNetPrice']);
                     $stmt->bindParam(':netCurrencyID', $_POST['CharNet_Currency_Type']);
                     $stmt->bindParam(':salePrice', $_POST['ChargeSalePrice']);
                     $stmt->bindParam(':saleCurrencyID', $_POST['CharSale_Currency_Type']);
                     $stmt->bindParam(':supplierID', $Supplier);
                     $stmt->bindParam(':accountID', $accountID);
                     $stmt->bindParam(':typeID', $_POST['ChargeType']);
                     $stmt->bindParam(':uploadedBy', $_SESSION['user_id']);
                 } 
                         
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
    }else if(isset($_POST['GetExtraChargesDetails'])){
        $selectQuery = $pdo->prepare("SELECT visaextracharges.visaExtraChargesID,visaextracharges.net_price, netCur.currencyName
        AS netCur, visaextracharges.salePrice, saleCur.currencyName  AS saleCur, CASE WHEN supplierID THEN (SELECT 
        supplier.supp_name FROM supplier WHERE supplier.supp_id = supplierID) ELSE (SELECT accounts.account_Name FROM accounts
        WHERE accounts.account_ID = visaextracharges.accountID) END AS chargedEntity, CASE WHEN supplierID THEN 1 ELSE 2 END AS 
        chargeFlag, DATE(datetime) AS date, CASE WHEN typeID = 1 THEN 'Fine' WHEN typeID = 2 THEN 'Escape Report' ELSE 'Escape Removal'
        END AS type, staff.staff_name,docName,orginalName, typeID FROM visaextracharges INNER JOIN staff ON staff.staff_id =
        visaextracharges.uploadedBy INNER JOIN currency AS netCur ON netCur.currencyID = visaextracharges.netCurrencyID INNER JOIN
        currency AS saleCur ON saleCur.currencyID = visaextracharges.saleCurrencyID WHERE visaextracharges.visa_id = :visa_id
        ORDER BY visaextracharges.visaExtraChargesID
        ASC");
             $selectQuery->bindParam(':visa_id', $_POST['ID']);
             $selectQuery->execute();
             /* Fetch all of the remaining rows in the result set */
             $ExChVisaRpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
             // encoding array to json format
             echo json_encode($ExChVisaRpt);
    }else if(isset($_POST['Upload_ExraChargeDoc'])){
        try{
            $image = uploadExtraDocs();
            //If Customer pays on the spot
                if($image == '')
                {
                    echo "Record not added becuase of file uploader";
                }else{
                        $sql = "UPDATE visaextracharges SET docName =:docName, orginalName=:orginalName WHERE visaExtraChargesID
                         =:visaExtraChargesID";
                }
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':docName', $image);
                $stmt->bindParam(':orginalName', $_FILES['Chargesuploader']['name']);
                $stmt->bindParam(':visaExtraChargesID', $_POST['uploadChargesID']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['EditExtraCharges'])){
            if($_POST['ChargeType'] == 1){
                $selectQuery = $pdo->prepare("SELECT `net_price`, `netCurrencyID`,`supplierID`, `accountID` FROM 
                `visaextracharges` WHERE visaExtraChargesID = :visaExtraChargesID");
            }else if($_POST['ChargeType'] == 2){
                $selectQuery = $pdo->prepare("SELECT `net_price`, `netCurrencyID`, `salePrice`, `saleCurrencyID` FROM 
                `visaextracharges` WHERE visaExtraChargesID = :visaExtraChargesID");
            }else if($_POST['ChargeType'] == 3){
                $selectQuery = $pdo->prepare("SELECT `net_price`, `netCurrencyID`, `salePrice`, `saleCurrencyID`, 
                `supplierID`, `accountID` FROM `visaextracharges` WHERE visaExtraChargesID = :visaExtraChargesID");
            }
            $selectQuery->bindParam(':visaExtraChargesID', $_POST['ID']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($data);
    }
    function upload_Image(){
        $new_image_name = '';
        if($_FILES['uploader']['size']<=2097152){
            $extension = explode(".", $_FILES['uploader']['name']);
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
            if (in_array(strtolower($f_ext), $ext))
            {
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'visa/'. $new_image_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['uploader']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    function uploadExtraDocs(){
        $new_image_name = '';
        if($_FILES['Chargesuploader']['size']<=2097152){
            $extension = explode(".", $_FILES['Chargesuploader']['name']);
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
            if (in_array(strtolower($f_ext), $ext))
            {
                $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                $new_image_name = md5($new_image_name);
                $new_image_name = 'visa/'. $new_image_name. '.' .$f_ext;
                $destination = $new_image_name;
                move_uploaded_file($_FILES['Chargesuploader']['tmp_name'],$destination);
            }else{
                $new_image_name = '';
            }
        }
        return $new_image_name;
    }
    // Close connection
    unset($pdo); 
?>