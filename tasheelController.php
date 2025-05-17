<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header('location:login.php');
    exit;
}

function api_response($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function filterInput($name)
{
    return htmlspecialchars(stripslashes(trim(isset($_POST[$name]) ? $_POST[$name] : '')));
}

function statusLabel($status)
{
    // Custom formatting for predefined status values
    if (empty($status)) {
        return '<span class="badge bg-secondary">No Status</span>';
    }
    
    if (strtolower($status) == 'pending') {
        return '<span class="badge bg-warning">Pending</span>';
    }
    
    if (strtolower($status) == 'completed') {
        return '<span class="badge bg-success">Completed</span>';
    }
    
    if (strtolower($status) == 'rejected') {
        return '<span class="badge bg-danger">Rejected</span>';
    }
    
    if (stripos($status, 'process') !== false || stripos($status, 'under') !== false) {
        return '<span class="badge bg-info">' . htmlspecialchars($status) . '</span>';
    }
    
    if (stripos($status, 'approved') !== false || stripos($status, 'success') !== false) {
        return '<span class="badge bg-success">' . htmlspecialchars($status) . '</span>';
    }
    
    if (stripos($status, 'reject') !== false || stripos($status, 'fail') !== false || stripos($status, 'error') !== false) {
        return '<span class="badge bg-danger">' . htmlspecialchars($status) . '</span>';
    }
    
    // Default formatting for any other status
    return '<span class="badge bg-secondary">' . htmlspecialchars($status) . '</span>';
}

// Function to generate pagination links
function generatePagination($currentPage, $totalPages, $statusFilter) {
    $pagination = '<ul class="pagination">';
    
    // Previous button
    if ($currentPage > 1) {
        $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($currentPage - 1) . '">Previous</a></li>';
    } else {
        $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $startPage + 4);
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $pagination .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
        } else {
            $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '">' . $i . '</a></li>';
        }
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . ($currentPage + 1) . '">Next</a></li>';
    } else {
        $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
    }
    
    $pagination .= '</ul>';
    return $pagination;
}

try {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    // Check if invalid action
    if (!in_array($action, ['searchTransactions', 'addTransaction', 'updateTransaction', 'deleteTransaction', 'getTransaction', 'changeStatus', 'addTransactionType', 'markAsCompleted'])) {
        api_response(['status' => 'error', 'message' => 'Invalid action']);
    }

    if ($action == 'addTransaction') {
        $company_id = filterInput('company_id');
        $transaction_type_id = filterInput('transaction_type_id');
        $transaction_number = filterInput('transaction_number');
        $cost = filterInput('cost');
        // Initialize mohrestatus as empty since it will be updated via API later
        $mohrestatus = '';
        // Default status for new transactions is "in_process"
        $status = 'in_process';

        // Convert empty optional fields to NULL
        $company_id = ($company_id === '') ? null : $company_id;
        $cost = ($cost === '') ? null : $cost;

        $errors = [];

        // Only transaction_type_id and transaction_number are required
        if (empty($transaction_type_id)) {
            $errors['transaction_type_id'] = 'Transaction Type is required';
        }
        if (empty($transaction_number)) {
            $errors['transaction_number'] = 'Transaction Number is required';
        }

        if (!empty($errors)) {
            api_response(['status' => 'error', 'errors' => $errors, 'message' => 'form_errors']);
        }

        // Check if transaction number already exists
        $sql = "SELECT COUNT(*) FROM `tasheel_transactions` WHERE `transaction_number` = :transaction_number";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':transaction_number', $transaction_number);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['transaction_number' => 'Transaction number already exists']]);
        }

        // Insert into database with status field
        $query = "
        INSERT INTO `tasheel_transactions` (`company_id`, `transaction_type_id`, `transaction_number`, `cost`, `mohrestatus`, `status`) 
        VALUES (:company_id, :transaction_type_id, :transaction_number, :cost, :mohrestatus, :status)
        ";
        
        $stmt = $pdo->prepare($query);
        if ($company_id === null) {
            $stmt->bindValue(':company_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        }
        $stmt->bindParam(':transaction_type_id', $transaction_type_id);
        $stmt->bindParam(':transaction_number', $transaction_number);
        if ($cost === null) {
            $stmt->bindValue(':cost', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':cost', $cost);
        }
        $stmt->bindParam(':mohrestatus', $mohrestatus);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        api_response(['status' => 'success', 'message' => 'Transaction added successfully']);
    }

    if ($action == 'searchTransactions') {
        $company = filterInput('company');
        $search = filterInput('search');
        $type = filterInput('type');
        $mohrestatus = filterInput('mohrestatus');
        $statusFilter = filterInput('status_filter');
        $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
        $recordsPerPage = 10;
        $offset = ($page - 1) * $recordsPerPage;

        $where = '';

        if (!empty($company)) {
            $where .= " AND tt.`company_id` = :company";
        }
        if (!empty($type)) {
            $where .= " AND tt.`transaction_type_id` = :type";
        }
        if (!empty($mohrestatus)) {
            $where .= " AND tt.`mohrestatus` = :mohrestatus";
        }

        if ($search != '') {
            $where .= " AND (tt.`transaction_number` LIKE :search)";
        }
        
        // Filter by status (in_process or completed)
        if ($statusFilter == 'in_process') {
            $where .= " AND (tt.`status` = 'in_process' OR tt.`status` IS NULL)";
        } else if ($statusFilter == 'completed') {
            $where .= " AND tt.`status` = 'completed'";
        }

        // Get total count for pagination
        $countSql = "
        SELECT COUNT(*) as total
        FROM `tasheel_transactions` tt
        WHERE 1 $where
        ";
        
        $stmt = $pdo->prepare($countSql);
        
        if (!empty($company)) {
            $stmt->bindParam(':company', $company);
        }
        if (!empty($type)) {
            $stmt->bindParam(':type', $type);
        }
        if (!empty($mohrestatus)) {
            $stmt->bindParam(':mohrestatus', $mohrestatus);
        }
        if ($search != '') {
            $search = "%$search%";
            $stmt->bindParam(':search', $search);
        }
        
        $stmt->execute();
        $totalCount = $stmt->fetchColumn();
        $totalPages = ceil($totalCount / $recordsPerPage);

        // Main query with pagination
        $sql = "
        SELECT tt.*, c.company_name as company_name, t.name as type_name
        FROM `tasheel_transactions` tt
        LEFT JOIN `company` c ON c.`company_id` = tt.`company_id`
        LEFT JOIN `transaction_type` t ON t.`id` = tt.`transaction_type_id`
        WHERE 1 $where
        ORDER BY tt.`id` DESC
        LIMIT :offset, :limit
        ";

        $stmt = $pdo->prepare($sql);

        if (!empty($company)) {
            $stmt->bindParam(':company', $company);
        }
        if (!empty($type)) {
            $stmt->bindParam(':type', $type);
        }
        if (!empty($mohrestatus)) {
            $stmt->bindParam(':mohrestatus', $mohrestatus);
        }
        if ($search != '') {
            $search = "%$search%";
            $stmt->bindParam(':search', $search);
        }
        
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $recordsPerPage, PDO::PARAM_INT);

        $stmt->execute();
        $transactions = $stmt->fetchAll();

        $html = '';
        if (count($transactions) > 0) {
            foreach ($transactions as $transaction) {
                $actions = [];

                // Add mark as completed button only for in-process transactions
                if ($statusFilter == 'in_process') {
                    $actions[] = '<button class="btn btn-sm btn-success mb-1 btn-mark-complete" data-id="' . $transaction['id'] . '">Mark Complete</button>';
                }

                $actions[] = '<button class="btn btn-sm btn-warning mb-1 btn-edit" data-id="' . $transaction['id'] . '" >Edit</button>';
                $actions[] = '<button class="btn btn-sm btn-danger mb-1 btn-delete" data-id="' . $transaction['id'] . '" >Delete</button>';
               
                $html .= '<tr>';
                $html .= '<td>' . $transaction['id'] . '</td>';
                $html .= '<td><strong>' . $transaction['company_name'] . '</strong>';
                
                // Add API company name if available and different
                if (!empty($transaction['api_company_name']) && $transaction['api_company_name'] != $transaction['company_name']) {
                    $html .= '<br><small class="text-muted">API: ' . htmlspecialchars($transaction['api_company_name']) . '</small>';
                }
                
                $html .= '</td>';
                
                $html .= '<td>Type: <strong>' . $transaction['type_name'] . '</strong>';
                
                // Add API transaction type if available
                if (!empty($transaction['api_transaction_type'])) {
                    $html .= '<br><small class="text-muted">API Type: ' . htmlspecialchars($transaction['api_transaction_type']) . '</small>';
                }
                
                $html .= '<br>Trx:# ' . $transaction['transaction_number'];
                
                // Add API emirates if available
                if (!empty($transaction['api_emirates'])) {
                    $html .= '<br>Emirates: ' . htmlspecialchars($transaction['api_emirates']);
                }
                
                // Add last status check time if available
                if (!empty($transaction['last_status_check'])) {
                    $checkTime = date('d M Y H:i', strtotime($transaction['last_status_check']));
                    $html .= '<br><small class="text-muted">Last Check: ' . $checkTime . '</small>';
                }
                
                $html .= '</td>';
                
                $html .= '<td>' . statusLabel($transaction['mohrestatus']) . '</td>';
                $html .= '<td>' . $transaction['cost'] . '</td>';
                $html .= '<td>' . implode('<br />', $actions) . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html = '<tr><td colspan="6" class="text-center">No transactions found</td></tr>';
        }
        
        // Generate pagination links
        $pagination = generatePagination($page, $totalPages, $statusFilter);
        
        // Generate page info text
        $start = min($totalCount, ($page - 1) * $recordsPerPage + 1);
        $end = min($totalCount, $page * $recordsPerPage);
        $pageInfo = "Showing $start to $end of $totalCount records";
        
        api_response([
            'status' => 'success', 
            'message' => 'Transactions fetched successfully', 
            'html' => $html,
            'pagination' => $pagination,
            'info' => $pageInfo
        ]);
    }

    if ($action == 'changeStatus') {
        $id = filterInput('id');
        $mohrestatus = filterInput('mohrestatus');

        $sql = "UPDATE `tasheel_transactions` SET `mohrestatus` = :mohrestatus WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':mohrestatus', $mohrestatus);
        $stmt->execute();
        api_response(['status' => 'success', 'message' => 'Status updated successfully']);
    }
    
    if ($action == 'markAsCompleted') {
        $id = filterInput('id');
        
        // Mark transaction as completed
        $sql = "UPDATE `tasheel_transactions` SET `status` = 'completed' WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            api_response(['status' => 'success', 'message' => 'Transaction marked as completed']);
        } else {
            api_response(['status' => 'error', 'message' => 'Failed to update transaction']);
        }
    }
    
    if ($action == 'getTransaction') {
        $id = filterInput('id');
        
        $sql = "SELECT * FROM `tasheel_transactions` WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($transaction) {
            api_response(['status' => 'success', 'data' => $transaction]);
        } else {
            api_response(['status' => 'error', 'message' => 'Transaction not found']);
        }
    }
    
    if ($action == 'updateTransaction') {
        // Log input data
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " updateTransaction Input: " . print_r($_POST, true) . "\n", FILE_APPEND);
        
        $id = filterInput('id');
        $company_id = filterInput('company_id');
        $transaction_type_id = filterInput('transaction_type_id');
        $transaction_number = filterInput('transaction_number');
        $cost = filterInput('cost');
        $mohrestatus = filterInput('mohrestatus');
        $status = filterInput('status') ?: 'in_process';
        
        // Convert empty optional fields to NULL
        $company_id = ($company_id === '') ? null : $company_id;
        $cost = ($cost === '') ? null : $cost;

        $errors = [];
        
        if (empty($transaction_type_id)) {
            $errors['transaction_type_id'] = 'Transaction Type is required';
        } else {
            $sql = "SELECT COUNT(*) FROM `transaction_type` WHERE `id` = :transaction_type_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':transaction_type_id', $transaction_type_id);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $errors['transaction_type_id'] = 'Invalid Transaction Type';
            }
        }
        if (empty($transaction_number)) {
            $errors['transaction_number'] = 'Transaction Number is required';
        }
        if (!empty($cost) && (!is_numeric($cost) || $cost < 0)) {
            $errors['cost'] = 'Cost must be a valid non-negative number';
        }
        
        if (!empty($errors)) {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " Errors: " . print_r($errors, true) . "\n", FILE_APPEND);
            api_response(['status' => 'error', 'errors' => $errors, 'message' => 'form_errors']);
        }
        
        // Check if transaction number belongs to another transaction
        $sql = "SELECT COUNT(*) FROM `tasheel_transactions` WHERE `transaction_number` = :transaction_number AND `id` != :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':transaction_number', $transaction_number);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " Transaction number exists\n", FILE_APPEND);
            api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['transaction_number' => 'Transaction number already exists']]);
        }
        
        // Check if transaction exists
        $sql = "SELECT COUNT(*) FROM `tasheel_transactions` WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            file_put_contents('debug.log', date('Y-m-d H:i:s') . " Transaction not found: ID $id\n", FILE_APPEND);
            api_response(['status' => 'error', 'message' => 'Transaction not found']);
        }
        
        // Update transaction
        $sql = "UPDATE `tasheel_transactions` SET 
                `company_id` = :company_id,
                `transaction_type_id` = :transaction_type_id,
                `transaction_number` = :transaction_number,
                `cost` = :cost,
                `mohrestatus` = :mohrestatus,
                `status` = :status
                WHERE `id` = :id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($company_id === null) {
            $stmt->bindValue(':company_id', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':company_id', $company_id, PDO::PARAM_INT);
        }
        $stmt->bindParam(':transaction_type_id', $transaction_type_id);
        $stmt->bindParam(':transaction_number', $transaction_number);
        if ($cost === null) {
            $stmt->bindValue(':cost', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':cost', $cost);
        }
        $stmt->bindParam(':mohrestatus', $mohrestatus);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        
        $affected_rows = $stmt->rowCount();
        file_put_contents('debug.log', date('Y-m-d H:i:s') . " Affected Rows: $affected_rows\n", FILE_APPEND);
        if ($affected_rows == 0) {
            api_response(['status' => 'error', 'message' => 'No changes made to the transaction']);
        }
        
        api_response(['status' => 'success', 'message' => 'Transaction updated successfully']);
    }
    
    if ($action == 'deleteTransaction') {
        $id = filterInput('id');
        
        $sql = "DELETE FROM `tasheel_transactions` WHERE `id` = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        api_response(['status' => 'success', 'message' => 'Transaction deleted successfully']);
    }

    if ($action == 'addTransactionType') {
        $name = filterInput('name');
        
        if (empty($name)) {
            api_response(['status' => 'error', 'message' => 'Transaction type name is required']);
        }
        
        // Check if type already exists
        $sql = "SELECT COUNT(*) FROM `transaction_type` WHERE name = :name";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            api_response(['status' => 'error', 'message' => 'This transaction type already exists']);
        }
        
        // Insert new type
        $sql = "INSERT INTO `transaction_type` (name) VALUES (:name)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        
        $typeId = $pdo->lastInsertId();
        
        api_response([
            'status' => 'success', 
            'message' => 'Transaction type added successfully', 
            'typeId' => $typeId
        ]);
    }

} catch (Exception $e) {
    api_response(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}