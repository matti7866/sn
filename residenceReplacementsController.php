<?php
require_once 'connection.php';

// Check for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mark residence replacement as complete
    if (isset($_POST['action']) && $_POST['action'] === 'markAsComplete') {
        try {
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            
            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid residence ID']);
                exit;
            }

            // First check if the record exists and has the right status
            $checkStmt = $pdo->prepare("SELECT residenceID FROM residence WHERE residenceID = ? AND current_status = 'replaced' AND (replacement_status IS NULL OR replacement_status = 'in_process')");
            $checkStmt->execute([$id]);
            
            if ($checkStmt->rowCount() === 0) {
                echo json_encode(['status' => 'error', 'message' => 'Residence not found or already completed']);
                exit;
            }

            // Update the record status
            $updateStmt = $pdo->prepare("UPDATE residence SET 
                replacement_status = 'completed',
                replacement_completed_date = NOW(),
                replacement_completed_by = :user_id
                WHERE residenceID = :id");
                
            $updateStmt->bindParam(':id', $id);
            $updateStmt->bindParam(':user_id', $_SESSION['user_id']);
            $updateStmt->execute();
            
            echo json_encode(['status' => 'success', 'message' => 'Replacement marked as completed']);
            
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Default response for invalid requests
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
?> 