<?php

set_time_limit(0);
ini_set('memory_limit', '1024M');
date_default_timezone_set('Asia/Dubai');

require 'connection.php';

header("Content-Type: text/plain");

// Select all transactions with transaction numbers
$query = $pdo->prepare("
SELECT id, transaction_number FROM tasheel_transactions 
WHERE transaction_number != ''
");
$query->execute();

$transactions = $query->fetchAll(PDO::FETCH_OBJ);

if (count($transactions) == 0) {
  echo "No transactions found that need status updates";
  die();
}

// Loop through each transaction and update its mohrestatus
foreach ($transactions as $trx) {
  // Get tasheel application status from transaction number
  $transaction_number = trim($trx->transaction_number);
  $id = $trx->id;
  
  // Skip if transaction number is too short or invalid
  if (strlen($transaction_number) < 3) {
    echo "Transaction ID: {$id} - Number: {$transaction_number} - Skipped: Transaction number too short or invalid \n";
    continue;
  }
  
  // Make API call to get status
  $url = 'https://api.sntrips.com/trx/?appNumber=' . urlencode($transaction_number);
  
  // Use try-catch to handle API errors
  try {
    $data = file_get_contents($url);
    echo "API Response for transaction {$transaction_number}: " . $data . "\n";
    $data = json_decode($data);
    
    if ($data && isset($data->status) && $data->status == 'success') {
      // Set default values
      $status = isset($data->data->status) ? $data->data->status : '';
      $transaction_type = isset($data->data->transaction_type) ? $data->data->transaction_type : '';
      $company_name = isset($data->data->company) ? $data->data->company : '';
      $emirates = isset($data->data->emirates) ? $data->data->emirates : '';
      
      // Always update the record with all available information
      $query = $pdo->prepare("
        UPDATE tasheel_transactions 
        SET 
          mohrestatus = :status, 
          last_status_check = :status_datetime,
          api_transaction_type = :transaction_type,
          api_company_name = :company_name,
          api_emirates = :emirates
        WHERE id = :id
      ");
      $query->bindParam(':status', $status);
      $query->bindParam(':status_datetime', date('Y-m-d H:i:s'));
      $query->bindParam(':transaction_type', $transaction_type);
      $query->bindParam(':company_name', $company_name);
      $query->bindParam(':emirates', $emirates);
      $query->bindParam(':id', $id);
      $query->execute();
      
      if (!empty($status)) {
        echo "Transaction ID: {$id} - Number: {$transaction_number} - New Status: {$status} \n";
      } else {
        echo "Transaction ID: {$id} - Number: {$transaction_number} - Status empty, but updated other fields \n";
      }
    } else {
      $error_message = isset($data->message) ? $data->message : 'Unknown API error';
      echo "Transaction ID: {$id} - Number: {$transaction_number} - Error: {$error_message} \n";
    }
  } catch (Exception $e) {
    echo "Transaction ID: {$id} - Number: {$transaction_number} - Exception: {$e->getMessage()} \n";
  }
  
  // Add a small delay to avoid overwhelming the API
  usleep(200000); // 0.2 seconds
}

echo 'All transaction statuses updated successfully'; 