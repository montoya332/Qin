<?php

require_once("sitelib.php");
require_once("settings.php");


// PHP_DOC
// INPUT: DB Attendie ID, DB Event ID  
// OUTPUT: User Info or Invalid
// DESCRIPTION: Used to check if attendee is enrolled in event. Then checks in with timestamp
function manualCheckin ($user_id, $event_id){

    $returnString="";
    openDatabase();
    $result = mysql_query("SELECT count(*) as is_valid, first_name, last_name, event_name, user_id, event_id, time_checked_in 
                            From qr_codes 
                            where user_id ='".$user_id."'
                            and event_id = '".$event_id."' 
                            and time_checked_in IS NULL   " );
    
    $result_array = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $result_array[] = $row;
    }

    closeDatabase();
    if($result_array[0]['is_valid'] >= 1){
        $cur_timestamp = date('Y-m-d H:i:s');
        checkinCheckoutAttendee($user_id, $event_id, true);

        $returnString .= $result_array[0]['first_name'];
        $returnString .= " ";
        $returnString .= $result_array[0]['last_name'];
        $returnString .= " has checked in at ";
    }
    else{
        $returnString .= "Invalid Ticket for ";

    }


	return $returnString;
}

// PHP_DOC
// INPUT: DB row with attributes Ticket_url, userid, am, pm
// OUTPUT:  
// DESCRIPTION: Custum code that calls checkin for Jan2014 event
function parseManualCheckinData($row){

	$ticket_url = $row['ticket_url'];
	$user_id = $row['user_id'];
	if ($_POST['am_signin']){
		echo manualCheckin ($user_id, 56);  //56 for Morning Session Event
		echo " Morning Session<br>";
		//echo "Attendee Successfully checked in for Morning Session!!!<br>";
	}

	if ($_POST['pm_signin']){
		echo manualCheckin ($user_id, 57); // 57 for Afternoon Session Event
		echo " Afternoon Session<br>";
		//echo "Attendee Successfully checked in for Afternoon Session!!!<br>";

	}

	//header ("Location: http://".$SERVER_IP."/getTicket.php?ticket=".$ticket_url);

	$link = "http://".$SERVER_IP."/getTicket.php?ticket=".$ticket_url;
	echo "<p>If you want to print a ticket for future events, please click below:</p>";
	echo "<a href = '".$link."'>Download Ticket</a>";

}


?>
