<?php
  include 'header.php';
?>
<style>
  .select2-selection__rendered {
    line-height: 31px !important;
}

.select2-container .select2-selection--single {
    height: 35px !important;
}

.select2-selection__arrow {
    height: 34px !important;
}
.nav-tabs .nav-link.active{
    color:red;
}
</style>
<title>Ticket Report</title>
<?php
  include 'nav.php';
  include 'connection.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>
<div style="margin-left:30px; margin-right:30px; margin-top:10px" class="card" id="todaycard">
<div class="card-header bg-light">
<h2><b>Pending Visa<span style="color:#C66"> in Market</span></b></h2>
</div>
<div class="card-body">
<form  method="post" target="_self">

<span class="fa fa-search">  Search Here  </span><input type="text" style="margin-left:5px"  class="form-control-sm" id="myInput" onkeyup="fff()" placeholder="Search for Customer">
<hr />
<table id="myTable" class="table  table-striped table-hover" cellpadding="10px" width="1340" >

  <thead class="thead-dark ">
  <tr>
<th class="bg-black-400 text-white">Visa#</th>
    <th class="bg-black-400 text-white">Customer Name</th>
  <th class="bg-black-400 text-white">Passenger Name</th>
    <th class="bg-black-400 text-white">Customer Phone</th>
    <th class="bg-black-400 text-white">Applied By</th>
    <th class="bg-black-400 text-white">Date of Apply</th>
    <th class="bg-black-400 text-white">Sale Price</th>
   <th class="bg-black-400 text-white">Company</th>
 <th class="bg-black-400 text-white">Type of Visa</th>
    <th class="bg-black-400 text-white">Status</th>
    <th class="bg-black-400 text-white">Action</th>

      </tr>
   <?php
 include "connection.php";
  
 $sql = "SELECT visa_id,datetime,passenger_name,customer_name,customer_phone,staff_name,supp_name,sale,currencyName,
 country_names FROM visa INNER JOIN customer ON customer.customer_id=visa.customer_id INNER JOIN staff ON 
 staff.staff_id=visa.staff_id INNER JOIN supplier ON supplier.supp_id=visa.supp_id INNER JOIN country_name ON 
 country_name.country_id=visa.country_id INNER JOIN currency ON currency.currencyID = visa.saleCurrencyID WHERE pendingvisa=1";
$result = $conn->query($sql);
$num_rows=1;
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {	
echo "<tr>";
echo "<td>".$num_rows++."</td>";
echo "<td>".$row['customer_name']."</td>";
echo "<td>".$row['passenger_name']."</td>";
echo "<td>".$row['customer_phone']."</td>";
echo "<td>".$row['staff_name']."</td>";

echo "<td>".$row['datetime']."</td>";	
echo "<td>".$row['sale']. ' ' .$row['currencyName'] . "</td>";
echo "<td>".$row['supp_name']."</td>";	
echo "<td>".$row['country_names']."$"."</td>";
echo "<td>".'Pending'."</td>";
echo '<td><button class="btn" style="color:green"><a href="approvevisa.php?id='.$row['visa_id'].'"><i class="fa fa-recycle"></i>APPROVE</a></button></td>';	
	}
   
$conn->close();
echo "</tr>";
}
include "connection.php";
            $sql = "SELECT * FROM (SELECT SUM(sale) AS amount,currency.currencyName FROM `visa` INNER JOIN 
            currency ON currency.currencyID = visa.saleCurrencyID GROUP BY currency.currencyName) AS baseTable WHERE amount !=0";
            $result = $conn->query($sql);
      if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {	
            echo "<tr style='background:black; color:white; font-size:20px;  '><td colspan='6' style='text-align:right;' >Total:</td><td style='width:100%'>". number_format( $row['amount']). ' ' . $row['currencyName'] ."</td><td></td><td></td><td></td><td></td><td></td><tr>";
	      }
        $conn->close();
      }
 ?>
 </tr>
  </table>
  </form>
  <p style="color:#FC0">
  </div>
 <div class="card-footer">
 
 </div>
<div  class="fa fa-rec">
<?php include 'footer.php'; ?>
</body>
</html>
