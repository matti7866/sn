<?php
// Include dependencies (non-fatal if missing)
if (!include 'vendor/autoload.php') {
    $error = "Failed to include vendor/autoload.php";
    file_put_contents('/www/wwwlogs/gmail_to_db_detailed.log', date('Y-m-d H:i:s') . " - " . $error . "\n", FILE_APPEND);
    exit($error);
}

if (!include 'connection.php') {
    $error = "Failed to include connection.php";
    file_put_contents('/www/wwwlogs/gmail_to_db_detailed.log', date('Y-m-d H:i:s') . " - " . $error . "\n", FILE_APPEND);
    exit($error);
}

// Log file
$logFile = '/www/wwwlogs/gmail_to_db_detailed.log';
$lastRunFile = '/www/wwwlogs/last_run.txt'; // File to store last run timestamp

function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

// Get or set last run timestamp
function getLastRunTime() {
    global $lastRunFile;
    if (file_exists($lastRunFile)) {
        return file_get_contents($lastRunFile);
    }
    // Default to 24 hours ago if no last run file exists
    return date('Y-m-d H:i:s', strtotime('-24 hours'));
}

function setLastRunTime($timestamp) {
    global $lastRunFile;
    file_put_contents($lastRunFile, $timestamp);
}

logMessage("Latest emails update script started");

// Check if PDO is defined
if (!isset($pdo) || !($pdo instanceof PDO)) {
    $error = "PDO connection not initialized";
    logMessage($error);
    exit($error);
}

// Update of latest_emails for recent emails
try {
    logMessage("Starting update of latest_emails for recent emails");

    $updateStartTime = microtime(true);
    $lastRunTime = getLastRunTime();
    logMessage("Processing emails received after: $lastRunTime");

    $sql = "
        INSERT INTO latest_emails (passenger_name, application_number, email_status, download_link, received_at)
        SELECT 
            r.passenger_name,
            g.application_number,
            CASE 
                -- Residency-specific patterns (checked first)
                WHEN g.subject LIKE '%residency%approved%' 
                     OR g.snippet LIKE '%residency%approved%' 
                     OR g.full_message LIKE '%residency%approved%' THEN 'Residency Approved'
                WHEN g.subject LIKE '%residency%submitted%' 
                     OR g.snippet LIKE '%residency%submitted%' 
                     OR g.full_message LIKE '%residency%submitted%' THEN 'Residency Submitted'
                WHEN g.subject LIKE '%new residency%approved%' 
                     OR g.snippet LIKE '%new residency%approved%' 
                     OR g.full_message LIKE '%new residency%approved%' THEN 'Residency Approved'
                WHEN g.subject LIKE '%new residency%submitted%' 
                     OR g.snippet LIKE '%new residency%submitted%' 
                     OR g.full_message LIKE '%new residency%submitted%' THEN 'Residency Submitted'
                WHEN g.snippet LIKE '%residency under%process%' 
                     OR g.full_message LIKE '%residency under%process%' THEN 'Residency Under Process'
                -- Other existing patterns
                WHEN g.application_number LIKE '0102%' THEN 'Card is Printed'
                WHEN g.snippet LIKE '%entry permit request has been approved%' 
                     OR g.subject LIKE '%Entry permit request has been approved%' THEN 'E-Visa Approved'
                WHEN g.snippet LIKE '%New Work Entry Permit%' THEN 'E-Visa Submitted'
                WHEN g.snippet LIKE '%rejected%' THEN 'Refused'
                WHEN g.snippet LIKE '%change status%' OR g.snippet LIKE '%status adjustment%' THEN 
                    CASE 
                        WHEN g.snippet LIKE '%approved%' THEN 'Status Changed'
                        ELSE 'Change Status Submitted'
                    END
                WHEN g.snippet LIKE '%labour under approval%' THEN 'Labour Under Approval'
                ELSE NULL
            END AS email_status,
            g.download_link,
            g.received_at
        FROM gmail_emails g
        JOIN residence r 
            ON (g.snippet LIKE CONCAT('%', r.passenger_name, '%') 
                OR g.full_message LIKE CONCAT('%', r.passenger_name, '%')
                OR g.subject LIKE CONCAT('%', r.passenger_name, '%'))
        WHERE (g.application_number IS NOT NULL OR g.download_link IS NOT NULL)
            AND g.received_at >= :recent_timestamp
        GROUP BY r.passenger_name, g.application_number
        ON DUPLICATE KEY UPDATE
            email_status = VALUES(email_status),
            download_link = VALUES(download_link),
            received_at = VALUES(received_at)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['recent_timestamp' => $lastRunTime]);

    $updateTime = microtime(true) - $updateStartTime;
    $rowCount = $stmt->rowCount();
    logMessage("Update of latest_emails completed in $updateTime seconds, affected $rowCount rows");

    // Update last run timestamp
    setLastRunTime(date('Y-m-d H:i:s'));
    logMessage("Updated last run timestamp to: " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    logMessage("Error updating latest_emails: " . $e->getMessage());
}

logMessage("Latest emails update script completed at " . date('Y-m-d H:i:s'));
echo "Latest emails update script completed at " . date('Y-m-d H:i:s') . "\n";
?>