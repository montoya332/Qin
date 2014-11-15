<?php

require_once "settings.php";

// Pear Mail Library
require_once "Mail.php";
require_once "Mail/mime.php";
require_once "maillib.php";
require_once("common.php");


if ($argc == 2){
	$event_id = filterNumberString($argv[1]);
} else {
	echo "Error";
	exit();
}

openDatabase();

$result = mysql_query("SELECT first_name, last_name, email, event_name, ticket_url From qr_codes where event_id=".$event_id);

$result_array = array();


while ($row = mysql_fetch_array($result, MYSQL_ASSOC)){

	$event_name = urldecode($row['event_name']);
	$to = $row['email'];
	$subject = 'SJSU Ticket - ' . $event_name;


	sendTicketLink($row['first_name'],$row['last_name'],$event_name,$row['ticket_url'], $to, $subject);


  echo "Email sent to ". $row['first_name']. " " . $row['last_name']. " at ".$row['email']."\n";
}


echo ("Emails have been sent\n\n");

closeDatabase();

?>
