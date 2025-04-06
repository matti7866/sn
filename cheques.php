<?php include 'header.php' ?>
<title>Cheques</title>
<?php 
  include 'nav.php';
  if(!isset($_SESSION['user_id'])){
    header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':role_id', $_SESSION['role_id']);
  $stmt->execute();

  // fetch single row
  $record = $stmt->fetch();

  if($record['select'] == 0){
    echo "<script>window.location.href='pageNotFound.php'</script>";
  }
  
  // get the accounts
  $sql = "SELECT * FROM `accounts`";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $accounts = $stmt->fetchAll();

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Cheques</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <button type="button" id="btnAddNewTransaction" class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#modalAddCheque">Add Cheque</button>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-inverse">
        <div class="panel-heading"><h3 class="panel-title">Search Cheques</h3></div>
        <div class="panel-body">
          <form action="" method="POST" id="frmSearch">
            <input type="hidden" name="action" value="searchCheques" />
            <div class="row">
              <div class="col-md-2">
                <label for="startDate"  class="form-label">From Date</label>
                <input type="text" name="startDate" id="startDate" class="form-control" value="<?php echo date('Y-m-01') ?>" />
              </div>
              <div class="col-md-2">
                <label for="endDate"  class="form-label">To Date</label>
                <input type="text" name="endDate" id="endDate" class="form-control" value="<?php echo date('Y-m-d') ?>" />
              </div>
              <div class="col-md-2">
                <label for="search"  class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
              </div>
              <div class="col-md-2 mb-2">
                <label for="type" class="form-label">Type</label>
                <select name="type" id="type" class="form-select">
                  <option value="">Select</option>
                  <option value="payable">Payable</option>
                  <option value="receivable">Receivable</option>
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <label for="account" class="form-label">Account</label>
                <select name="account" id="account" class="form-select">
                  <option value="">Select</option>
                  <?php foreach($accounts as $account): ?>
                    <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                  <?php endforeach; ?>
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
          <h4 class="panel-title">Cheques</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Number</th>
              <th>Type</th>
              <th>Payee</th>
              <th>Account / Bank</th>
              <th>Amount</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody  id="cheques">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
</div>
<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddCheque"  role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmAdd" method="POST">
    <input type="hidden" name="action" value="addCheque">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>New Cheque</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-4 mb-2">
              <label for="dateAdd" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="text" name="dateAdd" id="dateAdd" class="form-control" value="<?php echo date('Y-m-d'); ?>">
              <div class="invalid-feedback dateAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="dateAdd" class="form-label">Number <span class="text-danger">*</span></label>
              <input type="text" name="numberAdd" id="numberAdd" class="form-control" value="">
              <div class="invalid-feedback numberAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select" id="typeAdd" name="typeAdd">
                <option value="">Choose type</option>
                <option value="payable">Payable</option>
                <option value="receivable">Receivable</option>
              </select>
              <div class="invalid-feedback typeAdd"></div>
            </div>
            <div class="col-md-8 mb-2 colAccountIDAdd" style="display:none">
              <label for="accountIDAdd" class="form-label">From Account <span class="text-danger">*</span></label>
              <select name="accountIDAdd" id="accountIDAdd" class="form-select">
                <option value="">Select Account</option>
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback fromAccountAdd"></div>
            </div>
            <div class="col-md-8 mb-2 colBankAdd" style="display:none">
              <label for="bankAdd" class="form-label">Bank Name</label>
              <input type="text" class="form-control" id="bankAdd" name="bankAdd" />
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="payeeAdd">Payee</label>
              <input type="text" name="payeeAdd" id="payeeAdd" class="form-control">
              <div class="invalid-feedback trxNumberAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="amountAdd" class="form-label">Amount <span class="text-danger">*</span></label>
              <input type="text" name="amountAdd" id="amountAdd" class="form-control" placeholder="Amount">
              <div class="invalid-feedback amountAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="amountConfirmAdd" class="form-label">Confirm Amount <span class="text-danger">*</span></label>
              <input type="text" name="amountConfirmAdd" id="amountConfirmAdd" class="form-control" placeholder="Confirm Amount">
              <div class="invalid-feedback amountConfirmAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="filename">Cheque Photo <span class="text-danger">*</span></label>
              <input type="file" name="filename" id="filename" class="form-control" accept="image/*" />
              <div class="invalid-feedback filename"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveAdd" class="btn btn-success">Save</button>
        </div>
        </form>
      </div>
    </div>
  </form>
</div>




<div class="modal fade" id="modalEditCheque"  role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateCheque">
    <input type="hidden" name="idEdit" value="" id="idEdit">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Cheque</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-4 mb-2">
              <label for="dateEdit" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="text" name="dateEdit" id="dateEdit" class="form-control" value="">
              <div class="invalid-feedback dateEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="numberEdit" class="form-label">Number <span class="text-danger">*</span></label>
              <input type="text" name="numberEdit" id="numberEdit" class="form-control" value="">
              <div class="invalid-feedback numberEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="typeEdit" class="form-label">Type <span class="text-danger">*</span></label>
              <select class="form-select" id="typeEdit" name="typeEdit">
                <option value="payable">Payable</option>
                <option value="receivable">Receivable</option>
              </select>
              <div class="invalid-feedback typeEdit"></div>
            </div>
            <div class="col-md-8 mb-2 colAccountIDEdit" style="display:none">
              <label for="accountIDEdit" class="form-label">From Account <span class="text-danger">*</span></label>
              <select name="accountIDEdit" id="accountIDEdit" class="form-select">
                <option value="">Select Account</option>
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback accountIDEdit"></div>
            </div>
            <div class="col-md-8 mb-2 colBankEdit" style="display:none">
              <label for="bankEdit" class="form-label">Bank Name</label>
              <input type="text" class="form-control" id="bankEdit" name="bankEdit" />
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="payeeEdit">Payee</label>
              <input type="text" name="payeeEdit" id="payeeEdit" class="form-control">
              <div class="invalid-feedback trxNumberAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="amountEdit" class="form-label">Amount <span class="text-danger">*</span></label>
              <input type="text" name="amountEdit" id="amountEdit" class="form-control" placeholder="Amount">
              <div class="invalid-feedback amountEdit"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label for="amountConfirmEdit" class="form-label">Confirm Amount <span class="text-danger">*</span></label>
              <input type="text" name="amountConfirmEdit" id="amountConfirmEdit" class="form-control" placeholder="Confirm Amount">
              <div class="invalid-feedback amountConfirmEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="filename">Cheque Photo <span class="text-danger">(if you wish to update)</span></label>
              <input type="file" name="filename" id="filename" class="form-control" accept="image/*" />
              <div class="invalid-feedback filename"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveEdit" class="btn btn-success">Update</button>
        </div>
        </form>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
  $(document).ready(function(){

    $("#typeAdd").on('change',function(){
      var type = $(this).val();
      if( type == 'payable' ){
        $('.colAccountIDAdd').show();
        $('.colBankAdd').hide();
      }
      if( type == 'receivable' ){
        $('.colAccountIDAdd').hide();
        $('.colBankAdd').show();
      }
    });

    $("#typeEdit").on('change',function(){
      var type = $(this).val();
      if( type == 'payable' ){
        $('.colAccountIDEdit').show();
        $('.colBankEdit').hide();
      }
      if( type == 'receivable' ){
        $('.colAccountIDEdit').hide();
        $('.colBankEdit').show();
      }
    });

    $('#startDate').dateTimePicker();
    $('#endDate').dateTimePicker();
    $('#dateAdd').dateTimePicker();
    $('#dateEdit').dateTimePicker();

    $('.form-select,input[type=file]').on('change',function(){
      var vl = $(this).val();
      if( vl == '' ){
        $(this).addClass('is-invalid');
      }else{
        $(this).removeClass('is-invalid');
      }
    });
    $('.form-control').on('keyup',function(){
      var vl = $(this).val();
      if( vl == '' ){
        $(this).addClass('is-invalid');
      }else{
        $(this).removeClass('is-invalid');
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
          url: 'chequesController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function(){
            msg.html('<div class="alert alert-danger">An error occured while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e){
            $('#cheques').html('');
            if( e.status == 'success' ){
              $('#cheques').html(e.html);
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

    $('#frmAdd').on('submit',function(e){
      e.preventDefault();
      var frm = $(this);
      var btn = $('#btnSaveAdd');
      var msg = $('#message');
      var msgAdd =  $("#msgAdd");

      msgAdd.html('');
      btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');

      var formData = new FormData();
      frm.find('input,select,textarea').each(function(){
        var element = $(this);
        if( element.attr('type') == 'file' ){
          var file = element[0].files[0];
          formData.append(element.attr('name'),file);
        }else if(element.attr('type') == 'checkbox'){
          if( element.prop('checked') ){
            formData.append(element.attr('name'),element.val());
          }
        }else if( element.attr('type') == 'radio' ){
          if( element.prop('checked') ){
            formData.append(element.attr('name'),element.val());
          }
        }else{
          formData.append(element.attr('name'),element.val());
        }
      });

      $.ajax({
        url: '/chequesController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(){
          msgAdd.html('<div class="alert alert-danger">An error occured while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res){
          if(res.status == 'success'){
            
            frm[0].reset();
            $('#modalAddCheque').modal('hide');
            loadTransactions().then(() => {
              msg.html('<div class="alert alert-success">'+res.message+'</div>');
            });
          }else{
            if( res.message == 'form_errors' ){
              $.each(res.errors,function(key,value){
                $('#'+key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            }else{
              msgAdd.html('<div class="alert alert-danger">'+res.message+'</div>');
            }
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    $('#cheques').on('click','.btn-delete',function(){
      var btn = $(this);
      var id = btn.attr('data-id');
      if( confirm('Are you sure you want to delete this cheque record?') ){
        btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'chequesController.php',
          method: 'POST',
          data: {action: 'deleteCheque',id:id},
          error: function(){
            $('#message').html('<div class="alert alert-danger">An error occured while deleting</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(res){
            if(res.status == 'success'){
              loadTransactions();
            }else{
              $('#message').html('<div class="alert alert-danger">'+res.message+'</div>');
            }
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          }
        });
      }
    });

    $('#cheques').on('click','.btn-edit',function(){
      // open the edit modal
      var btn = $(this);
      var id = btn.attr('data-id');
      var modal = $('#modalEditCheque');

      modal.modal('show');

      
      btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        url: 'chequesController.php',
        method: 'POST',
        data: {action: 'getCheque',id:id},
        error: function(){
          $('#message').html('<div class="alert alert-danger">An error occured while loading transaction details</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(e){
          if( e.status == 'success' ){
            var data = e.data;
            console.log(data);
           
           
            modal.find('input,select,textarea').each(function(){
              var element = $(this);
              element.removeClass('is-invalid');
              element.next('.invalid-feedback').html('');
            });

            $('#idEdit').val(data.id);
            $('#dateEdit').val(data.date);
            $('#numberEdit').val(data.number);
            $("#typeEdit").val(data.type);
            $('#payeeEdit').val(data.payee);
            $('#amountEdit').val(data.amount);
            $('#amountConfirmEdit').val(data.amount);
            if( data.type == 'payable' ){
              $('.colAccountIDEdit').show();
              $('.colBankEdit').hide();
              $('#accountIDEdit').val(data.account_id);
            }
            if( data.type == 'receivable' ){
              $('.colAccountIDEdit').hide();
              $('.colBankEdit').show();
              $('#bankEdit').val(data.bank);
            }
            modal.modal('show');
          }else{
            $('#message').html('<div class="alert alert-danger">'+e.message+'</div>');
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
      
    });

    $('#modalEditCheque').on('submit',function(){
      var frm = $(this);
      var btn = $('#btnSaveEdit');
      var msg = $('#message');
      var modal = $('#modalEditTransaction');

      btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      var formData = new FormData();
      frm.find('input,select,textarea').each(function(){
        var element = $(this);
        if( element.attr('type') == 'file' ){
          var file = element[0].files[0];
          formData.append(element.attr('name'),file);
        }else if(element.attr('type') == 'checkbox'){
          if( element.prop('checked') ){
            formData.append(element.attr('name'),element.val());
          }
        }else if( element.attr('type') == 'radio' ){
          if( element.prop('checked') ){
            formData.append(element.attr('name'),element.val());
          }
        }else{
          formData.append(element.attr('name'),element.val());
        }
      });
      $.ajax({
        url: 'chequesController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function(){
          msg.html('<div class="alert alert-danger">An error occured while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res){
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
          if(res.status == 'success'){
            loadTransactions().then(function(){
              msg.html('<div class="alert alert-success">'+res.message+'</div>');
              $('#modalEditCheque').modal('hide');
            });
          }else{
            if( res.message == 'form_errors' ){
              $.each(res.errors,function(key,value){
                $('#'+key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            }else{
              msg.html('<div class="alert alert-danger">'+res.message+'</div>');
            }
          } 
        }
      });

      return false;
    });
    
  });
</script>