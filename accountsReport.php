<?php
  include 'header.php';
?>
<title>Accounts Report</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
  $sql = "SELECT permission.select,permission.update, permission.delete,permission.insert FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$insert = $records[0]['insert'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}
?>
<style>
  #customBtn{ color:#29323c;border-color:#29323c; }
  #customBtn:hover{color:  #FFFFFF;background-color:#485563;border-color:#485563}
  .bg-graident-lightcrimson{
    background: #485563;  /* fallback for old browsers */
    background: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    background: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

  }
  .text-graident-lightcrimson{
    color: #485563;  /* fallback for old browsers */
    color: -webkit-linear-gradient(to top, #29323c, #485563);  /* Chrome 10-25, Safari 5.1-6 */
    color: linear-gradient(to top, #29323c, #485563); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
  }
  
</style>
<div class="container-fluid"  >
  <div class="row">
  <div class="col-md-12">
    <div style="margin-left:30px;  margin-right:30px; margin-top:10px;" class="card" id="todaycard">
      <div class="card-header bg-light" >
        <h2 class="text-graident-lightcrimson" ><b><i class="fa fa-fw fa-money text-dark" ></i> <i>Accounts Report</i> </b></h2>
      </div>
      

    <div class="row">
      <div class="col-md-12">
      <div class="table-responsive ">
        <table id="myTable"  class="table  table-striped table-hover ">
          <thead class="text-white bg-graident-lightcrimson">
            <tr  style="font-size:14px">
              <th>S#</th>
              <th>Account</th>
              <th>Account Balance</th>
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
<?php include 'footer.php'; ?>

<script type="text/javascript">

  function loadAccounts(){
    $('#StaffReportTbl').html('<tr><td colspan="3" class="text-center">Loading...</td></tr>');
    $.ajax({
      url: '/accountsReportController.php',
      method: 'GET',
      success: function(e){
        $('#StaffReportTbl').html(e);
      }
    });
  }

  $(document).ready(function(){
    loadAccounts();
  });
</script>
</body>
</html>
