
<html>
<head>


	<link href="css/ticket.css" rel="stylesheet" type="text/css"/>

</head>

<body>

<?php

	include "../phpqrcode/qrlib.php";
	require_once("../common.php");
	$ticket_url = filterHexString($_GET['ticket']);

        //$qrCode = 'some';

	openDatabase();

        $result = mysql_query("SELECT first_name, last_name, event_name, qr_code, orientation_group From qr_codes where ticket_url = '".$ticket_url."'");

        $result_array = array();

	$row = mysql_fetch_row($result, MYSQL_ASSOC);

	if ($row) {

?>

<div id = "ticket">

<img src = '/template/ticket_template.png'/>

<?php
  echo generateQRCode($row['qr_code']);
  //echo generateQRCode('stohentoehsuth');
  echo generateLabel($row['first_name']." ".$row['last_name'],"SJSU - Spring 2014 Graduate Orientation", $row['orientation_group']);
  
  echo generateFooter();
  
?>
</div>

<?php


	} else {
		echo "Invalid parameter";
	}

	closeDatabase();

?>

</body>
</html>

<?php

function generateQRCode($qr_code){
		$sample_qr_code = "hi arturo";

		$image_filename = "images/" . $qr_code  . ".png";

		if (!file_exists($image_filename)){
			QRcode::png($qr_code, $image_filename);
		}
		return "<img id = 'qrcode' src = '".$image_filename."'/>";

}

function generateLabel($studentName, $eventName, $orientation_group){

  if ($orientation_group == 0){
     $groupLetter = 'A';
  } else if ($orientation_group == 1){
     $groupLetter = 'B';
  } else if ($orientation_group == 2){
     $groupLetter = 'C';
  } else {
     $groupLetter = 'NA';
  }


    $return = "<p class = 'studentlabel' id = 'studentname'>".$studentName."</p><br>";
    $return .= "<p class = 'studentlabel' id ='eventname'>".urldecode($eventName)."</p><br>";
    //$return .= "<p class = 'studentlabel' id = 'orientation_group'>".$groupLetter."</p><br>";

    return $return;

}



function generateFooter(){
    return "<p class = 'footer'>Please bring this ticket with you to the event.</p>";
}




?>
