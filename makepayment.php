<?php 
session_start();
if(!isset($_SESSION['user_id']))
{
	header('location:login.php');
}
include("design.php");
	$ticket_id = $_GET['id'];
	$conn =new mysqli('selabnadiry33026.domaincommysql.com', 'sntravel', 'Afghan@786','sntravel');
	$query = " SELECT * FROM customer";
    $result = $conn->query($query);
    $customers = $result->fetch_all(MYSQLI_ASSOC);
	
	
	$query = " SELECT * FROM ticket WHERE ticket = " . $ticket_id;
	$result = $conn->query($query);
	$ticket = $result->fetch_assoc();
	
	
	$query="select sale-customer_payment as remaining from ticket where ticket =" .$ticket_id;
	$result=$conn->query($query);
	$tkt=$result->fetch_assoc();
	
	
	
	
	?>


  <div class="card col-7">
<div class="card-header">
<h1> Customer Make Payment</h1>
</div>
<div class="card-body">
<form action="makepayment1.php" method="post" class="needs-validation col-6" novalidate>
 <div class="form-group"> 
 <input type="hidden" name="ticket" value="<?php echo $ticket['ticket'];?>" />
 <input type="hidden" name="cust_id" value="<?php echo $ticket['customer_id']?>"
 </div>
 <div class="form-group">
    <label for="supp_name"><i class="fa fa-fw fa-user"></i>Customer Name:</label>
    
    <select disabled="disabled"  name="cust_name" spry:default="select one">
    <br />
    <option value="">--Select Customer--</option>
    <?php
						 foreach($customers as $customer)
						 {
							$selected = ($customer['customer_id'] == $ticket['customer_id'])? 'selected' : '' ;
                            echo "<option value='{$customer['customer_id']}' {$selected}>{$customer['customer_name']}</option>";
							 
						 }
						 
						 ?>
    </select>
    </div>
    
    <div class="form-group">
    
 <label for="supp_name"><i class="fa fa-fw fa-user"></i>Net Payable</label>
 <input disabled="disabled" class="col-3" class="form-group" type="number" name="cust_payment" value="<?php echo $ticket['sale']; ?>" />
 


    
 <label for="supp_name"><i class="fa fa-fw fa-user"></i>Balance:</label>
 <input disabled="disabled" class="col-3" type="number" class="form-control-sm"  name="cust_payment" value="<?php echo $tkt['remaining']; ?>" />
 
</div>
<div class="form-group">
    
 <label for="supp_name"><i class="fa fa-fw fa-user"></i>Payment Recived:</label>
 <input type="number" class="form-control-sm"  name="cust_pymnt" />
 
</div>

<div class="card-footer text-center">
<input type="submit" name="paymentbtn" value="Make Payment" class="btn-success" />

</div>


</body>
</html>