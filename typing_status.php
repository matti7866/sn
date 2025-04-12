<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$response = ['typing' => []];

// Get username for display
$stmt = $pdo->prepare("SELECT staff_name FROM staff WHERE staff_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $user ? $user['staff_name'] : 'Unknown';

// Handle POST to update typing status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING) ?: 'main';
    $isTyping = (bool)filter_input(INPUT_POST, 'typing', FILTER_VALIDATE_INT);
    
    // Store typing status in session to avoid database overhead
    if (!isset($_SESSION['typing_status'])) {
        $_SESSION['typing_status'] = [];
    }
    
    // Format: [chat_id][user_id] = [timestamp, username]
    if ($isTyping) {
        $_SESSION['typing_status'][$chat_id][$user_id] = [time(), $username];
    } else {
        if (isset($_SESSION['typing_status'][$chat_id][$user_id])) {
            unset($_SESSION['typing_status'][$chat_id][$user_id]);
        }
    }
    
    echo json_encode(['success' => true]);
    exit();
}

// Handle GET to retrieve typing status
$chat_id = filter_input(INPUT_GET, 'chat_id', FILTER_SANITIZE_STRING) ?: 'main';

// No need to check typing status for oneself
if (!isset($_SESSION['typing_status']) || !isset($_SESSION['typing_status'][$chat_id])) {
    echo json_encode($response);
    exit();
}

// Get list of people typing (excluding current user)
$now = time();
$typing_timeout = 5; // 5 seconds timeout

foreach ($_SESSION['typing_status'][$chat_id] as $typer_id => $info) {
    if ($typer_id != $user_id && ($now - $info[0]) < $typing_timeout) {
        $response['typing'][] = $info[1];
    }
}

echo json_encode($response);
exit();
?> 