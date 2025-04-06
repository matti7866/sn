<?php  
session_start();  
if (!isset($_SESSION['user_id'])) {  
    header('location:login.php');  
}  
include 'connection.php';  

// Fetch permissions  
$sql = "SELECT permission.select, permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier'";  
$stmt = $pdo->prepare($sql);  
$stmt->bindParam(':role_id', $_SESSION['role_id']);  
$stmt->execute();  
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);  
$select = $records[0]['select'];  
$update = $records[0]['update'];  
$delete = $records[0]['delete'];  

if ($select == 0) {  
    echo "<script>window.location.href='pageNotFound.php'</script>";  
}  

if (isset($_POST['GetReport'])) {  
    // Pagination variables  
    $records_per_page = 10; // Number of records per page  
    $current_page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Current page number  
    $current_page = max($current_page, 1); // Ensure it's at least 1  
    $offset = ($current_page - 1) * $records_per_page; // Calculate offset  

    // Fetch total number of records for pagination  
    $countQuery = $pdo->query("SELECT COUNT(*) FROM `company`");  
    $total_records = $countQuery->fetchColumn();  
    $total_pages = ceil($total_records / $records_per_page); // Calculate total pages  

    // Fetch records with pagination  
    $selectQuery = $pdo->prepare("SELECT * FROM `company` LIMIT :limit OFFSET :offset");  
    $selectQuery->bindValue(':limit', $records_per_page, PDO::PARAM_INT);  
    $selectQuery->bindValue(':offset', $offset, PDO::PARAM_INT);  
    $selectQuery->execute();  

    // Fetch all of the remaining rows in the result set  
    $company_record = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);  

    // Prepare response data  
    $response = [  
        'data' => $company_record,  
        'total_pages' => $total_pages,  
        'current_page' => $current_page  
    ];  

    // Encoding array to JSON format  
    echo json_encode($response);  
}  

// Other actions (Delete, GetUpdSupplier, SaveUpdateSupplier) remain unchanged  

// Close connection  
unset($pdo);  
?>  