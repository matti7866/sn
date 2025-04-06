<?php
include 'phpMailer/PHPMailer.php';
include 'phpMailer/SMTP.php';
include 'phpMailer/Exception.php';
// Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
        
            $message = "Respected Sir/Madam, <br/> <br/>

            I hope this email finds you well. My name is Sayed Yousuf Mahbobi and I have been admitted to the Master's program in Informatica at the University of Milan, and classes started on September 26th . <br/><br/>
           Despite of tireless efforts, I have been unable to secure an appointment with VisaMetric. Missing any more classes will have a significant impact on my academic career and my merit scholarship, as these classes are conducted physically in laboratory settings.
<br/><br/>
Given the urgency of my situation, I kindly request an appointment with VisaMetric to facilitate the document collection process. Thanks in advanced.
<br/><br/>
           Name: Sayed Yousuf <br/>
           Surname: Mahbobi <br/>
           Email:rayanguzman345@gmail.com <br/>
           Phone number: 09054019302 <br/>
           Passport Number: P02740615 <br/>
           University in Italy: University of Milan <br/>
           Chosen Course: Informatica <br/>
           Application ID Number of the UniversItaly summary:  229632 <br/>
           Start date of lessons: 26 of september <br/>
           Card Number: 6362141076698670 <br/>
           IBAN Number: IR350620000000200167676007 <br/>
           Date of payment: 1402/07/14 <br/>
           Name of Cardholder: صدیقه سعیدی خادم <br/>
            ";
            $mail = new PHPMailer(true);
            // Set mailer to use SMTP
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;   
            $mail->isSMTP();    
            // define smtp host
            $mail->Host = "smtp.domain.com";
            // Enable smtp authentication 
            $mail->SMTPAuth = "true";
            //USername
            $mail->Username = 'omaid@smartdubaivisa.com';
            // Password
            $mail->Password = "Shekibem@1998";
            //Enable implicit TLS encryption
            $mail->SMTPSecure = 'tls';  
            // set port  to connect SMTP
            $mail->Port = "587";
            //Recipients
            $mail->setFrom('omaid@smartdubaivisa.com');
            $mail->addAddress('rayanguzman345@gmail.com');     //Add a recipient
            $mail->AddBCC('ryanguzman369@yahoo.com');   // Add BCC for sending to multiple people
            $mail->isHTML(true); 
            // Subject
            $mail->Subject = "Appointment Request, Class started on 26th of September";
            $mail->Body    =  $message;
            // Message Body
            $mail->AltBody = $message;
            // send mail
            $mail->Send();
            $mail->smtpClose();
            // Close connection
         



