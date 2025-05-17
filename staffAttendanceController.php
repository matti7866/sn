<?php
session_start();
include "connection.php";

// Set timezone to Dubai
date_default_timezone_set('Asia/Dubai');

// Enable error reporting for debugging
ini_set('display_errors', 0); // Disable display errors to prevent corrupting JSON
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Make sure no output before JSON response
ob_start();

// Log the current timezone for debugging
error_log("Current timezone: " . date_default_timezone_get());
error_log("Current Dubai time: " . date('Y-m-d H:i:s'));

// Check if user is admin (only user ID 1 and 12 are allowed to manage attendance)
function isAdmin() {
    return isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 || $_SESSION['user_id'] == 12);
}

// Function to create the table if it doesn't exist
function ensureTableExists($pdo) {
    try {
        // Check if table exists first
        $sql = "SHOW TABLES LIKE 'staff_attendance'";
        $result = $pdo->query($sql);
        
        if ($result->rowCount() == 0) {
            // Table doesn't exist, create it
            $sql = "CREATE TABLE IF NOT EXISTS `staff_attendance` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `staff_id` int(11) NOT NULL,
                `check_in_datetime` datetime NOT NULL,
                `check_in_date` date NOT NULL,
                `check_out_datetime` datetime DEFAULT NULL,
                `break_start_datetime` datetime DEFAULT NULL,
                `break_end_datetime` datetime DEFAULT NULL,
                `break_duration` int(11) DEFAULT 0 COMMENT 'Break duration in minutes (calculated)',
                `notes` text DEFAULT NULL,
                `recorded_by` int(11) NOT NULL COMMENT 'Admin user ID who recorded this',
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `staff_id` (`staff_id`),
                KEY `check_in_date` (`check_in_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            
            $pdo->exec($sql);
            
            return true;
        } else {
            // Check if break start and end columns exist
            $sql = "SHOW COLUMNS FROM `staff_attendance` LIKE 'break_start_datetime'";
            $result = $pdo->query($sql);
            
            if ($result->rowCount() == 0) {
                // Add break columns if they don't exist
                $sql = "ALTER TABLE `staff_attendance` 
                        ADD COLUMN `break_start_datetime` datetime DEFAULT NULL,
                        ADD COLUMN `break_end_datetime` datetime DEFAULT NULL";
                $pdo->exec($sql);
            }
            
            // Check if break_duration column exists
            $sql = "SHOW COLUMNS FROM `staff_attendance` LIKE 'break_duration'";
            $result = $pdo->query($sql);
            
            if ($result->rowCount() == 0) {
                // Add break_duration column if it doesn't exist
                $sql = "ALTER TABLE `staff_attendance` 
                        ADD COLUMN `break_duration` int(11) DEFAULT 0 COMMENT 'Break duration in minutes (calculated)',
                        ADD COLUMN `notes` text DEFAULT NULL,
                        ADD COLUMN `recorded_by` int(11) NOT NULL DEFAULT 1 COMMENT 'Admin user ID who recorded this',
                        ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
                $pdo->exec($sql);
            }
        }
        return true;
    } catch (PDOException $e) {
        // Log the error
        error_log("Error creating/updating table: " . $e->getMessage());
        return false;
    }
}

// Function to calculate break duration in minutes between two datetime values
function calculateBreakDuration($start, $end) {
    if (empty($start) || empty($end)) {
        return 0;
    }
    
    $startTime = new DateTime($start);
    $endTime = new DateTime($end);
    $interval = $startTime->diff($endTime);
    
    // Convert to minutes: hours * 60 + minutes
    return ($interval->h * 60) + $interval->i;
}

// Function to safely output JSON and end the script
function outputJSON($data) {
    // Clear any previous output
    ob_clean();
    
    // Set JSON content type header
    header('Content-Type: application/json');
    
    // Encode with options to handle Unicode correctly
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    // End script execution
    exit;
}

// Debug information - only shown when there's a problem
if (isset($_POST['debug']) && $_POST['debug'] === 'true') {
    // Make sure table exists before debugging
    $tableExists = ensureTableExists($pdo);
    
    outputJSON([
        'success' => false,
        'message' => 'Debug information',
        'session_data' => [
            'has_role_id' => isset($_SESSION['role_id']),
            'has_user_id' => isset($_SESSION['user_id']),
            'has_staff_id' => isset($_SESSION['staff_id']),
            'session_id' => session_id(),
            'session_status' => session_status()
        ],
        'table_exists' => $tableExists
    ]);
}

// Check if user is logged in
if (!isset($_SESSION['role_id'])) {
    outputJSON(['success' => false, 'message' => 'User not logged in']);
}

$current_datetime = date('Y-m-d H:i:s');
$current_date = date('Y-m-d');

// Make sure the table exists before proceeding
if (!ensureTableExists($pdo)) {
    outputJSON([
        'success' => false,
        'message' => 'Database error: Could not create or update staff attendance table'
    ]);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
    
        // Get staff list for dropdown
        if ($action === 'getStaffList') {
            // Only admin can access staff list
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            try {
                $sql = "SELECT staff_id, staff_name FROM staff WHERE staff_id NOT IN (1, 14) ORDER BY staff_name";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                outputJSON([
                    'success' => true,
                    'staff' => $staff
                ]);
            } catch (PDOException $e) {
                error_log("Error getting staff list: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Record staff attendance (admin only)
        elseif ($action === 'recordAttendance') {
            // Only admin can record attendance
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            // Validate required params
            $staff_id = $_POST['staff_id'] ?? '';
            $date = $_POST['date'] ?? '';
            $check_in_time = $_POST['check_in_time'] ?? '';
            $check_out_time = $_POST['check_out_time'] ?? '';
            $break_start_time = $_POST['break_start_time'] ?? '';
            $break_end_time = $_POST['break_end_time'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            if (empty($staff_id) || empty($date) || empty($check_in_time)) {
                outputJSON([
                    'success' => false,
                    'message' => 'Staff ID, date and check-in time are required'
                ]);
            }
            
            // Allow break start time without break end time
            // (removed validation that required both break times together)
            
            try {
                // Log the record attendance operation
                error_log("Recording attendance for staff ID: $staff_id, Date: $date, Check-in: $check_in_time");
                
                // Check if attendance record already exists for this staff member on this date
                $sql = "SELECT id FROM staff_attendance 
                        WHERE staff_id = :staff_id 
                        AND check_in_date = :date";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':staff_id', $staff_id);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    outputJSON([
                        'success' => false,
                        'message' => 'Attendance record already exists for this staff member on this date. Please edit the existing record.'
                    ]);
                }
                
                // Format datetime values with Dubai timezone
                // Create DateTime objects with Dubai timezone for proper time storage
                $dubai_tz = new DateTimeZone('Asia/Dubai');
                
                // Check-in datetime
                $check_in_dt = new DateTime($date . ' ' . $check_in_time, $dubai_tz);
                $check_in_datetime = $check_in_dt->format('Y-m-d H:i:s');
                
                // Check-out datetime (if provided)
                $check_out_datetime = null;
                if (!empty($check_out_time)) {
                    $check_out_dt = new DateTime($date . ' ' . $check_out_time, $dubai_tz);
                    $check_out_datetime = $check_out_dt->format('Y-m-d H:i:s');
                }
                
                // Break start datetime (if provided)
                $break_start_datetime = null;
                if (!empty($break_start_time)) {
                    $break_start_dt = new DateTime($date . ' ' . $break_start_time, $dubai_tz);
                    $break_start_datetime = $break_start_dt->format('Y-m-d H:i:s');
                }
                
                // Break end datetime (if provided)
                $break_end_datetime = null;
                if (!empty($break_end_time)) {
                    $break_end_dt = new DateTime($date . ' ' . $break_end_time, $dubai_tz);
                    $break_end_datetime = $break_end_dt->format('Y-m-d H:i:s');
                }
                
                // Calculate break duration - only if both start and end are provided
                $break_duration = 0;
                if (!empty($break_start_datetime) && !empty($break_end_datetime)) {
                    $break_duration = calculateBreakDuration($break_start_datetime, $break_end_datetime);
                }
                
                // Log the formatted datetimes for debugging
                error_log("Formatted datetimes for insertion:");
                error_log("Check-in datetime: $check_in_datetime");
                error_log("Check-out datetime: " . ($check_out_datetime ?? 'None'));
                error_log("Break start: " . ($break_start_datetime ?? 'None'));
                error_log("Break end: " . ($break_end_datetime ?? 'None'));
                error_log("Break duration: $break_duration minutes");
                
                // Insert new attendance record
                $sql = "INSERT INTO staff_attendance 
                        (staff_id, check_in_datetime, check_in_date, check_out_datetime, 
                         break_start_datetime, break_end_datetime, break_duration, notes, recorded_by) 
                        VALUES (:staff_id, :check_in_datetime, :date, :check_out_datetime, 
                               :break_start_datetime, :break_end_datetime, :break_duration, :notes, :recorded_by)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':staff_id', $staff_id);
                $stmt->bindParam(':check_in_datetime', $check_in_datetime);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':check_out_datetime', $check_out_datetime);
                $stmt->bindParam(':break_start_datetime', $break_start_datetime);
                $stmt->bindParam(':break_end_datetime', $break_end_datetime);
                $stmt->bindParam(':break_duration', $break_duration);
                $stmt->bindParam(':notes', $notes);
                $stmt->bindParam(':recorded_by', $_SESSION['user_id']);
                
                if ($stmt->execute()) {
                    outputJSON([
                        'success' => true,
                        'message' => 'Attendance record created successfully',
                        'debug_info' => [
                            'timezone' => date_default_timezone_get(),
                            'server_time' => date('Y-m-d H:i:s')
                        ]
                    ]);
                } else {
                    outputJSON([
                        'success' => false,
                        'message' => 'Failed to create attendance record'
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Error recording attendance: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Update staff attendance (admin only)
        elseif ($action === 'updateAttendance') {
            // Only admin can update attendance
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            // Validate required params
            $id = $_POST['id'] ?? '';
            $date = $_POST['date'] ?? '';
            $check_in_time = $_POST['check_in_time'] ?? '';
            $check_out_time = $_POST['check_out_time'] ?? '';
            $break_start_time = $_POST['break_start_time'] ?? '';
            $break_end_time = $_POST['break_end_time'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            if (empty($id) || empty($date) || empty($check_in_time)) {
                outputJSON([
                    'success' => false,
                    'message' => 'Attendance ID, date and check-in time are required'
                ]);
            }
            
            try {
                // Log the update attendance operation
                error_log("Updating attendance ID: $id, Date: $date, Check-in: $check_in_time");
                
                // Format datetime values with Dubai timezone
                // Create DateTime objects with Dubai timezone for proper time storage
                $dubai_tz = new DateTimeZone('Asia/Dubai');
                
                // Check-in datetime
                $check_in_dt = new DateTime($date . ' ' . $check_in_time, $dubai_tz);
                $check_in_datetime = $check_in_dt->format('Y-m-d H:i:s');
                
                // Check-out datetime (if provided)
                $check_out_datetime = null;
                if (!empty($check_out_time)) {
                    $check_out_dt = new DateTime($date . ' ' . $check_out_time, $dubai_tz);
                    $check_out_datetime = $check_out_dt->format('Y-m-d H:i:s');
                }
                
                // Break start datetime (if provided)
                $break_start_datetime = null;
                if (!empty($break_start_time)) {
                    $break_start_dt = new DateTime($date . ' ' . $break_start_time, $dubai_tz);
                    $break_start_datetime = $break_start_dt->format('Y-m-d H:i:s');
                }
                
                // Break end datetime (if provided)
                $break_end_datetime = null;
                if (!empty($break_end_time)) {
                    $break_end_dt = new DateTime($date . ' ' . $break_end_time, $dubai_tz);
                    $break_end_datetime = $break_end_dt->format('Y-m-d H:i:s');
                }
                
                // Calculate break duration
                $break_duration = calculateBreakDuration($break_start_datetime, $break_end_datetime);
                
                // Log the formatted datetimes for debugging
                error_log("Formatted datetimes for update:");
                error_log("Check-in datetime: $check_in_datetime");
                error_log("Check-out datetime: " . ($check_out_datetime ?? 'None'));
                error_log("Break start: " . ($break_start_datetime ?? 'None'));
                error_log("Break end: " . ($break_end_datetime ?? 'None'));
                error_log("Break duration: $break_duration minutes");
                
                // Update attendance record
                $sql = "UPDATE staff_attendance 
                        SET check_in_datetime = :check_in_datetime, 
                            check_in_date = :date, 
                            check_out_datetime = :check_out_datetime,
                            break_start_datetime = :break_start_datetime,
                            break_end_datetime = :break_end_datetime,
                            break_duration = :break_duration,
                            notes = :notes 
                        WHERE id = :id";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':check_in_datetime', $check_in_datetime);
                $stmt->bindParam(':date', $date);
                $stmt->bindParam(':check_out_datetime', $check_out_datetime);
                $stmt->bindParam(':break_start_datetime', $break_start_datetime);
                $stmt->bindParam(':break_end_datetime', $break_end_datetime);
                $stmt->bindParam(':break_duration', $break_duration);
                $stmt->bindParam(':notes', $notes);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    outputJSON([
                        'success' => true,
                        'message' => 'Attendance record updated successfully',
                        'debug_info' => [
                            'timezone' => date_default_timezone_get(),
                            'server_time' => date('Y-m-d H:i:s')
                        ]
                    ]);
                } else {
                    outputJSON([
                        'success' => false,
                        'message' => 'Failed to update attendance record'
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Error updating attendance: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Delete staff attendance (admin only)
        elseif ($action === 'deleteAttendance') {
            // Only admin can delete attendance
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                outputJSON([
                    'success' => false,
                    'message' => 'Attendance ID is required'
                ]);
            }
            
            try {
                $sql = "DELETE FROM staff_attendance WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id);
                
                if ($stmt->execute()) {
                    outputJSON([
                        'success' => true,
                        'message' => 'Attendance record deleted successfully'
                    ]);
                } else {
                    outputJSON([
                        'success' => false,
                        'message' => 'Failed to delete attendance record'
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Error deleting attendance: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Get individual attendance record (admin only)
        elseif ($action === 'getAttendanceRecord') {
            // Only admin can view attendance details
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            $id = $_POST['id'] ?? '';
            
            if (empty($id)) {
                outputJSON([
                    'success' => false,
                    'message' => 'Attendance ID is required'
                ]);
            }
            
            try {
                $sql = "SELECT a.*, s.staff_name 
                        FROM staff_attendance a
                        JOIN staff s ON a.staff_id = s.staff_id
                        WHERE a.id = :id";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $record = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Format date and times for the form
                    $record['date'] = date('Y-m-d', strtotime($record['check_in_datetime']));
                    $record['check_in_time'] = date('H:i', strtotime($record['check_in_datetime']));
                    
                    if (!empty($record['check_out_datetime'])) {
                        $record['check_out_time'] = date('H:i', strtotime($record['check_out_datetime']));
                    } else {
                        $record['check_out_time'] = '';
                    }
                    
                    if (!empty($record['break_start_datetime'])) {
                        $record['break_start_time'] = date('H:i', strtotime($record['break_start_datetime']));
                    } else {
                        $record['break_start_time'] = '';
                    }
                    
                    if (!empty($record['break_end_datetime'])) {
                        $record['break_end_time'] = date('H:i', strtotime($record['break_end_datetime']));
                    } else {
                        $record['break_end_time'] = '';
                    }
                    
                    outputJSON([
                        'success' => true,
                        'record' => $record
                    ]);
                } else {
                    outputJSON([
                        'success' => false,
                        'message' => 'Attendance record not found'
                    ]);
                }
            } catch (PDOException $e) {
                error_log("Error getting attendance record: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Handle attendance report (only for admin)
        elseif ($action === 'getAttendance') {
            // Check if user is authorized (only admin)
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            $date = $_POST['date'] ?? $current_date;
            
            try {
                // First get all staff from staff table (excluding IDs 1 and 14)
                $sql = "SELECT staff_id, staff_name FROM staff WHERE staff_id NOT IN (1, 14) ORDER BY staff_name";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $allStaff = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Get all attendance records for the specified date (excluding IDs 1 and 14)
                $sql = "SELECT a.*, s.staff_name 
                        FROM staff_attendance a
                        JOIN staff s ON a.staff_id = s.staff_id
                        WHERE a.check_in_date = :date
                        AND a.staff_id NOT IN (1, 14)";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':date', $date);
                $stmt->execute();
                $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Process records for display
                $staffAttendance = [];
                $staffWithAttendance = [];
                
                foreach ($attendanceRecords as $record) {
                    $staffWithAttendance[] = $record['staff_id'];
                    
                    // Format times
                    $checkInTime = !empty($record['check_in_datetime']) ? 
                        date('h:i A', strtotime($record['check_in_datetime'])) : null;
                    
                    $checkOutTime = !empty($record['check_out_datetime']) ? 
                        date('h:i A', strtotime($record['check_out_datetime'])) : null;
                    
                    $breakStartTime = !empty($record['break_start_datetime']) ? 
                        date('h:i A', strtotime($record['break_start_datetime'])) : null;
                    
                    $breakEndTime = !empty($record['break_end_datetime']) ? 
                        date('h:i A', strtotime($record['break_end_datetime'])) : null;
                    
                    $staffAttendance[] = [
                        'id' => $record['id'],
                        'staff_id' => $record['staff_id'],
                        'staff_name' => $record['staff_name'],
                        'check_in_time' => $checkInTime,
                        'check_out_time' => $checkOutTime,
                        'break_start_time' => $breakStartTime,
                        'break_end_time' => $breakEndTime,
                        'break_duration' => $record['break_duration'] ?? 0,
                        'notes' => $record['notes'] ?? ''
                    ];
                }
                
                // Add staff with no attendance records
                foreach ($allStaff as $staff) {
                    if (!in_array($staff['staff_id'], $staffWithAttendance)) {
                        $staffAttendance[] = [
                            'id' => 0, // No record ID
                            'staff_id' => $staff['staff_id'],
                            'staff_name' => $staff['staff_name'],
                            'check_in_time' => null,
                            'check_out_time' => null,
                            'break_start_time' => null,
                            'break_end_time' => null,
                            'break_duration' => 0,
                            'notes' => ''
                        ];
                    }
                }
                
                // Sort by staff name
                usort($staffAttendance, function($a, $b) {
                    return strcmp($a['staff_name'], $b['staff_name']);
                });
                
                outputJSON([
                    'success' => true,
                    'attendance' => $staffAttendance,
                    'date' => $date
                ]);
                
            } catch (PDOException $e) {
                error_log("Error getting attendance data: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Handle weekly attendance report (only for admin)
        elseif ($action === 'getWeeklyReport') {
            // Check if user is authorized (only admin)
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            $year = $_POST['year'] ?? date('Y');
            $week = $_POST['week'] ?? date('W');
            
            try {
                // Calculate the start and end date of the week
                $dto = new DateTime();
                $dto->setISODate($year, $week);
                $startDate = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $endDate = $dto->format('Y-m-d');
                
                // Get all dates in the week
                $dates = [];
                $currentDate = new DateTime($startDate);
                $lastDate = new DateTime($endDate);
                
                while ($currentDate <= $lastDate) {
                    $dates[] = $currentDate->format('Y-m-d');
                    $currentDate->modify('+1 day');
                }
                
                // First get all staff from staff table (excluding IDs 1 and 14)
                $sql = "SELECT staff_id, staff_name FROM staff WHERE staff_id NOT IN (1, 14) ORDER BY staff_name";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $allStaff = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Get all attendance records for the specified week (excluding IDs 1 and 14)
                $sql = "SELECT a.*, s.staff_name 
                        FROM staff_attendance a
                        JOIN staff s ON a.staff_id = s.staff_id
                        WHERE a.check_in_date BETWEEN :start_date AND :end_date
                        AND a.staff_id NOT IN (1, 14)
                        ORDER BY a.staff_id, a.check_in_date";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);
                $stmt->execute();
                $weekAttendance = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Process records for weekly report
                $weeklyData = [];
                $staffMap = [];
                
                // Initialize staff data with empty days
                foreach ($allStaff as $staff) {
                    $staffData = [
                        'staff_id' => $staff['staff_id'],
                        'staff_name' => $staff['staff_name'],
                        'days' => [],
                        'total_hours' => 0
                    ];
                    
                    $weeklyData[] = $staffData;
                    $staffMap[$staff['staff_id']] = count($weeklyData) - 1;
                }
                
                // Process attendance records
                foreach ($weekAttendance as $record) {
                    $staffId = $record['staff_id'];
                    $date = $record['check_in_date'];
                    $staffIndex = $staffMap[$staffId];
                    
                    // Calculate hours for this day
                    $hours = 0;
                    
                    if (!empty($record['check_in_datetime']) && !empty($record['check_out_datetime'])) {
                        $checkIn = new DateTime($record['check_in_datetime']);
                        $checkOut = new DateTime($record['check_out_datetime']);
                        
                        // Calculate duration in hours, accounting for break time
                        $diff = $checkOut->getTimestamp() - $checkIn->getTimestamp();
                        $breakDuration = $record['break_duration'] ?? 0;
                        $diff -= ($breakDuration * 60); // Convert break minutes to seconds
                        
                        $hours = round($diff / 3600, 1); // Convert seconds to hours with 1 decimal place
                    }
                    
                    // Store day data
                    $weeklyData[$staffIndex]['days'][$date] = [
                        'hours' => $hours,
                        'status' => ($hours > 0) ? 'present' : 'absent'
                    ];
                    
                    // Add to total hours
                    $weeklyData[$staffIndex]['total_hours'] += $hours;
                }
                
                // Round total hours to 1 decimal place for display
                foreach ($weeklyData as &$staff) {
                    $staff['total_hours'] = round($staff['total_hours'], 1);
                }
                
                outputJSON([
                    'success' => true,
                    'weeklyData' => $weeklyData,
                    'dates' => $dates,
                    'date_range' => [
                        'start' => $startDate,
                        'end' => $endDate
                    ]
                ]);
                
            } catch (PDOException $e) {
                error_log("Error getting weekly attendance data: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            } catch (Exception $e) {
                error_log("Error processing weekly report: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Handle monthly attendance report (only for admin)
        elseif ($action === 'getMonthlyReport') {
            // Check if user is authorized (only admin)
            if (!isAdmin()) {
                outputJSON([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }
            
            $year = $_POST['year'] ?? date('Y');
            $month = $_POST['month'] ?? date('m');
            
            try {
                // Calculate the start and end date of the month
                $startDate = $year . '-' . $month . '-01';
                $lastDay = date('t', strtotime($startDate)); // Get number of days in month
                $endDate = $year . '-' . $month . '-' . $lastDay;
                
                // First get all staff from staff table (excluding IDs 1 and 14)
                $sql = "SELECT staff_id, staff_name FROM staff WHERE staff_id NOT IN (1, 14) ORDER BY staff_name";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $allStaff = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Prepare monthly report data
                $monthlyData = [];
                
                foreach ($allStaff as $staff) {
                    // Count days present for this staff member
                    $sql = "SELECT COUNT(DISTINCT check_in_date) as days_present 
                            FROM staff_attendance 
                            WHERE staff_id = :staff_id 
                            AND check_in_date BETWEEN :start_date AND :end_date";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $staff['staff_id']);
                    $stmt->bindParam(':start_date', $startDate);
                    $stmt->bindParam(':end_date', $endDate);
                    $stmt->execute();
                    $daysPresent = $stmt->fetch(PDO::FETCH_ASSOC)['days_present'] ?? 0;
                    
                    // Calculate total work hours and break hours
                    $sql = "SELECT a.* FROM staff_attendance a 
                            WHERE a.staff_id = :staff_id 
                            AND a.check_in_date BETWEEN :start_date AND :end_date";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':staff_id', $staff['staff_id']);
                    $stmt->bindParam(':start_date', $startDate);
                    $stmt->bindParam(':end_date', $endDate);
                    $stmt->execute();
                    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $totalMinutes = 0;
                    $totalBreakMinutes = 0;
                    
                    foreach ($records as $record) {
                        // Only count hours if both check-in and check-out are recorded
                        if (!empty($record['check_in_datetime']) && !empty($record['check_out_datetime'])) {
                            $checkIn = new DateTime($record['check_in_datetime']);
                            $checkOut = new DateTime($record['check_out_datetime']);
                            
                            // Calculate total minutes worked
                            $diff = $checkOut->getTimestamp() - $checkIn->getTimestamp();
                            $minutes = $diff / 60; // Convert seconds to minutes
                            
                            $totalMinutes += $minutes;
                            
                            // Add break duration
                            $breakDuration = $record['break_duration'] ?? 0;
                            $totalBreakMinutes += $breakDuration;
                        }
                    }
                    
                    // Convert minutes to hours
                    $totalHours = round($totalMinutes / 60, 1);
                    $breakHours = round($totalBreakMinutes / 60, 1);
                    $netHours = round(($totalMinutes - $totalBreakMinutes) / 60, 1);
                    
                    // Calculate daily average (if days present > 0)
                    $dailyAverage = $daysPresent > 0 ? round($netHours / $daysPresent, 1) : 0;
                    
                    // Add staff data to report
                    $monthlyData[] = [
                        'staff_id' => $staff['staff_id'],
                        'staff_name' => $staff['staff_name'],
                        'days_present' => $daysPresent,
                        'total_hours' => $totalHours,
                        'break_hours' => $breakHours,
                        'net_hours' => $netHours,
                        'daily_average' => $dailyAverage
                    ];
                }
                
                outputJSON([
                    'success' => true,
                    'monthlyData' => $monthlyData,
                    'date_range' => [
                        'year' => $year,
                        'month' => $month,
                        'start' => $startDate,
                        'end' => $endDate
                    ]
                ]);
                
            } catch (PDOException $e) {
                error_log("Error getting monthly attendance data: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
            } catch (Exception $e) {
                error_log("Error processing monthly report: " . $e->getMessage());
                outputJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        }
        
        // Handle getting only the current user's attendance
        elseif ($action === 'getMyAttendance') {
            getMyAttendance($pdo);
        } else {
            // Unknown action
            outputJSON([
                'success' => false,
                'message' => 'Unknown action: ' . $action
            ]);
        }
    } catch (Exception $e) {
        // Catch any other errors
        error_log("Unexpected error: " . $e->getMessage());
        outputJSON([
            'success' => false,
            'message' => 'Unexpected error: ' . $e->getMessage()
        ]);
    }
}

// If we got here, no valid action was processed
outputJSON([
    'success' => false,
    'message' => 'No valid action specified'
]);

// Function to format date/time with Dubai timezone
function formatDubaiTime($dateTime, $format = 'Y-m-d H:i:s') {
    if (empty($dateTime)) {
        return null;
    }
    
    $date = new DateTime($dateTime, new DateTimeZone('Asia/Dubai'));
    return $date->format($format);
}

// Function to get only the current user's attendance
function getMyAttendance($pdo) {
    try {
        // Log the function call for debugging
        error_log("getMyAttendance called, timezone: " . date_default_timezone_get());
        
        // Get current user ID from session
        $staffId = $_SESSION['user_id'];
        $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d');
        
        error_log("Current user ID: $staffId, Date: $date");

        // Get the attendance record for the current user on the specified date
        $sql = "SELECT a.*, s.staff_name 
                FROM staff_attendance a 
                JOIN staff s ON a.staff_id = s.staff_id 
                WHERE a.staff_id = :staff_id AND a.check_in_date = :date";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':staff_id', $staffId, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Log the query result for debugging
        error_log("Query result: " . ($record ? json_encode($record) : "No record found"));
        
        // Format times for display
        if ($record) {
            // Format check-in time using Dubai timezone
            if (!empty($record['check_in_datetime'])) {
                $record['check_in_time'] = formatDubaiTime($record['check_in_datetime'], 'H:i');
                error_log("Formatted check-in time: " . $record['check_in_time']);
            }
            
            // Format check-out time
            if (!empty($record['check_out_datetime'])) {
                $record['check_out_time'] = formatDubaiTime($record['check_out_datetime'], 'H:i');
                error_log("Formatted check-out time: " . $record['check_out_time']);
            }
            
            // Format break times
            if (!empty($record['break_start_datetime'])) {
                $record['break_start_time'] = formatDubaiTime($record['break_start_datetime'], 'H:i');
            }
            
            if (!empty($record['break_end_datetime'])) {
                $record['break_end_time'] = formatDubaiTime($record['break_end_datetime'], 'H:i');
            }
            
            // Return formatted data
            outputJSON([
                'success' => true, 
                'attendance' => $record,
                'debug_info' => [
                    'timezone' => date_default_timezone_get(),
                    'server_time' => date('Y-m-d H:i:s')
                ]
            ]);
        } else {
            // Try to get staff name at least
            $sql = "SELECT staff_name FROM staff WHERE staff_id = :staff_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staff_id', $staffId, PDO::PARAM_INT);
            $stmt->execute();
            $staffName = $stmt->fetch(PDO::FETCH_COLUMN);
            
            error_log("No attendance record found. Staff name: $staffName");
            
            // Return empty record with staff name
            outputJSON([
                'success' => true,
                'attendance' => [
                    'staff_id' => $staffId,
                    'staff_name' => $staffName,
                    'check_in_time' => null,
                    'check_out_time' => null,
                    'break_duration' => 0
                ],
                'debug_info' => [
                    'timezone' => date_default_timezone_get(),
                    'server_time' => date('Y-m-d H:i:s'),
                    'message' => 'No attendance record found for today'
                ]
            ]);
        }
    } catch (PDOException $e) {
        error_log("Error getting user attendance: " . $e->getMessage());
        outputJSON([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'debug_info' => [
                'timezone' => date_default_timezone_get(),
                'server_time' => date('Y-m-d H:i:s')
            ]
        ]);
    }
}
?> 