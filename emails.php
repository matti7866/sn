<?php
require 'vendor/autoload.php'; // Include the Google API PHP Client
include 'connection.php'; // Include your MySQL database connection file

// Path to store the access token
$tokenFile = '/www/wwwroot/sntravels/access_token.json';

// Uncomment to force re-authentication (optional for testing)
// if (file_exists($tokenFile)) unlink($tokenFile);

// Initialize Google Client
$client = new Google_Client();
$client->setClientId('947608979620-289n3fmgri958pvmi9r2nk8l27k25am9.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://app.sntrips.com/emails.php');
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

// Function to log messages for debugging
function logMessage($message) {
    file_put_contents('/www/wwwroot/sntravels/email_debug.log', date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}

logMessage("Script started");

// Step 1: Authentication
if (!file_exists($tokenFile) || filesize($tokenFile) == 0) {
    if (!isset($_GET['code'])) {
        // Redirect to Google OAuth consent screen
        $authUrl = $client->createAuthUrl();
        logMessage("No token found. Redirecting to auth URL: $authUrl");
        echo "Authorize this app: <a href='$authUrl'>$authUrl</a>";
        exit;
    }

    // Handle the OAuth callback
    try {
        logMessage("Received code: " . $_GET['code']);
        $client->authenticate($_GET['code']);
        $accessToken = $client->getAccessToken();
        logMessage("Token response: " . print_r($accessToken, true));

        // Validate and save the access token
        if (is_array($accessToken) && isset($accessToken['access_token'])) {
            file_put_contents($tokenFile, json_encode($accessToken));
            logMessage("Token saved successfully");
            echo "Token saved successfully. <a href='https://app.sntrips.com/emails.php'>Refresh the page</a> to fetch emails.";
            exit;
        } else {
            logMessage("Invalid token response: " . json_encode($accessToken));
            echo "Authentication failed: Invalid token response - " . json_encode($accessToken);
            exit;
        }
    } catch (Exception $e) {
        logMessage("Auth error: " . $e->getMessage());
        echo "Authentication error: " . $e->getMessage();
        exit;
    }
}

// Step 2: Load the access token
$accessToken = json_decode(file_get_contents($tokenFile), true);
if (!isset($accessToken['access_token'])) {
    logMessage("Invalid token in $tokenFile");
    echo "Invalid token. Delete $tokenFile and re-authenticate.";
    exit;
}
$client->setAccessToken($accessToken);

// Step 3: Refresh the token if expired
if ($client->isAccessTokenExpired()) {
    logMessage("Token expired. Attempting refresh.");
    try {
        $refreshToken = $accessToken['refresh_token'] ?? null;
        if ($refreshToken) {
            $client->refreshToken($refreshToken);
            $newAccessToken = $client->getAccessToken();
            if (isset($newAccessToken['access_token'])) {
                file_put_contents($tokenFile, json_encode($newAccessToken));
                logMessage("Token refreshed successfully");
            } else {
                logMessage("Refresh failed: Invalid token response - " . json_encode($newAccessToken));
                echo "Refresh failed: Invalid token response";
                exit;
            }
        } else {
            logMessage("No refresh token available");
            echo "No refresh token. Re-authenticate.";
            exit;
        }
    } catch (Exception $e) {
        logMessage("Refresh failed: " . $e->getMessage());
        echo "Token refresh failed: " . $e->getMessage();
        exit;
    }
}

// Step 4: Fetch emails using the Gmail API
$service = new Google_Service_Gmail($client);
$optParams = ['q' => 'in:inbox -in:trash', 'maxResults' => 10]; // Fetch 10 emails from the inbox

try {
    $messages = $service->users_messages->listUsersMessages('me', $optParams);
    $emailList = $messages->getMessages();
    logMessage("Fetched " . count($emailList) . " emails");

    echo "Emails fetched: " . count($emailList) . "<br>";

    foreach ($emailList as $message) {
        $msg = $service->users_messages->get('me', $message->getId());
        $headers = $msg->getPayload()->getHeaders();
        $subject = '';
        $from = '';
        $date = '';

        // Extract email headers
        foreach ($headers as $header) {
            if ($header->getName() === 'Subject') {
                $subject = $header->getValue();
            }
            if ($header->getName() === 'From') {
                $from = $header->getValue();
            }
            if ($header->getName() === 'Date') {
                $date = $header->getValue();
            }
        }

        $snippet = $msg->getSnippet(); // Extract email snippet

        // Save email to MySQL database
        $stmt = $mysqli->prepare("INSERT INTO emails (subject, sender, date, snippet) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $subject, $from, $date, $snippet);
        if ($stmt->execute()) {
            logMessage("Email saved to database: " . $subject);
            echo "Email saved: " . htmlspecialchars($subject) . "<br>";
        } else {
            logMessage("Failed to save email: " . $stmt->error);
            echo "Failed to save email: " . $stmt->error . "<br>";
        }
        $stmt->close();
    }
} catch (Google_Service_Exception $e) {
    logMessage("Gmail API error: " . $e->getMessage());
    logMessage("Full error details: " . print_r($e->getErrors(), true));
    echo "Gmail API error: " . $e->getMessage();
} catch (Exception $e) {
    logMessage("General error: " . $e->getMessage());
    echo "General error: " . $e->getMessage();
}

logMessage("Script completed");
?>