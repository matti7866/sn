<?php
session_start();
include 'connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Send_OTP']) && $_POST['Send_OTP'] === 'send' && isset($_POST['Email'])) {
        $email = $_POST['Email'];

        $query = "SELECT staff_id FROM staff WHERE staff_email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $otp = rand(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            $updateQuery = "UPDATE staff SET otp = :otp, otp_expiry = :expiry WHERE staff_email = :email";
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
                echo "success";
            } catch (Exception $e) {
                echo "Failed to send OTP: " . $mail->ErrorInfo;
            }
        } else {
            echo "Email not found!";
        }
    } elseif (isset($_POST['Verify_OTP']) && $_POST['Verify_OTP'] === 'verify' && isset($_POST['Email']) && isset($_POST['OTP'])) {
        $email = $_POST['Email'];
        $otp = $_POST['OTP'];

        $query = "SELECT staff_id, role_id, otp, otp_expiry FROM staff WHERE staff_email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $current_time = date('Y-m-d H:i:s');
            if ((string)$user['otp'] === (string)$otp) {
                if ($current_time <= $user['otp_expiry']) {
                    $_SESSION['user_id'] = $user['staff_id'];
                    $_SESSION['role_id'] = $user['role_id'];
                    $_SESSION['email'] = $email;

                    $clearQuery = "UPDATE staff SET otp = NULL, otp_expiry = NULL WHERE staff_email = :email";
                    $clearStmt = $pdo->prepare($clearQuery);
                    $clearStmt->execute([':email' => $email]);

                    echo "success";
                } else {
                    echo "OTP expired!";
                }
            } else {
                echo "Invalid OTP!";
            }
        } else {
            echo "Email not found!";
        }
    } else {
        echo "Invalid request!";
    }
} else {
    echo "Invalid request!";
}
?>