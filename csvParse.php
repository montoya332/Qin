
<?php

  require_once("common.php");
  require_once("fileParsing.php");

  $event_id = filterNumberString($_POST['event_id']);

  openDatabase();


  $file = receiveFile(); 
  
  $attendees = parse_csvfile($file);
  

  $attendees = addAttendeesIDs($attendees);
  //error_log("DEBUG: attendees:  " . print_r($attendees, true));

  registerAttendees($attendees, $event_id);

  error_log("DEBUG: csvParse.php");
  
  closeDatabase();

// PHP_DOC
// Input:
// Output:
// Description:  TODO - Redundant - Replace with call to function in attendees_lib.php
function addAttendeesIDs($attendees) {
  foreach ($attendees as &$attendee){
    $attendee = addAttendeeID($attendee);
  }
  return $attendees;
}



// PHP_DOC
// Input:
// Output:
// Description:  TODO - Reduntant - Replace with call in attendee_lib.php
function addAttendee($attendee){

  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];
  $sjsu_id = $attendee['sjsu_id'];

  $result = mysql_query("
  INSERT INTO `".DB_SCHEMA."`.`attendees` (`firstname`, `lastname`, `email`,`sjsu_id`) 
  VALUES ('".$first_name."', '".$last_name."', '".$email."','".$sjsu_id."');
  
  ");

}



// PHP_DOC
// Input:
// Output:
// Description:  TODO - Redunant, replace with call in attendee_lib.php
function addattendeeID($attendee){


  $attendeeID = doesAttendeeExist($attendee);

  if($attendeeID){
      $attendee['user_id'] = $attendeeID;
  } else {
      addAttendee($attendee);
      $attendee['user_id'] = doesAttendeeExist($attendee);
  }
  
  return $attendee;
}



// PHP_DOC
// Input:
// Output:
// Description:  TODO - Redunant, replace with call in attendee_lib.php
function doesAttendeeExist($attendee){
  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];

  $result = mysql_query("SELECT count(*) as is_valid, user_id 
            FROM ".DB_SCHEMA.".attendees
            WHERE lastname = '".$last_name."'
            AND firstname = '".$first_name."'
            AND email = '".$email."'");
            
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
// Input:
// Output:
// Description:   TODO - Redunant, replace with call in attendee_lib.php
function registerAttendees($attendees, $event_id){
	foreach ($attendees as $attendee){
		$attendee_id = $attendee['user_id'];
		$result = mysql_query("
			INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, `event_id`) 
			VALUES (".$attendee_id.", ".$event_id.");
		");
  	}

}



// PHP_DOC
// Input:
// Output:
// Description:  **TODO** **DEPRECATED** **REMOVE**
function parse_csvfileold($file){

return;
  $handle = fopen($file,"r");


  $attendees = array();
  $fields = array();

  $line = fgets($handle);
  //var_dump ($line);
  $fields = custom_getcsv($line);

  //var_dump ($fields);

  $i = 0;
  while ($line = fgets($handle)){
	  $row = custom_getcsv($line);
	  if ($row[0] != NULL) {
		  foreach ($row as $k=>$value){
		  $attendees[$i][$fields[$k]] = $value;
		  }
	  }
	  $i++;

  }

  fclose ($handle);
  
  return $attendees;

}


// PHP_DOC
// Input:
// Output:
// Description:  **TODO** **DEPRECATED** **REMOVE**
function doesAttendeeExistREMOVE($attendee){
  $first_name = $attendee['first_name'];
  $last_name = $attendee['last_name'];
  $email = $attendee['email'];

  $result = mysql_query("SELECT count(*) as is_valid, user_id 
            FROM ".DB_SCHEMA.".users
            WHERE last_name = '".$last_name."'
            AND first_name = '".$first_name."'
            AND email = '".$email."'");
            
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
// Input:  NULL
// Output: $newname as filename of uploaded CSV file on server, prints 1 on success, "Invalid File Type" on Error
// Description:   Retrieves file from $_FILES, moves it to /uploads
function receiveFile (){

$targetFolder = APP_UPLOAD_DIR; 

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);




  if (!empty($_FILES)){
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $targetFolder;
        //$targetPath = "/vol/web/" . $targetFolder;

	$newname = md5(date('DdMYHis')) . ".csv";

        $targetFile = rtrim($targetPath,'/') . '/' . $newname;

        // Validate the file type
        $fileTypes = array('csv'); // File extensions
        $fileParts = pathinfo($_FILES['Filedata']['name']);

        if (in_array($fileParts['extension'],$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);
                echo 1;//$_POST['event_id'];
        } else {
                echo 'Invalid file type.';
        }
  }

  return $newname;
}




?>
