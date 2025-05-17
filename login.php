<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Selab Nadiry</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Selab Nadiry Travel And Tourism Portal" name="description" />
  <meta content="Selab Nadiry, Sntrips, Travel And Tourism, Dubai Travel And Tourism" name="keywords">
  <meta content="Mattiullah Nadiry" name="author" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="color_admin_v5.0/admin/template/assets/css/vendor.min.css" rel="stylesheet" />
  <link href="color_admin_v5.0/admin/template/assets/css/default/app.min.css" rel="stylesheet" />
  <link href="HoldOn/HoldOn.min.css" rel="stylesheet">
  <link href="pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="pnotify/dist/pnotify.brighttheme.css" rel="stylesheet">
  <link href="pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <style>
    .otp-container {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 25px;
      padding: 0 20px;
    }
    .otp-input {
      width: 50px;
      height: 50px;
      border: 2px solid #dfe3e8;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 600;
      text-align: center;
      background: #ffffff;
      color: #333;
      transition: all 0.3s ease;
    }
    .otp-input:focus {
      border-color: #ff5b57;
      box-shadow: 0 0 8px rgba(255, 91, 87, 0.4);
      outline: none;
    }
    .otp-input:not(:placeholder-shown) {
      border-color: #ff5b57;
    }
    .otp-label {
      text-align: center;
      color: #555;
      margin-bottom: 20px;
      font-size: 15px;
      font-weight: 500;
    }
    @media (max-width: 480px) {
      .otp-container {
        gap: 10px;
      }
      .otp-input {
        width: 45px;
        height: 45px;
      }
    }
  </style>
</head>
<body class='pace-top'>
<div id="app" class="app">
  <div class="login login-v2 fw-bold">
    <div class="login-cover">
      <div class="login-cover-img" style="background-image: url(loginbackground.jpg)" data-id="login-cover-image"></div>
      <div class="login-cover-bg"></div>
    </div>
    <div class="login-container">
      <div class="login-header">
        <div class="brand">
          <img src="assets/logo-white.png" height="50" alt="">
        </div>
        <div class="icon">
          <i class="fa fa-lock"></i>
        </div>
      </div>
      <div class="login-content">
        <form id="loginForm" method="POST">
          <div class="form-floating mb-20px" id="emailSection">
            <input type="email" id="email" class="form-control fs-13px h-45px border-0" placeholder="Email" required />
            <label for="email" class="d-flex align-items-center text-gray-600 fs-13px">Email</label>
            <button type="button" id="sendOtpBtn" onclick="sendOTP()" class="btn btn-red d-block w-100 h-45px btn-lg mt-3">Send OTP</button>
          </div>
          <div id="otpSection" style="display: none;">
            <p class="otp-label">Enter the 6-digit OTP sent to your email</p>
            <div class="otp-container">
              <input type="tel" maxlength="1" id="otp1" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
              <input type="tel" maxlength="1" id="otp2" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
              <input type="tel" maxlength="1" id="otp3" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
              <input type="tel" maxlength="1" id="otp4" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
              <input type="tel" maxlength="1" id="otp5" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
              <input type="tel" maxlength="1" id="otp6" class="otp-input" inputmode="numeric" pattern="[0-9]*" required />
            </div>
            <button type="button" id="loginBtn" onclick="verifyOTP()" class="btn btn-red d-block w-100 h-45px btn-lg">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <a href="javascript:;" class="btn btn-icon btn-circle btn-red btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="HoldOn/HoldOn.min.js"></script>
<script src="pnotify/dist/pnotify.js"></script>
<script src="pnotify/dist/pnotify.buttons.js"></script>
<script src="color_admin_v5.0/admin/template/assets/js/vendor.min.js"></script>
<script src="color_admin_v5.0/admin/template/assets/js/app.min.js"></script>
<script src="color_admin_v5.0/admin/template/assets/js/theme/default.min.js"></script>
<script src="color_admin_v5.0/admin/template/assets/js/demo/login-v2.demo.js"></script>

<script>
function notify(title, message, type) {
    new PNotify({
        title: title,
        text: message,
        type: type,
        icon: 'true',
        styling: 'brighttheme'
    });
}

$(document).ready(function() {
    $("#email").on("keydown", function(e) {
        if (e.code === "Enter") {
            e.preventDefault();
            sendOTP();
        }
    });

    // Handle OTP input navigation and auto-submit
    $('.otp-input').on('input', function(e) {
        if (this.value.length === 1 && e.inputType !== 'deleteContentBackward') {
            const nextInput = $(this).next('.otp-input');
            if (nextInput.length) {
                nextInput.focus();
            } else {
                // If last input, trigger verification
                verifyOTP();
            }
        }
    }).on('keydown', function(e) {
        if (e.key === 'Backspace' && !this.value) {
            $(this).prev('.otp-input').focus();
        }
        if (e.code === "Enter") {
            e.preventDefault();
            verifyOTP();
        }
    });

    // Auto-focus first OTP field when section appears
    $('#sendOtpBtn').click(function() {
        if ($('#otpSection').is(':visible')) {
            $('#otp1').focus();
        }
    });
});

function sendOTP() {
    var email = $('#email').val();
    if (!email) {
        notify('Validation Error!', "Email is required", 'error');
        return;
    }

    // Disable button and show loader
    $('#sendOtpBtn').prop('disabled', true);
    HoldOn.open({
        theme: 'sk-bounce',
        message: 'Sending OTP...'
    });

    $.ajax({
        type: "POST",
        url: "loginController.php",
        data: { Send_OTP: "send", Email: email },
        success: function(response) {
            console.log("Response:", response); // Debug
            // Hide loader
            HoldOn.close();
            
            if (response === "success") {
                notify('Success', "OTP generated successfully!", 'success');
                
                // For testing only: Display OTP from backend with timestamp to prevent caching
                $.ajax({
                    url: "showOTP.php?t=" + new Date().getTime(),
                    cache: false,
                    success: function(data) {
                        console.log("OTP data:", data); // Debug
                        if (data && data.trim() !== "") {
                            notify('Test Mode', "Your OTP is: " + data, 'info');
                        } else {
                            notify('Warning', "OTP not found in session", 'warning');
                        }
                    }
                });
                
                $('#emailSection').hide();
                $('#otpSection').show();
                $('#otp1').focus();
            } else {
                notify('Error', response, 'error');
                // Re-enable button on error
                $('#sendOtpBtn').prop('disabled', false);
            }
        },
        error: function(xhr, status, error) {
            // Hide loader and re-enable button on error
            HoldOn.close();
            $('#sendOtpBtn').prop('disabled', false);
            
            console.log("Error:", xhr.responseText); // Debug
            notify('Error', 'Failed to send OTP: ' + error, 'error');
        }
    });
}

function verifyOTP() {
    var email = $('#email').val();
    var otp = $('#otp1').val() + $('#otp2').val() + $('#otp3').val() + 
             $('#otp4').val() + $('#otp5').val() + $('#otp6').val();

    if (otp.length !== 6 || !/^\d{6}$/.test(otp)) {
        notify('Validation Error!', "Please enter a valid 6-digit OTP", 'error');
        return;
    }

    // Disable button and show loader
    $('#loginBtn').prop('disabled', true);
    HoldOn.open({
        theme: 'sk-bounce',
        message: 'Verifying OTP...'
    });

    $.ajax({
        type: "POST",
        url: "loginController.php",
        data: { Verify_OTP: "verify", Email: email, OTP: otp },
        success: function(response) {
            // Hide loader
            HoldOn.close();
            
            if (response === "success") {
                notify('Success', "Login successful!", 'success');
                window.location.href = "index.php";
            } else {
                notify('Error', response, 'error');
                // Re-enable button on error
                $('#loginBtn').prop('disabled', false);
            }
        },
        error: function() {
            // Hide loader and re-enable button on error
            HoldOn.close();
            $('#loginBtn').prop('disabled', false);
            
            notify('Error', 'Failed to verify OTP', 'error');
        }
    });
}
</script>
</body>
</html>