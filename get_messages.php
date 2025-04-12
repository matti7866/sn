<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

header('Content-Type: application/json');

// Function to generate consistent private chat ID
function generatePrivateChatId($user1, $user2) {
    $ids = [$user1, $user2];
    sort($ids);
    return implode('_', $ids);
}

$input_chat_id = $_GET['chat'] ?? 'main';
$user_id = $_SESSION['user_id'];

if ($input_chat_id === 'main') {
    $stmt = $pdo->prepare("
        SELECT cm.*, s.staff_name, s.staff_pic 
        FROM chat_messages cm 
        JOIN staff s ON cm.staff_id = s.staff_id 
        WHERE cm.chat_id = 'main' 
        ORDER BY cm.timestamp ASC
    ");
    $stmt->execute();
} else {
    $chat_id = generatePrivateChatId($user_id, $input_chat_id);
    $stmt = $pdo->prepare("
        SELECT cm.*, s.staff_name, s.staff_pic 
        FROM chat_messages cm 
        JOIN staff s ON cm.staff_id = s.staff_id 
        WHERE cm.chat_id = ? 
        ORDER BY cm.timestamp ASC
    ");
    $stmt->execute([$chat_id]);
}

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($messages);
exit();
?>