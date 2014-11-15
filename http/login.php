

<?php
	include_once ("../common.php");
	include ("../sitelib.php");

    openDatabase();
    //$user = filterHexString($_POST['UserName']);
    $passcode = filterHexString($_GET['Passcode']);
	$cur_timestamp = date('2013-m-d');//date('Y-m-d') //H:i:s//where admin_passcode ='".$passcode."'and  eventDate > '".$cur_timestamp."'
	$result = mysql_query("SELECT eventID, eventName, eventDate
                            From app_updater 
                            where eventDate > '".$cur_timestamp."'
							and admin_passcode ='".$passcode."'
                            Order By eventDate ASC" );
    
    $result_array = array();

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $result_array[] = $row;
    }
    closeDatabase();
    if(!empty($result_array)){
		var_dump($result_array);//echo urldecode($result_array[0]['eventName']);
	}
    else{
        echo "Invalid Passcode";
    }

?>



