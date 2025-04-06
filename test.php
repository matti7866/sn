<?php

include 'phpMailer/PHPMailer.php';
include 'phpMailer/SMTP.php';
include 'phpMailer/Exception.php';

// Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Create instance of phpmailer
$mail = new PHPMailer(true);
// Set mailer to use SMTP
try{
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
    $mail->isSMTP();    
    // define smtp host
    $mail->Host = "smtp.domain.com";
    // Enable smtp authentication 
    $mail->SMTPAuth = "true";
    //USername
    $mail->Username = "test@sntrips.com";
    // Password
    $mail->Password = "Test@123";
    //Enable implicit TLS encryption
    $mail->SMTPSecure = 'tls';  
    // set port  to connect SMTP
    $mail->Port = "587";
    //Recipients
    $mail->setFrom('test@sntrips.com');
    $mail->addAddress('gen.sayedmahbobi@gmail.com');     //Add a recipient
    $mail->isHTML(true); 
// Subject

$mail->Subject = "NEw Email";
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
// Message Body
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


// send mail
if($mail->Send()){
    echo "Success";
}

$mail->smtpClose();

// Close connection
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
