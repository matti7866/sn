<?php
ini_set('display_errors', 1);  
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

function validateInput($data, $fields) {
    $errors = [];
    foreach ($fields as $field => $message) {
        if (empty($data[$field])) {
            $errors[$field] = $message;
        }
    }
    return $errors;
}

$action = filterInput('action');

// Check if invalid action
$valid_actions = ['searchAmer', 'addAmer', 'updateAmer', 'deleteAmer', 'getAmer'];
if (!in_array($action, $valid_actions)) {
    api_response(['error' => 'Invalid action']);
}

try {
    switch ($action) {
        case 'searchAmer':
            $params = [
                'search'         => filterInput('search'),
                'customer_ID'    => filterInput('customer_ID'),
                'passenger_name' => filterInput('passenger_name'),
                'transaction_ID' => filterInput('transaction_ID'),
                'app_number'     => filterInput('app_number'),
                'trans_number'   => filterInput('trans_number'),
                'payment_date'   => filterInput('payment_date'),
                'net_cost'       => filterInput('net_cost'),
                'sale_cost'      => filterInput('sale_cost'),
                'iban_info'      => filterInput('iban_info')
            ];

            $where = [];
            if (!empty($params['search'])) {
                $where[] = "(passenger_name LIKE :search OR transaction_ID LIKE :search)";
                $params['search'] = "%{$params['search']}%";
            }

            foreach ($params as $key => $value) {
                if ($key !== 'search' && !empty($value)) {
                    $where[] = "$key = :$key";
                } else {
                    unset($params[$key]);
                }
            }

            $where = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';

            $stmt = $pdo->prepare("SELECT * FROM amer $where ORDER BY amerID DESC");
            $stmt->execute($params);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $html = '';
                foreach ($result as $row) {
                    $html .= '<tr>';
                    foreach ($row as $column => $value) {
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    $html .= '<td>';
                    $html .= '<button class="btn btn-sm btn-primary btn-edit" data-id="' . htmlspecialchars($row['amerID']) . '"><i class="fa fa-edit"></i></button>&nbsp;';
                    $html .= '<button class="btn btn-sm btn-danger btn-delete" data-id="' . htmlspecialchars($row['amerID']) . '"><i class="fa fa-trash"></i></button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }

                api_response(['status' => 'success', 'html' => $html]);
            } else {
                api_response(['status' => 'error', 'message' => 'No records found']);
            }
            break;

        case 'addAmer':
            $params = [
                'customer_ID'    => filterInput('customer_IDAdd'),
                'passenger_name' => filterInput('passenger_nameAdd'),
                'transaction_ID' => filterInput('transaction_IDAdd'),
                'app_number'     => filterInput('app_numberAdd'),
                'trans_number'   => filterInput('trans_numberAdd'),
                'payment_date'   => filterInput('payment_dateAdd'),
                'net_cost'       => filterInput('net_costAdd'),
                'sale_cost'      => filterInput('sale_costAdd'),
                'iban_info'      => filterInput('iban_infoAdd')
            ];

            $errors = validateInput($params, [
                'customer_ID'    => 'Customer ID is required',
                'passenger_name' => 'Passenger name is required',
                'transaction_ID' => 'Transaction ID is required',
                'app_number'     => 'App number is required',
                'trans_number'   => 'Trans number is required',
                'payment_date'   => 'Payment date is required',
                'net_cost'       => 'Net cost is required',
                'sale_cost'      => 'Sale cost is required',
                'iban_info'      => 'IBAN info is required'
            ]);

            if ($errors) {
                api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
            }

            $stmt = $pdo->prepare("INSERT INTO amer (customer_ID, passenger_name, transaction_ID, app_number, trans_number, payment_date, net_cost, sale_cost, iban_info) VALUES (:customer_ID, :passenger_name, :transaction_ID, :app_number, :trans_number, :payment_date, :net_cost, :sale_cost, :iban_info)");

            if (!$stmt->execute($params)) {
                api_response(['status' => 'error', 'message' => $stmt->errorInfo()]);
            } else {
                api_response(['status' => 'success', 'message' => 'Amer added successfully']);
            }
            break;

        case 'deleteAmer':
            $amerID = filterInput('id');
            $stmt = $pdo->prepare("DELETE FROM amer WHERE amerID = :amerID");
            $stmt->bindParam(":amerID", $amerID, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                api_response(['status' => 'error', 'message' => $stmt->errorInfo()]);
            } else {
                api_response(['status' => 'success', 'message' => 'Amer deleted successfully']);
            }
            break;

        case 'getAmer':
            $amerID = filterInput('id');
            $stmt = $pdo->prepare("SELECT * FROM amer WHERE amerID = :amerID");
            $stmt->bindParam(":amerID", $amerID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                api_response(['status' => 'error', 'message' => 'Amer not found']);
            } else {
                api_response(['status' => 'success', 'data' => $result]);
            }
            break;

        case 'updateAmer':
            $params = [
                'amerID'        => filterInput('idEdit'),
                'customer_ID'    => filterInput('customer_IDEdit'),
                'passenger_name' => filterInput('passenger_nameEdit'),
                'transaction_ID' => filterInput('transaction_IDEdit'),
                'app_number'     => filterInput('app_numberEdit'),
                'trans_number'   => filterInput('trans_numberEdit'),
                'payment_date'   => filterInput('payment_dateEdit'),
                'net_cost'       => filterInput('net_costEdit'),
                'sale_cost'      => filterInput('sale_costEdit'),
                'iban_info'      => filterInput('iban_infoEdit')
            ];

            $errors = validateInput($params, [
                'customer_ID'    => 'Customer ID is required',
                'passenger_name' => 'Passenger name is required',
                'transaction_ID' => 'Transaction ID is required',
                'app_number'     => 'App number is required',
                'trans_number'   => 'Trans number is required',
                'payment_date'   => 'Payment date is required',
                'net_cost'       => 'Net cost is required',
                'sale_cost'      => 'Sale cost is required',
                'iban_info'      => 'IBAN info is required'
            ]);

            if ($errors) {
                api_response(['status' => 'error', 'message' => 'form_errors', 'errors' => $errors]);
            }

            $stmt = $pdo->prepare("
                UPDATE amer
                SET
                    customer_ID = :customer_ID,
                    passenger_name = :passenger_name,
                    transaction_ID = :transaction_ID,
                    app_number = :app_number,
                    trans_number = :trans_number,
                    payment_date = :payment_date,
                    net_cost = :net_cost,
                    sale_cost = :sale_cost,
                    iban_info = :iban_info
                WHERE amerID = :amerID
            ");

            if (!$stmt->execute($params)) {
                api_response(['status' => 'error', 'message' => $stmt->errorInfo()]);
            } else {
                api_response(['status' => 'success', 'message' => 'Amer updated successfully']);
            }
            break;

        default:
            api_response(['status' => 'error', 'message' => 'Invalid action']);
            break;
    }
} catch (PDOException $e) {
    api_response(['status' => 'error', 'message' => $e->getMessage()]);
}