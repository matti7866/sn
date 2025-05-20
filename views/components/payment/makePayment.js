// make payment
function makePayment(customerID,paymentAmount,accountID,currencyID, btn, url,Modal,  callBackFun,totalChargeDiv,remarks){ 
    if(customerID.val()  === null){
        notify('Validation Error!', "Customer is required", 'error');
        return;
    }
    if(isNaN(parseInt(paymentAmount.val())) || parseInt(paymentAmount.val()) < 1) {
        notify('Validation Error!', "Payment amount must be a valid number", 'error');
        return;
    }
    if(accountID.val() === null){
        notify('Validation Error!', "Account is required", 'error');
        return;
    }
    if(accountID.select2('data')[0].text === "Cash" && currencyID.val() === null){
        notify('Validation Error!', "Currency is required", 'error');
        return;
    }
    btn.attr("disabled", true);

    $.ajax({
        type: "POST",
        url,  
        data: {
            MakePayment:'MakePayment',
            CustomerID: customerID.val(),
            AccountID:accountID.val(),
            PaymentAmount : paymentAmount.val(),
            CurrencyID:currencyID.val(),
            Remarks:remarks.val()
        },
        success: function (response) {
            var data = JSON.parse(response);
            if(data.message == "Success"){
                notify('Success!', 'Record successfully added.', 'success');
                paymentAmount.val('');
                customerID.val(-1).trigger('change.select2');
                accountID.val(-1).trigger('change.select2');
                totalChargeDiv.addClass('d-none');
                $('#currencySection').addClass('d-none');
                $('#accountSection').addClass('col-sm-8');
                remarks.val('');
                Modal.modal('hide');
                callBackFun();
                btn.attr("disabled", false);
            }else{
                notify('Error!', data.error, 'error');
                btn.attr("disabled", false);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var response = JSON.parse(jqXHR.responseText);
            notify('Error!', response.error, 'error');
            btn.attr("disabled", false);
        }
    });
}