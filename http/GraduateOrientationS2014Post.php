<?php session_start();
require_once("../sitelib.php");
require_once("../common.php");
require_once("../sendOrientationTicket.php");

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
	<!-- Ticket  Style -->
	<link href="css/ticket.css" rel="stylesheet" type="text/css"/>
</head>

<body>

<?php

	//include "phpqrcode/qrlib.php";
	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
        	$token = filterHexString($_POST["token"]);
        	$inter_choice = $_POST["International"];
        	$gen_choice = $_POST["General"];


		if (($gen_choice != 'on') && ($inter_choice != 'on')){
			header('Location: http://54.215.194.44/GraduateOrientationS2014.php?error=true&token=' . $token );
			exit();
		}

        	openDatabase();
        	$result = mysql_query("SELECT * From attendee_tokens where token = '".$token."'");
		$row = mysql_fetch_row($result, MYSQL_ASSOC);
		$attendee_id = $row['user_id'];


		if ($row) {
			if ($gen_choice=='on') {
       		     		// "enroll in one";
  				$insert_result = mysql_query("
  					INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, `event_id`, `checked_in`) 
  					VALUES ('".$attendee_id."', '57', '0');");
			} else {
				$reset_result = mysql_query("
					DELETE FROM `".DB_SCHEMA."`.`eventcheckin` WHERE `attendee_id`='".$attendee_id."' and`event_id`='57';
				");
			}
			if ($inter_choice=='on') {
				//"enroll in Both";
  				$insert_result = mysql_query("
  					INSERT INTO `".DB_SCHEMA."`.`eventcheckin` (`attendee_id`, `event_id`, `checked_in`) 
  					VALUES ('".$attendee_id."', '56', '0');");
			} else {
				 $reset_result = mysql_query("
                                        DELETE FROM `".DB_SCHEMA."`.`eventcheckin` WHERE `attendee_id`='".$attendee_id."' and`event_id`='56';
                                ");
			}
		} // end if ($row)

		?><div class='hidden'><?php
		sendOrientationTicket($attendee_id);
		?>
		</div><?php


		$ticketurl = hash('sha256', $attendee_id . 'newsalt7868686');


		$ticketlink = 'http://54.215.194.44/getTicket.php?ticket=' . $ticketurl;

        	closeDatabase();
	}
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
                                        echo "<p><b>" . $row['firstname'] . " " . $row['lastname']. ",</b></p>";
                                        ?>
                                        <br>
					<p>Your ticket has been sent to: <b><?php echo $row['email']; ?></b></p>
                                        <p>You have enrolled in the following: </p>



                                	<?php if($inter_choice=='on'): ?>
						<p> AM - International Student Arrival Program - January 15, 2014</p>
                                	<?php endif; ?>



                                	<?php if($gen_choice=='on'): ?>
                                        	<p> PM - Graduate Orientation Program - January 15, 2014</p>
                                	<?php endif; ?>

					<br>
					<p>You will receive a detailed program, parking information, and locations of events one week prior to the event. Please check your email and contact Cheryl Cowan at <a href="mailto:cheryl.cowan@sjsu.edu">cheryl.cowan@sjsu.edu</a> if you do not receive further communication by January 9, 2014</p>


					<br><br>

					<p>If you have not received your ticket by email, click here to download your ticket:</p>
					<p><a href = '<?php echo $ticketlink;  ?>'>Download Ticket</a></p>

                                <?php else : ?>
                                        <p>Please Navigate to this page using the direct link from your email</p>
                                <?php endif; ?>
                        </div>
            </div>
        </div>
  </div>

</body>
</html>
