<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

header('Content-Type: application/json');

$chat_id = $_GET['chat'] ?? 'main';
$user_id = $_SESSION['user_id'];

if ($chat_id === 'main') {
    $stmt = $pdo->prepare("
        SELECT cm.*, s.staff_name 
        FROM chat_messages cm 
        JOIN staff s ON cm.staff_id = s.staff_id 
        WHERE cm.chat_id = 'main' 
        ORDER BY cm.timestamp ASC
    ");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("
        SELECT cm.*, s.staff_name 
        FROM chat_messages cm 
        JOIN staff s ON cm.staff_id = s.staff_id 
        WHERE cm.chat_id = ? 
        AND (cm.staff_id = ? OR cm.chat_id = ?)
        ORDER BY cm.timestamp ASC
    ");
    $stmt->execute([$chat_id, $user_id, $user_id]);
}

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($messages);
exit();
?>