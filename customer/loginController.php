<?php
declare(strict_types=1);

ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400, '/', '.sntrips.com'); // Adjust domain
session_start();
ob_start();

date_default_timezone_set('Asia/Dubai');
require_once 'connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

header('Content-Type: application/json');

function sendJsonResponse(array $data): void {
    ob_clean();
    echo json_encode($data);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendJsonResponse(['error' => 'Invalid request method. Only POST is allowed.']);
    }

    if (isset($_POST['Send_OTP']) && $_POST['Send_OTP'] === 'send' && isset($_POST['Email'])) {
        $email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['error' => 'Invalid email format!']);
        }

        $query = "SELECT customer_id, status FROM customer WHERE customer_email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['status'] != 1) {
                sendJsonResponse(['error' => 'Account is not active!']);
            }

            $otp = sprintf("%06d", rand(0, 999999));
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $updateQuery = "UPDATE customer SET otp = :otp, otp_expiry = :expiry WHERE customer_email = :email";
            $updateStmt = $pdo->prepare($updateQuery);
            $updateStmt->execute([
                ':otp' => $otp,
                ':expiry' => $expiry,
                ':email' => $email
            ]);

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
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for SN Travels';
                $mail->Body = "Your OTP is <b>$otp</b>. Valid for 10 minutes.";
                $mail->AltBody = "Your OTP is $otp. Valid for 10 minutes.";

                $mail->send();
                sendJsonResponse(['status' => 'success']);
            } catch (Exception $e) {
                sendJsonResponse(['error' => 'Failed to send OTP: ' . $mail->ErrorInfo]);
            }
        } else {
            sendJsonResponse(['error' => 'Email not found!']);
        }
    } elseif (isset($_POST['Verify_OTP']) && $_POST['Verify_OTP'] === 'verify' && isset($_POST['Email']) && isset($_POST['OTP'])) {
        $email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
        $otp = trim($_POST['OTP']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            sendJsonResponse(['error' => 'Invalid email format!']);
        }

        if (!preg_match('/^\d{6}$/', $otp)) {
            sendJsonResponse(['error' => 'OTP must be a 6-digit number!']);
        }

        $query = "SELECT customer_id, otp, otp_expiry, status FROM customer WHERE customer_email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['status'] != 1) {
                sendJsonResponse(['error' => 'Account is not active!']);
            }

            $current_time = date('Y-m-d H:i:s');
            if ((string)$user['otp'] === (string)$otp) {
                if ($current_time <= $user['otp_expiry']) {
                    $_SESSION['customer_id'] = $user['customer_id'];
                    $_SESSION['email'] = $email;

                    $clearQuery = "UPDATE customer SET otp = NULL, otp_expiry = NULL WHERE customer_email = :email";
                    $clearStmt = $pdo->prepare($clearQuery);
                    $clearStmt->execute([':email' => $email]);

                    file_put_contents('/tmp/session_set.log', "Session set: " . $user['customer_id'] . "\n", FILE_APPEND);
                    sendJsonResponse(['status' => 'success']);
                } else {
                    sendJsonResponse(['error' => 'OTP expired!']);
                }
            } else {
                sendJsonResponse(['error' => 'Invalid OTP!']);
            }
        } else {
            sendJsonResponse(['error' => 'Email not found!']);
        }
    } elseif (isset($_POST['GetPassengerStatus'])) {
        if (!isset($_SESSION['customer_id'])) {
            sendJsonResponse(['error' => 'User not logged in.']);
        }

        $customer_id = (int)$_SESSION['customer_id'];
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $search = isset($_POST['search']['value']) ? '%' . $_POST['search']['value'] . '%' : '%';

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
            r.customer_id = :customer_id
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

        $countQuery = $pdo->prepare("SELECT COUNT(*) as total FROM residence r JOIN customer c ON r.customer_id = c.customer_id LEFT JOIN company co ON r.company = co.company_id WHERE r.customer_id = :customer_id AND r.deleted = 0 AND r.cancelled = 0");
        $countQuery->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $countQuery->execute();
        $totalRecords = $countQuery->fetch(PDO::FETCH_ASSOC)['total'];

        $filteredQuery = $pdo->prepare("SELECT COUNT(*) as filtered FROM residence r JOIN customer c ON r.customer_id = c.customer_id LEFT JOIN company co ON r.company = co.company_id WHERE r.customer_id = :customer_id AND (r.passenger_name LIKE :search OR c.customer_name LIKE :search OR COALESCE(co.company_name, '') LIKE :search_term) AND r.deleted = 0 AND r.cancelled = 0");
        $filteredQuery->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
        $filteredQuery->bindValue(':search', $search, PDO::PARAM_STR);
        $filteredQuery->bindValue(':search_term', $search, PDO::PARAM_STR);
        $filteredQuery->execute();
        $filteredRecords = $filteredQuery->fetch(PDO::FETCH_ASSOC)['filtered'];

        sendJsonResponse([
            'draw' => (int)($_POST['draw'] ?? 0),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $passengers
        ]);
    } elseif (isset($_POST['GetCustomerInfo'])) {
        if (!isset($_SESSION['customer_id'])) {
            sendJsonResponse(['error' => 'User not logged in.']);
        }

        $customer_id = (int)$_SESSION['customer_id'];
        $sql = "SELECT customer_name, customer_phone, customer_email FROM customer WHERE customer_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();
        $customerInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse($customerInfo);
    } elseif (isset($_POST['GetLedgerCurrency'])) {
        if (!isset($_POST['ID'])) {
            sendJsonResponse(['error' => 'Currency ID is required.']);
        }

        $currency_id = (int)$_POST['ID'];
        $sql = "SELECT currencyName FROM currency WHERE currencyID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $currency_id, PDO::PARAM_INT);
        $stmt->execute();
        $currency = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse($currency);
    } elseif (isset($_POST['GetResidenceReport'])) {
        if (!isset($_SESSION['customer_id'])) {
            sendJsonResponse(['error' => 'User not logged in.']);
        }

        if (!isset($_POST['CurID'])) {
            sendJsonResponse(['error' => 'Currency ID is required.']);
        }

        $customer_id = (int)$_SESSION['customer_id'];
        $currency_id = (int)$_POST['CurID'];

        $sql = "SELECT 
            residence.passenger_name AS main_passenger,
            IFNULL((SELECT IFNULL(company_name,'') FROM company 
                    WHERE company.company_id = residence.company),'') AS company_name, 
            DATE(residence.datetime) AS dt,  
            IFNULL(SUM(residence.sale_price),0) AS sale_price, 
            (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
             FROM residencefine 
             INNER JOIN residence ON residence.residenceID = residencefine.residenceID 
             WHERE residence.customer_id = :id 
             AND residencefine.fineCurrencyID = :CurID 
             AND residence.passenger_name = main_passenger 
             AND residence.islocked != 1) AS fine, 
            (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
             FROM customer_payments 
             INNER JOIN residence ON residence.residenceID = customer_payments.PaymentFor 
             WHERE customer_payments.customer_id = :id 
             AND customer_payments.currencyID = :CurID 
             AND residence.passenger_name = main_passenger 
             AND residence.islocked != 1) AS residencePayment, 
            (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
             FROM customer_payments 
             INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment 
             INNER JOIN residence ON residence.residenceID = residencefine.residenceID 
             WHERE customer_payments.customer_id = :id 
             AND customer_payments.currencyID = :CurID 
             AND residence.passenger_name = main_passenger 
             AND residence.islocked != 1) AS finePayment,
            (IFNULL(SUM(residence.sale_price),0) + 
             (SELECT IFNULL(SUM(residencefine.fineAmount),0) 
              FROM residencefine 
              INNER JOIN residence ON residence.residenceID = residencefine.residenceID 
              WHERE residence.customer_id = :id 
              AND residencefine.fineCurrencyID = :CurID 
              AND residence.passenger_name = main_passenger 
              AND residence.islocked != 1) - 
             (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
              FROM customer_payments 
              INNER JOIN residence ON residence.residenceID = customer_payments.PaymentFor 
              WHERE customer_payments.customer_id = :id 
              AND customer_payments.currencyID = :CurID 
              AND residence.passenger_name = main_passenger 
              AND residence.islocked != 1) - 
             (SELECT IFNULL(SUM(customer_payments.payment_amount),0) 
              FROM customer_payments 
              INNER JOIN residencefine ON residencefine.residenceFineID = customer_payments.residenceFinePayment 
              INNER JOIN residence ON residence.residenceID = residencefine.residenceID 
              WHERE customer_payments.customer_id = :id 
              AND customer_payments.currencyID = :CurID 
              AND residence.passenger_name = main_passenger 
              AND residence.islocked != 1)) AS balance
        FROM residence  
        WHERE residence.customer_id = :id 
        AND residence.saleCurID = :CurID 
        AND residence.islocked != 1 
        GROUP BY residence.passenger_name, residence.VisaType 
        ORDER BY residence.residenceID ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
        $stmt->bindParam(':CurID', $currency_id, PDO::PARAM_INT);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse($records);
    } else {
        sendJsonResponse(['error' => 'Invalid request.']);
    }
} catch (Exception $e) {
    file_put_contents('/tmp/passenger_status.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
    sendJsonResponse(['error' => $e->getMessage()]);
}

unset($pdo);
?>