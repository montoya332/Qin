<?php
	include_once ("../common.php");
	include ("../sitelib.php");

    openDatabase();
    $qrCode = filterHexString($_POST['qrcode']);
    $event_id = filterHexString($_POST['event']);

    // TODO: Get event id from the QR code rather than hard-code it
    //$event_id = 57;

    $result = mysql_query("SELECT count(*) as is_valid, first_name, last_name, event_name, user_id, event_id, time_checked_in 
                            From qr_codes 
                            where qr_code ='".$qrCode."'
                            and event_id = '".$event_id."' 
                            and time_checked_in IS NULL   " );
    
    $result_array = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $result_array[] = $row;
    }
    closeDatabase();

    if($result_array[0]['is_valid'] >= 1){
		$cur_timestamp = date('Y-m-d H:i:s');
		checkinCheckoutAttendee($result_array[0]['user_id'], $event_id, true);

        echo $result_array[0]['first_name'];
        echo " ";
        echo $result_array[0]['last_name'];
        echo " has checked in at ";
        echo urldecode($result_array[0]['event_name']);
        echo " on ";
        echo $cur_timestamp;
	}
    else{
        echo "Invalid Ticket";
    }
?>
