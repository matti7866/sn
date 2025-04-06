<?php
  include 'header.php';
?>
<title>Customer Receipt</title>
<style>
    .list-group-item:hover {
        background-color: #f2f2f2;
    }
    .list-group-item:nth-child(2n) {
        background-color: #f2f2f2;
    }   
    .uploadIcon{
        cursor: pointer;
    }
</style>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-danger"><i class="fa fa-list"></i> Receipt Lists</h1>
                        <div id="reportArea">

                        </div>
                        <form class="col-md-6 form-group" style="display:none"  method="post" enctype="multipart/form-data" id="upload" >
                            <input type="file" name="uploader" id="uploader" />
                            <input type="text" name="receiptID" id="receiptID" />
                            <button type="submit" id="submitUploadForm" >Call</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
$(document).ready(function(){
    getReport();
});
    function getReport(){
    var urlFirstParam = location.search.split('&')[0];
    var id = urlFirstParam.split('=')[1];
    var curID = location.search.split('&curID=')[1];
    var GetReport = "GetReport";
      $.ajax({
          type: "POST",
          url: "receiptReportController.php",  
          data: {
            GetReport:GetReport,
            ID:id,
            CurID:curID
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#reportArea').empty();
            if(report.length == 0){
                $('#reportArea').append('<h1 class="text-center">No Data Found</h1>')

            }else{
                var finalTable = '';
                finalTable +="<ul class='list-group'>";
                for(var i=0; i<report.length;i++){
                    finalTable +="<li class='list-group-item d-flex justify-content-between align-items-center'>"+
                    "<a href='receiptDetails.php?rcptID="+ report[i].invoiceID +"' style='font-size:14px'><b> Receipt#: "+
                    report[i].invoiceNumber + "</b></a><span style='font-size:14px'><b>Date: "+ report[i].formatedDate
                    +"</b></span><span class='badge badge-primary badge-pill'>";
                    if(report[i].documentName == null){
                        finalTable +="<i onclick='openUploader("+report[i].invoiceID+")' class='fa fa-upload uploadIcon "+
                        " text-info fa-2x' ></i>";
                    }else{
                        finalTable +="<a href='downloadReceipt.php?id=" + report[i].invoiceID  +"'><i class='fa fa-download "+
                        "text-dark fa-2x' aria-hidden='true'></i></a>&nbsp;<i onclick='deleteFile("+report[i].invoiceID+")' "+
                        " class='fa fa-trash text-danger fa-2x uploadIcon' aria-hidden='true'></i>"
                    }
                    finalTable +="</span></li>";
                }
                finalTable +="</ul>";
                $('#reportArea').append(finalTable);
            }

          },
      });
    }
    function openUploader(id){
          $('#receiptID').val(id);
          $('#uploader').click();
    }
    document.getElementById("uploader").onchange = function(event) {
      $('#submitUploadForm').click();
    };
    $(document).on('submit', '#upload', function(event){
      event.preventDefault();
      var receiptID = $('#receiptID');
      var uploader = $('#uploader').val();
      if(receiptID.val() == "" || receiptID.val() <1 || receiptID.val() =="undefined" || receiptID.val() == null ){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#uploader').val() == ''){
        notify('Validation Error!', 'File can not be empty! Cant upload somethig went wrong! try again.', 'error');
        return;
      }
      if($('#uploader').val() != ''){
        if($('#uploader')[0].files[0].size > 2097152){
          notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
          return;
        }
      }
      data = new FormData(this);
      data.append('UploadReceipt','UploadReceipt');
        $.ajax({
            type: "POST",
            url: "receiptReportController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getReport();
                    receiptID.val('');
                    $('#uploader').val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    function deleteFile(id){
        var DeleteFile = "DeleteFile";
        $.confirm({
            title: 'Delete!',
            content: 'Do you want to delete this file',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function(){
                    $.ajax({
                        type: "POST",
                        url: "receiptReportController.php",  
                        data: {
                            DeleteFile:DeleteFile,
                            ID:id,
                        },
                        success: function (response) {  
                        if(response == 'Success'){
                            notify('Success!', response, 'success');
                            getReport();
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
