<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'connection.php';

// Permission check
$PermissionSQL = "SELECT permission.select FROM `permission` WHERE role_id = :role_id AND page_name = 'Residence'";
$PermissionStmt = $pdo->prepare($PermissionSQL);
$PermissionStmt->bindParam(':role_id', $_SESSION['role_id']);
$PermissionStmt->execute();
$records = $PermissionStmt->fetchAll(PDO::FETCH_ASSOC);
$select = $records[0]['select'];

if ($select == 0) {
    header('Location: pageNotFound.php');
    exit;
}

if ($select == 1 && isset($_GET['file'])) {
    $file_path = $_GET['file'];

    // Retrieve file details from residencedocuments
    $fileExists = "SELECT file_name, original_name FROM residencedocuments WHERE file_name = :file_name";
    $fileExistsStmt = $pdo->prepare($fileExists);
    $fileExistsStmt->bindParam(':file_name', $file_path);
    $fileExistsStmt->execute();
    $fileInDatabase = $fileExistsStmt->fetchAll(PDO::FETCH_ASSOC);

    if ($fileInDatabase && $fileInDatabase[0]['file_name']) {
        $base_dir = '/www/wwwroot/sntravels/uploads/'; // Adjust to your upload directory
        $full_path = realpath($base_dir . $fileInDatabase[0]['file_name']);

        if ($full_path && file_exists($full_path)) {
            // Define headers
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"" . $fileInDatabase[0]['original_name'] . "\"");
            header("Content-Type: application/octet-stream");
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($full_path));

            // Read file
            readfile($full_path);
            exit;
        } else {
            file_put_contents('/www/wwwlogs/passenger_status.log', date('Y-m-d H:i:s') . " - File not found: $full_path\n", FILE_APPEND);
            echo "File not found.";
            exit;
        }
    } else {
        file_put_contents('/www/wwwlogs/passenger_status.log', date('Y-m-d H:i:s') . " - File not in database: $file_path\n", FILE_APPEND);
        echo "Something went wrong.";
        exit;
    }
}

// Close connection
unset($pdo);
?>