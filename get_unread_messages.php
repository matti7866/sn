<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$unread_counts = [];

try {
    // Get the last time user viewed each chat from the session
    $last_viewed = $_SESSION['last_viewed_chats'] ?? [];
    
    // For main chat
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM chat_messages 
        WHERE chat_id = 'main' 
        AND staff_id != ? 
        AND timestamp > ?
    ");
    $stmt->execute([
        $user_id, 
        isset($last_viewed['main']) ? date('Y-m-d H:i:s', $last_viewed['main']) : '1970-01-01'
    ]);
    $unread_counts['main'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // For private chats
    $stmt = $pdo->prepare("
        SELECT COUNT(*) AS count
        FROM chat_messages cm
        WHERE cm.staff_id = ?
        AND cm.chat_id = ?
        AND cm.timestamp > ?
    ");
    
    // Get all staff members
    $staff_stmt = $pdo->prepare("SELECT staff_id FROM staff WHERE staff_id != ? AND status = 1");
    $staff_stmt->execute([$user_id]);
    $staff_members = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($staff_members as $staff) {
        $chat_id = $staff['staff_id'];
        $last_time = isset($last_viewed[$chat_id]) ? 
            date('Y-m-d H:i:s', $last_viewed[$chat_id]) : '1970-01-01';
            
        // Count messages from this staff member to current user
        $stmt->execute([$chat_id, $user_id, $last_time]);
        $unread_counts[$chat_id] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }
    
    // Update last viewed time for current chat
    if(isset($_GET['mark_read']) && $_GET['mark_read']) {
        $chat_id = $_GET['mark_read'];
        $_SESSION['last_viewed_chats'][$chat_id] = time();
    }
    
    echo json_encode($unread_counts);

} catch (Exception $e) {
    error_log("Error in get_unread_messages.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
?> 