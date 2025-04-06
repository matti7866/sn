<?php include 'header.php' ?>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($id == '') {
  header('location:agents.php');
}

$req = $pdo->prepare("
SELECT agents.*, customer.customer_name, IFNULL(staff.staff_name,'') as staff_name
FROM agents 
LEFT JOIN customer ON customer.customer_id = agents.customer_id 
LEFT JOIN staff ON staff.staff_id = agents.added_by
WHERE agents.id = :id ");
$req->execute(['id' => $id]);
if ($req->rowCount() == 0) {
  header('location:agents.php');
}
$agent = $req->fetch(PDO::FETCH_OBJ);


?>
<div class="container-fluid">

  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Agent Details (<?php echo $agent->company ?>)</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <a href="agents.php" class="btn btn-success">View All Agents</a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12" id="message"></div>
  </div>
  <div class="row">
    <div class="col-md-12 mb-2">
      <?php if ($agent->status == 1):  ?>
        <button class="btn btn-danger btn-changeStatus" data-action="0">Suspend Agent</button>
      <?php else: ?>
        <button class="btn btn-success btn-changeStatus" data-action="1">Activate Agent</button>
      <?php endif; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-5 form-group">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h3 class="panel-title">Agent Details</h3>
        </div>
        <table class="table table-striped table-bordered mb-0">
          <tbody>
            <tr>
              <th>ID</th>
              <td><?php echo $agent->id ?></td>
            </tr>
            <tr>
              <th>Company/Agency Name</th>
              <td><?php echo $agent->company ?></td>
            </tr>
            <tr>
              <th>Email Address</th>
              <td><?php echo $agent->email ?></td>
            </tr>
            <tr>
              <th>Customer Attached</th>
              <td><?php echo $agent->customer_id ?></td>
            </tr>
            <tr>
              <th>Status</th>
              <td>
                <!-- status with label -->
                <?php if ($agent->status == 1):  ?>
                  <span class="badge bg-success">Active</span>
                <?php else: ?>
                  <span class="badge bg-danger">Inactive</span>
                <?php endif; ?>

              </td>
            </tr>
            <!-- datetime_created -->
            <tr>
              <th>Added At</th>
              <td><?php echo $agent->datetime_added ?></td>
            </tr>
            <!-- datetime_updated -->
            <tr>
              <th>Updated At</th>
              <td><?php echo $agent->datetime_updated ?></td>
            </tr>
            <!-- last_login_ip -->
            <tr>
              <th>Last Login IP</th>
              <td><?php echo $agent->last_login_ip ?></td>
            </tr>
            <!-- last_login -->
            <tr>
              <th>Last Login</th>
              <td><?php echo $agent->last_login_datetime ?></td>
            </tr>
            <tr>
              <th>Added By</th>
              <td><?php echo $agent->staff_name ?></td>
            </tr>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require 'footer.php' ?>

<script type="text/javascript">
  $(document).ready(function() {
    $('.btn-changeStatus').click(function() {
      var btn = $(this);
      var msg = $('#message');

      msg.html('');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('Please wait...');
      $.ajax({
        url: 'viewAgentsController.php',
        method: 'POST',
        data: {
          action: 'changeStatus',
          id: <?php echo $agent->id ?>,
          status: btn.attr('data-action')
        },
        error: function() {
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
          msg.html('<div class="alert alert-danger">An error occurred. Please try again later.</div>');
        },
        success: function(response) {
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
          if (response.status == 'success') {
            msg.html('<div class="alert alert-success">' + response.message + '</div>');
            setTimeout(function() {
              location.reload();
            }, 2000);
          } else {
            msg.html('<div class="alert alert-danger">' + response.message + '</div>');
          }
        }
      });
    });
  });
</script>