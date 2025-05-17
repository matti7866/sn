<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    // Log errors to a file
    ini_set('log_errors', 1);
    ini_set('error_log', 'attachment_errors.log');
    
    // Continue with the rest of the code
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
        exit;
    }
    
    include 'connection.php';
    
    // Set all permissions to allowed (bypass permission system)
    $select = 1;
    $insert = 1;
    $update = 1;
    $delete = 1;
    
    // Create attachments directory if it doesn't exist
    $attachments_dir = 'attachments';
    if (!file_exists($attachments_dir)) {
        try {
            if (!mkdir($attachments_dir, 0777, true)) {
                error_log("Failed to create directory: " . $attachments_dir);
                // Continue anyway as the directory might be created manually
            } else {
                // Successfully created directory, set permissions
                chmod($attachments_dir, 0777);
            }
        } catch (Exception $e) {
            error_log("Exception when creating directory: " . $e->getMessage());
            // Continue anyway as the directory might be created manually
        }
    }
    
    // Handle folder creation
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_folder']) && $insert == 1) {
        try {
            $folder_name = trim($_POST['folder_name']);
            
            // Validate folder name (only allow alphanumeric, spaces, underscores, and hyphens)
            if (!preg_match('/^[a-zA-Z0-9 _-]+$/', $folder_name)) {
                throw new Exception('Folder name contains invalid characters. Use only letters, numbers, spaces, hyphens, and underscores.');
            }
            
            // Create folder in database
            $sql = "INSERT INTO attachment_folders (folder_name, staff_id, parent_id) VALUES (:folder_name, :staff_id, :parent_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_name', $folder_name);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            
            // Handle parent folder if specified
            $parent_id = isset($_POST['parent_id']) && !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;
            $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
            
            $stmt->execute();
            $folder_id = $pdo->lastInsertId();
            
            // Create the physical folder (optional, as we're using database to track)
            $folder_path = $attachments_dir . '/' . $folder_id;
            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
            }
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'folder_id' => $folder_id,
                'folder_name' => $folder_name
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Folder creation failed: ' . $e->getMessage();
            exit;
        }
    }
    
    // Handle getting folders
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_folders']) && $select == 1) {
        try {
            // Get only folders that the user created, folders shared with the user, or all folders for admins
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            
            // Get parent folder if specified
            $parent_id = isset($_GET['parent_id']) && !empty($_GET['parent_id']) ? $_GET['parent_id'] : null;
            
            // First check if parent folder is accessible if specified
            if ($parent_id && !$isAdmin) {
                $accessCheck = "SELECT 1 FROM attachment_folders f
                               LEFT JOIN folder_shares fs ON f.folder_id = fs.folder_id AND fs.staff_id = :check_staff_id
                               WHERE f.folder_id = :check_folder_id
                               AND (f.staff_id = :check_staff_id OR fs.share_id IS NOT NULL)";
                $checkStmt = $pdo->prepare($accessCheck);
                $checkStmt->bindParam(':check_folder_id', $parent_id);
                $checkStmt->bindParam(':check_staff_id', $_SESSION['user_id']);
                $checkStmt->execute();
                
                if ($checkStmt->rowCount() === 0) {
                    // User doesn't have access to this parent folder
                    header('Content-Type: application/json');
                    echo json_encode([]);
                    exit;
                }
            }
            
            if ($isAdmin) {
                // Admins can see all folders
                $sql = "SELECT f.*, f.staff_id, s.staff_name as created_by, 
                       (SELECT COUNT(*) > 0 FROM folder_shares WHERE folder_id = f.folder_id) as is_shared
                       FROM attachment_folders f 
                       JOIN staff s ON f.staff_id = s.staff_id 
                       WHERE f.parent_id " . ($parent_id ? " = :parent_id" : " IS NULL") . "
                       ORDER BY f.folder_name ASC";
                
                $stmt = $pdo->prepare($sql);
                if ($parent_id) {
                    $stmt->bindParam(':parent_id', $parent_id);
                }
            } else {
                // Regular users only see their own folders and folders shared with them
                $sql = "SELECT f.*, f.staff_id, s.staff_name as created_by,
                       (SELECT COUNT(*) > 0 FROM folder_shares WHERE folder_id = f.folder_id) as is_shared
                       FROM attachment_folders f 
                       JOIN staff s ON f.staff_id = s.staff_id 
                       WHERE f.parent_id " . ($parent_id ? " = :parent_id" : " IS NULL") . "
                       AND (
                           f.staff_id = :staff_id 
                           OR f.folder_id IN (SELECT folder_id FROM folder_shares WHERE staff_id = :staff_id)
                       )
                       ORDER BY f.folder_name ASC";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                if ($parent_id) {
                    $stmt->bindParam(':parent_id', $parent_id);
                }
            }
            
            $stmt->execute();
            $folders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Return folders as JSON
            header('Content-Type: application/json');
            echo json_encode($folders);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode([
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }
    
    // Handle folder deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_folder']) && $delete == 1) {
        try {
            $folder_id = $_POST['delete_folder'];
            
            // Check ownership or admin privilege
            $sql = "SELECT * FROM attachment_folders WHERE folder_id = :folder_id AND (staff_id = :staff_id OR :is_admin = 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Folder not found or you do not have permission to delete it');
            }
            
            // Check if folder has files
            $sql = "SELECT COUNT(*) FROM attachments WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            $file_count = $stmt->fetchColumn();
            
            if ($file_count > 0 && !isset($_POST['force'])) {
                header('HTTP/1.0 400 Bad Request');
                echo json_encode([
                    'error' => 'Folder contains files',
                    'file_count' => $file_count,
                    'needs_confirmation' => true
                ]);
                exit;
            }
            
            // Begin transaction
            $pdo->beginTransaction();
            
            // Get all files in the folder to delete them
            $sql = "SELECT * FROM attachments WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            $files_to_delete = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Delete physical files from disk
            foreach ($files_to_delete as $file) {
                $file_path = $attachments_dir . '/' . $file['file_name'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Log deletion
                $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'delete')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':attachment_id', $file['id']);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->execute();
            }
            
            // Delete all files in the folder from database
            $sql = "DELETE FROM attachments WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            
            // Delete folder
            $sql = "DELETE FROM attachment_folders WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            
            // Commit transaction
            $pdo->commit();
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Folder deletion failed: ' . $e->getMessage();
            exit;
        }
    }
    
    // Handle file download request
    if (isset($_GET['download']) && $select == 1) {
        $file_id = $_GET['download'];
        
        // Get file info from database
        $sql = "SELECT * FROM attachments WHERE id = :id AND (staff_id = :staff_id OR 1 = :is_admin)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $file_id);
        $stmt->bindParam(':staff_id', $_SESSION['user_id']);
        $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
        $stmt->bindParam(':is_admin', $isAdmin);
        $stmt->execute();
        $file = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($file) {
            $file_path = $attachments_dir . '/' . $file['file_name'];
            
            if (file_exists($file_path)) {
                // Log download
                $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'download')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':attachment_id', $file_id);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->execute();
                
                // Send file to browser
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $file['file_name'] . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file_path));
                readfile($file_path);
                exit;
            }
        }
        
        // If we get here, file doesn't exist or user doesn't have permission
        header('HTTP/1.0 404 Not Found');
        echo 'File not found';
        exit;
    }
    
    // Handle GET request - list files
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['download']) && !isset($_GET['get_folders']) && !isset($_GET['get_staff']) && !isset($_GET['get_folder_shares']) && $select == 1) {
        try {
            // Base query with joining to check share permissions
            $sql = "SELECT a.*, 
                    DATE_FORMAT(a.upload_date, '%d %b %Y %H:%i') as upload_date
                    FROM attachments a
                    LEFT JOIN attachment_folders f ON a.folder_id = f.folder_id
                    LEFT JOIN folder_shares fs ON f.folder_id = fs.folder_id AND fs.staff_id = :staff_id
                    WHERE (a.staff_id = :staff_id 
                        OR :is_admin = 1
                        OR fs.share_id IS NOT NULL)";
                    
            // Add folder filter if provided
            if (isset($_GET['folder_id']) && is_numeric($_GET['folder_id'])) {
                $folder_id = $_GET['folder_id'];
                
                // Check if user has access to this folder
                $checkSql = "SELECT 1 FROM attachment_folders f
                            LEFT JOIN folder_shares fs ON f.folder_id = fs.folder_id AND fs.staff_id = :staff_id
                            WHERE f.folder_id = :folder_id
                            AND (f.staff_id = :staff_id 
                                OR :is_admin = 1
                                OR fs.share_id IS NOT NULL)";
                $checkStmt = $pdo->prepare($checkSql);
                $checkStmt->bindParam(':folder_id', $folder_id);
                $checkStmt->bindParam(':staff_id', $_SESSION['user_id']);
                $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
                $checkStmt->bindParam(':is_admin', $isAdmin);
                $checkStmt->execute();
                
                if ($checkStmt->rowCount() === 0) {
                    // User doesn't have access to this folder
                    header('HTTP/1.0 403 Forbidden');
                    echo json_encode(['error' => 'You do not have permission to access this folder']);
                    exit;
                }
                
                $sql .= " AND a.folder_id = :folder_id";
            } else if (isset($_GET['root_folder']) && $_GET['root_folder'] == '1') {
                $sql .= " AND a.folder_id IS NULL";
            }
            
            // Add timestamp filter for polling updates
            if (isset($_GET['since']) && !empty($_GET['since'])) {
                $sql .= " AND a.upload_date > :since";
            }
            
            $sql .= " ORDER BY a.upload_date DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            
            // Bind folder_id if provided
            if (isset($_GET['folder_id']) && is_numeric($_GET['folder_id'])) {
                $folder_id = $_GET['folder_id'];
                $stmt->bindParam(':folder_id', $folder_id);
            }
            
            // Bind since timestamp if provided
            if (isset($_GET['since']) && !empty($_GET['since'])) {
                $since = $_GET['since'];
                $stmt->bindParam(':since', $since);
            }
            
            $stmt->execute();
            $files = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Add file size information
            foreach ($files as &$file) {
                $file_path = $attachments_dir . '/' . $file['file_name'];
                if (file_exists($file_path)) {
                    $size_bytes = filesize($file_path);
                    if ($size_bytes < 1024) {
                        $file['file_size'] = $size_bytes . ' bytes';
                    } elseif ($size_bytes < 1048576) {
                        $file['file_size'] = round($size_bytes / 1024, 1) . ' KB';
                    } else {
                        $file['file_size'] = round($size_bytes / 1048576, 1) . ' MB';
                    }
                } else {
                    $file['file_size'] = 'Unknown';
                }
            }
            
            // Return files as JSON
            header('Content-Type: application/json');
            echo json_encode($files);
            exit;
        } catch (Exception $e) {
            // Log and return the actual error details
            error_log("Error listing files: " . $e->getMessage());
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'debug_info' => 'Check that the attachments table exists and has the correct columns'
            ]);
            exit;
        }
    }
    
    // Function to compress images using the TinyPNG API
    function compressImageWithTinyPNG($source_path, $destination_path, $api_key) {
        // Only compress these image types
        $file_extension = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));
        $compressible_extensions = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($file_extension, $compressible_extensions)) {
            // File type not compressible, return false to use original
            return false;
        }
        
        // Read image file
        $source_data = file_get_contents($source_path);
        
        // Initialize cURL
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.tinify.com/shrink",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERPWD => "api:" . $api_key,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $source_data,
            CURLOPT_HTTPHEADER => ["Content-Type: image/png", "Content-Length: " . strlen($source_data)]
        ]);
        
        // Execute the compression request
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        // Check for errors
        if ($status !== 201) {
            // Log error details for debugging
            error_log("TinyPNG API Error: Status " . $status . " - " . $response);
            return false;
        }
        
        // Extract headers
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $header_size);
        $res = json_decode(substr($response, $header_size));
        
        // Close first cURL session
        curl_close($curl);
        
        // Check if we have a compressed URL to download from
        if (!isset($res->output->url)) {
            error_log("TinyPNG API Error: No compressed URL received");
            return false;
        }
        
        // Download the compressed image
        $download_curl = curl_init();
        curl_setopt_array($download_curl, [
            CURLOPT_URL => $res->output->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        
        $compressed_image = curl_exec($download_curl);
        $download_status = curl_getinfo($download_curl, CURLINFO_HTTP_CODE);
        curl_close($download_curl);
        
        // Check download status
        if ($download_status !== 200) {
            error_log("TinyPNG API Error: Failed to download compressed image");
            return false;
        }
        
        // Save the compressed image
        if (file_put_contents($destination_path, $compressed_image)) {
            // Get compression stats
            $original_size = filesize($source_path);
            $compressed_size = filesize($destination_path);
            $saved = $original_size - $compressed_size;
            $percent = round(($saved / $original_size) * 100, 1);
            
            error_log("Image compressed: " . $source_path . " - Saved " . $percent . "% (" . 
                      round($saved / 1024, 1) . " KB)");
            return true;
        } else {
            error_log("Error saving compressed image: " . $destination_path);
            return false;
        }
    }

    // Handle file upload (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && $insert == 1) {
        try {
            $file = $_FILES['file'];
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $folder_id = isset($_POST['folder_id']) && !empty($_POST['folder_id']) ? $_POST['folder_id'] : null;
            
            // If uploading to a folder, check permissions
            if ($folder_id) {
                // Check if user has edit permission on this folder
                $sql = "SELECT 1 FROM attachment_folders f
                        LEFT JOIN folder_shares fs ON f.folder_id = fs.folder_id AND fs.staff_id = :staff_id
                        WHERE f.folder_id = :folder_id
                        AND (f.staff_id = :staff_id 
                            OR :is_admin = 1
                            OR (fs.share_id IS NOT NULL AND fs.permission = 'edit'))";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
                $stmt->bindParam(':is_admin', $isAdmin);
                $stmt->execute();
                
                if ($stmt->rowCount() === 0) {
                    header('HTTP/1.0 403 Forbidden');
                    echo 'You do not have permission to upload files to this folder.';
                    exit;
                }
            }
            
            // Validate file
            $max_size = 10 * 1024 * 1024; // 10 MB
            if ($file['size'] > $max_size) {
                header('HTTP/1.0 400 Bad Request');
                echo 'File exceeds maximum size limit of 10 MB';
                exit;
            }
            
            // Extract file extension
            $file_name = $file['name'];
            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Generate a temporary filename for initial storage
            $temp_filename = uniqid() . '.' . $extension;
            $temp_destination = $attachments_dir . '/' . $temp_filename;
            
            // Check if directory exists before uploading
            if (!file_exists($attachments_dir)) {
                error_log("Attachments directory doesn't exist: " . $attachments_dir);
                throw new Exception("The attachments directory doesn't exist. Please create it manually with proper permissions (chmod 777).");
            }
            
            // Check if directory is writable
            if (!is_writable($attachments_dir)) {
                error_log("Attachments directory isn't writable: " . $attachments_dir);
                throw new Exception("The attachments directory exists but isn't writable. Please set permissions with chmod 777.");
            }
            
            // Move uploaded file to temporary location
            if (move_uploaded_file($file['tmp_name'], $temp_destination)) {
                // Begin transaction
                $pdo->beginTransaction();
                
                // Save file info to database using the temporary filename first
                $sql = "INSERT INTO attachments (file_name, original_name, description, staff_id, folder_id) 
                        VALUES (:file_name, :original_name, :description, :staff_id, :folder_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':file_name', $temp_filename);
                $stmt->bindParam(':original_name', $file_name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->bindParam(':folder_id', $folder_id, PDO::PARAM_INT);
                $stmt->execute();
                
                // Get the new file ID
                $file_id = $pdo->lastInsertId();
                
                // Create the new filename using the attachment ID
                $new_filename = $file_id . '.' . $extension;
                $new_destination = $attachments_dir . '/' . $new_filename;
                
                // Try to compress the image if it's a supported file type
                $compressed = false;
                $file_compressed = false;
                
                // Check if this is a compressible image type
                $compressible_extensions = ['jpg', 'jpeg', 'png'];
                if (in_array($extension, $compressible_extensions)) {
                    // Define your TinyPNG API key - replace with your actual key
                    $tinypng_api_key = "YOUR_TINYPNG_API_KEY"; // Replace this with your actual API key
                    
                    // Only attempt compression if we have an API key
                    if ($tinypng_api_key && $tinypng_api_key !== "YOUR_TINYPNG_API_KEY") {
                        // Create a temporary file for compression
                        $compressed_destination = $attachments_dir . '/compressed_' . $temp_filename;
                        
                        // Try to compress the image
                        $file_compressed = compressImageWithTinyPNG($temp_destination, $compressed_destination, $tinypng_api_key);
                        
                        // If compression was successful, use the compressed file instead
                        if ($file_compressed) {
                            // Remove the original temporary file
                            unlink($temp_destination);
                            // Use the compressed file for renaming
                            $temp_destination = $compressed_destination;
                            $compressed = true;
                        }
                    }
                }
                
                // Rename the file to use the attachment ID
                if (rename($temp_destination, $new_destination)) {
                    // Update the database with the new filename
                    $sql = "UPDATE attachments SET file_name = :new_filename WHERE id = :file_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':new_filename', $new_filename);
                    $stmt->bindParam(':file_id', $file_id);
                    $stmt->execute();
                    
                    // Log upload
                    $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'upload')";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':attachment_id', $file_id);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    
                    // Commit transaction
                    $pdo->commit();
                    
                    // Return success response with compression info
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true, 
                        'file_id' => $file_id,
                        'compressed' => $compressed
                    ]);
                    exit;
                } else {
                    // Rollback if rename fails
                    $pdo->rollBack();
                    throw new Exception('Error renaming uploaded file');
                }
            } else {
                throw new Exception('Error moving uploaded file');
            }
        } catch (Exception $e) {
            // Rollback transaction if there was one started
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            // Clean up temporary file if it exists
            if (isset($temp_destination) && file_exists($temp_destination)) {
                unlink($temp_destination);
            }
            
            // Clean up compressed temporary file if it exists
            if (isset($compressed_destination) && file_exists($compressed_destination)) {
                unlink($compressed_destination);
            }
            
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Upload failed: ' . $e->getMessage();
            exit;
        }
    }
    
    // Handle file deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && $delete == 1) {
        $file_id = $_POST['delete'];
        
        // Get file info from database with folder information
        $sql = "SELECT a.*, f.staff_id as folder_owner_id 
                FROM attachments a
                LEFT JOIN attachment_folders f ON a.folder_id = f.folder_id
                WHERE a.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $file_id);
        $stmt->execute();
        $file = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$file) {
            header('HTTP/1.0 404 Not Found');
            echo 'File not found';
            exit;
        }
        
        // Check if user has permission to delete the file
        $hasPermission = false;
        
        // User owns the file
        if ($file['staff_id'] == $_SESSION['user_id']) {
            $hasPermission = true;
        }
        // User is admin
        else if ($_SESSION['role_id'] == 1) {
            $hasPermission = true;
        }
        // File is in a folder and user is checking shared permissions
        else if ($file['folder_id']) {
            // Check if user has edit permission on the folder
            $sql = "SELECT 1 FROM folder_shares 
                    WHERE folder_id = :folder_id 
                    AND staff_id = :staff_id 
                    AND permission = 'edit'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $file['folder_id']);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $hasPermission = true;
            }
        }
        
        if (!$hasPermission) {
            header('HTTP/1.0 403 Forbidden');
            echo 'You do not have permission to delete this file';
            exit;
        }
        
        // Delete file from disk
        $file_path = $attachments_dir . '/' . $file['file_name'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        
        // Log deletion
        $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'delete')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':attachment_id', $file_id);
        $stmt->bindParam(':staff_id', $_SESSION['user_id']);
        $stmt->execute();
        
        // Delete file from database
        $sql = "DELETE FROM attachments WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $file_id);
        $stmt->execute();
        
        // Return success response
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
    
    // Handle multiple file deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_multiple']) && $delete == 1) {
        try {
            // Get file IDs from request
            $file_ids = $_POST['file_ids'];
            
            // Begin transaction
            $pdo->beginTransaction();
            
            $successful_deletes = 0;
            $failed_deletes = 0;
            
            // Process each file
            foreach ($file_ids as $file_id) {
                // Get file info from database with folder information
                $sql = "SELECT a.*, f.staff_id as folder_owner_id 
                        FROM attachments a
                        LEFT JOIN attachment_folders f ON a.folder_id = f.folder_id
                        WHERE a.id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $file_id);
                $stmt->execute();
                $file = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if (!$file) {
                    $failed_deletes++;
                    continue;
                }
                
                // Check if user has permission to delete the file
                $hasPermission = false;
                
                // User owns the file
                if ($file['staff_id'] == $_SESSION['user_id']) {
                    $hasPermission = true;
                }
                // User is admin
                else if ($_SESSION['role_id'] == 1) {
                    $hasPermission = true;
                }
                // File is in a folder and user is checking shared permissions
                else if ($file['folder_id']) {
                    // Check if user has edit permission on the folder
                    $sql = "SELECT 1 FROM folder_shares 
                            WHERE folder_id = :folder_id 
                            AND staff_id = :staff_id 
                            AND permission = 'edit'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':folder_id', $file['folder_id']);
                    $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $hasPermission = true;
                    }
                }
                
                if (!$hasPermission) {
                    $failed_deletes++;
                    continue;
                }
                
                // Delete file from disk
                $file_path = $attachments_dir . '/' . $file['file_name'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Log deletion
                $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'delete')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':attachment_id', $file_id);
                $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                $stmt->execute();
                
                // Delete file from database
                $sql = "DELETE FROM attachments WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $file_id);
                $stmt->execute();
                
                $successful_deletes++;
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'deleted' => $successful_deletes,
                'failed' => $failed_deletes
            ]);
            exit;
        } catch (Exception $e) {
            // Roll back transaction if an error occurs
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Multiple file deletion failed: ' . $e->getMessage();
            exit;
        }
    }
    
    // Handle folder rename
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rename_folder']) && $update == 1) {
        try {
            $folder_id = $_POST['folder_id'];
            $new_name = trim($_POST['new_name']);
            
            // Validate folder name (only allow alphanumeric, spaces, underscores, and hyphens)
            if (!preg_match('/^[a-zA-Z0-9 _-]+$/', $new_name)) {
                throw new Exception('Folder name contains invalid characters. Use only letters, numbers, spaces, hyphens, and underscores.');
            }
            
            // Check ownership or admin privilege
            $sql = "SELECT * FROM attachment_folders WHERE folder_id = :folder_id AND (staff_id = :staff_id OR :is_admin = 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Folder not found or you do not have permission to rename it');
            }
            
            // Update folder name
            $sql = "UPDATE attachment_folders SET folder_name = :new_name WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':new_name', $new_name);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'folder_id' => $folder_id,
                'new_name' => $new_name
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo 'Folder rename failed: ' . $e->getMessage();
            exit;
        }
    }
    
    // Handle GET staff members for sharing
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_staff']) && $select == 1) {
        try {
            // Get all staff members except current user
            $sql = "SELECT staff_id, staff_name FROM staff WHERE staff_id != :current_staff_id ORDER BY staff_name ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':current_staff_id', $_SESSION['user_id']);
            $stmt->execute();
            $staff = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Return staff list as JSON
            header('Content-Type: application/json');
            echo json_encode($staff);
            exit;
            
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    
    // Handle GET folder shares
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_folder_shares']) && $select == 1) {
        try {
            $folder_id = $_GET['get_folder_shares'];
            
            // Verify folder access
            $sql = "SELECT * FROM attachment_folders WHERE folder_id = :folder_id AND (staff_id = :staff_id OR :is_admin = 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Folder not found or you do not have permission to access it');
            }
            
            // Get folder shares
            $sql = "SELECT fs.share_id, fs.folder_id, fs.staff_id, s.staff_name, fs.permission 
                    FROM folder_shares fs
                    JOIN staff s ON fs.staff_id = s.staff_id
                    WHERE fs.folder_id = :folder_id
                    ORDER BY s.staff_name ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            $shares = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Return shares as JSON
            header('Content-Type: application/json');
            echo json_encode($shares);
            exit;
            
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
    
    // Handle adding folder share
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_share']) && $update == 1) {
        try {
            $folder_id = $_POST['folder_id'];
            $staff_id = $_POST['staff_id'];
            $permission = $_POST['permission'];
            
            // Validate permission values
            if (!in_array($permission, ['view', 'edit'])) {
                throw new Exception('Invalid permission value');
            }
            
            // Verify folder ownership
            $sql = "SELECT * FROM attachment_folders WHERE folder_id = :folder_id AND (staff_id = :owner_id OR :is_admin = 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':owner_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            $stmt->execute();
            
            if ($stmt->rowCount() == 0) {
                throw new Exception('Folder not found or you do not have permission to share it');
            }
            
            // Begin transaction
            $pdo->beginTransaction();
            
            // Check if already shared with this staff member
            $sql = "SELECT * FROM folder_shares WHERE folder_id = :folder_id AND staff_id = :staff_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->bindParam(':staff_id', $staff_id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // Update existing share
                $sql = "UPDATE folder_shares SET permission = :permission WHERE folder_id = :folder_id AND staff_id = :staff_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->bindParam(':staff_id', $staff_id);
                $stmt->bindParam(':permission', $permission);
                $stmt->execute();
            } else {
                // Create new share
                $sql = "INSERT INTO folder_shares (folder_id, staff_id, permission) VALUES (:folder_id, :staff_id, :permission)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->bindParam(':staff_id', $staff_id);
                $stmt->bindParam(':permission', $permission);
                $stmt->execute();
                
                // Update is_shared flag on the folder (replacing the trigger)
                $sql = "UPDATE attachment_folders SET is_shared = 1 WHERE folder_id = :folder_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->execute();
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction if an error occurs
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            header('HTTP/1.0 500 Internal Server Error');
            echo $e->getMessage();
            exit;
        }
    }
    
    // Handle removing folder share
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_share']) && $update == 1) {
        try {
            $share_id = $_POST['remove_share'];
            
            // Begin transaction
            $pdo->beginTransaction();
            
            // Get share details before deletion to know which folder it belongs to
            $sql = "SELECT fs.*, f.folder_id 
                    FROM folder_shares fs
                    JOIN attachment_folders f ON fs.folder_id = f.folder_id
                    WHERE fs.share_id = :share_id AND (f.staff_id = :staff_id OR :is_admin = 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':share_id', $share_id);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $isAdmin = ($_SESSION['role_id'] == 1) ? 1 : 0;
            $stmt->bindParam(':is_admin', $isAdmin);
            $stmt->execute();
            
            $share = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$share) {
                throw new Exception('Share not found or you do not have permission to remove it');
            }
            
            $folder_id = $share['folder_id'];
            
            // Delete the share
            $sql = "DELETE FROM folder_shares WHERE share_id = :share_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':share_id', $share_id);
            $stmt->execute();
            
            // Check if there are any remaining shares for this folder
            $sql = "SELECT COUNT(*) FROM folder_shares WHERE folder_id = :folder_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':folder_id', $folder_id);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            
            // If no shares remain, update is_shared flag to 0 (replacing the trigger)
            if ($count == 0) {
                $sql = "UPDATE attachment_folders SET is_shared = 0 WHERE folder_id = :folder_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':folder_id', $folder_id);
                $stmt->execute();
            }
            
            // Commit transaction
            $pdo->commit();
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (Exception $e) {
            // Rollback transaction if an error occurs
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            
            header('HTTP/1.0 500 Internal Server Error');
            echo $e->getMessage();
            exit;
        }
    }
    
    // Handle OCR text extraction
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_ocr']) && $select == 1) {
        try {
            $file_id = $_POST['file_id'];
            
            // Get file info from database
            $sql = "SELECT * FROM attachments WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $file_id);
            $stmt->execute();
            $file = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$file) {
                throw new Exception('File not found');
            }
            
            $file_path = $attachments_dir . '/' . $file['file_name'];
            $file_ext = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
            
            // Check if file exists
            if (!file_exists($file_path)) {
                throw new Exception('File not found on server');
            }
            
            // Check file type
            if (!in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'pdf'])) {
                throw new Exception('File type not supported for OCR');
            }
            
            // Use appropriate OCR method based on file type
            $text = "";
            $useLocalOcr = false;
            $apiError = "";
            $extractedFields = [];
            
            // Try Google Document AI first (but only if not already decided to use local OCR)
            if (!$useLocalOcr) {
                try {
                    // Define Google Document AI API settings - use the same as processPassport.php
                    define('GOOGLE_DOCUMENT_AI_PROJECT_ID', '947608979620');
                    define('GOOGLE_DOCUMENT_AI_LOCATION', 'us'); 
                    define('GOOGLE_DOCUMENT_AI_PROCESSOR_ID', '72869a7e49823f1a'); // Passport processor ID
                    define('GOOGLE_APPLICATION_CREDENTIALS', __DIR__ . '/service-account-key.json');
                    
                    // Check if service account file exists
                    if (!file_exists(GOOGLE_APPLICATION_CREDENTIALS)) {
                        throw new Exception('Google Cloud credentials not found');
                    }
                    
                    // Get access token
                    $accessToken = getGoogleAccessToken();
                    if (!$accessToken) {
                        throw new Exception('Failed to authenticate with Google Cloud');
                    }
                    
                    // Read file as base64
                    $fileData = base64_encode(file_get_contents($file_path));
                    
                    // Set correct MIME type based on extension
                    $mimeType = '';
                    if (in_array($file_ext, ['jpg', 'jpeg'])) {
                        $mimeType = 'image/jpeg';
                    } elseif ($file_ext == 'png') {
                        $mimeType = 'image/png';
                    } elseif ($file_ext == 'gif') {
                        $mimeType = 'image/gif';
                    } elseif ($file_ext == 'pdf') {
                        $mimeType = 'application/pdf';
                    }
                    
                    // Build Document AI request
                    $apiUrl = "https://" . GOOGLE_DOCUMENT_AI_LOCATION . "-documentai.googleapis.com/v1/projects/" . 
                              GOOGLE_DOCUMENT_AI_PROJECT_ID . "/locations/" . GOOGLE_DOCUMENT_AI_LOCATION . 
                              "/processors/" . GOOGLE_DOCUMENT_AI_PROCESSOR_ID . ":process";
                    
                    // Build request payload
                    $requestData = [
                        'rawDocument' => [
                            'content' => $fileData,
                            'mimeType' => $mimeType
                        ]
                    ];
                    
                    $jsonPayload = json_encode($requestData);
                    
                    // Call Document AI API
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $apiUrl);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $accessToken
                    ]);
                    
                    // SSL settings
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    
                    // Set timeout
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    
                    // Execute the request
                    $response = curl_exec($ch);
                    $curlError = curl_error($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    
                    curl_close($ch);
                    
                    // Check for errors
                    if ($curlError) {
                        throw new Exception('API request failed: ' . $curlError);
                    }
                    
                    if ($httpCode != 200) {
                        $errorMessage = 'API returned error code: ' . $httpCode;
                        if ($response) {
                            // Try to parse error details from response
                            $errorData = json_decode($response, true);
                            if (json_last_error() === JSON_ERROR_NONE && isset($errorData['error'])) {
                                // Extract detailed error information
                                if (isset($errorData['error']['message'])) {
                                    $errorMessage .= ' - ' . $errorData['error']['message'];
                                }
                            }
                        }
                        throw new Exception($errorMessage);
                    }
                    
                    // Decode the response
                    $result = json_decode($response, true);
                    if (!$result) {
                        throw new Exception('Failed to decode API response');
                    }
                    
                    // Extract text from response
                    if (isset($result['document']) && isset($result['document']['text'])) {
                        $text = $result['document']['text'];
                        
                        // Extract structured fields for passports
                        $extractedFields = extractStructuredFields($result);
                        
                        // Format the output with extracted fields at the top if available
                        if (!empty($extractedFields)) {
                            $formattedText = formatExtractedFields($extractedFields);
                            $text = $formattedText . "\n\n--- RAW EXTRACTED TEXT ---\n\n" . $text;
                        }
                    } else {
                        throw new Exception('No text found in the document');
                    }
                } catch (Exception $e) {
                    // Store the error message
                    $apiError = $e->getMessage();
                    
                    // If Google Document AI fails, fall back to local OCR
                    $useLocalOcr = true;
                }
            }
            
            // Use local Tesseract OCR as fallback
            if ($useLocalOcr) {
                try {
                    // Check if Tesseract is installed
                    $tesseractInstalled = false;
                    $tesseractPath = '';
                    
                    // Common paths where Tesseract might be installed
                    $possiblePaths = [
                        'tesseract', // Default PATH
                        '/usr/local/bin/tesseract', // Homebrew on Mac
                        '/opt/homebrew/bin/tesseract', // Apple Silicon Homebrew
                        '/usr/bin/tesseract', // Linux standard
                        '/opt/local/bin/tesseract', // MacPorts
                        'C:\\Program Files\\Tesseract-OCR\\tesseract.exe', // Windows standard
                        'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe' // Windows 32-bit on 64-bit
                    ];
                    
                    // Check each path
                    foreach ($possiblePaths as $path) {
                        exec("$path --version 2>&1", $output, $return_var);
                        if ($return_var === 0) {
                            $tesseractInstalled = true;
                            $tesseractPath = $path;
                            break;
                        }
                    }
                    
                    if (!$tesseractInstalled) {
                        throw new Exception("Google Cloud OCR failed due to billing issue: $apiError\n\nLocal Tesseract OCR is not installed. Please enable billing for Google Cloud project or install Tesseract OCR.");
                    }
                    
                    if ($file_ext === 'pdf') {
                        // For PDFs, we need poppler-utils to convert to images first
                        $popplerInstalled = false;
                        $pdftoppmPath = '';
                        
                        // Check for pdftoppm (from poppler-utils)
                        $possiblePdftoppmPaths = [
                            'pdftoppm', // Default PATH
                            '/usr/local/bin/pdftoppm', // Homebrew on Mac
                            '/opt/homebrew/bin/pdftoppm', // Apple Silicon Homebrew
                            '/usr/bin/pdftoppm', // Linux standard
                            '/opt/local/bin/pdftoppm' // MacPorts
                        ];
                        
                        foreach ($possiblePdftoppmPaths as $path) {
                            exec("$path -v 2>&1", $output, $return_var);
                            if ($return_var === 0) {
                                $popplerInstalled = true;
                                $pdftoppmPath = $path;
                                break;
                            }
                        }
                        
                        if (!$popplerInstalled) {
                            throw new Exception("Google Cloud OCR failed due to billing issue: $apiError\n\nCan't process PDF with local OCR: poppler-utils not installed. Please install poppler-utils or enable billing for Google Cloud.");
                        }
                        
                        // Convert PDF to images
                        $tempDir = sys_get_temp_dir() . '/ocr_' . uniqid();
                        if (!file_exists($tempDir)) {
                            mkdir($tempDir, 0777, true);
                        }
                        
                        $baseFilename = $tempDir . '/page';
                        $convertCommand = "$pdftoppmPath -jpeg " . escapeshellarg($file_path) . " " . escapeshellarg($baseFilename);
                        
                        exec($convertCommand, $output, $return_var);
                        if ($return_var !== 0) {
                            throw new Exception("Failed to convert PDF to images for OCR processing.");
                        }
                        
                        // Process each page with Tesseract
                        $pageTexts = [];
                        $pageFiles = glob($tempDir . '/page-*.jpg');
                        if (empty($pageFiles)) {
                            $pageFiles = glob($tempDir . '/page*.jpg'); // Try different naming pattern
                        }
                        
                        if (!empty($pageFiles)) {
                            foreach ($pageFiles as $pageFile) {
                                $tessCommand = "$tesseractPath " . escapeshellarg($pageFile) . " stdout 2>/dev/null";
                                $pageText = shell_exec($tessCommand);
                                if ($pageText) {
                                    $pageTexts[] = trim($pageText);
                                }
                            }
                            
                            // Combine all page texts
                            $text = implode("\n\n--- Page Break ---\n\n", $pageTexts);
                            
                            // Clean up temporary files
                            foreach (glob($tempDir . '/*') as $tempFile) {
                                @unlink($tempFile);
                            }
                            @rmdir($tempDir);
                        }
                    } else {
                        // For images, use Tesseract directly
                        $tessCommand = "$tesseractPath " . escapeshellarg($file_path) . " stdout 2>/dev/null";
                        $extractedText = shell_exec($tessCommand);
                        if ($extractedText) {
                            $text = trim($extractedText);
                        }
                    }
                    
                    if (empty($text)) {
                        throw new Exception("No text could be extracted from the document using local OCR.");
                    }
                    
                    // Attempt simple field extraction with regex for Tesseract
                    $extractedFields = extractFieldsWithRegex($text);
                    
                    // Format the text with extracted fields at the top if available
                    if (!empty($extractedFields)) {
                        $formattedText = formatExtractedFields($extractedFields);
                        $text = "[Local OCR Mode - Google Cloud OCR failed due to billing issue]\n\n" . 
                                $formattedText . "\n\n--- RAW EXTRACTED TEXT ---\n\n" . $text;
                    } else {
                        // Prepend a note about using local OCR instead of Google Cloud
                        $text = "[Local OCR Mode - Google Cloud OCR failed due to billing issue]\n\n" . $text;
                    }
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }
            }
            
            // Log OCR operation
            $sql = "INSERT INTO attachments_log (attachment_id, staff_id, action) VALUES (:attachment_id, :staff_id, 'download')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':attachment_id', $file_id);
            $stmt->bindParam(':staff_id', $_SESSION['user_id']);
            $stmt->execute();
            
            // Return OCR text with debugging info
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'text' => $text,
                'extracted_fields' => $extractedFields,
                'debug' => [
                    'file_name' => $file['original_name'],
                    'file_type' => $file_ext,
                    'api_used' => $useLocalOcr ? 'Local Tesseract OCR' : 'Google Document AI',
                    'api_location' => $useLocalOcr ? 'local' : GOOGLE_DOCUMENT_AI_LOCATION,
                    'api_error' => $apiError,
                    'processor_id' => $useLocalOcr ? null : GOOGLE_DOCUMENT_AI_PROCESSOR_ID
                ]
            ]);
            exit;
            
        } catch (Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            echo $e->getMessage();
            exit;
        }
    }
    
    /**
     * Helper function to get Google access token
     */
    function getGoogleAccessToken() {
        try {
            // Read service account key file
            $keyContent = file_get_contents(GOOGLE_APPLICATION_CREDENTIALS);
            if (!$keyContent) {
                return false;
            }
            
            $serviceAccountKey = json_decode($keyContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }
            
            // Create JWT payload
            $now = time();
            $payload = [
                'iss' => $serviceAccountKey['client_email'],
                'aud' => 'https://oauth2.googleapis.com/token',
                'exp' => $now + 3600,
                'iat' => $now,
                'scope' => 'https://www.googleapis.com/auth/cloud-platform'
            ];
            
            // Create JWT header
            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT',
                'kid' => $serviceAccountKey['private_key_id']
            ];
            
            // Encode JWT header and payload
            $base64Header = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
            $base64Payload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
            
            // Create signature
            $privateKey = $serviceAccountKey['private_key'];
            $signature = '';
            
            $success = openssl_sign(
                $base64Header . '.' . $base64Payload,
                $signature,
                $privateKey,
                'SHA256'
            );
            
            if (!$success) {
                return false;
            }
            
            // Encode signature
            $base64Signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
            
            // Create JWT
            $jwt = $base64Header . '.' . $base64Payload . '.' . $base64Signature;
            
            // Request access token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]));
            
            // SSL settings
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            curl_close($ch);
            
            if ($status != 200) {
                return false;
            }
            
            $result = json_decode($response, true);
            if (!isset($result['access_token'])) {
                return false;
            }
            
            return $result['access_token'];
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Extract structured fields from Document AI response
     */
    function extractStructuredFields($result) {
        $fields = [];
        
        // Look for entities in the response
        if (isset($result['document']) && isset($result['document']['entities'])) {
            $entities = $result['document']['entities'];
            
            foreach ($entities as $entity) {
                if (!isset($entity['type']) || !isset($entity['mentionText'])) {
                    continue;
                }
                
                $type = $entity['type']; // Keep original case for exact matching
                $value = trim($entity['mentionText']);
                
                // Map exact field names from Google Document AI processor
                switch($type) {
                    case 'DOB':
                        $fields['Date of Birth'] = standardizeDate($value);
                        break;
                    case 'GENDER':
                        $fields['Gender'] = $value;
                        break;
                    case 'GIVEN_NAME':
                        $fields['Given Name'] = $value;
                        break;
                    case 'LAST_NAME':
                        $fields['Surname'] = $value;
                        break;
                    case 'NATIONALITY':
                        $fields['Nationality'] = $value;
                        break;
                    case 'PASSPORT_EXPIRY':
                        $fields['Expiry Date'] = standardizeDate($value);
                        break;
                    case 'passport_issue_date':
                        $fields['Issue Date'] = standardizeDate($value);
                        break;
                    case 'PASSPORT_NO':
                        $fields['Passport Number'] = $value;
                        break;
                    case 'arabic_full_name':
                        $fields['Arabic Name'] = $value;
                        break;
                    case 'arabic_last_name':
                        $fields['Arabic Surname'] = $value;
                        break;
                    // Keep additional field mappings for backward compatibility
                    case 'dob':
                    case 'date_of_birth':
                        if (!isset($fields['Date of Birth'])) {
                            $fields['Date of Birth'] = standardizeDate($value);
                        }
                        break;
                    case 'gender':
                    case 'sex':
                        if (!isset($fields['Gender'])) {
                            $fields['Gender'] = $value;
                        }
                        break;
                    case 'given_name':
                    case 'first_name':
                        if (!isset($fields['Given Name'])) {
                            $fields['Given Name'] = $value;
                        }
                        break;
                    case 'last_name':
                    case 'surname':
                        if (!isset($fields['Surname'])) {
                            $fields['Surname'] = $value;
                        }
                        break;
                    case 'nationality':
                    case 'citizenship':
                        if (!isset($fields['Nationality'])) {
                            $fields['Nationality'] = $value;
                        }
                        break;
                    case 'passport_expiry':
                    case 'expiry_date':
                        if (!isset($fields['Expiry Date'])) {
                            $fields['Expiry Date'] = standardizeDate($value);
                        }
                        break;
                    case 'passport_issue':
                    case 'issue_date':
                        if (!isset($fields['Issue Date'])) {
                            $fields['Issue Date'] = standardizeDate($value);
                        }
                        break;
                    case 'passport_no':
                    case 'passport_number':
                        if (!isset($fields['Passport Number'])) {
                            $fields['Passport Number'] = $value;
                        }
                        break;
                    case 'arabic_name':
                    case 'name_arabic':
                        if (!isset($fields['Arabic Name'])) {
                            $fields['Arabic Name'] = $value;
                        }
                        break;
                    case 'id_number':
                    case 'emirates_id':
                        $fields['ID Number'] = $value;
                        break;
                    case 'address':
                        $fields['Address'] = $value;
                        break;
                    case 'email':
                        $fields['Email'] = $value;
                        break;
                    case 'phone':
                    case 'telephone':
                        $fields['Phone'] = $value;
                        break;
                }
            }
        }
        
        // Also check for form fields which may have more structured data
        if (isset($result['document']) && isset($result['document']['pages'])) {
            foreach ($result['document']['pages'] as $page) {
                if (isset($page['formFields'])) {
                    foreach ($page['formFields'] as $field) {
                        if (isset($field['fieldName']) && isset($field['fieldName']['textAnchor']) && 
                            isset($field['fieldValue']) && isset($field['fieldValue']['textAnchor'])) {
                            
                            // Extract field name and value from text anchors
                            $fieldName = '';
                            $fieldValue = '';
                            
                            // Extract field name
                            if (isset($field['fieldName']['textAnchor']['textSegments'])) {
                                foreach ($field['fieldName']['textAnchor']['textSegments'] as $segment) {
                                    if (isset($segment['startIndex']) && isset($segment['endIndex'])) {
                                        $fieldName .= substr($result['document']['text'], 
                                                         $segment['startIndex'], 
                                                         $segment['endIndex'] - $segment['startIndex']);
                                    }
                                }
                            }
                            
                            // Extract field value
                            if (isset($field['fieldValue']['textAnchor']['textSegments'])) {
                                foreach ($field['fieldValue']['textAnchor']['textSegments'] as $segment) {
                                    if (isset($segment['startIndex']) && isset($segment['endIndex'])) {
                                        $fieldValue .= substr($result['document']['text'], 
                                                          $segment['startIndex'], 
                                                          $segment['endIndex'] - $segment['startIndex']);
                                    }
                                }
                            }
                            
                            $fieldName = trim($fieldName);
                            $fieldValue = trim($fieldValue);
                            
                            if (!empty($fieldName) && !empty($fieldValue)) {
                                // Standardize field name
                                $normalizedName = normalizeFieldName($fieldName);
                                
                                // Add to fields array if not already set or if empty
                                if (!isset($fields[$normalizedName]) || empty($fields[$normalizedName])) {
                                    $fields[$normalizedName] = $fieldValue;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $fields;
    }
    
    /**
     * Extract fields using regular expressions (fallback method)
     */
    function extractFieldsWithRegex($text) {
        $fields = [];
        
        // Define regex patterns for common document fields
        $patterns = [
            'Passport Number' => '/passport\s*(?:no|num|number|#)[\.:\s]*\s*([A-Z0-9]{5,12})/i',
            'Name' => '/name[\.:\s]*([A-Z\s]+(?:[A-Z]{2,}\s*)+)/i',
            'Surname' => '/surname[\.:\s]*([A-Z]+)/i',
            'Given Name' => '/given\s*names?[\.:\s]*([A-Z\s]+)/i',
            'Nationality' => '/nationality[\.:\s]*([A-Z]{2,}(?:\s[A-Z]+)*)/i',
            'Date of Birth' => '/(?:birth|born|dob)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
            'Gender' => '/(?:gender|sex)[\.:\s]*([MF]|MALE|FEMALE)/i',
            'Expiry Date' => '/(?:expiry|expiration)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
            'Issue Date' => '/(?:issue|issuance)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
            'Arabic Name' => '/(?:arabic|arabic\s*name)[\.:\s]*(.+?(?=\n|\s{2,}|$))/i',
            'Arabic Surname' => '/(?:arabic\s*surname|arabic\s*last\s*name)[\.:\s]*(.+?(?=\n|\s{2,}|$))/i',
            'ID Number' => '/(?:id|identification|emirates\s*id)[\.:\s]*(?:number|#)?[\.:\s]*(\d[\d\-]+\d)/i',
            'Phone' => '/(?:phone|telephone|mobile)[\.:\s]*(?:number|#)?[\.:\s]*(\+?[\d\s\-\(\)]{7,20})/i',
            'Email' => '/(?:email|e-mail)[\.:\s]*([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i'
        ];
        
        foreach ($patterns as $field => $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $value = trim($matches[1]);
                
                // Apply date standardization for date fields
                if ($field == 'Date of Birth' || $field == 'Expiry Date' || $field == 'Issue Date') {
                    $value = standardizeDate($value);
                }
                
                $fields[$field] = $value;
            }
        }
        
        return $fields;
    }
    
    /**
     * Format extracted fields as a nice text block
     */
    function formatExtractedFields($fields) {
        if (empty($fields)) {
            return '';
        }
        
        $output = "--- EXTRACTED FIELDS ---\n\n";
        
        // Define field order for better readability
        $orderedFields = [
            'Name', 'Arabic Name', 'Arabic Surname', 'Given Name', 'Surname', 
            'Passport Number', 'Nationality', 
            'Date of Birth', 'Gender', 
            'Issue Date', 'Expiry Date',
            'ID Number', 'Address', 'Phone', 'Email'
        ];
        
        // Add ordered fields first
        foreach ($orderedFields as $field) {
            if (isset($fields[$field]) && !empty($fields[$field])) {
                $output .= sprintf("%-18s: %s\n", $field, $fields[$field]);
            }
        }
        
        // Add any remaining fields not in the ordered list
        foreach ($fields as $field => $value) {
            if (!in_array($field, $orderedFields) && !empty($value)) {
                $output .= sprintf("%-18s: %s\n", $field, $value);
            }
        }
        
        return $output;
    }
    
    /**
     * Standardize field names
     */
    function normalizeFieldName($fieldName) {
        $fieldName = strtolower($fieldName);
        
        $mappings = [
            'surname' => 'Surname',
            'last name' => 'Surname',
            'family name' => 'Surname',
            'given name' => 'Given Name',
            'first name' => 'Given Name',
            'forename' => 'Given Name',
            'full name' => 'Name',
            'name' => 'Name',
            'passport number' => 'Passport Number',
            'passport no' => 'Passport Number',
            'passport' => 'Passport Number',
            'nationality' => 'Nationality',
            'citizenship' => 'Nationality',
            'date of birth' => 'Date of Birth',
            'birth date' => 'Date of Birth',
            'dob' => 'Date of Birth',
            'born' => 'Date of Birth',
            'gender' => 'Gender',
            'sex' => 'Gender',
            'expiry date' => 'Expiry Date',
            'expiration date' => 'Expiry Date',
            'expiration' => 'Expiry Date',
            'valid until' => 'Expiry Date',
            'issue date' => 'Issue Date',
            'issuance date' => 'Issue Date',
            'date of issue' => 'Issue Date',
            'issued on' => 'Issue Date',
            'arabic name' => 'Arabic Name',
            'name in arabic' => 'Arabic Name',
            'arabic full name' => 'Arabic Name',
            'arabic surname' => 'Arabic Surname', 
            'arabic last name' => 'Arabic Surname',
            'last name in arabic' => 'Arabic Surname',
            'id number' => 'ID Number',
            'identification number' => 'ID Number',
            'emirates id' => 'ID Number',
            'phone' => 'Phone',
            'telephone' => 'Phone',
            'mobile' => 'Phone',
            'email' => 'Email',
            'e-mail' => 'Email',
            'address' => 'Address'
        ];
        
        foreach ($mappings as $key => $value) {
            if (strpos($fieldName, $key) !== false) {
                return $value;
            }
        }
        
        // If no mapping found, capitalize first letter of each word
        return ucwords($fieldName);
    }
    
    /**
     * Standardize date formats
     */
    function standardizeDate($dateString) {
        if (empty($dateString)) return '';
        
        $dateString = trim($dateString);
        
        // Handle dates with month names like "20 MAY 2028"
        if (preg_match('/(\d{1,2})\s+([a-zA-Z]+)\s+(\d{4})/i', $dateString, $matches)) {
            $day = $matches[1];
            $month = $matches[2];
            $year = $matches[3];
            
            // Convert month name to number
            $monthNames = [
                'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 'may' => 5, 'jun' => 6,
                'jul' => 7, 'aug' => 8, 'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
            ];
            
            $monthLower = strtolower(substr($month, 0, 3));
            if (isset($monthNames[$monthLower])) {
                $monthNum = $monthNames[$monthLower];
                // Format as YYYY-MM-DD
                return sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
            }
        }
        
        // Handle numeric dates
        if (preg_match('/\d+[\.\/-]\d+[\.\/-]\d+/', $dateString, $matches)) {
            $dateString = trim($matches[0]);
            
            // Try to parse the date with various formats
            $formats = [
                'd/m/Y', 'd-m-Y', 'd.m.Y',
                'm/d/Y', 'm-d-Y', 'm.d.Y',
                'Y/m/d', 'Y-m-d', 'Y.m.d',
                'd/m/y', 'm/d/y', 'y/m/d'
            ];
            
            foreach ($formats as $format) {
                $date = DateTime::createFromFormat($format, $dateString);
                if ($date !== false) {
                    // Convert to database format YYYY-MM-DD
                    return $date->format('Y-m-d');
                }
            }
        }
        
        return $dateString; // Return original if no format matched
    }
    
    // If we get here, the request is invalid
    header('HTTP/1.0 400 Bad Request');
    echo 'Invalid request';
    exit;
?> 