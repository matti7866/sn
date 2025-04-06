<?php
include 'connection.php';

// Log file
$logFile = '/www/wwwlogs/gmail_extract_existing.log';

function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logMessage("Extract existing script started");

// Fetch all emails
try {
    $sql = "SELECT email_id, snippet, full_message FROM gmail_emails WHERE application_number IS NULL OR download_link IS NULL";
    $stmt = $pdo->query($sql);
    $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    logMessage("Found " . count($emails) . " emails to reprocess");
} catch (PDOException $e) {
    logMessage("Error fetching emails: " . $e->getMessage());
    exit;
}

foreach ($emails as $email) {
    $emailId = $email['email_id'];
    $snippet = $email['snippet'];
    $body = $email['full_message'];

    logMessage("Processing email ID: $emailId");

    // Log content for debugging
    logMessage("Snippet for $emailId: " . substr($snippet, 0, 200));
    logMessage("Full message snippet for $emailId: " . substr($body, 0, 200));

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

    // Extract download link from snippet or full_message
    $download_link = null;
    if (preg_match('/Click here to download your document\s*<(https:\/\/smart\.gdrfad\.gov\.ae\/SmartChannels_Extended\/DownloadReport\.aspx\?token=.*?)>/i', $content, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/(https:\/\/smart\.gdrfad\.gov\.ae\/SmartChannels_Extended\/DownloadReport\.aspx\?token=[^>\s]+)/i', $content, $matches)) {
        $download_link = $matches[1];
    } elseif (preg_match('/(https:\/\/smart\.gdrfad\.gov\.ae[^\s]+)/i', $content, $matches)) {
        $download_link = $matches[1];
    }

    // Update the email record if either value is found
    if ($application_number || $download_link) {
        try {
            $sql = "UPDATE gmail_emails 
                    SET application_number = :application_number, 
                        download_link = :download_link 
                    WHERE email_id = :email_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email_id' => $emailId,
                ':application_number' => $application_number,
                ':download_link' => $download_link
            ]);
            logMessage("Updated email ID: $emailId" . 
                ($application_number ? " with application number: $application_number" : "") . 
                ($download_link ? " with download link: $download_link" : ""));
        } catch (PDOException $e) {
            logMessage("Error updating email ID $emailId: " . $e->getMessage());
        }
    } else {
        logMessage("No application number or download link found for email ID: $emailId");
    }
}

logMessage("Extract existing script completed at " . date('Y-m-d H:i:s'));
echo "Extract existing script completed at " . date('Y-m-d H:i:s') . "\n";
?>