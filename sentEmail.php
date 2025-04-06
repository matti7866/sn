<?php
  include 'header.php';
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div class="mailbox" id="mailbox">
    
</div>
                
<?php include 'footer.php'; ?>
<script>
    $(document).ready(function(){
        getEmailDetail();
    });
    function getEmailDetail(){
        var getEmailDetail = "getEmailDetail";
        $.ajax({
            type: "POST",
            url: "sentEmailController.php",  
            data: {
                getEmailDetail:getEmailDetail,
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
               $('#mailbox').empty();
               $('#mailbox').append(response);
            },
        });
    }
    function deleteMessage(emNum){
        var deleteMessage = "deleteMessage";
        $.ajax({
            type: "POST",
            url: "sentEmailController.php",  
            data: {
                DeleteMessage:deleteMessage,
                EmNum:emNum
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
                    location.reload();
            },
        });
    }
</script>
</body>
</html>