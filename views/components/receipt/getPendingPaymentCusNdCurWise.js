// get remaining balance of customer based on specific currency

function getTotalRemainingPaymentCusAndCurWise(url,customerID, currencyID, displayOutstandingBalanceArea){
    if(parseInt(customerID.val()) < 1  || customerID.val() == "" || customerID.val()== null || customerID.val() == "undefined" ||
    parseInt(currencyID.val()) < 1  || currencyID.val() == "" ||  currencyID.val() == null ||  currencyID.val()== "undefined" ){
        notify('Error', 'Something went wrong. Please refresh page', 'error');
        return;
    }
  $.ajax({
      type: "POST",
      url,
      data: {
        GetTotalRemainingPaymentCusAndCurWise:"GetTotalRemainingPaymentCusAndCurWise",
        CurID: currencyID.val(),
        CustomerID: customerID.val()
      },
      success: function (response) {  
        var report = JSON.parse(response);
        displayOutstandingBalanceArea.text(numeral(report).format('0,0'));
      },
      error: function(jqXHR, textStatus, errorThrown) {
        var response = JSON.parse(jqXHR.responseText);
        notify('Error!', response.error, 'error');
    }
  });
}