
<?php //session_start();
require_once("sitelib.php");
require_once("common.php");
require_once("manual.php");
require_once("settings.php");


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
			if (($_SERVER['REQUEST_METHOD'] == 'POST')&&($_POST['sjsu_id'] != null)) {
				//echo $_POST['id'];
				$sjsu_id = filterNumberString($_POST['sjsu_id']);
				$am_signin = $_POST['am_signin'];
				$pm_signin = $_POST['pm_signin'];

				openDatabase();

				$query = "SELECT ticket_url, user_id FROM 
".DB_SCHEMA.".qr_codes 
where sjsu_id = ".$sjsu_id." limit 1;";

				$result = mysql_query($query);
				$row = mysql_fetch_row($result, MYSQL_ASSOC);
				closeDatabase();

				if ($row){
					parseManualCheckinData($row);

				} else {
					header ("Location: 
https://".$SERVER_IP.WEB_ROOT"/manualRegister.php?sjsu_id=".$sjsu_id."&am_signin=".$am_signin."&pm_signin=".$pm_signin);
				}

				

			} else {

				if (isset($_GET['error'])){
					echo "<h4 style='color:red;'>Invalid SID!!!</h4>";
				}

			?>

			<h3>Spring 2014 Orientation</h3>
			<p>Enter Student ID Below: (Full number including first two 00's)</p>
			<form method="post" action="getTicketByStudentID.php">
				<input name="sjsu_id"></input>
				<br>
                                AM Auto-Sign In (Person is signing in at the event): <input name="am_signin" type="checkbox"></input>
                                <br>
                                PM Auto-Sign In (Person is signing in at the event): <input name="pm_signin" type="checkbox"></input>
				<br>
				<input type="submit" value="Submit"></input>
			</form>

			<?php
			}
			?>
            </div>
        </div>
  </div>
</body>
</html>




