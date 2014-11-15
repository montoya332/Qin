
<?php //session_start();
//require_once("sitelib.php");
require_once("settings.php");
require_once("common.php");
require_once("manual.php");

?>
<html>
<head>
        <script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css" />
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- Our JS -->
	<script type="text/javascript" src="jsphp.js"></script>
</head>

<body>
<div class="container">

        <div class="row">
            <div class="col-md-2">
                <div class="sidebar">
                    <div class="alert alert-info">
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                        <div class="jumbotron">

			<?php
			if (($_SERVER['REQUEST_METHOD'] == 'POST')&&($_POST['id'] != null)) {
				include ("attendee_lib.php");

				//echo $_POST['id'];
				$sjsu_id = filterNumberString($_POST['id']);

				$attendee = array();

				$attendee['first_name'] = $_POST['first_name'];
				$attendee['last_name'] = $_POST['last_name'];
				$attendee['email'] = $_POST['email'];
				$attendee['sjsu_id'] = $sjsu_id;

				if ($_POST['am_register']){
					$attendee['am'] = "X";
					echo "Successfully registered for Morning<br>";
				}

				if ($_POST['pm_register']){
					$attendee['pm'] = "X";
					echo "Successfully registered for Afternoon<br>";
				}

				//echo var_dump ($attendee);

				openDatabase();
				addAttendee($attendee);
				$attendee['user_id'] = doesAttendeeExist($attendee); //This will normally return true upon the successful execution of the previous line. 
				// user_id = the PK of the row, not the sjsu_id.
				customRegisterAttendee($attendee);

				$query = "SELECT ticket_url, user_id FROM 
".DB_SCHEMA.".qr_codes 
where sjsu_id = ".$sjsu_id." limit 1;";

				$result = mysql_query($query);
				$row = mysql_fetch_row($result, MYSQL_ASSOC);
				closeDatabase();

				parseManualCheckinData($row);


			} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){

			$sjsu_id = $_GET['sjsu_id'];
			$am_signin = $_GET['am_signin'];
			$pm_signin = $_GET['pm_signin'];

			//echo $sjsu_id."<br>";
			//echo "AM ".$am_signin."<br>";
			//echo "PM ".$pm_signin."<br>";

			?>

			<h2>Spring 2014 Orientation</h2>

			<h3 style='color:red'>The entered SJSU ID does not have a match in the database. Please confirm the student's status and enter their information below to register for orientation.</h3>

			<p>Enter Student ID Below: (Full number including first two 00's)</p>
			<form method="post" action="manualRegister.php">
				SJSU ID: <input name="id" readonly value='<?php echo $sjsu_id; ?>'></input>
				<br>
				First Name: <input name="first_name"></input>
				<br>
				Last Name: <input name="last_name"></input>
				<br>
				Email: <input name="email"></input>
				<br>
				AM Register (Person is RSVPing): <input name="am_register" type="checkbox"  <?php if ($am_signin ) echo 'checked'; ?>></input>
				<br>
				AM Auto-Sign In (Person is signing in): <input name="am_signin" type="checkbox" <?php if ($am_signin ) echo 'checked'; ?>></input>
				<br>
				PM Register (Person is RSVPing): <input name="pm_register" type="checkbox"  <?php if ($pm_signin ) echo 'checked'; ?>></input>
				<br>
				PM Auto-Sign In (Person is signing in): <input name="pm_signin" type="checkbox" <?php if ($pm_signin ) echo 'checked'; ?>></input>

				<br>

				<input type="submit" value="Submit"></input>
			</form>

			<?php

			} // end else

			?>


            </div>
        </div>
  </div>
</body>
</html>



