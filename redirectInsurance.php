<?php

$mb_number = $_GET['mb_number'];

if ($mb_number == '') {
  echo 'Invalid request';
  exit;
}

$data = file_get_contents('https://api.sntrips.com/trx/electronicPreApprovalPayment.php?appNumber=' . $mb_number);

$data = json_decode($data, true);


if ($data['status'] == 'success') {
  // redirect to the url
  header('Location: ' . $data['url']);
  exit;
} else {
  echo 'Sorry Record not found or already paid';
  exit;
}
