<?php
  include 'header.php';
?>
<title>Hawala Countries Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<style>
  #customBtn{ color:#33001b;border-color:#33001b; }
  #customBtn:hover{color:  #FFFFFF;background-color:#33001b;border-color:#33001b}
</style>
<div class="container-fluid">
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header" >
        <h2 class="text-danger" ><b><i class="fa fa-fw fa-flag text-dark" ></i> <i>Hawala Countries  Report</i> </b></h2>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 offset-8">
            <button type="button" class="btn float-right" id="customBtn" data-toggle="modal" data-target="#exampleModal">
             <i class="fa fa-plus"> </i> Add Hawala Countries
            </button>
        </div>
      </div>
    
    <br/>
    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-dark">
            <tr class="text-center">
              <th>S#</th>
              <th>Country Name</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="StaffReportTbl">
                    
              </tbody>
        </table>
      </div> 
      </div>
      </div>
  </div>
</div>
</div>
</div>
</div>

<!-- INSERT Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="exampleModalLabel"><b><i>Add Hawala Country</i></b></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="CountryNameForm" method="post" enctype="multipart/form-data"> 
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-3 col-form-label">Country Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="country_name" id="country_name" placeholder="Country Name">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updexampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark" >
        <h3 class="modal-title text-white" id="updexampleModalLabel"><b><i>Update Country Name</i></b></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="UpdCountryNameForm" method="post" enctype="multipart/form-data"> 
          <input type="hidden" id="country_id" name="country_id" />
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-3 col-form-label">Country Name:</label>
            <div class="col-sm-9">
              <input type="text" class="form-control"  name="updcountry_name" id="updcountry_name" placeholder="Country Name">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"  data-dismiss="modal">Close</button>
        <button type="submit" class="btn text-white bg-danger">Save</button>
      </div>
      </form>
    </div>
  </div>
</div>

  
<?php include 'footer.php'; ?>
<script>
  $(document).ready(function(){
    getCountriesNameReport();
  });

function Delete(ID){
  var Delete = "Delete";
  $.confirm({
    title: 'Delete!',
    content: 'Do you want to delete this country',
    type: 'red',
    typeAnimated: true,
    buttons: {
        tryAgain: {
            text: 'Yes',
            btnClass: 'btn-red',
            action: function(){
              $.ajax({
                type: "POST",
                url: "hawalaCountriesController.php",  
                data: {
                  Delete:Delete,
                  ID:ID,
                },
                beforeSend: function () {
                    HoldOn.open({
                    theme: 'sk-cube-grid',
                    message: "<h4>Deleting Data...</h4>"
                    });
                },
                complete: function () {
                  HoldOn.close();
                },
                success: function (response) {  
                if(response == 'Success'){
                  notify('Success!', response, 'success');
                  getCountriesNameReport();
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
function GetDataForUpdate(id){
  var GetDataForUpdate = "GetDataForUpdate";
  $.ajax({
          type: "POST",
          url: "hawalaCountriesController.php",  
          data: {
            GetDataForUpdate:GetDataForUpdate,
            ID:id,
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
            var dataRpt = JSON.parse(response);
            $('#country_id').val(id);
            $('#updcountry_name').val(dataRpt[0].country_name);
            $('#updexampleModal').modal('show');
        },
  });
}
function getCountriesNameReport(){
      var getCountriesNameReport = "getCountriesNameReport";
      $.ajax({
          type: "POST",
          url: "hawalaCountriesController.php",  
          data: {
             GetCountriesNameReport:getCountriesNameReport
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
            var countryNameRpt = JSON.parse(response);
            $('#StaffReportTbl').empty();
            var j = 1;
            var finalTable = "";
            for(var i=0; i<countryNameRpt.length; i++){
              finalTable = "<tr><th scope='row' class='text-center'>"+ j + "</th><td class='text-capitalize text-center'>"+ countryNameRpt[i].country_name
              +"</td>";

              finalTable += "<td class='text-center'><button  type='button'0 onclick='GetDataForUpdate("+ 
              countryNameRpt[i].country_id +")'" +
              "class='btn '><i class='fa fa-edit text-dark fa-2x' aria-hidden='true'></i></button>&nbsp;&nbsp;<button type='button'0 onclick='Delete("+ 
              countryNameRpt[i].country_id +")'" +
              "class='btn'><i class='fa fa-trash text-danger fa-2x' aria-hidden='true'></i></button> </td>";
              
              finalTable += "</tr>";
              $('#StaffReportTbl').append(finalTable);
              j +=1;
            }
          },
      });
    }
$(document).on('submit', '#CountryNameForm', function(event){
    event.preventDefault();
    var country_name = $('#country_name');
    if(country_name.val() == ""){
        notify('Validation Error!', 'Country name is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Insert_CountryName','Insert_CountryName');
        $.ajax({
            type: "POST",
            url: "hawalaCountriesController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
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
                    $('#CountryNameForm')[0].reset();
                    $('#exampleModal').modal('hide');
                    getCountriesNameReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
    // Update 
    $(document).on('submit', '#UpdCountryNameForm', function(event){
    event.preventDefault();
    var country_id = $('#country_id');
    if(country_id.val() == ""){
        notify('Validation Error!', 'Something went wrong', 'error');
        return;
    }
    var updcountry_name = $('#updcountry_name');
    if(updcountry_name.val() == ""){
        notify('Validation Error!', 'Country name is required', 'error');
        return;
    }
      data = new FormData(this);
      data.append('Update_CountryName','Update_CountryName');
        $.ajax({
            type: "POST",
            url: "hawalaCountriesController.php",  
            data: data,
            contentType: false,       
            cache: false,             
            processData:false, 
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
                    $('#UpdCountryNameForm')[0].reset();
                    $('#updexampleModal').modal('hide');
                    getCountriesNameReport();
                }else{
                    notify('Error!', response, 'error');
                }
            },
        });
    });
</script>
</body>
</html>
