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
				<h2 id="notificationSubject"></h2>
			</div>
			<div class="card-body">
                <p><b>Notification Description</b></p>
				<div class="float-start" id="DescriptionArea" style="max-height:400px; overflow-y:auto;margin-left:10px">
	
					
				</div>
                <br/>
                <hr/>
				<div class=" float-start" id="notificationTime">
					
				</div>
			</div>
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
        var url = window.location.href;
        var splitId = url.split("=");
        var id = splitId[1];
        var getNotificationDetail = "getNotificationDetail";
    $.ajax({
        type: "POST",
        url: "getNotificationDetailController.php",  
        data: {
            GetNotificationDetail:getNotificationDetail,
            ID:id
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
            var $GetNotificationInfo = JSON.parse(response);
			$('#notificationSubject').text('Notification Subject: ' + $GetNotificationInfo[0].notification_subject);
            $('#DescriptionArea').text($GetNotificationInfo[0].notification_description);
            $('#notificationTime').text($GetNotificationInfo[0].datetime);
        },
    });
}
</script>
</body>
</html>