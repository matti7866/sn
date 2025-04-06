<?php
session_start();
if(!isset($_SESSION['user_id']))
{
	header('location:login.php');
}
include 'connection.php';
$sql = "SELECT permission.select,permission.update, permission.delete FROM `permission` WHERE role_id = :role_id AND page_name = 'Customer Ledger' ";
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
$sql9="select * from customer where customer_id='$id'";
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
if($row3['customer_name']  != "")
{
    $customer_name = ucwords(strtolower($row3['customer_name']));
}
if($row3['customer_email']  != "")
{
    $customer_email = $row3['customer_email'];
}
if($row3['customer_phone']  != "")
{
    $customer_phone = $row3['customer_phone'];
}
$output = '<img class="float-left rounded-circle" style=" width:120px; height:120px;" src="logoselab.png" /><b><h1 style="background:black; color:white;" class="text-center">Selab Nadiry Travel  & Tours Customer <span style="color:red"> Ledger</span></h2></b>
<div class="card" id="todaycard"><h3><b>Bill To: '. $customer_name . '</b></h3><h3><b>Email: '. $customer_email . '</b></h3><h3><b>Phone: '. $customer_phone . '</b></h3>';
$output .='
<table class="table table-striped table-bordered">
<b><tr style="background-color:black; color:red;">
    <th width="144">Transiction Type</th>
    <th width="364">Passenger Name</th>
    <th width="340">Date</th>
    <th width="140">Identification</th>
    <th width="110">Orgin</th>
    <th width="110">Destination</th>
    <th width="145">Debit</th>
      <th width="145">Credit</th>
<th width="145">Running Balance</th></b>
 </tr>';
 if(isset($_GET['id'])){
  include("connection.php");
  
 
 $sql = "SELECT * FROM (SELECT  'Ticket' AS TRANSACTION_Type,passenger_name AS Passenger_Name,ticket.datetime,DATE(datetime) as date,pnr AS Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, sale AS Debit, 0 AS Credit,'' AS remarks  FROM ticket INNER JOIN customer ON customer.customer_id=ticket.customer_id INNER JOIN supplier ON supplier.supp_id=ticket.supp_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.customer_id='$id'

 UNION ALL
 SELECT 'Visa'  AS TRANSACTION_Type,passenger_name AS Passenger_Name,visa.datetime,DATE(datetime) as date,country_names AS Identification, '' AS Orgin, '' AS Destination, sale AS Debit, 0 AS Credit,'' AS remarks FROM visa INNER JOIN customer ON customer.customer_id=visa.customer_id INNER JOIN country_name ON country_name.country_id=visa.country_id INNER JOIN supplier ON supplier.supp_id=visa.supp_id INNER JOIN staff ON staff.staff_id=visa.staff_id where visa.customer_id='$id'
 UNION ALL 
 SELECT  'Payment' AS TRANSACTION_Type, '' AS Passenger_Name,customer_payments.datetime,DATE(datetime) as date,'' AS Identification,'' AS Orgin, '' AS Destination, 0 AS Debit, IFNULL(payment_amount,0) AS Credit, remarks AS remarks
 from customer_payments where customer_id='$id'
 UNION ALL 
 SELECT  'Hotel Reservation' AS TRANSACTION_Type, CONCAT('Hotel: ',hotel_name) AS Passenger_Name,hotel.datetime,DATE(datetime) as date,'' AS Identification,'' AS Orgin, country_names AS Destination, sale_price AS Debit, 0 AS Credit, '' AS remarks
 from hotel INNER JOIN country_name ON country_name.country_id = hotel.country_id where customer_id='$id'
 UNION ALL 
 SELECT  'Car Reservation' AS TRANSACTION_Type, CONCAT('Car Description: ',car_description) AS Passenger_Name,car_rental.datetime,DATE(datetime) as date,'' AS Identification,'' AS Orgin, '' AS Destination, sale_price AS Debit, 0 AS Credit, '' AS remarks
 from car_rental where customer_id='$id' 
 UNION ALL 
 SELECT  'Date Extension' AS TRANSACTION_Type,passenger_name AS Passenger_Name,datechange.datetime,extended_Date as date,pnr AS Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, sale_amount AS Debit, 0 AS Credit, '' AS remarks
 from datechange INNER JOIN ticket ON ticket.ticket = datechange.ticket_id INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.customer_id='$id'
 UNION ALL 
 SELECT  'Loan' AS TRANSACTION_Type, remarks AS Passenger_Name,loan.datetime,DATE(datetime) as date,'' AS Identification,'' AS Orgin, '' AS Destination, amount AS Debit, 0 AS Credit, CASE WHEN remarks !='' THEN remarks ELSE '' END  AS remarks
 from loan INNER JOIN customer ON customer.customer_id = loan.customer_id  where loan.customer_id='$id'
 UNION ALL 
 SELECT  'Refund' AS TRANSACTION_Type,passenger_name AS Passenger_Name,refund_ticket.datetime,DATE(refund_ticket.datetime) as date,pnr AS Identification,airports.airport_code AS Orgin, to_airports.airport_code AS Destination, 0 AS Debit, return_refund AS Credit, '' AS remarks
 from refund_ticket INNER JOIN ticket ON ticket.ticket = refund_ticket.ticket INNER JOIN airports ON airports.airport_id=ticket.from_id INNER JOIN airports AS to_airports ON to_airports.airport_id=ticket.to_id where ticket.customer_id='$id'
                
 ) baseTable
 Order By datetime
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
  $total = $total - $row['Credit'];
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
  $total = $total + $row['Debit'];
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
  $total = $total + $row['Debit'];
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
  $total = $total + $row['Debit'];
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
  $total = $total + $row['Debit'];
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
  $total = $total - $row['Credit'];
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
else if($row['TRANSACTION_Type'] == 'Loan')
{
  $formatDate = date_create($row['date']);
  $total = $total + $row['Debit'];
  $output .='<td>'.$row['TRANSACTION_Type'].'</td>
                  <td>'.ucwords(strtolower($row['Passenger_Name'])).'</td>
                  <td>'.date_format($formatDate,"d-M-Y").'</td>
                  <td colspan="3">'.$row['Identification'].'</td>
                 <td>'.number_format($row['Debit']).'</td>
                 <td>'.number_format($row['Credit']).'</td>
                 <td>'.number_format($total).'</td>';
}
else if($row['TRANSACTION_Type'] == 'Visa')
{
  $formatDate = date_create($row['date']);
  $total = $total + $row['Debit'];
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
