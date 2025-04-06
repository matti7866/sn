<?php
session_start();
include 'connection.php';

function debugLog($message) {
    echo "[DEBUG] " . $message . "<br>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];
    debugLog("Verification attempt - Email: $email, OTP: $otp");

    $query = "SELECT staff_id, role_id, otp, otp_expiry FROM staff WHERE staff_email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        debugLog("User found. Stored OTP: " . $user['otp'] . ", Expiry: " . $user['otp_expiry']);
        $current_time = date('Y-m-d H:i:s');

        if ((string)$user['otp'] === (string)$otp) {
            if ($current_time <= $user['otp_expiry']) {
                debugLog("OTP is valid. Logging in...");
                $_SESSION['user_id'] = $user['staff_id'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['email'] = $email;

                $clearQuery = "UPDATE staff SET otp = NULL, otp_expiry = NULL WHERE staff_email = :email";
                $clearStmt = $pdo->prepare($clearQuery);
                $clearStmt->execute([':email' => $email]);
                debugLog("OTP cleared.");

                echo "Login successful!";
            } else {
                debugLog("OTP expired! Current: $current_time, Expiry: " . $user['otp_expiry']);
                echo "OTP expired!";
            }
        } else {
            debugLog("Invalid OTP! Stored: " . $user['otp'] . ", Entered: $otp");
            echo "Invalid OTP!";
        }
    } else {
        debugLog("Email not found or no OTP set!");
        echo "Email not found!";
    }
} else {
    echo "Invalid request!";
}
?>