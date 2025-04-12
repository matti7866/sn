<?php
include 'header.php';
?>
<title>Staff Chatroom</title>
<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-database-compat.js"></script>
<!-- Firebase Configuration -->
<script src="firebase-config.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';

// Fetch all active staff members
$stmt = $pdo->prepare("SELECT staff_id, staff_name, staff_pic FROM staff WHERE staff_id != ? AND status = 1");
$stmt->execute([$_SESSION['user_id']]);
$staff_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch last message for each chat (Main Hall and private chats)
$last_messages = [];
$user_id = $_SESSION['user_id'];

// Function to generate consistent private chat ID
function generatePrivateChatId($user1, $user2) {
    $ids = [$user1, $user2];
    sort($ids);
    return implode('_', $ids);
}

// Main Hall last message
$stmt = $pdo->prepare("
    SELECT cm.message, cm.timestamp, cm.staff_id 
    FROM chat_messages cm 
    WHERE cm.chat_id = 'main' 
    ORDER BY cm.timestamp DESC 
    LIMIT 1
");
$stmt->execute();
$main_last = $stmt->fetch(PDO::FETCH_ASSOC);
$last_messages['main'] = $main_last ? ($main_last['staff_id'] == $user_id ? "You: " . ($main_last['message'] ?? '[Attachment]') : ($main_last['message'] ?? '[Attachment]')) : '';

// Private chats last message
foreach ($staff_members as $staff) {
    $chat_id = generatePrivateChatId($user_id, $staff['staff_id']);
    $stmt = $pdo->prepare("
        SELECT cm.message, cm.timestamp, cm.staff_id 
        FROM chat_messages cm 
        WHERE cm.chat_id = ? 
        ORDER BY cm.timestamp DESC 
        LIMIT 1
    ");
    $stmt->execute([$chat_id]);
    $last = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_messages[$staff['staff_id']] = $last ? ($last['staff_id'] == $user_id ? "You: " . ($last['message'] ?? '[Attachment]') : ($last['message'] ?? '[Attachment]')) : '';
}
?>

<div id="content" class="app-content">
    <div class="chat-wrapper">
        <!-- Staff List Sidebar -->
        <div class="staff-list">
            <div class="staff-list-header">
                <i class="fa fa-users"></i> Staff Chat
            </div>
            <div class="staff-list-content">
                <div class="chat-room-item active" data-chat="main" onclick="switchChat('main')">
                    <img src="assets/default-avatar.png" alt="Main Hall" class="profile-pic">
                    <div class="chat-info">
                        <span>Main Hall</span>
                        <div class="last-message"><?php echo htmlspecialchars(substr($last_messages['main'], 0, 30)) . (strlen($last_messages['main']) > 30 ? '...' : ''); ?></div>
                    </div>
                </div>
                <?php foreach ($staff_members as $staff): ?>
                    <div class="chat-room-item" data-chat="<?php echo $staff['staff_id']; ?>" 
                         onclick="switchChat('<?php echo $staff['staff_id']; ?>')">
                        <img src="<?php echo $staff['staff_pic'] ?: 'assets/default-avatar.png'; ?>" 
                             alt="<?php echo htmlspecialchars($staff['staff_name']); ?>" class="profile-pic">
                        <div class="chat-info">
                            <span><?php echo htmlspecialchars($staff['staff_name']); ?></span>
                            <div class="last-message"><?php echo htmlspecialchars(substr($last_messages[$staff['staff_id']], 0, 30)) . (strlen($last_messages[$staff['staff_id']]) > 30 ? '...' : ''); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <div class="chat-header">
                <img id="chat-header-avatar" src="assets/default-avatar.png" alt="Chat" class="chat-header-avatar">
                <div class="chat-header-info">
                    <h4 id="chat-title" class="chat-header-name">Main Hall</h4>
                    <div class="chat-header-status" id="chat-status"></div>
                </div>
                <div class="chat-header-actions">
                    <button class="chat-header-action" title="Search messages" id="search-messages-btn"><i class="fa fa-search"></i></button>
                    <button class="chat-header-action" title="Refresh messages" onclick="loadMessages()"><i class="fa fa-sync-alt"></i></button>
                </div>
            </div>
            
            <!-- Search Modal -->
            <div id="search-modal" class="search-modal">
                <div class="search-modal-content">
                    <div class="search-header">
                        <h3>Search Messages</h3>
                        <button id="close-search-btn" class="close-search-btn">×</button>
                    </div>
                    <div class="search-form">
                        <input type="text" id="search-input" placeholder="Type to search...">
                        <button id="do-search-btn">Search</button>
                    </div>
                    <div class="search-results" id="search-results"></div>
                </div>
            </div>
            
            <input type="hidden" id="current-chat" value="main">
            <div id="chat-box">
                <div class="typing-indicator">
                    <span id="typing-name"></span> is typing
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
            
            <form id="message-form" enctype="multipart/form-data" class="chat-form">
                <div class="input-group">
                    <button type="button" id="emoji-btn" class="chat-control" title="Emojis"><i class="fa fa-smile"></i></button>
                    <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                    <label for="attachment-input" class="chat-control" title="Attach files"><i class="fa fa-paperclip"></i></label>
                    <input type="file" id="attachment-input" accept="image/*,.pdf" multiple style="display:none;">
                </div>
                <button type="submit" class="send-btn"><i class="fa fa-paper-plane"></i></button>
            </form>
            <div id="emoji-picker" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1.0.0/index.js" type="module"></script>

<style>
    :root {
        --primary-color: #4a6ee0;
        --secondary-color: #e9ecef;
        --accent-color: #3c5dbc;
        --text-primary: #333;
        --text-secondary: #666;
        --text-light: #fff;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --border-radius: 15px;
        --shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    body {
        background-color: #f5f7fa;
    }
    
    #content.app-content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        position: fixed !important;
        left: 0 !important;
        margin-left: 220px !important;
        width: calc(100% - 220px) !important;
        transition: margin-left 0.2s, width 0.2s;
        z-index: 1;
        top: 60px !important;
        height: calc(100vh - 60px) !important;
        overflow: hidden;
        pointer-events: auto;
    }
    
    body.sidebar-minify #content.app-content {
        margin-left: 60px !important;
        width: calc(100% - 60px) !important;
    }
    
    .chat-wrapper {
        max-width: 100%;
        margin: 0;
        display: flex;
        gap: 0;
        height: 100%;
        min-height: 500px;
        box-shadow: none;
        border-radius: 0;
        overflow: hidden;
        background: white;
        position: relative;
    }
    
    .staff-list {
        width: 280px;
        background: #fff;
        border-right: 1px solid #eaeaea;
        height: 100%;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }
    
    .staff-list-header {
        padding: 20px;
        background: var(--primary-color);
        color: white;
        font-weight: bold;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .staff-list-header i {
        font-size: 20px;
    }
    
    .staff-list-content {
        padding: 10px;
        flex-grow: 1;
        overflow-y: auto;
    }
    
    .chat-container {
        flex-grow: 1;
        max-width: none;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .chat-header {
        padding: 15px 20px;
        background: white;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .chat-header-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .chat-header-info {
        flex-grow: 1;
    }
    
    .chat-header-name {
        font-weight: bold;
        font-size: 16px;
        margin: 0;
    }
    
    .chat-header-status {
        font-size: 12px;
        color: var(--text-secondary);
    }
    
    .chat-header-actions {
        display: flex;
        gap: 15px;
    }
    
    .chat-header-action {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 18px;
    }
    
    .chat-header-action:hover {
        color: var(--primary-color);
    }
    
    .chat-room-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin: 5px 0;
        cursor: pointer;
        border-radius: 10px;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .chat-room-item:hover {
        background: #f5f7fa;
    }
    
    .chat-room-item.active {
        background: rgba(74, 110, 224, 0.1);
    }
    
    .unread-badge {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--danger-color);
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
        font-weight: bold;
    }
    
    .profile-pic {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }
    
    .chat-info {
        flex-grow: 1;
        overflow: hidden;
    }
    
    .chat-info span {
        font-weight: 600;
        display: block;
        margin-bottom: 3px;
        color: var(--text-primary);
        font-size: 14px;
    }
    
    .last-message {
        font-size: 12px;
        color: var(--text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    #chat-box {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        background: #f5f7fa;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    
    .message {
        margin: 8px 0;
        display: flex;
        flex-direction: column;
        max-width: 75%;
        animation: fadeIn 0.3s ease;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.sent {
        align-self: flex-end;
        align-items: flex-end;
    }
    
    .message.received {
        align-self: flex-start;
        align-items: flex-start;
    }
    
    .message-info {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
        gap: 8px;
    }
    
    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .staff-name {
        font-size: 12px;
        color: var(--text-secondary);
    }
    
    .message-bubble {
        padding: 12px 16px;
        border-radius: 18px;
        margin-bottom: 2px;
        word-wrap: break-word;
        font-size: 14px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .sent .message-bubble {
        background: var(--primary-color);
        color: white;
        border-top-right-radius: 4px;
    }
    
    .received .message-bubble {
        background: white;
        color: var(--text-primary);
        border-top-left-radius: 4px;
    }
    
    .message-bubble .emoji {
        font-size: 24px;
    }
    
    .message .emoji-only {
        font-size: 48px;
        background: transparent !important;
        box-shadow: none;
        padding: 0;
    }
    
    .new-message {
        animation: newMessage 0.3s ease;
    }
    
    @keyframes newMessage {
        0% { transform: translateY(20px); opacity: 0; }
        100% { transform: translateY(0); opacity: 1; }
    }
    
    .timestamp {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 2px;
        opacity: 0.8;
    }
    
    .message .attachment-preview {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        margin-top: 5px;
    }
    
    .message img {
        max-width: 250px;
        max-height: 200px;
        border-radius: 12px;
        display: block;
    }
    
    .message .attachment-actions {
        display: flex;
        padding: 8px;
        gap: 8px;
    }
    
    .message .attachment-actions a,
    .message .attachment-actions button {
        padding: 6px 12px;
        font-size: 12px;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
        flex: 1;
        text-align: center;
    }
    
    .attachment-actions button {
        background: var(--primary-color);
        color: white;
    }
    
    .attachment-actions a {
        background: #e9ecef;
        color: var(--text-primary);
    }
    
    .message .attachment-actions button:hover {
        background: var(--accent-color);
    }
    
    .message .attachment-actions a:hover {
        background: #dde1e4;
    }
    
    .message .attachment-filename {
        padding: 4px 8px 8px;
        font-size: 12px;
        color: var(--text-secondary);
        word-wrap: break-word;
    }
    
    .chat-form {
        padding: 15px;
        border-top: 1px solid #eaeaea;
        background: white;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .input-group {
        flex-grow: 1;
        display: flex;
        align-items: center;
        background: #f5f7fa;
        border-radius: 24px;
        padding: 8px 15px;
        transition: all 0.2s;
    }
    
    .input-group:focus-within {
        background: white;
        box-shadow: 0 0 0 2px var(--primary-color);
    }
    
    #message-input {
        flex-grow: 1;
        border: none;
        outline: none;
        background: transparent;
        padding: 8px 0;
        font-size: 14px;
    }
    
    .chat-control {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    
    .chat-control:hover {
        color: var(--primary-color);
    }
    
    .send-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        padding: 0;
    }
    
    .send-btn i {
        font-size: 18px;
    }
    
    .send-btn:hover {
        background: var(--accent-color);
    }
    
    .typing-indicator {
        display: none;
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        color: var(--text-secondary);
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        z-index: 5;
    }
    
    .typing-indicator span {
        margin-left: 5px;
    }
    
    .typing-dot {
        display: inline-block;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--text-secondary);
        margin: 0 1px;
        animation: typingAnimation 1.4s infinite;
    }
    
    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }
    
    @keyframes typingAnimation {
        0%, 60%, 100% { opacity: 0.3; transform: translateY(0); }
        30% { opacity: 1; transform: translateY(-5px); }
    }
    
    #emoji-picker {
        position: absolute;
        bottom: 80px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        z-index: 1000;
        max-width: 320px;
    }
    
    @media (max-width: 768px) {
        .chat-wrapper {
            flex-direction: column;
            height: calc(100vh - 60px);
        }
        
        .staff-list {
            width: 100%;
            height: auto;
            max-height: 200px;
        }
        
        .message {
            max-width: 85%;
        }
        
        #content.app-content {
            margin-left: 0;
            width: 100%;
        }
    }

    .app-sidebar, .app-sidebar-content, .menu {
        z-index: 10 !important;
    }

    .app-header {
        z-index: 10 !important;
    }

    #sidebar {
        z-index: 1030 !important;
        pointer-events: auto !important;
    }

    .menu .menu-item {
        position: relative !important;
        z-index: 1050 !important;
        pointer-events: auto !important;
    }

    .app-sidebar-content {
        z-index: 1040 !important;
    }

    .menu-toggler, 
    .menu-caret,
    .menu-submenu {
        pointer-events: auto !important;
        z-index: 1060 !important;
    }

    /* Search Modal Styles */
    .search-modal {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2000;
        justify-content: center;
        align-items: center;
    }
    
    .search-modal-content {
        background-color: white;
        width: 90%;
        max-width: 600px;
        max-height: 80%;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .search-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eaeaea;
    }
    
    .search-header h3 {
        margin: 0;
        font-size: 18px;
        color: var(--text-primary);
    }
    
    .close-search-btn {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: var(--text-secondary);
    }
    
    .search-form {
        display: flex;
        padding: 15px;
        border-bottom: 1px solid #eaeaea;
    }
    
    #search-input {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 4px 0 0 4px;
        font-size: 14px;
    }
    
    #do-search-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 0 20px;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
    }
    
    .search-results {
        padding: 15px;
        overflow-y: auto;
        flex-grow: 1;
    }
    
    .search-result-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    
    .search-result-item:hover {
        background-color: #f5f7fa;
    }
    
    .search-result-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }
    
    .search-result-sender {
        font-weight: bold;
        font-size: 14px;
    }
    
    .search-result-time {
        font-size: 12px;
        color: var(--text-secondary);
    }
    
    .search-result-content {
        font-size: 14px;
    }
    
    .search-highlight {
        background-color: #ffffa0;
        font-weight: bold;
    }
    
    /* Fix z-index for navbar and menu items */
    #header {
        z-index: 2010 !important;
    }
    
    #sidebar, .app-sidebar {
        z-index: 2005 !important;
        position: relative !important;
    }
    
    .app-sidebar .menu, 
    .app-sidebar .menu .menu-item,
    .app-sidebar .menu .menu-submenu,
    .app-sidebar .menu .menu-caret {
        z-index: 2006 !important;
        position: relative !important;
    }
    
    .app-content {
        position: relative !important;
        z-index: 1 !important;
    }
    
    /* Make sure menu submenus can be seen */
    .app-sidebar .menu .menu-submenu {
        position: absolute !important;
    }

    .message.highlight-message {
        animation: highlight-pulse 2s ease;
    }

    @keyframes highlight-pulse {
        0%, 100% { box-shadow: none; }
        50% { box-shadow: 0 0 20px rgba(255, 213, 79, 0.7); }
    }
    
    /* Loading indicator for file uploads */
    .loading-indicator {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Welcome message, history indicator and loading styles */
    .welcome-message {
        text-align: center;
        margin: 30px auto;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
        max-width: 400px;
    }

    .welcome-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 15px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .welcome-message h3 {
        margin: 0 0 10px;
        color: var(--text-primary);
    }

    .welcome-message p {
        margin: 0;
        color: var(--text-secondary);
    }

    .history-indicator {
        display: flex;
        align-items: center;
        margin: 20px 0;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .history-line {
        flex-grow: 1;
        height: 1px;
        background: #ddd;
    }

    .history-indicator span {
        padding: 0 10px;
    }

    .loading-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
        color: var(--text-secondary);
    }

    .spinner-large {
        width: 40px;
        height: 40px;
        border: 3px solid rgba(0,0,0,0.1);
        border-radius: 50%;
        border-top-color: var(--primary-color);
        animation: spin 1s ease-in-out infinite;
        margin-bottom: 15px;
    }

    .error-message {
        text-align: center;
        margin: 30px;
        color: var(--danger-color);
    }

    .error-message i {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .error-message button {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        margin-top: 15px;
        cursor: pointer;
    }

    /* Attachment styles */
    .image-attachment {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .image-attachment img {
        max-width: 250px;
        max-height: 200px;
        display: block;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .image-attachment img:hover {
        transform: scale(1.02);
    }

    .image-attachment.has-thumbnail img.thumbnail {
        filter: brightness(0.95);
    }

    .pdf-attachment, .file-attachment {
        height: 120px;
        width: 100px;
        background: #f5f7fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .pdf-icon, .file-icon {
        font-size: 40px;
        color: #e74c3c;
    }

    .file-icon {
        color: #3498db;
    }

    .attachment-actions {
        display: flex;
        gap: 8px;
    }

    .attachment-actions button,
    .attachment-actions a {
        padding: 6px 12px;
        font-size: 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
        flex: 1;
        justify-content: center;
    }

    .attachment-actions button {
        background: var(--primary-color);
        color: white;
    }

    .attachment-actions a {
        background: #f0f2f5;
        color: var(--text-primary);
        text-decoration: none;
    }

    .attachment-actions button:hover {
        background: var(--accent-color);
    }

    .attachment-actions a:hover {
        background: #e4e6e8;
    }

    /* Message link styles */
    .message-bubble a {
        color: inherit;
        text-decoration: underline;
        word-break: break-all;
    }

    .sent .message-bubble a {
        color: white;
        opacity: 0.9;
    }

    .sent .message-bubble a:hover {
        opacity: 1;
    }
</style>

<script>
    let isUserAtBottom = true;
    let lastMessageId = null;
    let currentChat = 'main';
    let lastReadTimes = {};
    let typingTimeout = null;
    let typingUsers = {};
    let firebaseListeners = [];
    let allMessages = [];

    // Function to generate consistent private chat ID
    function generatePrivateChatId(user1, user2) {
        const ids = [user1, user2].sort();
        return ids.join('_');
    }

    function sanitizeFirebasePath(chatId) {
        if (chatId !== 'main') {
            const currentUserId = '<?php echo $_SESSION['user_id']; ?>';
            return generatePrivateChatId(currentUserId, chatId).replace(/[.#$[\]]/g, '_');
        }
        return chatId.replace(/[.#$[\]]/g, '_');
    }

    // Search functions
    function setupSearch() {
        const searchBtn = document.getElementById('search-messages-btn');
        const closeBtn = document.getElementById('close-search-btn');
        const doSearchBtn = document.getElementById('do-search-btn');
        const searchModal = document.getElementById('search-modal');
        const searchInput = document.getElementById('search-input');
        
        searchBtn.addEventListener('click', () => {
            searchModal.style.display = 'flex';
            searchInput.focus();
        });
        
        closeBtn.addEventListener('click', () => {
            searchModal.style.display = 'none';
        });
        
        searchModal.addEventListener('click', e => {
            if (e.target === searchModal) {
                searchModal.style.display = 'none';
            }
        });
        
        doSearchBtn.addEventListener('click', performSearch);
        
        searchInput.addEventListener('keypress', e => {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
    
    function performSearch() {
        const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
        const resultsContainer = document.getElementById('search-results');
        
        if (!searchTerm) {
            resultsContainer.innerHTML = '<p>Please enter a search term</p>';
            return;
        }
        
        if (allMessages.length === 0) {
            fetchAllMessages().then(() => {
                displaySearchResults(searchTerm, resultsContainer);
            });
        } else {
            displaySearchResults(searchTerm, resultsContainer);
        }
    }
    
    function displaySearchResults(searchTerm, resultsContainer) {
        const results = allMessages.filter(msg => 
            (msg.message && msg.message.toLowerCase().includes(searchTerm)) ||
            (msg.filename && msg.filename.toLowerCase().includes(searchTerm))
        );
        
        if (results.length === 0) {
            resultsContainer.innerHTML = '<p>No messages found containing "' + searchTerm + '"</p>';
            return;
        }
        
        results.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));
        
        let html = '';
        results.forEach(msg => {
            const messageDate = msg.timestamp ? new Date(msg.timestamp) : new Date();
            const formattedTime = messageDate.toLocaleString();
            
            let content = '';
            if (msg.message) {
                const highlightedMessage = msg.message.replace(
                    new RegExp(searchTerm, 'gi'),
                    match => `<span class="search-highlight">${match}</span>`
                );
                content = highlightedMessage;
            } else if (msg.filename) {
                content = `Attachment: ${msg.filename}`;
            }
            
            html += `
            <div class="search-result-item" data-message-id="${msg.id}" data-chat-id="${msg.chat_id}">
                <div class="search-result-header">
                    <div class="search-result-sender">${msg.staff_name || 'Unknown'}</div>
                    <div class="search-result-time">${formattedTime}</div>
                </div>
                <div class="search-result-content">${content}</div>
            </div>`;
        });
        
        resultsContainer.innerHTML = html;
        
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const chatId = this.dataset.chatId;
                const messageId = this.dataset.messageId;
                
                document.getElementById('search-modal').style.display = 'none';
                
                if (chatId !== currentChat) {
                    switchChat(chatId);
                    setTimeout(() => scrollToMessage(messageId), 500);
                } else {
                    scrollToMessage(messageId);
                }
            });
        });
    }
    
    function scrollToMessage(messageId) {
        const messageElement = document.querySelector(`.message[data-id="${messageId}"]`);
        if (messageElement) {
            messageElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            messageElement.classList.add('highlight-message');
            setTimeout(() => {
                messageElement.classList.remove('highlight-message');
            }, 2000);
        }
    }
    
    function fetchAllMessages() {
        return new Promise((resolve, reject) => {
            fetch(`get_all_messages.php`, { cache: 'no-store' })
                .then(response => {
                    if (!response.ok) throw new Error('Network response not ok');
                    return response.json();
                })
                .then(data => {
                    if (data && Array.isArray(data)) {
                        allMessages = data;
                        resolve();
                    } else {
                        reject('Invalid data format');
                    }
                })
                .catch(error => {
                    console.error('Error fetching messages for search:', error);
                    reject(error);
                });
        });
    }
    
    function subscribeToFirebaseMessages(chatId) {
        if (firebaseListeners.length > 0) {
            firebaseListeners.forEach(unsubscribe => unsubscribe());
            firebaseListeners = [];
        }
        
        console.log(`Subscribing to Firebase messages for chat: ${chatId}`);
        
        const safeChatId = sanitizeFirebasePath(chatId);
        const messagesRef = database.ref('chats/' + safeChatId + '/messages');
        
        const unsubscribe = messagesRef.orderByChild('timestamp').limitToLast(50).on('child_added', 
            snapshot => {
                const message = snapshot.val();
                if (message && message.id > (lastMessageId || 0)) {
                    console.log("New message added via Firebase:", message);
                    addMessageToUI(message, true);
                }
            }, 
            error => {
                console.error("Firebase message subscription error:", error);
            }
        );
        
        firebaseListeners.push(() => messagesRef.off('child_added'));
        
        const typingRef = database.ref('chats/' + safeChatId + '/typing');
        const typingUnsubscribe = typingRef.on('value', 
            snapshot => {
                updateTypingIndicator(snapshot.val());
            },
            error => {
                console.error("Firebase typing subscription error:", error);
            }
        );
        
        firebaseListeners.push(() => typingRef.off('value'));
    }
    
    function updateTypingIndicator(typingData) {
        const typingIndicator = document.querySelector('.typing-indicator');
        const typingName = document.getElementById('typing-name');
        const currentUserId = '<?php echo $_SESSION['user_id']; ?>';
        
        if (!typingData || !typingIndicator) return;
        
        const typingUsers = Object.entries(typingData)
            .filter(([userId, status]) => status.isTyping && userId !== currentUserId)
            .map(([userId, status]) => status.name);
        
        if (typingUsers.length > 0) {
            typingName.textContent = typingUsers[0];
            typingIndicator.style.display = 'block';
        } else {
            typingIndicator.style.display = 'none';
        }
    }
    
    function addMessageToUI(message, isNew = false) {
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;
        
        const currentUserId = '<?php echo $_SESSION['user_id']; ?>';
        const isSent = parseInt(message.staff_id) === parseInt(currentUserId);
        const messageClass = isSent ? 'sent' : 'received';
        const staffName = message.staff_name || 'Unknown User';
        const staffPic = message.staff_pic || 'assets/default-avatar.png';
        
        const isEmojiOnly = message.type === 'text' && message.message && 
            /^[\p{Emoji}\s]+$/u.test(message.message) &&
            message.message.length <= 5;
        
        const messageDate = message.timestamp ? new Date(message.timestamp) : new Date();
        let formattedTime;
        const today = new Date();
        
        if (messageDate.toDateString() === today.toDateString()) {
            formattedTime = messageDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } else {
            formattedTime = messageDate.toLocaleDateString([], {month: 'short', day: 'numeric'}) + 
                            ' ' + messageDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        }
        
        let messageHTML = `
            <div class="message ${messageClass} ${isNew ? 'new-message' : ''}" data-id="${message.id}">`;
            
        if (!isSent) {
            messageHTML += `
                <div class="message-info">
                    <img src="${staffPic}" class="message-avatar" alt="${staffName}">
                    <span class="staff-name">${staffName}</span>
                </div>`;
        }
                
        if (message.type === 'text' && message.message) {
            let processedMessage = message.message;
            processedMessage = processedMessage.replace(
                /(https?:\/\/[^\s]+)/g, 
                '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
            );
            const messageWithBigEmojis = processedMessage.replace(
                /([\uD800-\uDBFF][\uDC00-\uDFFF])/g, 
                '<span class="emoji">$1</span>'
            );
            messageHTML += `<div class="message-bubble ${isEmojiOnly ? 'emoji-only' : ''}">${messageWithBigEmojis}</div>`;
        } else if (message.type === 'attachment' && message.attachment) {
            messageHTML += `<div class="attachment-preview">`;
            if (message.attachment.match(/\.(jpeg|jpg|png|gif)$/i)) {
                const useThumb = message.thumbnail ? true : false;
                messageHTML += `
                    <div class="image-attachment ${useThumb ? 'has-thumbnail' : ''}">
                        <img src="${useThumb ? message.thumbnail : message.attachment}" 
                             alt="${message.filename || 'Image'}"
                             ${useThumb ? 'class="thumbnail"' : ''}
                             onclick="window.open('${message.attachment}', '_blank')">
                    </div>`;
            } else if (message.attachment.match(/\.pdf$/i)) {
                messageHTML += `
                    <div class="pdf-attachment">
                        <div class="pdf-icon"><i class="fa fa-file-pdf"></i></div>
                    </div>`;
            } else {
                messageHTML += `
                    <div class="file-attachment">
                        <div class="file-icon"><i class="fa fa-file"></i></div>
                    </div>`;
            }
            messageHTML += `
                <div class="attachment-actions">
                    ${message.attachment.match(/\.pdf$/i) ? 
                      `<button onclick="window.open('${message.attachment}', '_blank')">
                          <i class="fa fa-eye"></i> View
                       </button>` : ''}
                    <a href="${message.attachment}" download="${message.filename || 'attachment'}">
                        <i class="fa fa-download"></i> Download
                    </a>
                </div>
                <div class="attachment-filename">${message.filename || 'Unnamed file'}</div>
            </div>`;
        }
        messageHTML += `<span class="timestamp">${formattedTime}</span></div>`;
        
        const wasAtBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 50;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = messageHTML;
        
        if (chatBox.querySelector('.typing-indicator')) {
            chatBox.appendChild(tempDiv.firstElementChild);
        } else {
            chatBox.appendChild(tempDiv.firstElementChild);
        }
        
        if (wasAtBottom || isNew) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        
        if (message.id > (lastMessageId || 0)) {
            lastMessageId = parseInt(message.id);
        }
    }

    function switchChat(chatId) {
        currentChat = chatId;
        document.getElementById('current-chat').value = chatId;
        
        document.querySelectorAll('.chat-room-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.chat === chatId) {
                item.classList.add('active');
                
                const chatName = item.querySelector('span').textContent;
                const chatPic = item.querySelector('img').src;
                document.getElementById('chat-title').textContent = chatName;
                document.getElementById('chat-header-avatar').src = chatPic;
                
                if (chatId === 'main') {
                    document.getElementById('chat-status').textContent = 'Group Chat';
                } else {
                    document.getElementById('chat-status').textContent = 'Private Chat';
                }
                
                const badge = item.querySelector('.unread-badge');
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        });
        
        if (typingTimeout) {
            clearTimeout(typingTimeout);
            sendTypingStatus(false);
        }
        
        fetch(`get_unread_messages.php?mark_read=${chatId}`, { cache: 'no-store' })
            .then(response => response.json())
            .catch(error => console.error('Mark as read error:', error));
            
        loadMessages();
        subscribeToFirebaseMessages(chatId);
    }

    function loadMessages(isNewMessage = false) {
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;
        
        console.log(`Loading messages for chat: ${currentChat}`);
        
        chatBox.innerHTML = `
            <div class="loading-chat">
                <div class="spinner-large"></div>
                <p>Loading messages...</p>
            </div>
        `;
        
        fetch(`get_messages.php?chat=${currentChat}`, { cache: 'no-store' })
            .then(response => {
                if (!response.ok) throw new Error('Network response not ok');
                return response.json();
            })
            .then(data => {
                console.log(`Received ${data.length} messages for chat ${currentChat}:`, data);
                
                chatBox.innerHTML = '';
                
                const typingIndicator = document.createElement('div');
                typingIndicator.className = 'typing-indicator';
                typingIndicator.innerHTML = '<span id="typing-name"></span> is typing <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>';
                chatBox.appendChild(typingIndicator);
                
                if (data && Array.isArray(data) && data.length > 0) {
                    lastReadTimes[currentChat] = new Date().getTime();
                    updateUnreadBadges();
                    
                    allMessages = allMessages.filter(msg => msg.chat_id !== currentChat);
                    allMessages = [...allMessages, ...data];
                    
                    const historyStart = document.createElement('div');
                    historyStart.className = 'history-indicator';
                    historyStart.innerHTML = `<div class="history-line"></div><span>Chat History</span><div class="history-line"></div>`;
                    chatBox.appendChild(historyStart);
                    
                    let chatName = "Main Hall";
                    if (currentChat !== 'main') {
                        const chatItem = document.querySelector(`.chat-room-item[data-chat="${currentChat}"]`);
                        if (chatItem) {
                            chatName = chatItem.querySelector('span').textContent;
                        }
                    }
                    
                    const welcomeMsg = document.createElement('div');
                    welcomeMsg.className = 'welcome-message';
                    welcomeMsg.innerHTML = `
                        <div class="welcome-icon"><i class="fa fa-comments"></i></div>
                        <h3>Welcome to ${chatName}</h3>
                        <p>${currentChat === 'main' ? 'This is the group chat for all staff members.' : 'This is a private conversation.'}</p>
                    `;
                    chatBox.appendChild(welcomeMsg);
                } else {
                    const welcomeMsg = document.createElement('div');
                    welcomeMsg.className = 'welcome-message';
                    welcomeMsg.innerHTML = `
                        <div class="welcome-icon"><i class="fa fa-comments"></i></div>
                        <h3>No messages yet</h3>
                        <p>Be the first to say hello!</p>
                    `;
                    chatBox.appendChild(welcomeMsg);
                }

                if (data && Array.isArray(data)) {
                    data.forEach(msg => {
                        const messageObj = {
                            id: parseInt(msg.id),
                            staff_id: parseInt(msg.staff_id),
                            staff_name: msg.staff_name,
                            staff_pic: msg.staff_pic,
                            chat_id: msg.chat_id || currentChat,
                            message: msg.message,
                            attachment: msg.attachment,
                            thumbnail: msg.thumbnail,
                            filename: msg.filename,
                            timestamp: msg.timestamp,
                            type: msg.message ? 'text' : 'attachment'
                        };
                        addMessageToUI(messageObj, false);
                    });
                    
                    const latestId = data.length > 0 ? Math.max(...data.map(msg => parseInt(msg.id))) : null;
                    if (latestId) {
                        lastMessageId = latestId;
                    }
                    
                    setTimeout(() => {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Load Messages Error:', error);
                chatBox.innerHTML = `
                    <div class="error-message">
                        <i class="fa fa-exclamation-triangle"></i>
                        <p>Failed to load messages. Please try again.</p>
                        <button onclick="loadMessages()">Retry</button>
                    </div>
                `;
            });
    }

    function sendAttachments(files, chatId) {
        if (!files || files.length === 0) return;

        const formData = new FormData();
        let validFiles = false;
        
        Array.from(files).forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} too large (max 5MB)`);
                return;
            }
            formData.append('attachments[]', file);
            validFiles = true;
        });
        
        if (!validFiles) return;
        
        formData.append('chat_id', chatId);

        console.log('Uploading attachments:', Array.from(files).map(f => f.name), 'to chat:', chatId);

        const loadingMessage = document.createElement('div');
        loadingMessage.className = 'message sent';
        loadingMessage.innerHTML = `
            <div class="message-bubble">
                <div class="loading-indicator">
                    <span>Uploading files...</span>
                    <div class="spinner"></div>
                </div>
            </div>
        `;
        
        const chatBox = document.getElementById('chat-box');
        const wasAtBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 1;
        chatBox.appendChild(loadingMessage);
        
        if (wasAtBottom) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Upload response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Upload server response:', data);
            
            chatBox.removeChild(loadingMessage);
            
            if (data.status === 'success') {
                document.getElementById('attachment-input').value = '';
                
                if (data.firebase_data) {
                    const safeChatId = sanitizeFirebasePath(chatId);
                    const messageRef = database.ref('chats/' + safeChatId + '/messages/' + data.firebase_data.id);
                    messageRef.set(data.firebase_data)
                        .then(() => console.log('Attachment saved to Firebase'))
                        .catch(error => console.error('Firebase attachment save error:', error));
                } else {
                    loadMessages(true);
                }
            } else {
                alert('Upload Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Upload Error:', error);
            chatBox.removeChild(loadingMessage);
            alert('Failed to upload file(s). Please try again.');
        });
    }

    function sendTypingStatus(isTyping) {
        const userId = '<?php echo $_SESSION['user_id']; ?>';
        const userName = '<?php echo $_SESSION['staff_name'] ?? 'Unknown'; ?>';
        const safeChatId = sanitizeFirebasePath(currentChat);
        
        const typingRef = database.ref('chats/' + safeChatId + '/typing/' + userId);
        typingRef.set({
            isTyping: isTyping,
            name: userName,
            timestamp: firebase.database.ServerValue.TIMESTAMP
        });
        
        fetch('typing_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `chat_id=${currentChat}&typing=${isTyping ? 1 : 0}`
        })
        .catch(error => console.error('Typing status error:', error));
    }

    function updateUnreadBadges() {
        fetch('get_unread_messages.php', { cache: 'no-store' })
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('.chat-room-item').forEach(item => {
                    const chatId = item.dataset.chat;
                    const badge = item.querySelector('.unread-badge');
                    
                    if (data[chatId] && data[chatId] > 0) {
                        if (!badge) {
                            const newBadge = document.createElement('div');
                            newBadge.className = 'unread-badge';
                            newBadge.textContent = data[chatId];
                            item.appendChild(newBadge);
                        } else {
                            badge.textContent = data[chatId];
                            badge.style.display = 'flex';
                        }
                    } else if (badge) {
                        badge.style.display = 'none';
                    }
                });
            })
            .catch(error => console.error('Unread messages error:', error));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('message-form');
        const attachmentInput = document.getElementById('attachment-input');
        const chatBox = document.getElementById('chat-box');

        if (chatBox) {
            chatBox.addEventListener('scroll', () => {
                isUserAtBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 1;
            });
        }

        setupSearch();

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = document.getElementById('message-input').value.trim();
                if (!message) return;

                const formData = new FormData();
                formData.append('message', message);
                formData.append('chat_id', currentChat);

                console.log('Sending message:', message, 'to chat:', currentChat);

                document.getElementById('message-input').value = '';
                
                fetch('send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        if (data.firebase_data) {
                            const safeChatId = sanitizeFirebasePath(currentChat);
                            const messageRef = database.ref('chats/' + safeChatId + '/messages/' + data.firebase_data.id);
                            messageRef.set(data.firebase_data)
                                .then(() => console.log('Message saved to Firebase'))
                                .catch(error => {
                                    console.error('Firebase save error:', error);
                                    addMessageToUI(data.firebase_data, true);
                                });
                        } else {
                            loadMessages(true);
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                        document.getElementById('message-input').value = message;
                    }
                })
                .catch(error => {
                    console.error('Send Message Error:', error);
                    alert('Failed to send message. Please try again.');
                    document.getElementById('message-input').value = message;
                });
                
                sendTypingStatus(false);
            });
        }

        if (attachmentInput) {
            attachmentInput.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    sendAttachments(files, currentChat);
                }
            });
        }

        const emojiBtn = document.getElementById('emoji-btn');
        const emojiPicker = document.getElementById('emoji-picker');
        if (emojiBtn && emojiPicker) {
            const picker = document.createElement('emoji-picker');
            emojiPicker.appendChild(picker);
            picker.addEventListener('emoji-click', e => {
                document.getElementById('message-input').value += e.detail.unicode;
                emojiPicker.style.display = 'none';
            });
            emojiBtn.addEventListener('click', () => {
                emojiPicker.style.display = emojiPicker.style.display === 'none' ? 'block' : 'none';
            });
        }

        const messageInput = document.getElementById('message-input');
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                clearTimeout(typingTimeout);
                sendTypingStatus(true);
                
                typingTimeout = setTimeout(() => {
                    sendTypingStatus(false);
                }, 3000);
            });
        }

        loadMessages();
        subscribeToFirebaseMessages('main');
        setInterval(() => {
            updateUnreadBadges();
        }, 5000);
        
        document.querySelectorAll('.menu-item, .menu-submenu, .menu-caret').forEach(item => {
            item.style.pointerEvents = 'auto';
            item.style.position = 'relative';
            item.style.zIndex = '2020';
        });
    });
</script>

<?php
$pdo = null;
?>
</div>
</body>
</html>