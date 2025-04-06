<?php
    try{
        $conn = new PDO('mysql:host=localhost;dbname=sntravels_prod', 'sntravels_prod', 'MG2NCiDwWWt5jxcP');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        die();
    }    
?>
