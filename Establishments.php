<?php include 'header.php'; ?>
<title>Cheques</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
   header('location:login.php');
}

$localName = isset($_GET['localName']) ? $_GET['localName'] : '';
$companyName = isset($_GET['companyName']) ? $_GET['companyName'] : '';
$companyType = isset($_GET['companyType']) ? $_GET['companyType'] : '';

$where = '';

if ($localName != '') {
   $where .= " AND md5(company.local_name) = '$localName'";
}
if ($companyName != '') {
   $where .= " AND company.company_name LIKE '%$companyName%'";
}
if ($companyType != '') {
   $where .= " AND company.company_type = '$companyType'";
}

// Pagination setup  
$records_per_page = 10; // Number of records to show per page  
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page number from the URL  
$current_page = max($current_page, 1); // Ensure current page is at least 1  
$offset = ($current_page - 1) * $records_per_page; // Calculate the offset  

// Fetch the total number of companies for pagination  
$totalCompaniesQuery = $pdo->query("SELECT COUNT(*) FROM company WHERE 1 $where");
$totalCompanies = $totalCompaniesQuery->fetchColumn();
$total_pages = ceil($totalCompanies / $records_per_page); // Calculate total pages  


// Query to fetch paginated data  
$stmt = $pdo->prepare("  
   SELECT company.*, IFNULL(COUNT(residence.residenceID), 0) as totalEmployees  
   FROM company  
   LEFT JOIN residence ON residence.company = company.company_id  
   WHERE 1 $where  
   GROUP BY company.company_id  
   ORDER BY company.company_id DESC  
   LIMIT :limit OFFSET :offset  
");
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to fetch total establishment and total quota for all records  
$stmt = $pdo->prepare("  
   SELECT COUNT(*) as totalEstablishments, SUM(starting_quota) as totalQuota  
   FROM company  
   WHERE 1 $where
");
$stmt->execute();
$totalData = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch unique local names from the company table  
$stmt = $pdo->prepare("SELECT DISTINCT local_name FROM company");
$stmt->execute();
$localNames = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize totals  
$totalEmployees = 0;
$totalQuota = 0;
foreach ($companies as $company) {
   $totalEmployees += $company['totalEmployees'];
   $totalQuota += $company['starting_quota'];
}

?>

<div class="container-fluid">
   <div class="row">
      <div class="col-md-5 mb-2">
         <h3>Establishments</h3>
      </div>
      <div class="col-md-7 text-end">
         <a class="btn btn-success" href="/manageEstablishments.php">Manage Establishments</a>
      </div>
   </div>
   <div class="row">
      <div class="col-md-3 mb-3">
         <div class="widget widget-stats bg-blue">
            <div class="stats-icon"><i class="fa fa-building"></i></div>
            <div class="stats-info">
               <h4>TOTAL ESTABLISHMENTS</h4>
               <p id="totalCompanies"><?php echo $totalData['totalEstablishments']; ?></p>
            </div>
         </div>
      </div>
      <div class="col-md-3 mb-3">
         <div class="widget widget-stats bg-red">
            <div class="stats-icon"><i class="fa fa-users"></i></div>
            <div class="stats-info">
               <h4>TOTAL EMPLOYEES</h4>
               <p id="totalEmployees"><?php echo number_format($totalEmployees); ?></p>
            </div>
         </div>
      </div>
      <div class="col-md-3 mb-3">
         <div class="widget widget-stats bg-purple">
            <div class="stats-icon"><i class="fa fa-address-card"></i></div>
            <div class="stats-info">
               <h4>TOTAL QUOTA</h4>
               <p id="totalQuota"><?php echo number_format($totalData['totalQuota']); ?></p>
            </div>
         </div>
      </div>
      <div class="col-md-3 mb-3">
         <div class="widget widget-stats bg-green">
            <div class="stats-icon"><i class="fa fa-at"></i></div>
            <div class="stats-info">
               <h4>AVAILABLE QUOTA</h4>
               <p id="availableQuota"><?php echo number_format($totalData['totalQuota'] - $totalEmployees); ?></p>
            </div>
         </div>
      </div>
   </div>
   <form action="" method="GET" id="frmSearchCompanies">
      <div class="row mb-4">
         <div class="col-md-3">
            <label class="form-label" for="companyName">Company Name</label>
            <input type="text" name="companyName" id="companyName" class="form-control" placeholder="Enter Company Name" value="<?php echo $companyName; ?>">
         </div>
         <div class="col-md-3 mb-2">
            <label class="form-label" for="companyType">Company Type <span class="text-danger">*</span></label>
            <select name="companyType" id="companyType" class="form-select">
               <option value="Mainland" <?php echo $companyType == 'Mainland' ? 'selected' : ''; ?>>Mainland</option>
               <option value="Freezone" <?php echo $companyType == 'Freezone' ? 'selected' : ''; ?>>Freezone</option>
            </select>
         </div>
         <div class="col-md-3 mv-2">
            <label class="form-label" for="localName">Local name</label>
            <select name="localName" id="localName" class="form-select">
               <option value="">Select Local Name</option>
               <?php foreach ($localNames as $ln): ?>
                  <option value="<?php echo md5($ln['local_name']); ?>" <?php echo $localName == md5($ln['local_name']) ? 'selected' : ''; ?>><?php echo $ln['local_name']; ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="col-md-1">
            <label for="submit">&nbsp;</label>
            <button type="submit" name="submit" id="submit" class="btn btn-primary w-100">Filter</button>
         </div>
      </div>
   </form>

   <div class="row">
      <?php foreach ($companies as $company): ?>
         <div class="col-sm-6 mb-3" data-total-employees="<?php echo $company['totalEmployees']; ?>" data-starting-quota="<?php echo $company['starting_quota']; ?>" data-type="<?php echo $company['company_type']; ?>" data-local-name="<?php echo md5($company['local_name']); ?>">
            <div class="card">
               <div class="card-body">
                  <h5 class="mb-0"><?php echo $company['company_name']; ?></h5>
                  <p class="mb-0"><i class="fa fa-building"></i> <?php echo $company['company_type']; ?></p>
                  <div class="row mt-3">
                     <div class="col-md-3">
                        <strong>Employees</strong><br /><?php echo $company['totalEmployees']; ?>
                     </div>
                     <div class="col-md-3">
                        <strong>A. Quota</strong><br /><?php echo $company['starting_quota'] - $company['totalEmployees']; ?>
                     </div>
                     <div class="col-md-3">
                        <strong>Expiry Date</strong><br /><?php echo date("M d, Y", strtotime($company['company_expiry'])); ?>
                     </div>
                     <div class="col-md-3">
                        <strong>Code</strong><br /><?php echo $company['company_number']; ?>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12 mt-4">
                        <a href="EstablishmentEmployees.php?companyID=<?php echo $company['company_id']; ?>" class="btn btn-sm btn-primary">View Employees</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   </div>

   <!-- Pagination controls -->
   <nav aria-label="Page navigation" class="custom-pagination mt-4 mb-4"> <!-- Added mt-4 for margin-top -->
      <ul class="pagination justify-content-center">
         <?php if ($current_page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $current_page - 1; ?>&companyName=<?php echo $companyName; ?>&companyType=<?php echo $companyType; ?>&localName=<?php echo $localName; ?>">&laquo; Previous</a></li>
         <?php endif; ?>

         <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
               <a class="page-link" href="?page=<?php echo $i; ?>&companyName=<?php echo $companyName; ?>&companyType=<?php echo $companyType; ?>&localName=<?php echo $localName; ?>"><?php echo $i; ?></a>
            </li>
         <?php endfor; ?>

         <?php if ($current_page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $current_page + 1; ?>&companyName=<?php echo $companyName; ?>&companyType=<?php echo $companyType; ?>&localName=<?php echo $localName; ?>">Next &raquo;</a></li>
         <?php endif; ?>
      </ul>
   </nav>

</div>
<?php require 'footer.php'; ?>

<style>
   .custom-pagination {
      margin-top: 30px;
      margin-bottom: 40px;
   }
</style>

<!-- <script type="text/javascript">
   function filterCompanies() {
      var companyType = $('#companyType').val();
      var localName = $('#localName').val();
      $('.col-sm-6').each(function() {
         var type = $(this).data('type');
         var local = $(this).data('local-name');
         if (companyType == type && (localName == '' || localName == local)) {
            $(this).show();
         } else {
            $(this).hide();
         }
      });

      // count only shown companies  
      var totalCompanies = $('.col-sm -->