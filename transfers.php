<?php include 'header.php' ?>
<title>Transfers</title>
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
      <h3>Transfers</h3>
    </div>
    <div class="col-md-7 text-end  mb-2">
      <button type="button" id="btnAddNewTransaction" class="btn btn-success"  data-bs-toggle="modal" data-bs-target="#modalAddTransaction">Add Transaction</button>
    </div>
    <div class="col-md-12 form-group">
      <div class="panel panel-inverse">
        <div class="panel-heading">
          <h4 class="panel-title">Search Transfers</h4>
        </div>
        <div class="panel-body">
          <form action="" id="frmSearch">
            <input type="hidden" name="action" value="searchTransactions">
            <div class="row">
              <div class="col-md-1 form-group">
                <label for="period" class="form-label">Period</label>
                <select name="period" id="period" class="form-select">
                  <option value="date">Dates</option>
                  <option value="all">All Time</option>
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="startDate" class="form-label">Start Date</label>
                <input type="date" name="startDate" id="startDate" class="form-control" value="<?php echo date('Y-m-01') ?>">
              </div>
              <div class="col-md-2 form-group">
                <label for="endDate" class="form-label">End Date</label>
                <input type="date" name="endDate" id="endDate" class="form-control" value="<?php echo date('Y-m-d') ?>">
              </div>
              <div class="col-md-2 form-group">
                <label for="fromAccount" class="form-label">From Account</label>
                <select name="fromAccount" id="fromAccount" class="form-select">
                  <option value="">Select Account</option>
                  <?php foreach($accounts as $account): ?>
                    <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="toAccount" class="form-label">To Account</label>
                <select name="toAccount" id="toAccount" class="form-select">
                  <option value="">Select Account</option>
                  <?php foreach($accounts as $account): ?>
                    <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Trx or Remarks">
              </div>
              <div class="col-md-1">
                <label for="search" class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control" id="btnSearch">
                  <i class="fa fa-search"></i>
                </button>
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
          <h4 class="panel-title">Transactions</h4>
        </div>
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date/Time</th>
              <th>Accounts</th>
              <th>Remarks</th>
              <th>Trx#</th>
              <th>Amount</th>
              <th>Charges</th>
              <th>Exchange<br />Rate</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody  id="transactions">
            
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php require 'footer.php' ?>

<div class="modal fade" id="modalAddTransaction"  role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmAdd" method="POST">
    <input type="hidden" name="action" value="addTransaction">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>New Transfer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-6 mb-2">
              <label for="dateAdd" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="text" name="dateAdd" id="dateAdd" class="form-control" value="<?php echo date('Y-m-d'); ?>">
              <div class="invalid-feedback dateAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="fromAccountAdd">From Account <span class="text-danger">*</span></label>
              <select name="fromAccountAdd" id="fromAccountAdd" class="form-select">
                <option value="">Select Account</option>
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback fromAccountAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="balanceFromAdd">Balance</label>
              <input type="text" disabled="disabled" id="balanceFromAdd" class="form-control">
            </div>
            <div class="col-md-8 mb-2">
              <label for="toAccountAdd">To Account <span class="text-danger">*</span></label>
              <select name="toAccountAdd" id="toAccountAdd" class="form-select">
                <option value="">Select Account</option>
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback toAccountAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="balanceToAdd">Balance</label>
              <input type="text" disabled="disabled" id="balanceToAdd" class="form-control">
            </div>
            <div class="col-md-12 mb-2">
              <label for="remarksAdd" class="input-label">Remarks</label>
              <textarea name="remarksAdd" id="remarksAdd" class="form-control" rows="2" placeholder="Remarks"></textarea>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="trxNumberAdd">Transaction No.</label>
              <input type="text" name="trxNumberAdd" id="trxNumberAdd" class="form-control" placeholder="Transaction Number">
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
            <div class="col-md-6 mb-2">
              <label class="form-label" for="charges" >Charges <span class="text-danger">*</span></label>
              <input type="text" name="chargesAdd" id="chargesAdd" class="form-control" value="0" placeholder="Charges">
              <div class="invalid-feedback chargesAdd"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-label" for="exchangeRateAdd" >Exchange Rate <span  class="text-danger">*</span></label>
              <input type="text" name="exchangeRateAdd" id="exchangeRateAdd" class="form-control" value="1" placeholder="Currency Exchange Rate">
              <div class="invalid-feedback exahnegRateAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="filenameAdd">Receipt <span class="text-danger">*</span></label>
              <input type="file" accept="image/*" name="filenameAdd" id="filenameAdd"  class="form-control">
              <div class="invalid-feedback file"></div>
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


<div class="modal fade" id="modalEditTransaction"  role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateTransaction">
    <input type="hidden" name="idEdit" id="idEdit" value="0"> 
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark" >
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Transfer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">
        
          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-6 mb-2">
              <label for="dateEdit" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="text" name="dateEdit" id="dateEdit" class="form-control" value="">
              <div class="invalid-feedback dateEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="fromAccountEdit">From Account <span class="text-danger">*</span></label>
              <select name="fromAccountEdit" id="fromAccountEdit" class="form-select">
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback fromAccountEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="toAccountEdit">To Account <span class="text-danger">*</span></label>
              <select name="toAccountEdit" id="toAccountEdit" class="form-select">
                <?php foreach($accounts as $account): ?>
                  <option value="<?php echo $account['account_ID'] ?>"><?php echo $account['account_Name'] ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback toAccountEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="remarksEdit" class="input-label">Remarks</label>
              <textarea name="remarksEdit" id="remarksEdit" class="form-control" rows="2" placeholder="Remarks"></textarea>
            </div>
            <div class="col-md-12 mb-2">
              <label class="form-label" for="trxNumberEdit">Transaction No.</label>
              <input type="text" name="trxNumberEdit" id="trxNumberEdit" class="form-control" placeholder="Transaction Number">
              <div class="invalid-feedback trxNumberEdit"></div>
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
            <div class="col-md-6 mb-2">
              <label class="form-label" for="charges" >Charges <span class="text-danger">*</span></label>
              <input type="text" name="chargesEdit" id="chargesEdit" class="form-control" value="0" placeholder="Charges">
              <div class="invalid-feedback chargesEdit"></div>
            </div>
            <div class="col-md-6 mb-2">
              <label class="form-label" for="exchangeRateEdit" >Exchange Rate <span  class="text-danger">*</span></label>
              <input type="text" name="exchangeRateEdit" id="exchangeRateEdit" class="form-control" value="1" placeholder="Currency Exchange Rate">
              <div class="invalid-feedback exahnegRateEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="filenameEdit">Receipt <span class="text-danger">(if you wish to update)</span></label>
              <input type="file" accept="image/*" name="filenameEdit" id="filenameEdit"  class="form-control">
              <div class="invalid-feedback file"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveEdit" class="btn btn-success">Save</button>
        </div>
        </form>
      </div>
    </div>
  </form>
</div>


<script type="text/javascript">
  $(document).ready(function(){
    $('#startDate').dateTimePicker();
    $('#endDate').dateTimePicker();
    $('#dateAdd').dateTimePicker();
    $('#dateEdit').dateTimePicker();


    function getAccountBalance(id){
      return new Promise((resolve, reject) => {
        $.ajax({
          url: 'getAccountBalance.php',
          method: 'POST',
          data: {id:id},
          error: function(){
            resolve(0);
          },
          success: function(res){
            if( res.status == 'success' ){
              resolve(res.account.Account_Balance);
            }else{
              resolve(0);
            }
          }
        });
      });
    }

    $('#fromAccountAdd').on('change',function(){
      var id = $(this).val();
      getAccountBalance(id).then((balance) => {
        $('#balanceFromAdd').val(balance);
      });
    }); 

    $('#toAccountAdd').on('change',function(){
      var id = $(this).val();
      getAccountBalance(id).then((balance) => {
        $('#balanceToAdd').val(balance);
      });
    });


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
          url: 'transfersController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function(){
            msg.html('<div class="alert alert-danger">An error occured while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e){
            $('#transactions').html('');
            if( e.status == 'success' ){
              $('#transactions').html(e.html);
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
        url: '/transfersController.php',
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
            $('#modalAddTransaction').modal('hide');
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

    $('#transactions').on('click','.btn-delete',function(){
      var btn = $(this);
      var id = btn.attr('data-id');
      if( confirm('Are you sure you want to delete this transfer record?') ){
        btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'transfersController.php',
          method: 'POST',
          data: {action: 'deleteTransaction',id:id},
          error: function(){
            $('#message').html('<div class="alert alert-danger">An error occured while deleting transaction</div>');
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

    $('#transactions').on('click','.btn-edit',function(){
      // open the edit modal
      var btn = $(this);
      var id = btn.attr('data-id');
      var modal = $('#modalEditTransaction');

      
      btn.attr('data-temp',btn.html()).attr('disabled','disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        url: 'transfersController.php',
        method: 'POST',
        data: {action: 'getTransaction',id:id},
        error: function(){
          $('#message').html('<div class="alert alert-danger">An error occured while loading transaction details</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(e){
          if( e.status == 'success' ){
            var trx = e.trx;
            var modal = $('#modalEditTransaction');
           
           
            modal.find('input,select,textarea').each(function(){
              var element = $(this);
              element.removeClass('is-invalid');
              element.next('.invalid-feedback').html('');
            });


            $('#dateEdit').val(trx.datetime);
            $('#fromAccountEdit').val(trx.from_account);
            $('#toAccountEdit').val(trx.to_account);
            $('#remarksEdit').val(trx.remarks);
            $('#trxNumberEdit').val(trx.trx);
            $('#amountEdit').val(trx.amount);
            $('#amountConfirmEdit').val(trx.amount);
            $('#chargesEdit').val(trx.charges);
            $('#exchangeRateEdit').val(trx.exchange_rate);
            $('#idEdit').val(trx.id);


            modal.modal('show');
          }else{
            $('#message').html('<div class="alert alert-danger">'+e.message+'</div>');
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
      
    });

    $('#modalEditTransaction').on('submit',function(){
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
        url: 'transfersController.php',
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
              $('#modalEditTransaction').modal('hide');
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