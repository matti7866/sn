<?php
  include 'header.php';
?>
<style>
    .nav-tabs .nav-link.active{
        color:red;
    },
    
</style>
<title>Reminder</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'pending Tasks' ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':role_id', $_SESSION['role_id']);
    $stmt->execute();
    $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $select = $records[0]['select'];
    $update = $records[0]['update'];
    $delete = $records[0]['delete'];
    $insert = $records[0]['insert'];
?>
<div class="col-xl-12">
    <ul class="nav nav-tabs">
        <?php if($insert == 1) { ?>
        <li class="nav-item">
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link <?php if($insert== 1) {echo 'active';} ?>  ">
                <span class="d-sm-none">Tab 1</span>
                <span class="d-sm-block d-none">Add Tasks</span>
            </a>
        </li>
        <?php } ?>
        <li class="nav-item">
            <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link <?php if($insert== 0 && $select == 1) {echo 'active';} ?>">
                <span class="d-sm-none">Tab 2</span>
                <span class="d-sm-block d-none">Task Report</span>
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white p-3">
        <?php if($insert == 1) { ?>
        <div class="tab-pane fade <?php if($insert== 1 ) {echo 'active show';} ?>" id="default-tab-1">
            <div class="panel panel-inverse">
                    <div class="panel-heading bg-black text-white">
                        <h4 class="panel-title"><i class="fa fa-info"></i> Task <code> Form <i class="fa fa-arrow-down"></i></code></h4>
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
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-file text-red"></i> Task title</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="task_title" class="form-control" placeholder="Task Title" aria-describedby="passwordHelpInline">
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-barcode text-red"></i> Task Description</label>
                                </div>
                                <div class="col-md-3">
                                <textarea class="col-md-2 form-control" id="task_description" placeholder="Enter Task Description" rows="7"></textarea>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-barcode text-red"></i> Assing Task To</label>
                                </div>
                                <div class="col-md-3">
                                <select class="form-control  js-example-basic-single" style="width:100%" name="employee_id" id="employee_id"></select>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-calendar text-red"></i> Task Date </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group date" id="task_date" data-date-format="dd-mm-yyyy" >
                                        <input type="text" id="actual_date" class="form-control"  placeholder="Task date">
                                        <span class="input-group-text input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-1">
                                    <label for="inputPassword6" class="col-form-label"><i class="fa fa-spinner text-red"></i>  Status</label>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" id="status">
                                        <option value="1">Pending</option>
                                        <option value="0">Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3 offset-1">
                                    <button type="button" onclick="addTask()" class="btn btn-inverse"><i class="fa fa-save"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
        <?php } ?>
        <?php if($select == 1) { ?>
        <div class="tab-pane  <?php if($insert== 0 && $select == 1 ) {echo 'active show';} else {echo '';} ?>" id="default-tab-2">
                <h1 class="text-center text-red mb-4"><span class="fa fa-spinner" ></span> Pending Tasks</h1>
                <div class="row">
                    <div class="col-12 ui-sortable">
                        <div class="panel panel-inverse" data-sortable-id="table-basic-1">
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title">Pending Tasks</h4>
                                        <div class="panel-heading-btn">
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand" data-tooltip-init="true"><i class="fa fa-expand"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                                            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                                        </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table mb-0  table-striped table-hover ">
                                        <thead>
                                            <tr class="bg-dark text-white" style="font-size:13px; font-weight: 600; line-height: 35px;min-height: 35px;height: 35px;" >
                                                <th>#</th>
                                                <th >Title</th>
                                                <th>Description</th>
                                                <th>Assigned To</th>
                                                <th>Due Date</th>
                                                <th>Assigned By</th>
                                                <th>Status</th>
                                                <th>Due date/Expiry date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tasksTable" style="font-size:12px; font-weight: 600;">
                                            
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <?php } ?>
    </div>
  <?php include 'footer.php'; ?>
  <script>
    $(document).ready(function(){
        getPendingTasks();
        getEmployee();
        $('.js-example-basic-single').select2();
       
    });
    $("#task_date").datepicker({
        todayHighlight: true,
        autoclose: true,
        format:'dd-mm-yyyy'
    });
    function addTask(){
    var insert ="INSERT";
    var task_title = $('#task_title');
    if(task_title.val() == ""){
        notify('Validation Error!', 'Task title is required', 'error');
        return;
    }
    var task_description = $('#task_description');
    if(task_description.val() == ""){
        notify('Validation Error!', 'Task description is required', 'error');
        return;
    }
    var task_date = $('#actual_date');
    if(task_date.val() == ""){
        notify('Validation Error!', 'Task date is required', 'error');
        return;
    }
    var status = $('#status');
    if(status.val() == ""){
        notify('Validation Error!', 'Status is required', 'error');
        return;
    }
  
    var assignee = $('#employee_id');
    if(assignee.val() == -1){
        notify('Validation Error!', 'Employee name is required', 'error');
        return;
    }


        $.ajax({
            type: "POST",
            url: "pendingTaskController.php",  
            data: {
                INSERT:insert,
                Task_Title: task_title.val(),
                Task_Description:task_description.val(),
                Task_Date:task_date.val(),
                Status:status.val(),
                Assignee:assignee.val()
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
    
   
    function getPendingTasks(){
        var getPendingTasks = "getPendingTasks";
        $.ajax({
            type: "POST",
            url: "pendingTaskController.php",  
            data: {
                GetPendingTasks:getPendingTasks
            },
            success: function (response) {
                var tasks = JSON.parse(response);
                var tasksTable = $('#tasksTable');
                tasksTable.empty();
                if(tasks.length !=0){
                    var dateStatus = '';
                    for(var i = 0; i< tasks.length; i++){
                        tasks[i].dateStatus == 'day(s) remaining' ?  dateStatus = `<button type="button" class="btn btn-blue"><span class="fa fa-clock-o"></span> ${tasks[i].daysRemining} ${tasks[i].dateStatus}</button>`:
                        dateStatus = `<button type="button" class="btn btn-red"><span class="fa fa-clock-o"></span> ${tasks[i].daysRemining} ${tasks[i].dateStatus}</span>`;
                        tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td >${i+1}</td><td>
                        ${tasks[i].task_name}</td><td>${tasks[i].task_description}</td>
                        <td>${tasks[i].AssigedTo}</td><td>${tasks[i].task_date}</td><td>${tasks[i].AssignedBy}</td>
                        <td><button type="button" class="btn btn-warning"><span class="fa fa-spinner"></span> ${tasks[i].status}</button></td><td>${dateStatus}</td><td><button type="button"
                        onclick="taskCompleted(${tasks[i].task_id},' ${tasks[i].task_name} ')" class="btn btn-danger"><span class="fa fa-check"></span> Completed</button></td></tr>`);
                }
                }else{
                    tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td></td><td></td><td></td><td></td>
                    <td></td><td>No Pending Tasks</td><td></td><td></td><td></td></tr>`);
                }
                
            },
        }); 
    }
   
   
    function getEmployee(){
    var select_employee = "select_employee";
    $.ajax({
        type: "POST",
        url: "pendingTaskController.php",  
        data: {
            Select_Employee:select_employee,
        },
        success: function (response) {  
            var employee = JSON.parse(response);
                $('#employee_id').empty();
                $('#employee_id').append("<option value='-1'>-- Select Employee --</option>");
                for(var i=0; i<employee.length; i++){
                $('#employee_id').append("<option value='"+ employee[i].staff_id +"'>"+ 
                employee[i].staff_name +"</option>");
            }
        },
    });
    }
    function taskCompleted(id,task_title){
    var completeTask = "completeTask";
  $.confirm({
    title: 'Complete!',
    content: 'Are you sure, task with title ' + task_title + ' completed?',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "pendingTaskController.php",  
                data: {
                  CompleteTask:completeTask,
                  ID:id
                },
                success: function (response) {  
                  if(response == "Success"){
                    notify('Success!', "Task completed successfully", 'success');
                    getPendingTasks();
                  }else{
                    notify('Error!', response, 'error');
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