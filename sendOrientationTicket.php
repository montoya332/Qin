<?php

// Pear Mail Library
require_once "Mail.php";
require_once "Mail/mime.php";
require_once "maillib.php";
require_once("common.php");
require_once("settings.php");


// PHP_DOC
// INPUT:attendee_id as string 
// OUTPUT: NULL
// DESCRIPTION:Call genetreOrientaionTicketEmail to send individual Email
function sendOrientationTicket($attendee_id){

	openDatabase();
	$result = mysql_query("SELECT first_name, last_name, email, event_name, ticket_url From qr_codes where user_id=".$attendee_id);
	$result_array = array();
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$eventName = "SJSU Spring 2014 Graduate Orientation"; //urldecode($row['event_name']);
	$to = $row['email'];
	$subject = 'SJSU Ticket - ' . $eventName;

	generateOrientationTicketEmail($row['first_name'], $row['last_name'], $row['ticket_url'],$to);
	closeDatabase();
}


// PHP_DOC
// INPUT: FirstName, lastName , ticketURL , Email as strings
// OUTPUT: NULL
// DESCRIPTION: calls SendEmaill($email, 'event name' , $html, $text)
function generateOrientationTicketEmail ($toFirstName, $toLastName, $ticketURL, $to){

       $html = generateOrientationEmailBody('html',$toFirstName,$toLastName,$ticketURL);
       $text = generateOrientationEmailBody('text',$toFirstName,$toLastName,$ticketURL);

        sendEmail($to,"SJSU Spring 2014 Graduate Orientation Ticket", $html, $text);

}

// PHP_DOC
// INPUT:$type,$first,$last,$ticket_url as string 
// OUTPUT: $body as string
// DESCRIPTION:
function generateOrientationEmailBody($type, $first_name, $last_name, $ticket_url){

  if ($type == 'html'){
      $br = "<br><br>";
  } else if ($type == 'text'){
      $br = "\n\n";
  
  }

        $body = "Greetings, ".$first_name . " " . $last_name . "!";

        $body .= paragraph("We look forward to meeting you on January 15, 2014, at SJSU's International Arrival Program and/or Graduate Orientation. To help make the day smoother, please print out the ticket using the following link. Thank you!",$type); 

   	$body .= htmllink("Download Ticket", 'http://'.$SERVER_IP.’/getTicket.php?ticket=' . $ticket_url, $type);




    $body .= paragraph("Once again, welcome.",$type);

    $body .= paragraph("The Graduate Orientation Team",$type);

  return $body;
}


?>
