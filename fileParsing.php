<?php

include_once("settings.php");

// PHP_DOC
// INPUT:file name of uploaded CSV file  from uploads directory 
// OUTPUT:array of  $attendees
// DESCRIPTION: Parse CSV file calling custom_getcsv at each line
function parse_csvfile($file){
  //error_log("DEBUG: fileParsing.php");
 $filefullpath = APP_UPLOAD_DIR . $file;
  error_log("DEBUG: " . $filefullpath );


  $handle = fopen($filefullpath,"r");


  $attendees = array();
  $fields = array();

  $line = fgets($handle);
  //var_dump ($line);
  $fields = custom_getcsv($line);
  error_log("DEBUG: line variable:  " . $line);
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
	  error_log("DEBUG Line output: " . $line);
  }

  fclose ($handle);
  //error_log("DEBUG: attendees" . print_r($attendees, true));
  return $attendees;

}

// PHP_DOC
// INPUT:$line from CSV  
// OUTPUT:$line as string  
// DESCRIPTION:convert line in to string  
function custom_getcsv ($line){
	return str_getcsv($line);

}

?>

