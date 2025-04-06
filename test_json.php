<?php
$file = '/www/wwwroot/sntravels/client_secret.json';
if (!file_exists($file)) {
    die("File not found: $file");
}
$json = file_get_contents($file);
$data = json_decode($json, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON Error: " . json_last_error_msg());
}
echo "JSON is valid. Contents:\n";
print_r($data);
exit;
?>