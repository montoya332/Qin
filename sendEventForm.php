<?php

require_once("common.php");
require_once("maillib.php");
require_once("settings.php");


if ($argc == 2){
	$file = filterAlphaNumericString($argv[1]);
} else {
	echo "error";
	exit();
}


$attendees = processAttendees($file);

sendCustomEmails ($attendees);

$filefullpath = "/vol/web/uploads/" . $file;

unlink ($filefullpath);

//PHP_DOC
// Input: $file as string of a file name 
//Output: $attendees as a list of all attendees registering for an event
//Desc. The functions will parse the input file and output a list of all attendees
function processAttendees ($file){
  include ("fileParsing.php");

  openDatabase();

  $attendees = parse_csvfile($file);

  $attendees = addAttendeesIDs($attendees);

  //registerAttendees($attendees);
  closeDatabase();

  return $attendees;
}

   
//PHP_DOC
// Input: $attendees as array of new students  
//Output: $attendees as array of registered students.
//Desc. It takes inputs as new students and outputs as assigns new student IDs to them.
function addAttendeesIDs($attendees) {
  foreach ($attendees as &$attendee){
    $attendee = addAttendeeID($attendee);
  }
  return $attendees;
}
  
//PHP_DOC
// Input: $attendee as a student
//Output: Sattedees as a registered student
//Desc. Checks if a student exists in the database, if not register the student with new ID.
function addattendeeID($attendee){


  $attendeeID = doesAttendeeExist($attendee);

  if($attendeeID){
      $attendee['user_id'] = $attendeeID;
  } else {
      addAttendee($attendee);
      $attendee['user_id'] = doesAttendeeExist($attendee);
  }

 $attendee['token'] = md5($attendee['user_id'].'salt54654' );
  
  return $attendee;
}
//PHP_DOC
// Input: $attendee as a student name
//Output: 'user_id'or NULL
//Desc. Check and compare the name, email, and student ID of a student with a list of all students in the database
//if exists it returns the ID of the student, else returns NULL
function doesAttendeeExist($attendee){
  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];
  $sjsu_id = $attendee['sjsu_id'];

  $result = mysql_query("SELECT count(*) as is_valid, user_id 
            FROM ".DB_SCHEMA.".attendees
            WHERE lastname = '".$last_name."'
            AND firstname = '".$first_name."'
            AND email = '".$email."'
	    AND sjsu_id = '".$sjsu_id."'");
 
  $result_array = array();
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $result_array[] = $row;
  }
  
//var_dump ($result_array);
  
  if ($result_array[0]['is_valid'] >= 1){
      return $result_array[0]['user_id'];
  } else {
      return NULL;
  }
  


}
//PHP_DOC
// Input: $attendee as a name of a student
//Output: NULL
//Desc. Insert a student with first and last name, emails, and student ID to attendees table in the database.
function addAttendee($attendee){

  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];
  $sjsu_id = $attendee['sjsu_id'];

  $result = mysql_query("
  INSERT INTO `".DB_SCHEMA."`.`attendees` (`firstname`, `lastname`, `email`, `sjsu_id`) 
  VALUES ('".$first_name."', '".$last_name."', '".$email."', '".$sjsu_id."');
  
  ");

}
//PHP_DOC
// Input: $attendees as a list of strings of names, $event_id as an integer
//Output: NULL
//Desc. Get a list of students and an event id, then insert their IDs corresponding with the assigned event ID
// into the database in 'eventcheckin' table
function registerAttendees($attendees, $event_id){
	foreach ($attendees as $attendee){
		$attendee_id = $attendee['user_id'];
		$result = mysql_query("
			INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, `event_id`) 
			VALUES (".$attendee_id.", ".$event_id.");
		");
  	}

}

//PHP_DOC
// Input: $attendees as a list of attendees
//Output: NULL
//Desc. This function takes a list of all registered attendees and send emails to these students
function sendCustomEmails ($attendees){
	// Pear Mail Library


        $subject = 'SJSU Spring 2014 Graduate Orientation'; // . $event_name;


        foreach ($attendees as $row){
		$to = $row['email'];
  		$html = generateOrientationWelcomeBody('html',$row['first_name'],$row['last_name'],$row['token']);
		$text = generateOrientationWelcomeBody('text',$row['first_name'],$row['last_name'],$row['token']);


  		//echo "Attempting to send email to ". $row['first_name']. " " . $row['last_name']. " at ".$row['email']."\n";
  		echo sendEmail($to, $subject, $html, $text);
		sleep (300);
	}


}
//PHP_DOC
// Input: $type, $first_name, $last_name,and $ticket_url as strings
//Output: $body as a paragraph
//Desc. Send a welcome mail with a unigue URL to each student.
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


	$body .= htmllink("Register Here", 'http://'.$SERVER_IP.’/GraduateOrientationS2014.php?token=' . $ticket_url , $type);

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

