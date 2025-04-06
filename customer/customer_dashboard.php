<?php
ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400, '/', '.sntrips.com');
session_start();
ob_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: /customer/index.php");
    exit;
}

require_once 'connection.php';
try {
    $stmt = $pdo->prepare("SELECT customer_name FROM customer WHERE customer_id = :customer_id");
    $stmt->execute([':customer_id' => (int)$_SESSION['customer_id']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $customer_name = $customer ? $customer['customer_name'] : 'Customer';
} catch (Exception $e) {
    $customer_name = 'Customer';
    file_put_contents('/tmp/customer_fetch.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . "\n", FILE_APPEND);
}
unset($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Customer Dashboard - SN Travels</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
            padding-top: 30px;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
            transition: width 0.3s ease;
        }
        .sidenav .profile {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidenav .profile img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #fff;
            object-fit: cover;
            margin-bottom: 10px;
            transition: transform 0.3s ease;
        }
        .sidenav .profile img:hover {
            transform: scale(1.1);
        }
        .sidenav .customer-name {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }
        .sidenav .nav-link {
            padding: 15px 20px;
            color: #ecf0f1;
            font-size: 16px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .sidenav .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        .sidenav .nav-link:hover, .sidenav .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #00d4ff;
        }
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }
        .card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card-header {
            background: linear-gradient(to right, #007bff, #00d4ff);
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 15px;
            font-size: 22px;
            font-weight: 600;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead th {
            background: #343a40;
            color: #fff;
            font-weight: 600;
            border: none;
        }
        .table tbody tr:hover {
            background: #f8f9fa;
            transition: background 0.3s ease;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .sidenav {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="sidenav">
        <div class="profile">
            <img src="https://via.placeholder.com/80" alt="Avatar"> <!-- Replace with actual avatar path -->
            <span class="customer-name"><?php echo htmlspecialchars($customer_name); ?></span>
        </div>
        <a href="/customer/customer_dashboard.php" class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'customer_dashboard.php' ? ' active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> Passenger Status
        </a>
        <a href="/customer/statement.php" class="nav-link<?php echo basename($_SERVER['PHP_SELF']) === 'statement.php' ? ' active' : ''; ?>">
            <i class="fas fa-file-invoice-dollar"></i> Statement
        </a>
        <a href="/customer/logout.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">Passenger Status</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="passengerTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Passenger Name</th>
                                    <th>Establishment Name</th>
                                    <th>Current Status</th>
                                    <th>App#</th>
                                    <th>Total Paid</th>
                                    <th>Due Since</th>
                                    <th>Download</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#passengerTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "/customer/loginController.php",
                    "type": "POST",
                    "data": function(d) {
                        d.GetPassengerStatus = true;
                    },
                    "dataSrc": function(json) {
                        $.each(json.data, function(i, row) {
                            row.due_since = moment(row.due_since, 'YYYY-MM-DD').fromNow(true);
                        });
                        return json.data;
                    },
                    "error": function(xhr) {
                        console.log("AJAX error: " + xhr.responseText);
                    }
                },
                "columns": [
                    { "data": "customer_name" },
                    { "data": "passenger_name" },
                    { "data": "establishment_name" },
                    { "data": "current_status", "defaultContent": "Pending" },
                    { "data": "application_number", "defaultContent": "N/A" },
                    { "data": "total_paid", "render": $.fn.dataTable.render.number(',', '.', 2) },
                    { "data": "due_since" },
                    {
                        "data": "download_link",
                        "render": function(data) {
                            return data ? '<a href="' + data + '" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-download"></i></a>' : 'N/A';
                        },
                        "orderable": false
                    }
                ],
                "pageLength": 10,
                "lengthMenu": [10, 25, 50, 100],
                "order": [[6, "desc"]]
            });

            $('#passengerTable tbody').on('click', 'tr', function(e) {
                if ($(e.target).is('a') || $(e.target).parents('a').length) return;
                var data = table.row(this).data();
                $('#modalCustomerName').text(data.customer_name);
                $('#modalPassengerName').text(data.passenger_name);
                $('#modalEstablishmentName').text(data.establishment_name || 'N/A');
                $('#modalCurrentStatus').text(data.current_status || 'Pending');
                $('#modalApplicationNumber').text(data.application_number || 'N/A');
                $('#modalTotalPaid').text(data.total_paid ? parseFloat(data.total_paid).toLocaleString('en-US', { minimumFractionDigits: 2 }) : '0');
                $('#modalDueSince').text(data.due_since);
                $('#modalDownload').html(data.download_link ? '<a href="' + data.download_link + '" class="btn btn-primary btn-sm" target="_blank">Download</a>' : 'N/A');
                $('#statusModal').modal('show');
            });
        });
    </script>

    <!-- Modal for Details -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: linear-gradient(to right, #007bff, #00d4ff); color: white; border-top-left-radius: 10px; border-top-right-radius: 10px;">
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
                    <p><strong class="text-dark">Download Link:</strong> <span id="modalDownload"></span></p>
                </div>
                <div class="modal-footer" style="background-color: #f8f9fa; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #6c757d; border: none;">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php ob_end_flush(); ?>