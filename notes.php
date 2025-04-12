<?php
include 'header.php';
?>
<title>Notes</title>
<link href="residenceCustom.css" rel="stylesheet">
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/ypmusxldrdagyn5urgd306v7ncbxuick76dfg5g3433tk79o/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    #notes-editor-container {
        margin-bottom: 20px;
    }
    .tox-tinymce {
        border-radius: 5px;
        min-height: 500px !important;
    }
    .save-status {
        font-size: 12px;
        margin-top: 10px;
        color: #666;
    }
    .saved {
        color: #28a745;
    }
    .saving {
        color: #ffc107;
    }
    .error {
        color: #dc3545;
    }
</style>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel text-white">
                <div class="panel-heading bg-inverse">
                    <h4 class="panel-title"><i class="fa fa-sticky-note"></i> Notes <code><i class="fa fa-arrow-down"></i></code></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="notes.php" class="btn btn-xs btn-icon btn-success"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="notes-editor-container">
                                <textarea id="notes-editor" placeholder="Start typing your notes here..."></textarea>
                            </div>
                            <div class="save-status">Start typing to save automatically...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize TinyMCE
        tinymce.init({
            selector: '#notes-editor',
            height: 500,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            setup: function(editor) {
                const saveStatus = document.querySelector('.save-status');
                let typingTimer;
                const doneTypingInterval = 1000; // time in ms (1 second)
                
                // Load notes when editor is initialized
                editor.on('init', function() {
                    loadNotes(editor);
                });
                
                // Add event listener for typing
                editor.on('input', function() {
                    clearTimeout(typingTimer);
                    saveStatus.textContent = 'Saving...';
                    saveStatus.className = 'save-status saving';
                    
                    typingTimer = setTimeout(function() {
                        saveNotes(editor);
                    }, doneTypingInterval);
                });
                
                // Also save on change (for images, formatting, etc.)
                editor.on('change', function() {
                    clearTimeout(typingTimer);
                    saveStatus.textContent = 'Saving...';
                    saveStatus.className = 'save-status saving';
                    
                    typingTimer = setTimeout(function() {
                        saveNotes(editor);
                    }, doneTypingInterval);
                });
            }
        });
        
        function loadNotes(editor) {
            const saveStatus = document.querySelector('.save-status');
            
            // Show loading message
            editor.setContent('Loading your notes...');
            
            // AJAX request to load notes
            fetch('notesController.php?action=load', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    editor.setContent(data.notes || '');
                    saveStatus.textContent = 'Loaded successfully';
                    saveStatus.className = 'save-status saved';
                    
                    // Clear the status message after 2 seconds
                    setTimeout(() => {
                        saveStatus.textContent = 'Start typing to save automatically...';
                        saveStatus.className = 'save-status';
                    }, 2000);
                } else {
                    editor.setContent('');
                    saveStatus.textContent = 'Error loading notes: ' + data.message;
                    saveStatus.className = 'save-status error';
                }
            })
            .catch(error => {
                console.error('Error loading notes:', error);
                editor.setContent('');
                saveStatus.textContent = 'Error loading notes. Please try again.';
                saveStatus.className = 'save-status error';
            });
        }
        
        function saveNotes(editor) {
            const saveStatus = document.querySelector('.save-status');
            const content = editor.getContent() || '';
            
            // AJAX request to save notes
            fetch('notesController.php?action=save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ notes: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    saveStatus.textContent = 'Saved';
                    saveStatus.className = 'save-status saved';
                    
                    // Clear the status message after 2 seconds
                    setTimeout(() => {
                        saveStatus.textContent = 'Start typing to save automatically...';
                        saveStatus.className = 'save-status';
                    }, 2000);
                } else {
                    saveStatus.textContent = 'Error saving: ' + data.message;
                    saveStatus.className = 'save-status error';
                }
            })
            .catch(error => {
                console.error('Error saving notes:', error);
                saveStatus.textContent = 'Error saving. Please try again.';
                saveStatus.className = 'save-status error';
            });
        }
    });
</script>

<?php
include 'footer.php';
?> 