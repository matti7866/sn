<?php
  include 'header.php';
?>
<style>
    .nav-tabs .nav-link.active{
        color:red;
    }
</style>
<title>Notification</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
//   include 'connection.php';
//     $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Reminder' ";
//     $stmt = $pdo->prepare($sql);
//     $stmt->bindParam(':role_id', $_SESSION['role_id']);
//     $stmt->execute();
//     $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
//     $select = $records[0]['select'];
//     $update = $records[0]['update'];
//     $delete = $records[0]['delete'];
//     $insert = $records[0]['insert'];
//     if($select == 0){
//     echo "<script>window.location.href='pageNotFound.php'</script>";
//     }
?>
<div class="col-xl-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active">
                <span class="d-sm-none">Tab 1</span>
                <span class="d-sm-block d-none">Set Notification</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link">
                <span class="d-sm-none">Tab 2</span>
                <span class="d-sm-block d-none">Get Notifications</span>
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white p-3">
        <div class="tab-pane fade active show" id="default-tab-1">
            <div class="panel panel-inverse">
                    <div class="panel-heading bg-black text-white">
                        <h4 class="panel-title"><i class="fa fa-bell text-warning"></i> Notification <code> Form <i class="fa fa-arrow-down"></i></code></h4>
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove" data-bs-original-title="" title="" data-tooltip-init="true"><i class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="reminderFrm">
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-file text-red"></i> Notification Subject</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="notification_subject" class="form-control" placeholder="Notification Subject" aria-describedby="passwordHelpInline">
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-barcode text-red"></i> Notification Description</label>
                                </div>
                                <div class="col-md-3">
                                <textarea class="col-md-2 form-control" id="notification_description" placeholder="Enter Notification Description" rows="7"></textarea>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-user text-red"></i> Notify</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-group form-control  js-example-basic-single col-md-4" style="width:100%"  name="employees_id" id="employees_id"></select>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3 offset-1">
                                    <button type="button" onclick="addNotification()" class="btn btn-inverse"><i class="fa fa-save"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        <div class="tab-pane fade" id="default-tab-2">
                <h1 class="text-center">Reminders</h1>
                <div class="col-xl-12 pt-3">
                    <div class="accordion" id="accordion">
                       
                        
                    </div>
                </div>
                <div class="text-center mt-4" id="showMore" >
                    <a href="#" onclick="getReminders()"><i class="fa fa-eye"></i> View More...</a>
                </div>
        </div>
    </div>
   <!-- Update Model  -->
   <div class="modal fade" id="updateModel" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white ">
        <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Update</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
      </div>
      <div class="modal-body">
      <form id="genralUpdForm">
        <input type="hidden"  class="form-control" id="ReminderID" name="ReminderID">
        <div id="ticketSection">
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Reminder Subject:</label>
          <div class="col-sm-9">
            <input type="text" id="updreminder_subject" class="form-control" placeholder="Reminder Subject" aria-describedby="passwordHelpInline">
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Reminder Description :</label>
          <div class="col-sm-9">
            <textarea class="col-md-2 form-control" id="updreminder_description" placeholder="Enter Reminder Description" rows="7"></textarea>
          </div>
        </div>
        <div class="form-group row mb-2">
          <label for="inputPassword" class="col-sm-3 col-form-label">Reminder Date Time:</label>
          <div class="col-sm-9">
            <input type="datetime-local" id="updreminder_datetime" class="form-control" placeholder="Reminder Date Time" aria-describedby="passwordHelpInline">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" onclick="SaveUpdate()">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>
  <?php include 'footer.php'; ?>
  <script>
    $(document).ready(function(){
        //getReminders();
        $('.js-example-basic-single').select2();
        getEmployees();
    });
    function getEmployees(){
    var getEmployees = "getEmployees";
    $.ajax({
        type: "POST",
        url: "notificationController.php",  
        data: {
            GetEmployees:getEmployees,
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
            var employees = JSON.parse(response);
            $('#employees_id').empty();
            $('#employees_id').append("<option value='-1'>--Employee--</option>");
            for(var i=0; i<employees.length; i++){
              $('#employees_id').append("<option value='"+ employees[i].staff_id +"'>"+ 
              employees[i].staff_name +"</option>");
            }
        },
    });
    }
    function addNotification(){
    var insert ="INSERT";
    var notification_subject = $('#notification_subject');
    if(notification_subject.val() == ""){
        notify('Validation Error!', 'Notification subject is required', 'error');
        return;
    }
    var notification_description = $('#notification_description');
    if(notification_description.val() == ""){
        notify('Validation Error!', 'Notification description is required', 'error');
        return;
    }
    var employees_id = $('#employees_id').select2('data');
    employees_id = employees_id[0].id;
        $.ajax({
            type: "POST",
            url: "notificationController.php",  
            data: {
                INSERT:insert,
                Notification_subject: notification_subject.val(),
                Notification_Description:notification_description.val(),
                Employees_ID:employees_id,
            },
            beforeSend: function () {
                HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Saving Data...</h4>"
                });
            },
            complete: function () {
                HoldOn.close();
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    var counter = 0;
    var start = 1;
    var end = start + 9;
    function getReminders(){
        var getReminders = "getReminders";
        counter+=1;
        if(counter !=1){
            start = end;
            end = end + 10;
        }
        $.ajax({
            type: "POST",
            url: "ReminderController.php",  
            data: {
                GetReminders:getReminders,
                Start: start,
                End:end,
            },
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
                var reminders = JSON.parse(response);
                //console.log(reminders);
                var accordion = $('#accordion');
                var circleTextColor = ['text-blue','text-indigo','text-teal','text-info','text-warning','text-danger','text-muted'];
                for(var i=0; i<reminders.length; i++){
                 if(i > circleTextColor.length){
                    circleTextColor.push('text-blue','text-indigo','text-teal','text-info','text-warning','text-danger','text-muted');
                 }
                accordion.append(
                `<div class="accordion-item border-0">
                            <div class="accordion-header" id="heading${reminders[i].reminder_id}"> 
                                
                                <button class="accordion-button  bg-gray-900 text-white px-3 py-10px pointer-cursor collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${reminders[i].reminder_id}" aria-expanded="false">
                                    <i class="fa fa-circle fa-fw ${circleTextColor[i]} me-2 fs-8px"></i> ${reminders[i].reminder_subject}
                                    <span style=" position: fixed; right: 0; width: 150px;"><i class="fa fa-edit text-white fa-2x" onclick="editReminder(${reminders[i].reminder_id})" ></i> <i class="fa fa-trash text-danger fa-2x" onclick="deleteReminder(${reminders[i].reminder_id})" ></i></span>
                                </button>
                            </div>
                            <div id="collapse${reminders[i].reminder_id}" class="accordion-collapse collapse" data-bs-parent="#accordion" style="">
                                <div class="accordion-body bg-gray-800 text-white">
                                    <p class="text-end">${filerDate(reminders[i].reminder_datetime)}</p>
                                    <p style="margin-left:20px">${reminders[i].reminder_description}</p>
                                </div>
                            </div>
                        </div>`);
                }
                // Show More Icon
                var showMore = $('#showMore');
                showMore.hide();
                if(reminders[0]){
                    if(reminders[0].TotalRecord> end){
                        showMore.show();
                    }else{
                        showMore.hide();
                    }
                }
            },
        }); 
    }
  </script>
</body>
</html>