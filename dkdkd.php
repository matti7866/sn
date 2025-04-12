<?php
include 'connection.php';

function generatePrivateChatId($user1, $user2) {
    $ids = [$user1, $user2];
    sort($ids);
    return implode('_', $ids);
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all private chat messages
    $stmt = $pdo->query("
        SELECT id, staff_id, chat_id 
        FROM chat_messages 
        WHERE chat_id != 'main'
    ");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($messages as $message) {
        $new_chat_id = generatePrivateChatId($message['staff_id'], $message['chat_id']);
        $stmt = $pdo->prepare("
            UPDATE chat_messages 
            SET chat_id = ? 
            WHERE id = ?
        ");
        $stmt->execute([$new_chat_id, $message['id']]);
        error_log("Updated message ID {$message['id']} to chat_id '$new_chat_id'");
    }
    
    echo "Migration completed successfully.";
} catch (Exception $e) {
    error_log("Migration error: " . $e->getMessage());
    echo "Migration failed: " . $e->getMessage();
}
?>