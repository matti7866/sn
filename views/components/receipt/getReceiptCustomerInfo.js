function GetReceiptCustomerInfo(url, ID,displayReceiptNumberArea,displayCustomerNameArea, displayReceiptDateArea,
    displayReceiptCurArea,displayReceiptCustomerIDArea,displayCurIDArea){
        return new Promise(function(resolve, reject) {
    $.ajax({
        type: "POST",
        url,  
        data: {
            GetReceiptCustomerInfo:"GetReceiptCustomerInfo",
            ID
        },
        success: function (response) {  
            var report = JSON.parse(response);
            displayReceiptNumberArea.text(report[0].invoiceNumber);
            displayCustomerNameArea.text(report[0].customer_name);
            displayReceiptDateArea.text(report[0].invoiceDate);
            displayReceiptCurArea.text(report[0].currencyName);
            displayReceiptCustomerIDArea.val(report[0].customerID);
            displayCurIDArea.val(report[0].invoiceCurrency);
            resolve(); // Resolve the promise
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response = JSON.parse(jqXHR.responseText);
            notify('Error!', response.error, 'error');
            reject(); // Reject the promise
        }
    });
    });
}