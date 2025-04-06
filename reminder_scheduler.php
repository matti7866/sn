<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'connection.php';

$fixedEmail = "selabnadirydxb@gmail.com"; // Replace with your email

date_default_timezone_set('Asia/Dubai'); // UTC+4
$serverTime = date('Y-m-d H:i:s');
echo "Server time (UTC+4): $serverTime\n";

$currentDateTime = date('Y-m-d H:i');
echo "Script started at $currentDateTime\n";

// Match reminders within the current minute
$start = date('Y-m-d H:i:00', strtotime($currentDateTime));
$end = date('Y-m-d H:i:59', strtotime($currentDateTime));
$sql = "SELECT * 
        FROM reminder 
        WHERE reminder_datetime BETWEEN :start AND :end 
        AND status = 'pending'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':start', $start);
$stmt->bindParam(':end', $end);
$stmt->execute();
$reminders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Found " . count($reminders) . " reminders due between $start and $end\n";
if (count($reminders) > 0) {
    foreach ($reminders as $reminder) {
        echo "Reminder ID: " . $reminder['reminder_id'] . ", Subject: " . $reminder['reminder_subject'] . ", Time: " . $reminder['reminder_datetime'] . ", Status: " . $reminder['status'] . "\n";
    }
}

foreach ($reminders as $reminder) {
    sendEmailReminder($reminder, $fixedEmail);
    markReminderAsSent($pdo, $reminder['reminder_id']);
}

// Cleanup old reminders (older than 5 minutes)
$pastDateTime = date('Y-m-d H:i:s', strtotime('-5 minutes'));
$sqlCleanup = "UPDATE reminder 
               SET status = 'completed' 
               WHERE reminder_datetime < :pastDateTime 
               AND status = 'pending'";
$stmtCleanup = $pdo->prepare($sqlCleanup);
$stmtCleanup->bindParam(':pastDateTime', $pastDateTime);
$stmtCleanup->execute();
echo "Cleaned up old reminders before $pastDateTime\n";

function sendEmailReminder($reminder, $toEmail) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'selabnadirydxb@gmail.com';
        $mail->Password = 'qyzuznoxbrfmjvxa';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('selabnadirydxb@gmail.com', 'SN Travels');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = "Reminder: " . $reminder['reminder_subject'];
        $mail->Body    = "<h3>Reminder from SN Travels</h3>
                          <p><strong>Subject:</strong> " . htmlspecialchars($reminder['reminder_subject']) . "</p>
                          <p><strong>Description:</strong> " . nl2br(htmlspecialchars($reminder['reminder_description'])) . "</p>
                          <p><strong>Scheduled Time:</strong> " . htmlspecialchars($reminder['reminder_datetime']) . "</p>";
        $mail->AltBody = "Reminder from SN Travels: " . $reminder['reminder_subject'] . "\nDescription: " . $reminder['reminder_description'] . "\nScheduled Time: " . $reminder['reminder_datetime'];

        $mail->send();
        echo "Reminder email sent for ID: " . $reminder['reminder_id'] . " at " . date('Y-m-d H:i:s') . "\n";
    } catch (Exception $e) {
        echo "Failed to send reminder email for ID: " . $reminder['reminder_id'] . ". Error: " . $mail->ErrorInfo . "\n";
    }
}

function markReminderAsSent($pdo, $reminderId) {
    try {
        $sql = "UPDATE reminder SET status = 'sent' WHERE reminder_id = :reminder_id AND status = 'pending'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':reminder_id', $reminderId);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "Marked reminder ID: " . $reminderId . " as sent at " . date('Y-m-d H:i:s') . "\n";
        } else {
            echo "Reminder ID: " . $reminderId . " was already processed or not pending\n";
        }
    } catch (PDOException $e) {
        echo "Failed to update reminder status for ID: " . $reminderId . ". Error: " . $e->getMessage() . "\n";
    }
}

unset($pdo);
echo "Script completed at " . date('Y-m-d H:i:s') . "\n";
?>