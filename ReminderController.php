<?php
session_start();
date_default_timezone_set('Asia/Dubai'); // Set to UTC+4

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';

$sql = "SELECT permission.select, permission.update, permission.delete, permission.insert 
        FROM `permission` 
        WHERE role_id = :role_id AND page_name = 'Reminder'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if ($select == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
}

if (isset($_POST['INSERT'])) {
    try {
        if ($insert == 1) {
            // Match the new table structure
            $sql = "INSERT INTO `reminder` (`reminder_subject`, `reminder_description`, `reminder_datetime`, `reminderSetBy`, `status`)
                    VALUES (:reminder_subject, :reminder_description, :reminder_datetime, :reminderSetBy, 'pending')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':reminder_subject', $_POST['Reminder_Subject']);
            $stmt->bindParam(':reminder_description', $_POST['Reminder_Description']);
            $stmt->bindParam(':reminder_datetime', $_POST['Reminder_Datetime']);
            $stmt->bindParam(':reminderSetBy', $_SESSION['user_id']);
            $stmt->execute();
            echo "Success";
        } else {
            echo "<script>window.location.href='pageNotFound.php'</script>";
        }
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} elseif (isset($_POST['GetReminders'])) {
    if ($_POST['Start'] == 1) {
        $selectQuery = $pdo->prepare("SELECT *, (SELECT IFNULL(COUNT(reminder_id),0) FROM reminder WHERE reminderSetBy = :reminderSetBy) AS TotalRecord 
                                      FROM `reminder` 
                                      WHERE reminderSetBy = :reminderSetBy 
                                      ORDER BY reminder_id DESC 
                                      LIMIT " . $_POST['End']);
        $selectQuery->bindParam(':reminderSetBy', $_SESSION['user_id']);
    } else {
        $selectQuery = $pdo->prepare("SELECT *, (SELECT IFNULL(COUNT(reminder_id),0) FROM reminder WHERE reminderSetBy = :reminderSetBy) AS TotalRecord 
                                      FROM `reminder` 
                                      WHERE reminderSetBy = :reminderSetBy 
                                      ORDER BY reminder_id DESC 
                                      LIMIT " . $_POST['Start'] . "," . $_POST['End']);
        $selectQuery->bindParam(':reminderSetBy', $_SESSION['user_id']);
    }
    $selectQuery->execute();
    $reminders = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($reminders);
} elseif (isset($_POST['Delete'])) {
    try {
        if ($delete == 1) {
            $sql = "DELETE FROM reminder WHERE reminder_id = :reminder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':reminder_id', $_POST['ID']);
            $stmt->execute();
            echo "Success";
        } else {
            echo "Permission denied";
        }
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
} elseif (isset($_POST['EditReminder'])) {
    $selectQuery = $pdo->prepare("SELECT * FROM reminder WHERE reminder_id = :reminder_id");
    $selectQuery->bindParam(':reminder_id', $_POST['ID']);
    $selectQuery->execute();
    $data = $selectQuery->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} elseif (isset($_POST['SaveUpdate'])) {
    try {
        if ($update == 1) {
            $sql = "UPDATE `reminder` 
                    SET reminder_subject = :reminder_subject, 
                        reminder_description = :reminder_description, 
                        reminder_datetime = :reminder_datetime, 
                        status = 'pending' 
                    WHERE reminder_id = :reminder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':reminder_subject', $_POST['Updreminder_Subject']);
            $stmt->bindParam(':reminder_description', $_POST['Updreminder_Description']);
            $stmt->bindParam(':reminder_datetime', $_POST['Updreminder_Datetime']);
            $stmt->bindParam(':reminder_id', $_POST['ReminderID']);
            $stmt->execute();
            echo "Success";
        } else {
            echo "Permission denied";
        }
    } catch (PDOException $e) {
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
}

unset($pdo);
?>