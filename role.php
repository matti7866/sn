<?php
  include 'header.php';
?>
<title>Role</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
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
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="card w3-card-24 " id="todaycard">
            <div class="card-header bg-light">
                <h1 class="text-danger text-center"><b><i>Role</i></b></h1>
            </div>
            <div class="card-body">
                <?php if($insert == 1) { ?>
                <form onsubmit="return false;">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="sr-only" for="inlineFormInputGroup">Role Name</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-user"></i></div>
                                </div>
                                <input type="text" class="form-control" name="role_name" id="role_name" autofocus="autofocus" placeholder="Role Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-dark mb-2" onclick="addRole()"> <i class="fa fa-plus"></i> Save</button>
                        </div>
                    </div>
                </form>
                <?php } ?>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive ">
                            <table id="myTable"  class="table  table-striped table-hover ">
                                <thead class="thead-dark bg-black text-white" style="font-size:14px">
                                    <tr>
                                        <th>S#</th>
                                        <th>Role ID</th>
                                        <th>Role Name</th>
                                          <?php  if($delete == 1) {   ?>
                                        <th>Action</th>
                                         <?php    } ?>
                                    </tr>
                                </thead>
                                <tbody id="RoleReportTbl">
          
                    
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div>
</div>
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getRoles();
  });
  function addRole(){
    var insert ="INSERT";
    var role_name = $('#role_name');
    if(role_name.val() == ""){
        notify('Validation Error!', 'Role is required', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "roleController.php",  
            data: {
                INSERT:insert,
                Role_Name: role_name.val(),
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getRoles();
                    role_name.val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    function getRoles(){
    var select_role = "select_role";
    $.ajax({
        type: "POST",
        url: "roleController.php",  
        data: {
            Select_Role:select_role,
        },
        success: function (response) {  
            var roleRpt = JSON.parse(response);
            $('#RoleReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<roleRpt.length; i++){
                finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize'>"+ roleRpt[i].role_id +"</td>"+
                "<td class='text-capitalize'>"+ roleRpt[i].role_name +"</td>"; 
<?php if($delete == 1) {  ?>
finalTable += "<td class='float-center'><button type='button'0 onclick='Delete(" + 
                roleRpt[i].role_id +  ")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                "</button></td>";
<?php } ?>
                finalTable += "</tr>";
                $('#RoleReportTbl').append(finalTable);
                j +=1;
            }
        },
    });
    }
   // Get the input field
var input = document.getElementById("role_name");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    addRole();
  }
});
function Delete(role_id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this role',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "roleController.php",  
                data: {
                  Delete:Delete,
                  Role_ID:role_id
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', 'Record Successfully Deleted', 'success');
                  getRoles();
                }else{
                  notify('Opps!', response, 'error');
                }
              },
            });
            }
        },
        close: function () {
        }
    }
});
}
</script>
</body>
</html>