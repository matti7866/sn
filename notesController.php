<?php
session_start();
require_once 'connection.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the action parameter from the request
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Handle different actions
switch ($action) {
    case 'save':
        saveNotes($pdo);
        break;
    case 'load':
        loadNotes($pdo);
        break;
    case 'getCategories':
        getCategories($pdo);
        break;
    case 'addCategory':
        addCategory($pdo);
        break;
    default:
        // Invalid action
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

/**
 * Save notes to the database
 */
function saveNotes($pdo) {
    // Get the request body
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);
    
    // Check if notes data is provided
    if (!isset($data['notes'])) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Notes data not provided']);
        exit;
    }
    
    $notes = $data['notes'];
    $userId = $_SESSION['user_id'];
    $categoryId = isset($data['category_id']) ? $data['category_id'] : 1; // Default to category 1 if not specified
    
    try {
        // Check if notes already exist for this user and category
        $stmt = $pdo->prepare("SELECT id FROM user_notes WHERE user_id = :user_id AND category_id = :category_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update existing notes
            $noteData = $stmt->fetch(PDO::FETCH_ASSOC);
            $noteId = $noteData['id'];
            
            $updateStmt = $pdo->prepare("UPDATE user_notes SET content = :content, updated_at = NOW() WHERE id = :id");
            $updateStmt->bindParam(':content', $notes);
            $updateStmt->bindParam(':id', $noteId);
            $updateStmt->execute();
        } else {
            // Insert new notes
            $insertStmt = $pdo->prepare("INSERT INTO user_notes (user_id, category_id, content, created_at, updated_at) VALUES (:user_id, :category_id, :content, NOW(), NOW())");
            $insertStmt->bindParam(':user_id', $userId);
            $insertStmt->bindParam(':category_id', $categoryId);
            $insertStmt->bindParam(':content', $notes);
            $insertStmt->execute();
        }
        
        // Return success response
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Load notes from the database
 */
function loadNotes($pdo) {
    $userId = $_SESSION['user_id'];
    $categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : 1; // Default to category 1 if not specified
    
    try {
        // Get notes for the current user and category
        $stmt = $pdo->prepare("SELECT content FROM user_notes WHERE user_id = :user_id AND category_id = :category_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Return notes
            $noteData = $stmt->fetch(PDO::FETCH_ASSOC);
            $notes = $noteData['content'];
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'notes' => $notes]);
        } else {
            // No notes found
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'notes' => '']);
        }
    } catch (PDOException $e) {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Get all categories for a user
 */
function getCategories($pdo) {
    $userId = $_SESSION['user_id'];
    
    try {
        // First, check if we need to create default category for this user
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM note_categories WHERE user_id = :user_id");
        $checkStmt->bindParam(':user_id', $userId);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() == 0) {
            // Create default category
            $insertStmt = $pdo->prepare("INSERT INTO note_categories (user_id, name, created_at) VALUES (:user_id, 'General', NOW())");
            $insertStmt->bindParam(':user_id', $userId);
            $insertStmt->execute();
        }
        
        // Get categories for the current user
        $stmt = $pdo->prepare("SELECT id, name FROM note_categories WHERE user_id = :user_id ORDER BY name");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'categories' => $categories]);
    } catch (PDOException $e) {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

/**
 * Add a new category
 */
function addCategory($pdo) {
    // Get the request body
    $requestBody = file_get_contents('php://input');
    $data = json_decode($requestBody, true);
    
    // Check if category name is provided
    if (!isset($data['name']) || empty(trim($data['name']))) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Category name not provided']);
        exit;
    }
    
    $categoryName = trim($data['name']);
    $userId = $_SESSION['user_id'];
    
    try {
        // Check if category already exists for this user
        $checkStmt = $pdo->prepare("SELECT id FROM note_categories WHERE user_id = :user_id AND name = :name");
        $checkStmt->bindParam(':user_id', $userId);
        $checkStmt->bindParam(':name', $categoryName);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Category already exists']);
            exit;
        }
        
        // Insert new category
        $insertStmt = $pdo->prepare("INSERT INTO note_categories (user_id, name, created_at) VALUES (:user_id, :name, NOW())");
        $insertStmt->bindParam(':user_id', $userId);
        $insertStmt->bindParam(':name', $categoryName);
        $insertStmt->execute();
        
        $newCategoryId = $pdo->lastInsertId();
        
        // Return success response with new category ID
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'category' => ['id' => $newCategoryId, 'name' => $categoryName]]);
    } catch (PDOException $e) {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?> 