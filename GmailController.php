<?php
declare(strict_types=1);

session_start();
date_default_timezone_set('Asia/Dubai');

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/www/wwwlogs/php_errors.log');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'connection.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST is allowed.');
    }

    if (isset($_POST['GetEmails'])) {
        $filter = $_POST['Filter'] ?? 'inbox';
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $search = isset($_POST['search']['value']) ? '%' . $_POST['search']['value'] . '%' : '%';

        // Ensure full_message is included in the query
        $sql = "SELECT e.email_id, e.subject, e.from_email, e.to_email, e.snippet, e.received_at, 
                       e.application_number, e.download_link, e.full_message 
                FROM gmail_emails e 
                WHERE (e.application_number IS NOT NULL OR e.download_link IS NOT NULL)
                AND (e.subject LIKE :search OR e.from_email LIKE :search OR e.snippet LIKE :search)
                ORDER BY e.received_at DESC 
                LIMIT :start, :length";

        $selectQuery = $pdo->prepare($sql);
        $selectQuery->bindValue(':start', $start, PDO::PARAM_INT);
        $selectQuery->bindValue(':length', $length, PDO::PARAM_INT);
        $selectQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $selectQuery->execute();
        $emails = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

        // Log the response for debugging
        file_put_contents('/www/wwwlogs/gmail_controller.log', date('Y-m-d H:i:s') . " - Fetched " . count($emails) . " emails\n", FILE_APPEND);
        if (!empty($emails)) {
            file_put_contents('/www/wwwlogs/gmail_controller.log', date('Y-m-d H:i:s') . " - Sample email: " . json_encode($emails[0]) . "\n", FILE_APPEND);
        }

        // Count total records for pagination
        $countQuery = $pdo->prepare("SELECT COUNT(*) as total 
                                     FROM gmail_emails 
                                     WHERE (application_number IS NOT NULL OR download_link IS NOT NULL)");
        $countQuery->execute();
        $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

        // Count filtered records
        $filteredQuery = $pdo->prepare("SELECT COUNT(*) as filtered 
                                        FROM gmail_emails 
                                        WHERE (application_number IS NOT NULL OR download_link IS NOT NULL)
                                        AND (subject LIKE :search OR from_email LIKE :search OR snippet LIKE :search)");
        $filteredQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $filteredQuery->execute();
        $filteredRecords = $filteredQuery->fetch(PDO::FETCH_ASSOC)['filtered'];

        $response = [
            'draw' => (int)($_POST['draw'] ?? 0),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $emails
        ];

        echo json_encode($response);
        exit;
    } elseif (isset($_POST['DeleteEmail']) && isset($_POST['EmailId'])) {
        $sql = "DELETE FROM gmail_emails WHERE email_id = :email_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email_id', $_POST['EmailId'], PDO::PARAM_STR);
        $stmt->execute();
        echo json_encode(['status' => 'Success']);
        exit;
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit;
    }
} catch (Exception $e) {
    file_put_contents('/www/wwwlogs/gmail_controller.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

unset($pdo);
?>