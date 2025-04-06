<?php
ob_start();
session_start();

// Log all errors to a file
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_reporting(E_ALL);

// Log request receipt
file_put_contents('request.log', "Request received: " . print_r($_FILES, true) . "\n", FILE_APPEND);
file_put_contents('debug.log', "Script started\n", FILE_APPEND);

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

function api_response($data)
{
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        api_response(['status' => 'error', 'message' => 'Invalid request method']);
    }

    if (!isset($_FILES['passportFile']) || $_FILES['passportFile']['error'] !== UPLOAD_ERR_OK) {
        api_response(['status' => 'error', 'message' => 'No valid passport file uploaded']);
    }

    $filePath = $_FILES['passportFile']['tmp_name'];
    $fileName = $_FILES['passportFile']['name'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    file_put_contents('debug.log', "File received: $fileName\n", FILE_APPEND);

    if (!in_array($extension, $allowedExtensions)) {
        api_response(['status' => 'error', 'message' => 'Invalid file type. Use JPG, JPEG, or PNG']);
    }
    if ($_FILES['passportFile']['size'] > 20971520) {
        api_response(['status' => 'error', 'message' => 'File size exceeds 20 MB']);
    }

    if (!function_exists('curl_init')) {
        file_put_contents('php_errors.log', "cURL extension not enabled\n", FILE_APPEND);
        api_response(['status' => 'error', 'message' => 'cURL extension not enabled']);
    }

    $apiKey = "31142325781399dc2de0f62dabdb03cb"; // Replace with your actual Mindee API key
    $url = "https://api.mindee.net/v1/products/mindee/passport/v1/predict";

    $ch = curl_init();
    $cFile = curl_file_create($filePath, mime_content_type($filePath), $fileName);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Token $apiKey"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['document' => $cFile]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    file_put_contents('debug.log', "HTTP Code: $httpCode, Response: $response, Error: $curlError\n", FILE_APPEND);

    if ($response === false || $httpCode !== 201) {
        curl_close($ch);
        api_response(['status' => 'error', 'message' => "API request failed (HTTP $httpCode): $curlError"]);
    }

    curl_close($ch);

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        api_response(['status' => 'error', 'message' => 'Invalid API response: ' . json_last_error_msg()]);
    }

    if (!isset($data['document']['inference']['prediction'])) {
        api_response(['status' => 'error', 'message' => 'No prediction data in API response']);
    }

    $prediction = $data['document']['inference']['prediction'];

    $result = [
        'status' => 'success',
        'message' => 'Passport data extracted successfully',
        'data' => [
            'passengerName' => implode(' ', array_column($prediction['given_names'], 'value')) . ' ' . $prediction['surname']['value'],
            'passportNumber' => $prediction['id_number']['value'],
            'passportExpiryDate' => $prediction['expiry_date']['value'],
            'dob' => $prediction['birth_date']['value'],
            'nationality' => $prediction['country']['value']
        ]
    ];

    api_response($result);
} catch (Exception $e) {
    file_put_contents('php_errors.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
    api_response(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>