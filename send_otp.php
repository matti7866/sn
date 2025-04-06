<?php
session_start();
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Assumes PHPMailer is installed via Composer

function debugLog($message) {
    echo "[DEBUG] " . $message . "<br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    debugLog("Received email: $email");

    $query = "SELECT staff_id FROM staff WHERE staff_email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        debugLog("Email found in staff table. Staff ID: " . $user['staff_id']);
        $otp = rand(100000, 999999);
        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        debugLog("Generated OTP: $otp, Expiry: $expiry");

        $updateQuery = "UPDATE staff SET otp = :otp, otp_expiry = :expiry WHERE staff_email = :email";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->execute([
            ':otp' => $otp,
            ':expiry' => $expiry,
            ':email' => $email
        ]);
        debugLog("OTP stored successfully. Rows affected: " . $updateStmt->rowCount());

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2;
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

            debugLog("Sending OTP email...");
            $mail->send();
            debugLog("Email sent successfully!");
            echo "OTP sent successfully! Check your email.";
        } catch (Exception $e) {
            debugLog("SMTP Error: " . $mail->ErrorInfo);
            echo "Failed to send OTP: " . $mail->ErrorInfo;
        }
    } else {
        debugLog("Email not found in staff database!");
        echo "Email not found!";
    }
} else {
    echo "Invalid request!";
}
?>