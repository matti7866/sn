<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Expenses' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    if($select == 0){
    echo "<script>window.location.href='pageNotFound.php'</script>";
    }
    if(isset($_POST['Select_employee'])){
            $selectQuery = $pdo->prepare("SELECT staff_id,staff_name FROM staff ORDER BY staff_name ASC");
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $employee = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($employee);
    }else if(isset($_POST['Select_ExpType'])){
        $selectQuery = $pdo->prepare("SELECT * FROM expense_type ORDER BY expense_type ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $expense_type = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($expense_type);
    }else if(isset($_POST['SearchExpense'])){
        if($_POST['SearchTerm'] == 'DateAndEmpWise'){
            $selectQuery = $pdo->prepare("SELECT `expense_id`, staff_name, expense_type.expense_type, `expense_amount`,
            currencyName, `expense_remark`, `time_creation`,account_Name,expense_document,original_name FROM `expense` 
            INNER JOIN staff ON staff.staff_id = expense.staff_id INNER JOIN expense_type ON expense_type.expense_type_id =
            expense.expense_type_id INNER JOIN accounts ON accounts.account_ID = expense.accountID INNER JOIN currency ON
            currency.currencyID = expense.CurrencyID WHERE expense.staff_id = :employee_id AND DATE(time_creation) BETWEEN
            :fromdate AND :todate ORDER BY expense_id DESC");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':employee_id', $_POST['Employee_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT `expense_id`, staff_name, expense_type.expense_type, `expense_amount`,
            currencyName,`expense_remark`, `time_creation`,account_Name,expense_document,original_name  FROM `expense`
            INNER JOIN staff ON staff.staff_id = expense.staff_id INNER JOIN expense_type ON expense_type.expense_type_id
            = expense.expense_type_id INNER JOIN accounts ON accounts.account_ID = expense.accountID INNER JOIN currency ON
            currency.currencyID = expense.CurrencyID WHERE DATE(time_creation) BETWEEN :fromdate AND :todate ORDER BY expense_id
            DESC");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'EmpWise'){
            $selectQuery = $pdo->prepare("SELECT `expense_id`, staff_name, expense_type.expense_type, `expense_amount`,
            currencyName,`expense_remark`, `time_creation`,account_Name,expense_document,original_name  FROM `expense` 
            INNER JOIN staff ON staff.staff_id = expense.staff_id INNER JOIN expense_type ON expense_type.expense_type_id
            = expense.expense_type_id INNER JOIN accounts ON accounts.account_ID = expense.accountID INNER JOIN currency ON
            currency.currencyID = expense.CurrencyID WHERE expense.staff_id = :employee_id ORDER BY expense_id DESC ");
            $selectQuery->bindParam(':employee_id', $_POST['Employee_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $expense = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($expense);
    }else if(isset($_POST['CurrencyTypes'])){
        $selectQuery = $pdo->prepare("SELECT currencyID,currencyName FROM currency ORDER BY currencyName ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $currencies = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($currencies);
    }else if(isset($_POST['GetTotal'])){
        if($_POST['SearchTerm'] == 'DateAndEmpWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(expense_amount) AS amount,currency.currencyName FROM `expense`
            INNER JOIN currency ON currency.currencyID = expense.CurrencyID WHERE expense.staff_id = :staff_id AND 
            DATE(time_creation) BETWEEN :fromdate AND :todate GROUP BY currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':staff_id', $_POST['Employee_ID']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(expense_amount) AS amount,currency.currencyName FROM `expense`
            INNER JOIN currency ON currency.currencyID = expense.CurrencyID WHERE DATE(time_creation) BETWEEN :fromdate AND 
            :todate GROUP BY currency.currencyName) AS baseTable WHERE amount !=0 ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'EmpWise'){
            $selectQuery = $pdo->prepare("SELECT * FROM (SELECT SUM(expense_amount) AS amount,currency.currencyName FROM `expense`
            INNER JOIN currency ON currency.currencyID = expense.CurrencyID WHERE expense.staff_id = :staff_id GROUP BY
            currency.currencyName) AS baseTable WHERE amount !=0;");
            $selectQuery->bindParam(':staff_id', $_POST['Employee_ID']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $total = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($total);
    }else if(isset($_POST['Delete'])){
        try{
                  if($delete == 1){
                        $sql = "DELETE FROM expense WHERE expense_id = :expense_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':expense_id', $_POST['Expense_ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "NoPermission";
                }
               
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetUpdExpense'])){
            $selectQuery = $pdo->prepare("SELECT * FROM expense WHERE expense_id=:expense_id");
            $selectQuery->bindParam(':expense_id', $_POST['Expense_ID']);
            $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['SaveUpdateExpense'])){
        try{
            if($update == 1){
                         $sql = "UPDATE `expense` SET expense_type_id =:expense_type_id, expense_amount =:expense_amount,
                         CurrencyID=:CurrencyID, expense_remark =:expense_remark,staff_id=:staff_id, accountID =:accountID WHERE expense_id =
                         :expense_id  ";
                         $stmt = $pdo->prepare($sql);
                         // bind parameters to statement
                         $stmt->bindParam(':expense_type_id', $_POST['Expense_type']);
                         $stmt->bindParam(':expense_amount', $_POST['Amount']);
                         $stmt->bindParam(':CurrencyID', $_POST['Currency_Type']);
                         $stmt->bindParam(':expense_remark', $_POST['Remarks']);
                         $stmt->bindParam(':staff_id', $_SESSION['user_id']);
                         $stmt->bindParam(':accountID', $_POST['Updaccount_ID']);
                         $stmt->bindParam(':expense_id', $_POST['ExpenseID']);
                         $stmt->execute();
                echo "Success";
            }else{
                echo "NoPermission";
            }
               
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    
    // Close connection
    unset($pdo); 
?>