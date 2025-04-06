<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('location:login.php');
}
include 'connection.php';
if(isset($_POST['getEmailDetail'])){
        // Get Specific Staff
        $selectQuery = $pdo->prepare("SELECT `staff_web_Email`,`staff_web_password` FROM staff WHERE 
        staff_id = :staff_id");
        $selectQuery->bindParam(':staff_id', $_SESSION['user_id']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $cridentail = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
        // Close connection
        unset($pdo); 
        /* connect to gmail */
        $hostname = '{imap.domain.com:143/imap/tls}INBOX.Sent';
        $username = $cridentail[0]['staff_web_Email'];
        $password = $cridentail[0]['staff_web_password'];
        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
        /* grab emails */
        $emails = imap_search($inbox,'ALL');
        /* if emails are returned, cycle through each... */
        $output = '
                    <div class="mailbox-sidebar">
                        <div class="mailbox-sidebar-header d-flex justify-content-center">
                            <a href="#emailNav" data-bs-toggle="collapse" class="btn btn-inverse btn-sm me-auto d-block d-lg-none">
                                <i class="fa fa-cog"></i>
                            </a>
                            <a href="compose.php" class="btn btn-inverse ps-40px pe-40px btn-sm">
                                Compose
                            </a>
                        </div>
                        <div class="mailbox-sidebar-content collapse d-lg-block" id="emailNav">
                            <div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
                            <div class="nav-title"><b>FOLDERS</b></div>
                            <ul class="nav nav-inbox">
                                <li><a href="emailInbox.php"><i class="fa fa-hdd fa-lg fa-fw me-2"></i> Inbox <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
                                <li class="active"><a href="sentEmail.php"><i class="fa fa-envelope fa-lg fa-fw me-2"></i> Sent <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
                            </ul>
                        </div>
                    </div>
                </div><div class="mailbox-content">
                <div class="mailbox-content-header">
                    <div class="btn-toolbar align-items-center">
                        <a href="sentEmail.php" class="btn btn-sm btn-white me-2"><i class="fa fa-redo"></i></a>
                        <div class="w-100 d-sm-none d-block mb-2 hide" data-email-action="divider"></div>
                    </div>
                </div>
                <div class="mailbox-content-body" style="height:100%">
                    <div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
                        <ul class="list-group list-group-lg no-radius list-email">';
        if($emails) {
            /* begin output var */            
            /* put the newest emails on top */
            rsort($emails);
            $colors = ['bg-danger','bg-blue','bg-warning','bg-indigo','bg-info'];
            /* for every email... */
            foreach($emails as $email_number) {
                if($email_number > count($colors)){
                    array_push($colors,'bg-danger','bg-blue','bg-warning','bg-indigo','bg-info');
                }
                //$Name = $overview[0]->from[0];
                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $message = imap_fetchbody($inbox,$email_number,1);
                $output.= ' <li class="list-group-item "' .($overview[0]->seen ? 'read' : 'unread'). '">
                <a href="#" onclick="deleteMessage('.$email_number.')"><i class="fa fa-trash fa-2x text-danger"></i></a> &nbsp;&nbsp;&nbsp;
                <a href="sentEmail_detail.php?uid='.$email_number.'" class="email-user '. $colors[$email_number -1] . '">
                    <span class="text-white">'.$overview[0]->to[0].'</span>
                </a>
                <div class="email-info">
                    <a href="sentEmail_detail.php?uid='.$email_number.'">
                            <span class="email-sender">'.$overview[0]->to.'</span>
                            <span class="email-title">'.$overview[0]->subject.'</span>
                            <span class="email-time">'.$overview[0]->date.'</span>
                    </a>
                </div>
            </li>';
            }
            $output .='</ul></div></div>';
        }else{
            $output .="<div class='text-center ' style='font-size:18px'><b><i class='fa fa-frown-o text-danger' aria-hidden='true'></i> No Email Sent...</b></div>";
        }
        echo $output;
        /* close the connection */
        imap_close($inbox);
        
}
else if(isset($_POST['DeleteMessage'])){
    try{
    // Get Specific Staff
    $selectQuery = $pdo->prepare("SELECT `staff_web_Email`,`staff_web_password` FROM staff WHERE 
    staff_id = :staff_id");
    $selectQuery->bindParam(':staff_id', $_SESSION['user_id']);
    $selectQuery->execute();
    /* Fetch all of the remaining rows in the result set */
    $cridentail = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
    // Close connection
    unset($pdo); 
    /* connect to gmail */
    $hostname = '{imap.domain.com:143/imap/tls}INBOX.Sent';
    $username = $cridentail[0]['staff_web_Email'];
    $password = $cridentail[0]['staff_web_password'];
    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
    /* grab emails */
    imap_delete($inbox, $_POST['EmNum']);
    imap_expunge($inbox);
    echo "Success";
    imap_close($inbox);
    }catch(PDOException $e){
        echo "ERROR: Could not able to execute $sql. " . $e->getMessage();
    }
}
?>

