<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	header('location:login.php');
}


$conn=new mysqli('selabnadiry33026.domaincommysql.com', 'sntravel', 'Afghan@786','sntravel');
$query5="select staff_name from staff where staff_id=".$_SESSION['user_id'];
$row5=$conn->query($query5);
$staffname=$row5->fetch_assoc();


$pdo = new PDO('mysql:host=selabnadiry33026.domaincommysql.com;dbname=sntravel', 'sntravel', 'Afghan@786');
$sql = "SELECT customer_id, concat(customer_name,'--',customer_phone) as customer_name FROM customer";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll();

if(isset($_GET['getcust'])){
	$customer=$_GET['getcust'];
	
$sql1="select (SUM(sale))-(SUM(customer_payment)) as tkttotal from ticket where customer_id=".$customer;
$sql2="select (SUM(sale))-(SUM(customer_payment)) as visatotal from visa where customer_id=".$customer;

$result1=$conn->query($sql1);
$result2=$conn->query($sql2);
$row1=$result1->fetch_assoc();	
$row2=$result2->fetch_assoc();

$sql3="select * from customer where customer_id=".$customer;
$result3=$conn->query($sql3);
$row3=$result3->fetch_assoc();	
}

?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.min.css"/>
<link rel="stylesheet" type="text/css" href="iCheck/all.css"/>
<link rel="stylesheet" type="text/css" href="bootstrap-slider/slider.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Suppliers</title>
<form action="customerledger.php"   method="get" target="_self">


<select name="getcust">
 <?php foreach($users as $user): ?>
        <option value="<?= $user['customer_id']; ?>"><?= $user['customer_name']; ?></option>
    <?php endforeach; ?>
</select>
<input type="submit" class="btn-success" name"sbmtbtn" value="Search" />
<link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap.min.css"/>
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/3/w3.css">
<link rel="stylesheet" type="text/css" href="datepicker/datepicker3.css"/>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/datepicker/moment.min.js"></script>
<script src="datepicker/daterangepicker.js"></script>
<script src="https://kit.fontawesome.com/782ba98ac5.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
   document.getElementById("myTopnav").style.marginLeft="250px";
   document.getElementById("cardreport").style.marginLeft="250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
   document.getElementById("myTopnav").style.marginLeft="0px";
   document.getElementById("cardreport").style.marginLeft="0px";
  
}
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
$(document).ready(function(){
        var date_input=$('input[name="date"]'); //our date input has the name "date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'mm/dd/yyyy',
            container: container,
            todayHighlight: true,
            autoclose: true,
        })
    })
</script>
<style>
body {
  font-family: "Lato", sans-serif;
}

.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
/* Add a black background color to the top navigation */
.w3-topnav {
  background-color: #333;
  overflow: hidden;
}

/* Style the links inside the navigation bar */
.w3-topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Change the color of links on hover */
.w3-topnav a:hover {
  background-color: #ddd;
  color: black;
}

/* Add an active class to highlight the current page */
.w3-topnav a.active {
  background-color: #4CAF50;
  color: white;
}

/* Hide the link that should open and close the topnav on small screens */
.w3-topnav .icon {
  display: none;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color:#666;
  color: white;
  text-align: center;
}
#form23{
	margin-left:auto;
	margin-right:auto;
	margin-top:auto;
	text-align:center;
	margin-top:inherit;
	background-color:#C66;
	width:40%;
}
#form44{
	margin-left:auto;
	margin-right:auto;
	margin-bottom:auto;
	margin-top:auto;
	background-color:#000;
	padd
}
#selabname
{
	font-size:18px;
	margin-bottom:auto;
	margin-top:auto;
	margin-left:auto;
	margin-right:auto;
;
}

</style>
</head>

<body>

<div id="mySidenav" class="sidenav">
<p  class="col-md-auto auto w3-left"style="color:white"><i class="fa fa-fw fa-plane"></i><b> Selab <span style="color:#C66">Nadiry</span> Travel & Tours</b></p>
<div class="text-center">
<img style="text-align:center;"  id="imgperson" class="rounded-circle"
width="98px" height="95px" src="logoselab.png" alt="myiamge" />
</div>
<p style="color:white; margin-top:5px; text-align:center;">Welcome! <br /><b><?php echo $staffname['staff_name']; ?></b></p>
  <a href="javascript:void(0)" class="btn-outline-warning float-right" onclick="closeNav()">&times;</a>
  <a href="home.php"><i class="fa fa-fw fa-home"></i> Home</a>
  <a href="Ticket.php"><i class="fa fa-fw fa-plane"></i> Ticket</a>
  <a href="visa.php"><i class="fa fa-fw fa-ticket"></i> Visa</a>
  <a href="supplier.php"><i class="fa fa-fw fa-product-hunt"></i> Supplier</a>
  <a href="views/expense/expenses.php"><i class="fa fa-fw fa-dollar"></i> Expenses</a>
  <a href="payments.php"><i class="fa fa-fw fa-dollar"></i> Payments</a>
  <a href="customer.php"><i class="fa fa-fw fa-user"></i> Customers</a>
  <a href="about.php"><i class="fa fa-fw fa-info"></i> About</a>
  <a href="contact.php"><i class="fa fa-fw fa-envelope-square"></i> Contact</a>
  </div>
<div style="background:black;" class="w3-topnav" id="myTopnav">
<div class="nav-link">
<img class="float-left rounded-circle" style=" width:100px; height:100px; margin-bottom:5px" src="logoselab.png" />
<p id="selabname"  class="col-md-auto auto float-left" style="color:white; margin-top:12px; font-size:36px;"><b> Selab Nadiry <span style="color:#C66">Customer</span>Invoice</b></p> 
</div>
</div>
<hr />
<h3><b>Bill To:</b>  <?php if(isset($_GET['getcust'])){ echo $row3['customer_name'];  } ?> </h3>
<h3><b>Email: </b>    <?php if(isset($_GET['getcust'])){ echo $row3['customer_email'];  } ?></h3>
<h3><b>Phone: </b> <?php if(isset($_GET['getcust'])){ echo $row3['customer_phone'];  } ?></h3>



<hr />
<h2> TICKET </h2>
<table class="table-bordered table-dark table-responsive" cellpadding="10px" width="101%" border="1">
 <b><tr  style="color:white; background-color:#F00">
    <th width="144">Transiction Type</th>
    <th width="364">Passenger Name</th>
    <th width="143">Issuance Date</th>
    <th width="140">PNR</th>
    <th width="145">Sale</th>
    <th width="174">Payment</th>
  

      </tr></b>
   <?php
 $id=$_GET['id'];
  $conn=ew mysqli('selabnadiry33026.domaincommysql.com', 'sntravel', 'Afghan@786','sntravel');
 
 $sql = "SELECT  ticket,DATE(datetime) as date,pnr,passenger_name,net_price,sale,supp_name, airports.airport_code AS from_code, to_airports.airport_code AS to_code FROM ticket INNER JOIN customer ON customer.customer_id=ticket.customer_id INNER JOIN supplier ON supplier.supp_id=ticket.supp_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.customer_id= '$id' ";
 
 
 //echo var_dump($sql);
$result=$conn->query($sql);
if($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {		 
echo "<tr style='color:white; background-color:black; '>";
echo "<td >".'Ticket'."</td>";
echo "<td>".$row['passenger_name']."</td>";
echo "<td>".$row['date']."</td>";
echo "<td>".$row['pnr']."</td>";
echo "<td>".$row['sale']."</td>";	
echo "<td>".$row['customer_payment']."</td>";	
echo "</tr>";
}
  }
}
?>
</table>


<h2> VISA </h2>

<table class="table-bordered table-dark table-responsive" cellpadding="10px" width="1340" border="1">
 <b><tr  style="color:white; background-color:#F00">
    <th width="144">Transiction Type</th>
    <th width="365">Passenger Name</th>
    <th width="144">Date of Apply</th>
    <th width="138">/Visa Type</th>
    <th width="145">Sale</th>
    <th width="166">Payment</th>
  </tr></b>
 <?php    
 $id=$_GET['id'];
 $sql1 = "SELECT visa_id,DATE(datetime) as date,passenger_name,country_names,supp_name,staff_name,net_price,sale,gaurantee,address FROM visa INNER JOIN customer ON customer.customer_id=visa.customer_id INNER JOIN country_name ON country_name.country_id=visa.country_id INNER JOIN supplier ON supplier.supp_id=visa.supp_id INNER JOIN staff ON staff.staff_id=visa.staff_id where visa.customer_id='$id' ";

 $result=$conn->query($sql1);
if ($result->num_rows > 0) {
while($roww = $result->fetch_assoc()) {
echo "<tr style='color:white; background-color:black;'>";	
echo "<td>".'Visa'."</td>";	 
echo "<td>".$roww['passenger_name']."</td>";
echo "<td>".$roww['date']."</td>";
echo "<td>".$roww['country_names']."</td>";
echo "<td>".$roww['sale']."</td>";	
echo "<td>".$roww['customer_payment']."</td>";
echo "</tr>";  

  

}
  }
   }
 ?>
 
 </table>
 <hr class="float-left" style="width:40%; margin-top:100px; border-color:black;" />
 <h3 style="margin-top:130px;"><b> Total Payable: $</b> <?php if(isset($_GET['getcust'])){
	$customer=$_GET['getcust']; echo $row1['tkttotal']+$row2['visatotal']; } ?> </h3>
   
 </form>


 
 
  <div style="background:black;" class="footer">
 <p style="font-size:24px">Selab Nadiry Travel & Tours</p>
 <p class="float-left"><i class="fa fa-fw fa-phone"></i>phone:+93(0)202104500, +93(0)7786130840, +93(0)744736969
 <i class="fa fa-fw fa-address-book"></i>Address: Haidari Brothers Market 3rd Floor Office #227</p>
 <p class="float-left"><i class="fa fa-fw fa-bank"></i>Account Numbers: Maiwand Bank(USD):00000000000000000  |  Azizi Bank(USD): 00000000000000 | AIB Bank(USD):0000000000000 </p>
   </div>
</body>
</html>
