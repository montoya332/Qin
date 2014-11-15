<?php

require_once("settings.php");

// PHP_DOC
// Input:  $attendees as array of $attendee
// Output: $attendees as array of $attendee
// Desc.   Adds $attendee['user_id'] to each $attendee
function addAttendeesIDs($attendees) {
  foreach ($attendees as &$attendee){
    $attendee = addAttendeeID($attendee);
  }
  return $attendees;
}

// PHP_DOC
// Input:  $attendee as array containing attributes such as name, email, etc
// Output: $attendee as array including attribute 'user_id' and 'token'
// Desc.   Retreives user_id from database, creates attendee if it does not exist
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


// PHP_DOC
// Input:   $attendee as array of attributes, including name, email, etc
// Output:  user_id as Integer
// Desc.    Searches the DB for the given attendee and returns the index of the database row, NULL on failure
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
// PHP_DOC
// Input:   $attendee as attributes including name, email, sjsu_id
// Output:  NULL
// Desc.    Adds $attendee as new entry in DB
function addAttendee($attendee){

  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];
  $sjsu_id = $attendee['sjsu_id'];

  $result = mysql_query("
  INSERT INTO `".DB_SCHEMA."`.`attendees` (`firstname`, `lastname`, `email`, 
`sjsu_id`) 
  VALUES ('".$first_name."', '".$last_name."', '".$email."', '".$sjsu_id."');
  
  ");

  // TODO - Check for confirmation, return false on Error

}
// PHP_DOC
// Input:   $attendees as array of $attendee, $event_id as integer
// Output:  NULL
// Desc.    Registers the given list of attendees with the event specified by the event ID
function registerAttendees($attendees, $event_id){
	foreach ($attendees as $attendee){
		$attendee_id = $attendee['user_id'];
		$result = mysql_query("
			INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, 
`event_id`) 
			VALUES (".$attendee_id.", ".$event_id.");
		");
  	}

}


// PHP_DOC
// Input:   $attendees as array of $attendee
// Output:  NULL
// Desc.    CUSTOM CHERYL CODE: calls customRegisterAttendee for each $attendee
function customRegisterAttendees($attendees){
        foreach ($attendees as $attendee){
		customRegisterAttendee($attendee);
        }

}

// PHP_DOC
// Input:   $attendee as array containing attributes
// Output:  NULL
// Desc.    Based on added attributes of $attendee, add attendee into events accordingly. If $attendee['am'] is set, register them to the morning session,  if $attendee['pm'] is set, register them to the afternoon session
function customRegisterAttendee($attendee){
        $attendee_id = $attendee['user_id'];

	$result = mysql_query("DELETE FROM `".DB_SCHEMA."`.`eventcheckin` WHERE 
`attendee_id`='".$attendee_id."';");

        if ($attendee['am']){
            $result = mysql_query("
                    INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, 
`event_id`)
                    VALUES (".$attendee_id.", 56);
            ");
        }
        if ($attendee['pm']){
            $result = mysql_query("
                    INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, 
`event_id`)
                    VALUES (".$attendee_id.", 57);
            ");
        }

}


?>
