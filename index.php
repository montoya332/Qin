<?php session_start();
require_once("sitelib.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">	<!-- Remove -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> <!-- Remove -->
<head>
	<meta name="Description" content="Q-In" />
	<meta name="Keywords" content="Quick Register Events" />
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Distribution" content="Global" />
	<meta name="author" content="Darryl, Erik , Arturo , Phuoc" />
	<meta name="Robots" content="index,follow" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SJSU Event Organizer</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

	<!-- Include JQuery File  -->
	<link href="jquery/jquery-ui.min.css" rel="stylesheet" type="text/css"/>                                                           
	<script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="jquery/jquery-ui.min.js"></script>
	<script type="text/javascript" src="jquery/jquery-datetime.js"></script>
    
	<!-- Uploadify Stuff -->
	<script type="text/javascript" src="uploadify/jquery.uploadify.min.js"></script>
	<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css">

	<!-- Google Charts -->
	<!--
	<script type='text/javascript' src='https://www.google.com/jsapi'></script>
	<script type="text/javascript">
			google.load('visualization', '1', {'packages': ['gauge', 'corechart', 'table']});
	</script>
	-->
    
    <!-- HighCharts  -->
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src="highcharts/highcharts.js" type="text/javascript"></script>
    <script src="highcharts/highcharts-more.js"></script>
    <!-- <script src="highcharts/modules/exporting.js" type="text/javascript"></script> -->
	
	<!-- Bootstrap -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css" />
	<script src="bootstrap/js/bootstrap.min.js"></script>
    
	<!-- Our JS -->
	<script type="text/javascript" src="jsphp.js"></script>
    <script type="text/javascript" src="event_create.js"></script>
    <script type="text/javascript" src="event_view.js"></script>
    <link rel="stylesheet" href="css/ticket.css" type="text/css" />

    <!-- Other miscellaneous scripts: -->
    <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="jquery.localtime.js"></script>
    <!-- <script type="text/javascript" src="timeago.js"></script> -->
  
</head>
<body>

    <!-- Popup modal -->
    <div class="modal" id="popupModalId">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal" onClick="javascript: hidePopupModal();">x</a>
                <h3><span id="popupModalDivHeader">Header</span></h3>
            </div>
        <div class="modal-body"><span id="popupModalDivBody">Body</span></div>
        <div class="modal-footer"><span id="popupModalDivFooter">Footer</span></div>
    </div></div></div>
        
    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">
		
			<div class="navbar-header">
			  <a class="navbar-brand" href="#">QR-Event</a>
			</div>

			<div id="TopBarSiteDiv">
                <div class="navbar-collapse collapse">
                    <?php echo getNavBar(); ?>
                </div><!--/.nav-collapse -->
            </div>

		</div>
    </div>
	
    <!-- The container with the left menu and the main content on the right -->
	<div class="container">   

        <div class="row">
            <div class="col-md-2">
                <div class="sidebar">
                    <div class="alert alert-info">
                        <div id="sideBarSiteDiv">
                            <?php echo getSideBar(); ?>
                        </div>
                    </div>
                </div>
            </div>
			
            <div class="col-md-10">
                <div id="mainContentSiteDivTopMsg"></div>	<!-- Semi permanent message appended before main content area of the page -->
                <div id="mainContentSiteDivTop"></div>	    <!-- Area above the main content (maybe for a menu?) -->
                <div id="mainContentSiteDiv">				<!-- Main content area for the user -->
                        <div class="jumbotron">
                            <h1>SJSU Event Organizer</h1>
                            <BR/>
                            <p>
                            SJSU Event Organizer is a San Jose State University developed event organizer and check-in system.
                            The application helps create events, manage check-ins and provide real-time event statistics
                            including total guests and other meaningful data to help the event organizers provide
                            a world-class experience to the San Jose State University events.
                            </p>
                            <BR/>
                            <p><center>
								<img src='sampleTicket.png' width="90%"></img>
                            </center></p>
                        </div>
                </div>
                
            </div>
        </div>
		
        <hr>
        <footer>
            <p>&copy; 2013 -- San Jose State University</p>
        </footer>
	</div> 
	
	
</body>
</html>
