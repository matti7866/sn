<?php
declare(strict_types=1);

session_start();
date_default_timezone_set('Asia/Dubai');

require_once 'connection.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST is allowed.');
    }

    if (isset($_POST['GetPassengerStatus'])) {
        $customer_id = (int)$_SESSION['customer_id'];  // Get logged-in customer's ID
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $search = isset($_POST['search']['value']) ? '%' . $_POST['search']['value'] . '%' : '%';

        // Main query to fetch passenger status for the logged-in customer
        $sql = "SELECT 
                    c.customer_name,
                    r.passenger_name,
                    co.company_name AS establishment_name,
                    COALESCE(
                        (SELECT 
                            CASE 
                                WHEN g.application_number LIKE '0102%' THEN 'Card is Printed'
                                WHEN g.snippet LIKE '%entry permit request has been approved%' OR g.subject LIKE '%Entry permit request has been approved%' THEN 'E-Visa Approved'
                                WHEN g.snippet LIKE '%new work permit%' THEN 'E-Visa Under Process'
                                WHEN g.snippet LIKE '%rejected%' THEN 'Refused'
                                WHEN g.snippet LIKE '%change status%' OR g.snippet LIKE '%status adjustment%' THEN 
                                    CASE 
                                        WHEN g.snippet LIKE '%approved%' THEN 'Status Changed'
                                        ELSE 'Change Status Submitted'
                                    END
                                WHEN g.snippet LIKE '%residency submitted%' THEN 'Residency Under Process'
                                WHEN g.snippet LIKE '%residency approved%' THEN 'Residency Approved'
                                WHEN g.snippet LIKE '%labour under approval%' THEN 'Labour Under Approval'
                                ELSE NULL
                            END
                         FROM gmail_emails g 
                         INNER JOIN (
                             SELECT application_number, MAX(received_at) AS max_received_at
                             FROM gmail_emails g2
                             WHERE g2.application_number IN (
                                 SELECT g3.application_number 
                                 FROM gmail_emails g3 
                                 WHERE (g3.snippet LIKE CONCAT('%', r.passenger_name, '%') OR g3.full_message LIKE CONCAT('%', r.passenger_name, '%'))
                                 AND g3.application_number IS NOT NULL
                             )
                             GROUP BY application_number
                         ) latest ON g.application_number = latest.application_number AND g.received_at = latest.max_received_at
                         WHERE g.application_number IS NOT NULL
                         ORDER BY g.received_at DESC 
                         LIMIT 1),
                        NULLIF(r.MOHREStatus, ''),
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
                    ) AS current_status,
                    (SELECT g.application_number 
                     FROM gmail_emails g 
                     INNER JOIN (
                         SELECT application_number, MAX(received_at) AS max_received_at
                         FROM gmail_emails g2
                         WHERE g2.application_number IN (
                             SELECT g3.application_number 
                             FROM gmail_emails g3 
                             WHERE (g3.snippet LIKE CONCAT('%', r.passenger_name, '%') OR g3.full_message LIKE CONCAT('%', r.passenger_name, '%'))
                             AND g3.application_number IS NOT NULL
                         )
                         GROUP BY application_number
                     ) latest ON g.application_number = latest.application_number AND g.received_at = latest.max_received_at
                     WHERE g.application_number IS NOT NULL
                     ORDER BY g.received_at DESC 
                     LIMIT 1) AS application_number,
                    (SELECT IFNULL(SUM(cp.payment_amount), 0) 
                     FROM customer_payments cp 
                     WHERE cp.PaymentFor = r.residenceID) AS total_paid,
                    CONCAT(DATEDIFF(CURDATE(), DATE(r.datetime)), ' days ago') AS due_since,
                    (SELECT g.download_link 
                     FROM gmail_emails g 
                     INNER JOIN (
                         SELECT application_number, MAX(received_at) AS max_received_at
                         FROM gmail_emails g2
                         WHERE g2.application_number IN (
                             SELECT g3.application_number 
                             FROM gmail_emails g3 
                             WHERE (g3.snippet LIKE CONCAT('%', r.passenger_name, '%') OR g3.full_message LIKE CONCAT('%', r.passenger_name, '%'))
                             AND g3.application_number IS NOT NULL
                         )
                         GROUP BY application_number
                     ) latest ON g.application_number = latest.application_number AND g.received_at = latest.max_received_at
                     WHERE g.application_number IS NOT NULL
                     ORDER BY g.received_at DESC 
                     LIMIT 1) AS download_link
                FROM 
                    residence r
                JOIN 
                    customer c ON r.customer_id = c.customer_id
                LEFT JOIN 
                    company co ON r.company = co.company_id
                WHERE 
                    r.customer_id = :customer_id  -- Filter by logged-in customer
                    AND (r.passenger_name LIKE :search OR c.customer_name LIKE :search OR COALESCE(co.company_name, '') LIKE :search_term)
                    AND r.deleted = 0 
                    AND r.cancelled = 0
                ORDER BY 
                    r.datetime DESC
                LIMIT :start, :length";

        $selectQuery = $pdo->prepare($sql);
        $selectQuery->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $selectQuery->bindValue(':start', $start, PDO::PARAM_INT);
        $selectQuery->bindValue(':length', $length, PDO::PARAM_INT);
        $selectQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $selectQuery->bindValue(':search_term', $search, PDO::PARAM_STR);
        $selectQuery->execute();
        $passengers = $selectQuery->fetchAll(PDO::FETCH_ASSOC);

        // Count total records for this customer
        $countQuery = $pdo->prepare("SELECT COUNT(*) as total 
                                    FROM residence r 
                                    JOIN customer c ON r.customer_id = c.customer_id 
                                    LEFT JOIN company co ON r.company = co.company_id 
                                    WHERE r.customer_id = :customer_id 
                                    AND r.deleted = 0 
                                    AND r.cancelled = 0");
        $countQuery->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $countQuery->execute();
        $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

        // Count filtered records for this customer
        $filteredQuery = $pdo->prepare("SELECT COUNT(*) as filtered 
                                        FROM residence r 
                                        JOIN customer c ON r.customer_id = c.customer_id
                                        LEFT JOIN company co ON r.company = co.company_id
                                        WHERE r.customer_id = :customer_id
                                        AND (r.passenger_name LIKE :search OR c.customer_name LIKE :search OR COALESCE(co.company_name, '') LIKE :search_term)
                                        AND r.deleted = 0 
                                        AND r.cancelled = 0");
        $filteredQuery->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $filteredQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $filteredQuery->bindValue(':search_term', $search, PDO::PARAM_STR);
        $filteredQuery->execute();
        $filteredRecords = $filteredQuery->fetch(PDO::FETCH_ASSOC)['filtered'];

        $response = [
            'draw' => (int)($_POST['draw'] ?? 0),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $passengers
        ];

        echo json_encode($response);
        exit;
    } else {
        echo json_encode(['error' => 'Invalid request.']);
        exit;
    }
} catch (Exception $e) {
    file_put_contents('/www/wwwlogs/passenger_status.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

unset($pdo);
?>