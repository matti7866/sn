<?php
require 'vendor/autoload.php';
include 'connection.php';

session_start();

// Google Client setup
$client = new Google_Client();
$client->setAuthConfig('client_secret.json'); // Ensure this file exists
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setRedirectUri('http://app.sntrips.com/sntravels/emails.php');
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

// Handle access token
$tokenFile = 'access_token.json';
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    $client->setAccessToken($_SESSION['access_token']);
} elseif (file_exists($tokenFile)) {
    $client->setAccessToken(json_decode(file_get_contents($tokenFile), true));
} else {
    // For initial setup (run manually in browser)
    if (!isset($_GET['code'])) {
        $authUrl = $client->createAuthUrl();
        die("Authorize this app by visiting: <a href='$authUrl'>$authUrl</a>");
    } else {
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $accessToken;
        file_put_contents($tokenFile, json_encode($accessToken));
        echo "Token saved. Now run the script via cron.";
        exit;
    }
}

// Refresh token if expired
if ($client->isAccessTokenExpired()) {
    $refreshToken = $client->getRefreshToken();
    if ($refreshToken) {
        $client->fetchAccessTokenWithRefreshToken($refreshToken);
        $newAccessToken = $client->getAccessToken();
        $_SESSION['access_token'] = $newAccessToken;
        file_put_contents($tokenFile, json_encode($newAccessToken));
    } else {
        die("Refresh token missing. Re-authenticate manually.");
    }
}

// Gmail API service
$service = new Google_Service_Gmail($client);

// Fetch emails (last 10 from inbox)
$optParams = ['q' => 'in:inbox -in:trash', 'maxResults' => 10];
$messages = $service->users_messages->listUsersMessages('me', $optParams);
$emailList = $messages->getMessages();

if (empty($emailList)) {
    echo "No new emails found at " . date('Y-m-d H:i:s') . "\n";
    exit;
}

foreach ($emailList as $message) {
    $msg = $service->users_messages->get('me', $message->getId());
    $headers = $msg->getPayload()->getHeaders();
    $from = $subject = $date = '';
    
    // Extract headers
    foreach ($headers as $header) {
        if ($header->getName() === 'From') $from = $header->getValue();
        if ($header->getName() === 'Subject') $subject = $header->getValue();
        if ($header->getName() === 'Date') $date = date('Y-m-d H:i:s', strtotime($header->getValue()));
    }
    
    $snippet = htmlspecialchars($msg->getSnippet());
    $parts = $msg->getPayload()->getParts();
    $body = '';
    $attachments = [];
    
    // Extract body and attachments
    if (!empty($parts)) {
        foreach ($parts as $part) {
            $data = $part->getBody()->getData();
            if ($data) {
                $decodedData = base64_decode(strtr($data, '-_', '+/'));
                if ($part->getMimeType() === 'text/plain' || $part->getMimeType() === 'text/html') {
                    $body = $decodedData;
                }
            }
            if ($part->getFilename() && $part->getBody()->getAttachmentId()) {
                $attachments[] = [
                    'filename' => $part->getFilename(),
                    'attachmentId' => $part->getBody()->getAttachmentId(),
                    'messageId' => $message->getId()
                ];
            }
        }
    } else {
        $bodyData = $msg->getPayload()->getBody()->getData();
        if ($bodyData) {
            $body = base64_decode(strtr($bodyData, '-_', '+/'));
        }
    }
    
    $isUnread = in_array('UNREAD', $msg->getLabelIds()) ? 1 : 0;

    // Parse for approval/rejection and match staff
    $name = '';
    $approved = null;
    if (preg_match('/for\s+([A-Za-z\s]+)\s+has\s+been\s+(approved|rejected)/i', $subject . ' ' . $body, $matches)) {
        $name = trim($matches[1]);
        $approved = strtolower($matches[2]) === 'approved' ? 1 : 0;
    }

    $staffId = null;
    if ($name) {
        // Adjust for your Staff table (assuming first_name + last_name)
        $stmt = $pdo->prepare("SELECT staff_id FROM Staff WHERE CONCAT(first_name, ' ', last_name) = :name");
        $stmt->execute([':name' => $name]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);
        $staffId = $staff ? $staff['staff_id'] : null;
    }

    // Store in database
    $stmt = $pdo->prepare("
        INSERT INTO email (message_id, staff_id, from_email, subject, body, approved, email_date, is_unread, attachments)
        VALUES (:message_id, :staff_id, :from_email, :subject, :body, :approved, :email_date, :is_unread, :attachments)
        ON DUPLICATE KEY UPDATE
        from_email = VALUES(from_email),
        subject = VALUES(subject),
        body = VALUES(body),
        approved = VALUES(approved),
        email_date = VALUES(email_date),
        is_unread = VALUES(is_unread),
        attachments = VALUES(attachments)
    ");
    $stmt->execute([
        ':message_id' => $message->getId(),
        ':staff_id' => $staffId,
        ':from_email' => $from,
        ':subject' => $subject,
        ':body' => $body ?: $snippet, // Fallback to snippet if body is empty
        ':approved' => $approved,
        ':email_date' => $date,
        ':is_unread' => $isUnread,
        ':attachments' => json_encode($attachments)
    ]);
}

echo "Emails fetched and stored successfully at " . date('Y-m-d H:i:s') . "\n";
exit;