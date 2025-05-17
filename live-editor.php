<?php
// Start output buffering to allow header redirects
ob_start();

include 'header.php';
?>
<title>Pixlr Professional Editor</title>
<?php
include 'nav.php';

// Check user authentication
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

// Load Pixlr API credentials
require_once 'config.php'; // Place in /www/wwwroot/sntravels/config.php

// Handle file uploads
$error = null;
if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
    $uploadDir = 'uploads';
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            $error = "Failed to create uploads directory.";
        }
    }

    if (!$error) {
        $uploadedFile = $_FILES['image'];
        $maxSize = 10 * 1024 * 1024; // 10MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if ($uploadedFile['size'] > $maxSize) {
            $error = "File size exceeds 10MB limit.";
        } elseif (!in_array($uploadedFile['type'], $allowedTypes)) {
            $error = "Only JPG, PNG, and GIF images are allowed.";
        } elseif ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
            $error = "Upload error: " . $uploadedFile['error'];
        } else {
            $filename = 'image_' . time() . '_' . uniqid() . '.' . pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);
            $target = $uploadDir . '/' . $filename;

            if (!move_uploaded_file($uploadedFile['tmp_name'], $target)) {
                $error = "Failed to save uploaded file.";
            } else {
                $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                           '://' . $_SERVER['HTTP_HOST'] . 
                           dirname($_SERVER['SCRIPT_NAME']);
                $imageUrl = rtrim($baseUrl, '/') . '/' . $target;
                $_SESSION['editor_image'] = $imageUrl;
                header("Location: live-editor.php");
                exit;
            }
        }
    }
}

// Get image to load (if any)
$selectedImage = isset($_SESSION['editor_image']) ? $_SESSION['editor_image'] : '';

// Generate JWT token for Pixlr API authentication
function generatePixlrJWT($imageUrl = '') {
    $payload = [
        'sub' => PIXLR_API_KEY,
        'mode' => 'http',
        'openUrl' => $imageUrl,
        'saveUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . 
                     '://' . $_SERVER['HTTP_HOST'] . '/save-image.php',
        'follow' => true,
        'settings' => [
            'referrer' => 'SN Travels',
            'accent' => 'blue',
            'workspace' => 'light',
            'blockOpen' => false,
            'exportFormats' => ['png', 'jpeg', 'webp']
        ],
        'exp' => time() + 3600 // Token expires in 1 hour
    ];

    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, PIXLR_API_SECRET, true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}
?>

<div class="container-fluid p-0">
    <!-- Overlay Upload Button -->
    <div class="upload-overlay">
        <form id="image-upload-form" action="live-editor.php" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="file" class="form-control-file" id="image-file" name="image" accept="image/*">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload"></i> Upload Image
                    </button>
                </div>
            </div>
            <small class="form-text text-muted">Supported formats: JPG, PNG, GIF (Max: 10MB)</small>
        </form>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-2">
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pixlr Editor -->
    <div id="pixlr-editor-container">
        <div id="loading-indicator" class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <iframe 
            id="pixlr-iframe" 
            src="https://pixlr.com/editor/?token=<?php echo urlencode(generatePixlrJWT($selectedImage)); ?>" 
            frameborder="0" 
            allow="clipboard-write; encrypted-media" 
            allowfullscreen>
        </iframe>
    </div>
</div>

<!-- Image Editor Results Modal -->
<div class="modal fade" id="editorResultModal" tabindex="-1" role="dialog" aria-labelledby="editorResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editorResultModalLabel">Your Edited Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img id="result-image" src="" class="img-fluid" alt="Edited Image">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-info" id="result-download" download="edited-image.jpg">
                    <i class="fa fa-download"></i> Download Image
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
body {
    margin: 0;
    overflow: hidden;
}

.container-fluid {
    padding: 0 !important;
    margin: 0 !important;
}

#pixlr-editor-container {
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1;
}

#pixlr-iframe {
    width: 100%;
    height: 100%;
    border: none;
    display: block;
}

.upload-overlay {
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 2;
    background: rgba(255, 255, 255, 0.9);
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.upload-overlay .input-group {
    max-width: 300px;
}

#loading-indicator {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    display: none;
}

/* Adjust sidenav and navbar spacing */
.navbar, .sidenav {
    margin: 0 !important;
    padding: 0 !important;
}
</style>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    // Show loading indicator while iframe loads
    $('#loading-indicator').show();
    $('#pixlr-iframe').on('load', function() {
        $('#loading-indicator').hide();
    });

    // Handle messages from Pixlr iframe
    window.addEventListener('message', function(event) {
        // Log all messages for debugging
        const logDir = '/www/wwwroot/sntravels/logs';
        const logFile = logDir + '/pixlr-messages.log';
        fetch('/log-message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: JSON.stringify({
                    origin: event.origin,
                    data: event.data,
                    timestamp: new Date().toISOString()
                })
            })
        });

        // Handle Pixlr messages
        if (event.origin.includes('pixlr.com')) {
            console.log('Pixlr message:', event.data);
            if (event.data && event.data.type === 'image' && event.data.url) {
                $('#result-image').attr('src', event.data.url);
                $('#result-download').attr('href', event.data.url);
                $('#editorResultModal').modal('show');
            }
        }
    });

    // Force download on button click
    $('#result-download').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const link = document.createElement('a');
            link.href = url;
            link.download = 'edited-image.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
});
</script>

<?php
include 'footer.php';
ob_end_flush();
?>