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
    <title>Customer Statement - SN Travels</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
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
        .btn-export {
            background: #28a745;
            border-color: #28a745;
        }
        .btn-export:hover {
            background: #218838;
            border-color: #218838;
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
                <div class="card-header">Financial Statement</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="currencySelect" class="form-label fw-bold">Select Currency:</label>
                            <select id="currencySelect" class="form-select" style="width: 200px;"></select>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-custom btn-primary me-2" id="printButton">Print</button>
                            <button class="btn btn-custom btn-export" id="exportPdfButton">Export PDF</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="statementTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S#</th>
                                    <th>Passenger Name</th>
                                    <th>Establishment Name</th>
                                    <th>Due Since</th>
                                    <th>Total Sale</th>
                                    <th>Total Fine</th>
                                    <th>Total Paid</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Totals:</th>
                                    <th id="totalSaleFooter"></th>
                                    <th id="totalFineFooter"></th>
                                    <th id="totalPaidFooter"></th>
                                    <th id="totalBalanceFooter"></th>
                                </tr>
                            </tfoot>
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
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            loadCurrencies();
            var table = $('#statementTable').DataTable({
                "processing": true,
                "serverSide": false,
                "paging": false,
                "ajax": {
                    "url": "/customer/loginController.php",
                    "type": "POST",
                    "data": function(d) {
                        d.GetResidenceReport = true;
                        d.CurID = $('#currencySelect').val() || 1;
                    },
                    "dataSrc": function(json) {
                        let totalSale = 0, totalFine = 0, totalPaid = 0, totalBalance = 0;
                        $.each(json, function(i, row) {
                            row.sno = i + 1;
                            row.due_since = moment(row.dt).fromNow(true);
                            row.total_paid = parseFloat(row.residencePayment) + parseFloat(row.finePayment);
                            row.balance = parseFloat(row.sale_price) + parseFloat(row.fine) - parseFloat(row.residencePayment) - parseFloat(row.finePayment);
                            totalSale += parseFloat(row.sale_price);
                            totalFine += parseFloat(row.fine);
                            totalPaid += row.total_paid;
                            totalBalance += row.balance;
                        });
                        $('#totalSaleFooter').text(totalSale.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('#totalFineFooter').text(totalFine.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('#totalPaidFooter').text(totalPaid.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        $('#totalBalanceFooter').text(totalBalance.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                        return json;
                    },
                    "error": function(xhr) {
                        console.log("AJAX error: " + xhr.responseText);
                    }
                },
                "columns": [
                    { "data": "sno" },
                    { "data": "main_passenger" },
                    { "data": "company_name" },
                    { "data": "due_since" },
                    { "data": "sale_price", "render": $.fn.dataTable.render.number(',', '.', 2) },
                    { "data": "fine", "render": $.fn.dataTable.render.number(',', '.', 2) },
                    { "data": "total_paid", "render": $.fn.dataTable.render.number(',', '.', 2) },
                    { "data": "balance", "render": $.fn.dataTable.render.number(',', '.', 2) }
                ],
                "order": [[3, "desc"]],
                "language": {
                    "emptyTable": "No financial data available for this currency."
                },
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-primary btn-custom d-none',
                        title: 'Customer Financial Statement - SN Travels',
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('table').addClass('table table-striped');
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export PDF',
                        className: 'btn btn-export btn-custom d-none',
                        title: 'Customer Financial Statement - SN Travels',
                        customize: function(doc) {
                            doc.styles.title = { fontSize: 16, alignment: 'center' };
                            doc.styles.tableHeader = { fillColor: '#343a40', color: 'white', alignment: 'center' };
                        }
                    }
                ]
            });

            $('#currencySelect').on('change', function() {
                table.ajax.reload();
            });

            $('#printButton').on('click', function() {
                table.button('.buttons-print').trigger();
            });

            $('#exportPdfButton').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });
        });

        function loadCurrencies() {
            $.ajax({
                url: '/customer/loginController.php',
                type: 'POST',
                data: { GetLedgerCurrency: true, ID: 1 }, // Adjust for all currencies if needed
                dataType: 'json',
                success: function(currencies) {
                    let options = '';
                    $.each(currencies, function(index, currency) {
                        options += `<option value="${index + 1}">${currency.currencyName}</option>`;
                    });
                    $('#currencySelect').html(options);
                },
                error: function(xhr) {
                    console.log('Error fetching currencies: ' + xhr.responseText);
                }
            });
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>