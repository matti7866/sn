<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Permission' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
    if(isset($_POST['GetRole'])){
        $selectQuery = $pdo->prepare("SELECT * FROM roles ORDER BY role_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $roles = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($roles);
    }else if(isset($_POST['Insert_Permission'])){
        try{
                if($insert == 1) {
                       // First of all, let's begin a transaction
                $pdo->beginTransaction();
                    $sql = "SELECT DISTINCT role_id FROM permission WHERE role_id = :roleID LIMIT 1";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':roleID', $_POST['Employee_Role']);
                    $stmt->execute();
                    $RoleID = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    if($RoleID){
                        $RoleID = $RoleID[0]['role_id'];
                        $sql = "DELETE FROM permission WHERE role_id =:roleID";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':roleID', $_POST['Employee_Role']);
                        $stmt->execute();
                        for ($x = 0; $x < count($_POST['FinalArr']); $x++) {
                            $sql = "INSERT INTO permission (`role_id`, `page_name`, `select`, `insert`, `update`, `delete`)
                             VALUES(:role_id, :page_name, :select,:insert,:update,:delete)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':role_id', $_POST['Employee_Role']);
                            $stmt->bindParam(':page_name', $_POST['FinalArr'][$x]);
                            $stmt->bindParam(':select', $_POST['FinalArr'][$x+1]);
                            $stmt->bindParam(':insert', $_POST['FinalArr'][$x+2]);
                            $stmt->bindParam(':update', $_POST['FinalArr'][$x+3]);
                            $stmt->bindParam(':delete', $_POST['FinalArr'][$x+4]);
                            $stmt->execute();
                            $x +=4;
                        }
                    }else{
                        for ($x = 0; $x < count($_POST['FinalArr']); $x++) {
                            $sql = "INSERT INTO permission (`role_id`, `page_name`, `select`, `insert`, `update`, `delete`)
                             VALUES(:role_id, :page_name, :select,:insert,:update,:delete)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':role_id', $_POST['Employee_Role']);
                            $stmt->bindParam(':page_name', $_POST['FinalArr'][$x]);
                            $stmt->bindParam(':select', $_POST['FinalArr'][$x+1]);
                            $stmt->bindParam(':insert', $_POST['FinalArr'][$x+2]);
                            $stmt->bindParam(':update', $_POST['FinalArr'][$x+3]);
                            $stmt->bindParam(':delete', $_POST['FinalArr'][$x+4]);
                            $stmt->execute();
                            $x +=4;
                        }
                    }
                $pdo->commit(); 
         echo "Success";

                }else{
                       echo "<script>window.location.href='pageNotFound.php'</script>";
                  }
               
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['GetPermissions'])){
        $selectQuery = $pdo->prepare("SELECT permission.select, permission.insert,permission.update,permission.delete 
        FROM permission WHERE role_id =:role_id ORDER BY permission_id ASC");
        $selectQuery->bindParam(':role_id', $_POST['Employee_Role']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $permissions = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($permissions);
    }
    // Close connection
    unset($pdo); 
?>