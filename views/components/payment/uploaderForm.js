// creating upload form
function UploaderComponent(){
           $('#uploaderArea').append(`<form style="display:none"  method="post" enctype="multipart/form-data" id="uploaderFrm" >
                <input type="file" name="uploaderFile" id="uploaderFile" />
                <input type="text" name="FileID" id="FileID" />
                <button type="submit" id="submitUploadForm" >Call</button>
                </form>`);
}
// open upload modal
function uploadFile(fileID){
    $('#FileID').val(fileID);
    $('#uploaderFile').click();
}

