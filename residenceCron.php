<?php

set_time_limit(0);
ini_set('memory_limit', '1024M');
date_default_timezone_set('Asia/Dubai');

require 'connection.php';

header("Content-Type: text/plain");

$query = $pdo->prepare("
SELECT residenceID, mb_number FROM residence 
WHERE ((completedStep = 2 AND offerLetterStatus = 'submitted') OR (completedStep = 4)) AND mb_number != ''
");
$query->execute();

$residences = $query->fetchAll(PDO::FETCH_OBJ);

if (count($residences)  == 0) {
  echo "No residence found";
  die();
}


foreach ($residences as $res) {
  // get uae application status from mb number;
  $mb_number = $res->mb_number;
  $residenceID = $res->residenceID;
  $url = 'https://api.sntrips.com/trx/?appNumber=' . $mb_number;
  $data = file_get_contents($url);
  $data = json_decode($data);
  if ($data->status == 'success') {
    $status = $data->data->status;
    $query = $pdo->prepare("UPDATE residence SET mohreStatus = :status, mohreStatusDateTime = :status_datetime WHERE residenceID = :residenceID");
    $query->bindParam(':status', $status);
    $query->bindParam(':status_datetime', date('Y-m-d H:i:s'));
    $query->bindParam(':residenceID', $residenceID);
    $query->execute();
    echo "{$res->residenceID} - {$res->mb_number} - {$status} \n";
  } else {
    echo "{$res->residenceID} - {$res->mb_number} - {$res->message} \n";
  }
}
echo 'All Status Updated';
