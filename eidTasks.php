<?php
include 'header.php';
include 'nav.php';

// Define steps array
$steps = [
    ['name' => 'Pending Delivery', 'count' => 0, 'slug' => 'pending'],
    ['name' => 'Received', 'count' => 0, 'slug' => 'received'],
    ['name' => 'Delivered', 'count' => 0, 'slug' => 'delivered']
];

// Calculate step counts
foreach ($steps as &$step) {
    $whereCount = "";
    if ($step['slug'] == 'pending') $whereCount = "eid_received = 0 AND eid_delivered = 0";
    elseif ($step['slug'] == 'received') $whereCount = "eid_received = 1 AND eid_delivered = 0";
    elseif ($step['slug'] == 'delivered') $whereCount = "eid_delivered = 1 AND eid_received = 1";

    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM (
            SELECT residenceID FROM residence WHERE completedStep >= 7 AND $whereCount
            UNION
            SELECT id FROM freezone WHERE completedSteps >= 5 AND $whereCount
        ) AS total");
    $stmt->execute();
    $step['count'] = $stmt->fetchColumn();
}
unset($step);

// Determine current step
$currentStep = isset($_GET['step']) && in_array($_GET['step'], array_column($steps, 'slug')) ? $_GET['step'] : 'pending';

// Retrieve data based on current step
$where = "";
if ($currentStep == 'pending') {
    $where .= " AND eid_received = 0 AND eid_delivered = 0 ";
} elseif ($currentStep == 'received') {
    $where .= " AND eid_received = 1 AND eid_delivered = 0 ";
} elseif ($currentStep == 'delivered') {
    $where .= " AND eid_delivered = 1 AND eid_received = 1 ";
}

// Initialize $files as empty array
$files = [];

try {
    $stmp = $pdo->prepare("
        SELECT residenceID, passenger_name, passportNumber, EmiratesIDNumber, completedStep, customer.customer_name as customer_name,
        IFNULL((sale_price - (SELECT IFNULL(SUM(payment_amount),0) FROM customer_payments WHERE PaymentFor = residence.residenceID)),0) as remaining_balance,
        'ML' as `type`
        FROM residence 
        LEFT JOIN customer ON customer.customer_id = residence.customer_id
        WHERE completedStep >= 7 $where
        UNION 
        SELECT id as residenceID, passangerName as passenger_name, passportNumber, eidNumber as EmiratesIDNumber, completedSteps as completedStep, customer.customer_name as customer_name, 0 as remaining_balance, 'FZ' as `type`
        FROM freezone
        LEFT JOIN customer ON customer.customer_id = freezone.customerID
        WHERE completedSteps >= 5 $where
        GROUP BY residenceID 
        ORDER BY remaining_balance DESC, completedStep ASC");
    
    if ($stmp->execute()) {
        $files = $stmp->fetchAll(PDO::FETCH_OBJ) ?: [];
    } else {
        error_log("Query execution failed: " . implode(", ", $stmp->errorInfo()));
    }
} catch (PDOException $e) {
    error_log("PDO Exception: " . $e->getMessage());
    $files = [];
}
?>

<title>Emirates ID Tasks</title>
<?php if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
} ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="d-flex">
                <h3>Emirates ID Tasks</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12" id="message"></div>
    </div>

    <div class="row">
        <div class="col-md-12 btn-group mb-3">
            <?php foreach ($steps as $step): ?>
                <a href="eidTasks.php?step=<?= $step['slug'] ?>" class="btn <?php echo $step['slug'] == $currentStep ? 'btn-primary' : 'btn-white' ?>">
                    <?php echo $step['name'] ?>
                    <?php echo $step['count'] > 0 ? '<span class="badge bg-danger">' . $step['count'] . '</span>' : '' ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-title">Emirates ID Supplier</div>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="50">ID</th>
                                    <th>Customer Name</th>
                                    <th>Passenger Name</th>
                                    <th width="150">Passport Number</th>
                                    <th width="150">EID Number</th>
                                    <th width="150">Remaining Balance</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalRemainingBalances = 0;
                                if (!empty($files)) {
                                    foreach ($files as $file):
                                        $totalRemainingBalances += $file->remaining_balance;
                                ?>
                                        <tr id="row-<?= $file->residenceID ?>">
                                            <td><?= $file->residenceID ?></td>
                                            <td><?= $file->customer_name ?></td>
                                            <td><?= $file->passenger_name ?></td>
                                            <td><?= $file->passportNumber ?></td>
                                            <td><?= $file->EmiratesIDNumber ?></td>
                                            <td class="<?php echo $file->remaining_balance > 0 ? 'text-red' : '' ?>"><strong><?= number_format($file->remaining_balance, 2) ?></strong></td>
                                            <td><span class="badge bg-<?php echo $file->type == 'ML' ? 'success' : 'danger' ?>"><?= $file->type ?></span></td>
                                            <td>
                                                <?php
                                                if ($file->completedStep < 8) {
                                                    echo '<span class="badge bg-red">Waiting For Residency</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($currentStep == 'pending'): ?>
                                                    <button data-id="<?= $file->residenceID ?>" data-type="<?= $file->type ?>" class="btn btn-sm btn-success btn-setEidReceived">Mark as Received</button>
                                                <?php endif; ?>
                                                <?php if ($currentStep == 'received'): ?>
                                                    <button data-id="<?= $file->residenceID ?>" data-type="<?= $file->type ?>" class="btn btn-sm btn-info btn-setEidDelivered">Mark as Delivered</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                } else {
                                    echo '<tr><td colspan="9">No records found</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="text-right">
                            <strong>Total Remaining Balance: <?= number_format($totalRemainingBalances, 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Mark as Received -->
<div class="modal fade" id="modalMarkReceived" role="dialog" aria-labelledby="modalMarkReceivedLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="eidTasksController.php" method="POST" id="frmEmiratesID" class="frmAjax" data-msg="#msgMarkReceived" data-popup="modalMarkReceived" data-delete-row="true">
                <input type="hidden" name="action" value="setMarkReceived">
                <input type="hidden" name="id" id="emiratesIDID" value="">
                <input type="hidden" name="type" id="emiratesIDType" value="">
                <input type="hidden" id="residenceID" value="">
                <div class="modal-header bg-dark">
                    <h3 class="modal-title text-white" id="modalMarkReceivedLabel"><b><i>Mark as Received</i></b></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12" id="msgMarkReceived"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="eidNumber">EID Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="eidNumber" name="eidNumber" value="784-">
                            <div class="invalid-feedback eidNumber"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="eidExpiryDate">EID Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="eidExpiryDate" name="eidExpiryDate" value="<?php echo date("Y-m-d", strtotime("+2 years")) ?>">
                            <div class="invalid-feedback eidExpiryDate"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="passenger_name">Passenger Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="passenger_name" name="passenger_name" value="">
                            <div class="invalid-feedback passenger_name"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="gender">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-control">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            <div class="invalid-feedback gender"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="dob" name="dob" value="">
                            <div class="invalid-feedback dob"></div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="occupation">Occupation <span class="text-muted">(From ID)</span></label>
                            <select class="form-control" id="occupation" name="occupation">
                                <option value="">-- Select Occupation --</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                            <div class="invalid-feedback occupation"></div>
                            <small id="currentOccupation" class="text-muted"></small>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="establishmentName">Establishment Name <small class="text-muted">(From Emirates ID)</small></label>
                            <select class="form-control" id="establishmentName" name="establishmentName">
                                <option value="">-- Select Company --</option>
                                <!-- Options will be loaded via AJAX -->
                            </select>
                            <small id="currentCompany" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="emiratesIDBack">Emirates ID Front <small class="text-muted">(Upload to auto-extract details)</small></label>
                            <div class="input-group mb-2">
                                <input type="file" class="form-control" id="emiratesIDBack" name="emiratesIDBack" accept="image/jpeg,image/png,application/pdf" capture="environment">
                                <button type="button" class="btn btn-primary" id="cameraCapture"><i class="fa fa-camera"></i> Use Camera</button>
                            </div>
                            <small class="text-muted">Upload or capture front side of the Emirates ID for data extraction</small>
                            <div id="backPreviewContainer" class="mt-2 d-none">
                                <img id="backPreview" class="img-thumbnail" style="max-height: 200px;" />
                                <button type="button" class="btn btn-sm btn-danger mt-1" id="removeBackImage"><i class="fa fa-times"></i> Remove</button>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="overrideExistingData" checked>
                                <label class="form-check-label" for="overrideExistingData">
                                    Override existing data with Emirates ID information
                                </label>
                            </div>
                            <div id="cameraInterface" style="display:none;" class="my-3 p-2 border rounded bg-light">
                                <div class="text-center mb-2">
                                    <h5>Front Side Camera Capture</h5>
                                    <small class="text-muted">Position Emirates ID front in the frame and take a photo</small>
                                </div>
                                <video id="cameraPreview" style="width:100%; max-height:300px; object-fit:cover;" autoplay playsinline></video>
                                <canvas id="captureCanvas" style="display:none;"></canvas>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="btn btn-danger me-2" id="cancelCamera"><i class="fa fa-times"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" id="takePhoto"><i class="fa fa-camera"></i> Take Photo</button>
                                </div>
                            </div>
                            <div id="eidProcessingIndicator" style="display:none;" class="mt-2 p-2 bg-light border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fa fa-spinner fa-spin"></i> Processing Emirates ID Front... <span id="processingTime">0.0s</span>
                                        <div class="progress mt-1" style="height: 6px; width: 200px;">
                                            <div id="processingBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%"></div>
                                        </div>
                                    </div>
                                    <button type="button" id="cancelProcessing" class="btn btn-sm btn-outline-danger">Cancel</button>
                                </div>
                                <small class="text-muted d-block mt-1">Extraction may take 5-15 seconds. You can continue filling other fields.</small>
                            </div>
                            <div id="eidExtractionError" class="text-danger mt-1" style="display:none;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="emiratesIDFront">Emirates ID Back <small class="text-muted">(Upload to store in database)</small></label>
                            <div class="input-group mb-2">
                                <input type="file" class="form-control" id="emiratesIDFront" name="emiratesIDFront" accept="image/jpeg,image/png,application/pdf" capture="environment">
                                <button type="button" class="btn btn-primary" id="cameraCaptureIDFront"><i class="fa fa-camera"></i> Use Camera</button>
                            </div>
                            <small class="text-muted">Upload or capture back side of the Emirates ID for occupation data</small>
                            <div id="frontPreviewContainer" class="mt-2 d-none">
                                <img id="frontPreview" class="img-thumbnail" style="max-height: 200px;" />
                                <button type="button" class="btn btn-sm btn-danger mt-1" id="removeFrontImage"><i class="fa fa-times"></i> Remove</button>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="overrideOccupationData" checked>
                                <label class="form-check-label" for="overrideOccupationData">
                                    Override existing occupation with data from Emirates ID Back
                                </label>
                            </div>
                            <div id="frontCameraInterface" style="display:none;" class="my-3 p-2 border rounded bg-light">
                                <div class="text-center mb-2">
                                    <h5>Back Side Camera Capture</h5>
                                    <small class="text-muted">Position Emirates ID back in the frame and take a photo</small>
                                </div>
                                <video id="frontCameraPreview" style="width:100%; max-height:300px; object-fit:cover;" autoplay playsinline></video>
                                <canvas id="frontCaptureCanvas" style="display:none;"></canvas>
                                <div class="d-flex justify-content-center mt-2">
                                    <button type="button" class="btn btn-danger me-2" id="cancelFrontCamera"><i class="fa fa-times"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" id="takeFrontPhoto"><i class="fa fa-camera"></i> Take Photo</button>
                                </div>
                            </div>
                            <input type="hidden" id="frontImageData" name="frontImageData">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript">
$(document).ready(function() {
    // Load positions and companies for dropdowns when the page loads
    loadPositionsAndCompanies();
    
    // DataTable initialization
    if ($('#datatable').length) {
        var table = $('#datatable').DataTable({
            responsive: false,
            "order": [],
            "dom": 'frtip'
        });
    }

    // Camera capture for Emirates ID Front
    let frontStream = null;
    
    $('#cameraCaptureIDFront').on('click', function() {
        // Show camera interface
        $('#frontCameraInterface').show();
        
        // Access device camera
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment', // Use back camera
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        })
        .then(function(mediaStream) {
            frontStream = mediaStream;
            const video = document.querySelector('#frontCameraPreview');
            video.srcObject = mediaStream;
            video.onloadedmetadata = function() {
                video.play();
            };
        })
        .catch(function(err) {
            console.error("Camera access error: ", err);
            $('#frontCameraInterface').hide();
            alert('Camera access error: ' + err.message);
        });
    });
    
    $('#cancelFrontCamera').on('click', function() {
        stopFrontCamera();
    });
    
    $('#takeFrontPhoto').on('click', function() {
        // Take a photo
        const video = document.querySelector('#frontCameraPreview');
        const canvas = document.querySelector('#frontCaptureCanvas');
        
        // Set canvas dimensions to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw current video frame to canvas
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Convert to base64 for preview and storage
        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        
        // Set the preview
        $('#frontPreview').attr('src', imageData);
        $('#frontPreviewContainer').removeClass('d-none');
        
        // Store the image data in the hidden field for form submission
        $('#frontImageData').val(imageData);
        
        // Process for occupation data
        canvas.toBlob(function(blob) {
            const file = new File([blob], "back-capture.jpg", { type: "image/jpeg" });
            processEmiratesIDBack(file);
        }, 'image/jpeg', 0.95);
        
        // Stop camera
        stopFrontCamera();
    });
    
    function stopFrontCamera() {
        if (frontStream) {
            frontStream.getTracks().forEach(track => track.stop());
            frontStream = null;
        }
        $('#frontCameraInterface').hide();
    }

    // Handle remove front image button
    $('#removeFrontImage').on('click', function() {
        $('#frontPreview').attr('src', '');
        $('#frontPreviewContainer').addClass('d-none');
        $('#frontImageData').val('');
        $('#emiratesIDFront').val('');
    });
    
    // Handle file upload for front image
    $('#emiratesIDFront').on('change', function(e) {
        if (!this.files || !this.files[0]) return;
        
        const file = this.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $('#frontPreview').attr('src', e.target.result);
            $('#frontPreviewContainer').removeClass('d-none');
            $('#frontImageData').val(e.target.result);
            
            // Process back side for occupation data
            processEmiratesIDBack(file);
        };
        
        reader.readAsDataURL(file);
    });
    
    // Process Emirates ID back side for occupation data
    function processEmiratesIDBack(file) {
        // Create FormData
        const formData = new FormData();
        formData.append('emiratesIDFile', file);
        formData.append('extractorID', '6cf54d3c175705b9'); // Specific ID for the occupation extractor
        
        // Show processing for back side
        $('#frontPreviewContainer').append('<div id="backProcessing" class="mt-2"><i class="fa fa-spinner fa-spin"></i> Extracting occupation data...</div>');
        
        console.log('Processing Emirates ID back side for occupation with extractor ID: 6cf54d3c175705b9');
        
        // Send to backend
        fetch('processEmiratesIDBack.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Emirates ID back side API response status:', response.status);
            return response.json();
        })
        .then(data => {
            $('#backProcessing').remove();
            console.log('Occupation extraction response:', data);
            
            if (data.success && data.data && data.data.occupation) {
                console.log('Occupation extracted:', data.data.occupation);
                
                // For occupation dropdown, match with existing positions
                if ($('#overrideOccupationData').is(':checked')) {
                    findAndSelectOptionByText('#occupation', data.data.occupation);
                }
                
                // For company dropdown, match with existing companies
                if (data.data.establishment) {
                    findAndSelectOptionByText('#establishmentName', data.data.establishment);
                }
                
                // Add success indicator
                $('#frontPreviewContainer').append('<div class="text-success mt-1"><i class="fa fa-check-circle"></i> Occupation data extracted: ' + data.data.occupation + '</div>');
                if (data.data.establishment) {
                    $('#frontPreviewContainer').append('<div class="text-success mt-1"><i class="fa fa-check-circle"></i> Establishment extracted: ' + data.data.establishment + '</div>');
                }
            } else {
                // Show detailed extraction error
                console.error('Occupation extraction failed:', data.error || 'Unknown error');
                $('#frontPreviewContainer').append(
                    '<div class="text-danger mt-1">' +
                    '<i class="fa fa-exclamation-circle"></i> Could not extract occupation data' +
                    (data.error ? ': ' + data.error : '') +
                    '</div>'
                );
            }
        })
        .catch(error => {
            $('#backProcessing').remove();
            console.error('Emirates ID back side processing error:', error);
            $('#frontPreviewContainer').append('<div class="text-danger mt-1"><i class="fa fa-exclamation-circle"></i> Error processing back side: ' + error.message + '</div>');
        });
    }
    
    // Handle remove back image button
    $('#removeBackImage').on('click', function() {
        $('#backPreview').attr('src', '');
        $('#backPreviewContainer').addClass('d-none');
        $('#emiratesIDBack').val('');
    });

    // Camera capture for Emirates ID
    let stream = null;
    
    $('#cameraCapture').on('click', function() {
        // Show camera interface
        $('#cameraInterface').show();
        
        // Access device camera
        navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'environment', // Use back camera
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        })
        .then(function(mediaStream) {
            stream = mediaStream;
            const video = document.querySelector('#cameraPreview');
            video.srcObject = mediaStream;
            video.onloadedmetadata = function() {
                video.play();
            };
        })
        .catch(function(err) {
            console.error("Camera access error: ", err);
            $('#cameraInterface').hide();
            $('#eidExtractionError').text('Camera access error: ' + err.message).show();
        });
    });
    
    $('#cancelCamera').on('click', function() {
        stopCamera();
    });
    
    $('#takePhoto').on('click', function() {
        // Take a photo
        const video = document.querySelector('#cameraPreview');
        const canvas = document.querySelector('#captureCanvas');
        
        // Set canvas dimensions to match video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Draw current video frame to canvas
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Get image data for preview
        const imageDataUrl = canvas.toDataURL('image/jpeg', 0.8);
        
        // Set the preview for back image
        $('#backPreview').attr('src', imageDataUrl);
        $('#backPreviewContainer').removeClass('d-none');
        
        // Convert to blob for processing
        canvas.toBlob(function(blob) {
            // Create a File object from Blob for compatibility
            const file = new File([blob], "camera-capture.jpg", { type: "image/jpeg" });
            
            // Stop camera before processing
            stopCamera();
            
            // Process the captured image like a file upload
            processEmiratesIDFront(file);
        }, 'image/jpeg', 0.95);
    });
    
    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        $('#cameraInterface').hide();
    }
    
    // Function to process Emirates ID front
    function processEmiratesIDFront(file) {
        // Show processing indicator and hide any previous error
        $('#eidProcessingIndicator').show();
        $('#eidExtractionError').hide();
        
        // Cancel any previous request
        if (eidController) {
            eidController.abort();
        }
        
        // Create new abort controller
        eidController = new AbortController();
        
        // Start timer for processing feedback
        const startTime = Date.now();
        $('#processingBar').css('width', '5%');
        
        const timerInterval = setInterval(() => {
            const elapsedSeconds = (Date.now() - startTime) / 1000;
            $('#processingTime').text(elapsedSeconds.toFixed(1) + 's');
            
            // Update progress bar - estimate 15 seconds total time
            const progressPercent = Math.min(Math.floor((elapsedSeconds / 15) * 100), 95);
            $('#processingBar').css('width', progressPercent + '%');
        }, 100);
        
        // Pre-process image to optimize for OCR and reduce file size
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = new Image();
                img.onload = function() {
                    // Create canvas for image processing
                    const canvas = document.createElement('canvas');
                    
                    // Calculate new dimensions (max width 1000px while maintaining aspect ratio)
                    let width = img.width;
                    let height = img.height;
                    const maxWidth = 1000;
                    
                    if (width > maxWidth) {
                        const ratio = maxWidth / width;
                        width = maxWidth;
                        height = height * ratio;
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    
                    // Draw image in grayscale (better for OCR)
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    // Convert to grayscale
                    const imageData = ctx.getImageData(0, 0, width, height);
                    const data = imageData.data;
                    for (let i = 0; i < data.length; i += 4) {
                        const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
                        data[i] = avg;     // red
                        data[i + 1] = avg; // green
                        data[i + 2] = avg; // blue
                    }
                    ctx.putImageData(imageData, 0, 0);
                    
                    // Convert to optimized JPEG with good quality
                    const optimizedImage = canvas.toDataURL('image/jpeg', 0.8);
                    
                    // Convert base64 to blob
                    const byteString = atob(optimizedImage.split(',')[1]);
                    const mimeString = optimizedImage.split(',')[0].split(':')[1].split(';')[0];
                    const ab = new ArrayBuffer(byteString.length);
                    const ia = new Uint8Array(ab);
                    for (let i = 0; i < byteString.length; i++) {
                        ia[i] = byteString.charCodeAt(i);
                    }
                    const optimizedBlob = new Blob([ab], {type: mimeString});
                    
                    // Log optimization results
                    console.log('Original image size:', Math.round(file.size / 1024), 'KB');
                    console.log('Optimized image size:', Math.round(optimizedBlob.size / 1024), 'KB');
                    console.log('Size reduction:', Math.round((1 - (optimizedBlob.size / file.size)) * 100), '%');
                    
                    sendOptimizedFrontFile(optimizedBlob);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // Not an image, send as is (PDF, etc.)
            sendOptimizedFrontFile(file);
        }
        
        function sendOptimizedFrontFile(fileToSend) {
            // Create FormData with optimization hint
            const formData = new FormData();
            formData.append('emiratesIDFile', fileToSend, file.name);
            formData.append('optimize', 'true');  // Hint for server to use faster processing if available
            formData.append('preprocessed', 'true');  // Tell server image is already optimized
            
            // Update progress bar to show preprocessing is complete
            $('#processingBar').css('width', '20%');
            
            // Add timeout to prevent hanging
            const timeoutPromise = new Promise((_, reject) => {
                setTimeout(() => reject(new Error('Request timed out after 20 seconds')), 20000);
            });
            
            // Send to processEmiratesID.php with timeout and abort signal
            Promise.race([
                fetch('processEmiratesID.php', {
                    method: 'POST',
                    body: formData,
                    signal: eidController.signal
                }),
                timeoutPromise
            ])
            .then(response => {
                console.log('Emirates ID API response status:', response.status);
                return response.json();
            })
            .then(data => {
                clearInterval(timerInterval);
                $('#processingBar').css('width', '100%');
                
                // Hide processing indicator after a short delay to show completion
                setTimeout(() => {
                    $('#eidProcessingIndicator').hide();
                    $('#processingBar').css('width', '0%');
                }, 500);
                
                console.log('Emirates ID data:', data);
                console.log('Full data inspection:');
                console.log('- Success:', data.success);
                console.log('- Error:', data.error);
                console.log('- Data object:', data.data);
                if (data.data) {
                    console.log('-- EID Number:', data.data.eid_number);
                    console.log('-- Expiry Date:', data.data.expiry_date);
                    console.log('-- Name:', data.data.name);
                    console.log('-- DOB:', data.data.dob);
                    console.log('-- Gender:', data.data.gender);
                }
                
                if (data.success) {
                    // Auto-fill fields with extracted data
                    if (data.data.eid_number) {
                        $('#eidNumber').val(data.data.eid_number);
                    }
                    
                    if (data.data.expiry_date) {
                        $('#eidExpiryDate').val(data.data.expiry_date);
                    }
                    
                    const shouldOverride = $('#overrideExistingData').is(':checked');
                    
                    // Fill passenger details based on override setting
                    if ((shouldOverride || !$('#passenger_name').val()) && data.data.name) {
                        console.log('Setting passenger name to:', data.data.name);
                        $('#passenger_name').val(data.data.name);
                        console.log('Passenger name field value after setting:', $('#passenger_name').val());
                    } else {
                        console.log('Not setting passenger name. Current value:', $('#passenger_name').val(), 'Name in data:', data.data.name);
                    }
                    
                    if ((shouldOverride || !$('#dob').val()) && data.data.dob) {
                        console.log('Setting DOB to:', data.data.dob);
                        $('#dob').val(data.data.dob);
                        console.log('DOB field value after setting:', $('#dob').val());
                    } else {
                        console.log('Not setting DOB. Current value:', $('#dob').val(), 'DOB in data:', data.data.dob);
                    }
                    
                    if (data.data.gender) {
                        const gender = data.data.gender.toLowerCase();
                        if (gender === 'm' || gender === 'male') {
                            $('#gender').val('male');
                        } else if (gender === 'f' || gender === 'female') {
                            $('#gender').val('female');
                        }
                    }
                } else {
                    // Show error message
                    $('#eidExtractionError').text(data.data?.extraction_error || 'Failed to extract data from Emirates ID').show();
                }
            })
            .catch(error => {
                console.error('Emirates ID processing error:', error);
                $('#eidProcessingIndicator').hide();
                $('#eidExtractionError').text('Error processing Emirates ID: ' + error.message).show();
            });
        }
    }
    
    // Emirates ID back image processing
    let eidController = null;
    
    $('#emiratesIDBack').on('change', function(e) {
        if (!this.files || !this.files[0]) return;
        
        const file = this.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            $('#backPreview').attr('src', e.target.result);
            $('#backPreviewContainer').removeClass('d-none');
        };
        
        reader.readAsDataURL(file);
        
        processEmiratesIDFront(this.files[0]);
    });

    // Form validation
    $('.form-control, .form-select').on('change keyup', function() {
        var vl = $(this).val();
        if (vl == '') {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    // Mark as Received button - Use event delegation
    $(document).on('click', '.btn-setEidReceived', function() {
        console.log('Mark as Received button clicked');
        
        var btn = $(this);
        var id = btn.data('id');
        var type = btn.data('type');
        
        console.log('ID:', id, 'Type:', type);
        
        btn.prop('disabled', true);
        
        $.ajax({
            url: 'eidTasksController.php',
            type: 'POST',
            data: {
                action: 'getResidence',
                id: id,
                type: type
            },
            beforeSend: function() {
                console.log('AJAX request started');
                $('#message').html('<div class="alert alert-info">Loading data...</div>');
            },
            success: function(e) {
                console.log('AJAX Success:', e);
                btn.prop('disabled', false);
                
                if (e && typeof e === 'object' && e.residence) {
                    console.log('Full residence data:', e.residence);
                    
                    $('#emiratesIDID').val(id);
                    $('#emiratesIDType').val(type);
                    $('#residenceID').val(id);
                    $("#passenger_name").val(e.residence.passenger_name || '');
                    $("#dob").val(e.residence.dob || '');
                    $("#gender").val(e.residence.gender || 'male');
                    
                    // Debug log for occupation-related fields
                    console.log('Occupation fields:', {
                        positionID: e.residence.positionID,
                        positionName: e.residence.positionName,
                        position: e.residence.position
                    });
                    
                    // Handle occupation dropdown selection
                    if (e.residence.positionID) {
                        $("#currentOccupation").html('<strong>Current occupation:</strong> ' + e.residence.positionName);
                        // Set the dropdown value to match the position ID
                        $("#occupation").val(e.residence.positionID);
                    } else {
                        $("#currentOccupation").html('<em>No occupation data available</em>');
                    }
                    
                    // Handle company dropdown selection
                    if (e.residence.company && e.residence.company_name) {
                        $("#currentCompany").html('<strong>Current company:</strong> ' + e.residence.company_name);
                        // Set the dropdown value to match the company ID
                        $("#establishmentName").val(e.residence.company);
                    } else {
                        $("#currentCompany").html('');
                    }
                    
                    console.log('Showing modal');
                    $('#modalMarkReceived').modal('show');
                    $('#message').html('');
                } else {
                    let errorMsg = 'Invalid response from server';
                    if (e && e.message) {
                        errorMsg = e.message;
                    }
                    $('#message').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
                btn.prop('disabled', false);
                $('#message').html('<div class="alert alert-danger">Error loading residence data: ' + error + '</div>');
            }
        });
    });

    // Mark as Delivered button - Use event delegation
    $(document).on('click', '.btn-setEidDelivered', function() {
        var btn = $(this);
        var id = btn.data('id');
        var type = btn.data('type');
        
        $.confirm({
            title: 'Confirm',
            content: 'Are you sure you want to mark this Emirates ID as delivered?',
            buttons: {
                confirm: function() {
                    $.ajax({
                        url: 'eidTasksController.php',
                        type: 'POST',
                        data: {
                            action: 'setMarkDelivered',
                            id: id,
                            type: type
                        },
                        success: function(e) {
                            $('#row-' + id).fadeOut('slow', function() {
                                $(this).remove();
                            });
                            $("#message").html('<div class="alert alert-success">' + e.message + '</div>');
                        }
                    });
                },
                cancel: function() {}
            }
        });
    });

    // Form submission with debug logging
    $('.frmAjax').on('submit', function(e) {
        e.preventDefault();
        console.log('Form submit event triggered');
        var frm = $(this);
        var btn = frm.find('button[type="submit"]');
        var formData = new FormData(frm[0]);
        
        console.log('Form action:', frm.attr('action'));
        console.log('Form method:', frm.attr('method'));
        
        if (frm.attr('data-delete-row') == 'true') {
            frm.attr('data-id', frm.find('input[name="id"]').val());
        }
        
        // Debug log the form values
        if (frm.attr('id') === 'frmEmiratesID') {
            console.log('Emirates ID Form Data:', {
                emiratesIDID: $('#emiratesIDID').val(),
                residenceID: $('#residenceID').val(),
                type: $('#emiratesIDType').val(),
                occupation: $('#occupation').val(),
                establishment: $('#establishmentName').val()
            });
            
            // Make sure the occupation field is properly named
            if ($('#occupation').val()) {
                console.log('Adding occupation to FormData');
                formData.append('occupation', $('#occupation').val());
            }
            
            // Make sure the establishment field is properly named
            if ($('#establishmentName').val()) {
                console.log('Adding establishmentName to FormData');
                formData.append('establishmentName', $('#establishmentName').val());
            }
        }

        console.log('Sending AJAX request...');
        $.ajax({
            url: frm.attr('action'),
            type: frm.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                console.log('Before send callback');
                btn.attr('data-temp-text', btn.html()).attr('disabled', true);
                btn.html('Submitting...');
            },
            success: function(response) {
                console.log('Raw Response:', response);
                btn.attr('disabled', false).html(btn.attr('data-temp-text'));
                
                // Handle both string and object responses
                var e = response;
                if (typeof response === 'string') {
                    try {
                        e = JSON.parse(response);
                        console.log('Parsed JSON response:', e);
                    } catch (err) {
                        console.error('Failed to parse response as JSON:', err);
                    }
                }
                
                if (e && e.status === 'success') {
                    console.log('Success detected');
                    if (frm.attr('data-delete-row') === 'true') {
                        $('#row-' + frm.attr('data-id')).fadeOut('slow', function() {
                            $(this).remove();
                        });
                    }
                    
                    // Close the modal
                    var popupId = frm.data('popup');
                    console.log('Popup ID:', popupId);
                    if (popupId) {
                        if (popupId.startsWith('#')) {
                            $(popupId).modal('hide');
                        } else {
                            $('#' + popupId).modal('hide');
                        }
                    }
                    
                    // Show success message
                    $('#message').html('<div class="alert alert-success">' + e.message + '</div>');
                    
                    // Reload the page after a short delay
                    setTimeout(function() {
                        console.log('Reloading page...');
                        location.reload();
                    }, 1000);
                } else {
                    console.log('Error or invalid response');
                    if (e && e.message === 'form_errors') {
                        $.each(e.errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value);
                        });
                    } else {
                        var msg = (e && e.message) ? e.message : 'Unknown error';
                        var msgTarget = frm.attr('data-msg');
                        console.log('Message target:', msgTarget);
                        if (msgTarget) {
                            $('#' + msgTarget).html('<div class="alert alert-danger">' + msg + '</div>');
                        } else {
                            $('#message').html('<div class="alert alert-danger">' + msg + '</div>');
                        }
                    }
                    // Re-enable the button
                    btn.attr('disabled', false).html(btn.attr('data-temp-text'));
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.error('Response text:', xhr.responseText);
                btn.attr('disabled', false).html(btn.attr('data-temp-text'));
                $('#message').html('<div class="alert alert-danger">An error occurred while saving: ' + error + '</div>');
            }
        });
    });

    // Cancel processing button
    $('#cancelProcessing').on('click', function() {
        if (eidController) {
            eidController.abort();
            eidController = null;
            $('#eidProcessingIndicator').hide();
            $('#eidExtractionError').text('Processing cancelled by user').show();
        }
    });

    // Function to load positions and companies into dropdowns
    function loadPositionsAndCompanies() {
        // Load positions
        $.ajax({
            url: 'eidTasksController.php',
            type: 'POST',
            data: {
                action: 'getPositions'
            },
            success: function(response) {
                if (response.status === 'success' && response.positions) {
                    populateDropdown('#occupation', response.positions, 'position_id', 'position_name');
                } else {
                    console.error('Failed to load positions:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading positions:', error);
            }
        });
        
        // Load companies
        $.ajax({
            url: 'eidTasksController.php',
            type: 'POST',
            data: {
                action: 'getCompanies'
            },
            success: function(response) {
                if (response.status === 'success' && response.companies) {
                    populateDropdown('#establishmentName', response.companies, 'company_id', 'company_name');
                } else {
                    console.error('Failed to load companies:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading companies:', error);
            }
        });
    }

    // Helper function to populate a dropdown
    function populateDropdown(selector, data, valueField, textField) {
        const dropdown = $(selector);
        // Keep the first placeholder option and clear the rest
        dropdown.find("option:gt(0)").remove();
        
        // Add the options
        $.each(data, function(index, item) {
            dropdown.append(new Option(item[textField], item[valueField]));
        });
    }

    // Helper function to find and select dropdown option by text (partial match)
    function findAndSelectOptionByText(selector, searchText) {
        if (!searchText) return;
        
        // Convert search text to uppercase for case-insensitive matching
        const searchUpper = searchText.toUpperCase();
        
        // Check each option for partial match
        let bestMatch = null;
        let bestMatchScore = 0;
        
        $(selector + ' option').each(function() {
            const optionText = $(this).text().toUpperCase();
            
            // Skip the placeholder
            if (optionText.includes('SELECT')) return;
            
            // Check if option text contains the search text
            if (optionText.includes(searchUpper) || searchUpper.includes(optionText)) {
                // Calculate match score (higher is better)
                const matchScore = Math.min(optionText.length, searchUpper.length) / 
                                   Math.max(optionText.length, searchUpper.length);
                
                // Update best match if this is better
                if (matchScore > bestMatchScore) {
                    bestMatch = $(this).val();
                    bestMatchScore = matchScore;
                }
            }
        });
        
        // If we found a match, select it
        if (bestMatch && bestMatchScore > 0.3) { // At least 30% match
            $(selector).val(bestMatch);
        } else {
            // If no good match, try to create new option dynamically
            // In production, this should be replaced with a proper "Add New" feature
            console.log('No match found for', searchText, 'in', selector);
        }
    }
});
</script>