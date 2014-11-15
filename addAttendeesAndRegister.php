
<?php //session_start();
//require_once("sitelib.php");
require_once("settings.php");
require_once("common.php");

?>
<html>
<head>
        <script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>


        <!-- Uploadify Stuff -->
        <script type="text/javascript" src="uploadify/jquery.uploadify.min.js"></script>
        <link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">




	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css" />
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- Our JS -->
	<script type="text/javascript" src="jsphp.js"></script>
	<script type="text/javascript" src="custom_attendees_and_register.js"></script>
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
			<h3>Spring 2014 Orientation</h3>
			<p>Select a .CSV file that contains the list of students and the sessions they wish to attend at Orientation</p>
			<input type="file" name="attendees_upload" id="custom_attendees_upload"/>
			<button onclick='sendemails()'>Send Emails</button>

        		<div id="newAttendees"></div>
			<input type="text" id="attendeesFileName" style="visibility:hidden"></input>
                        </div>
            </div>
        </div>
  </div>
</body>
</html>
