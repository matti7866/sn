<?php

/* connect to server */
$mail_server="imap.domain.com";

$mail_link="{{$mail_server}:143/imap/tls}" ;

$mail_user="test@sntrips.com";

$mail_pass="Test@123";


/* try to connect */
$connection = imap_open($mail_link, $mail_user, $mail_pass) or die('Cannot connect: ' . imap_last_error());

/* email number */
$messageNumber = 4;      
//The number of the mail (The first mail)

$structure = imap_fetchstructure($connection, $messageNumber);
$overview = imap_fetch_overview($connection,$messageNumber,0);
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

echo '<pre>'; print_r($attachment); echo '</pre>';
//echo '<pre>'; print_r($overview[0]->from); echo '</pre>';
// To get Message Body
//echo '<pre>'; print_r($message); echo '</pre>';
// End
/* fetch the email content in HTML mode */
//$message = imap_qprint(imap_body($inbox,$mail_number)); 

/* output the email body */
//$output= '<div class="body">'.$message.'</div>';
//$extension = '';
//$ext = [];
// Getting Attachment Part
// if(!empty($attachments)){
//     for($arrItem = 0; $arrItem< count($attachments); $arrItem++){
//         $extension = explode(".", $attachments[$arrItem]['filename']);
//         $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","ppt");
//         $imgext = array("jpg","jpeg","png");
//             if (in_array(strtolower($extension[1]), $imgext))
//             {

//             }else if(in_array(strtolower($extension[1]), $ext)){

//             }
        
//     }
// }
//echo '<pre>'; print_r($attachments[$arrItem]['filename']); echo '</pre>';
//$extension = explode(".", $_FILES['uploadFile']['name']);
//file_put_contents("tickets", $attachments[1]['attachment']);
//echo $attachment[1]['attachment'];
//echo '<img src="'.$attachments[1]['filename'] .'" alt="Girl in a jacket" width="500" height="600">';
//echo $attachments;
/* close the connection */
imap_close($connection);

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