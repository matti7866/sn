<?php

require 'connection.php';

$query = "
    SELECT residenceID, passenger_name, residence.mb_number, company, company.company_number
    FROM residence 
    LEFT JOIN company ON company.company_id = residence.company
    WHERE 
      ((residence.completedStep = 2 AND residence.offerLetterStatus = 'submitted') OR (residence.completedStep = 4)) 
    AND residence.mb_number != ''
  ";
$stmt = $pdo->prepare($query);
$stmt->execute();
$residences = $stmt->fetchAll(PDO::FETCH_OBJ);

if (count($residences)) {


  $companyNumbers = [];
  foreach ($residences as $res) {
    if (!in_array($res->company_number, $companyNumbers)) {
      $companyNumbers[] = $res->company_number;
    }
  }

  if (count($companyNumbers)) {

    $apiPrefix = 'https://api.sntrips.com/trx/pendingPayments.php?companyNumber=';


    $finalList = [];
    foreach ($companyNumbers as $companyNumber) {
      $api = $apiPrefix . $companyNumber;
      $response = file_get_contents($api);
      $data = json_decode($response);

      if ($data->status == 'success') {
        $payments = $data->payments;
        foreach ($payments as $payment) {
          $finalList[$companyNumber][strtoupper($payment->name)] = array(
            'mb_number' => $payment->trxNumber,
            'labour_card_number' => $payment->labourCardNumber,
          );
        }
      }
    }

    // finally Loopthrough the residences and update the mb_number and labour_card_number
    $updatesCount = 0;
    $updates = [];
    foreach ($residences as $res) {
      $companyNumber = $res->company_number;
      $passengerName = strtoupper($res->passenger_name);
      if (isset($finalList[$companyNumber][$passengerName])) {
        $mb_number = $finalList[$companyNumber][$passengerName]['mb_number'];
        $labour_card_number = $finalList[$companyNumber][$passengerName]['labour_card_number'];
        // $updates[] = [
        //   'residenceID' => $res->residenceID,
        //   'name' => $passengerName,
        //   'mb_number' => $mb_number,
        //   'labour_card_number' => $labour_card_number,
        // ];

        $query = "
        UPDATE residence 
        SET 
          offerLetterStatus = 'accepted', 
          mb_number = :mb_number, 
          LabourCardNumber = :labour_card_number ,
          mohreStatus = 'Approved in MOHRE'

        WHERE residenceID = :residenceID";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
          'mb_number' => $mb_number,
          'labour_card_number' => $labour_card_number,
          'residenceID' => $res->residenceID,
        ]);
        $updatesCount++;
      }
    }

    echo "All Tasks are done : "  . $updatesCount;


    //echo '<pre>' . print_r($updates, true) . '</pre>';
  } else {
    echo 'No Company to check';
  }
} else {
  echo 'No Residence to check';
}
