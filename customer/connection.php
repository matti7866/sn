<?php
$pdo = new PDO('mysql:host=localhost;dbname=sntravels_prod', 'sntravels_prod', 'MG2NCiDwWWt5jxcP', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
$mysqli = new mysqli("localhost", "sntravels_prod", "MG2NCiDwWWt5jxcP", "sntravels_prod");

if (!$pdo) {
    echo "PDO CONNECTION ERROR WITH DATABASE <br> CONTACT MATTIULLAH NADIRY";
    exit;
}

if ($mysqli->connect_error) {
    echo "MYSQLI CONNECTION ERROR WITH DATABASE: " . $mysqli->connect_error . "<br> CONTACT MATTIULLAH NADIRY";
    exit;
}

$settings = [];
$sql = $pdo->prepare("SELECT * FROM settings");
$sql->execute();
$set = $sql->fetchAll(PDO::FETCH_OBJ);
foreach ($set as $s) {
    $settings[$s->setting] = $s->value;
}

require 'functions.php';
?>