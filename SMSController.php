<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

// Set content type to JSON
header('Content-Type: application/json');

// Turn off error display, log instead
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/error.log'); // Adjust path (e.g., /tmp/error.log on Mac)

include 'connection.php'; // Include your connection file

// Pushbullet Access Token
$accessToken = "o.uOOwZzHraRQ9XKD7vDT6Pgry1asFLKpJ"; // Your verified token

// Fetch SMS from Pushbullet
function fetchPushbulletSMS($accessToken) {
    $modifiedAfter = time() - (24 * 60 * 60); // Last 24 hours
    $url = "https://api.pushbullet.com/v2/pushes?limit=50&active=true&modified_after=" . $modifiedAfter;
    $headers = ["Access-Token: $accessToken", "Content-Type: application/json"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg() . " - Response: " . $response);
        return false;
    }

    if ($httpCode !== 200) {
        error_log("Pushbullet API Error (HTTP $httpCode): " . json_encode($decoded));
        return false;
    }

    error_log("Pushbullet Full Response: " . json_encode($decoded));
    return $decoded['pushes'] ?? [];
}

// Store SMS in database
function storeSMS($pdo, $pushes) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO sms_messages (sender, message, timestamp, pushbullet_id, status)
            VALUES (:sender, :message, :timestamp, :pushbullet_id, 'Received')
            ON DUPLICATE KEY UPDATE message = :message
        ");

        $smsCount = 0;
        foreach ($pushes as $push) {
            // Target SMS mirrored notifications (per docs)
            if (isset($push['type']) && $push['type'] === 'mirror' && 
                (isset($push['application_name']) && stripos($push['application_name'], 'messaging') !== false || 
                 isset($push['title']) && stripos($push['title'], 'sms') !== false)) {
                $sender = $push['title'] ?? 'Unknown';
                $message = $push['body'] ?? '';
                $timestamp = date('Y-m-d H:i:s', $push['created']);
                $pushbulletId = $push['iden'];

                $stmt->execute([
                    ':sender' => $sender,
                    ':message' => $message,
                    ':timestamp' => $timestamp,
                    ':pushbullet_id' => $pushbulletId
                ]);
                $smsCount++;
            }
            error_log("Push: " . json_encode($push));
        }
        error_log("Stored $smsCount SMS messages");
    } catch (PDOException $e) {
        error_log("Database Error in storeSMS: " . $e->getMessage());
        return false;
    }
    return true;
}

// Handle DataTables request
if (isset($_POST['GetSMS'])) {
    $pushes = fetchPushbulletSMS($accessToken);
    if ($pushes === false) {
        echo json_encode(["error" => "Failed to fetch SMS from Pushbullet - check logs"]);
        exit;
    }
    if (!storeSMS($pdo, $pushes)) {
        echo json_encode(["error" => "Failed to store SMS in database"]);
        exit;
    }

    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];
    $orderColumn = $_POST['order'][0]['column'];
    $orderDir = $_POST['order'][0]['dir'];
    $columns = ['sender', 'message', 'timestamp', 'status'];

    try {
        $sql = "SELECT sender, message, timestamp, status FROM sms_messages";
        if (!empty($search)) {
            $sql .= " WHERE sender LIKE :search OR message LIKE :search";
        }
        $sql .= " ORDER BY " . $columns[$orderColumn] . " $orderDir LIMIT :start, :length";

        $stmt = $pdo->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', "%$search%");
        }
        $stmt->bindValue(':start', (int)$start, PDO::PARAM_INT);
        $stmt->bindValue(':length', (int)$length, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalStmt = $pdo->query("SELECT COUNT(*) FROM sms_messages");
        $totalRecords = $totalStmt->fetchColumn();

        $response = [
            "draw" => intval($draw),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data
        ];

        error_log("DataTables Response: " . json_encode($response));
    } catch (PDOException $e) {
        error_log("Database Query Error: " . $e->getMessage());
        echo json_encode(["error" => "Database query failed: " . $e->getMessage()]);
        exit;
    }

    echo json_encode($response);
    exit;
}

echo json_encode(["error" => "Invalid request"]);
exit;
?>