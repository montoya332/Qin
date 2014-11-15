<?php
// PHP_DOC
// INPUT: None
// OUTPUT: Call function
// DESCRIPTION: command to run email command and put it in the background
	require_once("common.php");

	$cmd = sprintf('php sendOrientationFollowup.php >> /dev/null 2>&1 &');

	shell_exec($cmd);

	//echo $cmd;

	echo "Emails are now being sent";

?>
