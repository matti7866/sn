<?php
require_once 'vendor/autoload.php';
require_once 'connection.php';

// Log file
$logFile = '/www/wwwlogs/gmail_to_db_detailed.log';

function logMessage($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

logMessage("Latest emails update script started");

// Update of latest_emails for all emails
try {
    logMessage("Starting full update of latest_emails");

    $updateStartTime = microtime(true);
    $sql = "
        INSERT INTO latest_emails (passenger_name, application_number, email_status, download_link, received_at)
        SELECT 
            r.passenger_name,
            g.application_number,
            CASE 
                WHEN g.application_number LIKE '0102%' THEN 'Card is Printed'
                WHEN g.snippet LIKE '%entry permit request has been approved%' 
                     OR g.subject LIKE '%Entry permit request has been approved%' THEN 'E-Visa Approved'
                WHEN g.snippet LIKE '%New Work Entry Permit%' THEN 'E-Visa Submitted'
                WHEN g.snippet LIKE '%rejected%' THEN 'Refused'
                WHEN g.snippet LIKE '%Dear Customer, Your application with number () of the service \"New Residency\" for  has been approved%' THEN 'Residency Approved'
                WHEN g.snippet LIKE '%Dear Customer, Your application with number () of the service \"New Residency\" for  has been submitted%' THEN 'Residency Submitted'
                WHEN g.snippet LIKE '%change status%' OR g.snippet LIKE '%status adjustment%' THEN 
                    CASE 
                        WHEN g.snippet LIKE '%approved%' THEN 'Status Changed'
                        ELSE 'Change Status Submitted'
                    END
                WHEN g.snippet LIKE '%residency submitted%' OR g.full_message LIKE '%residency submitted%' THEN 'Residency Under Process'
                WHEN g.snippet LIKE '%residency approved%' OR g.full_message LIKE '%residency approved%' THEN 'Residency Approved'
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
        GROUP BY r.passenger_name, g.application_number
        ON DUPLICATE KEY UPDATE
            email_status = VALUES(email_status),
            download_link = VALUES(download_link),
            received_at = VALUES(received_at)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $updateTime = microtime(true) - $updateStartTime;
    $rowCount = $stmt->rowCount();
    logMessage("Full update of latest_emails completed in $updateTime seconds, affected $rowCount rows");
} catch (Exception $e) {
    logMessage("Error updating latest_emails: " . $e->getMessage());
}

logMessage("Latest emails update script completed at " . date('Y-m-d H:i:s'));
echo "Latest emails update script completed at " . date('Y-m-d H:i:s') . "\n";
?>