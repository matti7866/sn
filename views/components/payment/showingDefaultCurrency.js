// The function will show the default currency of the account by getting account id
function getCurrencyByAccountID(AccountID,url,displayDropDown, dropdownParent){
    $.ajax({
        type: "POST",
        url,  
        data: {
            GetCurrencyByAccID:"GetCurrencyByAccID",
            AccountID:AccountID,
      },
      success: function (response) {  
          var data = JSON.parse(response);
          displayDropDown.attr('disabled','disabled');
          displayDropDown.empty();
          for(var i=0; i<data.length; i++){
            displayDropDown.append("<option value='"+ data[i].currencyID +"'>"+ data[i].currencyName +"</option>");
          }
          displayDropDown.select2({
            dropdownParent
          });
          
      },
      error: function(jqXHR, textStatus, errorThrown) {
        var response = JSON.parse(jqXHR.responseText);
        notify('Error!', response.error, 'error');
      }
    });
}