<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'connection.php';
header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => ''];

error_log("send_message.php accessed");

// Function to generate consistent private chat ID
function generatePrivateChatId($user1, $user2) {
    $ids = [$user1, $user2];
    sort($ids);
    return implode('_', $ids);
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    $staff_id = $_SESSION['user_id'];
    $input_chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING) ?: 'main';
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    
    // Determine chat_id
    $chat_id = ($input_chat_id === 'main') ? 'main' : generatePrivateChatId($staff_id, $input_chat_id);
    
    // Get staff name for the response
    $stmt = $pdo->prepare("SELECT staff_name, staff_pic FROM staff WHERE staff_id = ?");
    $stmt->execute([$staff_id]);
    $staff_info = $stmt->fetch(PDO::FETCH_ASSOC);
    $staff_name = $staff_info['staff_name'] ?? 'Unknown';
    $staff_pic = $staff_info['staff_pic'] ?? '';

    error_log("POST data: chat_id='$chat_id', message='$message', files=" . print_r($_FILES, true));

    // Handle text message if present
    if (!empty($message)) {
        $stmt = $pdo->prepare("
            INSERT INTO chat_messages (staff_id, chat_id, message, timestamp) 
            VALUES (?, ?, ?, NOW())
        ");
        error_log("Executing INSERT for text: staff_id=$staff_id, chat_id='$chat_id', message='$message'");
        $stmt->execute([$staff_id, $chat_id, $message]);
        error_log("Text insert successful, row count: " . $stmt->rowCount());
        
        // Get the ID of the inserted message
        $message_id = $pdo->lastInsertId();
        
        // Get the timestamp for Firebase
        $stmt = $pdo->prepare("SELECT timestamp FROM chat_messages WHERE id = ?");
        $stmt->execute([$message_id]);
        $timestamp = $stmt->fetch(PDO::FETCH_ASSOC)['timestamp'];
        
        $response = [
            'status' => 'success',
            'firebase_data' => [
                'id' => $message_id,
                'staff_id' => $staff_id,
                'staff_name' => $staff_name,
                'staff_pic' => $staff_pic,
                'chat_id' => $chat_id,
                'message' => $message,
                'timestamp' => $timestamp,
                'type' => 'text',
                'recipient_id' => ($input_chat_id !== 'main') ? $input_chat_id : null
            ]
        ];
    }

    // Handle multiple attachments
    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $upload_dir = 'Uploads/';
        
        // Make sure upload directory exists and is writable
        if (!file_exists($upload_dir)) {
            if (!@mkdir($upload_dir, 0777, true)) {
                throw new Exception("Failed to create uploads directory. Please check permissions.");
            }
            error_log("Created uploads directory");
        } elseif (!is_writable($upload_dir)) {
            throw new Exception("Uploads directory is not writable. Please check permissions.");
        }

        $file_count = count($_FILES['attachments']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if ($_FILES['attachments']['error'][$i] === UPLOAD_ERR_OK) {
                $file_type = $_FILES['attachments']['type'][$i];
                $file_size = $_FILES['attachments']['size'][$i];
                $file_name = $_FILES['attachments']['name'][$i];
                $file_tmp = $_FILES['attachments']['tmp_name'][$i];

                error_log("Processing attachment $i: type=$file_type, size=$file_size, name=$file_name");

                if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    $unique_id = uniqid();
                    $attachment_path = $upload_dir . $unique_id . '.' . $ext;

                    if (move_uploaded_file($file_tmp, $attachment_path)) {
                        $stmt = $pdo->prepare("
                            INSERT INTO chat_messages (staff_id, chat_id, attachment, filename, timestamp) 
                            VALUES (?, ?, ?, ?, NOW())
                        ");
                        $full_url = $attachment_path;
                        error_log("Executing INSERT for attachment: staff_id=$staff_id, chat_id='$chat_id', attachment='$full_url', filename='$file_name'");
                        $stmt->execute([$staff_id, $chat_id, $full_url, $file_name]);
                        
                        // Get the ID of the inserted message
                        $message_id = $pdo->lastInsertId();
                        
                        // Get the timestamp for Firebase
                        $stmt = $pdo->prepare("SELECT timestamp FROM chat_messages WHERE id = ?");
                        $stmt->execute([$message_id]);
                        $timestamp = $stmt->fetch(PDO::FETCH_ASSOC)['timestamp'];
                        
                        $response = [
                            'status' => 'success',
                            'firebase_data' => [
                                'id' => $message_id,
                                'staff_id' => $staff_id,
                                'staff_name' => $staff_name,
                                'staff_pic' => $staff_pic,
                                'chat_id' => $chat_id,
                                'attachment' => $full_url,
                                'filename' => $file_name,
                                'timestamp' => $timestamp,
                                'type' => 'attachment',
                                'recipient_id' => ($input_chat_id !== 'main') ? $input_chat_id : null
                            ]
                        ];
                    } else {
                        throw new Exception("Failed to move uploaded file: $file_name. Check permissions on upload directory.");
                    }
                } else {
                    throw new Exception("Invalid file type or size for $file_name (max 5MB, allowed: images, PDF)");
                }
            } elseif ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                $error_code = $_FILES['attachments']['error'][$i];
                $error_message = match($error_code) {
                    UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
                    UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form",
                    UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
                    UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
                    UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                    UPLOAD_ERR_EXTENSION => "File upload stopped by extension",
                    default => "Unknown upload error"
                };
                throw new Exception("Upload error for file $i: $error_message (code: $error_code)");
            }
        }
    }

    if ($response['status'] !== 'success') {
        $response['message'] = 'No message or valid attachments provided';
        error_log("No action taken - no message or valid attachments");
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Send Message Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
}

ob_end_clean();
echo json_encode($response);
exit();
?>