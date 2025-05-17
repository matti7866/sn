<?php
session_start();

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// Set CORS headers
header('Access-Control-Allow-Origin: https://pixlr.com');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

// Log requests for debugging
$logDir = '/www/wwwroot/sntravels/logs';
$logFile = $logDir . '/save-image.log';
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}
$logMessage = date('Y-m-d H:i:s') . ' - Method: ' . $_SERVER['REQUEST_METHOD'] . ' - Input: ' . file_get_contents('php://input') . ' - Query: ' . json_encode($_GET) . "\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);

// Handle Pixlr save request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $uploadDir = 'uploads/saved';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Handle POST (JSON payload) or GET (query parameter)
    $imageUrl = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        $imageUrl = $data['url'] ?? null;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $imageUrl = $_GET['url'] ?? null;
    }

    if ($imageUrl) {
        $filename = 'saved_' . time() . '_' . uniqid() . '.png';
        $target = $uploadDir . '/' . $filename;

        // Download the image
        $imageContent = file_get_contents($imageUrl);
        if ($imageContent !== false && file_put_contents($target, $imageContent)) {
            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                       '://' . $_SERVER['HTTP_HOST'] . 
                       dirname($_SERVER['SCRIPT_NAME']);
            $savedImageUrl = rtrim($baseUrl, '/') . '/' . $target;
            $_SESSION['editor_image'] = $savedImageUrl;
            http_response_code(200);
            echo json_encode(['status' => 'success', 'url' => $savedImageUrl]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to save image']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid request: No image URL provided']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>