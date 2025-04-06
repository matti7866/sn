<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Salary' ";
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
    if(isset($_POST['AddSalary'])){
        try{
            if($insert == 1){
                $sql = "INSERT INTO `salaries`(`employee_id`, `salary_amount`, `paid_by`,`paymentType`) VALUES(:employee_id
                ,:salary_amount,:paid_by,:paymentType)";
                // create prepared statement
                $stmt = $pdo->prepare($sql);
                // bind parameters to statement
                $stmt->bindParam(':employee_id', $_POST['Addemployee_ID']);
                $stmt->bindParam(':salary_amount', $_POST['Salary_Amount']);
                $stmt->bindParam(':paid_by', $_SESSION['user_id']);
                $stmt->bindParam(':paymentType', $_POST['Addaccount_ID']);
                // execute the prepared statement
                $stmt->execute();
                echo "Success";
            }else{
                echo "<script>window.location.href='pageNotFound.php'</script>";
            } 
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetSalaryReport'])){
        if($_POST['SearchTerm'] == 'DateAndEmpWise'){
            $selectQuery = $pdo->prepare("SELECT `salary_id`, pt.staff_name AS paidToEmployee, `salary_amount`, `datetime`
            ,pb.staff_name AS paidbyEmployee,account_Name FROM `salaries` INNER JOIN staff AS pt ON pt.staff_id = salaries.employee_id 
            INNER JOIN  staff AS pb ON salaries.paid_by = pb.staff_id INNER JOIN accounts ON accounts.account_ID = salaries.paymentType WHERE DATE(datetime) BETWEEN :fromdate AND :todate 
            AND salaries.employee_id = :employee_id ORDER BY datetime DESC  ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
            $selectQuery->bindParam(':employee_id', $_POST['Searchemployee_id']);
        }else if($_POST['SearchTerm'] == 'DateWise'){
            $selectQuery = $pdo->prepare("SELECT `salary_id`, pt.staff_name AS paidToEmployee, `salary_amount`, `datetime`,
            pb.staff_name AS paidbyEmployee,account_Name FROM `salaries` INNER JOIN staff AS pt ON pt.staff_id = salaries.employee_id 
            INNER JOIN  staff AS pb ON salaries.paid_by = pb.staff_id INNER JOIN accounts ON accounts.account_ID = salaries.paymentType WHERE DATE(datetime) BETWEEN :fromdate AND :todate 
            ORDER BY datetime DESC ");
            $selectQuery->bindParam(':fromdate', $_POST['Fromdate']);
            $selectQuery->bindParam(':todate', $_POST['Todate']);
        }else if($_POST['SearchTerm'] == 'EmpWise'){
            $selectQuery = $pdo->prepare("SELECT `salary_id`, pt.staff_name AS paidToEmployee, `salary_amount`, `datetime`,
            pb.staff_name AS paidbyEmployee,account_Name FROM `salaries` INNER JOIN staff AS pt ON pt.staff_id = salaries.employee_id 
            INNER JOIN  staff AS pb ON salaries.paid_by = pb.staff_id INNER JOIN accounts ON accounts.account_ID = salaries.paymentType WHERE salaries.employee_id = :employee_id ORDER BY 
            datetime DESC");
            $selectQuery->bindParam(':employee_id', $_POST['Searchemployee_id']);
        }
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $visa = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($visa);
    }else if(isset($_POST['Select_Employee'])){
        $selectQuery = $pdo->prepare("SELECT * FROM staff ORDER BY staff_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $employee = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($employee);
    }else if(isset($_POST['Delete'])){
        try{
                if($delete == 1){
                        $sql = "DELETE FROM salaries WHERE salary_id = :salary_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':salary_id', $_POST['ID']);
                        $stmt->execute();
                        echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetDataForUpdate'])){
        $selectQuery = $pdo->prepare("SELECT * FROM salaries WHERE salary_id = :id");
        $selectQuery->bindParam(':id', $_POST['ID']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $data = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($data);
    }else if(isset($_POST['UpdSalary'])){
        try{
                if($update == 1){
                    $sql = "UPDATE `salaries` SET employee_id = :employee_id,salary_amount=:salary_amount,paid_by= :paid_by
                    ,paymentType = :paymentType WHERE salary_id = :salary_id";
                    // create prepared statement
                    $stmt = $pdo->prepare($sql);
                    // bind parameters to statement
                    $stmt->bindParam(':employee_id', $_POST['Updemployee_id']);
                    $stmt->bindParam(':salary_amount', $_POST['Updsalary_Amount']);
                    $stmt->bindParam(':paid_by', $_SESSION['user_id']);
                    $stmt->bindParam(':paymentType', $_POST['Updaccount_ID']);
                    $stmt->bindParam(':salary_id', $_POST['SalaryID']);
                    // execute the prepared statement
                    $stmt->execute();
                    echo "Success";
                }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
                }
        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>