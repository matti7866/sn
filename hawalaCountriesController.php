<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    if(isset($_POST['Insert_CountryName'])){
        try{
                    $sql = "INSERT INTO `hawala_countries`(`country_name`) VALUES(:country_name)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':country_name', $_POST['country_name']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetCountriesNameReport'])){
            $selectQuery = $pdo->prepare("SELECT * FROM hawala_countries ORDER BY country_name");
            $selectQuery->execute();
            /* Fetch all of the remaining rows in the result set */
            $rpt = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
            // encoding array to json format
            echo json_encode($rpt);
    }else if(isset($_POST['Delete'])){
        try{
                        $sql = "DELETE FROM hawala_countries WHERE country_id = :country_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':country_id', $_POST['ID']);
                        $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM hawala_countries WHERE country_id = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['Update_CountryName'])){
        try{
                $sql = "UPDATE `hawala_countries` SET country_name = :country_name WHERE country_id = :country_id";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':country_name', $_POST['updcountry_name']);
                $stmt->bindParam(':country_id', $_POST['country_id']);
                // execute the prepared statement
                $stmt->execute();
            echo "Success";
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>