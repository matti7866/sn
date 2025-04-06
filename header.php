<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location:login.php');
    exit();
}

// Query for selecting employee name and picture
include 'connection.php';
$staffpic = "SELECT staff_pic FROM staff WHERE staff_id=" . $_SESSION['user_id'];
$result = $mysqli->query($staffpic); // Changed $conn to $mysqli
$row20 = $result->fetch_assoc();

$query5 = "SELECT staff_name FROM staff WHERE staff_id=" . $_SESSION['user_id'];
$row5 = $mysqli->query($query5); // Changed $conn to $mysqli
$staffname = $row5->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Selab Nadiry</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="Selab Nadiry Travel And Tourisim Portal" name="description" />
    <meta content="Selab Nadiry, Sntrips, Travel And Tourisim, Dubai Travel And Tourisim" name="keywords">
    <meta content="Mattiullah Nadiry" name="author" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:3 00,400,600,700" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/css/vendor.min.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/css/default/app.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="iCheck/all.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="logoselab.png">
    <link href="color_admin_v5.0/admin/template/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <link href="pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="pnotify/dist/pnotify.brighttheme.css" rel="stylesheet">
    <link href="pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="confirmation/dist/jquery-confirm.min.css" rel="stylesheet">
    <link href="HoldOn/HoldOn.min.css" rel="stylesheet">
    <link href="color_admin_v5.0/admin/template/assets/css/default/theme/red.min.css" rel="stylesheet" id="themeCss">
    <link href="color_admin_v5.0/admin/template/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link href="color_admin_v5.0/admin/template/assets/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />
    
    <link href="dataTable/datatable.min.css" rel="stylesheet" />
    

    <!-- Custom CSS for DataTables and Sidebar -->
    <style>
        div.dataTables_wrapper div.dataTables_filter {
            display: block !important;
            visibility: visible !important;
            margin-bottom: 10px;
            float: right;
        }

        .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            overflow-y: auto;
            z-index: 1030;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
    </style>

    <!-- Downgraded jQuery to 3.5.1 -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://unpkg.com/tesseract.js@5.0.0/dist/tesseract.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
</head>