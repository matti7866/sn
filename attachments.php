<?php include 'header.php' ?>
<title>File Attachments - SN Travel & Tours</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';

// All permissions enabled by default - no permission checks
$select = 1;
$insert = 1;
$update = 1;
$delete = 1;
?>

<!-- Custom CSS -->
<style>
    .dropzone {
        border: 2px dashed #007bff;
        border-radius: 5px;
        padding: 25px;
        text-align: center;
        background: #f8f9fa;
        transition: background 0.3s ease;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        cursor: pointer;
    }
    .dropzone:hover, .dropzone.dragover {
        background: #e9ecef;
    }
    .file-item {
        display: flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background: white;
        transition: transform 0.2s ease;
    }
    .file-item:hover {
        transform: translateX(5px);
    }
    .file-icon {
        font-size: 24px;
        margin-right: 15px;
    }
    .file-info {
        flex-grow: 1;
    }
    .file-name {
        font-weight: bold;
        margin-bottom: 3px;
    }
    .file-meta {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .file-actions {
        margin-left: 10px;
    }
    .upload-progress {
        height: 10px;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .hidden {
        display: none;
    }
    /* File type colors */
    .file-pdf { color: #e53935; }
    .file-image { color: #43a047; }
    .file-word { color: #1565c0; }
    .file-excel { color: #2e7d32; }
    .file-archive { color: #6d4c41; }
    .file-generic { color: #757575; }
    .file-success { color: #28a745; }
    .file-error { color: #dc3545; }
    
    /* Grid layout for file display */
    .files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .file-card {
        display: flex;
        flex-direction: column;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: white;
        position: relative;
    }
    
    .file-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .file-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 5;
        transform: scale(1.3);
    }
    
    .multi-select-actions {
        display: none;
        position: sticky;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 15px;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        margin-top: 20px;
        text-align: center;
    }
    
    .toggle-select-all {
        margin-right: 15px;
    }
    
    .file-thumbnail-large {
        height: 150px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .file-thumbnail-large img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .file-thumbnail-large i {
        font-size: 64px;
    }
    
    .file-card-body {
        padding: 12px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .file-card-name {
        font-weight: bold;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .file-card-meta {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 8px;
    }
    
    .file-card-actions {
        display: flex;
        justify-content: space-between;
        margin-top: auto;
    }
    
    /* New styles for file preview */
    .file-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }
    .file-preview-placeholder {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
        border-radius: 4px;
        background-color: #f8f9fa;
    }
    #preview-modal .modal-body {
        text-align: center;
        max-height: 70vh;
        overflow: auto;
    }
    #preview-modal img {
        max-width: 100%;
    }
    #preview-modal .pdf-preview {
        width: 100%;
        height: 60vh;
    }
    #preview-modal .preview-not-available {
        padding: 30px;
        color: #6c757d;
    }
    
    /* PDF thumbnail styles */
    .pdf-thumbnail-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pdf-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(248, 249, 250, 0.7);
        z-index: 1;
    }
    
    .pdf-loading i {
        font-size: 2rem;
        color: #007bff;
    }
    
    .pdf-thumbnail {
        max-width: 100%;
        max-height: 100%;
    }
    
    /* Folder styles */
    .folder-list {
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        background-color: #f8f9fa;
    }
    
    /* Highlight for new files */
    @keyframes highlightPulse {
        0% { box-shadow: 0 0 0 0 rgba(23, 162, 184, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(23, 162, 184, 0); }
        100% { box-shadow: 0 0 0 0 rgba(23, 162, 184, 0); }
    }
    
    .highlight-new {
        animation: highlightPulse 2s ease-in-out;
        border: 1px solid #17a2b8;
        position: relative;
    }
    
    .highlight-new::before {
        content: "NEW";
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #17a2b8;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: bold;
        z-index: 5;
    }
    
    .folder-item {
        display: inline-block;
        margin-right: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    
    .folder-btn {
        display: flex;
        align-items: center;
        padding: 8px 15px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .folder-btn:hover {
        background-color: #f0f0f0;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .folder-btn.active {
        background-color: #e9ecef;
        border-color: #007bff;
    }
    
    .folder-icon {
        color: #ffc107;
        margin-right: 8px;
        font-size: 18px;
    }
    
    /* Context menu styles */
    .folder-context-menu {
        position: absolute;
        display: none;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        z-index: 1000;
        min-width: 150px;
        padding: 5px 0;
    }
    
    .folder-context-menu-item {
        padding: 8px 15px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .folder-context-menu-item:hover {
        background-color: #f5f5f5;
    }
    
    .folder-context-menu-item.danger {
        color: #dc3545;
    }
    
    .folder-context-menu-item.danger:hover {
        background-color: #ffeaea;
    }
    
    .folder-context-menu-separator {
        height: 1px;
        background-color: #e9e9e9;
        margin: 5px 0;
    }
    
    .breadcrumb-item a {
        cursor: pointer;
    }
    
    #new-folder-form {
        display: none;
        margin-top: 10px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 5px;
    }
    
    .container-fluid {
        padding: 0; /* Remove container padding */
    }
    
    /* Hide only folder item actions, not the main folder controls */
    .folder-item .folder-actions {
        display: none; /* Hide the hover actions completely */
    }
    
    /* Make sure the main folder-actions container is visible */
    .folder-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    /* Panel adjustments */
    .panel {
        margin-bottom: 0;
        border-radius: 0;
    }
    
    .panel-heading {
        padding: 10px 15px;
    }
    
    /* Folder sharing styles */
    .share-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 8px;
    }
    
    .share-item-info {
        display: flex;
        align-items: center;
    }
    
    .share-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #6c757d;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-right: 10px;
    }
    
    .share-name {
        font-weight: 500;
    }
    
    .share-permission {
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .share-actions .btn {
        padding: 2px 6px;
        font-size: 0.8rem;
    }
    
    .shared-folder-indicator {
        position: absolute;
        top: -4px;
        right: -4px;
        color: #007bff;
        font-size: 12px;
        background: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
    }
    #ocr-fields-table tr:hover {
        background-color: #f8f9fa;
    }
    #ocr-fields-table td, #ocr-fields-table th {
        vertical-align: middle;
        padding: 0.5rem;
    }
    .copy-field-btn {
        transition: all 0.2s;
    }
    .copy-field-btn:hover {
        transform: scale(1.1);
    }
</style>

<div class="container-fluid">
    <?php if($insert == 1): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fas fa-upload"></i> Upload New Files</h4>
                </div>
                <div class="panel-body">
                    <div id="dropzone" class="dropzone">
                        <i class="fas fa-cloud-upload-alt mb-3" style="font-size: 48px; color: #007bff;"></i>
                        <h4>Drag & Drop Files Here</h4>
                        <p class="text-muted">or</p>
                        <button id="browse-files" class="btn btn-outline-primary">Browse Files</button>
                        <input type="file" id="file-input" multiple class="hidden">
                    </div>
                    
                    <div id="file-queue" class="mt-4">
                        <!-- Files being uploaded will appear here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fas fa-file-alt"></i> Your Attachments</h4>
                </div>
                <div class="panel-body">
                    <!-- Folder Navigation Breadcrumbs -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb" id="folder-breadcrumb">
                            <li class="breadcrumb-item"><a data-folder="root">Home</a></li>
                            <!-- Additional breadcrumbs will be added dynamically -->
                        </ol>
                    </nav>
                    
                    <!-- Folder Actions -->
                    <div class="folder-actions">
                        <div>
                            <button id="new-folder-btn" class="btn btn-sm btn-primary">
                                <i class="fas fa-folder-plus"></i> New Folder
                            </button>
                            
                            <!-- New Folder Form -->
                            <div id="new-folder-form">
                                <div class="input-group">
                                    <input type="text" id="folder-name" class="form-control" placeholder="Folder Name">
                                    <div class="input-group-append">
                                        <button id="create-folder-btn" class="btn btn-success">Create</button>
                                        <button id="cancel-folder-btn" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="search-container">
                            <div class="form-group mb-3">
                                <input type="text" id="search-files" class="form-control" placeholder="Search files...">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Folder List -->
                    <div class="folder-list" id="folder-list">
                        <!-- Folders will be loaded here -->
                        <div class="text-center py-2">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span class="ml-2">Loading folders...</span>
                        </div>
                    </div>
                    
                    <?php if($delete == 1): ?>
                    <!-- Multi-select Actions -->
                    <div id="multi-select-actions" class="multi-select-actions">
                        <div class="container">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input toggle-select-all" type="checkbox" id="selectAllFiles">
                                        <label class="form-check-label" for="selectAllFiles">Select All</label>
                                    </div>
                                    <span id="selected-count" class="badge bg-primary">0</span> files selected
                                </div>
                                <div>
                                    <button id="delete-selected" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div id="file-list" class="files-grid">
                        <!-- Files will be loaded here via AJAX -->
                        <div class="text-center py-5 w-100">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p class="mt-2">Loading your files...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="preview-content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="preview-download-btn" href="#" class="btn btn-primary">Download</a>
            </div>
        </div>
    </div>
</div>

<!-- OCR Results Modal -->
<div class="modal fade" id="ocr-modal" tabindex="-1" role="dialog" aria-labelledby="ocrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ocrModalLabel">OCR Text Extraction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="ocr-loading" class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Processing OCR...</span>
                    </div>
                    <p class="mt-2">Extracting text from document...</p>
                </div>
                
                <!-- Structured Fields Section -->
                <div id="ocr-structured-fields" class="mb-3" style="display: none;">
                    <h6 class="mb-2"><i class="fas fa-clipboard-list"></i> Extracted Fields</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover">
                            <tbody id="ocr-fields-table">
                                <!-- Fields will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div id="ocr-content" class="p-3 border rounded bg-light" style="max-height: 400px; overflow-y: auto; display: none;"></div>
                <div id="ocr-error" class="alert alert-danger mt-3" style="display: none;"></div>
                <div id="ocr-debug" class="mt-3 p-2 border rounded bg-light" style="font-size: 0.8rem; display: none;">
                    <div class="mb-2"><strong>Debugging Information:</strong></div>
                    <pre id="ocr-debug-content" style="max-height: 200px; overflow-y: auto;"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="copy-ocr-text" class="btn btn-primary">Copy Text</button>
                <button type="button" id="toggle-debug" class="btn btn-outline-info">Show Debug Info</button>
            </div>
        </div>
    </div>
</div>

<!-- Rename Folder Modal -->
<div class="modal fade" id="rename-folder-modal" tabindex="-1" role="dialog" aria-labelledby="renameFolderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameFolderLabel">Rename Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="rename-folder-error" class="alert alert-danger d-none"></div>
                <form id="rename-folder-form">
                    <input type="hidden" id="rename-folder-id">
                    <div class="form-group">
                        <label for="rename-folder-name">New Folder Name</label>
                        <input type="text" class="form-control" id="rename-folder-name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="save-folder-name" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Folder Confirmation Modal -->
<div class="modal fade" id="delete-folder-modal" tabindex="-1" role="dialog" aria-labelledby="deleteFolderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFolderLabel">Delete Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the folder "<span id="delete-folder-name"></span>"?</p>
                <div id="delete-folder-warning" class="alert alert-warning d-none">
                    <strong>Warning:</strong> This folder contains <span id="file-count"></span> files. 
                    <span class="text-danger">All files in this folder will be permanently deleted.</span>
                </div>
                <div id="delete-folder-error" class="alert alert-danger d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirm-delete-folder" class="btn btn-danger">Delete Folder</button>
            </div>
        </div>
    </div>
</div>

<!-- Share Folder Modal -->
<div class="modal fade" id="share-folder-modal" tabindex="-1" role="dialog" aria-labelledby="shareFolderLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareFolderLabel">Share Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div id="share-folder-error" class="alert alert-danger d-none"></div>
                <p>Share folder "<span id="share-folder-name"></span>" with other staff members:</p>
                
                <form id="share-folder-form">
                    <input type="hidden" id="share-folder-id">
                    
                    <div class="form-group mb-3">
                        <label for="share-staff-select">Select Staff Member</label>
                        <select class="form-control" id="share-staff-select">
                            <option value="">Loading staff members...</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Permission Level</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="share-permission" id="permission-view" value="view" checked>
                            <label class="form-check-label" for="permission-view">
                                View Only (can view and download files)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="share-permission" id="permission-edit" value="edit">
                            <label class="form-check-label" for="permission-edit">
                                Edit (can add/remove files and rename folder)
                            </label>
                        </div>
                    </div>
                    
                    <button type="button" id="add-share-btn" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Share
                    </button>
                </form>
                
                <hr>
                
                <div class="mt-3">
                    <h6>Shared With</h6>
                    <div id="folder-shares-list" class="mt-2">
                        <div class="text-center py-2 text-muted">
                            <i class="fas fa-spinner fa-spin me-2"></i> Loading shares...
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Include PDF.js for thumbnail generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    // Set the PDF.js worker source
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';
</script>

<!-- Folder Context Menu -->
<div id="folderContextMenu" class="folder-context-menu">
    <div class="folder-context-menu-item rename-folder">
        <i class="fas fa-edit"></i> Rename Folder
    </div>
    <div class="folder-context-menu-item share-folder">
        <i class="fas fa-share-alt"></i> Share Folder
    </div>
    <div class="folder-context-menu-separator"></div>
    <div class="folder-context-menu-item danger delete-folder">
        <i class="fas fa-trash-alt"></i> Delete Folder
    </div>
</div>

<!-- Custom JavaScript -->
<script>
    $(document).ready(function() {
        const dropzone = $('#dropzone');
        const fileInput = $('#file-input');
        const fileQueue = $('#file-queue');
        const fileList = $('#file-list');
        const searchInput = $('#search-files');
        const previewModal = $('#preview-modal');
        const folderList = $('#folder-list');
        const folderBreadcrumb = $('#folder-breadcrumb');
        const contextMenu = $('#folderContextMenu');
        const multiSelectActions = $('#multi-select-actions');
        const selectAllCheckbox = $('#selectAllFiles');
        const selectedCountBadge = $('#selected-count');
        const deleteSelectedBtn = $('#delete-selected');
        
        // Current folder context
        let currentFolder = null; // null means root folder
        let folderPath = []; // For breadcrumb navigation
        let lastUpdateTime = new Date().toISOString(); // Track when files were last updated
        let pollingInterval = null; // Store polling interval ID
        
        // PDF thumbnails cache
        const pdfThumbnailCache = {};
        
        // Initialize page
        loadFolders();
        loadFiles();
        
        // Start polling for updates
        startPolling();
        
        // Context menu handling
        let activeFolder = null;
        
        // Hide context menu on document click
        $(document).on('click', function() {
            contextMenu.hide();
        });
        
        // Prevent context menu from closing when clicking inside it
        contextMenu.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Show context menu on right-click
        $(document).on('contextmenu', '.folder-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Store folder data
            activeFolder = {
                id: $(this).data('folder-id'),
                name: $(this).data('folder-name'),
                owner_id: $(this).data('owner-id'),
                element: $(this)
            };
            
            // Show/hide share option based on ownership
            const currentUserId = <?php echo $_SESSION['user_id']; ?>;
            const isAdmin = <?php echo ($_SESSION['role_id'] == 1) ? 'true' : 'false'; ?>;
            
            if (activeFolder.owner_id == currentUserId || isAdmin) {
                contextMenu.find('.share-folder').show();
                contextMenu.find('.rename-folder').show();
                contextMenu.find('.delete-folder').show();
            } else {
                contextMenu.find('.share-folder').hide();
                contextMenu.find('.rename-folder').hide();
                contextMenu.find('.delete-folder').hide();
            }
            
            // Position and show the context menu
            contextMenu.css({
                left: e.pageX + 'px',
                top: e.pageY + 'px',
                display: 'block'
            });
        });
        
        // Context menu actions
        contextMenu.find('.rename-folder').on('click', function() {
            if (activeFolder) {
                openRenameModal(activeFolder.id, activeFolder.name);
                contextMenu.hide();
            }
        });
        
        contextMenu.find('.share-folder').on('click', function() {
            if (activeFolder) {
                openShareModal(activeFolder.id, activeFolder.name);
                contextMenu.hide();
            }
        });
        
        contextMenu.find('.delete-folder').on('click', function() {
            if (activeFolder) {
                openDeleteModal(activeFolder.id, activeFolder.name);
                contextMenu.hide();
            }
        });
        
        // New folder button
        $('#new-folder-btn').click(function() {
            $('#new-folder-form').slideToggle();
            $('#folder-name').focus();
        });
        
        // Cancel folder creation
        $('#cancel-folder-btn').click(function() {
            $('#new-folder-form').slideUp();
            $('#folder-name').val('');
        });
        
        // Create folder 
        $('#create-folder-btn').click(function() {
            const folderName = $('#folder-name').val().trim();
            
            if (!folderName) {
                alert('Please enter a folder name');
                return;
            }
            
            // Ajax call to create folder
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    create_folder: true,
                    folder_name: folderName,
                    parent_id: currentFolder // Pass null or the current folder ID
                },
                success: function(response) {
                    // Add new folder to list
                    addFolderItem(response.folder_id, folderName);
                    
                    // Reset form
                    $('#new-folder-form').slideUp();
                    $('#folder-name').val('');
                    
                    // Show success message
                    alert('Folder created successfully');
                },
                error: function(xhr) {
                    alert('Error creating folder: ' + xhr.responseText);
                }
            });
        });
        
        // Handle folder breadcrumb navigation
        $(document).on('click', '#folder-breadcrumb a', function() {
            const folder = $(this).data('folder');
            
            if (folder === 'root') {
                // Navigate to root
                currentFolder = null;
                folderPath = [];
                updateBreadcrumb();
                loadFiles();
                loadFolders();
            } else {
                // Navigate to specific folder in path
                const index = folderPath.findIndex(item => item.id === folder);
                if (index !== -1) {
                    // Truncate path to this point
                    folderPath = folderPath.slice(0, index + 1);
                    currentFolder = folder;
                    updateBreadcrumb();
                    loadFiles();
                    loadFolders();
                }
            }
        });
        
        // Handle folder click
        $(document).on('click', '.folder-btn', function(e) {
            // Prevent default if this is a right-click (for context menu)
            if (e.which === 3) return;
            
            const folderId = $(this).data('folder-id');
            const folderName = $(this).data('folder-name');
            
            // Update current folder
            currentFolder = folderId;
            
            // Update folder path for breadcrumb
            folderPath.push({
                id: folderId,
                name: folderName
            });
            
            updateBreadcrumb();
            loadFiles();
            loadFolders();
        });
        
        // Update breadcrumb based on current folder path
        function updateBreadcrumb() {
            // Clear all except home
            folderBreadcrumb.find('li:not(:first-child)').remove();
            
            // Add path items
            folderPath.forEach(folder => {
                folderBreadcrumb.append(`
                    <li class="breadcrumb-item">
                        <a data-folder="${folder.id}">${folder.name}</a>
                    </li>
                `);
            });
            
            // Make last item active
            folderBreadcrumb.find('li:last-child').addClass('active');
        }
        
        // Add a new folder item to the list
        function addFolderItem(folderId, folderName, isShared = false, ownerId = null) {
            // Set default owner to current user if not specified
            if (ownerId === null) {
                ownerId = <?php echo $_SESSION['user_id']; ?>;
            }
            
            const folderItem = $(`
                <div class="folder-item">
                    <div class="folder-btn" data-folder-id="${folderId}" data-folder-name="${folderName}" data-owner-id="${ownerId}">
                        <i class="fas fa-folder folder-icon"></i>
                        <span>${folderName}</span>
                        ${isShared ? `
                        <div class="shared-folder-indicator" title="Shared folder">
                            <i class="fas fa-share-alt"></i>
                        </div>` : ''}
                    </div>
                </div>
            `);
            
            folderList.append(folderItem);
        }
        
        // Open rename folder modal
        function openRenameModal(folderId, folderName) {
            // Set form values
            $('#rename-folder-id').val(folderId);
            $('#rename-folder-name').val(folderName);
            
            // Reset error message
            $('#rename-folder-error').addClass('d-none').html('');
            
            // Show modal
            $('#rename-folder-modal').modal('show');
        }
        
        // Handle rename folder form submission
        $('#save-folder-name').on('click', function() {
            const folderId = $('#rename-folder-id').val();
            const newName = $('#rename-folder-name').val().trim();
            
            if (!newName) {
                $('#rename-folder-error').removeClass('d-none').html('Please enter a folder name');
                return;
            }
            
            // Send rename request
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    rename_folder: true,
                    folder_id: folderId,
                    new_name: newName
                },
                success: function(response) {
                    // Update folder name in UI
                    const folderBtn = $(`.folder-btn[data-folder-id="${folderId}"]`);
                    folderBtn.data('folder-name', newName).attr('data-folder-name', newName);
                    folderBtn.find('span').text(newName);
                    
                    // Update rename button data
                    const editBtn = folderBtn.closest('.folder-item').find('.folder-action-btn.edit');
                    editBtn.data('folder-name', newName).attr('data-folder-name', newName);
                    
                    // Update delete button data
                    const deleteBtn = folderBtn.closest('.folder-item').find('.folder-action-btn.delete');
                    deleteBtn.data('folder-name', newName).attr('data-folder-name', newName);
                    
                    // Update breadcrumb if in this folder
                    const index = folderPath.findIndex(item => item.id === parseInt(folderId));
                    if (index !== -1) {
                        folderPath[index].name = newName;
                        updateBreadcrumb();
                    }
                    
                    // Close modal
                    $('#rename-folder-modal').modal('hide');
                },
                error: function(xhr) {
                    $('#rename-folder-error').removeClass('d-none').html('Error: ' + xhr.responseText);
                }
            });
        });
        
        // Open delete folder modal
        function openDeleteModal(folderId, folderName) {
            // Store folder ID in data attribute
            $('#confirm-delete-folder').data('folder-id', folderId);
            
            // Set folder name in modal
            $('#delete-folder-name').text(folderName);
            
            // Reset error and warning
            $('#delete-folder-error').addClass('d-none').html('');
            $('#delete-folder-warning').addClass('d-none');
            
            // Check if folder has files
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    delete_folder: folderId,
                },
                error: function(xhr) {
                    if (xhr.status === 400 && xhr.responseJSON && xhr.responseJSON.needs_confirmation) {
                        // Folder has files, show warning
                        $('#file-count').text(xhr.responseJSON.file_count);
                        $('#delete-folder-warning').removeClass('d-none');
                    } else if (xhr.status !== 400) {
                        // Other error
                        $('#delete-folder-error').removeClass('d-none').html('Error: ' + xhr.responseText);
                    }
                }
            });
            
            // Show modal
            $('#delete-folder-modal').modal('show');
        }
        
        // Handle delete folder confirmation
        $('#confirm-delete-folder').on('click', function() {
            const folderId = $(this).data('folder-id');
            
            // Send delete request with force parameter
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    delete_folder: folderId,
                    force: true
                },
                success: function(response) {
                    // Remove folder from UI
                    $(`.folder-item .folder-btn[data-folder-id="${folderId}"]`).closest('.folder-item').remove();
                    
                    // If we're in this folder, navigate back to parent
                    if (currentFolder == folderId) {
                        // Navigate to parent or root
                        if (folderPath.length > 1) {
                            // Go to parent folder
                            const parentIndex = folderPath.length - 2;
                            const parentFolder = folderPath[parentIndex];
                            
                            // Update breadcrumb and reload
                            folderPath = folderPath.slice(0, parentIndex + 1);
                            currentFolder = parentFolder.id;
                            updateBreadcrumb();
                        } else {
                            // Go to root
                            folderPath = [];
                            currentFolder = null;
                            updateBreadcrumb();
                        }
                        
                        // Reload contents
                        loadFiles();
                        loadFolders();
                    } else if (folderList.find('.folder-item').length === 0) {
                        // If no more folders, show "No folders" message
                        folderList.html(`
                            <div class="text-muted text-center py-3">
                                <i class="fas fa-folder-open"></i> No folders found
                            </div>
                        `);
                    }
                    
                    // Close modal
                    $('#delete-folder-modal').modal('hide');
                },
                error: function(xhr) {
                    $('#delete-folder-error').removeClass('d-none').html('Error: ' + xhr.responseText);
                }
            });
        });
        
        // Load folders from server
        function loadFolders() {
            let params = {
                get_folders: true
            };
            
            // Add parent_id parameter if we're in a folder
            if (currentFolder !== null) {
                params.parent_id = currentFolder;
            }
            
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function(folders) {
                    console.log("Folders returned from server:", folders);
                    renderFolders(folders);
                },
                error: function() {
                    folderList.html(`
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Error loading folders. Please try again later.
                        </div>
                    `);
                }
            });
        }
        
        // Override the renderFolders function to include shared status
        function renderFolders(folders) {
            // Filter folders for current parent
            const currentFolders = folders.filter(folder => {
                if (currentFolder === null) {
                    return folder.parent_id === null;
                } else {
                    return folder.parent_id == currentFolder;
                }
            });
            
            if (currentFolders.length === 0) {
                folderList.html(`
                    <div class="text-muted text-center py-3">
                        <i class="fas fa-folder-open"></i> No folders found
                    </div>
                `);
                return;
            }
            
            folderList.empty();
            
            currentFolders.forEach(folder => {
                // Check if folder is shared (convert to boolean to handle numeric string)
                const isShared = folder.is_shared == 1 || folder.is_shared === '1' || folder.is_shared === 'true' || folder.is_shared === true;
                // Pass owner information to the folder item
                addFolderItem(folder.folder_id, folder.folder_name, isShared, folder.staff_id);
            });
        }
        
        // Load files from server
        function loadFiles() {
            let params = {};
            
            if (currentFolder !== null) {
                params.folder_id = currentFolder;
            } else {
                params.root_folder = 1;
            }
            
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function(files) {
                    renderFileList(files);
                },
                error: function() {
                    fileList.html(`
                        <div class="alert alert-danger text-center" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Error loading files. Please try again later.
                        </div>
                    `);
                }
            });
        }
        
        // Handle file browse button
        $('#browse-files').click(function(e) {
            e.preventDefault();
            fileInput.click();
        });
        
        // Handle file input change
        fileInput.on('change', function() {
            handleFiles(this.files);
            // Clear the input so the same file can be selected again
            $(this).val('');
        });
        
        // Handle drag and drop events
        dropzone.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });
        
        dropzone.on('dragleave', function() {
            $(this).removeClass('dragover');
        });
        
        dropzone.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            
            if (e.originalEvent.dataTransfer.files.length) {
                handleFiles(e.originalEvent.dataTransfer.files);
            }
        });
        
        // File search functionality
        searchInput.on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('#file-list .file-card').each(function() {
                const fileName = $(this).find('.file-card-name').text().toLowerCase();
                
                if (fileName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Handle files added to queue - now automatically uploads
        function handleFiles(files) {
            if (!files || files.length === 0) return;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Check file size (10 MB max)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} exceeds the 10MB size limit.`);
                    continue;
                }
                
                // Add file to queue and immediately upload it
                const fileItem = createFileQueueItem(file);
                fileQueue.append(fileItem);
                uploadFile(file);
            }
        }
        
        // Create queue item element
        function createFileQueueItem(file) {
            const fileExt = file.name.split('.').pop().toLowerCase();
            const fileSize = formatFileSize(file.size);
            let fileIconClass = 'file-generic';
            let fileIcon = 'file-alt';
            
            // Determine appropriate icon based on file type
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExt)) {
                fileIconClass = 'file-image';
                fileIcon = 'file-image';
            } else if (['pdf'].includes(fileExt)) {
                fileIconClass = 'file-pdf';
                fileIcon = 'file-pdf';
            } else if (['doc', 'docx'].includes(fileExt)) {
                fileIconClass = 'file-word';
                fileIcon = 'file-word';
            } else if (['xls', 'xlsx', 'csv'].includes(fileExt)) {
                fileIconClass = 'file-excel';
                fileIcon = 'file-excel';
            } else if (['zip', 'rar', '7z', 'tar', 'gz'].includes(fileExt)) {
                fileIconClass = 'file-archive';
                fileIcon = 'file-archive';
            }
            
            const html = `
                <div class="file-item bg-light mb-3 p-3 rounded" data-filename="${file.name}">
                    <div class="d-flex align-items-center mb-2">
                        <div class="file-icon ${fileIconClass} mr-3">
                            <i class="fas fa-${fileIcon} fa-2x"></i>
                        </div>
                        <div class="file-info">
                            <div class="file-name font-weight-bold">${file.name}</div>
                            <div class="file-meta text-muted">${fileSize} - Uploading...</div>
                        </div>
                    </div>
                    <div class="progress upload-progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            `;
            
            return $(html);
        }
        
        // Format file size for display
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // Upload file automatically
        function uploadFile(file) {
            const fileItem = $(`.file-item[data-filename="${file.name.replace(/"/g, '\\"')}"]`);
            const progressBar = fileItem.find('.progress-bar');
            const fileInfo = fileItem.find('.file-meta');
            
            // Create FormData
            const formData = new FormData();
            formData.append('file', file);
            formData.append('description', ''); // Optional description
            
            // Add folder information if we're in a folder
            if (currentFolder !== null) {
                formData.append('folder_id', currentFolder);
            }
            
            // Send AJAX request
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    
                    // Add progress event listener
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            progressBar.css('width', percent + '%');
                        }
                    }, false);
                    
                    return xhr;
                },
                success: function(response) {
                    // Update UI to show success
                    fileItem.find('.file-icon').removeClass().addClass('file-icon file-success');
                    fileItem.find('.file-icon i').removeClass().addClass('fas fa-check-circle');
                    fileInfo.text('Upload complete');
                    
                    // Remove from queue after short delay
                    setTimeout(() => {
                        fileItem.fadeOut(function() {
                            $(this).remove();
                            
                            // Refresh file list
                            loadFiles();
                        });
                    }, 2000);
                },
                error: function(xhr) {
                    // Update UI to show error
                    fileItem.find('.file-icon').removeClass().addClass('file-icon file-error');
                    fileItem.find('.file-icon i').removeClass().addClass('fas fa-exclamation-circle');
                    fileInfo.text('Upload failed: ' + (xhr.responseText || 'Unknown error'));
                    progressBar.parent().addClass('hidden');
                }
            });
        }
        
        // Render file list
        function renderFileList(files) {
            if (!files || files.length === 0) {
                fileList.html(`
                    <div class="text-center py-4 w-100" style="grid-column: 1 / -1;">
                        <i class="fas fa-folder-open text-muted" style="font-size: 48px;"></i>
                        <p class="mt-3 text-muted">No files found</p>
                    </div>
                `);
                return;
            }
            
            fileList.empty();
            
            files.forEach(file => {
                const fileItem = createFileListItem(file);
                fileList.append(fileItem);
            });
        }
        
        // Create file list item
        function createFileListItem(file) {
            const fileExt = file.file_name.split('.').pop().toLowerCase();
            let fileIconClass = 'file-generic';
            let fileIcon = 'file-alt';
            let thumbnailContent = '';
            
            // Determine appropriate icon based on file type
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExt)) {
                fileIconClass = 'file-image';
                fileIcon = 'file-image';
                // Use actual image as thumbnail for image files
                thumbnailContent = `<img src="attachments/${file.file_name}" alt="${file.file_name}">`;
            } else if (['pdf'].includes(fileExt)) {
                fileIconClass = 'file-pdf';
                fileIcon = 'file-pdf';
                // Create a container for the PDF thumbnail with loading indicator
                thumbnailContent = `
                    <div class="pdf-thumbnail-container position-relative">
                        <div class="pdf-loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <canvas class="pdf-thumbnail" data-file="attachments/${file.file_name}" width="150" height="150"></canvas>
                    </div>
                `;
            } else if (['doc', 'docx'].includes(fileExt)) {
                fileIconClass = 'file-word';
                fileIcon = 'file-word';
                thumbnailContent = `<i class="fas fa-file-word"></i>`;
            } else if (['xls', 'xlsx', 'csv'].includes(fileExt)) {
                fileIconClass = 'file-excel';
                fileIcon = 'file-excel';
                thumbnailContent = `<i class="fas fa-file-excel"></i>`;
            } else if (['zip', 'rar', '7z', 'tar', 'gz'].includes(fileExt)) {
                fileIconClass = 'file-archive';
                fileIcon = 'file-archive';
                thumbnailContent = `<i class="fas fa-file-archive"></i>`;
            } else {
                thumbnailContent = `<i class="fas fa-file-alt"></i>`;
            }
            
            // Determine if OCR button should be shown (only for images and PDFs)
            const shouldShowOcr = ['jpg', 'jpeg', 'png', 'gif', 'pdf'].includes(fileExt);
            
            const html = `
                <div class="file-card" data-id="${file.id}" data-filename="${file.file_name}" data-filetype="${fileExt}">
                    <?php if($delete == 1): ?>
                    <input type="checkbox" class="file-checkbox" data-id="${file.id}">
                    <?php endif; ?>
                    <div class="file-thumbnail-large ${fileIconClass}">
                        ${thumbnailContent}
                    </div>
                    <div class="file-card-body">
                        <div class="file-card-name" title="${file.file_name}">${file.file_name}</div>
                        <div class="file-card-meta">
                            ${file.upload_date}
                            ${file.file_size ? ' - ' + file.file_size : ''}
                        </div>
                        <div class="file-card-actions">
                            <button class="btn btn-sm btn-info preview-file" title="Preview">
                                <i class="fas fa-eye"></i> View
                            </button>
                            ${shouldShowOcr ? `
                            <button class="btn btn-sm btn-warning ocr-file" title="Extract text with OCR">
                                <i class="fas fa-font"></i> OCR
                            </button>
                            ` : ''}
                            <a href="attachmentsController.php?download=${file.id}" class="btn btn-sm btn-primary" title="Download">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <?php if($delete == 1): ?>
                            <button class="btn btn-sm btn-danger delete-file" title="Delete">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            `;
            
            const element = $(html);
            
            // Add preview button event handler
            element.find('.preview-file').on('click', function() {
                previewFile(file, fileExt);
            });
            
            // Add OCR button event handler
            if (shouldShowOcr) {
                element.find('.ocr-file').on('click', function() {
                    processOcr(file.id, file.file_name);
                });
            }
            
            // Add delete button event handler
            element.find('.delete-file').on('click', function() {
                const fileId = $(this).closest('.file-card').data('id');
                
                if (confirm('Are you sure you want to delete this file?')) {
                    deleteFile(fileId);
                }
            });
            
            // Generate PDF thumbnail if it's a PDF file
            if (fileExt === 'pdf') {
                generatePDFThumbnail(element.find('canvas.pdf-thumbnail')[0], file.file_name);
            }
            
            return element;
        }
        
        // Preview file
        function previewFile(file, fileType) {
            const previewContent = $('#preview-content');
            const previewDownloadBtn = $('#preview-download-btn');
            const fileName = file.file_name;
            const filePath = `attachments/${file.file_name}`;
            
            // Set the modal title to file name
            $('#previewModalLabel').text(fileName);
            
            // Set download button href
            previewDownloadBtn.attr('href', `attachmentsController.php?download=${file.id}`);
            
            // Clear previous content
            previewContent.empty();
            
            // Show appropriate preview based on file type
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileType)) {
                // Image preview
                previewContent.html(`<img src="${filePath}" alt="${fileName}" class="img-fluid">`);
            } else if (fileType === 'pdf') {
                // PDF preview using iframe
                previewContent.html(`<iframe src="${filePath}" class="pdf-preview" frameborder="0"></iframe>`);
            } else {
                // No preview available
                previewContent.html(`
                    <div class="preview-not-available">
                        <i class="fas fa-file-${getFileIconClass(fileType)} mb-3" style="font-size: 48px;"></i>
                        <h5>Preview not available</h5>
                        <p>This file type (${fileType}) cannot be previewed in the browser.</p>
                        <p>Please download the file to view its contents.</p>
                    </div>
                `);
            }
            
            // Show the modal
            previewModal.modal('show');
        }
        
        // Get file icon class based on extension
        function getFileIconClass(fileExt) {
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExt)) {
                return 'image';
            } else if (['pdf'].includes(fileExt)) {
                return 'pdf';
            } else if (['doc', 'docx'].includes(fileExt)) {
                return 'word';
            } else if (['xls', 'xlsx', 'csv'].includes(fileExt)) {
                return 'excel';
            } else if (['zip', 'rar', '7z', 'tar', 'gz'].includes(fileExt)) {
                return 'archive';
            } else {
                return 'alt';
            }
        }
        
        // Delete file
        function deleteFile(fileId) {
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    delete: fileId
                },
                success: function(response) {
                    // Remove file from list
                    $(`.file-card[data-id="${fileId}"]`).fadeOut(function() {
                        $(this).remove();
                        
                        // Show "no files" message if list is empty
                        if (fileList.children().length === 0) {
                            renderFileList([]);
                        }
                    });
                },
                error: function() {
                    alert('Error deleting file. Please try again.');
                }
            });
        }
        
        // Generate PDF thumbnail
        function generatePDFThumbnail(canvas, filename) {
            if (!canvas) return;
            
            const url = `attachments/${filename}`;
            const canvasKey = filename;
            
            // Check cache first - skip rendering if already in cache
            if (pdfThumbnailCache[canvasKey]) {
                // Still hide loading indicator even if cached
                const container = $(canvas).closest('.pdf-thumbnail-container');
                container.find('.pdf-loading').hide();
                
                // For cached PDFs, try to copy the cached render to the new canvas
                if (pdfThumbnailCache[canvasKey].dataUrl) {
                    const img = new Image();
                    img.onload = function() {
                        const ctx = canvas.getContext('2d');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        ctx.drawImage(img, 0, 0);
                    };
                    img.src = pdfThumbnailCache[canvasKey].dataUrl;
                }
                return;
            }
            
            // Create cache entry to prevent duplicate rendering attempts
            pdfThumbnailCache[canvasKey] = { rendering: true };
            
            // Load the PDF file
            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                // Get the first page
                pdf.getPage(1).then(function(page) {
                    // Set the scale to fit within our thumbnail container
                    const viewport = page.getViewport({ scale: 1 });
                    const scale = Math.min(canvas.width / viewport.width, canvas.height / viewport.height);
                    const scaledViewport = page.getViewport({ scale: scale });
                    
                    // Set canvas dimensions to match the scaled page
                    canvas.width = scaledViewport.width;
                    canvas.height = scaledViewport.height;
                    
                    // Render the PDF page
                    const renderContext = {
                        canvasContext: canvas.getContext('2d'),
                        viewport: scaledViewport
                    };
                    
                    const renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        // Hide loading indicator
                        const container = $(canvas).closest('.pdf-thumbnail-container');
                        container.find('.pdf-loading').hide();
                        
                        // Store the rendered image in the cache for future use
                        try {
                            const dataUrl = canvas.toDataURL('image/png');
                            pdfThumbnailCache[canvasKey] = { 
                                rendering: false,
                                dataUrl: dataUrl,
                                width: canvas.width,
                                height: canvas.height
                            };
                        } catch (e) {
                            console.error('Error caching PDF thumbnail:', e);
                            pdfThumbnailCache[canvasKey] = { rendering: false };
                        }
                    }).catch(function(error) {
                        console.error('Error rendering PDF thumbnail:', error);
                        // Show fallback icon if rendering fails
                        const container = $(canvas).closest('.file-thumbnail-large');
                        container.empty().append('<i class="fas fa-file-pdf" style="font-size: 64px;"></i>');
                        pdfThumbnailCache[canvasKey] = { rendering: false, error: true };
                    });
                }).catch(function(error) {
                    console.error('Error getting PDF page:', error);
                    // Show fallback icon if loading page fails
                    const container = $(canvas).closest('.file-thumbnail-large');
                    container.empty().append('<i class="fas fa-file-pdf" style="font-size: 64px;"></i>');
                    pdfThumbnailCache[canvasKey] = { rendering: false, error: true };
                });
            }).catch(function(error) {
                console.error('Error loading PDF document:', error);
                // Show fallback icon if loading fails
                const container = $(canvas).closest('.file-thumbnail-large');
                container.empty().append('<i class="fas fa-file-pdf" style="font-size: 64px;"></i>');
                pdfThumbnailCache[canvasKey] = { rendering: false, error: true };
            });
        }
        
        // Start polling for updates
        function startPolling() {
            // Clear any existing interval
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
            
            // Poll every 5 seconds
            pollingInterval = setInterval(checkForNewFiles, 5000);
        }
        
        // Stop polling (when navigating away)
        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }
        
        // Check for new files since last update
        function checkForNewFiles() {
            let params = {
                since: lastUpdateTime
            };
            
            if (currentFolder !== null) {
                params.folder_id = currentFolder;
            } else {
                params.root_folder = 1;
            }
            
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: params,
                dataType: 'json',
                success: function(newFiles) {
                    if (newFiles && newFiles.length > 0) {
                        // Update timestamp to now
                        lastUpdateTime = new Date().toISOString();
                        
                        // Check if we need to initialize the grid
                        if (fileList.find('.text-center').length > 0 && fileList.find('.file-card').length === 0) {
                            // First files, clear the "no files" message
                            fileList.empty();
                        }
                        
                        // Add new files to the display
                        newFiles.forEach(file => {
                            // Check if this file is already displayed
                            if (fileList.find(`.file-card[data-id="${file.id}"]`).length === 0) {
                                const fileItem = createFileListItem(file);
                                fileList.prepend(fileItem); // Add to top of list
                                fileItem.addClass('highlight-new');
                                
                                // Remove highlight after 2 seconds
                                setTimeout(() => {
                                    fileItem.removeClass('highlight-new');
                                }, 2000);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    console.error('Error checking for new files:', xhr.responseText);
                    // Don't stop polling on error, just continue
                }
            });
        }
        
        // File checkbox change handler - for multi-select
        $(document).on('change', '.file-checkbox', function() {
            updateSelectedCount();
        });
        
        // Select all checkbox handler
        selectAllCheckbox.on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.file-checkbox').prop('checked', isChecked);
            updateSelectedCount();
        });
        
        // Update selected count
        function updateSelectedCount() {
            const selectedCount = $('.file-checkbox:checked').length;
            selectedCountBadge.text(selectedCount);
            
            // Show/hide multi-select actions
            if (selectedCount > 0) {
                multiSelectActions.slideDown();
            } else {
                multiSelectActions.slideUp();
                selectAllCheckbox.prop('checked', false);
            }
        }
        
        // Delete selected files
        deleteSelectedBtn.on('click', function() {
            const selectedFiles = $('.file-checkbox:checked');
            const fileIds = [];
            
            selectedFiles.each(function() {
                fileIds.push($(this).data('id'));
            });
            
            if (fileIds.length === 0) {
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${fileIds.length} selected files?`)) {
                deleteMultipleFiles(fileIds);
            }
        });
        
        // Delete multiple files
        function deleteMultipleFiles(fileIds) {
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    delete_multiple: true,
                    file_ids: fileIds
                },
                success: function(response) {
                    // Remove deleted files from list
                    fileIds.forEach(fileId => {
                        $(`.file-card[data-id="${fileId}"]`).fadeOut(function() {
                            $(this).remove();
                        });
                    });
                    
                    // Reset multi-select state
                    selectAllCheckbox.prop('checked', false);
                    multiSelectActions.slideUp();
                    
                    // Show "no files" message if list is empty
                    setTimeout(() => {
                        if (fileList.children().length === 0) {
                            renderFileList([]);
                        }
                    }, 500);
                },
                error: function() {
                    alert('Error deleting files. Please try again.');
                }
            });
        }
        
        // Open share folder modal
        function openShareModal(folderId, folderName) {
            // Set folder data
            $('#share-folder-id').val(folderId);
            $('#share-folder-name').text(folderName);
            
            // Reset any previous errors
            $('#share-folder-error').addClass('d-none').html('');
            
            // Load staff members
            loadStaffMembers();
            
            // Load existing shares
            loadFolderShares(folderId);
            
            // Show modal
            $('#share-folder-modal').modal('show');
        }
        
        // Load staff members for sharing
        function loadStaffMembers() {
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: {
                    get_staff: true
                },
                dataType: 'json',
                success: function(staff) {
                    const selectElement = $('#share-staff-select');
                    selectElement.empty();
                    
                    if (staff.length === 0) {
                        selectElement.append(`<option value="">No staff members available</option>`);
                        return;
                    }
                    
                    selectElement.append(`<option value="">Select a staff member</option>`);
                    
                    staff.forEach(member => {
                        selectElement.append(`<option value="${member.staff_id}">${member.staff_name}</option>`);
                    });
                },
                error: function() {
                    $('#share-staff-select').html(`<option value="">Error loading staff members</option>`);
                }
            });
        }
        
        // Load existing folder shares
        function loadFolderShares(folderId) {
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: {
                    get_folder_shares: folderId
                },
                dataType: 'json',
                success: function(shares) {
                    renderFolderShares(shares);
                },
                error: function() {
                    $('#folder-shares-list').html(`
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Error loading folder shares. Please try again.
                        </div>
                    `);
                }
            });
        }
        
        // Render folder shares list
        function renderFolderShares(shares) {
            const sharesContainer = $('#folder-shares-list');
            
            if (shares.length === 0) {
                sharesContainer.html(`
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-users-slash"></i> Not shared with anyone
                    </div>
                `);
                return;
            }
            
            sharesContainer.empty();
            
            shares.forEach(share => {
                const shareItem = $(`
                    <div class="share-item" data-share-id="${share.share_id}">
                        <div class="share-item-info">
                            <div class="share-avatar">
                                ${getInitials(share.staff_name)}
                            </div>
                            <div>
                                <div class="share-name">${share.staff_name}</div>
                                <div class="share-permission">${formatPermission(share.permission)}</div>
                            </div>
                        </div>
                        <div class="share-actions">
                            <button class="btn btn-sm btn-danger remove-share" data-share-id="${share.share_id}">
                                <i class="fas fa-times"></i> Remove
                            </button>
                        </div>
                    </div>
                `);
                
                // Add click handler for remove button
                shareItem.find('.remove-share').on('click', function() {
                    const shareId = $(this).data('share-id');
                    removeShare(shareId);
                });
                
                sharesContainer.append(shareItem);
            });
        }
        
        // Get initials from name for avatar
        function getInitials(name) {
            if (!name) return '?';
            
            const parts = name.split(' ');
            if (parts.length === 1) {
                return parts[0].charAt(0).toUpperCase();
            }
            
            return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase();
        }
        
        // Format permission text
        function formatPermission(permission) {
            switch(permission) {
                case 'view':
                    return 'View Only';
                case 'edit':
                    return 'Can Edit';
                default:
                    return permission;
            }
        }
        
        // Add share button handler
        $('#add-share-btn').on('click', function() {
            const folderId = $('#share-folder-id').val();
            const staffId = $('#share-staff-select').val();
            const permission = $('input[name="share-permission"]:checked').val();
            
            if (!staffId) {
                $('#share-folder-error').removeClass('d-none').html('Please select a staff member');
                return;
            }
            
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    add_share: true,
                    folder_id: folderId,
                    staff_id: staffId,
                    permission: permission
                },
                dataType: 'json',
                success: function(response) {
                    // Reset staff select
                    $('#share-staff-select').val('');
                    
                    // Update shares list
                    loadFolderShares(folderId);
                    
                    // Update folder icon in the list
                    updateFolderShareIndicator(folderId, true);
                },
                error: function(xhr) {
                    $('#share-folder-error').removeClass('d-none').html('Error: ' + xhr.responseText);
                }
            });
        });
        
        // Remove share
        function removeShare(shareId) {
            const folderId = $('#share-folder-id').val();
            
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    remove_share: shareId
                },
                dataType: 'json',
                success: function(response) {
                    // Update shares list
                    loadFolderShares(folderId);
                    
                    // Check if there are any shares left and update indicator
                    checkRemainingShares(folderId);
                },
                error: function(xhr) {
                    $('#share-folder-error').removeClass('d-none').html('Error: ' + xhr.responseText);
                }
            });
        }
        
        // Check if folder has any remaining shares
        function checkRemainingShares(folderId) {
            $.ajax({
                url: 'attachmentsController.php',
                type: 'GET',
                data: {
                    get_folder_shares: folderId
                },
                dataType: 'json',
                success: function(shares) {
                    // Update indicator based on shares count
                    updateFolderShareIndicator(folderId, shares.length > 0);
                },
                error: function() {
                    // Handle error silently
                }
            });
        }
        
        // Update folder share indicator
        function updateFolderShareIndicator(folderId, hasShares) {
            const folderBtn = $(`.folder-btn[data-folder-id="${folderId}"]`);
            
            // Remove existing indicator if any
            folderBtn.find('.shared-folder-indicator').remove();
            
            if (hasShares) {
                // Add share indicator
                folderBtn.append(`
                    <div class="shared-folder-indicator" title="Shared folder">
                        <i class="fas fa-share-alt"></i>
                    </div>
                `);
                
                // Add shared class for styling
                folderBtn.addClass('is-shared');
            } else {
                // Remove shared class
                folderBtn.removeClass('is-shared');
            }
        }
        
        // Process OCR for the file
        function processOcr(fileId, fileName) {
            // Show OCR modal with loading indicator
            $('#ocr-modal').modal('show');
            $('#ocr-loading').show();
            $('#ocr-content').hide();
            $('#ocr-structured-fields').hide();
            $('#ocr-error').hide();
            $('#ocr-debug').hide();
            $('#ocrModalLabel').text('OCR: ' + fileName);
            
            // Send AJAX request to process OCR
            $.ajax({
                url: 'attachmentsController.php',
                type: 'POST',
                data: {
                    process_ocr: true,
                    file_id: fileId
                },
                dataType: 'json',
                success: function(response) {
                    // Hide loading indicator
                    $('#ocr-loading').hide();
                    
                    if (response && response.success) {
                        // Display structured fields if available
                        if (response.extracted_fields && Object.keys(response.extracted_fields).length > 0) {
                            displayStructuredFields(response.extracted_fields);
                            $('#ocr-structured-fields').show();
                        }
                        
                        // Show extracted text
                        if (response.text) {
                            $('#ocr-content').html(response.text.replace(/\n/g, '<br>')).show();
                        }
                        
                        // Store debug info if available
                        if (response.debug) {
                            $('#ocr-debug-content').text(JSON.stringify(response.debug, null, 2));
                        }
                    } else {
                        // Show generic error if response is not as expected
                        $('#ocr-error').html('Invalid response from server.').show();
                    }
                },
                error: function(xhr, status, error) {
                    // Hide loading indicator
                    $('#ocr-loading').hide();
                    
                    // Provide more detailed error information
                    let errorMessage = 'Error extracting text';
                    
                    if (xhr.responseText) {
                        try {
                            // Try to parse as JSON first
                            const jsonResponse = JSON.parse(xhr.responseText);
                            if (jsonResponse.error) {
                                errorMessage += ': ' + jsonResponse.error;
                            } else {
                                errorMessage += ': ' + xhr.responseText;
                            }
                        } catch (e) {
                            // If not JSON, use as plain text
                            errorMessage += ': ' + xhr.responseText;
                        }
                    } else if (error) {
                        errorMessage += ': ' + error;
                    }
                    
                    $('#ocr-error').html(errorMessage).show();
                }
            });
        }
        
        // Helper function to format dates as DD/MM/YYYY
        function formatDateToDDMMYYYY(dateStr) {
            // Skip formatting if not a valid date string
            if (!dateStr || typeof dateStr !== 'string') {
                return dateStr;
            }
            
            // Try to parse the date
            let date;
            
            // Handle common formats (YYYY-MM-DD, MM/DD/YYYY, etc.)
            if (dateStr.match(/^\d{4}-\d{2}-\d{2}$/)) {
                // ISO format YYYY-MM-DD
                const [year, month, day] = dateStr.split('-');
                return `${day}/${month}/${year}`;
            } else if (dateStr.match(/^\d{1,2}\/\d{1,2}\/\d{4}$/)) {
                // Already in MM/DD/YYYY or DD/MM/YYYY format
                const parts = dateStr.split('/');
                if (parts.length === 3) {
                    // If it's in MM/DD/YYYY format, convert to DD/MM/YYYY
                    if (parseInt(parts[0]) <= 12 && parseInt(parts[1]) > 12) {
                        // Looks like MM/DD/YYYY, swap month and day
                        return `${parts[1]}/${parts[0]}/${parts[2]}`;
                    }
                    // If first part is >12, it's likely already DD/MM/YYYY
                    return dateStr;
                }
            }
            
            // Try to create a date object as a fallback
            try {
                date = new Date(dateStr);
                if (!isNaN(date.getTime())) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                }
            } catch (e) {
                console.log('Error parsing date:', e);
            }
            
            // Return original if we couldn't parse it
            return dateStr;
        }
        
        // Function to display structured fields in a table
        function displayStructuredFields(fields) {
            const fieldsTable = $('#ocr-fields-table');
            fieldsTable.empty();
            
            // Create a copy of fields to modify
            const displayFields = {...fields};
            
            // Format date fields to DD/MM/YYYY format
            const dateFields = ['Date of Birth', 'Expiry Date', 'Issue Date'];
            dateFields.forEach(field => {
                if (displayFields[field]) {
                    displayFields[field] = formatDateToDDMMYYYY(displayFields[field]);
                }
            });
            
            // Combine English Name fields (Name, Given Name, Surname)
            if (displayFields['Name']) {
                // Keep existing Name field if it exists
                displayFields['Full Name'] = displayFields['Name'];
                delete displayFields['Name'];
            } else if (displayFields['Given Name'] && displayFields['Surname']) {
                // Combine Given Name and Surname
                displayFields['Full Name'] = displayFields['Given Name'] + ' ' + displayFields['Surname'];
                // Remove the individual fields
                delete displayFields['Given Name'];
                delete displayFields['Surname'];
            } else if (displayFields['Given Name']) {
                // Use Given Name as full name if that's all we have
                displayFields['Full Name'] = displayFields['Given Name'];
                delete displayFields['Given Name'];
            } else if (displayFields['Surname']) {
                // Use Surname as full name if that's all we have
                displayFields['Full Name'] = displayFields['Surname'];
                delete displayFields['Surname'];
            }
            
            // Combine Arabic Name and Arabic Surname if both exist
            if (displayFields['Arabic Name'] && displayFields['Arabic Surname']) {
                displayFields['Arabic Full Name'] = displayFields['Arabic Name'] + ' ' + displayFields['Arabic Surname'];
                // Remove the individual fields
                delete displayFields['Arabic Name'];
                delete displayFields['Arabic Surname'];
            } else if (displayFields['Arabic Name']) {
                // Rename to Arabic Full Name if only Name exists
                displayFields['Arabic Full Name'] = displayFields['Arabic Name'];
                delete displayFields['Arabic Name'];
            } else if (displayFields['Arabic Surname']) {
                // Use surname as full name if that's all we have
                displayFields['Arabic Full Name'] = displayFields['Arabic Surname'];
                delete displayFields['Arabic Surname'];
            }
            
            // Define field order for better readability
            const orderedFields = [
                'Full Name', 'Arabic Full Name',
                'Passport Number', 'Nationality', 
                'Date of Birth', 'Gender', 
                'Issue Date', 'Expiry Date',
                'ID Number', 'Address', 'Phone', 'Email'
            ];
            
            // Add fields in the specified order first
            orderedFields.forEach(field => {
                if (displayFields[field] && displayFields[field].trim() !== '') {
                    // Escape HTML entities and quotes for the data attribute
                    const escapedValue = displayFields[field]
                        .replace(/&/g, '&amp;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                    
                    const row = $(`
                        <tr>
                            <th scope="row" style="width: 30%;">${field}</th>
                            <td style="width: 60%;">${displayFields[field]}</td>
                            <td style="width: 10%; text-align: center;">
                                <button class="btn btn-sm btn-outline-primary copy-field-btn" data-value="${escapedValue}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    fieldsTable.append(row);
                    // Remove from the object so we don't show it twice
                    delete displayFields[field];
                }
            });
            
            // Add any remaining fields
            for (const [field, value] of Object.entries(displayFields)) {
                if (value && value.trim() !== '') {
                    // Escape HTML entities and quotes for the data attribute
                    const escapedValue = value
                        .replace(/&/g, '&amp;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                    
                    const row = $(`
                        <tr>
                            <th scope="row" style="width: 30%;">${field}</th>
                            <td style="width: 60%;">${value}</td>
                            <td style="width: 10%; text-align: center;">
                                <button class="btn btn-sm btn-outline-primary copy-field-btn" data-value="${escapedValue}">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                    fieldsTable.append(row);
                }
            }
            
            // Attach event handlers to field copy buttons
            fieldsTable.on('click', '.copy-field-btn', function() {
                // Get the actual text from data attribute, and decode any HTML entities
                let value = $(this).data('value');
                
                // Convert value to string in case it's a number or other type
                value = String(value);
                
                // Create a temporary textarea element outside the DOM to avoid layout issues
                const textarea = document.createElement('textarea');
                textarea.value = value;
                textarea.setAttribute('readonly', ''); // Make it readonly to avoid focus
                textarea.style.position = 'absolute';  // Position out of view
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                
                // Check if the browser supports the newer clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    // Use the Clipboard API when available
                    navigator.clipboard.writeText(value)
                        .then(() => showCopySuccess($(this)))
                        .catch(err => {
                            console.error('Error copying text: ', err);
                            // Fall back to the older method
                            fallbackCopy(textarea, $(this));
                        });
                } else {
                    // Fall back to the older method for browsers without Clipboard API
                    fallbackCopy(textarea, $(this));
                }
                
                // Clean up
                document.body.removeChild(textarea);
            });
        }
        
        // Helper function to execute the fallback copy method
        function fallbackCopy(textarea, button) {
            try {
                // Select the text and copy it
                textarea.select();
                textarea.setSelectionRange(0, 99999); // For mobile devices
                
                // Execute the copy command
                const successful = document.execCommand('copy');
                
                if (successful) {
                    showCopySuccess(button);
                } else {
                    console.error('Fallback: Could not copy text');
                }
            } catch (err) {
                console.error('Fallback: Unable to copy', err);
            }
        }
        
        // Helper function to show copy success visual feedback
        function showCopySuccess(button) {
            // Visual feedback
            const originalHtml = button.html();
            button.html('<i class="fas fa-check text-success"></i>');
            button.addClass('btn-success').removeClass('btn-outline-primary');
            
            setTimeout(() => {
                button.html(originalHtml);
                button.addClass('btn-outline-primary').removeClass('btn-success');
            }, 1500);
        }
        
        // Handle copy OCR text button
        $('#copy-ocr-text').on('click', function() {
            // First check if structured fields are available
            if ($('#ocr-structured-fields').is(':visible')) {
                let fieldText = '';
                // Just get the text directly from the displayed table
                $('#ocr-fields-table tr').each(function() {
                    const field = $(this).find('th').text();
                    const value = $(this).find('td').text();
                    fieldText += `${field}: ${value}\n`;
                });
                
                // Add raw text if available
                if ($('#ocr-content').is(':visible')) {
                    fieldText += '\n--- RAW TEXT ---\n\n';
                    fieldText += $('#ocr-content').text();
                }
                
                // Use the more reliable copy method
                copyToClipboard(fieldText, $(this));
            } else {
                // Just copy the content if no structured fields
                copyToClipboard($('#ocr-content').text(), $(this));
            }
        });
        
        // Improved clipboard copy function
        function copyToClipboard(text, button) {
            if (!text) return;
            
            // Check if the browser supports the newer clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                // Use the Clipboard API when available
                navigator.clipboard.writeText(text)
                    .then(() => {
                        // Show success feedback
                        const originalText = button.html();
                        button.html('<i class="fas fa-check"></i> Copied!');
                        
                        setTimeout(() => {
                            button.html(originalText);
                        }, 2000);
                    })
                    .catch(err => {
                        console.error('Error copying text: ', err);
                        // Fall back to the textarea method
                        fallbackCopyFullText(text, button);
                    });
            } else {
                // Use the fallback method
                fallbackCopyFullText(text, button);
            }
        }
        
        // Fallback copy method for the main copy button
        function fallbackCopyFullText(text, button) {
            try {
                // Create a temporary textarea element
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                
                // Select and copy the text
                textarea.select();
                textarea.setSelectionRange(0, 99999);
                const successful = document.execCommand('copy');
                
                // Clean up
                document.body.removeChild(textarea);
                
                if (successful) {
                    // Show success feedback
                    const originalText = button.html();
                    button.html('<i class="fas fa-check"></i> Copied!');
                    
                    setTimeout(() => {
                        button.html(originalText);
                    }, 2000);
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
            }
        }

        // Toggle debug info display
        $('#toggle-debug').on('click', function() {
            const debugPanel = $('#ocr-debug');
            
            if (debugPanel.is(':visible')) {
                debugPanel.hide();
                $(this).text('Show Debug Info');
            } else {
                debugPanel.show();
                $(this).text('Hide Debug Info');
            }
        });
    });
</script>

<?php require 'footer.php' ?>