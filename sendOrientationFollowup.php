<?php

// Pear Mail Library
require_once "Mail.php";
require_once "Mail/mime.php";
require_once "maillib.php";
require_once("common.php");


openDatabase();

$result = mysql_query("SELECT distinct first_name, last_name, email, ticket_url From qr_codes where event_id=56 OR event_id=57");

$result_array = array();


while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){

	$to = $row['email'];

	sendOrientationFollowupEmail($row['first_name'],$row['last_name'],$event_name,$row['ticket_url'], $to);


  echo "Email sent to ". $row['first_name']. " " . $row['last_name']. " at ".$row['email']."\n";
}


echo ("Emails have been sent\n\n");

closeDatabase();

//PHP_DOC
// Input: first name , lastname , EventName, ticketURL, Email
//Output: NULL
//Desc: Calls SendEmail($Email, "SJSU Graduation Orientation", HTML() ,Text(),attachments
function sendOrientationFollowupEmail ($toFirstName, $toLastName, $eventName, $ticketURL, $to){

       $html = generateOrientationFollowupBody('html',$toFirstName,$toLastName,$ticketURL);

       $text = generateOrientationFollowupBody('text',$toFirstName,$toLastName,$ticketURL);

	$attachments = array(
		"uploads/diablo_sunset.jpg",
		"uploads/sjsu_student_union.pdf"
	);

       sendEmail($to, "SJSU Graduate Orientation", $html, $text, $attachments);

}

//PHP_DOC
// Input: $type , first_name, lastname, ticketURL as strings
//Output:returns $body as a string
//Desc.: create body by calling paragraph ("testing Attachemnt Functionality", HTML/text)
function generateOrientationFollowupBody($type, $first_name, $last_name, $ticketURL) {
	$body = "Greetings, ".$first_name . " " . $last_name . "!";

	$body .= paragraph("Testing Attachment Functionality", $type);

	return $body;
}

?>
