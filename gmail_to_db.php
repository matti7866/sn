<?php
require_once 'vendor/autoload.php';
require_once 'connection.php';

// Log file
$logFile = '/www/wwwlogs/gmail_to_db_detailed.log';

function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Function to process all parts recursively
function extractContent($parts) {
    $content = '';
    if (!empty($parts)) {
        foreach ($parts as $part) {
            if ($part->getMimeType() === 'text/plain' || $part->getMimeType() === 'text/html') {
                $content .= base64_decode(strtr($part->getBody()->getData(), '-_', '+/')) . "\n";
            }
            $subParts = $part->getParts();
            if (!empty($subParts)) {
                $content .= extractContent($subParts);
            }
        }
    }
    return $content;
}

logMessage("Email fetch script started");

// Google API setup
$client = new Google_Client();
try {
    $client->setAuthConfig('/www/wwwroot/sntravels/client_secret.json');
    logMessage("Auth config set");
} catch (Exception $e) {
    logMessage("Error setting auth config: " . $e->getMessage());
    exit;
}
$client->addScope(Google_Service_Gmail::GMAIL_READONLY);
$client->setRedirectUri('http://app.sntrips.com/gmail_auth.php');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$tokenFile = '/www/wwwroot/sntravels/token.json';
if (file_exists($tokenFile)) {
    $client->setAccessToken(json_decode(file_get_contents($tokenFile), true));
    logMessage("Token loaded from $tokenFile");
} else {
    logMessage("Token file not found. Run gmail_auth.php to authorize first.");
    echo "Token file not found. Run gmail_auth.php to authorize first.\n";
    exit;
}

if ($client->isAccessTokenExpired()) {
    $refreshToken = $client->getRefreshToken();
    $client->fetchAccessTokenWithRefreshToken($refreshToken);
    file_put_contents($tokenFile, json_encode($client->getAccessToken()));
    logMessage("Token refreshed and saved.");
}

$service = new Google_Service_Gmail($client);

// Fetch the last 10 emails
try {
    $messages = $service->users_messages->listUsersMessages('me', [
        'maxResults' => 10,
    ]);
    $emailList = $messages->getMessages();
    logMessage("Fetched " . count($emailList) . " recent messages");
} catch (Exception $e) {
    logMessage("Error fetching messages: " . $e->getMessage());
    exit;
}

foreach ($emailList as $message) {
    $emailId = $message->getId();
    logMessage("Processing email ID: $emailId");

    $msg = $service->users_messages->get('me', $emailId, ['format' => 'full']);
    $headers = $msg->getPayload()->getHeaders();
    $snippet = $msg->getSnippet();

    $subject = '';
    $from = '';
    $to = '';
    $date = '';
    foreach ($headers as $header) {
        if ($header->getName() === 'Subject') $subject = $header->getValue();
        if ($header->getName() === 'From') $from = $header->getValue();
        if ($header->getName() === 'To') $to = $header->getValue();
        if ($header->getName() === 'Date') $date = date('Y-m-d H:i:s', strtotime($header->getValue()));
    }

    // Extract full message from all parts
    $body = '';
    $parts = $msg->getPayload()->getParts();
    if (!empty($parts)) {
        $body = extractContent($parts);
    } else {
        $body = base64_decode(strtr($msg->getPayload()->getBody()->getData(), '-_', '+/'));
    }

    // Log content for debugging
    logMessage("Snippet for $emailId: " . substr($snippet, 0, 200));
    logMessage("Full message for $emailId: " . substr($body, 0, 200));

    // Extract application number from snippet or full_message
    $application_number = null;
    $content = $snippet . " " . $body;
    if (preg_match('/Your application with number \((\d+)\)/i', $content, $matches)) {
        $application_number = $matches[1];
    } elseif (preg_match('/Your entry permit application no:?\s*(\d+)/i', $content, $matches)) {
        $application_number = $matches[1];
    } elseif (preg_match('/(?:Request|Application|Permit)\s*(?:Number|No\.?)\s*[:\-\s]*(\d+)/i', $content, $matches)) {
        $application_number = $matches[1];
    } elseif (preg_match('/(\d{12,})/', $content, $matches)) {
        $application_number = $matches[1];
    }

    // Extract download link with broader patterns
    $download_link = null;
    $decodedContent = urldecode($content);
    if (preg_match('/Click here to download your document\s*<(https:\/\/smart\.gdrfad\.gov\.ae\/SmartChannels_Extended\/DownloadReport\.aspx\?token=[^>\s]+)/i', $decodedContent, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/href=["\']?(https:\/\/smart\.gdrfad\.gov\.ae\/[^"\'>]+)/i', $decodedContent, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/Download Certificate\s*<(https:\/\/fitness\.ehs\.gov\.ae\/onlineportal\/Reports\/Printing\/PrintEFitnessCertificate\?transactionId=[^>\s]+)/i', $decodedContent, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/href=["\']?(https:\/\/fitness\.ehs\.gov\.ae\/onlineportal\/Reports\/Printing\/PrintEFitnessCertificate\?transactionId=[^"\'>]+)/i', $decodedContent, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/(https:\/\/fitness\.ehs\.gov\.ae\/onlineportal\/Reports\/Printing\/PrintEFitnessCertificate\?transactionId=[a-zA-Z0-9=]+)/i', $decodedContent, $matches)) {
        $download_link = $matches[1];
    }
    if ($download_link) {
        $download_link = preg_replace('/[>].*$/', '', $download_link);
        $download_link = rtrim($download_link, '"\'><');
        logMessage("Final cleaned link for $emailId: " . $download_link);
    }

    try {
        $sql = "INSERT INTO `gmail_emails` (`email_id`, `subject`, `from_email`, `to_email`, `snippet`, `full_message`, `received_at`, `application_number`, `download_link`)
                VALUES (:email_id, :subject, :from_email, :to_email, :snippet, :full_message, :received_at, :application_number, :download_link)
                ON DUPLICATE KEY UPDATE 
                    subject = :subject,
                    from_email = :from_email,
                    to_email = :to_email,
                    snippet = :snippet,
                    full_message = :full_message,
                    received_at = :received_at,
                    application_number = :application_number,
                    download_link = :download_link";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email_id' => $emailId,
            ':subject' => $subject,
            ':from_email' => $from,
            ':to_email' => $to,
            ':snippet' => $snippet,
            ':full_message' => $body,
            ':received_at' => $date,
            ':application_number' => $application_number,
            ':download_link' => $download_link
        ]);
        logMessage("Saved/Updated email ID: $emailId" . 
            ($application_number ? " with application number: $application_number" : "") . 
            ($download_link ? " with download link: $download_link" : ""));
    } catch (PDOException $e) {
        logMessage("Error processing email ID $emailId: " . $e->getMessage());
        continue;
    }
}

logMessage("Email fetch script completed at " . date('Y-m-d H:i:s'));
echo "Email fetch script completed at " . date('Y-m-d H:i:s') . "\n";
?>