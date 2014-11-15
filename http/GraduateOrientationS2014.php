<?php session_start();
require_once("../sitelib.php");
require_once("../common.php");
?>
<html>
<head>
	<!-- Include JQuery File  -->
        <link href="jquery/jquery-ui.min.css" rel="stylesheet" type="text/css"/>                                                           
        <script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="jquery/jquery-datetime.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css" />
	<script src="bootstrap/js/bootstrap.min.js"></script>

</head>

<body>

<?php
	//include "phpqrcode/qrlib.php";

	$token = filterHexString($_GET['token']);
        openDatabase();
        $result = mysql_query("SELECT * From attendee_tokens where token = '".$token."'");
        closeDatabase();
        $result_array = array();

	$row = mysql_fetch_row($result, MYSQL_ASSOC);


?>
 <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                    <div class="alert alert-info">
				Orientation Enrollment Form
                    </div>
            </div>
	</div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                        <div class="well">
                            <h3>Spring 2014 Graduate Orientation</h3>
				<br>
				<?php if($row):
					echo "<p>Welcome, " . $row['firstname'] . " " . $row['lastname']. "!</p>"; 
					?>
					<br>
					<p>
						Check one or more boxes below depending on which sessions you plan to attend.  If you plan to attend only one session, please check which one you plan to attend. If you plan to attend both sessions, please check each box.
					</p>

					<?php
					if (isset($_GET['error'])){

					?>

					<p style='color: #f00;'>You have to choose at least one option</p>
					<?php } ?>


					<form action="GraduateOrientationS2014Post.php" method="post">
						<?php 
        						$outputIDForm = '<input type="text" name="token" id="token" value="'. $token . '" style="display:none">';
							echo $outputIDForm;
						?>
						<br>
  						<input type="checkbox" name="International" id="International">
							AM Session - International Student Arrival Program
							<br>
							<span style="color: #ff0000;font-weight:bold;">
								(F1 Visa students ONLY)
							</span>

						</input>
						<br>
						<br>
  						<input type="checkbox" name="General" id="General">
							PM Session - Graduate Orientation Program 
							<br>
							<span style="font-weight:bold;">
							(All entering graduate students welcome)
							</span>
						</input>
						<br>


						<input type="submit" value="Submit" class="btn btn-primary">
					</form>

				<?php else : ?>
					<p>Please Navigate to this page using the direct link from your email</p>
				<?php endif; ?>
                        </div>
            </div>
        </div>
  </div>
</body>
</html>
