<?php
require '/www/wwwroot/sntravels/google-api-php-client/vendor/autoload.php';
require '/www/wwwroot/sntravels/connection.php';

// Google Client setup
$client = new Google_Client();
$client->setAuthConfig('/www/wwwroot/sntravels/client_secret.json');
$client->addScope('https://www.googleapis.com/auth/gmail.readonly');
$client->setRedirectUri('https://app.sntrips.com/gmail_connect.php'); // Updated to match script URL

session_start();
if (!isset($_SESSION['access_token'])) {
    if (isset($_GET['code'])) {
        $client->authenticate($_GET['code']);
        $_SESSION['access_token'] = $client->getAccessToken();
        header('Location: https://app.sntrips.com/gmail_connect.php');
        exit;
    } else {
        $authUrl = $client->createAuthUrl();
        echo "<a href='$authUrl'>Login with Google</a>";
        exit;
    }
}

$client->setAccessToken($_SESSION['access_token']);
$gmail = new Google_Service_Gmail($client);
$messages = $gmail->users_messages->listUsersMessages('me', ['maxResults' => 10]);

foreach ($messages->getMessages() as $message) {
    $msg = $gmail->users_messages->get('me', $message->getId());
    $headers = $msg->getPayload()->getHeaders();
    $subject = '';
    foreach ($headers as $header) {
        if ($header->getName() == 'Subject') {
            $subject = $header->getValue();
            break;
        }
    }
    $message_id = $msg->getId();
    $snippet = $msg->getSnippet();

    $stmt = $db->prepare("INSERT INTO emails (message_id, subject, snippet) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE subject = ?");
    $stmt->bind_param('ssss', $message_id, $subject, $snippet, $subject);
    $stmt->execute();
}

echo "Emails fetched and stored!";
$db->close();
?>