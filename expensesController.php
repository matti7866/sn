<?php
session_start();

// Check if user is logged in and has a role
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header('location:login.php');
    exit();
}

// Database connection
include 'connection.php';

// Ensure PDO throws exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check permissions for inserting expenses
$sql = "SELECT `insert` FROM `permission` WHERE role_id = :role_id AND page_name = 'Expenses'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$result = $stmt->fetch(\PDO::FETCH_ASSOC);
if (!$result || $result['insert'] == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
    exit();
}

// Handle SaveExpenseType request
if (isset($_POST['SaveExpenseType'])) {
    try {
        $image = '';
        if (!empty($_FILES['uploadFile']['name'])) {
            $image = upload_Image($_FILES['uploadFile']['name']);
            if ($image === '') {
                $image = 'Error';
            }
        }
        $pdo->beginTransaction();
        if ($image === 'Error') {
            $pdo->rollback();
            echo "Record not added because of file uploader";
        } else {
            // Assign POST and FILES values to variables
            $staff_id = $_SESSION['user_id'];
            $expense_type_id = $_POST['expense_type'] ?? '';
            $expense_amount = $_POST['amount'] ?? '';
            $currency_id = $_POST['expCurrencyType'] ?? '';
            $expense_remark = $_POST['remarks'] ?? '';
            $account_id = $_POST['addaccount_id'] ?? '';
            $expense_document = $image;
            $original_name = $_FILES['uploadFile']['name'] ?? '';
            $amount_type = $_POST['amount_type'] ?? 'fixed'; // Default to 'fixed' if not provided
            $time_creation = date('Y-m-d H:i:s'); // Current timestamp

            // Debug: Log the values being inserted
            $debug_data = [
                'staff_id' => $staff_id,
                'expense_type_id' => $expense_type_id,
                'expense_amount' => $expense_amount,
                'CurrencyID' => $currency_id,
                'expense_remark' => $expense_remark,
                'accountID' => $account_id,
                'expense_document' => $expense_document,
                'original_name' => $original_name,
                'amount_type' => $amount_type,
                'time_creation' => $time_creation
            ];
            file_put_contents('debug.log', "Inserting: " . print_r($debug_data, true) . "\n", FILE_APPEND);

            $sql = "INSERT INTO `expense` (`staff_id`, `expense_type_id`, `expense_amount`, `CurrencyID`, `amount_type`, `expense_remark`, `time_creation`, `accountID`, `expense_document`, `original_name`) 
                    VALUES (:staff_id, :expense_type_id, :expense_amount, :CurrencyID, :amount_type, :expense_remark, :time_creation, :accountID, :expense_document, :original_name)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':staff_id', $staff_id);
            $stmt->bindParam(':expense_type_id', $expense_type_id);
            $stmt->bindParam(':expense_amount', $expense_amount);
            $stmt->bindParam(':CurrencyID', $currency_id);
            $stmt->bindParam(':amount_type', $amount_type);
            $stmt->bindParam(':expense_remark', $expense_remark);
            $stmt->bindParam(':time_creation', $time_creation);
            $stmt->bindParam(':accountID', $account_id);
            $stmt->bindParam(':expense_document', $expense_document);
            $stmt->bindParam(':original_name', $original_name);

            // Debug: Log the query with bound values
            file_put_contents('debug.log', "SQL Query: $sql\nBound Values: " . print_r($debug_data, true) . "\n", FILE_APPEND);

            // Execute and check result
            if ($stmt->execute()) {
                $rowCount = $stmt->rowCount();
                file_put_contents('debug.log', "Rows affected: $rowCount\n", FILE_APPEND);
                if ($rowCount > 0) {
                    $pdo->commit();
                    echo "Success";
                } else {
                    $pdo->rollback();
                    echo "No rows inserted";
                }
            } else {
                $pdo->rollback();
                $errorInfo = $stmt->errorInfo();
                file_put_contents('debug.log', "Execute failed with error: " . print_r($errorInfo, true) . "\n", FILE_APPEND);
                echo "Execute failed: " . $errorInfo[2];
            }
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not execute $sql. " . $e->getMessage();
        file_put_contents('debug.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}

// Handle GetExpenseTypes request
elseif (isset($_POST['GetExpenseTypes'])) {
    $selectQuery = $pdo->prepare("SELECT expense_type_id, expense_type FROM expense_type ORDER BY expense_type ASC");
    $selectQuery->execute();
    $GetExpenseTypes = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    echo json_encode($GetExpenseTypes);
}

// Handle uploadExpDocuments request
elseif (isset($_POST['uploadExpDocuments'])) {
    try {
        $image = '';
        if (!empty($_FILES['uploadFile']['name'])) {
            $image = upload_Image($_FILES['uploadFile']['name']);
            if ($image === '') {
                $image = 'Error';
            }
        }
        $pdo->beginTransaction();
        if ($image === 'Error') {
            $pdo->rollback();
            echo "Record not added because of file uploader";
        } else {
            $expense_document = $image;
            $original_name = $_FILES['uploadFile']['name'] ?? '';
            $expense_id = $_POST['expid'] ?? '';

            $sql = "UPDATE `expense` SET expense_document = :expense_document, original_name = :original_name WHERE expense_id = :expense_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':expense_document', $expense_document);
            $stmt->bindParam(':original_name', $original_name);
            $stmt->bindParam(':expense_id', $expense_id);
            $stmt->execute();
            $pdo->commit();
            echo "Success";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not execute $sql. " . $e->getMessage();
    }
}

// Handle DeleteFile request
elseif (isset($_POST['DeleteFile']) && isset($_POST['ID'])) {
    try {
        $pdo->beginTransaction();
        $sql = "SELECT expense_document FROM expense WHERE expense_id = :expense_id";
        $stmt = $pdo->prepare($sql);
        $expense_id = $_POST['ID'];
        $stmt->bindParam(':expense_id', $expense_id);
        $stmt->execute();
        $file = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($file && !empty($file['expense_document'])) {
            $file_path = $file['expense_document'];
            if (file_exists($file_path) && unlink($file_path)) {
                $sql = "UPDATE expense SET expense_document = NULL, original_name = NULL WHERE expense_id = :expense_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':expense_id', $expense_id);
                $stmt->execute();
                $pdo->commit();
                echo "Success";
            } else {
                $pdo->rollback();
                echo "Error: Could not delete file";
            }
        } else {
            $pdo->rollback();
            echo "Error: File not found in database";
        }
    } catch (PDOException $e) {
        $pdo->rollback();
        echo "ERROR: Could not execute $sql. " . $e->getMessage();
    }
}

// File upload function
function upload_Image($companyDocument) {
    $new_image_name = '';
    if (isset($_FILES['uploadFile']) && $_FILES['uploadFile']['size'] <= 10485760) { // 10MB limit
        $extension = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
        $f_name = pathinfo($_FILES['uploadFile']['name'], PATHINFO_FILENAME);
        $ext = ["txt", "pdf", "doc", "docx", "xls", "xlsx", "jpg", "jpeg", "png", "ppt", "zip"];
        if (in_array(strtolower($extension), $ext)) {
            $new_image_name = $f_name . "_" . date("YmdHis") . "." . $extension;
            $new_image_name = md5($new_image_name);
            $new_image_name = 'expense_documents/' . $new_image_name . '.' . $extension;
            if (!is_dir('expense_documents')) {
                mkdir('expense_documents', 0755, true);
            }
            if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $new_image_name)) {
                return $new_image_name;
            }
        }
    }
    return ''; // Return empty string on failure
}

// Close connection
unset($pdo);
?>