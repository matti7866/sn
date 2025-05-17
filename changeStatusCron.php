<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
session_start();

include 'connection.php';


$data = $pdo->prepare("SELECT * FROM residence WHERE completedStep = 5 AND eVisaStatus = 'submitted'");
$data->execute();
$result = $data->fetchAll(PDO::FETCH_ASSOC);


echo "Total applications: " . count($result) . "<br>";
foreach ($result as $row) {
  $name = $row['passenger_name'];
  $data2 = $pdo->prepare("
  SELECT * FROM latest_emails WHERE UCASE(passenger_name) = UCASE(:name) AND email_status = 'E-Visa Approved'");
  $data2->execute(['name' => $name]);
  $result2 = $data2->fetchAll(PDO::FETCH_ASSOC);
  if (count($result2) > 0) {
    // update the eVisaStatus to 'approved'
    $data3 = $pdo->prepare("UPDATE residence SET eVisaStatus = 'accepted' WHERE residenceID = :residenceID");
    $data3->execute(['residenceID' => $row['residenceID']]);
    echo "Updated " . $row['residenceID'] . " - " . $name . "<br>";
  }
}


echo "Total applications: " . count($result) . "<br>";
foreach ($result as $row) {
  $name = $row['passenger_name'];
  $data2 = $pdo->prepare("
  SELECT * FROM latest_emails WHERE UCASE(passenger_name) = UCASE(:name) AND email_status = 'E-Visa Rejected'");
  $data2->execute(['name' => $name]);
  $result2 = $data2->fetchAll(PDO::FETCH_ASSOC);
  if (count($result2) > 0) {
    // update the eVisaStatus to 'approved'
    $data3 = $pdo->prepare("UPDATE residence SET eVisaStatus = 'rejected' WHERE residenceID = :residenceID");
    $data3->execute(['residenceID' => $row['residenceID']]);
    echo "Updated " . $row['residenceID'] . " - " . $name . "<br>";
  }
}
