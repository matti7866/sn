<?php
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['user_id'])) {
      header('location:../../../login.php');
    }
    // Query for selecting employee name
    require_once   '../../../api/connection/index.php';
    $sql = "select staff_name, staff_pic from staff where staff_id= :staff_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':staff_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $row20 = $records[0]['staff_name'];
    $staff_pic = $records[0]['staff_pic'];
    
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="../../../color_admin_v5.0/admin/template/assets/css/vendor.min.css" rel="stylesheet" />
  <link href="../../../color_admin_v5.0/admin/template/assets/css/default/app.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="../../../iCheck/all.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="icon" type="image/png" href="../../../logoselab.png">
  <link href="../../../color_admin_v5.0/admin/template/assets/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
  <link href="../../../color_admin_v5.0/admin/template/assets/plugins/nvd3/build/nv.d3.css" rel="stylesheet" />
  
  <link href="../../../color_admin_v5.0/admin/template/assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />
    <!-- Pnotify-->
    <link href="../../../pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../../../pnotify/dist/pnotify.brighttheme.css" rel="stylesheet">
    <link href="../../../pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <!-- Hold On Style -->
    <link href="../../../confirmation/dist/jquery-confirm.min.css" rel="stylesheet">
    <link href="../../../HoldOn/HoldOn.min.css" rel="stylesheet">
    <link href="../../../color_admin_v5.0/admin/template/assets/css/default/theme/red.min.css" rel="stylesheet" id="themeCss">
    
    <!-- required files -->
    <link href="../../../color_admin_v5.0/admin/template/assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="../../../color_admin_v5.0/admin/template/assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="../../../color_admin_v5.0/admin/template/assets/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />
<link href="../../../color_admin_v5.0/admin/template/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
<link href="../../../dataTable/datatable.min.css" rel="stylesheet" />
    
    
 

 