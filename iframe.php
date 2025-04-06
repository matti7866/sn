<?php
  include 'header.php';
?>
<title>Hotel Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <iframe src="https://calm-shadow-ec1b.rayan.workers.dev/" style="width:100vw; height:100vh"></iframe>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>