<?php
include 'header.php';
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}
?>
<div class="container-fluid mt-3">
    <h2 class="text-primary mb-4">Passenger Status</h2>

    <table id="passengerTable" class="table table-striped table-bordered" style="width:100%; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <thead class="table-dark" style="background-color: #343a40; color: white;">
            <tr>
                <th>Customer Name</th>
                <th>Passenger Name</th>
                <th>Establishment Name</th>
                <th>Current Status</th>
                <th>App#</th>
                <th>Sale Price</th>
                <th>Total Paid</th>
                <th>Due Since</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data loaded via DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal for Details -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
            <div class="modal-header" style="background-color: #007bff; color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h5 class="modal-title" id="statusModalLabel">Passenger Status Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <p><strong class="text-dark">Customer Name:</strong> <span id="modalCustomerName" class="text-muted"></span></p>
                <p><strong class="text-dark">Passenger Name:</strong> <span id="modalPassengerName" class="text-muted"></span></p>
                <p><strong class="text-dark">Establishment Name:</strong> <span id="modalEstablishmentName" class="text-muted"></span></p>
                <p><strong class="text-dark">Current Status:</strong> <span id="modalCurrentStatus" class="text-muted"></span></p>
                <p><strong class="text-dark">Application Number:</strong> <span id="modalApplicationNumber" class="text-muted"></span></p>
                <p><strong class="text-dark">Total Paid:</strong> <span id="modalTotalPaid" class="text-muted"></span></p>
                <p><strong class="text-dark">Due Since:</strong> <span id="modalDueSince" class="text-muted"></span></p>
                <div id="modalSteps" class="mt-3">
                    <h6 class="text-dark">All Steps and Attachments:</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Step Name</th>
                                <th>Attachment</th>
                                <th>Date</th>
                                <th>Source</th>
                            </tr>
                        </thead>
                        <tbody id="modalStepsBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #f8f9fa; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #6c757d; border: none;">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
    body {
        background-color: #f1f4f8;
        font-family: 'Arial', sans-serif;
    }
    .container-fluid {
        padding: 20px;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #e9ecef;
    }
    .table-striped tbody tr:hover {
        background-color: #dee2e6;
        transition: background-color 0.3s ease;
    }
    .download-btn {
        background-color: #28a745 !important;
        border: none;
        padding: 5px 15px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
    .download-btn:hover {
        background-color: #218838 !important;
    }
    h2.text-primary {
        font-weight: bold;
        color: #007bff !important;
    }
    /* Style for status filter beside entries */
    .dataTables_length {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    #statusFilter {
        width: 200px;
    }
</style>

<script>
    $(document).ready(function() {
        // Define the status filter dropdown HTML
        var statusFilterHtml = `
            <label for="statusFilter">Filter by Status:</label>
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="Offer Letter Pending">Offer Letter Pending</option>
                <option value="Offer Letter Under Process">Offer Letter Under Process</option>
                <option value="Labour Approved">Labour Approved</option>
                <option value="Ready For E-Visa">Ready For E-Visa</option>
                <option value="E-Visa Under Process">E-Visa Under Process</option>
                <option value="E-Visa Approved">E-Visa Approved</option>
                <option value="Change Status Ready To Pay">Change Status Ready To Pay</option>
                <option value="Residency is Ready To Pay">Residency is Ready To Pay</option>
                <option value="Residency Approved">Residency Approved</option>
                <option value="Card Under Process">Card Under Process</option>
            </select>
        `;

        var table = $('#passengerTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "PassengerStatusController.php",
                "type": "POST",
                "data": function(d) {
                    d.GetPassengerStatus = "GetPassengerStatus";
                    d.status_filter = $('#statusFilter').val();
                },
                "error": function(xhr, status, error) {
                    console.log("AJAX error fetching passenger status: " + error);
                    console.log("Response text: " + xhr.responseText);
                    alert("Error fetching passenger status: " + error + "\nResponse: " + xhr.responseText);
                }
            },
            "columns": [
                { "data": "customer_name" },
                { "data": "passenger_name" },
                { "data": "establishment_name" },
                { "data": "current_status", "defaultContent": "Pending" },
                { "data": "application_number", "defaultContent": "N/A" },
                { "data": "sale_price", "render": function(data) { return data ? parseFloat(data).toLocaleString() : '0'; } },
                { "data": "total_paid", "render": function(data) { return data ? parseFloat(data).toLocaleString() : '0'; } },
                { "data": "due_since" },
                {
                    "data": "download_link",
                    "render": function(data, type, row) {
                        return data ? '<button class="btn btn-primary btn-sm download-btn" data-url="' + data + '" data-source="Email">Download</button>' : 'N/A';
                    },
                    "orderable": false
                }
            ],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "order": [[7, "desc"]],
            "initComplete": function() {
                // Append status filter beside the length menu
                $('.dataTables_length').append(statusFilterHtml);
                
                // Bind change event to status filter
                $('#statusFilter').on('change', function() {
                    table.ajax.reload();
                });
            }
        });

        // Click event to fetch and display detailed steps in modal
        $('#passengerTable tbody').on('click', 'tr', function(e) {
            if ($(e.target).hasClass('download-btn')) return;
            var data = table.row(this).data();

            $('#modalCustomerName').text(data.customer_name);
            $('#modalPassengerName').text(data.passenger_name);
            $('#modalEstablishmentName').text(data.establishment_name);
            $('#modalCurrentStatus').text(data.current_status || 'Pending');
            $('#modalApplicationNumber').text(data.application_number || 'N/A');
            $('#modalTotalPaid').text(data.total_paid ? parseFloat(data.total_paid).toLocaleString() : '0');
            $('#modalDueSince').text(data.due_since);

            $.ajax({
                url: 'PassengerStatusController.php',
                type: 'POST',
                data: {
                    GetPassengerDetails: true,
                    passenger_name: data.passenger_name
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.log('Error:', response.error);
                        $('#modalStepsBody').html('<tr><td colspan="4">Error loading steps: ' + response.error + '</td></tr>');
                    } else {
                        var stepsHtml = '';
                        response.steps.forEach(function(step) {
                            stepsHtml += '<tr>' +
                                '<td>' + (step.step_name || 'Unknown') + '</td>' +
                                '<td>' + (step.attachment ? '<button class="btn btn-primary btn-sm download-btn" data-url="' + step.attachment + '" data-original-name="' + (step.original_name || '') + '" data-source="' + step.source + '">Download</button>' : 'N/A') + '</td>' +
                                '<td>' + (step.datetime ? new Date(step.datetime).toLocaleString() : 'N/A') + '</td>' +
                                '<td>' + (step.source || 'N/A') + '</td>' +
                                '</tr>';
                        });
                        $('#modalStepsBody').html(stepsHtml);
                    }
                    $('#statusModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error fetching passenger details: ' + error);
                    $('#modalStepsBody').html('<tr><td colspan="4">Error fetching details: ' + error + '</td></tr>');
                    $('#statusModal').modal('show');
                }
            });
        });

        // Click event for download buttons
        $(document).on('click', '.download-btn', function() {
            var url = $(this).data('url');
            var original_name = $(this).data('original-name');
            var source = $(this).data('source');

            if (source === 'Email') {
                window.open(url, '_blank');
            } else if (source === 'Database') {
                fetch('PassengerStatusController.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'DownloadAttachment': true,
                        'file_path': url
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.blob();
                })
                .then(blob => {
                    var filename = original_name || url.split('/').pop();
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                })
                .catch(error => {
                    console.log('Error downloading file: ' + error);
                    alert('Error downloading file: ' + error);
                });
            }
        });
    });
</script>
</body>
</html>