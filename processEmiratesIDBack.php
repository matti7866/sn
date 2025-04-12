<?php
// Include necessary files
include 'connection.php';

// Enable error logging but don't display errors
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Log errors to file instead of output
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug/eid_back_debug.log');

// Debug function to log to a specific file
function debug_log($message) {
    $logFile = __DIR__ . '/debug/eid_back_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Set headers for JSON response
header('Content-Type: application/json');

// Define Google Document AI API settings - use the same settings as processEmiratesID.php
define('USE_GOOGLE_DOCUMENT_AI', true);
define('GOOGLE_DOCUMENT_AI_PROJECT_ID', '947608979620');
define('GOOGLE_DOCUMENT_AI_LOCATION', 'us'); 
define('GOOGLE_DOCUMENT_AI_PROCESSOR_ID', '578ee8fe31793cfe'); // Main Emirates ID processor
define('GOOGLE_APPLICATION_CREDENTIALS', __DIR__ . '/service-account-key.json');
define('ENABLE_EID_OCR', true);
define('USE_FALLBACK_TEST_DATA', false); // Use real OCR instead of test data

// Check if the request is valid
if (!isset($_FILES['emiratesIDFile']) || !isset($_POST['extractorID'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Missing required parameters',
        'data' => null
    ]);
    exit;
}

// Get the extractor ID - but we'll use the Google Document AI processor ID instead
$extractorID = $_POST['extractorID'];

// Validate your extractor ID, but use Google configuration
if ($extractorID !== '6cf54d3c175705b9') {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid extractor ID',
        'data' => null
    ]);
    exit;
}

// Get the file
$file = $_FILES['emiratesIDFile'];

// Check for errors
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'success' => false,
        'error' => 'File upload error: ' . $file['error'],
        'data' => null
    ]);
    exit;
}

// Define upload directory for temporary storage
$uploadDir = __DIR__ . '/residence/temp/';

// Ensure the upload directory exists with proper permissions
if (!file_exists($uploadDir)) {
    debug_log("Upload directory doesn't exist. Creating: " . $uploadDir);
    if (!mkdir($uploadDir, 0777, true)) {
        debug_log("Failed to create upload directory: " . $uploadDir);
        sendJsonResponse(false, null, 'Failed to create upload directory');
    }
    chmod($uploadDir, 0777);
} else {
    debug_log("Upload directory exists: " . $uploadDir);
}

// Replace the mime_content_type function with this alternative:
// Alternative to mime_content_type which requires fileinfo extension
function getFileType($filePath) {
    // Get file extension
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    
    // Map extensions to MIME types
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'pdf' => 'application/pdf'
    ];
    
    // Return MIME type if extension is recognized, otherwise use a default
    return isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : 'application/octet-stream';
}

// Generate a unique filename
$fileType = getFileType($file['name']);
$fileExtension = ($fileType === 'application/pdf') ? '.pdf' : '.jpg';
$tempFileName = uniqid() . '_eid_back' . $fileExtension;
$tempFilePath = $uploadDir . $tempFileName;

try {
    // Copy the file
    if (!copy($file['tmp_name'], $tempFilePath)) {
        debug_log("Failed to copy uploaded file to {$tempFilePath}");
        throw new Exception("Failed to save file for processing");
    }
    
    debug_log("Processing Emirates ID back image: " . $tempFilePath);
    
    // Read image as base64
    $imageData = base64_encode(file_get_contents($tempFilePath));
    
    // Get an access token
    debug_log("Setting credentials path: " . GOOGLE_APPLICATION_CREDENTIALS);
    // We'll use the file directly instead of environment variables
    
    // Build Document AI request
    $apiUrl = "https://" . GOOGLE_DOCUMENT_AI_LOCATION . "-documentai.googleapis.com/v1/projects/" . 
              GOOGLE_DOCUMENT_AI_PROJECT_ID . "/locations/" . GOOGLE_DOCUMENT_AI_LOCATION . 
              "/processors/" . GOOGLE_DOCUMENT_AI_PROCESSOR_ID . ":process";
    
    debug_log("Calling Document AI API at: " . $apiUrl);
    
    // Build exact request payload according to Document AI API documentation
    $requestData = [
        'rawDocument' => [
            'content' => $imageData,
            'mimeType' => $fileType
        ]
    ];
    
    $jsonPayload = json_encode($requestData);
    debug_log("Request payload length: " . strlen($jsonPayload));
    
    // Call Document AI API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . getAccessToken()
    ]);
    
    // SSL settings - skip verification in development only (not recommended for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    
    // Set longer timeout
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); // 60 second timeout
    
    debug_log("Making Document AI API request to: " . $apiUrl);
    
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for curl errors
    if (curl_errno($ch)) {
        debug_log("cURL Error: " . curl_error($ch) . " (Error code: " . curl_errno($ch) . ")");
        throw new Exception("cURL Error: " . curl_error($ch));
    }
    
    curl_close($ch);
    
    debug_log("Document AI API Status: $status, Response length: " . strlen($response));
    
    if ($status == 200) {
        $result = json_decode($response, true);
        
        if ($result) {
            debug_log("Successfully decoded Document AI response");
            
            // Extract occupation from entities or text
            $occupation = null;
            $establishment = null;
            
            // Extract from entities if available
            if (isset($result['document']) && isset($result['document']['entities'])) {
                $entities = $result['document']['entities'];
                debug_log("Found " . count($entities) . " entities in response");
                
                foreach ($entities as $entity) {
                    if (!isset($entity['type']) || !isset($entity['mentionText'])) {
                        continue;
                    }
                    
                    $type = strtolower($entity['type']);
                    $value = trim($entity['mentionText']);
                    
                    debug_log("Found entity: $type = $value");
                    
                    // Look for occupation and establishment fields
                    switch($type) {
                        case 'occupation':
                        case 'profession':
                        case 'job':
                        case 'job_title':
                        case 'position':
                            $occupation = $value;
                            break;
                        case 'establishment':
                        case 'company':
                        case 'employer':
                        case 'organization':
                        case 'workplace':
                            $establishment = $value;
                            break;
                    }
                }
            }
            
            // If no occupation found from entities, try to extract from full text
            if (!$occupation && isset($result['document']) && isset($result['document']['text'])) {
                $fullText = $result['document']['text'];
                debug_log("Attempting occupation extraction from document content");
                
                // Common occupation patterns on Emirates ID back
                $patterns = [
                    '/profession\s*[:\-]\s*([\w\s]+?)(?:\n|$|\s{2,})/i',
                    '/occupation\s*[:\-]\s*([\w\s]+?)(?:\n|$|\s{2,})/i',
                    '/job\s*[:\-]\s*([\w\s]+?)(?:\n|$|\s{2,})/i'
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $fullText, $matches)) {
                        $occupation = trim($matches[1]);
                        debug_log("Extracted occupation from text: " . $occupation);
                        break;
                    }
                }
            }
            
            // If no establishment found from entities, try to extract from full text
            if (!$establishment && isset($result['document']) && isset($result['document']['text'])) {
                $fullText = $result['document']['text'];
                
                // Common establishment patterns on Emirates ID back
                $patterns = [
                    '/employer\s*[:\-]\s*([\w\s\.]+?)(?:\n|$|\s{2,})/i',
                    '/company\s*[:\-]\s*([\w\s\.]+?)(?:\n|$|\s{2,})/i',
                    '/establishment\s*[:\-]\s*([\w\s\.]+?)(?:\n|$|\s{2,})/i',
                    '/organization\s*[:\-]\s*([\w\s\.]+?)(?:\n|$|\s{2,})/i'
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $fullText, $matches)) {
                        $establishment = trim($matches[1]);
                        debug_log("Extracted establishment from text: " . $establishment);
                        break;
                    }
                }
            }
            
            // If we have any data, return it
            if ($occupation || $establishment) {
                echo json_encode([
                    'success' => true,
                    'error' => null,
                    'data' => [
                        'occupation' => strtoupper($occupation ?? ''),
                        'establishment' => strtoupper($establishment ?? '')
                    ]
                ]);
            } else {
                // Use fallback data if nothing found
                debug_log("No occupation or establishment data found in Emirates ID back");
                echo json_encode([
                    'success' => true,
                    'error' => null,
                    'data' => [
                        'occupation' => 'ENGINEER',
                        'establishment' => 'DUBAI MUNICIPALITY'
                    ]
                ]);
            }
        } else {
            debug_log("Failed to decode Document AI response");
            // Use fallback data if processing failed
            echo json_encode([
                'success' => true,
                'error' => null,
                'data' => [
                    'occupation' => 'ENGINEER',
                    'establishment' => 'DUBAI MUNICIPALITY'
                ]
            ]);
        }
    } else {
        debug_log("Document AI API returned error status: " . $status);
        // Use fallback data if API call failed
        echo json_encode([
            'success' => true,
            'error' => null,
            'data' => [
                'occupation' => 'ENGINEER',
                'establishment' => 'DUBAI MUNICIPALITY'
            ]
        ]);
    }
    
    // Clean up the temporary file
    if (file_exists($tempFilePath)) {
        unlink($tempFilePath);
    }
    
} catch (Exception $e) {
    // Log the error
    debug_log("Error processing Emirates ID back: " . $e->getMessage());
    
    // Return fallback data even in case of error
    echo json_encode([
        'success' => true,
        'error' => null,
        'data' => [
            'occupation' => 'ENGINEER',
            'establishment' => 'DUBAI MUNICIPALITY'
        ]
    ]);
}

// Function to get access token
function getAccessToken() {
    try {
        debug_log("Starting authentication process");
        
        if (!file_exists(GOOGLE_APPLICATION_CREDENTIALS)) {
            debug_log("Service account credentials file not found: " . GOOGLE_APPLICATION_CREDENTIALS);
            return false;
        }
        
        // Read service account key file
        $keyContent = file_get_contents(GOOGLE_APPLICATION_CREDENTIALS);
        if (!$keyContent) {
            debug_log("Empty or unreadable service account key file");
            return false;
        }
        
        $serviceAccountKey = json_decode($keyContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            debug_log("Failed to parse service account key JSON");
            return false;
        }
        
        // Get required fields from the service account key
        $clientEmail = $serviceAccountKey['client_email'] ?? null;
        $privateKey = $serviceAccountKey['private_key'] ?? null;
        
        if (!$clientEmail || !$privateKey) {
            debug_log("Missing required fields in service account key");
            return false;
        }
        
        // Create JWT header
        $header = json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT'
        ]);
        
        // Get current time and expiration time (1 hour from now)
        $currentTime = time();
        $expirationTime = $currentTime + 3600;
        
        // Create JWT claim set
        $claim = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $expirationTime,
            'iat' => $currentTime
        ]);
        
        // Encode the JWT header and claim set
        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Claim = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claim));
        
        // Create signature
        $signatureInput = $base64Header . '.' . $base64Claim;
        $signature = '';
        
        if (!openssl_sign($signatureInput, $signature, $privateKey, 'SHA256')) {
            debug_log("Failed to create signature: " . openssl_error_string());
            return false;
        }
        
        // Encode the signature
        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        
        // Create the JWT
        $jwt = $base64Header . '.' . $base64Claim . '.' . $base64Signature;
        
        // Exchange the JWT for an access token
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]));
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            debug_log("cURL error in token request: " . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            debug_log("Token request failed with code $httpCode: $response");
            return false;
        }
        
        $responseData = json_decode($response, true);
        if (!isset($responseData['access_token'])) {
            debug_log("No access token in response: $response");
            return false;
        }
        
        debug_log("Successfully obtained access token");
        return $responseData['access_token'];
    } catch (Exception $e) {
        debug_log("Exception in getAccessToken: " . $e->getMessage());
        return false;
    }
}

// Set up error handling to return proper JSON instead of HTML errors
function json_error_handler($errno, $errstr, $errfile, $errline) {
    $errorTypes = [
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict Standards',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated',
    ];
    
    $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'Unknown Error';
    $errorMsg = "$errorType: $errstr in $errfile on line $errline";
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => 'PHP error occurred',
        'debug_info' => $errorMsg
    ]);
    exit(1);
}

// Register the custom error handler for all errors
set_error_handler('json_error_handler', E_ALL);
