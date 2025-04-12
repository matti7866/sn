<?php
// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>CURL Extension Test</h1>";

// Check if curl extension is loaded
if (!extension_loaded('curl')) {
    echo "<p style='color: red'>ERROR: cURL extension is NOT loaded!</p>";
    exit;
} else {
    echo "<p style='color: green'>SUCCESS: cURL extension is loaded.</p>";
}

// Test basic curl functionality
echo "<h2>Testing basic cURL request:</h2>";
$ch = curl_init('https://httpbin.org/get');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    echo "<p style='color: red'>ERROR: cURL request failed: " . curl_error($ch) . "</p>";
} else {
    echo "<p style='color: green'>SUCCESS: cURL request worked!</p>";
    echo "<pre>" . htmlspecialchars(substr($response, 0, 200)) . "...</pre>";
}
curl_close($ch);

// Check service account file
echo "<h2>Testing Google service account credentials:</h2>";
$serviceAccountFile = __DIR__ . '/service-account-key.json';

if (!file_exists($serviceAccountFile)) {
    echo "<p style='color: red'>ERROR: Service account file does not exist at: $serviceAccountFile</p>";
} else {
    echo "<p style='color: green'>SUCCESS: Service account file exists.</p>";
    
    // Check file permissions
    $perms = fileperms($serviceAccountFile);
    $fileOwner = fileowner($serviceAccountFile);
    $fileGroup = filegroup($serviceAccountFile);
    echo "<p>File permissions: " . decoct($perms & 0777) . "</p>";
    echo "<p>File owner/group: $fileOwner/$fileGroup</p>";
    
    // Try to read the file
    if (!is_readable($serviceAccountFile)) {
        echo "<p style='color: red'>ERROR: Service account file is not readable!</p>";
    } else {
        echo "<p style='color: green'>SUCCESS: Service account file is readable.</p>";
        
        // Check if it's valid JSON
        $contents = file_get_contents($serviceAccountFile);
        $json = json_decode($contents, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<p style='color: red'>ERROR: Service account file is not valid JSON: " . json_last_error_msg() . "</p>";
        } else {
            echo "<p style='color: green'>SUCCESS: Service account file contains valid JSON.</p>";
            
            // Check for required keys
            $requiredKeys = ['type', 'project_id', 'private_key_id', 'private_key', 'client_email'];
            $missingKeys = [];
            
            foreach ($requiredKeys as $key) {
                if (!isset($json[$key]) || empty($json[$key])) {
                    $missingKeys[] = $key;
                }
            }
            
            if (!empty($missingKeys)) {
                echo "<p style='color: red'>ERROR: Service account file is missing required keys: " . implode(', ', $missingKeys) . "</p>";
            } else {
                echo "<p style='color: green'>SUCCESS: Service account file contains all required keys.</p>";
                echo "<p>Project ID: " . htmlspecialchars($json['project_id']) . "</p>";
                echo "<p>Client Email: " . htmlspecialchars($json['client_email']) . "</p>";
            }
        }
    }
}

echo "<h2>processPassport.php file check:</h2>";
$processorFile = __DIR__ . '/processPassport.php';

if (!file_exists($processorFile)) {
    echo "<p style='color: red'>ERROR: processPassport.php does not exist!</p>";
} else {
    echo "<p style='color: green'>SUCCESS: processPassport.php exists.</p>";
    
    // Check file permissions
    $perms = fileperms($processorFile);
    echo "<p>File permissions: " . decoct($perms & 0777) . "</p>";
    
    // Try to read the first few lines
    if (!is_readable($processorFile)) {
        echo "<p style='color: red'>ERROR: processPassport.php is not readable!</p>";
    } else {
        echo "<p style='color: green'>SUCCESS: processPassport.php is readable.</p>";
        
        // Check for common PHP errors
        $content = file_get_contents($processorFile);
        if (strpos($content, '<?php') === false) {
            echo "<p style='color: red'>WARNING: processPassport.php does not start with <?php tag!</p>";
        }
    }
}