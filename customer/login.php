<?php
session_start();
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400, '/', '.sntrips.com'); // Adjust domain if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Selab Nadiry Customer Portal</title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Selab Nadiry Travel And Tourism Portal" name="description" />
  <meta content="Selab Nadiry, Sntrips, Travel And Tourism, Dubai Travel And Tourism" name="keywords">
  <meta content="Mattiullah Nadiry" name="author" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="/color_admin_v5.0/admin/template/assets/css/vendor.min.css" rel="stylesheet" />
  <link href="/color_admin_v5.0/admin/template/assets/css/default/app.min.css" rel="stylesheet" />
  <link href="/HoldOn/HoldOn.min.css" rel="stylesheet">
  <link href="/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="/pnotify/dist/pnotify.brighttheme.css" rel="stylesheet">
  <link href="/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
</head>
<body class='pace-top'>
<div id="app" class="app">
  <div class="login login-v2 fw-bold">
    <div class="login-cover">
      <div class="login-cover-img" style="background-image: url(/color_admin_v5.0/admin/template/assets/img/login-bg/login-bg-18.jpg)" data-id="login-cover-image"></div>
      <div class="login-cover-bg"></div>
    </div>
    <div class="login-container">
      <div class="login-header">
        <div class="brand">
          <img src="/assets/logo-white.png" height="50" alt="">
        </div>
        <div class="icon">
          <i class="fa fa-lock"></i>
        </div>
      </div>
      <div class="login-content">
        <form id="loginForm" method="POST">
          <div class="form-floating mb-20px" id="emailSection">
            <input type="email" id="email" class="form-control fs-13px h-45px border-0" placeholder="Email" required />
            <label for="email" class="d-flex align-items-center text-gray-600 fs-13px">Customer Email</label>
            <button type="button" id="sendOtpBtn" onclick="sendOTP()" class="btn btn-red d-block w-100 h-45px btn-lg mt-3" disabled>Send OTP</button>
          </div>
          <div id="otpSection" style="display: none;">
            <p>Enter the 6-digit OTP sent to your email:</p>
            <div class="form-floating mb-20px">
              <input type="text" id="otp" class="form-control fs-13px h-45px border-0" placeholder="Enter OTP" maxlength="6" pattern="\d{6}" required />
              <label for="otp" class="d-flex align-items-center text-gray-600 fs-13px">OTP</label>
            </div>
            <button type="button" id="loginBtn" onclick="verifyOTP()" class="btn btn-red d-block w-100 h-45px btn-lg" disabled>Login</button>
            <p class="mt-2"><a href="#" onclick="resendOTP()" id="resendLink" class="text-muted">Resend OTP</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
  <a href="javascript:;" class="btn btn-icon btn-circle btn-red btn-scroll-to-top" data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/HoldOn/HoldOn.min.js"></script>
<script src="/pnotify/dist/pnotify.js"></script>
<script src="/pnotify/dist/pnotify.buttons.js"></script>
<script src="/color_admin_v5.0/admin/template/assets/js/vendor.min.js"></script>
<script src="/color_admin_v5.0/admin/template/assets/js/app.min.js"></script>
<script src="/color_admin_v5.0/admin/template/assets/js/theme/default.min.js"></script>
<script src="/color_admin_v5.0/admin/template/assets/js/demo/login-v2.demo.js"></script>

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
    $("#email").on("input", function() {
        $(this).val() && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($(this).val()) 
            ? $("#sendOtpBtn").prop("disabled", false) 
            : $("#sendOtpBtn").prop("disabled", true);
    });

    $("#email").on("keydown", function(e) {
        if (e.code === "Enter" && !$("#sendOtpBtn").prop("disabled")) {
            e.preventDefault();
            sendOTP();
        }
    });

    $("#otp").on("input", function() {
        $(this).val().length === 6 && /^\d{6}$/.test($(this).val()) 
            ? $("#loginBtn").prop("disabled", false) 
            : $("#loginBtn").prop("disabled", true);
    });

    $("#otp").on("keydown", function(e) {
        if (e.code === "Enter" && !$("#loginBtn").prop("disabled")) {
            e.preventDefault();
            verifyOTP();
        }
    });
});

function sendOTP() {
    var email = $("#email").val();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        notify('Validation Error!', "Please enter a valid email", 'error');
        return;
    }

    $("#sendOtpBtn").prop("disabled", true).text("Sending...");
    $.ajax({
        type: "POST",
        url: "/customer/loginController.php", // Adjust path
        data: { Send_OTP: "send", Email: email },
        dataType: "json",
        success: function(response) {
            $("#sendOtpBtn").prop("disabled", false).text("Send OTP");
            if (response.status === "success") {
                notify('Success', "OTP sent to your email!", 'success');
                $("#emailSection").hide();
                $("#otpSection").show();
                $("#otp").focus();
            } else {
                notify('Error', response.error || 'Failed to send OTP', 'error');
            }
        },
        error: function(xhr, status, error) {
            $("#sendOtpBtn").prop("disabled", false).text("Send OTP");
            notify('Error', xhr.responseJSON?.error || 'Failed to send OTP: ' + error, 'error');
        }
    });
}

function verifyOTP() {
    var email = $("#email").val();
    var otp = $("#otp").val();

    if (!otp || !/^\d{6}$/.test(otp)) {
        notify('Validation Error!', "Please enter a valid 6-digit OTP", 'error');
        return;
    }

    $("#loginBtn").prop("disabled", true).text("Verifying...");
    $.ajax({
        type: "POST",
        url: "/customer/loginController.php", // Adjust path
        data: { Verify_OTP: "verify", Email: email, OTP: otp },
        dataType: "json",
        success: function(response) {
            $("#loginBtn").prop("disabled", false).text("Login");
            if (response.status === "success") {
                notify('Success', "Login successful!", 'success');
                window.location.href = "/customer/customer_dashboard.php";
            } else {
                notify('Error', response.error || 'Failed to verify OTP', 'error');
            }
        },
        error: function(xhr, status, error) {
            $("#loginBtn").prop("disabled", false).text("Login");
            notify('Error', xhr.responseJSON?.error || 'Failed to verify OTP: ' + error, 'error');
        }
    });
}

function resendOTP() {
    $("#resendLink").addClass("disabled").text("Resending...");
    sendOTP();
    setTimeout(() => {
        $("#resendLink").removeClass("disabled").text("Resend OTP");
    }, 2000);
}
</script>
</body>
</html>