<?php
// PHP_DOC
// INPUT:  $_POST['filename']
// OUTPUT: "Emails are now being sent" as string
// DESCRIPTION:  Initiates call to sendEventForm.php with filename as argument, throws output to log file, throws it in background. TODO: Merge with customSendTicketsRequest.php
// customMailRequest.php



	require_once("common.php");

	$file = filterAlphaNumericString($_POST['filename']);
	//$file = "b7d4108c46f7aafd15ded3908f51b3fe.csv";
	$cmd = sprintf('php sendEventForm.php %s >> /home/ddelacruz/log/mail.log 2>&1 &', $file);

	shell_exec($cmd);

	//echo $cmd;

	echo "Emails are now being sent";

?>
