<?php
session_start();
include 'connection.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['messageId']) || !isset($_GET['attachmentId'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$client = new Google_Client();
$client->setAuthConfigFile('client_secret.json');
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setAccessToken($_SESSION['access_token']);

$service = new Google_Service_Gmail($client);
$attachment = $service->users_messages_attachments->get('me', $_GET['messageId'], $_GET['attachmentId']);
$data = base64_decode(strtr($attachment->getData(), '-_', '+/'));

// Get filename from message metadata
$message = $service->users_messages->get('me', $_GET['messageId']);
$parts = $message->getPayload()->getParts();
$filename = '';
foreach ($parts as $part) {
    if ($part->getBody()->getAttachmentId() === $_GET['attachmentId']) {
        $filename = $part->getFilename();
        break;
    }
}

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $filename . '"');
echo $data;
exit;
?>