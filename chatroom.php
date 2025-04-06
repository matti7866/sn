<?php
include 'header.php';
?>
<title>Staff Chatroom</title>
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
    $chat_id = $staff['staff_id'];
    $stmt = $pdo->prepare("
        SELECT cm.message, cm.timestamp, cm.staff_id 
        FROM chat_messages cm 
        WHERE cm.chat_id = ? 
        AND (cm.staff_id = ? OR cm.chat_id = ?) 
        ORDER BY cm.timestamp DESC 
        LIMIT 1
    ");
    $stmt->execute([$chat_id, $user_id, $user_id]);
    $last = $stmt->fetch(PDO::FETCH_ASSOC);
    $last_messages[$chat_id] = $last ? ($last['staff_id'] == $user_id ? "You: " . ($last['message'] ?? '[Attachment]') : ($last['message'] ?? '[Attachment]')) : '';
}
?>

<div id="content" class="app-content">
    <div class="chat-wrapper">
        <!-- Staff List Sidebar -->
        <div class="staff-list">
            <h3>Staff Members</h3>
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

        <!-- Chat Container -->
        <div class="chat-container">
            <h2 id="chat-title">Main Hall</h2>
            <input type="hidden" id="current-chat" value="main">
            <div id="chat-box"></div>
            <form id="message-form" enctype="multipart/form-data" class="chat-form">
                <div class="input-group">
                    <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                    <button type="button" id="emoji-btn" title="Emojis">😊</button>
                    <label for="attachment-input" class="attach-btn" title="Attach files">📎</label>
                    <input type="file" id="attachment-input" accept="image/*,.pdf" multiple style="display:none;">
                </div>
                <button type="submit" class="send-btn">Send</button>
            </form>
            <div id="emoji-picker" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1.0.0/index.js" type="module"></script>

<style>
    .chat-wrapper {
        max-width: 1100px;
        margin: 20px auto;
        display: flex;
        gap: 20px;
    }
    .staff-list {
        width: 250px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        height: 650px;
        overflow-y: auto;
    }
    .chat-container {
        flex-grow: 1;
        max-width: 800px;
        padding: 20px;
    }
    .chat-room-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin: 5px 0;
        cursor: pointer;
        border-radius: 5px;
        transition: background 0.2s;
    }
    .chat-room-item:hover {
        background: #e9ecef;
    }
    .chat-room-item.active {
        background: #007bff;
        color: #fff;
    }
    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }
    .chat-info {
        flex-grow: 1;
    }
    .last-message {
        font-size: 0.8em;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .chat-room-item.active .last-message {
        color: #ccc;
    }
    #chat-box {
        width: 100%;
        height: 600px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow-y: auto;
        padding: 15px;
        background: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .message {
        margin: 10px 0;
        display: flex;
        flex-direction: column;
    }
    .message.sent { align-items: flex-end; }
    .message.received { align-items: flex-start; }
    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin: 2px 0;
        word-wrap: break-word;
        font-size: 16px;
    }
    .message-bubble .emoji {
        font-size: 48px;
        vertical-align: middle;
    }
    .new-message .message-bubble {
        animation: slideIn 0.2s ease;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .sent .message-bubble {
        background: #007bff;
        color: #fff;
    }
    .received .message-bubble {
        background: #e9ecef;
        color: #333;
    }
    .message .staff-name {
        font-weight: bold;
        font-size: 0.9em;
        margin-bottom: 2px;
        color: #333;
    }
    .sent .staff-name { color: #fff; }
    .message img {
        margin-top: 5px;
        max-width: 200px;
    }
    .message .attachment-preview {
        margin-top: 5px;
        max-width: 200px;
    }
    .message .attachment-actions {
        margin-top: 5px;
        display: flex;
        gap: 10px;
    }
    .message .attachment-actions a, .message .attachment-actions button {
        padding: 5px 10px;
        font-size: 12px;
        text-decoration: none;
        color: #fff;
        background: #007bff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .message .attachment-actions button:hover, .message .attachment-actions a:hover {
        background: #0056b3;
    }
    .message .attachment-filename {
        margin-top: 5px;
        font-size: 0.9em;
        color: #666;
        word-wrap: break-word;
    }
    .timestamp {
        font-size: 0.8em;
        color: #666;
        margin: 2px 0;
    }
    .chat-form {
        display: flex;
        align-items: center;
        margin-top: 10px;
        gap: 10px;
    }
    .input-group {
        flex-grow: 1;
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 5px;
    }
    #message-input {
        flex-grow: 1;
        border: none;
        outline: none;
        padding: 5px 10px;
        font-size: 14px;
    }
    #emoji-btn, .attach-btn {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 5px;
    }
    .send-btn {
        background: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .send-btn:hover {
        background: #0056b3;
    }
    #emoji-picker {
        position: absolute;
        bottom: 60px;
        right: 20px;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 10000;
    }
</style>

<script>
    let isUserAtBottom = true;
    let lastMessageId = null;
    let currentChat = 'main';

    function switchChat(chatId) {
        currentChat = chatId;
        document.getElementById('current-chat').value = chatId;
        document.querySelectorAll('.chat-room-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.chat === chatId) {
                item.classList.add('active');
                document.getElementById('chat-title').textContent = 
                    chatId === 'main' ? 'Main Hall' : item.querySelector('span').textContent;
            }
        });
        loadMessages();
    }

    function loadMessages(isNewMessage = false) {
        const chatId = currentChat;
        console.log('Fetching messages for chatId:', chatId);
        fetch(`get_messages.php?chat=${chatId}`, { cache: 'no-store' })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) throw new Error('Network response not ok');
                return response.json();
            })
            .then(data => {
                console.log('Received data:', data);
                const chatBox = document.getElementById('chat-box');
                if (!chatBox) return;

                const wasAtBottom = chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 1;
                const currentScrollTop = chatBox.scrollTop;

                if (data && Array.isArray(data)) {
                    const latestId = data.length > 0 ? Math.max(...data.map(msg => parseInt(msg.id))) : null;
                    if (lastMessageId === latestId && !isNewMessage) {
                        chatBox.scrollTop = currentScrollTop;
                        return;
                    }
                    lastMessageId = latestId;

                    chatBox.innerHTML = '';
                    const currentUserId = <?php echo json_encode($_SESSION['user_id']); ?>;
                    data.forEach((msg, index) => {
                        const isSent = parseInt(msg.staff_id) === currentUserId;
                        const messageClass = isSent ? 'sent' : 'received';
                        const staffName = msg.staff_name || 'Unknown User';
                        const isLatest = index === data.length - 1 && isNewMessage;
                        let content = `
                            <div class="message ${messageClass} ${isLatest ? 'new-message' : ''}">
                                <span class="staff-name">${staffName}</span>`;
                        if (msg.message) {
                            const messageWithBigEmojis = msg.message.replace(/([\uD800-\uDBFF][\uDC00-\uDFFF])/g, '<span class="emoji">$1</span>');
                            content += `<div class="message-bubble">${messageWithBigEmojis}</div>`;
                        }
                        if (msg.attachment) {
                            content += `<div class="attachment-preview">`;
                            if (msg.attachment.match(/\.(jpeg|jpg|png|gif)$/i)) {
                                content += `<img src="${msg.attachment}" alt="Attachment">`;
                            }
                            content += `
                                <div class="attachment-actions">
                                    ${msg.attachment.match(/\.pdf$/i) ? `<button onclick="window.open('${msg.attachment}', '_blank')">Preview</button>` : ''}
                                    <a href="${msg.attachment}" download>Download</a>
                                </div>
                                <div class="attachment-filename">${msg.filename || 'Unnamed file'}</div>
                            </div>`;
                        }
                        content += `<span class="timestamp">${msg.timestamp || '[No timestamp]'}</span></div>`;
                        chatBox.innerHTML += content;
                    });

                    if (wasAtBottom || isNewMessage) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    } else {
                        chatBox.scrollTop = currentScrollTop;
                    }
                } else {
                    chatBox.innerHTML = '<p>No messages found.</p>';
                }
            })
            .catch(error => console.error('Load Messages Error:', error));
    }

    function sendAttachments(files, chatId) {
        if (!files || files.length === 0) return;

        const formData = new FormData();
        Array.from(files).forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                alert(`File ${file.name} too large (max 5MB)`);
                return;
            }
            formData.append('attachments[]', file);
        });
        formData.append('chat_id', chatId);

        console.log('Uploading attachments:', Array.from(files).map(f => f.name));

        fetch('https://app.sntrips.com/send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Upload response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Upload server response:', data);
            if (data.status === 'success') {
                document.getElementById('attachment-input').value = '';
                loadMessages(true);
            } else {
                alert('Upload Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => console.error('Upload Error:', error));
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

        // Handle text message submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = document.getElementById('message-input').value.trim();
                const chatId = currentChat;

                if (!message) return;

                const formData = new FormData();
                formData.append('message', message);
                formData.append('chat_id', chatId);

                console.log('Sending message:', message);

                fetch('https://app.sntrips.com/send_message.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Text response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Text server response:', data);
                    if (data.status === 'success') {
                        document.getElementById('message-input').value = '';
                        loadMessages(true);
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => console.error('Send Message Error:', error));
            });
        }

        // Handle multiple attachment upload on file selection
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

        loadMessages();
        setInterval(() => loadMessages(false), 2000);
    });
</script>

<?php
$pdo = null;
?>
</div>
</body>
</html>