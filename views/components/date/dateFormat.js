/* the function will format the date to Duabi local date and return the formated date in order to show in bootstrap datepicker by
default */
function localDateFormat(){
    // Get the current date in the UAE time zone
    var now = new Date();
    var uaeOptions = { timeZone: 'Asia/Dubai', day: '2-digit', month: '2-digit', year: 'numeric' };
    var uaeDate = now.toLocaleDateString('en-US', uaeOptions);
    // Split the date into day, month, and year
    var dateParts = uaeDate.split('/');
    var month = dateParts[0];
    var day = dateParts[1];
    var year = dateParts[2];
    // Format the date as 'MM-DD-YYYY'
    return  day + '-' + month + '-' + year;
}


// the function will initalize the bootstrap datepicker and format it to dd-mm-yyyy and it will show the current date of Dubai
function formatDate(d){
    d.datepicker({
        format: 'dd-mm-yyyy',
        autoclose:true,
    });
    // Set datepicker default value to today's date
    d.datepicker('setDate', localDateFormat());
}