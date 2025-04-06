function generateCustomerPaymentReceipt(paymentID,url){
    $('#reportCustomerPaymentBtn' + paymentID).attr('disabled', true);
    $.ajax({
        type: "POST",
        url,  
        data: {
            GeneratePaymentReceipt:'GeneratePaymentReceipt',
            PaymentID:paymentID
        },
        success: function (response) {
            var data = JSON.parse(response);
            if(data.message.includes("Success")){
                $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
                let getResponseStr = data.message.split("&");
                //The get the id generated
                let generatedID = getResponseStr[1];
                window.location.href = '../receipt/receiptDetails.php?rcptID=' + generatedID;

            }else{
                notify('Error!', data.error, 'error');
                $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response = JSON.parse(jqXHR.responseText);
            notify('Error!', response.error, 'error');
            $('#reportCustomerPaymentBtn' + paymentID).attr("disabled", false);
        }
    });
}