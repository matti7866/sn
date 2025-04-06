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

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8mb4");

    $staff_id = $_SESSION['user_id'];
    $chat_id = filter_input(INPUT_POST, 'chat_id', FILTER_SANITIZE_STRING) ?: 'main';
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

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
        $response['status'] = 'success';
    }

    // Handle multiple attachments
    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
            error_log("Created uploads directory");
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
                        error_log("Executing INSERT for attachment: staff_id=$staff_id, chat_id='$chat_id', attachment='$attachment_path', filename='$file_name'");
                        $stmt->execute([$staff_id, $chat_id, $attachment_path, $file_name]);
                        error_log("Attachment insert successful, row count: " . $stmt->rowCount());
                        $response['status'] = 'success';
                    } else {
                        throw new Exception("Failed to move uploaded file: $file_name");
                    }
                } else {
                    throw new Exception("Invalid file type or size for $file_name (max 5MB, allowed: images, PDF)");
                }
            } elseif ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                throw new Exception("Upload error for file $i: " . $_FILES['attachments']['error'][$i]);
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