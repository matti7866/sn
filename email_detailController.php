<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('location:login.php');
}
include 'connection.php';
if(isset($_POST['GetEmailDetail'])){
        // Get Specific Staff
        $selectQuery = $pdo->prepare("SELECT `staff_web_Email`,`staff_web_password` FROM staff WHERE 
        staff_id = :staff_id");
        $selectQuery->bindParam(':staff_id', $_SESSION['user_id']);
        $selectQuery->execute();
        /* Fetch all of the remaining rows in the result set */
        $cridentail = $selectQuery->fetchAll(\PDO::FETCH_ASSOC);
         // Close connection
        unset($pdo); 
        /* connect to server */
        $mail_server="imap.domain.com";
        $mail_link="{{$mail_server}:143/imap/tls}" ;
        $mail_user=$cridentail[0]['staff_web_Email'];
        $mail_pass=$cridentail[0]['staff_web_password'];
        /* try to connect */
        $connection = imap_open($mail_link, $mail_user, $mail_pass) or die('Cannot connect: ' . imap_last_error());
        /* email number */
        $messageNumber = $_POST['ID'];      
        //The number of the mail (The first mail)
        $structure = imap_fetchstructure($connection, $messageNumber);
        $overview = imap_fetch_overview($connection,$messageNumber,0);
        $header = imap_headerinfo($connection, $messageNumber);
        $fromaddr = $header->from[0]->mailbox . "@" . $header->from[0]->host;
        $flattenedParts = flattenParts($structure->parts);
        foreach($flattenedParts as $partNumber => $part) {

            switch($part->type) {
                
                case 0:
                    // the HTML or plain text part of the email
                    $message = getPart($connection, $messageNumber, $partNumber, $part->encoding);
                    // now do something with the message, e.g. render it
                break;
            
                case 1:
                    // multi-part headers, can ignore
            
                break;
                case 2:
                    // attached message headers, can ignore
                break;
            
                case 3: // application
                case 4: // audio
                case 5: // image
                case 6: // video
                case 7: // other
                    $filename = getFilenameFromPart($part);
                    if($filename) {
                        // it's an attachment
                        $attachment = getPart($connection, $messageNumber, $partNumber, $part->encoding);
                        // now do something with the attachment, e.g. save it somewhere
                    }
                    else {
                        // don't know what it is
                    }
                break;
            
            }
            
        }
        $attachments = array();
        if(isset($structure->parts) && count($structure->parts)) {
        
            for($i = 0; $i < count($structure->parts); $i++) {
        
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
                
                if($structure->parts[$i]->ifdparameters) {
                    foreach($structure->parts[$i]->dparameters as $object) {
                        if(strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
                
                if($structure->parts[$i]->ifparameters) {
                    foreach($structure->parts[$i]->parameters as $object) {
                        if(strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
                
                if($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($connection, $messageNumber, $i+1);
                    if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
        set_time_limit(3000);
        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];

                if(empty($filename)) $filename = time() . ".dat";
                $folder = "attachment";
                if(!is_dir($folder))
                {
                     mkdir($folder);
                }
                $fp = fopen("./". $folder ."/" . $filename, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }
        }
        $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","ppt");
        $imgext = array("jpg","jpeg","png");
       $output = ''; 
       $output .= '<div class="mailbox-sidebar">
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
              <ul class="nav nav-inbox">
              <li class="active"><a href="emailInbox.php"><i class="fa fa-hdd fa-lg fa-fw me-2"></i> Inbox <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
              <li><a href="sentEmail.php"><i class="fa fa-envelope fa-lg fa-fw me-2"></i> Sent <span class="badge bg-gray-600 fs-10px rounded-pill ms-auto fw-bolder pt-4px pb-5px px-8px"></span></a></li>
              </ul>
           </div>
        </div>
     </div>
     <div class="mailbox-content">
        <div class="mailbox-content-header">
           <div class="btn-toolbar">
              <div class="btn-group">
                 <a href="#" class="btn btn-white btn-sm"><i class="fa fa-fw fa-times"></i></a>
              </div>
           </div>
        </div>
        <div class="mailbox-content-body">
        <div data-scrollbar="true" data-height="100%" data-skip-mobile="true">
           <div class="p-3">
                 <h3 class="mb-3">'. $overview[0]->subject .'</h3>
                 <div class="d-flex mb-3">
                    <a href="javascript:;" class="email-user bg-blue" style="width: 40px;height: 40px;
                    min-width: 40px;
                    overflow: hidden;
                    font-size: 25px;
                    line-height: 37px;
                    text-align: center;
                    color: #6f8293;
                    background: #c6ced5;
                    margin: -5px 0;
                    border-radius: 40px;">
                       <span class="text-white">'.$overview[0]->from[0].'</span>
                    </a>
                    <div class="ps-3">
                       <div class="email-from text-inverse fs-14px mb-3px fw-bold">
                          from <a href="#" class="__cf_email__">' . $fromaddr .'</a>
                       </div>
                          <div class="mb-3px"><i class="fa fa-clock fa-fw"></i>'. $overview[0]->date .'</div>
                       <div class="email-to">
                          To: <a href="#" class="__cf_email__" >Me</a>
                       </div>
                    </div>
                 </div>
                 <hr class="bg-gray-500" />
                 <ul class="attached-document clearfix">';
                 $extension = '';
                 $ext = [];
                 $fileEx = '';
                 //Getting Attachment Part
                if(!empty($attachments)){
                    for($arrItem = 1; $arrItem< count($attachments); $arrItem++){
                        if($attachments[$arrItem]['filename']){
                            $extension = explode(".", $attachments[$arrItem]['filename']);
                            if (in_array(strtolower($extension[1]), $imgext))
                            {
                                $output .= '<li class="fa-camera">
                                <div class="document-file">
                                   <a href="attachment/'.$attachments[$arrItem]['filename'] .'">
                                      <img src="attachment/'.$attachments[$arrItem]['filename'].'" alt="" />
                                   </a>
                                </div>
                                <div class="document-name"><a href="attachment/'.$attachments[$arrItem]['filename'].'" class="text-decoration-none">'.$attachments[$arrItem]['filename'].'</a></div>
                             </li>';
                            }else if(in_array(strtolower($extension[1]), $ext)){
                                if($ext == 'doc' || $ext == 'docx'){
                                    $fileEx = '<i class="fa fa-file-word-o"></i>';
                                }else if($ext == 'xls' || $ext == 'xlsx'){
                                    $fileEx = '<i class="fa fa-file-excel-o"></i>';
                                }else if($ext == 'ppt'){
                                    $fileEx = '<i class="fa fa-file-powerpoint-o"></i>';
                                }else if($ext == 'pdf'){
                                    $fileEx = '<i class="fa fa-file-pdf"></i>';
                                }else if($ext == 'txt'){
                                    $fileEx = '<i class="fa fa-file-text"></i>';
                                }
                                $output .= '<li class="fa-file">
                                        <div class="document-file">
                                            <a href="attachment/'.$attachments[$arrItem]['filename'].'">'. $fileEx . '</a>
                                        </div>
                                    <div class="document-name"><a href="attachment/'.$attachments[$arrItem]['filename'].'" class="text-decoration-none">'.$attachments[$arrItem]['filename'].'</a></div>
                                </li>';
                            }
                        }    
                    }
                }
                $output .= '</ul>
                 <p class="text-inverse">
                    '. $message .'
                 </p>
                 <br />
                 <br />
                 <p class="text-inverse">
                    Best Regards,<br />
                    '. $overview[0]->from  . '<br /><br />
                 </p>
           </div>
        </div>
     </div>
     <div class="mailbox-content-footer d-flex align-items-center justify-content-end">
     </div>';
     echo $output;
        imap_close($connection);
        
}
function flattenParts($messageParts, $flattenedParts = array(), $prefix = '', $index = 1, $fullPrefix = true) {
	foreach($messageParts as $part) {
		$flattenedParts[$prefix.$index] = $part;
		if(isset($part->parts)) {
			if($part->type == 2) {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.', 0, false);
			}
			elseif($fullPrefix) {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix.$index.'.');
			}
			else {
				$flattenedParts = flattenParts($part->parts, $flattenedParts, $prefix);
			}
			unset($flattenedParts[$prefix.$index]->parts);
		}
		$index++;
	}
	return $flattenedParts;
}
function getPart($connection, $messageNumber, $partNumber, $encoding) {
	
	$data = imap_fetchbody($connection, $messageNumber, $partNumber);
	switch($encoding) {
		case 0: return $data; // 7BIT
		case 1: return $data; // 8BIT
		case 2: return $data; // BINARY
		case 3: return base64_decode($data); // BASE64
		case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
		case 5: return $data; // OTHER
	}
	
	
}
function getFilenameFromPart($part) {
	$filename = '';
	if($part->ifdparameters) {
		foreach($part->dparameters as $object) {
			if(strtolower($object->attribute) == 'filename') {
				$filename = $object->value;
			}
		}
	}
	if(!$filename && $part->ifparameters) {
		foreach($part->parameters as $object) {
			if(strtolower($object->attribute) == 'name') {
				$filename = $object->value;
			}
		}
	}
	return $filename;
}
?>