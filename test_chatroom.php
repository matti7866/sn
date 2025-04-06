<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}
include 'connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Chatroom</title>
</head>
<body>
<h2>Staff Chatroom</h2>
<div id="chat-box"></div>
<form id="message-form" enctype="multipart/form-data">
    <input type="text" id="message-input" placeholder="Type a message...">
    <input type="file" id="attachment-input" accept="image/*,.pdf">
    <button type="submit">Send</button>
</form>

<style>
    #chat-box { 
        width: 400px; 
        height: 300px; 
        border: 1px solid #ccc; 
        overflow-y: scroll; 
        padding: 10px; 
        margin-bottom: 10px; 
        background: #fff; /* White background */
        display: block; /* Ensure it’s visible */
        position: relative; /* Prevent overlap */
        z-index: 1000; /* Bring to front */
        min-height: 300px; /* Ensure size */
    }
    #message-form { 
        display: flex; 
        gap: 10px; 
    }
    #attachment-input { 
        padding: 5px; 
    }
</style>

<script>
    function loadMessages() {
        fetch('get_messages.php')
            .then(response => {
                console.log('Response Status:', response.status);
                if (!response.ok) throw new Error('Network response not ok: ' + response.status);
                return response.json();
            })
            .then(data => {
                console.log('Messages from server:', data);
                const chatBox = document.getElementById('chat-box');
                console.log('Chat box element:', chatBox);
                if (!chatBox) {
                    console.error('Chat box not found in DOM!');
                    return;
                }
                chatBox.innerHTML = ''; // Clear existing content
                if (data && Array.isArray(data)) {
                    console.log('Processing', data.length, 'messages');
                    data.forEach((msg, index) => {
                        const staffName = msg.staff_name || 'Unknown User';
                        const messageText = msg.message || '[No message]';
                        const timestamp = msg.timestamp || '[No timestamp]';
                        let content = `<p><strong>${staffName}</strong> (${timestamp}): ${messageText}</p>`;
                        if (msg.attachment) {
                            content += msg.attachment.match(/\.(jpeg|jpg|png|gif)$/i) 
                                ? `<img src="${msg.attachment}" style="max-width: 200px;" onerror="console.error('Image load error:', '${msg.attachment}')">`
                                : `<a href="${msg.attachment}" target="_blank">View Attachment</a>`;
                        }
                        console.log('Adding content:', content);
                        chatBox.innerHTML += content; // Append each message
                    });
                    console.log('Final chat box HTML:', chatBox.innerHTML);
                    // Force DOM update
                    chatBox.style.display = 'none';
                    chatBox.offsetHeight; // Trigger reflow
                    chatBox.style.display = 'block';
                } else {
                    chatBox.innerHTML = '<p>No messages found or invalid data format.</p>';
                }
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => console.error('Load Messages Error:', error));
    }

    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const message = document.getElementById('message-input').value;
        const attachment = document.getElementById('attachment-input').files[0];
        const formData = new FormData();
        formData.append('message', message);
        if (attachment) formData.append('attachment', attachment);

        fetch('send_message.php', { method: 'POST', body: formData })
            .then(response => {
                if (!response.ok) throw new Error('Network response not ok: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('message-input').value = '';
                    document.getElementById('attachment-input').value = '';
                    loadMessages();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => console.error('Send Message Error:', error));
    });

    document.addEventListener('DOMContentLoaded', () => {
        loadMessages();
        setInterval(loadMessages, 2000);
    });
</script>
</body>
</html>
<?php
$pdo = null;
?>