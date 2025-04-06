<?php include 'header.php' ?>
<title>New Transactions</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}
// Update SQL query to fetch permissions for the new page  
$sql = "SELECT permission.select, permission.update, permission.delete, permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'NewTransactions' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();

// fetch single row  
$record = $stmt->fetch();


// get the types  
$sql = "SELECT * FROM `amer_types`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll();


?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Manage Amer Transaction Types</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <button type="button" class="btn btn-success" id="btnAddType">Add Type</button>
    </div>
  </div>
  <div class="row">

    <div class="col-md-12" id="message"></div>
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title">Amer Transaction Types</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Type</th>
              <th>Cost Price</th>
              <th>Sale Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="types">
            <?php foreach ($types as $type): ?>
              <tr>
                <td><?php echo $type['id'] ?></td>
                <td><?php echo $type['name'] ?></td>
                <td><?php echo $type['cost_price'] ?></td>
                <td><?php echo $type['sale_price'] ?></td>
                <td>
                  <button data-id="<?php echo $type['id'] ?>" class="btn btn-primary btn-sm btn-edit">Edit</button>
                  <button data-id="<?php echo $type['id'] ?>" class="btn btn-danger btn-sm btn-delete">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddTransaction" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="amerController.php" id="frmAdd" method="POST" class="frmAjax" data-message="#message">
    <input type="hidden" name="action" value="addType" id="frmTypeAction">
    <input type="hidden" name="id" id="frmTypeID" value="0">
    <div class=" modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="frmTypeTitle">Add Amer Transaction Type</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-12 mb-2">
              <label for="customer_ID" class="form-label">Transaction Type <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control" value="">
              <div class="invalid-feedback name"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="passenger_name" class="form-label">Cost Price <span class="text-danger">*</span></label>
              <input type="text" name="cost_price" id="cost_price" class="form-control" value="">
              <div class="invalid-feedback cost_price"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="sale_price" class="form-label">Sale Price <span class="text-danger">*</span></label>
              <input type="text" name="sale_price" id="sale_price" class="form-control" value="">
              <div class="invalid-feedback sale_price"></div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveAdd" class="btn btn-success">Save</button>
        </div>
  </form>
</div>
</div>
</div>

<script type="text/javascript">
  function getTypes() {
    $.ajax({
      url: 'amerController.php',
      type: 'POST',
      data: {
        action: 'getTypes'
      },
      success: function(response) {
        var html = '';
        $.each(response.data, function(index, type) {
          html += '<tr id="row-' + type.id + '"><td>' + type.id + '</td><td>' + type.name + '</td><td>' + type.cost_price + '</td><td>' + type.sale_price + '</td><td><button class="btn btn-primary btn-sm btn-edit" data-id="' + type.id + '">Edit</button><button class="btn btn-danger btn-sm btn-delete" data-id="' + type.id + '">Delete</button></td></tr>';
        });
        $('#types').html(html);
      }
    });
  }

  $(document).ready(function() {

    $('#btnAddType').on('click', function() {
      $('#frmTypeID').val(0);
      $("#frmTypeAction").val('addType');
      $('#frmTypeTitle').html('Add Amer Transaction Type');
      $('#modalAddTransaction').modal('show');
    });

    $('.form-select,input[type=file]').on('change', function() {
      var vl = $(this).val();
      if (vl == '') {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });

    $('#types').on('click', '.btn-edit', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'amerController.php',
        type: 'POST',
        data: {
          action: 'getType',
          id: id
        },
        success: function(response) {
          $('#frmTypeID').val(response.data.id);
          $("#frmTypeAction").val('updateType');
          $('#name').val(response.data.name);
          $('#cost_price').val(response.data.cost_price);
          $('#sale_price').val(response.data.sale_price);
          $('#frmTypeTitle').html('Update Amer Transaction Type');
          $('#modalAddTransaction').modal('show');
        }
      });
    });

    $('#types').on('click', '.btn-delete', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'amerController.php',
        type: 'POST',
        data: {
          action: 'deleteType',
          id: id
        },
        success: function(response) {
          $('#row-' + id).fadeOut('slow', function() {
            $(this).remove();
          });
          $('#message').html('<div class="alert alert-success">' + response.message + '</div>');
          getTypes();
        }
      });
    });


    $('.frmAjax').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = frm.find('button[type="submit"]');
      frm.find('.invalid-feedback').html('');
      frm.find('.is-invalid').removeClass('is-invalid');
      var formData = new FormData(frm[0]);


      if (frm.attr('data-delete-row') == 'true') {
        frm.attr('data-id', frm.find('input[name="id"]').val());
      }

      $.ajax({
        url: frm.attr('action'),
        type: frm.attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
          btn.attr('data-temp-text', btn.html()).attr('disabled', true);
        },
        success: function(e) {
          btn.attr('disabled', false).html(btn.attr('data-temp-text'));
          if (e.status == 'success') {


            if (frm.attr('data-delete-row') == 'true') {
              $('#row-' + frm.attr('data-id')).fadeOut('slow', function() {
                $(this).remove();
              });
            }

            if (frm.data('popup')) {
              $('#' + frm.data('popup')).modal('hide');
            }
            $('#message').html('<div class="alert alert-success">' + e.message + '</div>');

            frm[0].reset();
            // close the modal
            $('#modalAddTransaction').modal('hide');
            getTypes();


          } else {
            if (e.message == 'form_errors') {
              $.each(e.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').siblings('.invalid-feedback').html(value);
              });
            } else {
              $(frm.attr('data-message')).html('<div class="alert alert-danger">' + e.message + '</div>');
            }
          }
        },
        error: function(resp) {
          btn.attr('disabled', false).html(btn.attr('data-temp-text'));
        }
      });
    });
  });
</script>