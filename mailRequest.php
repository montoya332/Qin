<?php
	//TODO: Check Login of User before sending out emails... possibily send hash(event_id) instead, so not to expose event id's
	require_once ("common.php");

	$event_id = filterNumberString($_POST['event_id']);

	$cmd = sprintf('php sendTickets.php %d >> /dev/null 2>&1 &', $event_id);

	shell_exec($cmd);

	//echo $cmd;

	echo "\n\nEmails are now being sent\n";

?>
