<?php
declare(strict_types=1);

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/www/wwwlogs/php_errors.log');

ob_start();

$logFile = '/www/wwwlogs/passenger_status.log';
if (is_writable(dirname($logFile))) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Script started\n", FILE_APPEND);
}

session_start();
date_default_timezone_set('Asia/Dubai');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    ob_end_flush();
    exit;
}

require_once 'connection.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST is allowed.');
    }

    if (is_writable(dirname($logFile))) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Request: " . json_encode($_POST) . "\n", FILE_APPEND);
    }

    if (isset($_POST['GetPassengerStatus'])) {
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $search = isset($_POST['search']['value']) ? '%' . $_POST['search']['value'] . '%' : '%';
        $statusFilter = isset($_POST['status_filter']) ? $_POST['status_filter'] : '';

        $startTime = microtime(true);
        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Start query\n", FILE_APPEND);
        }

        $sql = "
            SELECT 
                r.residenceID AS residence_id,  -- Changed from residence_id to residenceID
                c.customer_name,
                r.passenger_name,
                co.company_name AS establishment_name,
                CASE 
                    WHEN le.email_status IS NOT NULL THEN le.email_status
                    WHEN r.MOHREStatus IS NOT NULL AND r.MOHREStatus != '' THEN r.MOHREStatus
                    WHEN r.completedStep = '1' THEN 'Offer Letter Pending'
                    WHEN r.completedStep = '2' THEN 'Offer Letter Under Process'
                    WHEN r.completedStep = '3' THEN 'Labour Approved'
                    WHEN r.completedStep = '4' THEN 'Ready For E-Visa'
                    WHEN r.completedStep = '5' THEN 'E-Visa Under Process'
                    WHEN r.completedStep = '6' THEN 'E-Visa Approved'
                    WHEN r.completedStep = '7' THEN 'Change Status Ready To Pay'
                    WHEN r.completedStep = '8' THEN 'Residency is Ready To Pay'
                    WHEN r.completedStep = '9' THEN 'Residency Approved'
                    WHEN r.completedStep = '10' THEN 'Card Under Process'
                    ELSE 'Unknown Status'
                END AS current_status,
                le.application_number,
                COALESCE(r.sale_price, 0) AS sale_price,
                (SELECT IFNULL(SUM(cp.payment_amount), 0) 
                 FROM customer_payments cp 
                 WHERE cp.PaymentFor = r.residenceID) AS total_paid,
                CONCAT(DATEDIFF(CURDATE(), DATE(r.datetime)), ' days ago') AS due_since,
                le.download_link
            FROM 
                residence r
            JOIN 
                customer c ON r.customer_id = c.customer_id
            LEFT JOIN 
                company co ON r.company = co.company_id
            LEFT JOIN 
                latest_emails le ON le.passenger_name = r.passenger_name 
                AND le.received_at = (
                    SELECT MAX(le2.received_at)
                    FROM latest_emails le2
                    WHERE le2.passenger_name = r.passenger_name
                )
            WHERE 
                (r.passenger_name LIKE :search 
                 OR c.customer_name LIKE :search 
                 OR COALESCE(co.company_name, '') LIKE :search_term)
                AND r.deleted = 0 
                AND r.cancelled = 0";
        
        if (!empty($statusFilter)) {
            $sql .= " AND (
                CASE 
                    WHEN le.email_status IS NOT NULL THEN le.email_status
                    WHEN r.MOHREStatus IS NOT NULL AND r.MOHREStatus != '' THEN r.MOHREStatus
                    WHEN r.completedStep = '1' THEN 'Offer Letter Pending'
                    WHEN r.completedStep = '2' THEN 'Offer Letter Under Process'
                    WHEN r.completedStep = '3' THEN 'Labour Approved'
                    WHEN r.completedStep = '4' THEN 'Ready For E-Visa'
                    WHEN r.completedStep = '5' THEN 'E-Visa Under Process'
                    WHEN r.completedStep = '6' THEN 'E-Visa Approved'
                    WHEN r.completedStep = '7' THEN 'Change Status Ready To Pay'
                    WHEN r.completedStep = '8' THEN 'Residency is Ready To Pay'
                    WHEN r.completedStep = '9' THEN 'Residency Approved'
                    WHEN r.completedStep = '10' THEN 'Card Under Process'
                    ELSE 'Unknown Status'
                END
            ) = :status_filter";
        }

        $sql .= " ORDER BY r.datetime DESC LIMIT :start, :length";

        $selectQuery = $pdo->prepare($sql);
        $selectQuery->bindValue(':start', $start, PDO::PARAM_INT);
        $selectQuery->bindValue(':length', $length, PDO::PARAM_INT);
        $selectQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $selectQuery->bindValue(':search_term', $search, PDO::PARAM_STR);
        if (!empty($statusFilter)) {
            $selectQuery->bindValue(':status_filter', $statusFilter, PDO::PARAM_STR);
        }
        $selectQuery->execute();
        $passengers = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

        if (is_writable(dirname($logFile))) {
            foreach ($passengers as $passenger) {
                if (!empty($passenger['email_status'])) {
                    file_put_contents($logFile, 
                        date('Y-m-d H:i:s') . " - Status for {$passenger['passenger_name']}: {$passenger['current_status']} (from email)\n", 
                        FILE_APPEND);
                } elseif (!empty($passenger['MOHREStatus'])) {
                    file_put_contents($logFile, 
                        date('Y-m-d H:i:s') . " - Status for {$passenger['passenger_name']}: {$passenger['current_status']} (from MOHREStatus)\n", 
                        FILE_APPEND);
                } else {
                    file_put_contents($logFile, 
                        date('Y-m-d H:i:s') . " - Status for {$passenger['passenger_name']}: {$passenger['current_status']} (from completedStep)\n", 
                        FILE_APPEND);
                }
            }
        }

        $executionTime = microtime(true) - $startTime;
        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Query executed in $executionTime seconds\n", FILE_APPEND);
        }

        $countQuery = $pdo->query("SELECT COUNT(*) as total FROM residence r JOIN customer c ON r.customer_id = c.customer_id LEFT JOIN company co ON r.company = co.company_id WHERE r.deleted = 0 AND r.cancelled = 0");
        $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

        $filteredSql = "
            SELECT COUNT(*) as filtered 
            FROM residence r 
            JOIN customer c ON r.customer_id = c.customer_id
            LEFT JOIN company co ON r.company = co.company_id
            LEFT JOIN latest_emails le ON le.passenger_name = r.passenger_name 
                AND le.received_at = (
                    SELECT MAX(le2.received_at)
                    FROM latest_emails le2
                    WHERE le2.passenger_name = r.passenger_name
                )
            WHERE (r.passenger_name LIKE :search OR c.customer_name LIKE :search OR COALESCE(co.company_name, '') LIKE :search_term)
            AND r.deleted = 0 AND r.cancelled = 0";
        if (!empty($statusFilter)) {
            $filteredSql .= " AND (
                CASE 
                    WHEN le.email_status IS NOT NULL THEN le.email_status
                    WHEN r.MOHREStatus IS NOT NULL AND r.MOHREStatus != '' THEN r.MOHREStatus
                    WHEN r.completedStep = '1' THEN 'Offer Letter Pending'
                    WHEN r.completedStep = '2' THEN 'Offer Letter Under Process'
                    WHEN r.completedStep = '3' THEN 'Labour Approved'
                    WHEN r.completedStep = '4' THEN 'Ready For E-Visa'
                    WHEN r.completedStep = '5' THEN 'E-Visa Under Process'
                    WHEN r.completedStep = '6' THEN 'E-Visa Approved'
                    WHEN r.completedStep = '7' THEN 'Change Status Ready To Pay'
                    WHEN r.completedStep = '8' THEN 'Residency is Ready To Pay'
                    WHEN r.completedStep = '9' THEN 'Residency Approved'
                    WHEN r.completedStep = '10' THEN 'Card Under Process'
                    ELSE 'Unknown Status'
                END
            ) = :status_filter";
        }
        $filteredQuery = $pdo->prepare($filteredSql);
        $filteredQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $filteredQuery->bindValue(':search_term', $search, PDO::PARAM_STR);
        if (!empty($statusFilter)) {
            $filteredQuery->bindValue(':status_filter', $statusFilter, PDO::PARAM_STR);
        }
        $filteredQuery->execute();
        $filteredRecords = $filteredQuery->fetch(PDO::FETCH_ASSOC)['filtered'];

        $response = [
            'draw' => (int)($_POST['draw'] ?? 0),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $passengers
        ];

        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Response sent: " . json_encode($response) . "\n", FILE_APPEND);
        }
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    if (isset($_POST['GetPassengerDetails'])) {
        $passenger_name = $_POST['passenger_name'] ?? '';
        if (empty($passenger_name)) {
            throw new Exception('Passenger name is required.');
        }

        $startTime = microtime(true);
        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Start modal query for $passenger_name\n", FILE_APPEND);
        }

        $sql = "
            SELECT 
                step_name,
                attachment,
                datetime,
                source,
                original_name
            FROM (
                SELECT 
                    COALESCE(
                        le.email_status,
                        NULLIF(r.mohreStatus, ''),
                        CASE 
                            WHEN r.completedStep = '1' THEN 'Waiting for Offer Letter'
                            WHEN r.completedStep = '1a' THEN 'Labour Under Process'
                            WHEN r.completedStep = '2' THEN 'Labour Approved'
                            WHEN r.completedStep = '3' THEN 'Labour Fee Paid'
                            WHEN r.completedStep = '4' THEN 'Ready for E-Visa'
                            WHEN r.completedStep = '6' THEN 'Medical Test Under Process'
                            WHEN r.completedStep = '10' THEN 'Completed'
                            ELSE CONCAT('Step ', r.completedStep)
                        END
                    ) AS step_name,
                    le.download_link AS attachment,
                    le.received_at AS datetime,
                    'Email' AS source,
                    NULL AS original_name
                FROM residence r
                LEFT JOIN latest_emails le 
                    ON le.passenger_name = r.passenger_name
                WHERE r.passenger_name = :passenger_name
                AND r.deleted = 0 
                AND r.cancelled = 0

                UNION ALL

                SELECT 
                    CASE 
                        WHEN rd.fileType = 2 THEN 'Waiting for Offer Letter'
                        WHEN rd.fileType = 3 THEN 'Labour Under Process'
                        WHEN rd.fileType = 4 THEN 'Labour Approved'
                        WHEN rd.fileType = 5 THEN 'Labour Fee Paid'
                        WHEN rd.fileType = 6 THEN 'Ready for E-Visa'
                        WHEN rd.fileType = 7 THEN 'Medical Test Under Process'
                        WHEN rd.fileType = 8 THEN 'Completed'
                        ELSE CONCAT('Step ', rd.fileType)
                    END AS step_name,
                    rd.file_name AS attachment,
                    r.datetime AS datetime,
                    'Database' AS source,
                    rd.original_name AS original_name
                FROM residence r
                JOIN residencedocuments rd ON rd.ResID = r.residenceID
                WHERE r.passenger_name = :passenger_name
                AND r.deleted = 0 
                AND r.cancelled = 0
                AND rd.fileType >= 2
            ) AS combined_steps
            WHERE step_name IS NOT NULL
            ORDER BY datetime DESC";

        $selectQuery = $pdo->prepare($sql);
        $selectQuery->bindValue(':passenger_name', $passenger_name, PDO::PARAM_STR);
        $selectQuery->execute();
        $steps = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

        $executionTime = microtime(true) - $startTime;
        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Modal query executed in $executionTime seconds\n", FILE_APPEND);
        }

        $response = [
            'passenger_name' => $passenger_name,
            'steps' => $steps
        ];

        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Modal response sent\n", FILE_APPEND);
        }
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    if (isset($_POST['DownloadAttachment'])) {
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        while (ob_get_level()) ob_end_clean();

        $file_path = $_POST['file_path'] ?? '';
        if (empty($file_path)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'File path is required.']);
            exit;
        }

        $sql = "SELECT original_name FROM residencedocuments WHERE file_name = :file_name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':file_name', $file_path, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $original_name = $result['original_name'] ?? basename($file_path);

        $base_dir = '/www/wwwroot/sntravels/uploads/';
        $full_path = realpath($base_dir . $file_path);

        if (is_writable(dirname($logFile))) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - File path requested: $file_path\n", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Resolved full path: $full_path\n", FILE_APPEND);
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - File exists: " . (file_exists($full_path) ? 'Yes' : 'No') . "\n", FILE_APPEND);
            if (file_exists($full_path)) {
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - File size: " . filesize($full_path) . " bytes\n", FILE_APPEND);
            }
        }

        if ($full_path === false || strpos($full_path, $base_dir) !== 0) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid file path: ' . $base_dir . $file_path]);
            exit;
        }

        if (!file_exists($full_path)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'File not found at ' . $full_path]);
            exit;
        }

        header('Cache-Control: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename="' . $original_name . '"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($full_path));

        readfile($full_path);
        exit;
    }

    if (isset($_POST['GetBanks'])) {
        $sql = "SELECT id, bank_name FROM banks ORDER BY bank_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $banks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($banks);
        exit;
    }

    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Invalid request.']);
    exit;
} catch (Exception $e) {
    if (is_writable(dirname($logFile))) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
    ob_end_clean();
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

unset($pdo);
?>