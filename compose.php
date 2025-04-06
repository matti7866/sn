<?php
  include 'header.php';
?>
<link href="color_admin_v5.0/admin/template/assets/plugins/tag-it/css/jquery.tagit.css" rel="stylesheet" />
<link href="color_admin_v5.0/admin/template/assets/plugins/summernote/dist/summernote-lite.css" rel="stylesheet" />
<title>Compose Email</title>
<style>
    #ccText:hover{
        cursor: pointer;
    }
    #bccText:hover{
        cursor: pointer;
    }
    table, th, td {
  border: 1px solid black;
}
</style>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
    <div class="mailbox">
        <div class="mailbox-sidebar">
            <div class="mailbox-sidebar-header d-flex justify-content-center">
                <a href="#emailNav" data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
                    <i class="fa fa-cog"></i>
                </a>
                <a href="compose.php" class="btn btn-inverse ps-40px pe-40px btn-sm">
                    Compose
                </a>
            </div>
            <div class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
                <div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
                    <div class="nav-title"><b>FOLDERS</b></div>
                        <ul class="nav nav-inbox">
                                  <li><a href="emailInbox.php"><i class="fa fa-hdd fa-lg fa-fw me-2"></i> Inbox <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
                                <li><a href="sentEmail.php"><i class="fa fa-envelope fa-lg fa-fw me-2"></i> Sent <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
                            
                        </ul>
                    
                    </div>
                </div>
            </div>
            <div class="mailbox-content">
                <div class="mailbox-content-header">
                    <div class="btn-toolbar align-items-center">
                        <div class="btn-group me-2">
                        <form method='post' action='' id="mailForm" enctype="multipart/form-data">
                            <a onclick="sendMail()" class="btn btn-white btn-sm"><i class="fa fa-fw fa-envelope"></i> <span class="hidden-xs">Send</span></a><input style="display:none" type="file" id='files' name="files[]" multiple>
                            <a href="javascript:;" id="attachmentUploader" class="btn btn-white btn-sm"><i class="fa fa-fw fa-paperclip"></i> <span class="hidden-xs">Attach</span></a>
                        </div>
                    <div>
                </div>
                <div class="ms-auto">
                    <a href="email_inbox.html" class="btn btn-white btn-sm"><i class="fa fa-fw fa-times"></i> <span class="hidden-xs">Discard</span></a>
                </div>
            </div>
        </div>
        <div class="mailbox-content-body">
            <div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
            
                <div class="mailbox-to">
                    <label class="control-label">To:</label>
                        <ul id="email-to" class="primary line-mode">
                        </ul>
                        <div class="mailbox-float-link">
                            <a  data-click="add-cc" data-name="Cc" id="ccText" onclick="addCcToUI()" class="me-5px">Cc</a>
                            <a  data-click="add-cc" id="bccText" onclick="addBccToUI()" data-name="Bcc">Bcc</a>
                        </div>
                </div>  
                <div data-id="extra-cc" id="cc"></div>
                    <div class="mailbox-subject">
                    <input type="text" id="email_subject" name="email_subject" class="form-control" placeholder="Subject" />
                </div>
                <div class="mailbox-input">
                    <textarea class="summernote" id="mesgBody" ></textarea>
                </div>

        </div>
    </div>
        <div class="mailbox-content-footer d-flex align-items-center justify-content-end">
            <button type="button" onclick="getAll()" class="btn btn-white ps-40px pe-40px me-5px">Discard</button>
            <button id="sendMail" type="submit" class="btn btn-primary ps-40px pe-40px">Send</button>
        </div>
    </form>
</div>

</div>

</div>

  <?php include 'footer.php'; ?>
  <script src="color_admin_v5.0/admin/template/assets/plugins/jquery-migrate/dist/jquery-migrate.min.js"></script>
  <script src="color_admin_v5.0/admin/template/assets/plugins/tag-it/js/tag-it.min.js"></script>
  <script src="color_admin_v5.0/admin/template/assets/plugins/summernote/dist/summernote-lite.min.js"></script>
  
  <script>
      $(document).ready(function() {
        $('.summernote').summernote({
            
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'hr']],
                ['help', ['help']]
            ],
        });
        $("#email-to").tagit();
        
    });
    function addCcToUI(){
        $('#ccText').hide();
        $('#cc').append(`<div class="mailbox-to">		
        <label class="control-label">Cc:</label>		
        <ul id="email-cc-Cc" class="primary line-mode tagit ui-widget ui-widget-content ui-corner-all">
        </ul>	</div>`);
        $("#email-cc-Cc").tagit();
    }
    function addBccToUI(){
        $('#bccText').hide();
        $('#cc').append(`<div class="mailbox-to">		
        <label class="control-label">Bcc:</label>		
        <ul id="email-bcc-Bcc" class="primary line-mode tagit ui-widget ui-widget-content ui-corner-all">
        </ul>	</div>`);
        $("#email-bcc-Bcc").tagit();
    }
    function sendMail(){
        $('#sendMail').click();
    }
    $('#attachmentUploader').click(function(){ 
        $('#files').trigger('click'); 
    });
    $(document).on('submit', '#mailForm', function(event){
        event.preventDefault();
        var form_data = new FormData();
        // Read selected files
        var totalfiles = document.getElementById('files').files.length;
        for (var index = 0; index < totalfiles; index++) {
            form_data.append("files[]", document.getElementById('files').files[index]);
        }
        //get All emails
        var recipient = [];
        var ccrecipient = [];
        var bccrecipient = [];
        $("ul#email-to li span.tagit-label").each(function() {
            recipient.push($(this).text());
        });
        if(recipient == ''){
         notify('Oops!', 'Recipient is not mentioned','error');
         return;
        }
        form_data.append('Recipient',JSON.stringify(recipient));
        // get all cc
        $("ul#email-cc-Cc li span.tagit-label").each(function() {
            ccrecipient.push($(this).text());
        });
        form_data.append('Ccrecipient',JSON.stringify(ccrecipient));
        // get all Bcc
            $("ul#email-bcc-Bcc li span.tagit-label").each(function() {
                bccrecipient.push($(this).text());
            });
        form_data.append('Bccrecipient',JSON.stringify(bccrecipient));
        var mesgBody =  $('#mesgBody').summernote('code');
        form_data.append('MesgBody',mesgBody);
        var email_subject = $('#email_subject').val();
        if(email_subject == ''){
         notify('Oops!', 'Email must have subject','error');
         return;
        }
        form_data.append('EmailSubject',email_subject);
        // AJAX request
       // console.log(typeof(recipient));
        $.ajax({
            url: 'composeController.php', 
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function () {
                HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Getting Data...</h4>"
                });
            },
            complete: function () {
                HoldOn.close();
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', 'Email sent!','success');
                    location.reload();

                }else{
                    notify('Opp!', 'Something went wrong emails did not send','error');
                }
            }
        });
    });
  </script>
  
 
</body>
</html>