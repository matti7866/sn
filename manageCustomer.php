<?php
include 'header.php';
?>
<title>Accounts Report</title>
<?php
include 'nav.php';
if(!isset($_SESSION['user_id']))
{
    header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<style>
    .bg-blue{
        background: #000046;  /* fallback for old browsers */
        background: -webkit-linear-gradient(to right, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
        background: linear-gradient(to right, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .text-blue{
        color: #000046;  /* fallback for old browsers */
        color: -webkit-linear-gradient(to right, #1CB5E0, #000046);  /* Chrome 10-25, Safari 5.1-6 */
        color: linear-gradient(to right, #1CB5E0, #000046); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
  #customBtn{ color:#1CB5E0;border-color:#000046; }
  #customBtn:hover{color:  #1CB5E0;background-color:#000046;border-color:#000046}
  
  .filter-card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: none;
  }
  
  .pagination {
    justify-content: center;
    margin-top: 20px;
  }
  
  .pagination .page-item.active .page-link {
    background-color: #000046;
    border-color: #000046;
  }
  
  .pagination .page-link {
    color: #000046;
  }
  
  .filter-btn {
    background: linear-gradient(to right, #1CB5E0, #000046);
    color: white;
    border: none;
  }
  
  .filter-btn:hover {
    opacity: 0.9;
    color: white;
  }
  
  .data-table {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }
  
  .table th {
    font-weight: 600;
  }
  
  .badge-active {
    background-color: #28a745;
    color: white;
  }
  
  .badge-inactive {
    background-color: #dc3545;
    color: white;
  }
  
  .modal-content {
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  }
</style>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card filter-card">
        <div class="card-header bg-blue">
          <h4 class="text-white mb-0"><i class="fa fa-filter"></i> Filter Customers</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label for="filter_name">Customer Name</label>
                <input type="text" class="form-control" id="filter_name" placeholder="Search by name">
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label for="filter_phone">Phone Number</label>
                <input type="text" class="form-control" id="filter_phone" placeholder="Search by phone">
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label for="filter_email">Email</label>
                <input type="text" class="form-control" id="filter_email" placeholder="Search by email">
              </div>
            </div>
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label for="filter_status">Status</label>
                <select class="form-control" id="filter_status">
                  <option value="">All</option>
                  <option value="1">Active</option>
                  <option value="2">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-md-3 mb-2">
              <div class="form-group">
                <label for="filter_supplier">Supplier</label>
                <select class="form-control" id="filter_supplier">
                  <option value="">All Suppliers</option>
                </select>
              </div>
            </div>
            <div class="col-md-9 d-flex align-items-end justify-content-end">
              <button type="button" id="applyFilters" class="btn filter-btn me-2"><i class="fa fa-search"></i> Search</button>
              <button type="button" id="resetFilters" class="btn btn-secondary"><i class="fa fa-refresh"></i> Reset</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card" id="todaycard">
        <div class="card-header bg-blue d-flex justify-content-between align-items-center">
          <h2 class="text-white mb-0"><b><i class="fa fa-fw fa-user text-dark"></i> <i>Customers Report</i></b></h2>
          <?php if($insert ==1) { ?>
          <button type="button" class="btn btn-light" id="customBtn" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fa fa-plus"></i> Add Customer
          </button>
          <?php } ?>
        </div>
        <div class="card-body">
          <div class="table-responsive data-table">
            <table id="myTable" class="table table-striped table-hover">
              <thead class="text-white bg-blue">
                <tr class="text-center">
                  <th>S#</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Whatsapp</th>
                  <th>Customer Address</th>
                  <th>Customer Email</th>
                  <th>Password</th>
                  <th>Status</th>
                  <th>Affiliate</th>
                  <?php if($update == 1 || $delete == 1) { ?>
                  <th>Action</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody id="StaffReportTbl">
                <!-- Data will be loaded here -->
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="row">
            <div class="col-md-6">
              <div class="mt-3">
                <span id="page_info">Showing 0 to 0 of 0 entries</span>
              </div>
            </div>
            <div class="col-md-6">
              <nav aria-label="Page navigation">
                <ul class="pagination" id="pagination">
                  <!-- Pagination will be loaded here -->
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- INSERT Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Customer</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_name" id="customer_name" placeholder="Customer Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-phone"></i> Customer Phone:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_phone" id="customer_phone" placeholder="Customer Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-whatsapp"></i> Customer Whatsapp:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_whatsapp" id="customer_whatsapp" placeholder="Customer Whatsapp">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-address-card"></i> Customer Address:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_address" id="customer_address" placeholder="Customer Address">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-envelope"></i> Customer Email:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_email" id="customer_email" placeholder="Customer Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Password:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="customer_password" id="customer_password" placeholder="Customer Password">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Status:</label>
            <div class="col-sm-7">
              <select class="form-control" id="customer_status" name="customer_status">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Affliate Supplier:</label>
            <div class="col-sm-7">
            <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="supplier" name="supplier" spry:default="select one"></select>
            </div>
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn filter-btn">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-blue" >
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Customer</i></b></h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="customer_id" name="customer_id" />
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i> Customer Name:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_name" id="updcustomer_name" placeholder="Customer Name">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-phone"></i> Customer Phone:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_phone" id="updcustomer_phone" placeholder="Customer Phone">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-whatsapp"></i> Customer Whatsapp:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_whatsapp" id="updcustomer_whatsapp" placeholder="Customer Whatsapp">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-address-card"></i> Customer Address:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_address" id="updcustomer_address" placeholder="Customer Address">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-envelope"></i> Customer Email:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_email" id="updcustomer_email" placeholder="Customer Email">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Password:</label>
            <div class="col-sm-7">
              <input type="text" class="form-control"  name="updcustomer_password" id="updcustomer_password" placeholder="Customer Password">
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-key"></i> Customer Status:</label>
            <div class="col-sm-7">
              <select class="form-control" id="updcustomer_status" name="updcustomer_status">
                <option value="1">Active</option>
                <option value="2">Deactive</option>
              </select>
            </div>
          </div>
          <div class="form-group row mb-2">
            <label for="inputPassword" class="col-sm-5 col-form-label"><i class="fa fa-user"></i>Affliate Supplier:</label>
            <div class="col-sm-7">
            <select class="col-sm-4 form-control js-example-basic-single" style="width:100%" id="updSupplier" name="updSupplier" spry:default="select one"></select>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn filter-btn">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
<script>
  // Declare currentPage and other pagination variables globally
  let currentPage = 1;
  let recordsPerPage = 10;
  let totalRecords = 0;
  let totalPages = 0;
  
  $(document).ready(function(){
    // Initialize Select2 for dropdowns
    $("#supplier").select2({
       dropdownParent: $("#exampleModal")
    });
    $("#updSupplier").select2({
       dropdownParent: $("#updexampleModal")
    });
    $("#filter_supplier").select2();
    
    // Load initial data
    getCustomersReport();
    getSupplier('byAdd', null);
    loadFilterSuppliers();
    
    // Handle filter button click
    $("#applyFilters").click(function() {
      currentPage = 1;
      getCustomersReport();
    });
    
    // Handle reset filters
    $("#resetFilters").click(function() {
      $("#filter_name").val("");
      $("#filter_phone").val("");
      $("#filter_email").val("");
      $("#filter_status").val("");
      $("#filter_supplier").val("").trigger('change');
      currentPage = 1;
      getCustomersReport();
    });
    
    // Handle pagination clicks
    $(document).on('click', '.page-link', function(e) {
      e.preventDefault();
      const page = $(this).data('page');
      if (page) {
        currentPage = page;
        getCustomersReport();
      }
    });
  });

function loadFilterSuppliers() {
  var select_supplier = "SELECT_Supplier";
  $.ajax({
    type: "POST",
    url: "ticketController.php",  
    data: {
      SELECT_Supplier: select_supplier,
    },
    success: function (response) {  
      var supplier = JSON.parse(response);
      $('#filter_supplier').empty();
      $('#filter_supplier').append("<option value=''>All Suppliers</option>");
      for(var i=0; i<supplier.length; i++) {
        $('#filter_supplier').append("<option value='"+ supplier[i].supp_id +"'>"+ 
        supplier[i].supp_name +"</option>");
      }
    },
  });
}

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this customer',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "manageCustomerController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getCustomersReport();
                }else{
                  notify('Opps!', response, 'error');
                }
              },
            });
            }
        },
        close: function () {
        }
    }
});
}

function GetDataForUpdate(id){
  var GetDataForUpdate = "GetDataForUpdate";
  $.ajax({
          type: "POST",
          url: "manageCustomerController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
        },
        success: function (response) {  
            var dataRpt = JSON.parse(response);
            $('#customer_id').val(id);
            $('#updcustomer_name').val(dataRpt[0].customer_name);
            $('#updcustomer_phone').val(dataRpt[0].customer_phone);
            $('#updcustomer_whatsapp').val(dataRpt[0].customer_whatsapp);
            $('#updcustomer_address').val(dataRpt[0].customer_address);
            $('#updcustomer_email').val(dataRpt[0].customer_email);
            $('#updcustomer_status').val(dataRpt[0].status);
            getSupplier('byUpdate', dataRpt[0].affliate_supp_id);
            $('#updexampleModal').modal('show');
        },
  });
}

function getCustomersReport(){
      var getCustomersReport = "getCustomersReport";
      
      // Get filter values
      const filterName = $("#filter_name").val();
      const filterPhone = $("#filter_phone").val();
      const filterEmail = $("#filter_email").val();
      const filterStatus = $("#filter_status").val();
      const filterSupplier = $("#filter_supplier").val();
      
      $.ajax({
          type: "POST",
          url: "manageCustomerController.php",  
          data: {
            GetCustomersReport: getCustomersReport,
            page: currentPage,
            limit: recordsPerPage,
            filterName: filterName,
            filterPhone: filterPhone,
            filterEmail: filterEmail,
            filterStatus: filterStatus,
            filterSupplier: filterSupplier
          },
          success: function (response) {  
            console.log(response);
            var result = JSON.parse(response);
            
            // Set pagination data
            totalRecords = result.total;
            totalPages = Math.ceil(totalRecords / recordsPerPage);
            const startRecord = ((currentPage - 1) * recordsPerPage) + 1;
            const endRecord = Math.min(startRecord + recordsPerPage - 1, totalRecords);
            
            // Update page info
            $("#page_info").text(`Showing ${totalRecords > 0 ? startRecord : 0} to ${endRecord} of ${totalRecords} entries`);
            
            // Render pagination
            renderPagination(currentPage, totalPages);
            
            // Extract customer data
            var CustomerRpt = result.data;
            
            $('#StaffReportTbl').empty();
            var finalTable = "";
            
            if (CustomerRpt.length === 0) {
              finalTable = "<tr><td colspan='10' class='text-center'>No records found</td></tr>";
              $('#StaffReportTbl').append(finalTable);
              return;
            }
            
            for(var i=0; i<CustomerRpt.length; i++){
              const index = startRecord + i;
              let statusBadge = CustomerRpt[i].status == "Active" ? 
                "<span class='badge badge-active rounded-pill'>Active</span>" : 
                "<span class='badge badge-inactive rounded-pill'>Inactive</span>";
                
              finalTable = "<tr><th scope='row' class='text-center'>"+ index + "</th><td class='text-capitalize text-center'>"+ CustomerRpt[i].customer_name
              +"</td><td class='text-center'>"+CustomerRpt[i].customer_phone + "</td><td class='text-center'>"+CustomerRpt[i].customer_whatsapp + 
              "</td><td class='text-capitalize text-center'>"+CustomerRpt[i].customer_address + "</td><td class='text-center'>"+
              CustomerRpt[i].customer_email + "</td><td class='text-center'>"+CustomerRpt[i].cust_password+"</td><td class='text-center'>"+ statusBadge +"</td>";
              
              if(CustomerRpt[i].affliate_supp_id == 1){
                finalTable += "<td class='text-center'><i class='fas fa-medal text-info' style='font-size:20px' ></i></td>" ;
              }else{
                finalTable += "<td class='text-center'>-</td>";
              }
              
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "<td style='width:150px' class='text-center'>";
              <?php } ?>
              
              <?php if($update == 1) { ?>
              finalTable += "<button type='button' onclick='GetDataForUpdate("+ 
              CustomerRpt[i].customer_id +")'" +
              "class='btn btn-sm btn-info me-2'><i class='fa fa-edit' aria-hidden='true'></i></button>";
              <?php } ?>
              
              <?php if($delete == 1) { ?>
              finalTable += "<button type='button' onclick='Delete("+ 
              CustomerRpt[i].customer_id +")'" +
              "class='btn btn-sm btn-danger'><i class='fa fa-trash' aria-hidden='true'></i></button>";
              <?php } ?>
              
              <?php if($update == 1 || $delete == 1) { ?>
              finalTable += "</td>";
              <?php } ?>
              
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
            }
          },
          error: function(xhr, status, error) {
            console.error("Error fetching customer data:", error);
            notify('Error!', 'Failed to load customer data', 'error');
          }
      });
}

function renderPagination(currentPage, totalPages) {
  $('#pagination').empty();
  
  // Don't show pagination if there's only 1 page
  if (totalPages <= 1) return;
  
  // Previous button
  let pagination = `
    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
  `;
  
  // Page numbers
  const maxVisiblePages = 5;
  let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
  let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
  
  // Adjust if we're near the end
  if (endPage - startPage + 1 < maxVisiblePages) {
    startPage = Math.max(1, endPage - maxVisiblePages + 1);
  }
  
  // First page
  if (startPage > 1) {
    pagination += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
    if (startPage > 2) {
      pagination += `<li class="page-item disabled"><a class="page-link" href="#">...</a></li>`;
    }
  }
  
  // Page numbers
  for (let i = startPage; i <= endPage; i++) {
    pagination += `<li class="page-item ${i === currentPage ? 'active' : ''}">
      <a class="page-link" href="#" data-page="${i}">${i}</a>
    </li>`;
  }
  
  // Last page
  if (endPage < totalPages) {
    if (endPage < totalPages - 1) {
      pagination += `<li class="page-item disabled"><a class="page-link" href="#">...</a></li>`;
    }
    pagination += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages}">${totalPages}</a></li>`;
  }
  
  // Next button
  pagination += `
    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
      <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  `;
  
  $('#pagination').append(pagination);
}

$(document).on('submit', '#CountryNameForm', function(event){
    event.preventDefault();
    var customer_id = $('#customer_id');
    var customer_name = $('#customer_name');
    if(customer_name.val() == ""){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    var customer_phone = $('#customer_phone');
    var customer_whatsapp = $('#customer_whatsapp');
    var customer_address = $('#customer_address');
    var customer_email = $('#customer_email');
    var customer_password = $('#customer_password');
    var customer_status = $('#customer_status');
    var supplier = $('#supplier');
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "manageCustomerController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#CountryNameForm')[0].reset();
                    $('#supplier').val(-1).trigger('change.select2');
                    $('#exampleModal').modal('hide');
                    getCustomersReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var updcustomer_name = $('#updcustomer_name');
    if(updcustomer_name.val() == ""){
        notify('Validation Error!', 'Customer name is required', 'error');
        return;
    }
    var updcustomer_phone = $('#updcustomer_phone');
    var updcustomer_whatsapp = $('#updcustomer_whatsapp');
    var updcustomer_address = $('#updcustomer_address');
    var updcustomer_email = $('#updcustomer_email');
    var updcustomer_password = $('#updcustomer_password');
    var updcustomer_status = $('#updcustomer_status');
    var updSupplier = $('#updSupplier');
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "manageCustomerController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updexampleModal').modal('hide');
                    getCustomersReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function getSupplier(type,id){
    var select_supplier = "SELECT_Supplier";
    $.ajax({
        type: "POST",
        url: "ticketController.php",  
        data: {
            SELECT_Supplier:select_supplier,
        },
        success: function (response) {  
            var supplier = JSON.parse(response);
            if(type =="byAdd"){
              $('#supplier').empty();
              $('#supplier').append("<option value='-1'>--Supplier--</option>");
              for(var i=0; i<supplier.length; i++){
                $('#supplier').append("<option value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
          }else{
            $('#updSupplier').empty();
              $('#updSupplier').append("<option value='-1'>--Supplier--</option>");
              var selected = "";
              for(var i=0; i<supplier.length; i++){
                if(supplier[i].supp_id == id){
                  selected = "selected";
                }else{
                  selected = "";
                }
                $('#updSupplier').append("<option "+ selected + " value='"+ supplier[i].supp_id +"'>"+ 
                supplier[i].supp_name +"</option>");
              }
          }
        },
    });
    }
</script>
</body>
</html>