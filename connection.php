<?php
require 'vendor/autoload.php';

// Try different database connection methods
$db_host = '127.0.0.1'; // Changed from 'localhost' to fix socket issues
$db_name = 'snjst';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));
} catch(PDOException $e) {
    // If 127.0.0.1 fails, try localhost as fallback
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=$db_name", $db_user, $db_pass, array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));
    } catch(PDOException $e2) {
        echo json_encode(['error' => 'PDO CONNECTION ERROR: ' . $e2->getMessage()]);
        exit;
    }
}

try {
    $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($mysqli->connect_error) {
        // Try localhost as fallback for mysqli too
        $mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
        if ($mysqli->connect_error) {
            echo json_encode(['error' => 'MYSQLI CONNECTION ERROR: ' . $mysqli->connect_error]);
            exit;
        }
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'MYSQLI CONNECTION ERROR: ' . $e->getMessage()]);
    exit;
}

$settings = [];
try {
    $sql = $pdo->prepare("SELECT * FROM settings");
    $sql->execute();
    $set = $sql->fetchAll(PDO::FETCH_OBJ);
    foreach ($set as $s) {
        $settings[$s->setting] = $s->value;
    }
} catch (Exception $e) {
    // Settings table might not exist, continue without it
    error_log("Settings table error: " . $e->getMessage());
}

require 'functions.php';
?>