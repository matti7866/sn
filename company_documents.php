<?php
  include 'header.php';
?>
<title>Completed Tasks Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
  $sql = "SELECT permission.select,permission.insert,permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Company Documents' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $insert = $records[0]['insert'];
    $delete = $records[0]['delete'];

    if($select == 0 && $insert == 0 ){
    echo "<script>window.location.href='pageNotFound.php'</script>";
    }

?>

<div class="col-xl-12">
    
<h1 class="text-center text-red mb-4">COMPANY DOCUMENTS <span class="fa fa-files-o" ></span> </h1>

                <div class="row">
                    <div class="col-12 ui-sortable">
                        <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                            <div class="panel-heading ui-sortable-handle">
                                
                                <h4 class="panel-title">Completed Tasks</h4>
                                        <div class="panel-heading-btn">
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                                        </div>
                            </div>
                            <div class="panel-body">
                                <?php if($insert == 1){ ?>
                                <div class="row">
                                    <div class="col-12">
                                    <button type="button" class="btn btn-info pull-right mb-2" data-bs-toggle="modal" data-bs-target="#companyDocumentsModel">
                                        <i class="fa fa-plus"></i> Add Company Documents
                                    </button>   
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <?php if($select == 1){ ?>
                                        <!-- html -->
                                        <div id="jstree-drag-and-drop"></div>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
          
                    </div>
                </div>
<!-- Modal -->
<?php if($insert == 1){ ?>
<div class="modal fade" id="companyDocumentsModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="exampleModalLabel">Add Company Documents</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form id="createDirectory" >
            <div class="row g-3 mb-2 align-items-center">
                <div class="col-md-3 text-inverse">
                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-folder"></i> Folder Name:</label>
                </div>
                <div class="col-md-9">
                    <input type="text" class="col-sm-4 form-control controlCounter" id="foler_name" name="folder_name" placeholder="Folder Name" >
                    <input type="text" class="col-sm-4 form-control d-none" id="DID" name="DID"  />
                </div>
            </div>
            
      </form>
      <form method="post" class="d-none" enctype="multipart/form-data" id="addCompanyDocuments">
            <div class="row g-3 mb-2 align-items-center">
                <div class="col-md-3 text-inverse">
                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-file"></i> Document:</label>
                </div>
                <div class="col-md-9">
                <input type="file" class="form-group form-control col-md-4" id="uploadFile" name="uploadFile">
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="createBtnLink" onclick="mkDir()" class="btn btn-dark d-none">Create Folder</button>
        <button type="submit" id="uploader" class="btn btn-primary d-none">Upload</button>
        <button type="button" id="createbtn" onclick="createFolder()" class="btn btn-primary">Create</button>
      </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>

  <?php include 'footer.php'; ?>
  <script>
    var Agree = 0;
    var globalDID = '';
    $(document).on('submit', '#addCompanyDocuments', function(event){
    event.preventDefault();
    var company_documents = $('#uploadFile').val();
    if(company_documents == ''){
        notify('Error!', 'Please upload document ', 'error');
        return;
    }
    if($('#uploadFile').val() != ''){
    if($('#uploadFile')[0].files[0].size > 10485760){
        notify('Error!', 'File size is greater than 10 MB. Make Sure It should be less than 10 MB ', 'error');
        return;
      }
    }
    var  did =  globalDID;
    if(did == ''){
        notify('Error!', 'Please create folder first', 'error');
        return;
    } 
      data = new FormData(this);
      data.append('uploadCompanyFiles','uploadCompanyFiles');
      data.append('Agree',Agree);
      data.append('DID',did);
        $.ajax({
            type: "POST",
            url: "company_documentsController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
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
                var result = JSON.parse(response);
                if(result.msg == "success"){
                    notify('Success!', result.msgDetails, 'success');
                    $("#jstree-drag-and-drop").jstree('destroy');
                    fetchAllDcouments();
                    $('#uploadFile').val('');
                    globalDID = '';
                    Agree = 0;
                }else if(result.msg == "info"){
                    $.confirm({
                            title: 'Alert!',
                            content: result.msgDetails,
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'Yes',
                                    btnClass: 'btn-red',
                                    action: function(){
                                        Agree = 1;
                                        globalDID = '';
                                        $('#addCompanyDocuments').submit();
                                    }
                                },
                                close: function () {
                                }
                            }
                    });
                }else{
                    notify('Error!', result.msgDetails, 'error');
                    return;
                }
            },
        });
    });
    // create Folder
    function createFolder(){
        globalDID = '';
        var createFolder = "createFolder";
        var foler_name =  $('#foler_name');
        if(foler_name.val() == ''){
            notify('Error!', 'Please type folder name', 'error');
            return;
        }
        $.ajax({
            type: "POST",
            url: "company_documentsController.php",  
            data: {
                CreateFolder:createFolder,
                Foler_Name:foler_name.val()
            },
            success: function (response) {  
                 var result = JSON.parse(response);
                if(result.msg == "success"){
                    notify('Success!', 'Directory created Successfully.', 'success');
                    $('#foler_name').val('');
                    $('#companyDocumentsModel').modal('hide');
                    $("#jstree-drag-and-drop").jstree('destroy');
                    fetchAllDcouments();

                }else{
                    notify('Error!', result.msgDetails, 'error');
                    return;
                }
            },
        });

    }

    // mkDir() fn
    function mkDir(){
        $('foler_name').val('');
    }
    function fetchAllDcouments(){
        var getDocuments = "getDocuments";
        $.ajax({
            type: "POST",
            url: "company_documentsController.php",  
            data: {
                GetDocuments:getDocuments,
            },
            success: function (response) {  
                var res = JSON.parse(response);
                showFoldersAndFiles(res);
            },
        });
    }
      function showFoldersAndFiles(data){
        
        $("#jstree-drag-and-drop").jstree({
    "plugins": ["contextmenu", "dnd", "state", "types"],
    "core": {
      "themes": { "responsive": false },
      "check_callback": true,
      "data": data
    },
    "contextmenu":{         
    "items": function($node) {
        var tree = $("#jstree-drag-and-drop").jstree(true);
        if($node.original.isFile == 'false'){
            return {
                    <?php if($insert == 1) { ?> 
                    "Create": {
                        "separator_before": false,
                        "separator_after": true,
                        "label": "Upload File",
                        "action": function (obj) { 
                            uploadFile($node.original.customID);
                        }
                    },       
                    <?php } ?>
                    <?php if($delete == 1) { ?>                 
                    "Remove": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Delete",
                        "action": function (obj) { 
                            deleteFile($node.original.customID, $node.original.isFile,0);
                        }
                    },
                    <?php } ?>
            };
        }else{
            return {
                <?php if($delete == 1) { ?>   
                "Remove": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Delete",
                        "action": function (obj) { 
                            deleteFile($node.original.customID, $node.original.isFile,$node.original.parentCustomID);
                        }
                    },
                    <?php } ?>
                    <?php if($select == 1) { ?>
                    "Download": {
                        "separator_before": false,
                        "separator_after": false,
                        "label": "Download",
                        "action": function (obj) { 
                            downloadFile($node.original.customID, $node.original.parentCustomID);
                        }
                    }
                    <?php } ?>
            }
        }
    }
},
    "types": {
      "default": { "icon": "fa fa-folder text-warning fa-lg" },
      "file": { "icon": "fa fa-file text-warning fa-lg" }
    },
    "state": { "key": "demo2"  }
  });
 }
$(document).ready(function(){
    fetchAllDcouments();
});

  function uploadFile(dirID){
    globalDID = dirID;
    $('#uploadFile').click();
  }
  document.getElementById("uploadFile").onchange = function() {
    $('#uploader').click();
  };
  function deleteFile(customID, isFile, parentCustomID){
        var delete_var = "delete_var";
        $.ajax({
            type: "POST",
            url: "company_documentsController.php",  
            data: {
                DELETE_VAR:delete_var,
                CustomID:customID,
                ParentCustomID:parentCustomID,
                IsFile:isFile
            },
            success: function (response) {  
                var result = JSON.parse(response);
                if(result.msg == "success"){
                    notify('Success!', result.msgDetails , 'success');
                    $("#jstree-drag-and-drop").jstree('destroy');
                    fetchAllDcouments();
                }else{
                    notify('Error!', result.msgDetails, 'error');
                    return;
                }
            },
        });    
  }
  function downloadFile(CustomID,ParentCustomID){
    window.location = "downloadCompanyFiles.php?CustomID="+CustomID +"&ParentCustomID="+ParentCustomID;  
  }

  </script>
</body>
</html>