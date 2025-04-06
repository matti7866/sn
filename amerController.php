<?php
session_start();
include 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header('location:login.php');
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
    if ($status == 'pending') {
        return '<span class="badge bg-warning">Pending</span>';
    }
    if ($status == 'completed') {
        return '<span class="badge bg-success">Completed</span>';
    }
    if ($status == 'rejected') {
        return '<span class="badge bg-danger">Rejected</span>';
    }

    if ($status == 'refunded') {
        return '<span class="badge bg-success">Refunded</span>';
    }
    if ($status == 'visit_required') {
        return '<span class="badge bg-warning">Visit Required</span>';
    }

    return '<span class="badge bg-secondary">Unknown</span>';
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

// Check if invalid action
if (!in_array($action, ['searchTransactions', 'addTransaction', 'updateTransaction', 'deleteTransaction', 'getTransaction', 'getTypes', 'addType', 'updateType', 'getType', 'deleteType', 'changeStatus'])) {
    api_response(['error' => 'Invalid action']);
}


if ($action == 'getTypes') {
    $sql = "SELECT * FROM `amer_types`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $types = $stmt->fetchAll();
    api_response([
        'status' => 'success',
        'data' => $types
    ]);
}

if ($action == 'getType') {
    $id = filterInput('id');
    $sql = "SELECT * FROM `amer_types` WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $type = $stmt->fetch();
    api_response([
        'status' => 'success',
        'data' => $type
    ]);
}


if ($action == 'addType') {
    $name = filterInput('name');
    $cost_price = filterInput('cost_price');
    $sale_price = filterInput('sale_price');

    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    if (empty($cost_price)) {
        $errors['cost_price'] = 'Cost Price is required';
    }
    if (empty($sale_price)) {
        $errors['sale_price'] = 'Sale Price is required';
    }

    if (!empty($errors)) {
        api_response(['status' => 'error', 'errors' => $errors, 'message' => 'form_errors']);
    }

    // check if type already exists
    $sql = "SELECT COUNT(*) FROM `amer_types` WHERE LCASE(`name`) = LCASE(:name)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['name' => 'Type already exists']]);
    }

    $sql = "INSERT INTO `amer_types` (`name`, `cost_price`, `sale_price`) VALUES (:name, :cost_price, :sale_price)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cost_price', $cost_price);
    $stmt->bindParam(':sale_price', $sale_price);
    $stmt->execute();
    api_response(['status' => 'success', 'message' => 'Type added successfully']);
}


if ($action == 'deleteType') {
    $id = filterInput('id');

    // first check if there are any transactions using this type
    $sql = "SELECT COUNT(*) FROM `amer` WHERE `type_id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        api_response(['status' => 'error', 'message' => 'Type cannot be deleted as it is being used in transactions']);
    }

    $sql = "DELETE FROM `amer_types` WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    api_response(['status' => 'success', 'message' => 'Type deleted successfully']);
}

if ($action == 'updateType') {
    $id = filterInput('id');
    $name = filterInput('name');
    $cost_price = filterInput('cost_price');
    $sale_price = filterInput('sale_price');

    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    if (empty($cost_price)) {
        $errors['cost_price'] = 'Cost Price is required';
    }
    if (empty($sale_price)) {
        $errors['sale_price'] = 'Sale Price is required';
    }

    if (!empty($errors)) {
        api_response(['status' => 'error', 'errors' => $errors, 'message' => 'form_errors']);
    }

    // check if type already exists
    $sql = "SELECT COUNT(*) FROM `amer_types` WHERE LCASE(`name`) = LCASE(:name) AND `id` != :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['name' => 'Type already exists']]);
    }

    $sql = "UPDATE `amer_types` SET `name` = :name, `cost_price` = :cost_price, `sale_price` = :sale_price WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':cost_price', $cost_price);
    $stmt->bindParam(':sale_price', $sale_price);
    $stmt->execute();
    api_response(['status' => 'success', 'message' => 'Type updated successfully']);
}



// AMER


if ($action == 'addTransaction') {

    $customer_id = filterInput('customer_id');
    $passenger_name = filterInput('passenger_name');
    $type_id = filterInput('type_id');
    $application_number = filterInput('application_number');
    $transaction_number = filterInput('transaction_number');
    $payment_date = filterInput('payment_date');
    $cost_price = filterInput('cost_price');
    $sale_price = filterInput('sale_price');
    $iban = filterInput('iban');
    $file = isset($_FILES['receipt']) ? $_FILES['receipt'] : null;
    $status = filterInput('status');

    $errors = [];

    if (empty($customer_id) || $customer_id == '') {
        $errors['customer_id'] = 'Customer is required';
    }
    if (empty($passenger_name)) {
        $errors['passenger_name'] = 'Passenger Name is required';
    }
    if (empty($type_id)) {
        $errors['type_id'] = 'Type is required';
    }
    if (empty($application_number)) {
        $errors['application_number'] = 'Application Number is required';
    }
    if (empty($transaction_number)) {
        $errors['transaction_number'] = 'Transaction Number is required';
    }
    if (empty($payment_date)) {
        $errors['payment_date'] = 'Payment Date is required';
    }
    if (empty($cost_price)) {
        $errors['cost_price'] = 'Net Cost is required';
    }
    if (empty($sale_price)) {
        $errors['sale_price'] = 'Sale Cost is required';
    }

    if (!empty($errors)) {
        api_response(['status' => 'error', 'errors' => $errors, 'message' => 'form_errors']);
    }

    // check if transaction number already exists
    $sql = "SELECT COUNT(*) FROM `amer` WHERE `transaction_number` = :transaction_number";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':transaction_number', $transaction_number);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['transaction_number' => 'Transaction number already exists']]);
    }

    // check if application number already exists
    $sql = "SELECT COUNT(*) FROM `amer` WHERE `application_number` = :application_number";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':application_number', $application_number);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => ['application_number' => 'Application number already exists']]);
    }

    // insert into database

    $query = "
    INSERT INTO `amer` (`customer_id`, `passenger_name`, `type_id`, `application_number`, `transaction_number`, `payment_date`, `cost_price`, `sale_price`, `iban`, `status`, `datetime`) 
    VALUES (:customer_id, :passenger_name, :type_id, :application_number, :transaction_number, :payment_date, :cost_price, :sale_price, :iban, :status, :datetime)
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':passenger_name', $passenger_name);
    $stmt->bindParam(':type_id', $type_id);
    $stmt->bindParam(':application_number', $application_number);
    $stmt->bindParam(':transaction_number', $transaction_number);
    $stmt->bindParam(':payment_date', $payment_date);
    $stmt->bindParam(':cost_price', $cost_price);
    $stmt->bindParam(':sale_price', $sale_price);
    $stmt->bindParam(':iban', $iban);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':datetime', date("Y-m-d H:i:s"));
    $stmt->execute();

    // check if there is an error
    if ($stmt->errorCode() != 0) {
        api_response(['status' => 'error', 'message' => $stmt->errorInfo(), 'compliedQuery' => $stmt]);
    }

    api_response(['status' => 'success', 'message' => 'Transaction added successfully', 'compliedQuery' => $stmt]);
}

if ($action == 'searchTransactions') {

    $start_date = filterInput('start_date');
    $end_date = filterInput('end_date');
    $customer = filterInput('customer');
    $search = filterInput('search');
    $type = filterInput('type');
    $status = filterInput('status');


    $where = '';

    if (!empty($customer)) {
        $where .= " AND amer.`customer_id` = :customer";
    }
    if (!empty($type)) {
        $where .= " AND amer.`type_id` = :type";
    }
    if (!empty($status)) {
        $where .= " AND amer.`status` = :status";
    }

    if ($start_date != '' && $end_date != '') {
        $where .= " AND amer.`datetime` BETWEEN :start_date AND :end_date";
    }
    if ($search != '') {
        $where .= " AND (amer.`transaction_number` LIKE :search OR amer.`application_number` LIKE :search OR amer.`passenger_name` LIKE :search OR amer.`iban` LIKE :search)";
    }


    $sql = "
    SELECT amer.*, customer.customer_name , amer_types.name as type_name
    FROM `amer` 
    LEFT JOIN `customer` ON `customer`.`customer_id` = `amer`.`customer_id`
    LEFT JOIN `amer_types` ON `amer_types`.`id` = `amer`.`type_id`
    WHERE 1 $where
    GROUP BY `amer`.`id`
    ORDER BY `amer`.`id` DESC
    ";

    $stmt = $pdo->prepare($sql);

    if (!empty($customer)) {
        $stmt->bindParam(':customer', $customer);
    }
    if (!empty($type)) {
        $stmt->bindParam(':type', $type);
    }
    if (!empty($status)) {
        $stmt->bindParam(':status', $status);
    }
    if ($search != '') {
        $stmt->bindParam(':search', $search);
    }

    if ($start_date != '' && $end_date != '') {
        $startDate = "{$start_date} 00:00:00";
        $endDate = "{$end_date} 23:59:59";
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
    }

    $stmt->execute();
    $transactions = $stmt->fetchAll();





    $html = '';
    foreach ($transactions as $transaction) {

        $actions = [];

        //$actions[] = '<button class="btn btn-sm btn-primary mb-1" data-id="' . $transaction['id'] . '" data-action="updateTransaction" data-status="completed">Complete</button>';
        //$actions[] = '<button class="btn btn-sm btn-danger mb-1" data-id="' . $transaction['id'] . '" data-action="updateTransaction" data-status="rejected">Reject</button>';


        $actions[] = '<button class="btn btn-sm btn-warning mb-1 btn-edit" data-id="' . $transaction['id'] . '" >Edit</button>';
        $actions[] = '<button class="btn btn-sm btn-danger mb-1 btn-delete" data-id="' . $transaction['id'] . '" >Delete</button>';
        $actions[] = '<button class="btn btn-sm btn-primary mb-1 btnChangeStatus" data-status="' . $transaction['status'] . '" data-id="' . $transaction['id'] . '" >Change Status</button>';


        $html .= '<tr>';
        $html .= '<td>' . $transaction['id'] . '</td>';
        $html .= '<td><strong>' . $transaction['customer_name'] . '</strong><br />' . $transaction['passenger_name'] . '</td>';

        $html .= '<td>Type: <strong>' . $transaction['type_name'] . '</strong><br />Trx:# ' . $transaction['transaction_number'] . '<br />App:# ' . $transaction['application_number'] . '<br />Payment Date: ' . date('d-m-Y', strtotime($transaction['payment_date'])) . '<br />IBAN: ' . $transaction['iban'] . '</td>';
        $html .= '<td>' . statusLabel($transaction['status']) . '</td>';
        $html .= '<td>' . $transaction['cost_price'] . '</td>';
        $html .= '<td>' . $transaction['sale_price'] . '</td>';

        $html .= '<td>' . implode('<br />', $actions) . '</td>';
        $html .= '</tr>';
    }
    api_response(['status' => 'success', 'message' => 'Transactions fetched successfully', 'html' => $html]);
}


if ($action == 'changeStatus') {

    $id = filterInput('id');
    $status = filterInput('status');

    $sql = "UPDATE `amer` SET `status` = :status WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
    api_response(['status' => 'success', 'message' => 'Status updated successfully']);
}
