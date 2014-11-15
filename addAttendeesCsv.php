<?php

include ("fileParsing.php");


  $file = receiveFile();

  $attendees = parse_csvfile($file);

  $returnArray = array(
		'file' => $file,
		'attendees' => $attendees
	);

  $returnJSON = json_encode($returnArray);
  echo ($returnJSON);


  //registerAttendees($attendees, $event_id);

//var_dump ($attendees);

//PHP_DOC
// Input: Null
//Output: Name of uploaded file on server
//Desc.  TODO: Merge with receiveFile() in csvParse.csv
function receiveFile (){

$targetFolder = APP_UPLOAD_DIR; 

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

  if (!empty($_FILES)){
        $tempFile = $_FILES['Filedata']['tmp_name'];
        $targetPath = $targetFolder;
	$newname = md5(date('DdMYHis')) . ".csv";

        $targetFile = rtrim($targetPath,'/') . '/' . $newname;

        // Validate the file type
        $fileTypes = array('csv'); // File extensions
        $fileParts = pathinfo($_FILES['Filedata']['name']);

        if (in_array($fileParts['extension'],$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);
                //echo 1;//$_POST['event_id'];
        } else {
                //echo 'Invalid file type.';
        }
  }
  return $newname;
}

?>

