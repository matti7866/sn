<?php include 'header.php' ?>
<title>Delete Request</title>
<?php
require 'connection.php';
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
}

$user_id = $_SESSION['user_id'];
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3><i class="fa fa-building"></i> Manage Establishments</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <button data-bs-toggle="modal" data-bs-target="#modalAddCompany" class="btn btn-success ml-auto">Add New Establishment</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h3 class="panel-title">Search Establishments</h3>
        </div>
        <div class="panel-body">
          <form action="" method="POST" id="frmSearch">
            <input type="hidden" name="action" value="searchCompanies" />
            <div class="row">
              <div class="col-md-4 mb-2">
                <label for="search" class="form-label">Search Company</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search Company" />
              </div>
              <div class="col-md-2 mb-2">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select">
                  <option value="">Select</option>
                  <option value="Mainland">Mainland</option>
                  <option value="Freezone">Freezone</option>
                </select>
              </div>
              <div class="col-md-1 mb-2">
                <label for="" class="form-label"> </label>
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
          <h4 class="panel-title">Establishments</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Type</th>
              <th>Local Name</th>
              <th>Expiry Date</th>
              <th>Starting Quota</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="companies"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require 'footer.php' ?>

<!-- Add Establishment Modal -->
<div class="modal fade" id="modalAddCompany" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmAdd" method="POST">
    <input type="hidden" name="action" value="addCompany">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add New Establishment</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-12 mb-2">
              <label for="nameAdd" class="form-label">Establishment Name <span class="text-danger">*</span></label>
              <input type="text" name="nameAdd" id="nameAdd" class="form-control" value="">
              <div class="invalid-feedback nameAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="numberAdd" class="form-label">Company Number <span class="text-danger">*</span></label>
              <input type="text" name="numberAdd" id="numberAdd" class="form-control" value="">
              <div class="invalid-feedback numberAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="expiryAdd" class="form-label">Expiry Date <span class="text-danger">*</span></label>
              <input type="text" name="expiryAdd" id="expiryAdd" class="form-control" value="">
              <div class="invalid-feedback expiryAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select" id="typeAdd" name="typeAdd">
                <option value="">Choose type</option>
                <option value="Mainland">Mainland</option>
                <option value="Freezone">Freezone</option>
              </select>
              <div class="invalid-feedback typeAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="localNameAdd" class="form-label">Local Name</label>
              <input type="text" class="form-control" id="localNameAdd" name="localNameAdd" />
              <div class="invalid-feedback localNameAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="quotaAdd" class="form-label">Starting Quota <span class="text-danger">*</span></label>
              <input type="number" value="0" name="quotaAdd" id="quotaAdd" class="form-control" value="">
              <div class="invalid-feedback quotaAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="usernameAdd" class="form-label">Username</label>
              <input type="text" name="usernameAdd" id="usernameAdd" class="form-control" />
              <div class="invalid-feedback usernameAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="passwordAdd" class="form-label">Password</label>
              <input type="text" name="passwordAdd" id="passwordAdd" class="form-control" />
              <div class="invalid-feedback passwordAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="letterHeadAdd">Letterhead Image</label>
              <input type="file" name="letterHead" id="letterHeadAdd" class="form-control" accept="image/*" />
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="stampAdd">Stamp Image</label>
              <input type="file" name="stampAdd" id="stampAdd" class="form-control" accept="image/*" />
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="signatureAdd">Signature</label>
              <input type="file" name="signatureAdd" id="signatureAdd" class="form-control" accept="image/*" />
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

<!-- Edit Establishment Modal -->
<div class="modal fade" id="modalEditCompany" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="updateCompany">
    <input type="hidden" name="idEdit" id="idEdit" value="">
    <input type="hidden" name="processedStamp" id="processedStamp">
    <input type="hidden" name="processedSignature" id="processedSignature">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Establishment</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-12 mb-2">
              <label for="nameEdit" class="form-label">Establishment Name <span class="text-danger">*</span></label>
              <input type="text" name="nameEdit" id="nameEdit" class="form-control" value="">
              <div class="invalid-feedback nameEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="numberEdit" class="form-label">Company Number <span class="text-danger">*</span></label>
              <input type="text" name="numberEdit" id="numberEdit" class="form-control" value="">
              <div class="invalid-feedback numberEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="expiryEdit" class="form-label">Expiry Date <span class="text-danger">*</span></label>
              <input type="text" name="expiryEdit" id="expiryEdit" class="form-control" value="">
              <div class="invalid-feedback expiryEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="typeEdit" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select" id="typeEdit" name="typeEdit">
                <option value="Mainland">Mainland</option>
                <option value="Freezone">Freezone</option>
              </select>
              <div class="invalid-feedback typeEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="localNameEdit" class="form-label">Local Name</label>
              <input type="text" class="form-control" id="localNameEdit" name="localNameEdit" />
              <div class="invalid-feedback localNameEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="quotaEdit" class="form-label">Starting Quota <span class="text-danger">*</span></label>
              <input type="number" value="0" name="quotaEdit" id="quotaEdit" class="form-control" value="">
              <div class="invalid-feedback quotaEdit"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-label" for="usernameEdit">Username</label>
              <input type="text" name="usernameEdit" id="usernameEdit" class="form-control" />
              <div class="invalid-feedback usernameEdit"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-label" for="passwordEdit">Password</label>
              <input type="text" name="passwordEdit" id="passwordEdit" class="form-control" />
              <div class="invalid-feedback passwordEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="letterHeadEdit">Letterhead Image</label>
              <div class="input-group">
                <input type="file" name="letterHeadEdit" id="letterHeadEdit" class="form-control" accept="image/*" />
                <a href="" id="letterHeadEditLink" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                <button id="letterHeadEditDelete" type="button" class="btn btn-danger btn-delete-image" data-type="letterhead" data-id=""><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="stampEdit">Stamp Image</label>
              <div class="input-group">
                <input type="file" name="stampEdit" id="stampEdit" class="form-control" accept="image/*" />
                <a href="" id="stampEditLink" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                <button id="stampEditDelete" type="button" class="btn btn-danger btn-delete-image" data-type="stamp" data-id=""><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="signatureEdit">Signature</label>
              <div class="input-group">
                <input type="file" name="signatureEdit" id="signatureEdit" class="form-control" accept="image/*" />
                <a href="" id="signatureEditLink" target="_blank" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                <button id="signatureEditDelete" type="button" class="btn btn-danger btn-delete-image" data-type="signature" data-id=""><i class="fa fa-times"></i></button>
              </div>
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

<!-- Add Quota Modal -->
<div class="modal fade" id="modalAddQuota" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmQuota" method="POST">
    <input type="hidden" name="action" value="addQuota">
    <input type="hidden" name="idQuota" id="idQuota" value="">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Quota to Establishment</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 mb-2" id="msgQuota"></div>
            <div class="col-md-4 mb-2">
              <label for="quotaQuota" class="form-label">New Quota <span class="text-danger">*</span></label>
              <input type="number" value="0" name="quotaQuota" id="quotaQuota" class="form-control">
              <div class="invalid-feedback quotaQuota"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveQuota" class="btn btn-success">Add Quota</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('#expiryAdd').datepicker({
      format: 'yyyy-mm-dd'
    });
    $('#expiryEdit').datepicker({
      format: 'yyyy-mm-dd'
    });

    function loadCompanies() {
      return new Promise((resolve, reject) => {
        var frm = $('#frmSearch');
        var btn = $('#btnSearch');
        var msg = $('#message');

        msg.html('');
        btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'manageEstablishmentsController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function() {
            msg.html('<div class="alert alert-danger">An error occurred while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e) {
            $('#companies').html('');
            if (e.status == 'success') {
              $('#companies').html(e.html);
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
      loadCompanies();
    });

    loadCompanies();

    $('.form-select, input[type=file]').on('change', function() {
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

    $('#frmAdd').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = $('#btnSaveAdd');
      var msg = $('#message');
      var msgAdd = $("#msgAdd");

      msgAdd.html('');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');

      var formData = new FormData();
      frm.find('input, select, textarea').each(function() {
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
        url: '/manageEstablishmentsController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
          msgAdd.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          if (res.status == 'success') {
            frm[0].reset();
            $('#modalAddCompany').modal('hide');
            loadCompanies().then(() => {
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

    // Function to remove background using remove.bg API
    async function removeBackground(file) {
      const apiKey = 'nWowAkcCWvtKGtCH465hrCnz'; // Your remove.bg API key
      const formData = new FormData();
      formData.append('image_file', file);
      formData.append('size', 'auto');

      try {
        const response = await fetch('https://api.remove.bg/v1.0/removebg', {
          method: 'POST',
          headers: {
            'X-Api-Key': apiKey
          },
          body: formData
        });

        if (!response.ok) {
          throw new Error(`Remove.bg API error: ${response.statusText}`);
        }

        const blob = await response.blob();
        return new Promise((resolve) => {
          const reader = new FileReader();
          reader.onloadend = () => resolve(reader.result); // Base64 string
          reader.readAsDataURL(blob);
        });
      } catch (error) {
        console.error('Background removal failed:', error);
        return null;
      }
    }

    // Handle file input changes for stamp and signature in edit modal
    $('#stampEdit, #signatureEdit').on('change', async function(e) {
      const file = e.target.files[0];
      if (!file) return;

      const inputId = $(this).attr('id');
      const processedFieldId = inputId === 'stampEdit' ? '#processedStamp' : '#processedSignature';

      $(this).parent().append('<span class="processing text-muted">Processing...</span>');

      const processedImage = await removeBackground(file);
      if (processedImage) {
        $(processedFieldId).val(processedImage);
      } else {
        alert('Failed to remove background for ' + (inputId === 'stampEdit' ? 'stamp' : 'signature'));
      }

      $(this).parent().find('.processing').remove();
    });

    $('#frmEdit').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = $('#btnSaveEdit');
      var msg = $('#message');
      var msgEdit = $("#msgEdit");

      msgEdit.html('');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');

      var formData = new FormData();
      frm.find('input, select, textarea').each(function() {
        var element = $(this);
        if (element.attr('type') === 'file' && element.attr('id') !== 'stampEdit' && element.attr('id') !== 'signatureEdit') {
          var file = element[0].files[0];
          if (file) formData.append(element.attr('name'), file);
        } else if (element.attr('id') === 'processedStamp' && element.val()) {
          const blob = dataURLtoBlob(element.val());
          formData.append('stampEdit', blob, 'stamp.png');
        } else if (element.attr('id') === 'processedSignature' && element.val()) {
          const blob = dataURLtoBlob(element.val());
          formData.append('signatureEdit', blob, 'signature.png');
        } else if (element.attr('type') !== 'file') {
          formData.append(element.attr('name'), element.val());
        }
      });

      $.ajax({
        url: '/manageEstablishmentsController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
          msgEdit.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          if (res.status === 'success') {
            frm[0].reset();
            $('#modalEditCompany').modal('hide');
            loadCompanies().then(() => {
              msg.html('<div class="alert alert-success">' + res.message + '</div>');
            });
          } else {
            if (res.message === 'form_errors') {
              $.each(res.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            } else {
              msgEdit.html('<div class="alert alert-danger">' + res.message + '</div>');
            }
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    // Helper function to convert base64 to Blob
    function dataURLtoBlob(dataURL) {
      const byteString = atob(dataURL.split(',')[1]);
      const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
      const ab = new ArrayBuffer(byteString.length);
      const ia = new Uint8Array(ab);
      for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
      }
      return new Blob([ab], { type: mimeString });
    }

    $('#frmQuota').on('submit', function(e) {
      e.preventDefault();
      var frm = $(this);
      var btn = $('#btnSaveQuota');
      var msg = $('#message');
      var msgQuota = $("#msgQuota");

      msgQuota.html('');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');

      $.ajax({
        url: '/manageEstablishmentsController.php',
        method: 'POST',
        data: {
          action: 'addQuota',
          id: $('#idQuota').val(),
          quota: $('#quotaQuota').val()
        },
        error: function() {
          msgQuota.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          if (res.status == 'success') {
            frm[0].reset();
            $('#modalAddQuota').modal('hide');
            loadCompanies().then(() => {
              msg.html('<div class="alert alert-success">' + res.message + '</div>');
            });
          } else {
            if (res.message == 'form_errors') {
              $.each(res.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            } else {
              msgQuota.html('<div class="alert alert-danger">' + res.message + '</div>');
            }
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    $('#companies').on('click', '.btn-delete', function() {
      var btn = $(this);
      $.confirm({
        'title': 'Delete',
        'message': 'Are you sure you want to delete this record?',
        buttons: {
          yes: {
            text: 'Yes, Delete',
            btnClass: 'btn-danger',
            action: function() {
              var id = btn.attr('data-id');
              $.ajax({
                url: 'manageEstablishmentsController.php',
                method: 'POST',
                data: {
                  action: 'deleteCompany',
                  id: id
                },
                error: function() {
                  $('#message').html('<div class="alert alert-danger">An error occurred while deleting record</div>');
                },
                success: function(res) {
                  if (res.status == 'success') {
                    loadCompanies().then(() => {
                      $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
                    });
                  } else {
                    $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
                  }
                }
              });
            }
          },
          no: {
            text: 'No',
            btnClass: 'btn-secondary'
          }
        }
      });
    });

    $('#companies').on('click', '.btn-edit', function() {
      var btn = $(this);
      var id = btn.attr('data-id');
      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        url: 'manageEstablishmentsController.php',
        method: 'POST',
        data: {
          action: 'loadCompany',
          id: id
        },
        error: function() {
          $('#message').html('<div class="alert alert-danger">An error occurred while loading record</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          if (res.status == 'success') {
            $('#idEdit').val(res.company.company_id);
            $('#nameEdit').val(res.company.company_name);
            $('#numberEdit').val(res.company.company_number);
            $('#expiryEdit').val(res.company.company_expiry);
            $('#typeEdit').val(res.company.company_type);
            $('#localNameEdit').val(res.company.local_name);
            $('#quotaEdit').val(res.company.starting_quota);
            $('#usernameEdit').val(res.company.username);
            $('#passwordEdit').val(res.company.password);
            $('#modalEditCompany').modal('show');
            if (res.company.letterhead) {
              $('#letterHeadEditLink').attr('href', 'letters/' + res.company.letterhead).show();
              $('#letterHeadEditDelete').attr('data-id', res.company.company_id);
            } else {
              $('#letterHeadEditLink').hide();
              $('#letterHeadEditDelete').hide();
            }
            if (res.company.stamp) {
              $('#stampEditLink').attr('href', 'letters/' + res.company.stamp).show();
              $('#stampEditDelete').attr('data-id', res.company.company_id);
            } else {
              $('#stampEditLink').hide();
              $('#stampEditDelete').hide();
            }
            if (res.company.signature) {
              $('#signatureEditLink').attr('href', 'letters/' + res.company.signature).show();
              $('#signatureEditDelete').attr('data-id', res.company.company_id);
            } else {
              $('#signatureEditLink').hide();
              $('#signatureEditDelete').hide();
            }
          } else {
            $('#message').html('<div class="alert alert-danger">' + res.message + '</div>');
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    $('#companies').on('click', '.btn-quota', function() {
      var btn = $(this);
      var id = btn.attr('data-id');
      $('#idQuota').val(id);
      $('#modalAddQuota').modal('show');
    });

    $('.btn-delete-image').on('click', function() {
      var btn = $(this);
      $.confirm({
        title: 'Delete',
        content: 'Are you sure you want to delete this image?',
        buttons: {
          yes: {
            text: 'Yes',
            btnClass: 'btn-danger',
            action: function() {
              $.ajax({
                url: 'manageEstablishmentsController.php',
                method: 'POST',
                data: {
                  action: 'deleteImage',
                  id: btn.attr('data-id'),
                  type: btn.attr('data-type')
                },
                error: function() {
                  $('#msgEdit').html('<div class="alert alert-danger">An error occurred while deleting image</div>');
                },
                success: function(res) {
                  if (res.status == 'success') {
                    $('#modalEditCompany').modal('hide');
                    loadCompanies().then(() => {
                      $('#message').html('<div class="alert alert-success">' + res.message + '</div>');
                    });
                  } else {
                    $('#msgEdit').html('<div class="alert alert-danger">' + res.message + '</div>');
                  }
                }
              });
            }
          },
          no: {
            text: 'No',
            btnClass: 'btn-secondary'
          }
        }
      });
    });
  });
</script>