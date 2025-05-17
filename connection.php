<?php
require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=snjst', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"));
$mysqli = new mysqli("localhost", "root", "", "snjst");

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