<?php
require_once 'vendor/autoload.php';

// Google API setup
$client = new Google_Client();
try {
    $client->setAuthConfig('/www/wwwroot/sntravels/client_secret.json');
} catch (Exception $e) {
    die("Error loading client_secret.json: " . $e->getMessage());
}
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setRedirectUri('http://app.sntrips.com/gmail_auth.php');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (isset($token['error'])) {
            die("OAuth error: " . $token['error'] . " - " . $token['error_description']);
        }
        $tokenFile = '/www/wwwroot/sntravels/token.json';
        if (file_put_contents($tokenFile, json_encode($token)) === false) {
            die("Error writing token to $tokenFile: Check file permissions.");
        }
        echo "Token saved to $tokenFile. You can close this window.";
    } catch (Exception $e) {
        die("Error fetching token: " . $e->getMessage());
    }
} else {
    try {
        $authUrl = $client->createAuthUrl();
        header("Location: $authUrl");
        exit;
    } catch (Exception $e) {
        die("Error creating auth URL: " . $e->getMessage());
    }
}
?>