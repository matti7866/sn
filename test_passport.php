<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passport MRZ Test (Mindee)</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Test Passport MRZ Extraction (Mindee)</h1>
    <form id="passportForm" enctype="multipart/form-data">
        <label for="passportFile">Upload Passport Image:</label>
        <input type="file" id="passportFile" name="passportFile" accept="image/jpeg,image/png">
    </form>
    <div id="result"></div>

    <script>
        $(document).ready(function() {
            $('#passportFile').on('change', function() {
                var fileInput = this;
                if (!fileInput.files[0]) {
                    alert('Please select a passport image!');
                    return;
                }

                var formData = new FormData();
                formData.append('action', 'ExtractPassportData');
                formData.append('passportFile', fileInput.files[0]);

                $.ajax({
                    type: 'POST',
                    url: 'test_controller.php',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        console.log('Raw response:', response);
                        // No need for JSON.parse() since jQuery already parsed it
                        if (response.status === 'success') {
                            console.log('Extracted Data:', response.data);
                            $('#result').html(
                                'Passenger Name: ' + (response.data.passengerName || 'N/A') + '<br>' +
                                'Passport Number: ' + (response.data.passportNumber || 'N/A') + '<br>' +
                                'Expiry Date: ' + (response.data.passportExpiryDate || 'N/A') + '<br>' +
                                'Date of Birth: ' + (response.data.dob || 'N/A') + '<br>' +
                                'Nationality: ' + (response.data.nationality || 'N/A')
                            );
                            alert('Success: ' + response.message);
                        } else {
                            alert('Error: ' + response.message);
                            fileInput.value = '';
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
                        alert('Failed to process file: ' + error + ' - Response: ' + xhr.responseText);
                        fileInput.value = '';
                    }
                });
            });
        });
    </script>
</body>
</html>