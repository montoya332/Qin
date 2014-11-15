<?php session_start();
require_once("sitelib.php");


    // Default response
    $response = array('ok' => false, 'msg' => "Invalid Javascript Request");

    $javascriptReqType = $_POST['action'];
    if(isset($javascriptReqType))
    {
        if($javascriptReqType == "login" || $javascriptReqType == "registerUser")
        {
			$username = $_POST['username'];
			$password = $_POST['password'];

			if($javascriptReqType == "registerUser") 
			{
				// Register your user here :
				$fname = $_POST['fname'];
				$lname = $_POST['lname'];
                $passcode = $_POST['passcode'];
                $expectedPasscode = "qreventgroup";
	
				// Purposely change this such that user will login after registering
                if ($passcode == $expectedPasscode) {
                    openDatabase();
                    // Check if username already exists
                    $query = "SELECT * FROM admins WHERE email='" . $username . "';";
                    $result = getQueryCount($query);
                    $status = false;
                    $msg = "A user already exists with that email ID";

                    if (0 == $result) {
                        $query = sprintf("INSERT INTO admins VALUES(NULL, '%s', '%s', '%s', '%s');",
                                         $fname, $lname, $password, $username);
                        $result = mysql_query($query);
                        $status = true;
                        $msg = "OK";

                        // Change the request to "login" such that user can be logged in below.
                        $javascriptReqType = "login";
                        $response['d1'] = $result;
                    }
                    closeDatabase();

                    $response = array('ok' => $status, 'msg' => $msg);
                }
                else {
                    $response = array('ok' => false, 'msg' => "Invalid passcode");
                }
			}

			if($javascriptReqType == "login")
			{
				$loginOk = false;

				if($username != "" && $password != "") {
                    $query = "SELECT * FROM admins WHERE email='" . $username . "';";
                    openDatabase();
                    $result = mysql_query($query);
                    $result = mysql_fetch_assoc($result);
                    closeDatabase();
                    $loginOk = ($result['password'] == $password);
                }

				if(!$loginOk) {
					$response = array('ok' => false, 'msg' => "Login Failed.");
				}
				else
				{
					session_start();
					$_SESSION['isValid'] = true;
					$_SESSION['sess_username']  = $username;
					$response = array('ok' => true, 'msg' => "OK",
										'navBarData'  => getNavBar(),
										'sideBarData' => getSideBar()
										);
				}
            }
        }
 
        // Next requests until isUserLoggedIn() are permitted without user registration
		else if($javascriptReqType == "Registered")
		{
			$response = array('ok' => true, 'msg' => "OK");
			$event_id = $_POST['event_id'];

			openDatabase();

			$result = mysql_query("SELECT sjsu_id, first_name, last_name, event_name, 
qr_code, is_checked_in From ".DB_SCHEMA.".qr_codes Where event_id = " . $event_id .";");
			$result_array = array();
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$result_array[] = $row;
			}
			$response['registered'] = $result_array;
			$response['event_id'] = $event_id;

			closeDatabase();
		}       
		else if($javascriptReqType == "getSampleNames")
		{
	
            $response = array('ok' => true, 'msg' => "OK");
            $response['sample_names'] = array(
                0=> array(
                     'name' => 'Arturo Montoya',
                     'email' => 'arturo.montoya332@gmail.com',
                     'role' => 'Android App & Web Developer',
                    ),
                1=> array(
                     'name' => 'Darryl DelaCruz',
                     'email' => 'darrylndelacruz@gmail.com',
                     'role' => 'Android App & Web Developer',
		     'link' => '<a href="http://www.linkedin.com/in/darrylndelacruz">
      
          <img 
src="https://static.licdn.com/scds/common/u/img/webpromo/btn_viewmy_160x33.png" 
width="160" height="33" border="0" alt="View Darryl DelaCruz\'s profile on LinkedIn">
        
    </a>'
                    ),
                2=> array(
					
                     'name' => 'Phuoc Tran',
                     'email' => 'phuoctrankim@gmail.com',
                     'role' => 'Android App & Web Developer',
					 'link' => '<a href="http://www.linkedin.com/in/phuoctran">  
                                <img src="https://static.licdn.com/scds/common/u/img/webpromo/btn_viewmy_160x33.png" width="160" height="33" border="0" alt="View Phuoc Trans profile on LinkedIn">
                                </a>',
					 
                    ),
                3=> array(
                     'name' => 'Erik Montoya',
                     'email' => 'erikcmontoya@hotmail.com',
                     'role' => 'Android App & Web Developer',
                    )
					
                
            );
		}
        
        // Rest of the request require user being logged in, so reject everything if user is not logged in
        else if (!isUserLoggedIn())
        {
            $response = array('ok' => false, 'msg' => "Invalid session, please login again");
        }
		else if($javascriptReqType == "logout")
		{
			$response = array('ok' => true, 'msg' => "OK");
			$_SESSION['isValid'] = false;
			session_destroy();
		}
        /* User creating an event with list of attendees */
        else if($javascriptReqType == "createAdminEvent")
		{
            $name = $_POST['name'];
            $time = $_POST['time'];
            $info = $_POST['info'];
            $file = $_POST['file']; 
            $inviteList = $_POST['list'];
            
            $response = createNewEvent($name, $time, $inviteList, $info);
            $event_id = mysql_insert_id();
            //include ("commitAttendees.php");
            //commitAttendees($event_id, $file);
            /* TODO - Remove csvParseLegacy, merge code, Fix $event_id so that it accurately passes the correct ID  */
        }

   	else if($javascriptReqType == "createAdminMultiEvent")
	{
            $name = $_POST['name'];
            $info = $_POST['info'];
            $eventids = $_POST['eventids']; 

            
            $response = createNewMultiEvent($name, $info, $eventids);
            $multi_event_id = mysql_insert_id();
            //include ("commitAttendees.php");
            //commitAttendees($event_id, $file);
            /* TODO - Remove csvParseLegacy, merge code, Fix $event_id so that it accurately passes the correct ID  */


	}

        /* Users retrieving a list of their events */
        else if($javascriptReqType == "getAdminEvents")
        {
            $response = getAdminEvents();
        }
        else if($javascriptReqType == "getAdminMulitEvents")
        {
            $response = getAdminMultiEvents();
        }
        else if($javascriptReqType == "getAdminInfo")
        {
            $response = getAdminInfo();
        }
        /* User deleting a created event */
        else if($javascriptReqType == "deleteAdminEvent")
        {
            $id = $_POST['eventId'];
            $response = deleteAdminEvent($id);
        }
        /* User retrieving list of attendees of an event */
        else if($javascriptReqType == "getEventAttendeeList")
        {
            $id = $_POST['eventId'];
            $response = getEventAttendeeList($id);           
        }
        /* Check-in or Check-out an attendee
         * This is the API for an admin that has already logged in.
         */
        else if($javascriptReqType == "checkinCheckoutAttendee")
        {
            $userId = $_POST['userId'];
            $eventId = $_POST['eventId'];
            $checkedIn = $_POST['checkedIn'];
            $response = checkinCheckoutAttendee($userId, $eventId, $checkedIn);
        }
        /* Get the last X number of checkins */
        else if($javascriptReqType == "getLastCheckinList")
        {
            $minutes = $_POST['minutes'];
            $eventId = $_POST['eventId'];
            $response = getLastCheckinList($minutes, $eventId);
        }
        
        
		/* User retrieving values for attendees per hour of an event */
        else if($javascriptReqType == "getEventAttendeeCount")
        {
            $id = $_POST['eventId'];
			//$time =  "2013-11-25 03:08:53";//$_POST['time']; // Would like to start from initial event time
            $response = getEventAttendeeCount($id);                
        }
		else if($javascriptReqType == "createQRcode")
		{
			$image_filename = "images/" . md5(date('DdMYHis')) . ".png";

    		$response = array('ok' => true, 'msg' => "OK");
			$comment = $_POST['comment'];
			include "phpqrcode/qrlib.php";
       		QRcode::png($comment, $image_filename); //, $errorCorrectionLevel, $matrixPointSize, 2);    ^M
   			$response['filename'] = $image_filename;
		}
        /* User retrieving list of attendees of an event */
        else if($javascriptReqType == "getEventAttendeeListOrientation")
        {
            $id = $_POST['eventId'];
            $response = getEventAttendeeListOrientation($id);           
        }
		/* User retrieving values for attendees per hour of an event */
        else if($javascriptReqType == "getEventAttendeeCountOrientation")
        {
            $id = $_POST['eventId'];
			$time =  "2013-11-25 03:08:53";//$_POST['time']; // Would like to start from initial event time
            $response = getEventAttendeeCountOrientation($id);                
        }
        else if($javascriptReqType == "getMailLog")
        {
            $stuff = file_get_contents('mail.log');
            $response = array('ok' => true, 'msg' => "OK");
            $response["filecontent"] = $stuff;
        }

    } // isset()

    echo json_encode($response);
	exit;
?>

