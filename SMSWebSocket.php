<?php
require 'vendor/autoload.php'; // Install: composer require textalk/websocket
use WebSocket\Client;

include 'connection.php'; // Your database connection file

// Pushbullet Access Token
$accessToken = "o.ueA3EdkFdcoxmX3U2MkSwHPU047iO4cG"; // Your verified token

// WebSocket connection
$client = new Client("wss://stream.pushbullet.com/websocket/{$accessToken}");
echo "Connected to Pushbullet WebSocket\n";
error_log("Connected to Pushbullet WebSocket");

// Ensure logging works
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/error.log'); // Adjust path if needed

while (true) {
    try {
        $message = $client->receive();
        $data = json_decode($message, true);
        error_log("WebSocket Message: " . json_encode($data));

        // Check for SMS mirrored notifications
        if (isset($data['type']) && $data['type'] === 'push' && 
            isset($data['push']['type']) && $data['push']['type'] === 'mirror') {
            $sender = $data['push']['title'] ?? 'Unknown';
            $messageText = $data['push']['body'] ?? '';
            $timestamp = isset($data['push']['created']) ? date('Y-m-d H:i:s', $data['push']['created']) : date('Y-m-d H:i:s');
            $pushbulletId = $data['push']['iden'] ?? uniqid();

            // Store in database
            $stmt = $pdo->prepare("
                INSERT INTO sms_messages (sender, message, timestamp, pushbullet_id, status)
                VALUES (:sender, :message, :timestamp, :pushbullet_id, 'Received')
                ON DUPLICATE KEY UPDATE message = :message
            ");
            $stmt->execute([
                ':sender' => $sender,
                ':message' => $messageText,
                ':timestamp' => $timestamp,
                ':pushbullet_id' => $pushbulletId
            ]);

            echo "Stored SMS: $sender - $messageText\n";
            error_log("Stored SMS: $sender - $messageText");
        }
    } catch (Exception $e) {
        error_log("WebSocket Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage() . "\n";
        sleep(5); // Wait before reconnecting
        $client = new Client("wss://stream.pushbullet.com/websocket/{$accessToken}");
    }
}
?>