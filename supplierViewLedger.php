<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Supplier Ledger' ";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':role_id', $_SESSION['role_id']);
$stmt->execute();
$records = $stmt->fetchAll(\PDO::FETCH_ASSOC);
$select = $records[0]['select'];
$update = $records[0]['update'];
$delete = $records[0]['delete'];
if($select == 0){
  echo "<script>window.location.href='pageNotFound.php'</script>";
}


require("design.php");
function fetch_customer_data(){
include("connection.php");
$id=$_GET['id'];
echo $id;
$sql9="select * from supplier where supp_id='$id'";
$result=$conn->query($sql9);
$row3=$result->fetch_assoc();


$tktsale="select sum(sale) as sale from ticket where customer_id=".$id;
$tktresult=$conn->query($tktsale);
$tktresult1=$tktresult->fetch_assoc();

$visasale="select sum(sale) as sale from visa where customer_id=".$id;
$visaresult=$conn->query($visasale);
$visaresult1=$visaresult->fetch_assoc();

$payment="select sum(payment_amount) as payment_amount from customer_payments where customer_id=".$id;
$paymentresult=$conn->query($payment);
$paymentresult1=$paymentresult->fetch_assoc();
$customer_name = "";
$customer_email ="";
$customer_phone ="";
if($row3['supp_name']  != "")
{
    $supp_name = ucwords(strtolower($row3['supp_name']));
}
if($row3['supp_email']  != "")
{
    $supp_email = $row3['supp_email'];
}
if($row3['supp_phone']  != "")
{
    $supp_phone = $row3['supp_phone'];
}
$output = '<img class="float-left rounded-circle" style=" width:120px; height:120px;" src="logoselab.png" /><b><h1 style="background:black; color:white;" class="text-center">Selab Nadiry Travel  & Tours Customer <span style="color:red"> Ledger</span></h2></b>
<div class="card" id="todaycard"><h3><b>Bill To: '. $supp_name . '</b></h3><h3><b>Email: '. $supp_email . '</b></h3><h3><b>Phone: '. $supp_phone . '</b></h3>';
$output .='
<table class="table table-striped  table-responsive table-bordered">
 <b><tr style="background-color:black; color:red;">
    <th width="144">Transiction Type</th>
    <th width="364">Supplier Name</th>
    <th width="340">Date</th>
    <th width="140">Identification</th>
    <th width="110">Orgin</th>
    <th width="110">Destination</th>
    <th width="145">Debit</th>
      <th width="145">Credit</th>
<th width="145">Running Balance</th>
 </tr></b>';
 if(isset($_GET['id'])){
  include("connection.php");
  
 
 $sql = "SELECT * FROM (SELECT  'Ticket' AS TRANSACTION_Type,supp_name AS Passenger_Name,DATE(datetime) as date,pnr AS 
 Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, net_price AS Debit, 0 AS Credit,'' 
 AS remarks  FROM ticket INNER JOIN supplier ON supplier.supp_id=ticket.supp_id INNER JOIN airports ON airports.airport_id=
 ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.supp_id='$id'

 UNION ALL
 SELECT 'Visa'  AS TRANSACTION_Type,supp_name AS Passenger_Name, DATE(datetime) as date,country_names AS Identification,
'' AS Orgin, '' AS Destination, net_price AS Debit, 0 AS Credit,'' AS remarks FROM visa INNER JOIN country_name ON 
country_name.country_id=visa.country_id INNER JOIN supplier ON supplier.supp_id=visa.supp_id where visa.supp_id='$id'
 UNION ALL 
 SELECT  'Payment' AS TRANSACTION_Type, supp_name AS Passenger_Name,DATE(time_creation) as date,'' AS Identification,'' AS Orgin, 
 '' AS Destination, 0 AS Debit, IFNULL(payment_amount,0) AS Credit, '' AS remarks
 from payment INNER JOIN supplier ON supplier.supp_id = payment.supp_id where payment.supp_id='$id'
 UNION ALL 
 SELECT  'Hotel Reservation' AS TRANSACTION_Type, CONCAT('Hotel: ',hotel_name) AS Passenger_Name,DATE(datetime) as date,'' 
 AS Identification,'' AS Orgin, country_names AS Destination, net_price AS Debit, 0 AS Credit, '' AS remarks
 from hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id where supplier_id='$id'
 UNION ALL 
 SELECT  'Car Reservation' AS TRANSACTION_Type, CONCAT('Car Description: ',car_description) AS Passenger_Name,DATE(datetime) as
date,'' AS Identification,'' AS Orgin, '' AS Destination, net_price AS Debit, 0 AS Credit, '' AS remarks
 from car_rental where supplier_id='$id' 
 UNION ALL 
 SELECT  'Date Extension' AS TRANSACTION_Type,supp_name AS Passenger_Name,extended_Date as date,pnr AS 
 Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, net_amount AS Debit, 0 AS Credit, ''
 AS remarks from datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id
 =ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id INNER JOIN supplier ON supplier.supp_id
 = ticket.supp_id where ticket.supp_id='$id'
 UNION ALL 
 SELECT  'Refund' AS TRANSACTION_Type,supp_name AS Passenger_Name,DATE(refund_ticket.datetime) as date,pnr AS 
 Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, 0 AS Debit, net_refund AS Credit, '' 
 AS remarks from refund_ticket INNER JOIN ticket ON ticket.ticket = refund_ticket.ticket INNER JOIN airports ON 
 airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id INNER JOIN supplier
 ON supplier.supp_id = ticket.supp_id where ticket.supp_id='$id'
                
 ) baseTable
 Order By date, CASE
         WHEN  TRANSACTION_Type = 'Visa' THEN 1
         WHEN  TRANSACTION_Type = 'Ticket' THEN 2
         WHEN  TRANSACTION_Type = 'Hotel Reservation' THEN 3
         WHEN  TRANSACTION_Type = 'Car Reservation' THEN 4
         WHEN  TRANSACTION_Type = 'Date Extension' THEN 5
         WHEN  TRANSACTION_Type = 'Refund' THEN 6
         WHEN  TRANSACTION_Type = 'Payment' THEN 7
     END
 ; ";
$result=$conn->query($sql);
if($result->num_rows > 0) {
$total = 0;
$formatDate ="";
while($row = $result->fetch_assoc()) {		
$output .='<tr>';
if($row['TRANSACTION_Type'] == 'Payment')
{ 
  $formatDate = date_create($row['date']);
  $total = $total + $row['Credit'];
  $output .='<td style="background-color:gray; color:white;">'.$row['TRANSACTION_Type'].'</td>
                  <td colspan="1">Remarks:'.($row['remarks']).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                    <td colspan="3"></td>
                   <td>'.number_format($row['Debit']).'</td>
                   <td>'.number_format($row['Credit']).'</td>
                   <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Ticket')
{
  $formatDate = date_create($row['date']);
  $total = $total - $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td>'.$row['Identification'].'</td>
                  <td>'.$row['Orgin'].'</td>
                  <td>'.$row['Destination'].'</td>
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Hotel Reservation')
{
  $formatDate = date_create($row['date']);
  $total = $total - $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td colspan="2">'.$row['Identification'].'</td>
                  <td>'.$row['Destination'].'</td>
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Car Reservation')
{
  $formatDate = date_create($row['date']);
  $total = $total - $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td colspan="3">'.$row['Identification'].'</td>
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Date Extension')
{
  $formatDate = date_create($row['date']);
  $total = $total - $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td>'.$row['Identification'].'</td>
                  <td>'.$row['Orgin'].'</td>
                  <td>'.$row['Destination'].'</td>
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Refund')
{
  $formatDate = date_create($row['date']);
  $total = $total + $row['Credit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td>'.$row['Identification'].'</td>
                  <td>'.$row['Orgin'].'</td>
                  <td>'.$row['Destination'].'</td>
     
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Visa')
{
  $formatDate = date_create($row['date']);
  $total = $total - $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                   <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                   <td>'.date_format($formatDate,"d-M-Y").'</td>
                   <td colspan="3">'.$row['Identification'].'</td>
                  <td>'.number_format($row['Debit']).'</td>
                  <td>'.number_format($row['Credit']).'</td>
                  <td>'.number_format($total).'</td>';
}
$output .='<tr>';
}
}
}
$output .= '</table>';
 $output .='</div>';
$total_payable = 0;
if($total != 0)
{
$total_payable=number_format($total);
}
$output .='<div class="card-body float-left" style="margin-top:200px;">
<h1 id="todaycard"><b>Total Payable</b></h1>
<hr class="float-left" style="width:400px">
<h2><b> USD '. $total_payable .'</b></h2></div>';
return $output;
}
$message = '';
if(isset($_POST["action"]))
{
include('pdf.php');
$file_name = md5(rand()). '.pdf';
$html_code = '<link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap.min.css">';
$html_code .= fetch_customer_data();
$pdf = new pdf();
$pdf->load_html($html_code);
$pdf->render();
}

?>
<html>
<head><link rel="stylesheet" type="text/css" href="bootstrap-4.3.1-dist/css/bootstrap.min.css">
<script src="jasonday-printThis-0aa7434/printThis.js"></script>
<link rel="stylesheet" type="text/css" href="jasonday-printThis-0aa7434/assets/css/skeleton.css">
<link rel="stylesheet" type="text/css" href="jasonday-printThis-0aa7434/assets/css/normalize.css">
<title>Customer Ledger</title>

<style>
@media print { 
body {
  position: relative;
  }
.footer {
  position: absolute;
  bottom: 0;
  }
}

</style>
</head>
<body>
<button id="printbtn" onClick="closeNav();" style="margin-top:40px; margin-right:40px;; font-size:16px" class="btn btn-success col-2 float-right"><i class="fa fa-print"></i> print Ledger</button>
<br>
<form method="post">
    <input type="submit" name="action" class="btn btn-danger" value="PDF Send" />
   </form>
   <br />
<br>
<hr>
<div id="selector">
   <?php
         echo fetch_customer_data();
    ?>

 
</div>
 <script>
	  $('#printbtn').on("click", function () {
      $('#selector').printThis();
    });
</script>
</body>
</html>
