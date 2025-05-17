<?php
// Database update script

// Include database connection
require_once 'connection.php';

try {
    echo "Starting database update...<br>";
    
    // Check if status column exists
    $checkQuery = "SELECT COUNT(*) AS column_exists 
                  FROM INFORMATION_SCHEMA.COLUMNS 
                  WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'tasheel_transactions' 
                  AND COLUMN_NAME = 'status'";
    
    $stmt = $pdo->query($checkQuery);
    $columnExists = $stmt->fetchColumn();
    
    if ($columnExists == 0) {
        // Add status column if it doesn't exist
        echo "Adding 'status' column to tasheel_transactions table...<br>";
        $alterQuery = "ALTER TABLE tasheel_transactions ADD COLUMN status VARCHAR(20) DEFAULT 'in_process'";
        $pdo->exec($alterQuery);
        echo "Column added successfully.<br>";
        
        // Update existing records
        echo "Updating existing records...<br>";
        $updateQuery = "UPDATE tasheel_transactions SET status = 'in_process' WHERE status IS NULL";
        $rowsUpdated = $pdo->exec($updateQuery);
        echo "$rowsUpdated records updated.<br>";
    } else {
        echo "Status column already exists in the tasheel_transactions table.<br>";
    }
    
    echo "Database update completed successfully!";
} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?> 