<?php include 'header.php' ?>
<title>Agents</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}

$query = $pdo->prepare('SELECT customer_id, customer_name FROM customer ORDER BY customer_name ASC');
$query->execute();
$customers = $query->fetchAll(PDO::FETCH_OBJ);

?>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Manage Agents</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <button type="button" id="btnAddNewTransaction" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAgent">Add Agent</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h3 class="panel-title">Search Agents</h3>
        </div>
        <div class="panel-body">
          <form action="" method="POST" id="frmSearch">
            <input type="hidden" name="action" value="searchAgents" />
            <div class="row">

              <div class="col-md-6">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
              </div>
              <div class="col-md-2 mb-2">
                <label for="type" class="form-label">Status</label>
                <select name="type" id="type" class="form-select">
                  <option value="">All</option>
                  <option value="1">Active</option>
                  <option value="0">Suspended</option>
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
          <h4 class="panel-title">Agents</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Agency / Company Name / Customer</th>
              <th>Email</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="agents">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddAgent" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmAdd" method="POST">
    <input type="hidden" name="action" value="addAgent">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>New Agent</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-12 mb-2">
              <label for="companyAdd" class="form-label">Agent / Company Name <span class="text-danger">*</span></label>
              <input type="text" name="companyAdd" id="companyAdd" class="form-control" />
              <div class="invalid-feedback companyAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="customerAdd" class="form-label">Select Customer Account <span class="text-danger">*</span></label>
              <select name="customerAdd" id="customerAdd" class="form-select">
                <option value="">Select</option>
                <?php foreach ($customers as $customer) : ?>
                  <option value="<?= $customer->customer_id ?>"><?= $customer->customer_name ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback customerAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="emailAdd" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="emailAdd" id="emailAdd" class="form-control" />
              <div class="invalid-feedback emailAdd"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveAdd" class="btn btn-success">Save</button>
        </div>
      </div>
    </div>
  </form>
</div>


<div class="modal fade" id="modalEditAgent" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateAgent" />
    <input type="hidden" name="idEdit" id="idEdit" value="" />
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Agent</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-12 mb-2">
              <label for="companyEdit" class="form-label">Agent / Company Name <span class="text-danger">*</span></label>
              <input type="text" name="companyEdit" id="companyEdit" class="form-control" />
              <div class="invalid-feedback companyEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="customerEdit" class="form-label">Customer Account <span class="text-danger">*</span></label>
              <select name="customerEdit" id="customerEdit" class="form-select">
                <option value="">Select</option>
                <?php foreach ($customers as $customer) : ?>
                  <option value="<?= $customer->customer_id ?>"><?= $customer->customer_name ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback customerAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="emailEdit" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" name="emailEdit" id="emailEdit" class="form-control" />
              <div class="invalid-feedback emailEdit"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveEdit" class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
  $(document).ready(function() {

    $('.form-select,input[type=file]').on('change', function() {
      var vl = $(this).val();

      if (vl == '') {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });
    $('.form-control').on('keyup', function() {
      var vl = $(this).val();
      if (vl == '') {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });


    function loadAgents() {

      return new Promise((resolve, reject) => {
        var frm = $('#frmSearch');
        var btn = $('#btnSearch');
        var msg = $('#message');

        msg.html('');
        btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'agentsController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function() {
            msg.html('<div class="alert alert-danger">An error occured while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e) {
            $('#agents').html('');
            if (e.status == 'success') {
              $('#agents').html(e.html);
            } else {
              msg.html('<div class="alert alert-danger">' + e.message + '</div>');
            }
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
            resolve();
          }
        });
      });
    }

    $('#frmSearch').submit(function(e) {
      e.preventDefault();
      loadAgents();
    });

    loadAgents();

    $('#frmAdd').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = $('#btnSaveAdd');
      var msg = $('#message');
      var msgAdd = $("#msgAdd");

      msgAdd.html('');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');

      var formData = new FormData();
      frm.find('input,select,textarea').each(function() {
        var element = $(this);
        if (element.attr('type') == 'file') {
          var file = element[0].files[0];
          formData.append(element.attr('name'), file);
        } else if (element.attr('type') == 'checkbox') {
          if (element.prop('checked')) {
            formData.append(element.attr('name'), element.val());
          }
        } else if (element.attr('type') == 'radio') {
          if (element.prop('checked')) {
            formData.append(element.attr('name'), element.val());
          }
        } else {
          formData.append(element.attr('name'), element.val());
        }
      });

      $.ajax({
        url: '/agentsController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
          msgAdd.html('<div class="alert alert-danger">An error occured while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          if (res.status == 'success') {

            frm[0].reset();
            $('#modalAddAgent').modal('hide');
            loadAgents().then(() => {
              msg.html('<div class="alert alert-success">' + res.message + '</div>');
            });
          } else {
            if (res.message == 'form_errors') {
              $.each(res.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            } else {
              msgAdd.html('<div class="alert alert-danger">' + res.message + '</div>');
            }
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    $('#agents').on('click', '.btn-delete', function() {
      var btn = $(this);
      var id = btn.attr('data-id');
      if (confirm('Are you sure you want to delete this agent record?')) {
        btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'agentsController.php',
          method: 'POST',
          data: {
            action: 'deleteAgent',
            id: id
          },
          error: function() {
            $('#message').html('<div class="alert alert-danger">An error occured while deleting</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(res) {
            if (res.status == 'success') {
              loadAgents();
            } else {
              $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
            }
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          }
        });
      }
    });

    $('#agents').on('click', '.btn-edit', function() {
      // open the edit modal
      var btn = $(this);
      var id = btn.attr('data-id');
      var modal = $('#modalEditAgent');

      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        url: 'agentsController.php',
        method: 'POST',
        data: {
          action: 'getAgent',
          id: id
        },
        error: function() {
          $('#message').html('<div class="alert alert-danger">An error occured while loading transaction details</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(e) {
          if (e.status == 'success') {
            var data = e.data;


            modal.find('input,select,textarea').each(function() {
              var element = $(this);
              element.removeClass('is-invalid');
              element.next('.invalid-feedback').html('');
            });

            $("#idEdit").val(data.id);
            $("#companyEdit").val(data.company);
            $("#customerEdit").val(data.customer_id);
            $("#emailEdit").val(data.email);



            modal.modal('show');
          } else {
            $('#message').html('<div class="alert alert-danger">' + e.message + '</div>');
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });

    });

    $('#frmEdit').on('submit', function() {
      var frm = $(this);
      var btn = $('#btnSaveEdit');
      var msg = $('#message');
      var modal = $('#modalEditAgent');
      var msgEdit = $("#msgEdit");

      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      var formData = new FormData();
      frm.find('input,select,textarea').each(function() {
        var element = $(this);
        if (element.attr('type') == 'file') {
          var file = element[0].files[0];
          formData.append(element.attr('name'), file);
        } else if (element.attr('type') == 'checkbox') {
          if (element.prop('checked')) {
            formData.append(element.attr('name'), element.val());
          }
        } else if (element.attr('type') == 'radio') {
          if (element.prop('checked')) {
            formData.append(element.attr('name'), element.val());
          }
        } else {
          formData.append(element.attr('name'), element.val());
        }
      });
      $.ajax({
        url: 'agentsController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
          msgEdit.html('<div class="alert alert-danger">An error occured while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
          if (res.status == 'success') {
            loadAgents().then(function() {
              msgEdit.html('<div class="alert alert-success">' + res.message + '</div>');
              modal.modal('hide');
            });
          } else {
            if (res.message == 'form_errors') {
              $.each(res.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            } else {
              msgEdit.html('<div class="alert alert-danger">' + res.message + '</div>');
            }
          }
        }
      });

      return false;
    });

  });
</script>