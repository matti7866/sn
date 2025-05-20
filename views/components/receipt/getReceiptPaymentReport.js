// get payment receipt detail report
function getReceiptPaymentReport(url, ID,displayArea, totalPaidArea){
   
    $.ajax({
        type: "POST",
        url,  
        data: {
            GetReceiptPaymentReport:"GetReceiptPaymentReport",
            ID
        },
        success: function (response) {  
            var report = JSON.parse(response);
            displayArea.empty();
            var j = 1;
            var finalTable = "";
            var customerTotalPaid = 0;
            for(var i=0; i<report.length; i++){
                if(report[i].transactionType == "Payment"){
                    customerTotalPaid += parseInt(report[i].salePrice);
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>" + report[i].transactionType +"</td>"+ "<td "+
                    "class='style='-webkit-print-color-adjust: exact;'>"+ report[i].serviceInfo +"</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+ report[i].PassengerName + "</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+report[i].formatedDate+"</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].salePrice).format('0,0') +"</td></tr>";
                }else{
                    finalTable = "<tr><td scope='row' style='-webkit-print-color-adjust: exact;'>"+ j + "</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+ report[i].transactionType +"</td><td "+
                    "class='style='-webkit-print-color-adjust: exact;'>"+ report[i].serviceInfo +"</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+ report[i].PassengerName + "</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+report[i].formatedDate+"</td><td "+
                    "style='-webkit-print-color-adjust: exact;'>"+ numeral(report[i].salePrice).format('0,0') +"</td></tr>";
                }
                displayArea.append(finalTable);
                j +=1; 
            }
            totalPaidArea.text(numeral(customerTotalPaid).format('0,0'));
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response = JSON.parse(jqXHR.responseText);
            notify('Error!', response.error, 'error');
            
        }
    });
    
}


