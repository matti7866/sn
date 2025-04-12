<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

try {
    // Get all messages the user has access to (their private chats and main hall)
    $stmt = $pdo->prepare("
        SELECT 
            cm.id, 
            cm.staff_id, 
            s.staff_name,
            s.staff_pic,
            cm.chat_id, 
            cm.message, 
            cm.attachment, 
            cm.thumbnail,
            cm.filename, 
            cm.timestamp
        FROM 
            chat_messages cm 
        JOIN 
            staff s ON cm.staff_id = s.staff_id
        WHERE 
            cm.chat_id = 'main' 
            OR (cm.staff_id = ? AND cm.chat_id != 'main')
            OR (cm.chat_id = ? AND cm.staff_id != ?)
        ORDER BY 
            cm.timestamp DESC
        LIMIT 1000
    ");
    
    $stmt->execute([$user_id, $user_id, $user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($messages);
} catch (Exception $e) {
    error_log("Error in get_all_messages.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
exit();
?> 