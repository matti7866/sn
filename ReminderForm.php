<?php
include 'header.php';
?>
<style>
    .nav-tabs .nav-link.active {
        color: red;
    }
</style>
<title>Reminder</title>
<?php
include 'nav.php';
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select, permission.update, permission.delete, permission.insert 
        FROM `permission` 
        WHERE role_id = :role_id AND page_name = 'Reminder'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if ($select == 0) {
    echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<div class="col-xl-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active">
                <span class="d-sm-none">Tab 1</span>
                <span class="d-sm-block d-none">Set Reminder</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link">
                <span class="d-sm-none">Tab 2</span>
                <span class="d-sm-block d-none">Get Reminder</span>
            </a>
        </li>
    </ul>
    <div class="tab-content bg-white p-3">
        <div class="tab-pane fade active show" id="default-tab-1">
            <div class="panel panel-inverse">
                <div class="panel-heading bg-black text-white">
                    <h4 class="panel-title"><i class="fa fa-info"></i> Reminder <code> Form <i class="fa fa-arrow-down"></i></code></h4>
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="reminderFrm">
                        <div class="row g-3 mb-3 align-items-center">
                            <div class="col-md-1">
                                <label for="reminder_subject" class="col-form-label"><i class="fa fa-file text-red"></i> Reminder Subject</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" id="reminder_subject" class="form-control" placeholder="Reminder Subject" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row g-3 mb-3 align-items-center">
                            <div class="col-md-1">
                                <label for="reminder_description" class="col-form-label"><i class="fa fa-barcode text-red"></i> Reminder Description</label>
                            </div>
                            <div class="col-md-3">
                                <textarea class="form-control" id="reminder_description" placeholder="Enter Reminder Description" rows="7"></textarea>
                            </div>
                        </div>
                        <div class="row g-3 mb-3 align-items-center">
                            <div class="col-md-1">
                                <label for="reminder_datetime" class="col-form-label"><i class="fa fa-whatsapp text-red"></i> Reminder Date Time</label>
                            </div>
                            <div class="col-md-3">
                                <input type="datetime-local" id="reminder_datetime" class="form-control" placeholder="Reminder Date Time" aria-describedby="passwordHelpInline">
                            </div>
                        </div>
                        <div class="row g-3 align-items-center">
                            <div class="col-md-3 offset-1">
                                <button type="button" onclick="addReminder()" class="btn btn-inverse"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="default-tab-2">
            <h1 class="text-center">Reminders</h1>
            <div class="col-xl-12 pt-3">
                <div class="accordion" id="accordion"></div>
            </div>
            <div class="text-center mt-4" id="showMore">
                <a href="#" onclick="getReminders()"><i class="fa fa-eye"></i> View More...</a>
            </div>
        </div>
    </div>
    <!-- Update Modal -->
    <div class="modal fade" id="updateModel" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title font-weight-bold" id="exampleModalLabel">Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form id="genralUpdForm">
                        <input type="hidden" class="form-control" id="ReminderID" name="ReminderID">
                        <div id="ticketSection">
                            <div class="form-group row mb-2">
                                <label for="updreminder_subject" class="col-sm-3 col-form-label">Reminder Subject:</label>
                                <div class="col-sm-9">
                                    <input type="text" id="updreminder_subject" class="form-control" placeholder="Reminder Subject" aria-describedby="passwordHelpInline">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="updreminder_description" class="col-sm-3 col-form-label">Reminder Description:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="updreminder_description" placeholder="Enter Reminder Description" rows="7"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="updreminder_datetime" class="col-sm-3 col-form-label">Reminder Date Time:</label>
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
    </div>
    <?php include 'footer.php'; ?>
    <script>
        $(document).ready(function() {
            getReminders();
        });

        function addReminder() {
            var insert = "INSERT";
            var reminder_subject = $('#reminder_subject');
            var reminder_description = $('#reminder_description');
            var reminder_datetime = $('#reminder_datetime');

            if (reminder_subject.val() == "") {
                notify('Validation Error!', 'Reminder subject is required', 'error');
                return;
            }
            if (reminder_description.val() == "") {
                notify('Validation Error!', 'Reminder description is required', 'error');
                return;
            }
            if (reminder_datetime.val() == "") {
                notify('Validation Error!', 'Reminder date and time is required', 'error');
                return;
            }

            var formatted_datetime = changeDateTimeFromat(reminder_datetime.val());
            console.log("Sending data: ", {
                INSERT: insert,
                Reminder_Subject: reminder_subject.val(),
                Reminder_Description: reminder_description.val(),
                Reminder_Datetime: formatted_datetime
            });

            $.ajax({
                type: "POST",
                url: "ReminderController.php",
                data: {
                    INSERT: insert,
                    Reminder_Subject: reminder_subject.val(),
                    Reminder_Description: reminder_description.val(),
                    Reminder_Datetime: formatted_datetime,
                },
                success: function(response) {
                    console.log("Response from server: ", response);
                    if (response == "Success") {
                        notify('Success!', response, 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        notify('Error!', response, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX error: ", status, error);
                    notify('Error!', 'AJAX request failed: ' + error, 'error');
                }
            });
        }

        function changeDateTimeFromat(daTime) {
            // Convert datetime-local (YYYY-MM-DDTHH:MM) to YYYY-MM-DD HH:MM:SS
            return daTime.replace('T', ' ') + ':00';
        }

        var counter = 0;
        var start = 1;
        var end = start + 9;

        function getReminders() {
            var getReminders = "getReminders";
            counter += 1;
            if (counter != 1) {
                start = end;
                end = end + 10;
            }
            $.ajax({
                type: "POST",
                url: "ReminderController.php",
                data: {
                    GetReminders: getReminders,
                    Start: start,
                    End: end,
                },
                success: function(response) {
                    var reminders = JSON.parse(response);
                    var accordion = $('#accordion');
                    var circleTextColor = ['text-blue', 'text-indigo', 'text-teal', 'text-info', 'text-warning', 'text-danger', 'text-muted'];
                    for (var i = 0; i < reminders.length; i++) {
                        if (i > circleTextColor.length) {
                            circleTextColor.push('text-blue', 'text-indigo', 'text-teal', 'text-info', 'text-warning', 'text-danger', 'text-muted');
                        }
                        accordion.append(
                            `<div class="accordion-item border-0">
                                <div class="accordion-header" id="heading${reminders[i].reminder_id}">
                                    <button class="accordion-button bg-gray-900 text-white px-3 py-10px pointer-cursor collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${reminders[i].reminder_id}" aria-expanded="false">
                                        <i class="fa fa-circle fa-fw ${circleTextColor[i]} me-2 fs-8px"></i> ${reminders[i].reminder_subject}
                                        <span style="position: fixed; right: 0; width: 150px;">
                                            <i class="fa fa-edit text-white fa-2x" onclick="editReminder(${reminders[i].reminder_id})"></i>
                                            <i class="fa fa-trash text-danger fa-2x" onclick="deleteReminder(${reminders[i].reminder_id})"></i>
                                        </span>
                                    </button>
                                </div>
                                <div id="collapse${reminders[i].reminder_id}" class="accordion-collapse collapse" data-bs-parent="#accordion" style="">
                                    <div class="accordion-body bg-gray-800 text-white">
                                        <p class="text-end">${filerDate(reminders[i].reminder_datetime)} - Status: <span class="${reminders[i].status === 'sent' ? 'text-success' : reminders[i].status === 'completed' ? 'text-muted' : 'text-warning'}">${reminders[i].status}</span></p>
                                        <p style="margin-left:20px">${reminders[i].reminder_description}</p>
                                    </div>
                                </div>
                            </div>`
                        );
                    }
                    var showMore = $('#showMore');
                    showMore.hide();
                    if (reminders[0]) {
                        if (reminders[0].TotalRecord > end) {
                            showMore.show();
                        } else {
                            showMore.hide();
                        }
                    }
                },
            });
        }

        function deleteReminder(id) {
            var Delete = "Delete";
            $.confirm({
                title: 'Delete!',
                content: 'Do you want to delete this reminder',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Yes',
                        btnClass: 'btn-red',
                        action: function() {
                            $.ajax({
                                type: "POST",
                                url: "ReminderController.php",
                                data: {
                                    Delete: Delete,
                                    ID: id,
                                },
                                success: function(response) {
                                    if (response == 'Success') {
                                        notify('Success!', response, 'success');
                                        setTimeout(function() {
                                            location.reload();
                                        }, 2000);
                                    } else {
                                        notify('Opps!', response, 'error');
                                    }
                                },
                            });
                        }
                    },
                    close: function() {}
                }
            });
        }

        function editReminder(id) {
            var editReminder = "editReminder";
            $.ajax({
                type: "POST",
                url: "ReminderController.php",
                data: {
                    EditReminder: editReminder,
                    ID: id,
                },
                success: function(response) {
                    var dataRpt = JSON.parse(response);
                    $('#ReminderID').val(id);
                    $('#updreminder_subject').val(dataRpt[0].reminder_subject);
                    $('#updreminder_description').val(dataRpt[0].reminder_description);
                    $('#updreminder_datetime').val(changeDateFormatBackToNormal(dataRpt[0].reminder_datetime));
                    $('#updateModel').modal('show');
                },
            });
        }

        function changeDateFormatBackToNormal(datetime) {
            // Convert YYYY-MM-DD HH:MM:SS to YYYY-MM-DDTHH:MM for datetime-local
            return datetime.substring(0, 16); // Trims seconds
        }

        function SaveUpdate() {
            var ReminderID = $('#ReminderID');
            var updreminder_subject = $('#updreminder_subject');
            var updreminder_description = $('#updreminder_description');
            var updreminder_datetime = $('#updreminder_datetime');
            var formatted_datetime = changeDateTimeFromat(updreminder_datetime.val());

            if (updreminder_subject.val() == "") {
                notify('Validation Error!', 'Reminder subject is required', 'error');
                return;
            }
            if (updreminder_description.val() == "") {
                notify('Validation Error!', 'Reminder description is required', 'error');
                return;
            }
            if (updreminder_datetime.val() == "") {
                notify('Validation Error!', 'Reminder date & time is required', 'error');
                return;
            }

            var saveUpdate = "saveUpdate";
            $.ajax({
                type: "POST",
                url: "ReminderController.php",
                data: {
                    SaveUpdate: saveUpdate,
                    ReminderID: ReminderID.val(),
                    Updreminder_Subject: updreminder_subject.val(),
                    Updreminder_Description: updreminder_description.val(),
                    Updreminder_Datetime: formatted_datetime,
                },
                success: function(response) {
                    if (response == 'Success') {
                        notify('Success!', response, 'success');
                        $('#updateModel').modal('hide');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        notify('Opps!', response, 'error');
                    }
                },
            });
        }

        function filerDate(datetime) {
            var finalDate = "";
            var splitDate = datetime.split(' ');
            var hourpart = '';
            var splitHour = splitDate[1].split(':');
            if (splitHour[0] == 13) { hourpart = "01"; }
            else if (splitHour[0] == 14) { hourpart = "02"; }
            else if (splitHour[0] == 15) { hourpart = "03"; }
            else if (splitHour[0] == 16) { hourpart = "04"; }
            else if (splitHour[0] == 17) { hourpart = "05"; }
            else if (splitHour[0] == 18) { hourpart = "06"; }
            else if (splitHour[0] == 19) { hourpart = "07"; }
            else if (splitHour[0] == 20) { hourpart = "08"; }
            else if (splitHour[0] == 21) { hourpart = "09"; }
            else if (splitHour[0] == 22) { hourpart = "10"; }
            else if (splitHour[0] == 23) { hourpart = "11"; }
            else if (splitHour[0] == 00) { hourpart = "12"; }
            finalDate = splitDate[0] + " " + hourpart + ":" + splitHour[1];
            return finalDate;
        }
    </script>
</body>
</html>