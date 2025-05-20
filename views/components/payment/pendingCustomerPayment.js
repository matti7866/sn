// get total payment by customer
function getPendingCustomerPayment(customerID, url, displaySection){
    $.ajax({
        type: "POST",
        url,  
        data: {
            GetCustomerPendingPayment:'GetCustomerPendingPayment',
            CustomerID:customerID
        },
        success: function (response) {  
            var data = JSON.parse(response);
            displaySection.empty();
            if(data.length > 1){
              var finalTable = '';
              finalTable += '<div class="btn-group"><button type="button" class="btn btn-danger  dropdown-toggle" '+
              ' data-bs-toggle="dropdown" aria-expanded="false"> Total Pending Payments <i class="fa fa-caret-down" '+
              'aria-hidden="true"></i></button><ul class="dropdown-menu" id="total_charge" style="z-index:90000">';
              for(var i=0; i<data.length; i++){
                finalTable +='<li><a class="dropdown-item" href="#">'+ numeral(data[i].total).format('0,0') + " " +
                data[i].curName +'</a></li><li><hr class="dropdown-divider"></li>';
              }
              finalTable += '</ul></div>';
              displaySection.append(finalTable);
            }else if(data.length == 1){
                displaySection.append('<input type="text" value="'+ numeral(data[0].total).format('0,0') + " " +
                data[0].curName +'" class="form-control " disabled="disabled"></input>');
            }else{
              displaySection.append('<input type="text" value="No Pending Payment" class="form-control" disabled="disabled"></input>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
          var response = JSON.parse(jqXHR.responseText);
          notify('Error!', response.error, 'error');
        }
    });
}