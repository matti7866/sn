<?php include 'header.php' ?>
<title>Delete Request</title>
<?php 
  require 'connection.php';
  include 'nav.php';
  if(!isset($_SESSION['user_id'])){
    header('location:login.php');
  }

  $types = array(
    'residence' => 'Residence'
  );

  $user_id = $_SESSION['user_id'];

  if( $user_id != 1 ){
    header('location: /');
  }

  $stm = $pdo->prepare("SELECT * FROM staff ");
  $stm->execute();
  $staffs = $stm->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Delete Requests</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading"><h3 class="panel-title">Search Requests</h3></div>
        <div class="panel-body">
          <form action="" method="POST" id="frmSearch">
            <input type="hidden" name="action" value="searchDeleteRequests" />
            <div class="row">
              <div class="col-md-1 mb-2">
                <label class="form-check-label" for="bydate">By Date</label>
                <div>
                  <input type="checkbox" name="bydate" id="bydate" class="form-check-input" value="1">
                </div>
              </div>
              <div class="col-md-2 mb-2 col-date" style="display:none">
                <label for="startDate"  class="form-label">From Date</label>
                <input type="text" name="startDate" id="startDate" class="form-control" value="<?php echo date('Y-m-01') ?>" />
              </div>
              <div class="col-md-2 mb-2 col-date" style="display:none">
                <label for="endDate"  class="form-label">To Date</label>
                <input type="text" name="endDate" id="endDate" class="form-control" value="<?php echo date('Y-m-d') ?>" />
              </div>
              <div class="col-md-2">
                <label for="staff_id"  class="form-label">Staff</label>
                <select name="staff_id" id="staff_id" class="form-select">
                  <option value="">All</option>
                  <?php foreach($staffs as $staff): ?>
                    <option value="<?php echo $staff['staff_id'] ?>"><?php echo $staff['staff_name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select">
                  <option value="">Select</option>
                  <option value="residence">Residence</option>
                </select>
              </div>
              <div class="col-md-2 mb-2">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                  <option value="pending">Pending</option>
                  <option value="accepted">Accepted</option>
                  <option value="rejected">Rejected</option>
                  <option value="all">All</option>
                </select>
              </div>
              
              <div class="col-md-1 mb-2">
                <label for="" class="form-label">&nbsp;</label>
                <button id="btnSearch" class="btn btn-primary btn-block w-100"><i class="fa fa-filter"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12" id="message"></div>
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title">Delete Requests</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Type</th>
              <th>Deleted By</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody  id="requests">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalRequestDetails"  role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Request Details</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body" id="requestDetails">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function(){

    

   
    $('#startDate').dateTimePicker();
    $('#endDate').dateTimePicker();

    $("#bydate").on('change', function(){
      if( $(this).is(':checked') ){
        $('.col-date').show();
      }else{
        $('.col-date').hide();
      }
    });




    function loadTransactions(){
      
      return new Promise((resolve, reject) =>{
        var frm = $('#frmSearch');
        var btn = $('#btnSearch');
        var msg = $('#message');

        msg.html('');
        btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'deleteRequestsController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function(){
            msg.html('<div class="alert alert-danger">An error occured while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e){
            $('#requests').html('');
            if( e.status == 'success' ){
              $('#requests').html(e.html);
            }else{
              msg.html('<div class="alert alert-danger">'+e.message+'</div>');
            }
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
            resolve();
          }
        });
      });
    }

    $('#frmSearch').submit(function(e){
      e.preventDefault();
      loadTransactions();
    });

    loadTransactions();


    $('#requests').on('click','.btn-approval',function(){
      var thisBtn = $(this);
      var msg = $('#message');
      $.confirm({
        title: 'Delete',
        content: 'Are you sure you want to '+$(this).html()+' this request?',
        type: 'red',
        typeAnimated: true,
        buttons: {
          tryAgain:{
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              var id = thisBtn.attr('data-id');
              var action = thisBtn.attr('data-action');
              $.ajax({
                url: 'deleteRequestsController.php',
                method: 'POST',
                data: {action:action,id:id},
                error: function(){
                  msg.html('<div class="alert alert-danger">An error occured while processing your request</div>');
                },
                success: function(e){
                  if( e.status == 'success' ){
                    loadTransactions().then(function(){
                      msg.html('<div class="alert alert-success">'+e.message+'</div>');
                    });
                  }else{
                    $('#message').html('<div class="alert alert-danger">'+e.message+'</div>');
                  }
                }
              });
            }
          },
          cancel: {}
        }
      });
    });


    $('#requests').on('click','.btn-request-details',function(){
      var id = $(this).attr('data-id');
      var modal = $('#modalRequestDetails');
      var dataContainer = $('#requestDetails');
      modal.modal('show');

      dataContainer.html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
      $.ajax({
        url: 'deleteRequestsController.php',
        method: 'POST',
        data: {action:'getRequestDetails',id:id},
        error: function(){
          dataContainer.html('<div class="alert alert-danger">An error occured while loading request details</div>');
        },
        success: function(e){
          if( e.status == 'success' ){
            dataContainer.html(e.html);
          }else{
            dataContainer.html('<div class="alert alert-danger">'+e.message+'</div>');
          }
        }
      });

    })
    
    
  });
</script>