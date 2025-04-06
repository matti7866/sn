<?php
include 'header.php';
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}
?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="panel">
        <div class="panel-heading  bg-inverse">
          <div class="panel-title">Add New Residence</div>
        </div>
        <div class="panel-body">
          123
        </div>
      </div>
    </div>
  </div>
</div>
<?php include 'footer.php' ?>