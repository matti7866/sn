<?php
  include 'header.php';
?>
<title>Customer Receipt Generator Form</title>
<?php
  include 'nav.php';
  if(!isset($_SESSION['user_id']))
  {
	  header('location:login.php');
  }
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <h1>Select Records To Generate Receipt <i class="fa fa-arrow-down text-danger"></i></h1>
                        </div>
                        <div class="col-lg-4">
                            <button type="button" id="GenerateReceipt" onclick="GenerateReceipt()" class="btn btn-danger pull-right disabled">Generate Receipt</button>
                            <button type="button"  onclick="viewReceipts()" class="btn btn-info pull-right"><i class="fa fa-eye"></i> View Receipt</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive ">
                            <table id="myTable"  class="table  table-striped table-hover table-bordered ">
                                <thead>
                                    <tr id="ad" class="bg-danger text-white">
                                        <th style="-webkit-print-color-adjust: exact;">S#</th>
                                        <th style="-webkit-print-color-adjust: exact;">Select</th>
                                        <th style="-webkit-print-color-adjust: exact;">Transaction Type</th>
                                        <th style="-webkit-print-color-adjust: exact;">Passenger Name</th>
                                        <th style="-webkit-print-color-adjust: exact;" >Date</th>
                                        <th style="-webkit-print-color-adjust: exact;">Identification</th>
                                        <th style="-webkit-print-color-adjust: exact;" >Orgin</th>
                                        <th style="-webkit-print-color-adjust: exact;">Destination</th>
                                        <th style="-webkit-print-color-adjust: exact;">Debit</th>
                                        <th style="-webkit-print-color-adjust: exact;">Credit</th>
                                    </tr>
                                </thead>
                                <tbody id="TicketReportTbl">
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    

<?php include 'footer.php'; ?>
<script src="Numeral-js-master/numeral.js"></script>
<script>
$(document).ready(function(){
    getTicketReport();
});  
function getTicketReport(){
    let urlFirstParam = location.search.split('&')[0];
    let id = urlFirstParam.split('=')[1];
    let curID = location.search.split('&curID=')[1];
    // var urlThiredParam = location.search.split('&fromdate=')[1];
    // var fromdate = urlThiredParam.split('&todate=')[0];
    // var todate = location.search.split('&todate=')[1];
    const getTicketReport = "getTicketReport";
      $.ajax({
          type: "POST",
          url: "receiptController.php",  
          data: {
            GetTicketReport:getTicketReport,
            ID:id,
            CurID:curID,
          },
          success: function (response) {  
            var report = JSON.parse(response);
            $('#TicketReportTbl').empty();
            var j = 1;
            var finalTable = "";
            var remaining = 0;
            var total = 0;
            var totalRefund = 0;
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].TRANSACTION_Type == "Payment"){
                    remaining = remaining - parseInt(report[i].Credit);
                    customerTotalPaid += parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize customAbc' style='-webkit-print-color-adjust: exact;'>"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' style='-webkit-print-color-adjust: exact;' colspan='1'>Remarks:"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+ report[i].Identification + "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Ticket"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td style='-webkit-print-color-adjust: exact;' class='text-capitalize' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Hotel Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='2' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Car Reservation"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Date Extension"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Refund"){
                    totalRefund +=  parseInt(report[i].Credit);
                    total += parseInt(report[i].Debit);
                    remaining = remaining - parseInt(report[i].Credit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>:"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Orgin+"</td><td style='-webkit-print-color-adjust: exact;'>"+report[i].Destination+
                    "</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Loan"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Visa"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td></tr>";
                }else if(report[i].TRANSACTION_Type == "Visa Fine"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Report"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Escape Removal"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Residence"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else if(report[i].TRANSACTION_Type == "Residence Fine"){
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }else{
                    total += parseInt(report[i].Debit);
                    remaining = remaining + parseInt(report[i].Debit);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td><div class='form-check'><input class='form-check-input' type='checkbox' id='"+report[i].TRANSACTION_Type+"&" + report[i].refID +"' /></div></td><td class='text-capitalize' style='-webkit-print-color-adjust: exact;' "+
                    ">"+ report[i].TRANSACTION_Type +"</td>"+
                    "<td class='text-capitalize' colspan='1' style='-webkit-print-color-adjust: exact;'>"+ report[i].Passenger_Name +"</td><td style='-webkit-print-color-adjust: exact;'>"+ report[i].date +
                    "</td><td colspan='3' style='-webkit-print-color-adjust: exact;'>"+report[i].Identification+"</td><td style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].Debit).format('0,0') +"</td><td style='-webkit-print-color-adjust: exact;'>"+
                    numeral(report[i].Credit).format('0,0') +"</td>"+
                    "</tr>";
                }
                
                $('#TicketReportTbl').append(finalTable);
                j +=1;
            
              
              
            }
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const maxCheckboxes = 7;
            function handleCheckboxChange() {
                const numChecked = document.querySelectorAll('input[type="checkbox"]:checked').length;
                if(numChecked >=1){
                    $('#GenerateReceipt').removeClass('disabled');
                }else{
                    $('#GenerateReceipt').addClass('disabled');
                }
                if (numChecked >= maxCheckboxes) {
                    // Disable all un-checked checkboxes
                    checkboxes.forEach((checkbox) => {
                    if (!checkbox.checked) {
                        checkbox.disabled = true;
                    }
                    });
                } else {
                    // Enable all checkboxes
                    checkboxes.forEach((checkbox) => {
                    checkbox.disabled = false;
                    });
                }
            }
            // Add event listener to each checkbox
            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });
          },
      });
    } 
    function GenerateReceipt(){
        const saveReceiptInfo = "saveReceiptInfo";
        let urlFirstParam = location.search.split('&')[0];
        let id = urlFirstParam.split('=')[1];
        let curID = location.search.split('&curID=')[1];
        // Declare an empty associative array
        let receiptArr = [];
        const getCheckedBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        getCheckedBoxes.forEach((ChkCheckBox) => {
            let str = ChkCheckBox.id;
            // Split the string at the "&" character
            let parts = str.split("&");
            // // The first part 
            let trType = parts[0];
            // // The second part
            let trID = parts[1];
            // // Create an object with the "ID" and "Type" properties
            let ReceiptObject = {
               id: trID,
               type: trType
            };
            // // Push the object into the associative array
            receiptArr.push(ReceiptObject);
            
        });
        $.ajax({
          type: "POST",
          url: "receiptController.php",  
          data: {
            SaveReceiptInfo:saveReceiptInfo,
            ID:id,
            ReceiptArr: JSON.stringify(receiptArr),
            CurID:curID
          },
          success: function (response) {  
            if (response.includes("Success")){
                let getResponseStr = response.split("&");
                //The get the id generated
                let generatedID = getResponseStr[1];
                window.location.href = 'receiptDetails.php?rcptID=' + generatedID;
                
            }else{
                notify('Error!', response + ' ' + 'Refresh the page!' , 'error');
                console.log(response)
            }
          },
        });
       
    }
    function viewReceipts(){
        // Get the current URL
        let currentUrl = window.location.href;
        // Replace "receipt.php" with "abc.php"
        let newUrl = currentUrl.replace("receipt.php", "receiptReport.php");
        // Update the URL to the new one
        window.location.href = newUrl;
    }

</script>
</body>
</html>
