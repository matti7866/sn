<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Hotel' ";
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
    if(isset($_POST['SELECT_CUSTOMER'])){
        if($_POST['Type'] == "byAll"){
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name FROM customer ORDER BY customer_name ASC");
            $selectQuery->execute();
        }else{
            $selectQuery = $pdo->prepare("SELECT customer_id,concat(customer_name,'--',customer_phone)
            as customer_name,(SELECT DISTINCT customer_id FROM customer WHERE customer_id =:customer_id) AS 
            selectedCustomer FROM customer ORDER BY customer_name ASC");
            $selectQuery->bindParam(':customer_id', $_POST['ID']);
            $selectQuery->execute();
        }
        
        /* Fetch all of the remaining rows in the result set */
        $customers = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($customers);
    }else if(isset($_POST['SearchHotel'])){
            $selectQuery = $pdo->prepare("SELECT `hotel_id`, `customer_name`,passenger_name, `supp_name`, `hotel_name`, `checkin_date`,
            `checkout_date`, `net_price`,netCur.currencyName AS netCurrency,`sale_price`,saleCur.currencyName AS saleCurrency, 
            `country_names`, `datetime`, `staff_name` FROM `hotel` INNER JOIN customer ON customer.customer_id = hotel.customer_id
            INNER JOIN supplier ON supplier.supp_id = hotel.supplier_id INNER JOIN country_name ON country_name.country_id = 
            hotel.country_id INNER JOIN staff ON staff.staff_id = hotel.staffID INNER JOIN currency AS netCur ON netCur.currencyID
            = hotel.netCurrencyID INNER JOIN currency AS saleCur ON saleCur.currencyID = hotel.saleCurrencyID  
            WHERE hotel.customer_id = :customer_id ORDER BY hotel_id DESC");
            $selectQuery->bindParam(':customer_id', $_POST['Customer_ID']);
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $hotel = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($hotel);
    }else if(isset($_POST['Delete'])){
        try{
                  if($delete == 1){
                        $sql = "DELETE FROM hotel WHERE hotel_id = :hotel_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':hotel_id', $_POST['Hotel_ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "NoPermission";
                }
               
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdHotel'])){
            $selectQuery = $pdo->prepare("SELECT * FROM hotel WHERE hotel_id=:hotel_id");
            $selectQuery->bindParam(':hotel_id', $_POST['Hotel_ID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['SaveUpdateHotel'])){
        try{
            if($update == 1){
                         $sql = "UPDATE `hotel` SET `customer_id`=:updcustomer_id,`supplier_id`=:supplier_id,`hotel_name`=
                         :hotel_name,`checkin_date`=:checkin_date,`checkout_date`=:checkout_date,`net_price`=:net_price,
                         netCurrencyID = :netCurrencyID,`sale_price`=:sale_price,saleCurrencyID = :saleCurrencyID,`country_id`=
                         :country_id,`staffID`=:staffID,passenger_name = :passenger_name WHERE hotel_id =:hotel_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':updcustomer_id', $_POST['Updcustomer_id']);
                         $stmt->bindParam(':supplier_id', $_POST['Supplier_ID']);
                         $stmt->bindParam(':hotel_name', $_POST['Hotel_Name']);
                         $stmt->bindParam(':checkin_date', $_POST['Checkin_Date']);
                         $stmt->bindParam(':checkout_date', $_POST['Checkout_Date']);
                         $stmt->bindParam(':net_price', $_POST['Net_Price']);
                         $stmt->bindParam(':netCurrencyID', $_POST['Net_Currency_Type']);
                         $stmt->bindParam(':sale_price', $_POST['Sale_Price']);
                         $stmt->bindParam(':saleCurrencyID', $_POST['Sale_Currency_Type']);
                         $stmt->bindParam(':country_id', $_POST['Country_ID']);
                         $stmt->bindParam(':staffID',$_SESSION['user_id']);
                         $stmt->bindParam(':passenger_name', $_POST['Passenger_Name']);
                         $stmt->bindParam(':hotel_id',$_POST['HotelID']);
                         $stmt->execute();
                echo "Success";
            }else{
                echo "NoPermission";
            }
               
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>