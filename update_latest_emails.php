<?php
// Include dependencies
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
$lastRunFile = '/www/wwwlogs/last_run.txt';

function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

function getLastRunTime() {
    global $lastRunFile;
    if (file_exists($lastRunFile)) {
        return file_get_contents($lastRunFile);
    }
    return date('Y-m-d H:i:s', strtotime('-24 hours'));
}

function setLastRunTime($timestamp) {
    global $lastRunFile;
    file_put_contents($lastRunFile, $timestamp);
}

logMessage("Latest emails update script started (processing all previous emails)");

// Check PDO
if (!isset($pdo) || !($pdo instanceof PDO)) {
    $error = "PDO connection not initialized";
    logMessage($error);
    exit($error);
}

// Update latest_emails
try {
    logMessage("Starting update of latest_emails");
    $updateStartTime = microtime(true);
    $lastRunTime = '2025-04-01 00:00:00'; // Consider: $argv[1] ?: getLastRunTime();
    logMessage("Processing emails since $lastRunTime");

    // Phase 1: Update latest_emails with EID statuses from residence
    logMessage("Phase 1: Processing residence records with EID statuses");
    $eidRecords = $pdo->query("
        SELECT residenceID, passenger_name, eid_received, eid_delivered, EmiratesIDNumber, eid_receive_datetime, eid_delivered_datetime
        FROM residence
        WHERE (eid_received = 1 OR eid_delivered = 1)
        AND deleted = 0 AND islocked = 0 AND cancelled = 0
    ")->fetchAll(PDO::FETCH_ASSOC);

    if ($eidRecords) {
        logMessage("Found " . count($eidRecords) . " residence records with eid_received=1 or eid_delivered=1");
        $eidInsertCount = 0;
        foreach ($eidRecords as $record) {
            // Normalize passenger_name
            $passengerName = trim(preg_replace('/\s+/', ' ', $record['passenger_name']));
            $status = $record['eid_delivered'] == 1 ? 'Completed' : ($record['eid_received'] == 1 ? 'Card Received' : NULL);
            $receivedAt = $record['eid_delivered_datetime'] ?: $record['eid_receive_datetime'] ?: date('Y-m-d H:i:s');
            // Use EmiratesIDNumber for both eid_received and eid_delivered, fallback to EID-<residenceID>
            $appNumber = $record['EmiratesIDNumber'] ?: 'EID-' . $record['residenceID'];

            logMessage("Processing EID: ID: {$record['residenceID']}, Passenger: {$passengerName}, Status: {$status}, AppNum: {$appNumber}, EmiratesIDNumber: " . ($record['EmiratesIDNumber'] ?: 'NULL'));

            // Check for matching emails to get download_link or application_number (for non-EID cases)
            $emailStmt = $pdo->prepare("
                SELECT application_number, download_link
                FROM gmail_emails
                WHERE (LOWER(snippet) LIKE :passenger_name
                    OR LOWER(full_message) LIKE :passenger_name
                    OR LOWER(subject) LIKE :passenger_name)
                AND received_at >= :recent_timestamp
                LIMIT 1
            ");
            $emailStmt->execute([
                ':passenger_name' => '%' . strtolower($passengerName) . '%',
                ':recent_timestamp' => $lastRunTime
            ]);
            $email = $emailStmt->fetch(PDO::FETCH_ASSOC);

            if ($email) {
                logMessage("Found matching email for {$passengerName}: AppNum: {$email['application_number']}, DownloadLink: {$email['download_link']}");
                $downloadLink = $email['download_link'];
            } else {
                logMessage("No matching email found for {$passengerName}");
                $downloadLink = NULL;
            }

            $sql = "
                INSERT INTO latest_emails (passenger_name, application_number, email_status, download_link, received_at)
                VALUES (:passenger_name, :application_number, :email_status, :download_link, :received_at)
                ON DUPLICATE KEY UPDATE
                    email_status = :email_status,
                    application_number = :application_number,
                    download_link = :download_link,
                    received_at = :received_at
            ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':passenger_name' => $passengerName,
                ':application_number' => $appNumber,
                ':email_status' => $status,
                ':download_link' => $downloadLink,
                ':received_at' => $receivedAt
            ]);
            $eidInsertCount += $stmt->rowCount();
            logMessage("EID Record Updated: ID: {$record['residenceID']}, Passenger: {$passengerName}, Status: {$status}, AppNum: {$appNumber}, Rows Affected: {$stmt->rowCount()}");
        }
        logMessage("Phase 1 completed: Updated $eidInsertCount rows for EID statuses");
    } else {
        logMessage("No residence records found with eid_received=1 or eid_delivered=1. Check if eidTasks.php is setting these fields.");
    }

    // Phase 2: Update latest_emails with email-based statuses
    logMessage("Phase 2: Processing email-based statuses");
    $offset = 0;
    $limit = 1000;
    $totalRows = 0;
    do {
        $sql = "
            INSERT INTO latest_emails (passenger_name, application_number, email_status, download_link, received_at)
            SELECT 
                r.passenger_name,
                g.application_number,
                CASE 
                    WHEN r.eid_delivered = 1 THEN 'Completed'
                    WHEN r.eid_received = 1 THEN 'Card Received'
                    WHEN EXISTS (
                        SELECT 1 
                        FROM gmail_emails g2 
                        WHERE g2.application_number = g.application_number
                        AND (g2.subject LIKE '%Entry permit request has been approved%' 
                             OR g2.snippet LIKE '%entry permit request has been approved%')
                        AND g2.received_at >= :recent_timestamp
                    ) THEN 'E-Visa Approved'
                    WHEN EXISTS (
                        SELECT 1 
                        FROM gmail_emails g2 
                        WHERE g2.application_number = g.application_number
                        AND (g2.subject LIKE '%approved%' OR g2.snippet LIKE '%approved%')
                        AND (g2.snippet LIKE '%change status%' OR g2.snippet LIKE '%status adjustment%'
                             OR g2.subject LIKE '%change status%' OR g2.subject LIKE '%status adjustment%')
                        AND g2.received_at >= :recent_timestamp
                    ) THEN 'Status Changed'
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
                    WHEN g.application_number LIKE '0102%' THEN 'Card is Printed'
                    WHEN g.snippet LIKE '%New Work Entry Permit%' THEN 'E-Visa Submitted'
                    WHEN g.snippet LIKE '%rejected%' THEN 'Refused'
                    WHEN g.snippet LIKE '%change status%' OR g.snippet LIKE '%status adjustment%' THEN 
                        'Change Status Submitted'
                    WHEN g.snippet LIKE '%labour under approval%' THEN 'Labour Under Approval'
                    ELSE NULL
                END AS email_status,
                g.download_link,
                g.received_at
            FROM gmail_emails g
            JOIN residence r 
                ON (LOWER(TRIM(g.snippet)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%')
                    OR LOWER(TRIM(g.full_message)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%')
                    OR LOWER(TRIM(g.subject)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%'))
            WHERE g.received_at >= :recent_timestamp
            AND g.application_number IS NOT NULL
            AND (r.eid_received != 1 AND r.eid_delivered != 1) -- Skip EID statuses
            GROUP BY r.passenger_name, g.application_number
            ON DUPLICATE KEY UPDATE
                email_status = VALUES(email_status),
                application_number = VALUES(application_number),
                download_link = VALUES(download_link),
                received_at = VALUES(received_at)
            LIMIT :offset, :limit
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':recent_timestamp', $lastRunTime, PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $totalRows += $stmt->rowCount();
        $offset += $limit;
    } while ($stmt->rowCount() > 0);

    logMessage("Phase 2 completed: Updated $totalRows rows for email-based statuses");

    // Log unmatched emails
    $unmatchedLogFile = '/www/wwwlogs/gmail_unmatched_emails.log';
    $unmatchedEmails = $pdo->query("
        SELECT g.application_number, g.subject, g.snippet, g.received_at
        FROM gmail_emails g
        LEFT JOIN residence r 
            ON (LOWER(TRIM(g.snippet)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%')
                OR LOWER(TRIM(g.full_message)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%')
                OR LOWER(TRIM(g.subject)) LIKE CONCAT('%', LOWER(TRIM(r.passenger_name)), '%'))
        WHERE r.passenger_name IS NULL
        AND (g.application_number IS NOT NULL OR g.download_link IS NOT NULL)
        LIMIT 100
    ")->fetchAll(PDO::FETCH_ASSOC);
    if ($unmatchedEmails) {
        $unmatchedMessage = "Unmatched emails found (" . count($unmatchedEmails) . "):\n";
        foreach ($unmatchedEmails as $email) {
            $unmatchedMessage .= "AppNum: {$email['application_number']}, Subject: {$email['subject']}, Snippet: {$email['snippet']}, Received: {$email['received_at']}\n";
        }
        file_put_contents($unmatchedLogFile, date('Y-m-d H:i:s') . " - " . $unmatchedMessage . "\n", FILE_APPEND);
        logMessage("Logged " . count($unmatchedEmails) . " unmatched emails to $unmatchedLogFile");
    }

    // Log final status counts
    $statusCounts = $pdo->query("
        SELECT email_status, COUNT(*) as count
        FROM latest_emails
        WHERE email_status IN ('Card Received', 'Completed')
        GROUP BY email_status
    ")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($statusCounts as $status) {
        logMessage("Status {$status['email_status']}: {$status['count']} records");
    }

    $updateTime = microtime(true) - $updateStartTime;
    logMessage("Update of latest_emails completed in $updateTime seconds, total affected rows: " . ($eidInsertCount + $totalRows));
    logMessage("Skipped updating last run timestamp to preserve regular runs");
} catch (Exception $e) {
    logMessage("Error updating latest_emails: " . $e->getMessage());
    exit("Error: " . $e->getMessage());
}

logMessage("Latest emails update script completed at " . date('Y-m-d H:i:s'));
echo "Latest emails update script completed at " . date('Y-m-d H:i:s') . "\n";
?>