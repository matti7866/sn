<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'header.php';
?>
<title>Amer Transactions</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
  header('location:login.php');
  exit(); // Add this to ensure the script stops executing if the user is not logged in
}
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Amer' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();

// fetch single row
$record = $stmt->fetch();

// get the customer
$sql = "SELECT * FROM `customer`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$customer = $stmt->fetchAll();

// get the transaction types
$sql = "SELECT * FROM `transaction_type`";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$transactionType = $stmt->fetchAll();

// get the account types

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-5 mb-2">
      <h3>Amer Transactions</h3>
    </div>
    <div class="col-md-7 text-end mb-2">
      <button type="button" id="btnAddNewTransaction" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAmer">Add Transactions</button>
    </div>
  </div>
  <div class="col-md-12">
    <div class="panel panel-inverse">
      <div class="panel-heading">
        <h3 class="panel-title">Search Transaction</h3>
      </div>
      <div class="panel-body">
        <form action="" method="POST" id="frmSearch">
          <input type="hidden" name="action" value="searchAmer" />
          <div class="row">
            <div class="col-md-2">
              <label for="startDate" class="form-label">From Date</label>
              <input type="text" name="startDate" id="startDate" class="form-control" value="<?php echo date('Y-m-01') ?>" />
            </div>
            <div class="col-md-2">
              <label for="endDate" class="form-label">To Date</label>
              <input type="text" name="endDate" id="endDate" class="form-control" value="<?php echo date('Y-m-d') ?>" />
            </div>
            <div class="col-md-2 mb-2">
              <label for="customer_ID" class="form-label">Customer ID</label>
              <select name="customer_ID" id="customer_ID" class="form-control">
                <option value="">Select Customer</option>
                <?php foreach ($customer as $c) { ?>
                  <option value="<?php echo $c['customer_ID'] ?>"><?php echo $c['customer_name'] ?></option>
                <?php } ?>
              </select>

            </div>
            <div class="col-md-2 mb-2">
              <label for="app_number" class="form-label">&nbsp;</label>
              <input type="text" name="app_number" id="app_number" class="form-control" placeholder="App Number">
            </div>
            <div class="col-md-2 mb-2">
              <label for="trans_number" class="form-label">&nbsp;</label>
              <input type="text" name="trans_number" id="trans_number" class="form-control" placeholder="Trans Number">
            </div>
            <div class="col-md-2 mb-2">
              <label for="" class="form-label">&nbsp;</label>
              <button id="btnSearch" class="btn btn-primary btn-block w-100"><i class="fa fa-filter"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="panel panel-inverse">
      <div class="panel-heading">Amer Transactions</div>
      <table class="table" id="tblAmer">
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Passenger Name</th>
            <th>Transaction Type</th>
            <th>App Number</th>
            <th>Trans Number</th>
          </tr>
        </thead>
        <tbody id="amer"></tbody>
      </table>
    </div>

  </div>
</div>

</div>
<?php require 'footer.php' ?>

<div class=" modal fade" id="modalAddAmer" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmAdd" method="POST">
    <input type="hidden" name="action" value="addAmer">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>New Amer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12 mb-2" id="msgAdd"></div>
            <div class="col-md-4 mb-2">
              <label for="customer" class="form-label">Customer Name</label>
              <select name="customer_IDAdd" id="customer_IDAdd" class="form-select">
                <option value="">Select</option>
                <?php foreach ($customer as $customer): ?>
                  <option value="<?php echo $customer['customer_id'] ?>"><?php echo $customer['customer_name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <label for="passenger_nameAdd" class="form-label">Passenger Name <span class="text-danger">*</span></label>
              <input type="text" name="passenger_nameAdd" id="passenger_nameAdd" class="form-control" value="">
              <div class="invalid-feedback passenger_nameAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="transaction_ID" class="form-label">Transaction Type <span class="text-danger">*</span></label>
              <select name="transaction_ID" id="transaction_ID" class="form-select">
                <option value="">Select</option>
                <?php foreach ($transactionType as $transactionType): ?>
                  <option value="<?php echo $transactionType['trans_id'] ?>"><?php echo $transactionType['transaction_name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-8 mb-2">
              <label for="app_numberAdd" class="form-label">App Number <span class="text-danger">*</span></label>
              <input type="text" name="app_numberAdd" id="app_numberAdd" class="form-control" value="">
              <div class="invalid-feedback app_numberAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="trans_numberAdd" class="form-label">Trans Number <span class="text-danger">*</span></label>
              <input type="text" name="trans_numberAdd" id="trans_numberAdd" class="form-control" value="">
              <div class="invalid-feedback trans_numberAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="payment_dateAdd" class="form-label">Payment Date <span class="text-danger">*</span></label>
              <input type="text" name="payment_dateAdd" id="payment_dateAdd" class="form-control" value="<?php echo date('Y-m-d'); ?>">
              <div class="invalid-feedback payment_dateAdd"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="net_costAdd" class="form-label">Net Cost <span class="text-danger">*</span></label>
              <input type="text" name="net_costAdd" id="net_costAdd" class="form-control" value="">
              <div class="invalid-feedback net_costAdd"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="sale_costAdd" class="form-label">Sale Cost <span class="text-danger">*</span></label>
              <input type="text" name="sale_costAdd" id="sale_costAdd" class="form-control" value="">
              <div class="invalid-feedback sale_costAdd"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="iban_infoAdd" class="form-label">IBAN Info <span class="text-danger">*</span></label>
              <input type="text" name="iban_infoAdd" id="iban_infoAdd" class="form-control" value="">
              <div class="invalid-feedback iban_infoAdd"></div>
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
</form>
</div>

<div class="modal fade" id="modalEditAmer" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateAmer">
    <input type="hidden" name="idEdit" value="" id="idEdit">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Amer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
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
</form>
</div>

<div class="modal fade" id="modalEditAmer" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateAmer">
    <input type="hidden" name="idEdit" value="" id="idEdit">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Amer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-4 mb-2">
              <label for="customer_IDEdit" class="form-label">Customer ID <span class="text-danger">*</span></label>
              <input type="text" name="customer_IDEdit" id="customer_IDEdit" class="form-control" value="">
              <div class="invalid-feedback customer_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="passenger_nameEdit" class="form-label">Passenger Name <span class="text-danger">*</span></label>
              <input type="text" name="passenger_nameEdit" id="passenger_nameEdit" class="form-control" value="">
              <div class="invalid-feedback passenger_nameEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="transaction_IDEdit" class="form-label">Transaction ID <span class="text-danger">*</span></label>
              <input type="text" name="transaction_IDEdit" id="transaction_IDEdit" class="form-control" value="">
              <div class="invalid-feedback transaction_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="app_numberEdit" class="form-label">App Number <span class="text-danger">*</span></label>
              <input type="text" name="app_numberEdit" id="app_numberEdit" class="form-control" value="">
              <div class="invalid-feedback app_numberEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="trans_numberEdit" class="form-label">Trans Number <span class="text-danger">*</span></label>
              <input type="text" name="trans_numberEdit" id="trans_numberEdit" class="form-control"

                // get the customer
                $sql="SELECT * FROM `customer`" ;
                $stmt=$pdo->prepare($sql);
              $stmt->execute();
              $customer = $stmt->fetchAll();

              // get the transaction types
              $sql = "SELECT * FROM `transaction_type`";
              $stmt = $pdo->prepare($sql);
              $stmt->execute();
              $transactionType = $stmt->fetchAll();

              // get the account types

              ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-5 mb-2">
                    <h3>Amer Transactions</h3>
                  </div>
                  <div class="col-md-7 text-end mb-2">
                    <button type="button" id="btnAddNewTransaction" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddAmer">Add Transactions</button>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="panel panel-inverse">
                    <div class="panel-heading">
                      <h3 class="panel-title">Search Transaction</h3>
                    </div>
                    <div class="panel-body">
                      <form action="" method="POST" id="frmSearch">
                        <input type="hidden" name="action" value="searchAmer" />
                        <div class="row">
                          <div class="col-md-2">
                            <label for="startDate" class="form-label">From Date</label>
                            <input type="text" name="startDate" id="startDate" class="form-control" value="<?php echo date('Y-m-01') ?>" />
                          </div>
                          <div class="col-md-2">
                            <label for="endDate" class="form-label">To Date</label>
                            <input type="text" name="endDate" id="endDate" class="form-control" value="<?php echo date('Y-m-d') ?>" />
                          </div>
                          <div class="col-md-2">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="customer_ID" class="form-label">&nbsp;</label>
                            <input type="text" name="customer_ID" id="customer_ID" class="form-control" placeholder="Customer ID">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="app_number" class="form-label">&nbsp;</label>
                            <input type="text" name="app_number" id="app_number" class="form-control" placeholder="App Number">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="transaction_ID" class="form-label">&nbsp;</label>
                            <input type="text" name="transaction_ID" id="transaction_ID" class="form-control" placeholder="Transaction ID">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="app_number" class="form-label">&nbsp;</label>
                            <input type="text" name="app_number" id="app_number" class="form-control" placeholder="App Number">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="trans_number" class="form-label">&nbsp;</label>
                            <input type="text" name="trans_number" id="trans_number" class="form-control" placeholder="Trans Number">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="payment_date" class="form-label">&nbsp;</label>
                            <input type="text" name="payment_date" id="payment_date" class="form-control" placeholder="Payment Date">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="net_cost" class="form-label">&nbsp;</label>
                            <input type="text" name="net_cost" id="net_cost" class="form-control" placeholder="Net Cost">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="sale_cost" class="form-label">&nbsp;</label>
                            <input type="text" name="sale_cost" id="sale_cost" class="form-control" placeholder="Sale Cost">
                          </div>
                          <div class="col-md-2 mb-2">
                            <label for="" class="form-label">&nbsp;</label>
                            <button id="btnSearch" class="btn btn-primary btn-block w-100"><i class="fa fa-filter"></i></button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <?php require 'footer.php' ?>

            <div class="modal fade" id="modalAddAmer" role="dialog" aria-labelledby="" aria-hidden="true">
              <form action="" id="frmAdd" method="POST">
                <input type="hidden" name="action" value="addAmer">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header bg-dark">
                      <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>New Amer Transaction</i></b></h3>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">

                      <div class="row">
                        <div class="col-md-12 mb-2" id="msgAdd"></div>
                        <div class="col-md-4 mb-2">
                          <label for="customer" class="form-label">Customer Name</label>
                          <select name="customer" id="customer" class="form-select">
                            <option value="">Select</option>
                            <?php foreach ($customer as $customer): ?>
                              <option value="<?php echo $customer['customer_id'] ?>"><?php echo $customer['customer_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-8 mb-2">
                          <label for="passenger_nameAdd" class="form-label">Passenger Name <span class="text-danger">*</span></label>
                          <input type="text" name="passenger_nameAdd" id="passenger_nameAdd" class="form-control" value="">
                          <div class="invalid-feedback passenger_nameAdd"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <label for="transaction_ID" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                          <select name="transaction_ID" id="transaction_ID" class="form-select">
                            <option value="">Select</option>>
                            <?php foreach ($transactionType as $transactionType): ?>
                              <option value="<?php echo $transactionType['trans_id'] ?>"><?php echo $transactionType['transaction_name'] ?></option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                        <div class="col-md-8 mb-2">
                          <label for="app_numberAdd" class="form-label">App Number <span class="text-danger">*</span></label>
                          <input type="text" name="app_numberAdd" id="app_numberAdd" class="form-control" value="">
                          <div class="invalid-feedback app_numberAdd"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <label for="trans_numberAdd" class="form-label">Trans Number <span class="text-danger">*</span></label>
                          <input type="text" name="trans_numberAdd" id="trans_numberAdd" class="form-control" value="">
                          <div class="invalid-feedback trans_numberAdd"></div>
                        </div>
                        <div class="col-md-8 mb-2">
                          <label for="payment_dateAdd" class="form-label">Payment Date <span class="text-danger">*</span></label>
                          <input type="text" name="payment_dateAdd" id="payment_dateAdd" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                          <div class="invalid-feedback payment_dateAdd"></div>
                        </div>
                        <div class="col-md-4 mb-2">
                          <label for="net_costAdd" class="form-label">Net Cost <span class="text-danger">*</span></label>
                          <input type="text" name="net_costAdd" id="net_costAdd" class="form-control" value="">
                          <div class="invalid-feedback net_costAdd"></div>
                        </div>
                        <div class="col-md-8 mb-2">
                          <label for="sale_costAdd" class="form-label">Sale Cost <span class="text-danger">*</span></label>
                          <input type="text" name="sale_costAdd" id="sale_costAdd" class="form-control" value="">
                          <div class="invalid-feedback sale_costAdd"></div>
                        </div>
                        <div class="col-md-12 mb-2">
                          <label for="iban_infoAdd" class="form-label">IBAN Info <span class="text-danger">*</span></label>
                          <input type="text" name="iban_infoAdd" id="iban_infoAdd" class="form-control" value="">
                          <div class="invalid-feedback iban_infoAdd"></div>
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
  </form>
</div>

<div class="modal fade" id="modalEditAmer" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateAmer">
    <input type="hidden" name="idEdit" value="" id="idEdit">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Amer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-4 mb-2">
              <label for="customer_IDEdit" class="form-label">Customer ID <span class="text-danger">*</span></label>
              <input type="text" name="customer_IDEdit" id="customer_IDEdit" class="form-control" value="">
              <div class="invalid-feedback customer_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="passenger_nameEdit" class="form-label">Passenger Name <span class="text-danger">*</span></label>
              <input type="text" name="passenger_nameEdit" id="passenger_nameEdit" class="form-control" value="">
              <div class="invalid-feedback passenger_nameEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="transaction_IDEdit" class="form-label">Transaction ID <span class="text-danger">*</span></label>
              <input type="text" name="transaction_IDEdit" id="transaction_IDEdit" class="form-control" value="">
              <div class="invalid-feedback transaction_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="app_numberEdit" class="form-label">App Number <span class="text-danger">*</span></label>
              <input type="text" name="app_numberEdit" id="app_numberEdit" class="form-control" value="">
              <div class="invalid-feedback app_numberEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="trans_numberEdit" class="form-label">Trans Number <span class="text-danger">*</span></label>
              <input type="text" name="trans_numberEdit" id="trans_numberEdit" class="form-control" value="">
              <div class="invalid-feedback trans_numberEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="payment_dateEdit" class="form-label">Payment Date <span class="text-danger">*</span></label>
              <input type="text" name="payment_dateEdit" id="payment_dateEdit" class="form-control" value="<?php echo date('Y-m-d'); ?>">
              <div class="invalid-feedback payment_dateEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="net_costEdit" class="form-label">Net Cost <span class="text-danger">*</span></label>
              <input type="text" name="net_costEdit" id="net_costEdit" class="form-control" value="">
              <div class="invalid-feedback net_costEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="sale_costEdit" class="form-label">Sale Cost <span class="text-danger">*</span></label>
              <input type="text" name="sale_costEdit" id="sale_costEdit" class="form-control" value="">
              <div class="invalid-feedback sale_costEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="iban_infoEdit" class="form-label">IBAN Info <span class="text-danger">*</span></label>
              <input type="text" name="iban_infoEdit" id="iban_infoEdit" class="form-control" value="">
              <div class="invalid-feedback iban_infoEdit"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveEdit" class="btn btn-success">Update</button>
        </div>
  </form>
</div>
</div>
</form>
</div>


<div class="modal fade" id="modalEditAmer" role="dialog" aria-labelledby="" aria-hidden="true">
  <form action="" id="frmEdit" method="POST">
    <input type="hidden" name="action" value="updateAmer">
    <input type="hidden" name="idEdit" value="" id="idEdit">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Edit Amer Transaction</i></b></h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
        </div>
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12 mb-2" id="msgEdit"></div>
            <div class="col-md-4 mb-2">
              <label for="customer_IDEdit" class="form-label">Customer ID <span class="text-danger">*</span></label>
              <input type="text" name="customer_IDEdit" id="customer_IDEdit" class="form-control" value="">
              <div class="invalid-f eedback customer_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="passenger_nameEdit" class="form-label">Passenger Name <span class="text-danger">*</span></label>
              <input type="text" name="passenger_nameEdit" id="passenger_nameEdit" class="form-control" value="">
              <div class="invalid-feedback passenger_nameEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="transaction_IDEdit" class="form-label">Transaction Type <span class="text-danger">*</span></label>
              <select name="transaction_IDEdit" id="transaction_IDEdit" class="form-select">
                <option value="">Select</option>
                <?php foreach ($transactionType as $type): ?>
                  <option value="<?php echo $type['trans_id']; ?>"><?php echo $type['transaction_name']; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback transaction_IDEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="app_numberEdit" class="form-label">App Number <span class="text-danger">*</span></label>
              <input type="text" name="app_numberEdit" id="app_numberEdit" class="form-control" value="">
              <div class="invalid-feedback app_numberEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="trans_numberEdit" class="form-label">Trans Number <span class="text-danger">*</span></label>
              <input type="text" name="trans_numberEdit" id="trans_numberEdit" class="form-control" value="">
              <div class="invalid-feedback trans_numberEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="payment_dateEdit" class="form-label">Payment Date <span class="text-danger">*</span></label>
              <input type="text" name="payment_dateEdit" id="payment_dateEdit" class="form-control" value="<?php echo date('Y-m-d'); ?>">
              <div class="invalid-feedback payment_dateEdit"></div>
            </div>
            <div class="col-md-4 mb-2">
              <label for="net_costEdit" class="form-label">Net Cost <span class="text-danger">*</span></label>
              <input type="text" name="net_costEdit" id="net_costEdit" class="form-control" value="">
              <div class="invalid-feedback net_costEdit"></div>
            </div>
            <div class="col-md-8 mb-2">
              <label for="sale_costEdit" class="form-label">Sale Cost <span class="text-danger">*</span></label>
              <input type="text" name="sale_costEdit" id="sale_costEdit" class="form-control" value="">
              <div class="invalid-feedback sale_costEdit"></div>
            </div>
            <div class="col-md-12 mb-2">
              <label for="iban_infoEdit" class="form-label">IBAN Info <span class="text-danger">*</span></label>
              <input type="text" name="iban_infoEdit" id="iban_infoEdit" class="form-control" value="">
              <div class="invalid-feedback iban_infoEdit"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" id="btnSaveEdit" class="btn btn-success">Update</button>
        </div>
  </form>
</div>
</div>
</form>
</div>


<script type="text/javascript">
  $(document).ready(function() {

    // Date picker initialization
    $('#startDate').dateTimePicker();
    $('#endDate').dateTimePicker();
    $('#payment_dateAdd').dateTimePicker();
    $('#payment_dateEdit').dateTimePicker();

    // Input change handlers
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

    // Load transactions
    function loadTransactions() {
      return new Promise((resolve, reject) => {
        var frm = $('#frmSearch');
        var btn = $('#btnSearch');
        var msg = $('#message');

        msg.html('');
        btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
          url: 'amerController.php',
          method: 'POST',
          data: frm.serialize(),
          error: function() {
            msg.html('<div class="alert alert-danger">An error occurred while loading transactions</div>');
            btn.removeAttr('disabled').html(btn.attr('data-temp'));
          },
          success: function(e) {
            $('#amer').html('');
            if (e.status == 'success') {
              $('#amer').html(e.html);
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
      loadTransactions();
    });

    loadTransactions();

    // Form submission for adding a transaction
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

      // AJAX request to add the         
      $.ajax({
        url: 'amerController.php',
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
            $('#modalAddAmer').modal('hide');
            loadTransactions().then(() => {
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



    // Edit transaction
    $('#amer').on('click', '.btn-edit', function() {
      var btn = $(this);
      var id = btn.attr('data-id');
      var modal = $('#modalEditAmer');

      modal.modal('show');

      btn.attr('data-temp', btn.html()).attr('disabled', 'disabled').html('<i class="fa fa-spinner fa-spin"></i>');
      $.ajax({
        url: 'amerController.php',
        method: 'POST',
        data: {
          action: 'getAmer',
          id: id
        },
        error: function() {
          $('#message').html('<div class="alert alert-danger">An error occurred while loading transaction details</div>');
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

            $('#idEdit').val(data.id);
            $('#customer_IDEdit').val(data.customer_ID);
            $('#passenger_nameEdit').val(data.passenger_name);
            $('#transaction_IDEdit').val(data.transaction_ID);
            $('#app_numberEdit').val(data.app_number);
            $('#trans_numberEdit').val(data.trans_number);
            $('#payment_dateEdit').val(data.payment_date);
            $('#net_costEdit').val(data.net_cost);
            $('#sale_costEdit').val(data.sale_cost);
            $('#iban_infoEdit').val(data.iban_info);
            modal.modal('show');
          } else {
            $('#message').html('<div class="alert alert-danger">' + e.message + '</div>');
          }
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        }
      });
    });

    // Form submission for editing a transaction
    $('#modalEditAmer').on('submit', function() {
      var frm = $(this);
      var btn = $('#btnSaveEdit');
      var msg = $('#message');
      var modal = $('#modalEditAmer');

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

      // AJAX request to update the transaction
      $.ajax({
        url: 'amerController.php',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
          msg.html('<div class="alert alert-danger">An error occurred while saving transaction</div>');
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
        },
        success: function(res) {
          btn.removeAttr('disabled').html(btn.attr('data-temp'));
          if (res.status == 'success') {
            loadTransactions().then(function() {
              msg.html('<div class="alert alert-success">' + res.message + '</div>');
              $('#modalEditAmer').modal('hide');
            });
          } else {
            if (res.message == 'form_errors') {
              $.each(res.errors, function(key, value) {
                $('#' + key).addClass('is-invalid').next('.invalid-feedback').html(value);
              });
            } else {
              msg.html('<div class="alert alert-danger">' + res.message + '</div>');
            }
          }
        }
      });

      return false;
    });

  });
</script>