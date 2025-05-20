    /* Initialize Select2 for search purpose
       -- The first argument will recieve dropdown for intialization purpose
       -- The second argument set the placeholder
       -- The third argument redirect the page to server
       -- The foruth argument will pass identifier to the server
    */
    function search(dropdown, placeholder,url,serverLabel,dropdownParent){
         dropdown.select2({
            placeholder,
            minimumInputLength: 3,
            ajax: {
               url,
               dataType: 'json',
               delay: 250,
               data: function (params) {
                     return {
                        q: params.term, // search term
                        serverLabel // function to call on server
                     };
               },
               processResults: function (data) {
                        return {
                           results: $.map(data, function(obj) {
                              return { id: obj.id, text: obj.name };
                           })
                        };
               },
               cache: true,
               error: function(jqXHR, textStatus, errorThrown) {
                  var response = JSON.parse(jqXHR.responseText);
                  notify('Error!', response.error, 'error');
               }
            },
            dropdownParent
         })
    }
    
    