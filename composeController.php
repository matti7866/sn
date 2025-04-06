<?php
    session_start();
    if(!isset($_SESSION['user_id'])){
        header('location:login.php');
    }
    include 'connection.php';
    include 'phpMailer/PHPMailer.php';
    include 'phpMailer/SMTP.php';
    include 'phpMailer/Exception.php';
    // Define name spaces
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    try {
        $selectQuery = $pdo->prepare("SELECT `staff_web_Email`,`staff_web_password` FROM staff WHERE 
        staff_id = :staff_id");
        $selectQuery->bindParam(':staff_id', $_SESSION['user_id']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $cridentail = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        if($cridentail){
            $dRecipient  = json_decode($_POST['Recipient']);
            $dCcrecipient  = json_decode($_POST['Ccrecipient']);
            $dBccrecipient  = json_decode($_POST['Bccrecipient']);
            $mail = new PHPMailer(true);
            // Set mailer to use SMTP
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;   
            $mail->isSMTP();    
            // define smtp host
            $mail->Host = "smtp.domain.com";
            // Enable smtp authentication 
            $mail->SMTPAuth = "true";
            //USername
            $mail->Username = $cridentail[0]['staff_web_Email'];
            // Password
            $mail->Password = $cridentail[0]['staff_web_password'];
            //Enable implicit TLS encryption
            $mail->SMTPSecure = 'tls';  
            // set port  to connect SMTP
            $mail->Port = "587";
            //Recipients
            $mail->setFrom($cridentail[0]['staff_web_Email']);
            for($indexOfRecipient=0;$indexOfRecipient < count($dRecipient); $indexOfRecipient++){
                $mail->addAddress($dRecipient[$indexOfRecipient]);     //Add recipients
            }
            for($indexOfccRecipient=0;$indexOfccRecipient < count($dCcrecipient); $indexOfccRecipient++){
                $mail->addCC($dCcrecipient[$indexOfccRecipient]);     //Add Cc recipients
            }
            for($indexOfBccRecipient=0;$indexOfBccRecipient < count($dBccrecipient); $indexOfBccRecipient++){
                $mail->addBCC($dBccrecipient[$indexOfBccRecipient]);     //Add Bcc recipients
            }
             //Count total files
             if(isset($_FILES['files']) && $_FILES['files'] != ''){
                $countfiles = count($_FILES['files']['name']);
            
            for($index = 0;$index < $countfiles;$index++){
                if(isset($_FILES['files']['name'][$index]) && $_FILES['files']['name'][$index] != ''){
                    // File name
                    $filename = $_FILES['files']['name'][$index];
                    // Get extension
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    // Valid image extension
                    $valid_ext = array("png","jpeg","jpg","doc","docx",'pdf','zip','rar','txt','xls','ppt','tar');
                    // Check extension
                    if(in_array($ext, $valid_ext)){
                        //Attachments
                        $mail->addAttachment($filename); //Add attachments
                    }
                }
            }
            }
            $mail->isHTML(true); 
            // Subject
            $mail->Subject = $_POST['EmailSubject'];
            $mail->Body    = $_POST['MesgBody'];
            // send mail
            if($mail->Send()){
                
                echo 'Success';
                $mail->smtpClose();
            }else{
                
                echo 'Error';
                $mail->smtpClose();
            }
            // Close connection
        }
    } catch (phpmailerException $e) {
        echo 'Error'; 
        $mail->smtpClose();
    }
?>