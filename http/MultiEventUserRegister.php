<?php session_start();
require_once("sitelib.php");
require_once("common.php");
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
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="alert alert-info">
                        Enrollment Form
                    </div>
                </div>
	    </div>
        <?php  if ( $_SERVER['REQUEST_METHOD'] == 'GET' ):
            //include "phpqrcode/qrlib.php";
            //MultiEventUserRegister?token=%20bba49c0c286dc2216f42162e2aa3d66e&reg[]=1&reg[]=3
            $token = filterHexString($_GET['token']);
            $eventID = filterHexString($_GET['reg']);
            $event_result = array();
            openDatabase();
            $result = mysql_query("SELECT * From attendee_tokens where token = '".$token."'");
            foreach ($eventID as &$value) {
                $event_result[] = mysql_query("SELECT event_id, event_name from events WHERE event_id=" . $value . " ORDER BY event_id ASC;");
            }
            closeDatabase();
            $result_array = array();
            $row = mysql_fetch_row($result, MYSQL_ASSOC);
        ?>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="well">
                        <h3>Q-in Event Organizer</h3>
			<br>
                        <?php if($row):
                            echo "<p>Welcome, " . $row['firstname'] . " " . $row['lastname']. "!</p>"; 
                        ?>
                            <br>
                            <p>
                                Check any of the sessions you plan to attend.
                            </p>
                            <form action="MultiEventUserRegister.php" method="post">
                                <?php 
                                    $outputIDForm = '<input type="text" name="token" id="token" value="'. $token . '" style="display:none">';
                                    echo $outputIDForm;
                                foreach ($event_result as &$value) {
                                         $singleEvent = mysql_fetch_row($value, MYSQL_ASSOC);
                                    echo '<input type="checkbox" name="choice[]" value="' . $singleEvent['event_id'] . '">' . urldecode($singleEvent['event_name']) . '</input>';
                                    echo "<br>";
                                }
                                ?>
                                <br>
                                <input type="submit" value="Submit" class="btn btn-primary">
                            </form>
                        <?php else : ?>
                            <p>Cant Retrieve User Information. Please contact event host for more information. </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
    <?php  elseif( $_SERVER['REQUEST_METHOD'] == 'POST' ):
        //include "phpqrcode/qrlib.php";
        if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
                $token = filterHexString($_POST["token"]);
                print_r($_POST);
                throw new Exception('Division by zero.');
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
                                        INSERT INTO `sjsu_quick_register`.`eventcheckin` (`attendee_id`, `event_id`, `checked_in`) 
                                        VALUES ('".$attendee_id."', '57', '0');");
                        } else {
                                $reset_result = mysql_query("
                                        DELETE FROM `sjsu_quick_register`.`eventcheckin` WHERE `attendee_id`='".$attendee_id."' and`event_id`='57';
                                ");
                        }
                        if ($inter_choice=='on') {
                                //"enroll in Both";
                                $insert_result = mysql_query("
                                        INSERT INTO `sjsu_quick_register`.`eventcheckin` (`attendee_id`, `event_id`, `checked_in`) 
                                        VALUES ('".$attendee_id."', '56', '0');");
                        } else {
                                 $reset_result = mysql_query("
                                        DELETE FROM `sjsu_quick_register`.`eventcheckin` WHERE `attendee_id`='".$attendee_id."' and`event_id`='56';
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
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                        <div class="well">
                            <h3>Enrollent Summary</h3>
                                <br>
                                <?php if($row):
                                    echo "<p><b>" . $row['firstname'] . " " . $row['lastname']. ",</b></p>";
                                ?>
                                        <br>
                                        <p>Your ticket has been sent to: <b><?php echo $row['email']; ?></b></p>
                                        <p>You have enrolled in the following: </p>
                                        <p><a href = '<?php echo $ticketlink;  ?>'>Download Ticket</a></p>
                                <?php else : ?>
                                        <p>Please Navigate to this page using the direct link from your email</p>
                                <?php endif; ?>
                        </div>
            </div>
        </div>
    <?php else : ?>
        <p>It may seem that we are having problems loading this page. Please contact the person that sent you the email that contained this link. </p>
    <?php endif; ?>
        </div>
</body>
</html>
