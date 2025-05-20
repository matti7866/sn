/* fetch the exchange rate from the server
    The function will get the url to which is should request,
    The second argument is the currency dropdown id in order to get the exchange rate in USD
    The Thired argument is the original amount that the customer pays 
    The forth argument is the place where we want to show the exchange rate after api call
    The fifth argument is showing the exchange rate * amount in order to show the equivalent in USD
    The sisxth argument is decision flag whether to show the exchange rate or not because in Case of USD there is no need for exchange
*/
function getExchangeRate(url,currenyDropDown, originalAmount,displayExchangeResult,displayAmountResult, displayExchangeRateArea){
    // get the currrency name
    var currencyName =  currenyDropDown.find('option:selected').text();
    // get the currency id
    var currencyID = currenyDropDown.find('option:selected').val();
    // check if currency name is USD
    if(currencyName === "USD"){
        // if it is then there is no need to show currency exchange as USD to USD conversion makes no sense
        displayExchangeRateArea.addClass('d-none');
        return;
    }
    // if the currency is not USD then send ajax call to get the exchange rate
    $.ajax({
        type: "POST",
        url,  
        data: {
            GetExchangeRate:"GetExchangeRate",
            CurrencyID:currencyID
      },
      success: function (response) {  
       
          var data = JSON.parse(response);
          console.log(data);
          displayExchangeResult.empty();
          displayAmountResult.empty();
          displayExchangeResult.val(data.exchange_rate);
          displayAmountResult.val(originalAmount * data.exchange_rate);
      },
    });
}