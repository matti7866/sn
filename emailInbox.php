<?php
include 'header.php';
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}
?>
<div class="container-fluid mt-3">
    <h2>Email Inbox</h2>
    <table id="emailTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Application Number</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data loaded via DataTables -->
        </tbody>
    </table>
</div>

<!-- Modal for Full Message -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">Email Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> <span id="modalFrom"></span></p>
                <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
                <p><strong>Date:</strong> <span id="modalDate"></span></p>
                <p><strong>Application Number:</strong> <span id="modalAppNumber"></span></p>
                <p><strong>Download Link:</strong> <span id="modalDownload"></span></p>
                <hr>
                <div id="modalFullMessage" style="white-space: pre-wrap; line-height: 1.6;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Downloading -->
<div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Downloading File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="downloadStatus">Downloading your file... Please wait.</p>
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
        var table = $('#emailTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "GmailController.php",
                "type": "POST",
                "data": function(d) {
                    d.GetEmails = "GetEmails";
                    d.Filter = "inbox";
                },
                "error": function(xhr, status, error) {
                    console.log("AJAX error fetching emails: " + error);
                    alert("Error fetching emails: " + error);
                }
            },
            "columns": [
                { "data": "from_email" },
                { "data": "subject" },
                { "data": "received_at" },
                { "data": "application_number", "defaultContent": "N/A" },
                {
                    "data": "download_link",
                    "render": function(data, type, row) {
                        return data ? '<button class="btn btn-primary btn-sm download-btn" data-url="' + data + '">Download</button>' : 'N/A';
                    },
                    "orderable": false
                }
            ],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50, 100],
            "order": [[2, "desc"]]
        });

        // Add click event to rows for full message
        $('#emailTable tbody').on('click', 'tr', function(e) {
            if ($(e.target).hasClass('download-btn')) return; // Ignore if clicking download button
            var data = table.row(this).data();
            $('#modalFrom').text(data.from_email);
            $('#modalSubject').text(data.subject);
            $('#modalDate').text(data.received_at);
            $('#modalAppNumber').text(data.application_number || 'N/A');
            $('#modalDownload').html(data.download_link ? '<button class="btn btn-primary btn-sm download-btn" data-url="' + data.download_link + '">Download</button>' : 'N/A');

            var fullMessage = data.full_message || 'No message content available.';
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = fullMessage
                .replace(/<br\s*\/?>/gi, '\n')
                .replace(/<\/p>/gi, '\n\n')
                .replace(/<p[^>]*>/gi, '');
            var plainText = tempDiv.textContent || tempDiv.innerText || '';
            plainText = plainText.replace(/\n{3,}/g, '\n\n').trim();
            $('#modalFullMessage').text(plainText);

            $('#emailModal').modal('show');
        });

        // Add click event to download buttons
        $(document).on('click', '.download-btn', function() {
            var url = $(this).data('url');
            $('#downloadModal').modal('show');
            $('#downloadStatus').text("Downloading your file... Please wait.");

            // Create a hidden link to trigger download
            var link = document.createElement('a');
            link.href = url;
            link.download = ''; // Suggests download without specifying filename
            link.style.display = 'none'; // Keep it hidden
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Update status after triggering download
            setTimeout(function() {
                $('#downloadStatus').text("Download started! You can close this modal.");
            }, 1000); // 1-second delay to give feedback
        });
    });
</script>
</body>
</html>