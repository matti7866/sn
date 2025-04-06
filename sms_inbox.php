<?php
include 'header.php';
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}

include 'connection.php'; // Your database connection
?>

<div class="container-fluid mt-3">
    <h2>SMS Inbox</h2>
    <button id="refreshBtn" class="btn btn-primary mb-3">Refresh SMS</button>
    <table id="smsTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>From</th>
                <th>Message</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data loaded via DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal for Full Message -->
<div class="modal fade" id="smsModal" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="smsModalLabel">SMS Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> <span id="modalFrom"></span></p>
                <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <hr>
                <div id="modalFullMessage" style="white-space: pre-wrap; line-height: 1.6;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<script>
    $(document).ready(function() {
        var table = $('#smsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "SMSController.php",
                "type": "POST",
                "data": function(d) {
                    d.GetSMS = "GetSMS";
                },
                "error": function(xhr, status, error) {
                    console.log("AJAX error fetching SMS: " + xhr.responseText);
                    var errorMsg = xhr.responseJSON?.error || "Unknown error";
                    alert("Error fetching SMS: " + errorMsg);
                }
            },
            "columns": [
                { "data": "sender" },
                { 
                    "data": "message", 
                    "render": function(data) { 
                        return data.length > 50 ? data.substr(0, 50) + '...' : data; 
                    } 
                },
                { "data": "timestamp" },
                { "data": "status", "defaultContent": "Received" }
            ],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "order": [[2, "desc"]]
        });

        // Click event for row details
        $('#smsTable tbody').on('click', 'tr', function() {
            var data = table.row(this).data();
            if (!data) {
                console.log("No data available for this row");
                return;
            }
            $('#modalFrom').text(data.sender || 'N/A');
            $('#modalMessage').text(data.message || 'N/A');
            $('#modalDate').text(data.timestamp || 'N/A');
            $('#modalStatus').text(data.status || 'Received');

            var fullMessage = data.message || 'No message content available.';
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = fullMessage
                .replace(/<br\s*\/?>/gi, '\n')
                .replace(/<\/p>/gi, '\n\n')
                .replace(/<p[^>]*>/gi, '');
            var plainText = tempDiv.textContent || tempDiv.innerText || '';
            plainText = plainText.replace(/\n{3,}/g, '\n\n').trim();
            $('#modalFullMessage').text(plainText);

            $('#smsModal').modal('show');
        });

        // Refresh button
        $('#refreshBtn').on('click', function() {
            table.ajax.reload();
        });

        // Optional: Auto-refresh every 30 seconds
        setInterval(function() {
            table.ajax.reload(null, false); // false prevents page reset
        }, 30000);
    });
</script>
</body>
</html>