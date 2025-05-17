<?php
include 'header.php';
?>
<title>Notes</title>
<link href="residenceCustom.css" rel="stylesheet">
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/wbtz6ccstxl5lymfgz3liq3j4xdxletrgfjjm3qumdpc7q42/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
    .category-selector {
        margin-bottom: 15px;
    }
    .category-selector select {
        margin-right: 10px;
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .add-category-btn {
        padding: 6px 12px;
        background-color: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .add-category-btn:hover {
        background-color: #5a6268;
    }
    .category-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .category-modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .category-modal input {
        width: 100%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }
    .category-modal-buttons {
        display: flex;
        justify-content: flex-end;
    }
    .category-modal-buttons button {
        margin-left: 10px;
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .category-modal-cancel {
        background-color: #6c757d;
        color: white;
    }
    .category-modal-save {
        background-color: #007bff;
        color: white;
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
                            <div class="category-selector">
                                <select id="category-select">
                                    <option value="">Loading categories...</option>
                                </select>
                                <button class="add-category-btn" id="add-category-btn">
                                    <i class="fa fa-plus"></i> Add Category
                                </button>
                            </div>
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

<!-- Add Category Modal -->
<div id="category-modal" class="category-modal">
    <div class="category-modal-content">
        <h5>Add New Category</h5>
        <input type="text" id="new-category-name" placeholder="Category name">
        <div class="category-modal-buttons">
            <button class="category-modal-cancel" id="category-modal-cancel">Cancel</button>
            <button class="category-modal-save" id="category-modal-save">Save</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category-select');
        const addCategoryBtn = document.getElementById('add-category-btn');
        const categoryModal = document.getElementById('category-modal');
        const newCategoryInput = document.getElementById('new-category-name');
        const cancelCategoryBtn = document.getElementById('category-modal-cancel');
        const saveCategoryBtn = document.getElementById('category-modal-save');
        const saveStatus = document.querySelector('.save-status');
        
        let currentCategoryId = 1; // Default to first category
        let editor = null;
        
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
                window.editor = editor; // Making the editor globally accessible
                
                let typingTimer;
                const doneTypingInterval = 1000; // time in ms (1 second)
                
                // Load categories when editor is initialized
                editor.on('init', function() {
                    loadCategories();
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
        
        // Load categories from server
        function loadCategories() {
            fetch('notesController.php?action=getCategories', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear the select options
                    categorySelect.innerHTML = '';
                    
                    // Add categories to the select dropdown
                    data.categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                    
                    // Set the first category as selected if available
                    if (data.categories.length > 0) {
                        currentCategoryId = data.categories[0].id;
                        categorySelect.value = currentCategoryId;
                    }
                    
                    // Load notes for the selected category
                    loadNotes(currentCategoryId);
                } else {
                    console.error('Error loading categories:', data.message);
                    saveStatus.textContent = 'Error loading categories: ' + data.message;
                    saveStatus.className = 'save-status error';
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
                saveStatus.textContent = 'Error loading categories. Please try again.';
                saveStatus.className = 'save-status error';
            });
        }
        
        // Add event listener for category change
        categorySelect.addEventListener('change', function() {
            currentCategoryId = this.value;
            loadNotes(currentCategoryId);
        });
        
        // Add event listeners for category modal
        addCategoryBtn.addEventListener('click', function() {
            categoryModal.style.display = 'block';
            newCategoryInput.value = '';
            newCategoryInput.focus();
        });
        
        cancelCategoryBtn.addEventListener('click', function() {
            categoryModal.style.display = 'none';
        });
        
        saveCategoryBtn.addEventListener('click', function() {
            saveNewCategory();
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === categoryModal) {
                categoryModal.style.display = 'none';
            }
        });
        
        // Allow pressing Enter to save new category
        newCategoryInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                saveNewCategory();
            }
        });
        
        function saveNewCategory() {
            const categoryName = newCategoryInput.value.trim();
            if (categoryName === '') {
                alert('Please enter a category name');
                return;
            }
            
            fetch('notesController.php?action=addCategory', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name: categoryName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new category to dropdown
                    const option = document.createElement('option');
                    option.value = data.category.id;
                    option.textContent = data.category.name;
                    categorySelect.appendChild(option);
                    
                    // Select the new category
                    categorySelect.value = data.category.id;
                    currentCategoryId = data.category.id;
                    
                    // Load notes (will be empty for new category)
                    loadNotes(currentCategoryId);
                    
                    // Close the modal
                    categoryModal.style.display = 'none';
                } else {
                    alert('Error adding category: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error adding category:', error);
                alert('Error adding category. Please try again.');
            });
        }
        
        function loadNotes(categoryId) {
            // Show loading message
            editor = editor || window.editor; // Use the editor from window if not yet available
            if (!editor) return; // Exit if editor not yet initialized
            
            editor.setContent('Loading your notes...');
            
            // AJAX request to load notes
            fetch(`notesController.php?action=load&category_id=${categoryId}`, {
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
            const content = editor.getContent() || '';
            
            // AJAX request to save notes
            fetch('notesController.php?action=save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    notes: content,
                    category_id: currentCategoryId
                })
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