<?php
  include 'header.php';
?>
<title>Days Calculator</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 " >
	<form method="post" id="changePassForm">
		<div id="todaycard" class="card float-center"  >
			<div class="card-header text-center" style="background-color:black; color:white;">
				<h2>Days Calculator</h2>
			</div>
			<div class="card-body">
        <div class="row">
          <div class="col-sm-2">
                From:<input type="text" class="form-control" id="fromdate" name="fromdate"/> 
          </div>
          <div class="col-sm-2">
                To:<input type="text" class="form-control" id="todate" name="todate"/> 
          </div>
          <div class="col-sm-2">
                <button type="button" onclick="dateDef('DateWise')" class="btn btn-success mt-4 form-control"> <i class="fa fa-calculator" aria-hidden="true"></i> Calculate</button>
          </div>
        </div>
        <hr/>
        <div id="dateDiv" style="display:none">
          <code style="font-size:30px" ><b>Total Days:</b></code> <span style="font-size:30px" id="dateDays"><b></b></span>
          <hr/>
        </div>
        <div class="row">
          <div class="col-sm-2">
                From:<input type="text" class="form-control" id="from_date" name="from_date"/> 
          </div>
          <div class="col-sm-2">
                Days:<input type="text" placeholder="Number of days" class="form-control" id="days" name="days"/> 
          </div>
          <div class="col-sm-2">
                <button type="button" onclick="dateDef('daysWise')" class="btn btn-success mt-4 form-control"> <i class="fa fa-calculator" aria-hidden="true"></i> Calculate</button>
          </div>
        </div>
        
        <div id="daysDiv" style="display:none">
          <hr/>
          <code style="font-size:30px" ><b>Date will be:</b></code> <span style="font-size:30px" id="dateSelf"><b></b></span>
        </div>
			</div>
		</div>
	
	</form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
<script>
    $(document).ready(function() {
      $('#fromdate').dateTimePicker();
      $('#todate').dateTimePicker();
      $('#from_date').dateTimePicker();
      const date = new Date();
      month = date.getMonth() + 1;
      if(month == 1){
        month ="01";
      }else if(month == 2){
        month ="02";
      }else if(month == 3){
        month ="03";
      }else if(month == 4){
        month ="04";
      }else if(month == 5){
        month ="05";
      }else if(month == 6){
        month ="06";
      }else if(month == 7){
        month ="07";
      }else if(month == 8){
        month ="08";
      }else if(month == 9){
        month ="09";
      }
      var day = date.getDate();
      if(day == 1){
        day ="01";
      }else if(day == 2){
        day ="02";
      }else if(day == 3){
        day ="03";
      }else if(day == 4){
        day ="04";
      }else if(day == 5){
        day ="05";
      }else if(day == 6){
        day ="06";
      }else if(day == 7){
        day ="07";
      }else if(day == 8){
        day ="08";
      }else if(day == 9){
        day ="09";
      }
      $('#fromdate').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#todate').val(date.getFullYear() + '-' + month + '-'+ day);
      $('#from_date').val(date.getFullYear() + '-' + month + '-'+ day);
});
function dateDef(type){
  var daysDiv = $('#daysDiv');
  var dateDiv = $('#dateDiv');
  daysDiv.hide();
  dateDiv.hide();
  var fromdate = $('#fromdate');
  var todate = $('#todate');
  var from_date = $('#from_date');
  var days = $('#days');
  var getCalculation = "getCalculation";
    $.ajax({
        type: "POST",
        url: "datediffController.php",  
        data: {
            GetCalculation:getCalculation,
            Fromdate:fromdate.val(),
            Todate:todate.val(),
            From_Date:from_date.val(),
            Days:days.val(),
            Type:type
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
            var getCalculation = JSON.parse(response);
			      if(type == "DateWise"){
              dateDiv.show();
              $('#dateDays').text(getCalculation[0].diff);
            }else{
              daysDiv.show();
              $('#dateSelf').text(getCalculation[0].diff);
            }
        },
    });
}
</script>
</body>
</html>