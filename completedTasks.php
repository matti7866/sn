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
?>
<div class="col-xl-12">
    
<h1 class="text-center text-red mb-4"><span class="fa fa-check" ></span> Completed Tasks Report</h1>
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
                                                <th>Completed By</th>
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
  <?php include 'footer.php'; ?>
  <script>
    $(document).ready(function(){
        getCompletedTasks();
    });
    
    
    
   
    function getCompletedTasks(){
        var getCompletedTasks = "getCompletedTasks";
        $.ajax({
            type: "POST",
            url: "completedTasksController.php",  
            data: {
                GetCompletedTasks:getCompletedTasks
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
                var tasks = JSON.parse(response);
                var tasksTable = $('#tasksTable');
                tasksTable.empty();
                if(tasks.length !=0){
                    for(var i = 0; i< tasks.length; i++){
                        tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td >${i+1}</td><td>
                        ${tasks[i].task_name}</td><td>${tasks[i].task_description}</td>
                        <td>${tasks[i].AssigedTo}</td><td>${tasks[i].task_date}</td><td>${tasks[i].AssignedBy}</td>
                        <td><button type="button" class="btn btn-blue"><span class="fa fa-check"></span> ${tasks[i].status}</button></td><td>${tasks[i].completedBy}</td><td><button type="button"
                        onclick="taskUndo(${tasks[i].task_id},' ${tasks[i].task_name} ')" class="btn btn-danger"><span class="fa fa-undo"></span> Undo</button></td></tr>`);
                }
                }else{
                    tasksTable.append(`<tr style="line-height: 35px;min-height: 35px;height: 35px;"><td></td><td></td><td></td><td></td>
                    <td></td><td>No Pending Tasks</td><td></td><td></td><td></td></tr>`);
                }
                
            },
        }); 
    }
   
    function taskUndo(id,task_title){
    var taskUndo = "taskUndo";
  $.confirm({
    title: 'Complete!',
    content: 'Are you sure, you want to undo task with title ' + task_title + '?',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "completedTasksController.php",  
                data: {
                    TaskUndo:taskUndo,
                  ID:id
                },
                beforeSend: function () {
                    HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Updating Data...</h4>"
                    });
                },
                complete: function () {
                  HoldOn.close();
                },
                success: function (response) {  
                  if(response == "Success"){
                    notify('Success!', "Task undo successfully", 'success');
                    getCompletedTasks();
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