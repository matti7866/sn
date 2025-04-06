<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	header('location:login.php');
}
  $conn =new mysqli('selabnadiry33026.domaincommysql.com', 'sntravel', 'Afghan@786','sntravel');
$id=$_GET['visa_id'];
if($_SERVER['REQUEST_METHOD']=='GET')
{
$q="UPDATE `visa` SET `pendingvisa` = '0' WHERE `visa`.`visa_id`='$id' ";
$r=$conn->query($q);
if(!$r) echo "there is error";
else 
header("location:pendingvisa.php");	
	
}

?>