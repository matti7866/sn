// checking file ID 
function checkFileID(fileID){
    if(fileID.val() === "" || fileID.val() === "undefined" || fileID.val() < 1 || fileID.val() === null ){
        notify('Validation Error!', 'Cant upload somethig went wrong! try again.', 'error');
        return;
    }
}

// check file uploader is not empty
function checkFileUploader(fileUploader){
    if(fileUploader.val() === '' || fileUploader.val() === "undefined" || fileUploader.val() === null){
        notify('Error!', 'Please select a file to be uploaed', 'error');
        return;
    }
}

// check file size
 function checkFileSize(fileUploader){
    if(fileUploader[0].files[0].size > 2097152){
        notify('Error!', 'File size is greater than 2 MB. Make Sure It should be less than 2 MB ', 'error');
        return;
    }
 }

// check file extension
function checkFileExtension(fileUploader){
    var allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    var fileExtension = fileUploader.val().split('.').pop().toLowerCase();
    if ($.inArray(fileExtension, allowedExtensions) === -1){
        notify('Error!', 'Invalid file type. Only ' + allowedExtensions.join(', ') + ' files are allowed.', 'error');
        return;
    } 
}
