<?php
  include 'header.php';
?>
<title>Role</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Currency' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
$insert = $records[0]['insert'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
        <div class="card w3-card-24 " id="todaycard">
            <div class="card-header bg-dark ">
                <h1 class="text-white text-center"><b><i class="fa fa-money"> Currency</i></b></h1>
            </div>
            <div class="card-body">
                <?php if($insert == 1) { ?>
                <form onsubmit="return false;">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label class="sr-only" for="inlineFormInputGroup">Currency Name</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-money"></i></div>
                                </div>
                                <input type="text" class="form-control" name="currency_name" id="currency_name" autofocus="autofocus" placeholder="Currency Name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-dark mb-2" onclick="addCurrency()"> <i class="fa fa-plus"></i> Save</button>
                        </div>
                    </div>
                </form>
                <?php } ?>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive ">
                            <table id="myTable"  class="table  text-center table-striped table-hover ">
                                <thead class="thead-dark bg-black text-white" style="font-size:14px">
                                    <tr>
                                        <th>S#</th>
                                        <th>Currency Name Name</th>
                                          <?php  if($delete == 1  || $update == 1) {   ?>
                                        <th>Action</th>
                                         <?php    } ?>
                                    </tr>
                                </thead>
                                <tbody id="CurrencyReportTbl">
          
                    
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title " id="exampleModalLabel">Update Currency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <form onsubmit="return false;">
                <div class="row ">
                    <div class="col-md-12">
                        <label class="sr-only" for="inlineFormInputGroup">Currency Name</label>
                        <div class="input-group mb-2">
                            <input type="text"  class="d-none" value="" name="updcurrencyID" id="updcurrencyID" >
                            <input type="text" class="form-control" name="updcurrency_name" id="updcurrency_name" autofocus="autofocus" placeholder="Currency Name">
                        </div>
                    </div>
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" onclick="updateCurrency()" class="btn btn-info">Save changes</button>
      </div>
    </div>
  </div>
</div>
   </div>
</div>

<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getCurrency();
  });
  function addCurrency(){
    var insert ="INSERT";
    var currency_name = $('#currency_name');
    if(currency_name.val() == ""){
        notify('Validation Error!', 'Currency name is required', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "currencyController.php",  
            data: {
                INSERT:insert,
                Currency_Name: currency_name.val(),
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getCurrency();
                    currency_name.val('');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    }
    function getCurrency(){
    var select_currency = "select_currency";
    $.ajax({
        type: "POST",
        url: "currencyController.php",  
        data: {
            Select_Currency:select_currency,
        },
        success: function (response) {  
            var currencyRpt = JSON.parse(response);
            $('#CurrencyReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<currencyRpt.length; i++){
                finalTable = "<tr><th scope='row'>"+ j + "</th><td class='text-capitalize' style='font-size:15px'><b>"+ currencyRpt[i].currencyName +"</b></td>"; 
                <?php if($update == 1 || $delete == 1){ ?>
                    finalTable += "<td class='float-center'>";
                <?php  } ?>
                <?php if($update == 1) {  ?>
                finalTable += "<button type='button'0 onclick='edit("+ currencyRpt[i].currencyID + ", \""+ currencyRpt[i].currencyName + "\" )' class='btn'><i class='fa fa-edit text-info fa-2x' aria-hidden='true'></i>"+
                                "</button>";
                <?php } ?>
                <?php if($delete == 1) {  ?>
                finalTable += "<button type='button'0 onclick='Delete(" + 
                                currencyRpt[i].currencyID +  ")' class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i>"+
                                "</button>";
                <?php } ?>
                <?php if($update == 1 || $delete == 1){ ?>
                    finalTable += "</td>";
                <?php } ?>
                finalTable += "</tr>";
                $('#CurrencyReportTbl').append(finalTable);
                j +=1;
            }
        },
    });
    }
   // Get the input field
var input = document.getElementById("currency_name");
// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    addCurrency();
  }
});
function Delete(id){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this currrency',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "currencyController.php",  
                data: {
                  Delete:Delete,
                  ID:id
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', 'Record Successfully Deleted', 'success');
                  getCurrency();
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
function edit(id, name){
    $('#updcurrencyID').val(id);
    $('#updcurrency_name').val(name);
    $('#exampleModal').modal('show');
}

function updateCurrency(){
    var UpdateCurrency = "UpdateCurrency";
    var updID = $('#updcurrencyID');
    var updName = $('#updcurrency_name');
    if(updID.val() == ""){
        notify('Error!', 'Something went wrong', 'error');
        return;
    }
    if(updName.val() == ""){
        notify('Error!', 'currency name is required', 'error');
        return;
    }
        $.ajax({
            type: "POST",
            url: "currencyController.php",  
            data: {
                UpdateCurrency:UpdateCurrency,
                UpdID:updID.val(),
                UpdName: updName.val(),
            },
            success: function (response) {
                if(response == "Success"){
                    notify('Success!', response, 'success');
                    getCurrency();
                    updName.val('');
                    updID.val('');
                    $('#exampleModal').modal('hide');
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
}
</script>
</body>
</html>