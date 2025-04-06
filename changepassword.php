<?php
  include 'header.php';
?>
<title>Change Password</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 " >
	<form method="post" id="changePassForm">
		<div id="todaycard" class="card float-center"  >
			<div class="card-header text-center" style="background-color:black; color:white;">
				<h2>Change Password</h2>
			</div>
			<div class="card-body">
				<div class="container text-center">
					<img style="text-align:center;"  id="imgperson1" class="rounded-circle "
					width="150px" height="150px"   alt="myiamge" />
					<p style="color:black; margin-top:5px; text-align:center;">Welcome! <br /><b><span id="staff_name"></span></b></p>
					<hr>
				</div>
				<div class=" float-center">
					<i class="fa fa-fw fa-user offset-md-4"></i><label>Username:</label><input class="form-control col-md-4 offset-md-4 " id="staffName" type="text" disabled><br>
					<i class="fa fa-fw fa-key offset-md-4"></i><label>New Password:</label><input name="p1" id="password" placeholder="Password" class=" form-control col-md-4 offset-md-4 " type="password"  >
					<i class="fa fa-fw fa-key offset-md-4"></i><label>Confirm Password:</label><input name="p2" id="confirmpassword" placeholder="Confirm Password"  min="5" max="7" class=" form-control col-md-4 offset-md-4" type="password"  >
				</div>
			</div>
			<button type="button" name="change" onclick="Save()" value="Change Password" class="btn btn-danger col-md-4 offset-md-4 text-center">Change Password</button>
			<br>
		</div>
	</div>
	</form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script>
	$(document).ready(function(){
		getEmployeeInfo();
	});
	function getEmployeeInfo(){
    var getEmployeeInfo = "getEmployeeInfo";
    $.ajax({
        type: "POST",
        url: "changePasswordController.php",  
        data: {
            GetEmployeeInfo:getEmployeeInfo,
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Loading Data...</h4>"
            });
        },
        complete: function () {

            HoldOn.close();
        },
        success: function (response) {  
            var employeeInfo = JSON.parse(response);
			$('#staff_name').text(employeeInfo[0].staff_name);
            $('#staffName').val(employeeInfo[0].staff_name);
			$("#imgperson1").attr("src", employeeInfo[0].staff_pic);
        },
    });
}
function Save(){
    var changePassword = "changePassword";
	var password = $('#password');
	var confirmpassword = $('#confirmpassword');
	if(password.val() !=confirmpassword.val()){
		notify('Ops!','Password does not match' , 'error');
		return;
	}
	if($.isArray(validatePassword())){
		for(var i=0;i< validatePassword().length; i++){
			notify('Validation error',validatePassword()[i] , 'error');
			
		}
		return;
	}
    $.ajax({
        type: "POST",
        url: "changePasswordController.php",  
        data: {
            ChangePassword:changePassword,
			Password:password.val()
        },
        beforeSend: function () {
            HoldOn.open({
                theme: 'sk-cube-grid',
                message: "<h4>Loading Data...</h4>"
            });
        },
        complete: function () {

            HoldOn.close();
        },
        success: function (response) {  
            if(response == "Success"){
				notify('Success', 'Password successfully changed', 'success');
				$('#password').val('');
				$('#confirmpassword').val('');
			}else{
				notify('Ops!',response , 'error');
			}
        },
    });
}
function validatePassword() {
    var p = document.getElementById('password').value,
        errors = [];
    if (p.length < 4) {
        errors.push("Your password must be at least 4 characters"); 
    }
    if (p.search(/[a-z]/i) < 0) {
        errors.push("Your password must contain at least one letter.");
    }
    if (p.search(/[0-9]/) < 0) {
        errors.push("Your password must contain at least one digit."); 
    }
    if (errors.length > 0) {
        
        return errors;
    }
    return true;
}
</script>
</body>
</html>