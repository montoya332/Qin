<?php

require_once("common.php");
require_once("maillib.php");
require_once("attendee_lib.php");
require_once("settings.php");

if ($argc == 2){
	$file = filterAlphaNumericString($argv[1]);
} else {
	echo "error";
	exit();
}


$attendees = processAttendees($file);

//sendCustomEmails ($attendees);

sendCustomTicketRegisterEmails($attendees);

$filefullpath = APP_UPLOAD_DIR . $file;

unlink ($filefullpath);

// PHP_DOC
// Input:  $file as filename of uploaded csv file
// Output: $attendees as array of attendees
// Desc.  TODO - Merge with processAttendees in sendEventForm.php
function processAttendees ($file){
  include ("fileParsing.php");

  openDatabase();

  $attendees = parse_csvfile($file);

  $attendees = addAttendeesIDs($attendees);

  customRegisterAttendees($attendees);

  //registerAttendees($attendees);
  closeDatabase();

  return $attendees;
}



// PHP_DOC
// Input:  $attendees as array of attendees
// Output: Null
// Desc.   Iterates through the $attendees array and sends an orientation ticket.
function sendCustomTicketRegisterEmails ($attendees){
        $subject = 'SJSU Spring 2014 Graduate Orientation'; // . $event_name;
        foreach ($attendees as $row){
                require_once("sendOrientationTicket.php");
                $attendee_id = $row['user_id'];
                sendOrientationTicket($attendee_id);
                sleep (1000);
        }
}

// PHP_DOC
// Input:   $attendees as array of attendees
// Output:  Null
// Desc.    Sends a custom email to Students going to the Spring 2014 Graduate Orientation
function sendCustomEmails ($attendees){
	// Pear Mail Library


        $subject = 'SJSU Spring 2014 Graduate Orientation'; // . $event_name;


        foreach ($attendees as $row){
		$to = $row['email'];
  		$html = generateOrientationWelcomeBody('html',$row['first_name'],$row['last_name'],$row['token']);
		$text = generateOrientationWelcomeBody('text',$row['first_name'],$row['last_name'],$row['token']);


  		//echo "Attempting to send email to ". $row['first_name']. " " . $row['last_name']. " at ".$row['email']."\n";
  		echo sendEmail($to, $subject, $html, $text);
		sleep (500);
	}


}

// PHP_DOC
// Input:   $type as html|text, $first_name, $last_name, $ticket_url as unique link for the ticket
// Output:  text or html encoded message
// Desc.
function generateOrientationWelcomeBody($type, $first_name, $last_name, $ticket_url){

  if ($type == 'html'){
      $br = "<br><br>";
  } else if ($type == 'text'){
      $br = "\n\n";
  
  }

	$full_name = $first_name . " " . $last_name;


	$body = "";

	$body .= paragraph($full_name . ",", $type);

	$body .= paragraph("Greetings from SJSU!", $type);


	$body .= paragraph("Congratulations on your graduate admission to San Jose State University!  Please see the information below about two events we have planned to assist you in a successful transition to graduate school.  Registration information follows.", $type);

	$body .= underlinetext("Graduate Orientation Program", $type);

	$body .= paragraph("All students who will attend SJSU in the coming semester are invited to our Graduate Student Orientation starting at 1:00 p.m. on Wednesday, January 15, 2014.  Speakers will attend from the Admissions Office, Graduate Studies & Research, Financial Aid, Academic Advising Services, the Registrar, and the Bursar's office.  Other offices will be represented at a Resource Fair.  Campus tours will be available.  In addition, a student poster session displaying research conducted at SJSU will be held during a catered reception.  While this is not intended as a venue to meet with program advisors, many will attend, an opportunity exists for you to meet and mingle with fellow students within your specific program.  This program is a general orientation covering graduate school practices and procedures.  It is not mandatory; however, we believe you will benefit from your attendance.  Some departments may hold program-specific orientations on different dates that concentrate on departmental expectations.  We encourage you to attend both.", $type);



	$body .= paragraph("The afternoon orientation session is open to all new graduate students, domestic and international with all visa types.", $type);


	$body .= underlinetext("International Student Arrival Program", $type);

	$body .= paragraph("International students with F-1 visas are also required to attend a mandatory morning session on the same day beginning at 9:00 a.m. You should already have received another invitation from the International Program Services Office for this session.  If you are not an F-1 international student, your program begins at 1:00 p.m.  At the International Students Arrival Program immigration laws and university policies specific to students with F-1 visas will be discussed.  An esteemed international SJSU faculty member will present tips for getting along as well as strategies for adjusting to a new culture and surroundings.  A student panel will follow up on this theme as well as answering questions from the audience.  Lunch will be provided. Although you should have received another invitation from the International Program Services Office for this session (and may have already registered with them), it is necessary for you to register once again as set out below.", $type);



	$body .= underlinetext("Registration", $type);

	$body .= paragraph("To attend either or both of these sessions, you will need a 'ticket' in the form of a QR code that you can print out (preferred) or display on a cell phone.  To register for the Graduate Orientation Program and/or International Student Arrival Program, click below to access our Registration Page and receive your QR code.", $type);


	$body .= htmllink("Register Here", 'http://'.$SERVER_IP.'/GraduateOrientationS2014.php?token=' . $ticket_url , $type);

	$body .= boldtext("Please register only if you plan to attend.", $type);

	$body .= paragraph("You must register on or before January 6, 2014 to attend the Graduate Orientation.  Print the QR code or bring your cell phone with the code displayed for staff to read at the registration tables.  Space is limited so we ask that you not bring guests.  SJSU is a public facility and photographers may be present.", $type);


	$body .= underlinetext("Photographic Approval", $type);

	$body .= paragraph("By registering to attend either event, you have agreed to allow us to use your image on SJSU's website and promotional materials.  If you do not wish to be photographed, please contact Cheryl Cowan at cheryl.cowan@sjsu.edu.  We will manually register your attendance and provide you with an identifier to prevent photography.", $type);

	$body .= underlinetext("Special Accomodations", $type);

	$body .= paragraph("If you require special accommodations to attend either or both of these orientation events, please contact Cheryl Cowan at cheryl.cowan@sjsu.edu." ,$type);

	$body .= paragraph("We look forward to meeting you!", $type);

	$body .= paragraph("The Gratuate Orientation Team", $type);


  return $body;
}

?>

