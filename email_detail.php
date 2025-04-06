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
        var url = window.location.href;
        var splitId = url.split("=");
        var id = splitId[1];
        var getEmailDetail = "getEmailDetail";
        $.ajax({
            type: "POST",
            url: "email_detailController.php",  
            data: {
                GetEmailDetail:getEmailDetail,
                ID:id
            },
            success: function (response) { 
               $('#mailbox').empty();
               $('#mailbox').append(response);
            },
        });
    }
</script>
</body>
</html>