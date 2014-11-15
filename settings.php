<?php

// GLOBAL VARIABLES


//const  iddh


/***************************************************
Ip addres of application
Server IP used In:
	count	Name
	1	customSendTicketsOnRegister.php
	1	customSendTicketsOnRegister.php.save
	1	customSendTicketsOnRegister.php.save.1
	1	getTicketByStudentID.php
	3	getTicketByStudentID.php~
	2	getTicketByStudentID.php.old
	2	maillib.php
	2	manual.php
	2	manualRegister.php.old
	1	sendEventForm.php
	1	sendOrientationTicket.php
Already changed in files
***************************************************/
//const  $server_ip = "54.193.106.158";
//const SERVER_IP = "54.193.106.158";

define('SERVER_IP', '54.193.18.162/');
define('WEB_ROOT', 'web/');

/***************************************************
Email used to Send out Emails
email used in:
	count	Name
	1	jsphp.js
	1	maillib.php
Have not changed in files (function has a relay)
***************************************************/
define('APP_EMAIL', "sjsu.ticket@gmail.com");
define('APP_EMAIL_PASSWORD',"preetpal");


/***************************************************
Database Setings
Seen in Common.php
Have not changed in files
TODO: NEED provide tables and attributes including Views for peter 
***************************************************/
define('DB_USER',"root");
define('DB_PASSWORD', "senior2014");
define('DB_SCHEMA', "senior_project");

/***************************************************
Error
Failed to retrieve logged in user id
getAdminEvents
Params:action=getAdminEvents
***************************************************/


/********************************/

//Uploads folder

define('APP_UPLOAD_DIR', getcwd() . "\\uploads\\");



?>
