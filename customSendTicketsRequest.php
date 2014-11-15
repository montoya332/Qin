<?php
// PHP_DOC
// Input
// Output
// Description: TODO Merge with  customMailRequest.php
// customSendTicketRequest.php

require_once("common.php");

	$file = filterAlphaNumericString($_POST['filename']);
	//$file = "b7d4108c46f7aafd15ded3908f51b3fe.csv";
	$cmd = sprintf('php customSendTicketsOnRegister.php %s >> /home/ddelacruz/log/mail.log 2>&1 &', $file);

	shell_exec($cmd);

	//echo $cmd;

	echo "Emails are now being sent";

?>
