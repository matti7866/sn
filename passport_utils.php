<?php
/**
 * Passport utilities for MRZ reading using Google Document AI
 */

/**
 * Extract MRZ data from passport image using Google Document AI API
 * 
 * @param string $imagePath Path to the passport image
 * @return array Array of extracted passport data
 */
function extractMRZData($imagePath) {
    $data = [
        'passport_number' => '',
        'country_code' => '',
        'surname' => '',
        'given_names' => '',
        'nationality' => '',
        'dob' => '',
        'gender' => '',
        'expiry_date' => ''
    ];
    
    // Check if we have Google Document AI configured
    if (!defined('USE_GOOGLE_DOCUMENT_AI') || !USE_GOOGLE_DOCUMENT_AI) {
        error_log("Google Document AI not configured");
        return $data;
    }
    
    // Check required settings
    if (!defined('GOOGLE_DOCUMENT_AI_PROJECT_ID') || 
        !defined('GOOGLE_DOCUMENT_AI_LOCATION') || 
        !defined('GOOGLE_DOCUMENT_AI_PROCESSOR_ID') || 
        !defined('GOOGLE_APPLICATION_CREDENTIALS')) {
        error_log("Google Document AI settings not properly defined");
        return $data;
    }
    
    // Check if file exists
    if (!file_exists($imagePath)) {
        error_log("File not found at path: $imagePath");
        return $data;
    }
    
    // Check file size
    $fileSize = filesize($imagePath);
    if ($fileSize > 20 * 1024 * 1024) { // 20MB limit
        error_log("File too large: $fileSize bytes");
        return $data;
    }
    
    // Check image type and convert if needed
    $imageInfo = getimagesize($imagePath);
    if (!$imageInfo) {
        error_log("Not a valid image file: $imagePath");
        return $data;
    }
    
    error_log("Image type: " . $imageInfo['mime']);
    error_log("Image dimensions: " . $imageInfo[0] . "x" . $imageInfo[1]);
    
    // Always preprocess the image to enhance quality
    error_log("Preprocessing image to enhance MRZ visibility");
    
    // Get source image
    $srcImage = null;
    switch ($imageInfo['mime']) {
        case 'image/jpeg':
            $srcImage = imagecreatefromjpeg($imagePath);
            break;
        case 'image/png':
            $srcImage = imagecreatefrompng($imagePath);
            break;
        case 'image/gif':
            $srcImage = imagecreatefromgif($imagePath);
            break;
        case 'image/bmp':
            $srcImage = imagecreatefrombmp($imagePath);
            break;
        case 'image/webp':
            $srcImage = imagecreatefromwebp($imagePath);
            break;
        default:
            error_log("Unsupported image format: " . $imageInfo['mime']);
            return $data;
    }
    
    if (!$srcImage) {
        error_log("Failed to create source image");
        return $data;
    }
    
    // Create temporary image file with enhanced quality
    $customTempDir = dirname(__FILE__) . '/residence/temp/';
    
    // Ensure temp directory exists
    if (!file_exists($customTempDir)) {
        if (!mkdir($customTempDir, 0777, true)) {
            error_log("Failed to create custom temp directory: " . $customTempDir);
            return $data;
        }
        chmod($customTempDir, 0777);
    }
    
    $tempImagePath = $customTempDir . uniqid() . '.jpg';
    
    // Apply image enhancements for better MRZ detection
    // Increase contrast and brightness
    $result = @imagefilter($srcImage, IMG_FILTER_CONTRAST, -20);
    if (!$result) {
        error_log("Failed to apply contrast filter");
    }
    
    $result = @imagefilter($srcImage, IMG_FILTER_BRIGHTNESS, 15);
    if (!$result) {
        error_log("Failed to apply brightness filter");
    }
    
    // Add sharpen filter to improve text clarity
    $sharpen = array(
        array(-1, -1, -1),
        array(-1, 16, -1),
        array(-1, -1, -1)
    );
    $divisor = array_sum(array_map('array_sum', $sharpen));
    imageconvolution($srcImage, $sharpen, $divisor, 0);
    
    // Apply grayscale to improve OCR text recognition
    imagefilter($srcImage, IMG_FILTER_GRAYSCALE);
    
    $result = @imagefilter($srcImage, IMG_FILTER_SMOOTH, 0);
    if (!$result) {
        error_log("Failed to apply smoothing filter");
    }
    
    // Save enhanced image - use @ to suppress errors, we'll handle them
    if (!@imagejpeg($srcImage, $tempImagePath, 100)) { // Use maximum quality
        error_log("Failed to save enhanced image to: " . $tempImagePath);
        imagedestroy($srcImage);
        return $data;
    }
    
    imagedestroy($srcImage);
    error_log("Image enhanced and saved to: $tempImagePath");
    
    error_log("Attempting to extract passport data using Google Document AI: $tempImagePath");
    
    try {
        // Set GOOGLE_APPLICATION_CREDENTIALS for authentication
        putenv('GOOGLE_APPLICATION_CREDENTIALS=' . GOOGLE_APPLICATION_CREDENTIALS);
        
        // Read the enhanced image file as base64
        $imageContent = file_get_contents($tempImagePath);
        $base64Image = base64_encode($imageContent);
        
        // Build Document AI request payload
        $requestData = [
            'name' => 'projects/' . GOOGLE_DOCUMENT_AI_PROJECT_ID . '/locations/' . GOOGLE_DOCUMENT_AI_LOCATION . '/processors/' . GOOGLE_DOCUMENT_AI_PROCESSOR_ID,
            'rawDocument' => [
                'content' => $base64Image,
                'mimeType' => 'image/jpeg'
            ]
        ];
        
        $jsonPayload = json_encode($requestData);
        
        // Build API request URL
        $apiUrl = "https://" . GOOGLE_DOCUMENT_AI_LOCATION . "-documentai.googleapis.com/v1/" . 
                  "projects/" . GOOGLE_DOCUMENT_AI_PROJECT_ID . "/locations/" . GOOGLE_DOCUMENT_AI_LOCATION . 
                  "/processors/" . GOOGLE_DOCUMENT_AI_PROCESSOR_ID . ":process";
        
        // Get access token using service account credentials
        $accessToken = getGoogleAccessToken();
        if (!$accessToken) {
            error_log("Failed to get Google access token");
            return $data;
        }
        
        // Set up cURL request to Document AI API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 30 second timeout
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonPayload),
            'Authorization: Bearer ' . $accessToken
        ]);
        
        // Enable verbose output for debugging
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'w+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);
        
        error_log("Sending request to Google Document AI API...");
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        // Log verbose info
        rewind($verbose);
        $verboseLog = stream_get_contents($verbose);
        error_log("Curl verbose output: " . $verboseLog);
        
        if (curl_errno($ch)) {
            error_log("cURL Error: " . curl_error($ch));
            throw new Exception("cURL Error: " . curl_error($ch));
        }
        
        curl_close($ch);
        
        error_log("Google Document AI API Status: $status, Response length: " . strlen($response));
        
        // Save response to debug file if in debug mode
        if (defined('DEBUG_MODE') && DEBUG_MODE) {
            $debugDir = dirname(__FILE__) . '/debug/';
            if (!file_exists($debugDir)) {
                mkdir($debugDir, 0777, true);
            }
            file_put_contents($debugDir . 'docai_response_' . time() . '.json', $response);
            error_log("Saved debug response to file");
        }
        
        if ($status == 200) {
            $result = json_decode($response, true);
            
            if ($result) {
                error_log("Document AI response successfully decoded");
                // Parse the Document AI response to extract passport fields
                $data = parseDocumentAiResponse($result);
                
                // Check if we got any meaningful data
                $hasData = false;
                foreach ($data as $key => $value) {
                    if (!empty($value) && $key != 'extraction_error') {
                        $hasData = true;
                        break;
                    }
                }
                
                // Use test data if fallback is enabled and no data was extracted
                if (!$hasData && defined('USE_FALLBACK_TEST_DATA') && USE_FALLBACK_TEST_DATA) {
                    error_log("No data extracted, using test data");
                    $data = [
                        'passport_number' => 'AB1234567',
                        'country_code' => 'USA',
                        'surname' => 'DOE',
                        'given_names' => 'JOHN',
                        'nationality' => 'USA',
                        'dob' => '01/01/1980',
                        'gender' => 'M',
                        'expiry_date' => '01/01/2030',
                        'name' => 'JOHN DOE',
                        'date_of_birth' => '01/01/1980'
                    ];
                }
            } else {
                error_log("Failed to decode Document AI response: " . json_last_error_msg());
                error_log("Raw response: " . substr($response, 0, 1000) . "...");
            }
        } else {
            error_log("Document AI API returned error status: " . $status);
            error_log("Response: " . $response);
        }
        
        // Clean up temporary file
        @unlink($tempImagePath);
        
        return $data;
    } catch (Exception $e) {
        error_log("Exception during Document AI processing: " . $e->getMessage());
        return $data;
    }
}

/**
 * Parse Google Document AI response to extract passport fields
 * 
 * @param array $response The Document AI API response
 * @return array Structured passport data
 */
function parseDocumentAiResponse($response) {
    $data = [
        'passport_number' => '',
        'country_code' => '',
        'surname' => '',
        'given_names' => '',
        'nationality' => '',
        'dob' => '',
        'gender' => '',
        'expiry_date' => '',
        'name' => ''
    ];
    
    try {
        // Log the complete response for debugging
        error_log("Document AI complete response: " . json_encode($response));
        
        // Check if we have raw text (OCR) from Document AI
        if (isset($response['document']) && isset($response['document']['text'])) {
            // Always extract raw text first, regardless of whether entities are present
            $fullText = $response['document']['text'];
            error_log("Document AI raw OCR text: " . substr($fullText, 0, 500));
            
            // Define more comprehensive patterns to match common passport field formats
            $patterns = [
                'passport_number' => [
                    '/passport\s*(?:no|num|number|#|№)[\.:\s]*\s*([A-Z0-9]{5,12})[\s\n\.]/i',
                    '/document\s*(?:no|num|number|#|№)[\.:\s]*\s*([A-Z0-9]{5,12})[\s\n\.]/i',
                    '/(?<!\w)([A-Z][A-Z0-9]{6,9})(?!\w)/i', // Standalone passport numbers (common format)
                    '/№\s*([A-Z0-9]{5,12})[\s\n\.]/i'
                ],
                'name' => [
                    '/name[\.:\s]*([A-Z\s]+(?:[A-Z]{2,}\s*)+)/i',
                    '/surname[\.:\s]*([A-Z]+)[\.:\s]*given\s*names[\.:\s]*([A-Z\s]+)/i',
                    '/given\s*names?[\.:\s]*([A-Z\s]+)[\.:\s]*surname[\.:\s]*([A-Z]+)/i'
                ],
                'nationality' => [
                    '/nationality[\.:\s]*([A-Z]{2,}(?:\s[A-Z]+)*)/i',
                    '/citizenship[\.:\s]*([A-Z]{2,}(?:\s[A-Z]+)*)/i',
                    '/national[\.:\s]*([A-Z]{2,}(?:\s[A-Z]+)*)/i'
                ],
                'country_code' => [
                    '/country\s*code[\.:\s]*([A-Z]{2,3})/i',
                    '/issuing\s*(?:country|state)[\.:\s]*([A-Z]{2,}(?:\s[A-Z]+)*)/i'
                ],
                'dob' => [
                    '/(?:birth|born)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
                    '/date\s*of\s*birth[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
                    '/DOB[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
                    '/(?:birth|born)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}\s*[A-Za-z]{3,}\s*\d{2,4})/i', // Format: 01 JAN 1980
                ],
                'gender' => [
                    '/(?:gender|sex)[\.:\s]*([MF]|MALE|FEMALE)/i',
                    '/(?:gender|sex)[\.:\s]*(M|F)/i'
                ],
                'expiry_date' => [
                    '/(?:expiry|expiration)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
                    '/(?:valid|expiry|expires)[\.:\s]*(?:until|to|date)?[\.:\s]*(\d{1,2}[\/\.\-]\d{1,2}[\/\.\-]\d{2,4})/i',
                    '/(?:expiry|expiration)[\.:\s]*(?:date)?[\.:\s]*(\d{1,2}\s*[A-Za-z]{3,}\s*\d{2,4})/i' // Format: 01 JAN 2030
                ]
            ];
            
            // Try to extract data using regular expressions
            foreach ($patterns as $field => $fieldPatterns) {
                foreach ($fieldPatterns as $pattern) {
                    if (empty($data[$field]) && preg_match($pattern, $fullText, $matches)) {
                        if ($field == 'name' && count($matches) > 2) {
                            // Handle combined surname/given names pattern
                            $data['surname'] = trim($matches[1]);
                            $data['given_names'] = trim($matches[2]);
                            $data['name'] = trim($matches[2] . ' ' . $matches[1]);
                        } else {
                            $data[$field] = trim($matches[1]);
                        }
                        error_log("Extracted $field from OCR text using pattern $pattern: " . $data[$field]);
                    }
                }
            }
            
            // Special case: Try to extract MRZ data if visible
            if (preg_match('/[A-Z0-9<]{30,44}[\r\n\s]+[A-Z0-9<]{30,44}/i', $fullText, $matches)) {
                error_log("Found potential MRZ data: " . $matches[0]);
                $mrzData = parseMRZData($matches[0]);
                
                // Only use MRZ data for fields that aren't already populated
                foreach ($mrzData as $field => $value) {
                    if (empty($data[$field]) && !empty($value)) {
                        $data[$field] = $value;
                        error_log("Using MRZ data for $field: $value");
                    }
                }
            }
        }
        
        // Process structured entity data if available
        if (isset($response['document']) && isset($response['document']['entities'])) {
            $entities = $response['document']['entities'];
            error_log("Processing " . count($entities) . " structured entities");
            
            // Map of field names to our data structure
            $fieldMap = [
                // Passport number fields
                'document_id' => 'passport_number',
                'passport_number' => 'passport_number',
                'id_number' => 'passport_number',
                'identity_document.identity_document_number' => 'passport_number',
                'document_number' => 'passport_number',
                'number' => 'passport_number',
                
                // Country fields
                'country' => 'country_code',
                'issuing_country' => 'country_code',
                'identity_document.issuing_country' => 'country_code',
                'country_of_issue' => 'country_code',
                
                // Name fields
                'surname' => 'surname',
                'family_name' => 'surname',
                'last_name' => 'surname',
                'identity_document.surname' => 'surname',
                'given_names' => 'given_names',
                'first_name' => 'given_names',
                'given_name' => 'given_names',
                'identity_document.given_names' => 'given_names',
                'name' => 'name',
                'full_name' => 'name',
                'identity_document.name' => 'name',
                
                // Nationality
                'nationality' => 'nationality',
                'citizenship' => 'nationality',
                'identity_document.nationality' => 'nationality',
                
                // DOB
                'birth_date' => 'dob',
                'date_of_birth' => 'dob',
                'dob' => 'dob',
                'identity_document.date_of_birth' => 'dob',
                
                // Gender
                'sex' => 'gender',
                'gender' => 'gender',
                'identity_document.sex' => 'gender',
                
                // Expiry date
                'expiry_date' => 'expiry_date',
                'expiration_date' => 'expiry_date',
                'date_of_expiry' => 'expiry_date',
                'identity_document.expiration_date' => 'expiry_date',
                'identity_document.date_of_expiry' => 'expiry_date'
            ];
            
            // Process entities
            foreach ($entities as $entity) {
                if (!isset($entity['type']) || !isset($entity['mentionText'])) {
                    continue;
                }
                
                $type = strtolower($entity['type']);
                $value = trim($entity['mentionText']);
                
                if (empty($value)) {
                    continue;
                }
                
                error_log("Entity: $type = $value");
                
                // Check if this entity type is in our map
                if (isset($fieldMap[$type])) {
                    $targetField = $fieldMap[$type];
                    
                    // Only update if empty or the new value is better
                    if (empty($data[$targetField]) || strlen($value) > strlen($data[$targetField])) {
                        $data[$targetField] = $value;
                        error_log("Set $targetField from entity: $value");
                    }
                } else {
                    // Log unknown entity types for future reference
                    error_log("Unknown entity type: $type with value: $value");
                }
            }
        }
        
        // Process document pages to look for text blocks that might contain passport data
        if (isset($response['document']) && isset($response['document']['pages'])) {
            foreach ($response['document']['pages'] as $page) {
                if (isset($page['blocks'])) {
                    foreach ($page['blocks'] as $block) {
                        if (isset($block['layout']) && isset($block['layout']['textAnchor']) && 
                            isset($block['layout']['textAnchor']['textSegments'])) {
                            
                            $blockText = '';
                            foreach ($block['layout']['textAnchor']['textSegments'] as $segment) {
                                if (isset($segment['startIndex']) && isset($segment['endIndex']) && 
                                    isset($response['document']['text'])) {
                                    $blockText .= substr($response['document']['text'], 
                                                        $segment['startIndex'], 
                                                        $segment['endIndex'] - $segment['startIndex']);
                                }
                            }
                            
                            if (!empty($blockText)) {
                                error_log("Processing text block: " . substr($blockText, 0, 50));
                                // Try to identify key-value patterns in this block
                                if (preg_match('/passport\s+no[\.:\s]*\s*([A-Z0-9]{5,12})/i', $blockText, $matches)) {
                                    if (empty($data['passport_number'])) {
                                        $data['passport_number'] = $matches[1];
                                        error_log("Found passport number in text block: " . $data['passport_number']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Post-processing and cleanup
        
        // If we have surname and given names but no full name, construct it
        if (empty($data['name']) && (!empty($data['surname']) || !empty($data['given_names']))) {
            if (!empty($data['surname']) && !empty($data['given_names'])) {
                $data['name'] = $data['given_names'] . ' ' . $data['surname'];
            } else {
                $data['name'] = $data['surname'] . $data['given_names'];
            }
            error_log("Constructed name from parts: " . $data['name']);
        }
        
        // If we don't have nationality but have country_code, use that
        if (empty($data['nationality']) && !empty($data['country_code'])) {
            $data['nationality'] = $data['country_code'];
            error_log("Using country code as nationality: " . $data['nationality']);
        }
        
        // Format gender consistently (M/F)
        if (!empty($data['gender'])) {
            $gender = strtoupper(trim($data['gender']));
            if ($gender == 'MALE' || $gender == 'M') {
                $data['gender'] = 'M';
            } else if ($gender == 'FEMALE' || $gender == 'F') {
                $data['gender'] = 'F';
            }
            error_log("Normalized gender: " . $data['gender']);
        }
        
        // Try to clean and format dates consistently
        foreach (['dob', 'expiry_date'] as $dateField) {
            if (!empty($data[$dateField])) {
                $data[$dateField] = cleanDateFormat($data[$dateField]);
            }
        }
        
        // For date of birth compatibility
        if (!empty($data['dob']) && empty($data['date_of_birth'])) {
            $data['date_of_birth'] = $data['dob'];
        }
        
        // Log the final extracted data
        error_log("Final extracted passport data: " . json_encode($data));
        return $data;
    } catch (Exception $e) {
        error_log("Error parsing Document AI response: " . $e->getMessage());
        return $data;
    }
}

/**
 * Clean and standardize date formats
 * 
 * @param string $dateString The date string to format
 * @return string Formatted date
 */
function cleanDateFormat($dateString) {
    // Remove any extra spaces
    $dateString = trim(preg_replace('/\s+/', ' ', $dateString));
    
    // Try to parse date in common formats
    $formats = [
        'd/m/Y', 'm/d/Y', 'Y/m/d',
        'd-m-Y', 'm-d-Y', 'Y-m-d',
        'd.m.Y', 'm.d.Y', 'Y.m.d',
        'j F Y', 'F j Y', 'Y F j', // e.g., "10 January 2020"
        'j M Y', 'M j Y', 'Y M j'  // e.g., "10 Jan 2020"
    ];
    
    foreach ($formats as $format) {
        $date = \DateTime::createFromFormat($format, $dateString);
        if ($date !== false) {
            // Convert to a standard format (MM/DD/YYYY)
            return $date->format('m/d/Y');
        }
    }
    
    // If we couldn't parse it, return as is
    return $dateString;
}

/**
 * Parse MRZ (Machine Readable Zone) data from passport
 * 
 * @param string $mrzText The MRZ text (two lines)
 * @return array Extracted passport data
 */
function parseMRZData($mrzText) {
    $data = [
        'passport_number' => '',
        'country_code' => '',
        'surname' => '',
        'given_names' => '',
        'nationality' => '',
        'dob' => '',
        'gender' => '',
        'expiry_date' => ''
    ];
    
    // Clean up the MRZ text - remove spaces, newlines, etc.
    $mrzText = preg_replace('/[\s\r\n]+/', "\n", trim($mrzText));
    $lines = explode("\n", $mrzText);
    
    if (count($lines) >= 2) {
        $line1 = $lines[0];
        $line2 = $lines[1];
        
        // Standard MRZ format for passport:
        // Line 1: P<ISSUING_COUNTRY<SURNAME<<GIVEN_NAMES
        // Line 2: PASSPORT_NUMBER<NATIONALITY<DOB<GENDER<EXPIRY_DATE
        
        // Extract country code (usually first 3 chars after P in line 1)
        if (strlen($line1) > 2 && substr($line1, 0, 1) == 'P') {
            $data['country_code'] = substr($line1, 2, 3);
            $data['country_code'] = str_replace('<', '', $data['country_code']);
        }
        
        // Extract names
        $namePart = substr($line1, 5);
        $nameParts = explode('<<', $namePart, 2);
        if (count($nameParts) >= 2) {
            $data['surname'] = str_replace('<', ' ', $nameParts[0]);
            $data['given_names'] = str_replace('<', ' ', $nameParts[1]);
            $data['name'] = trim($data['given_names'] . ' ' . $data['surname']);
        }
        
        // Extract data from line 2
        if (strlen($line2) >= 28) {
            // Passport number (positions 0-8)
            $data['passport_number'] = substr($line2, 0, 9);
            $data['passport_number'] = str_replace('<', '', $data['passport_number']);
            
            // Nationality (positions 10-12)
            $data['nationality'] = substr($line2, 10, 3);
            $data['nationality'] = str_replace('<', '', $data['nationality']);
            
            // Date of birth (positions 13-18) in format YYMMDD
            $dobYY = substr($line2, 13, 2);
            $dobMM = substr($line2, 15, 2);
            $dobDD = substr($line2, 17, 2);
            // Assume 19xx for years > current year, 20xx otherwise
            $century = (intval($dobYY) > date('y')) ? '19' : '20';
            $data['dob'] = $dobMM . '/' . $dobDD . '/' . $century . $dobYY;
            
            // Gender (position 20)
            $data['gender'] = substr($line2, 20, 1);
            
            // Expiry date (positions 21-26) in format YYMMDD
            $expYY = substr($line2, 21, 2);
            $expMM = substr($line2, 23, 2);
            $expDD = substr($line2, 25, 2);
            // Assume 20xx for expiry dates
            $data['expiry_date'] = $expMM . '/' . $expDD . '/20' . $expYY;
        }
    }
    
    return $data;
}

/**
 * Get Google OAuth access token from service account credentials
 * 
 * @return string|false Access token or false on failure
 */
function getGoogleAccessToken() {
    try {
        if (!file_exists(GOOGLE_APPLICATION_CREDENTIALS)) {
            error_log("Service account credentials file not found: " . GOOGLE_APPLICATION_CREDENTIALS);
            return false;
        }
        
        // Read service account key file
        $serviceAccountKey = json_decode(file_get_contents(GOOGLE_APPLICATION_CREDENTIALS), true);
        
        if (!$serviceAccountKey) {
            error_log("Failed to parse service account key file");
            return false;
        }
        
        // Check required fields
        if (!isset($serviceAccountKey['client_email']) || !isset($serviceAccountKey['private_key'])) {
            error_log("Service account key file missing required fields");
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
            error_log("Failed to create JWT signature");
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
        
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status != 200) {
            error_log("Failed to get access token. Status: $status, Response: $response");
            return false;
        }
        
        $result = json_decode($response, true);
        if (!isset($result['access_token'])) {
            error_log("Access token not found in response: " . $response);
            return false;
        }
        
        error_log("Successfully obtained Google access token");
        return $result['access_token'];
    } catch (Exception $e) {
        error_log("Exception getting access token: " . $e->getMessage());
        return false;
    }
} 