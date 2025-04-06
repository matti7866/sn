<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
    header('location:login.php');
    }
    include 'connection.php';
    $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Role' ";
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
if(isset($_POST['INSERT'])){
        try{
            if($insert == 1) {
                  // create prepared statement
            $sql = "INSERT INTO roles (role_name) 
            VALUES (:role_name)";
            $stmt = $pdo->prepare($sql);
            // bind parameters to statement
            $stmt->bindParam(':role_name', $_POST['Role_Name']);
            // execute the prepared statement
            $stmt->execute();
            echo "Success";
            }else{
                     echo "<script>window.location.href='pageNotFound.php'</script>";
             }

        }catch(PDOException $e){
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }else if(isset($_POST['Select_Role'])){
        $selectQuery = $pdo->prepare("SELECT * FROM roles ORDER BY role_name ASC");
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $roles = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // encoding array to json format
        echo json_encode($roles);
    }else if(isset($_POST['Delete'])){
        try{        
                if($delete == 1) {
                $sql = "DELETE FROM roles WHERE role_id = :role_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':role_id', $_POST['Role_ID']);
                $stmt->execute();
            echo "Success";
            }else{
                    echo "<script>window.location.href='pageNotFound.php'</script>";
            }
        }catch(PDOException $e){
            $pdo->rollback();
            echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
        }
    }
    // Close connection
    unset($pdo); 
?>
