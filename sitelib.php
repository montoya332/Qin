<?php session_start(); require_once("common.php");

// PHP_DOC
// INPUT:NULL 
// OUTPUT: $barContent
// DESCRIPTION:
function getNavBar(){
    $barContent = "";
     
    $barContent .= '<ul class="nav navbar-nav">';
	$barContent .= '<li><a class="active" href="index.php"><b>Home</b></a></li>';
	$barContent .= '<li><a class="active" href="#" onClick="ContactHandler()"><b>Contact Info</b></a></li>';
    $barContent .= '</ul>';
    
	//$barContent .= '<li><a href="#" onClick="QRHandler()"><b>CreateQR</b></a></li>';
	//$barContent .= '<li><a href="#" onClick="EventHandler()"><b>Events</b></a></li>';
	//$barContent .= '<li><a href="#" onClick="PastEventHandler()"><b>Events</b></a></li>';
    
    $barContent .= '<ul class="nav navbar-nav navbar-right">';
    if(isUserLoggedIn())
    {
		$barContent .= '<li><a href="#" onclick="javascript: performLogout()"><b>Logout</b></a></li>';
	}
    else {
        $barContent .= '<div class="navbar-collapse collapse"><form class="navbar-form navbar-right" onSubmit="return false;">' .
							'<div class="form-group"><input type="text"     name="loginUsername" id="loginUsername" class="form-control" placeholder="Email/User"> </div>' .
							'<div class="form-group"><input type="password" name="password"      id="password"      class="form-control" placeholder="Password"> </div>' .
							'&nbsp;&nbsp;<button class="btn btn-success" type="submit" onclick="attemptLogin()"><i class="glyphicon glyphicon-share icon-white"></i>&nbsp;&nbsp;<b>Sign in</b></button>' .
							'&nbsp;&nbsp;<button class="btn btn-primary" onClick="showRegistrationModal()"/><i class="glyphicon glyphicon-user icon-white"></i>&nbsp;&nbsp;<b>Sign Up!</b></button>' .
						'</form></div>';
    }
    $barContent .= '</ul>';

    return $barContent;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getSideBar(){
    $barContent = "";
    
    if(!isUserLoggedIn())
    {
       $barContent .= '<BR/><HR><img src="spartan_logo.gif" width="100%"></img><HR>';
       $barContent .= '<blockquote><p>Please login . . .</p></blockquote><HR><BR/>';
    }
    else
    {
		$barContent .= '<HR><ul class="nav nav-pills nav-stacked">';
		$barContent .= 		'<li><font color="black"><B>Menu</B></font></li>';
		$barContent .= 		'<li> <A HREF="#"  onClick="MyHome()">&nbsp;<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;Home</a> </li>';
		//$barContent .= 		'<li> <A HREF="#"  onClick="myGraph()">&nbsp;<i class="icon-list"></i>Tran</a> </li>';
		//$barContent .= 		'<li> <A HREF="#"  onClick="MyInfo()">&nbsp;<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Profile</a> </li>';
		$barContent .= '<li> <A HREF="#"  onClick="MyInfo()">&nbsp;<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;Profile</a> </li>';
                $barContent .= '<li> <A HREF="#"  onClick="readlogfile()">&nbsp;<i class="glyphicon glyphicon-road"></i>&nbsp;&nbsp;Mail Log</a> </li>';

        $barContent .= '</ul>';
 
        $barContent .= '<HR><ul class="nav nav-list">';
        $barContent .= 	'<li><font color="black"><B>Event Menu</B></font></li>';
        $barContent .= 	'<li> <A HREF="#"  onClick="eventsButtonHandler(0)">&nbsp;<i class="glyphicon glyphicon-eye-open"></i>&nbsp;&nbsp;View</a> </li>';
        $barContent .= 	'<li> <A HREF="#"  onClick="eventsButtonHandler(1)">&nbsp;<i class="glyphicon glyphicon-plus"></i>&nbsp;&nbsp;Create</a> </li>';
        $barContent .= 	'<li> <A HREF="#"  onClick="eventsButtonHandler(2)">&nbsp;<i class="glyphicon glyphicon-list-alt"></i>&nbsp;&nbsp;View Multi</a> </li>';
	$barContent .=  '<li> <A HREF="#"  onClick="eventsButtonHandler(3)">&nbsp;<i class="glyphicon glyphicon-tower"></i>&nbsp;&nbsp;Multi Create</a> </li>';
	$barContent .= '</ul><HR>';

        $barContent .= '<img src="spartan_logo.gif" width="90%"></img>';
        $barContent .= '<HR/>';
    }

    return $barContent;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getLoggedInAdminId(){
    $query = "SELECT * FROM admins WHERE email='" . getSessionUsername() . "';";
    $result = performSingleDbQueryGetRow($query);
    return $result['id'];
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function createNewEvent($name, $evTime, $inviteList, $info){
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    // Make sure the fields are not empty
    if ($name == "" || $evTime == "" || $inviteList == "" || $info == "") {
        $response['ok'] = false;
        $response['msg'] = "One or more required fields are emtpy";
    }
    else {
        openDatabase();
        $adminId = getLoggedInAdminId();
        
        // Make sure we can retrieve user session's admin id
        if (!$adminId) {
            $response['ok'] = false;
            $response['msg'] = "Failed to retrieve logged in user id";
        }
        else {
            $query = "INSERT INTO events VALUES(NULL, '" . SQLE($name) . "', '" . SQLE($evTime) . "', '" . $adminId . "', '" . SQLE($info) . "');";
            $result = mysql_query($query);

            $response['ok'] = true;
            $response['msg'] = "OK";
        }
        closeDatabase();
    }

    return $response;
}


function createNewMultiEvent($name, $info, $eventids) {
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    // Make sure the fields are not empty
    if ($name == "" || $info == "") {
        $response['ok'] = false;
        $response['msg'] = "One or more required fields are emtpy";
    }
    else {
        openDatabase();
        $adminId = getLoggedInAdminId();
        
        // Make sure we can retrieve user session's admin id
        if (!$adminId) {
            $response['ok'] = false;
            $response['msg'] = "Failed to retrieve logged in user id";
        }
        else {

	    $query = "INSERT INTO `multi_event` (`multi_event_name`, `multi_event_info`, `event_ids`, `admin_id`) VALUES ('".SQLE($name)."', '".SQLE($info)."', '".filterNumberCSV($eventids)."', '".SQLE($adminId)."');
";

            $result = mysql_query($query);

            $response['ok'] = true;
            $response['msg'] = "OK";
        }
        closeDatabase();
    }

    return $response;
}


// PHP_DOC
// INPUT: NULL
// OUTPUT: response[ok, msg, eventsArray]
// DESCRIPTION: returns events created by the user
function getAdminEvents(){
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    openDatabase();
    $adminId = getLoggedInAdminId();
        
    // Make sure we can retrieve user session's admin id
    if (!$adminId) {
        $response['ok'] = false;
        $response['msg'] = "Failed to retrieve logged in user id";
    }
    else {
        $query = sprintf("SELECT * from events WHERE admin_id=%s ORDER BY event_id ASC;", $adminId);
        $result = mysql_query($query);

        $eventsArray = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $eventsArray[] = $row;
        }
        
        $response['eventsArray'] = $eventsArray;  
        $response['ok'] = true;
        $response['msg'] = "OK";
    }
    closeDatabase();
    
    return $response;
}

function getAdminMultiEvents(){
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    openDatabase();
    $adminId = getLoggedInAdminId();
        
    // Make sure we can retrieve user session's admin id
    if (!$adminId) {
        $response['ok'] = false;
        $response['msg'] = "Failed to retrieve logged in user id";
    }
    else {
        $query = sprintf("SELECT * from multi_event WHERE admin_id=%s ORDER BY event_id ASC;", $adminId);
        $result = mysql_query($query);

        $multieventsArray = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $multieventsArray[] = $row;
        }
        
        $response['multieventsArray'] = $eventsArray;  
        $response['ok'] = true;
        $response['msg'] = "OK";
    }
    closeDatabase();
    
    return $response;
}
function getAdminInfo(){
    $response = array('ok' => false, 'msg' => "Undefined error");

    openDatabase();
    $adminId = getLoggedInAdminId();

    // Make sure we can retrieve user session's admin id
    if (!$adminId) {
        $response['ok'] = false;
        $response['msg'] = "Failed to retrieve logged in user id";
    }
    else {
        $query = sprintf("SELECT firstname, lastname, email from admins WHERE id=%s ;", $adminId);
        $result = mysql_query($query);

        $userArray = array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $userArray[] = $row;
        }

        $response['userArray'] = $userArray;
        $response['ok'] = true;
        $response['msg'] = "OK";
    }
    closeDatabase();
    return $response;
}


// PHP_DOC
// INPUT: eventId
// OUTPUT: response[ok, msg, ]
// DESCRIPTION:
function deleteAdminEvent($eventId) {
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    $sqlCon = openDatabase();
    $adminId = getLoggedInAdminId();
        
    // Make sure we can retrieve user session's admin id
    if (!$adminId) {
        $response['ok'] = false;
        $response['msg'] = "Failed to retrieve logged in user id";
    }
    else {
        $query = sprintf("DELETE FROM events WHERE event_id=%s AND admin_id=%s;", SQLE($eventId), $adminId);
        $result = mysql_query($query);

        $deletedOk = (1 == mysql_affected_rows($sqlCon));
        
        // TODO : Deleted the event, so also delete the guest-list of this event
        if ($deletedOk) {
        }
        
        $response['ok'] = $deletedOk;
        $response['msg'] = "OK";
    }
    closeDatabase();
    
    return $response;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getEventAttendeeList($id){
    $response = array('ok' => false, 'msg' => "Undefined error");

    $sqlCon = openDatabase();
    $query = "SELECT eventcheckin.checkin_time, eventcheckin.checked_in, " .
                "attendees.user_id, attendees.firstname, attendees.lastname, attendees.email " .
             "FROM ".DB_SCHEMA.".eventcheckin " .
             "JOIN ".DB_SCHEMA.".attendees ON " .
             "eventcheckin.attendee_id = attendees.user_id " .
             "WHERE eventcheckin.event_id=" . SQLE($id) . 
             " ORDER BY attendees.lastname ASC;";

    $list = array();
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $list[] = $row;
    }
    $response = array('ok' => true, 'msg' => "OK", 'list' => $list);

    closeDatabase();
    return $response;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function checkinCheckoutAttendee($userId, $eventId, $checkedIn)
{
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    $sqlCon = openDatabase();
    //$adminId = getLoggedInAdminId();
        
    // Make sure we can retrieve user session's admin id
    
/*
    if (!$adminId) {
        $response['ok'] = false;
        $response['msg'] = "Failed to retrieve logged in user id";
    }
    else
*/ {
        // TODO If checkin, then update TIMESTAMP as current timestamp
        // TODO If not checkin, set TIMESTAMP to NULL
        $checkinTime = $checkedIn ? "now()" : "NULL";
        $query = sprintf("UPDATE eventcheckin SET checked_in='%s', checkin_time=%s WHERE attendee_id=%s AND event_id=%s;",
                         SQLE($checkedIn), $checkinTime, SQLE($userId), SQLE($eventId));
        $result = mysql_query($query);

        $modifiedOk = (1 == mysql_affected_rows($sqlCon));        
        $response['ok'] = $modifiedOk;
        $response['msg'] = $modifiedOk ? "OK" : "Error updating data";
    }
    closeDatabase();
    
    return $response;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getLastCheckinList($minutes, $eventId)
{
    $response = array('ok' => false, 'msg' => "Undefined error");
    
    $sqlCon = openDatabase();
    
    $query = "SELECT eventcheckin.checkin_time, eventcheckin.checked_in, " .
                "attendees.user_id, attendees.firstname, attendees.lastname, attendees.email " .
             "FROM ".DB_SCHEMA.".eventcheckin " .
             "JOIN ".DB_SCHEMA.".attendees ON " .
             "eventcheckin.attendee_id = attendees.user_id " .
             "WHERE eventcheckin.event_id=" . SQLE($eventId) . 
             " AND eventcheckin.checkin_time > date_sub( now( ) , INTERVAL " . SQLE($minutes) . " MINUTE ) " .
             " ORDER BY eventcheckin.checkin_time DESC;";

    $list = array();
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $list[] = $row;
    }
    $response = array('ok' => true, 'msg' => "OK", 'list' => $list);
    
    closeDatabase();
    
    return $response;
}








// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getEventAttendeeCount($id){
    $response = array('ok' => false, 'msg' => "Undefined error");
	$eventDate = "2013-11-25 0";// changing zero will change start value
	//$createDate_Start = new DateTime($eventDate);
	//$eventDate_Start = $createDate_Start->format('Y-m-d');	
	//$eventHour_Start = $createDate_Start->format('H');
    $sqlCon = openDatabase();
    $query = sprintf("SELECT count(orientation_group) AS countChecked FROM view_Count WHERE event_id='%s' and time_checked_in < '", SQLE($id));
	
    $list = array();
	
	for ($i=1; $i<=9; $i++){
	if ($i < 10 & $i>$eventHour_Start){$h = '0'.$i;} else {$h = $i;}
	$result = mysql_query($query.$eventDate.$i."'");
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
    $list[] = $row;
    }

    $response = array('ok' => true, 'msg' => "OK", 'list' => $list);

    closeDatabase();
    return $response;
}

// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getEventAttendeeCountOrientation($id,$time){
    $response = array('ok' => false, 'msg' => "Undefined error");

    $sqlCon = openDatabase();
    $query = sprintf("SELECT count(orientation_group) AS countChecked FROM view_Count WHERE event_id='%s'", SQLE($id));
	$createDate_Start = new DateTime($time);
	$eventDate_Start = $createDate_Start->format('Y-m-d');	
	$eventHour_Start = 0;//$createDate_Start->format('H');
	
	$whereGroup =" and orientation_group ='";
	$whereTime = " and time_checked_in < '";//need "';"
    $list = array();
	$result_arrayA = array();
	$result_arrayB = array();
	$result_arrayC = array();

		for ($i=$eventHour_Start; $i<=($eventHour_Start+9); $i++){
				 if ($i < 10 & $i>$eventHour_Start){ $h = '0'.$i;} else {$h = $i;}
				$result = mysql_query($query.$whereGroup."0'".$whereTime."2013-11-25 ".$h.":59'");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$result_arrayA[] = $row;	
		}
		
		for ($j=$eventHour_Start; $j<=($eventHour_Start+9); $j++){
				if ($j < 10 & $j>$eventHour_Start){ $h = '0'.$j;} else {$h = $j;}
				$result = mysql_query($query.$whereGroup."0'".$whereTime.$eventDate_Start." ".$h.":59'");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$result_arrayB[] = $row;	
		}
		for ($k=$eventHour_Start; $k<=($eventHour_Start+9); $k++){
				if ($k < 10 & $k>$eventHour_Start){ $h = '0'.$k;} else {$h = $k;}
				$result = mysql_query($query.$whereGroup."0'".$whereTime.$eventDate_Start." ".$h.":59'");
				$row = mysql_fetch_array($result, MYSQL_ASSOC);
				$result_arrayC[] = $row;	
		}	
		$list['GroupA'] = $result_arrayA;
		$list['GroupB'] = $result_arrayB;
		$list['GroupC'] = $result_arrayC;
		 
    $response = array('ok' => true, 'msg' => "OK", 'list' => $list);

    closeDatabase();
    return $response;
}
/* TODO
 * This uses SQL views so it is deprecated and should be removed after verifying with the team
 */
// PHP_DOC
// INPUT: 
// OUTPUT:
// DESCRIPTION:
function getEventAttendeeListOrientation($id){
    $response = array('ok' => false, 'msg' => "Undefined error");

    $sqlCon = openDatabase();
    $query = sprintf("SELECT * FROM view_Count WHERE event_id='%s' order by time_checked_in DESC", SQLE($id));
    
    $list = array();
    $result = mysql_query($query);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $list[] = $row;
    }
    $response = array('ok' => true, 'msg' => "OK", 'list' => $list);

    closeDatabase();
    return $response;
}

?>
