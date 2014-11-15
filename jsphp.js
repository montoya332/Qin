// ***************************************************/
// Encodes URL to transmit + and spaces
// ***************************************************/
function urlencode(str) {return encodeURI(str); }
function urldecode(str) {return decodeURI(str); }
function isValidRegExStr(str, regExStr) { return regExStr.test(str); }
var programmersInfo = 'Arturo Montoya arturo.montoya332@gmail.com' + '</BR>' + 'Darryl DelaCruz darrylndelacruz@gmail.com' + '</BR>' +
						'Phuoc Tran phuockimtran@yahoo.com' + '</BR>' + 'Erik Montoya erikcmontoya@hotmail.com';
 
function isValidWholeNumber(str)
{
	var regex = /^\s*\d+\s*$/;
	return regex.test(str);
}
function isValidEmail(str)
{
  var regex = /^[-_.a-z0-9]+@(([-_a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i;
  return regex.test(str);
}
function isValidName(str)
{
	var regex = /^[^ ][a-zA-Z ]+$/;
	return regex.test(str);
}

// *** Is executed once the webpage is fully loaded.
$(window).load(function () 
{
    SplashScreen();
});

// *** When document is ready
$(document).ready(function ()
{
    
});


/**
 * This shows a Pop-up modal given the contents of heading, body, and footer.
 * Another way to use this function is not pass in any parameters, in which 
 * case, it will display display the previous modal with previous parameters.
 * For example: 
 *    showPopupModal("heading, "body", "footer");
 *    hidePopupModal();
 *    // At some point later:
 *    showPopupModal(); // Redisplay previous modal
 */
function showPopupModal(heading, body, footer, pixelWidth)
{
	// No parameters?  Simply show previous modal and return
	if(!heading) {
		$('#actualPopupModalDivID').modal('show');
		return;
	}
    
	var footerContent = "";
	if(footer && "" != footer) {
		footerContent += '<div class="modal-footer"><form onsubmit="return false;">' + footer + '</form></div>';
	}
	
	$('#popupModalDivHeader').html(heading);
    $('#popupModalDivBody').html(body);
    $('#popupModalDivFooter').html(footer);
	
	// Now that Modal HTML is displayed, turn this HTML into actual Modal
    $('#popupModalId').modal('show');
    
    /*
	$('#popupModalId').modal({
		keyboard: true
	}).css({
		width: pixelWidth ? pixelWidth+'px' : '500px',
		'margin-left': function () { return -($(this).width() / 2); }
	});
    */
}

/**
 * This hides the popup Modal
 */
function hidePopupModal() 
{
	$('#popupModalId').modal('hide');
}

/**
 * This updates the Webpage's side-bar given HTML content
 */
function updateSideBar(theContent)
{
	$("#sideBarSiteDiv").html(theContent);
}

/**
 * This modifies the temporary HTML DIV used to output Error Messages or Temporary notifications to the user
 */
 var FADEOUT_TIMER_ID = 0;
 
function prependMainContent(theContent, timeout)
{
	// Cancel any previous timer
	clearTimeout(FADEOUT_TIMER_ID);
    
	//TODO when multiple calls are made, dismissible alert doesn't display for full 5 seconds
	var topDiv = $("#mainContentSiteDivTopMsg");
	topDiv.html(theContent).show();
	if(!timeout) {
		timeout = 3000;
	}
	if(timeout) {
		FADEOUT_TIMER_ID = setTimeout(function () {
			topDiv.fadeOut(250);
		}, timeout);
	}
}

/**
 * This is the area above the main content.
 * This can be used to affix certain content above the main content
 */
function updateMainContentTopDiv(theContent)
{
	$("#mainContentSiteDivTop").html(theContent);
	prependMainContent('');
}

/**
 * This updates the webpage's main content area
 */
function updateMainContent(theContent)
{
	$("#mainContentSiteDiv").html(theContent);
	prependMainContent('');
}

// This updates the Top Navigation Webpage Area
function updateTopBar(theContent)
{
	$("#TopBarSiteDiv").html(theContent);
}

// Gets a dismissable alert message to the user given its title, message, and alertType, which could be "info", "warning", or "error"
function getDismissibleAlert(title, msg, alertType)
{
	var alert = '<div class="alert alert-block alert-' + alertType + ' fade in">';
		alert += '<a class="close" data-dismiss="alert" href="#">&times;</a>';
		alert += '<strong>' + title + '</strong></BR>' + msg;
		alert += '</div>';
		
	return alert;
}

// Gets a wrapped text inside an "alert-info" div of Bootstrap CSS
function getWrappedText(text)
{
	var content = '<div class="alert alert-info">';
		content += text;
		content += '</div>';
		
	return content;
}

// This shows a splash screen
// display: If true, displays the splash screen, otherwise hides it
// message: Optional parameter of message, if null, "Loading . . ." message is displayed
function displayLoadingSplashScreen(display, message)
{
	(display) ? showPopupModal(message ? message : "Loading", "", "", "150") : hidePopupModal();
}

// Sends Ajax request
// action: The action to send to phpjs.php web-page
// postVars: The post variables to send to phpjs.php
// funcAfterSuccess, the function to execute after successful ajax response
//						This function is given AJAX's response
// funcUponFailure: Optional failure function to execute, otherwise generic
//					 failure message is displayed using prependMainContent()
function sendAjaxRequest(action, postVars, funcAfterSuccess, funcUponFailure)
{
	varsToSend = 'action=' + urlencode(action);
	if("" != postVars) {
		varsToSend += '&' + urlencode(postVars);
	}
	
	$.ajax({
		url: 'phpjs.php',
		data: varsToSend,
		dataType: 'json',
		type: 'post',
		success: function (response) 
		{
			displayLoadingSplashScreen(false);
			var htmlContent = "";
			if(response.ok)
			{
				funcAfterSuccess(response);
			}
			else
			{
				if(funcUponFailure) {
					funcUponFailure(response);
				}
				else {
					prependMainContent(getDismissibleAlert("Error", response.msg + "<BR/>"+action+"<BR/>Params:"+varsToSend, "danger"), 5000);
				}
			}
		}
	}); 
}

// This returns a generic form with a textarea inside of it.
// the span is used to determine the span of the textarea
// contentAfterForm is any content to display after the form, such as a button
// textareaContents is what the textarea input should be set to.
function getGenericForm(textAreaDivID, span, contentAfterForm, textareaContents)
{
	var form = '<div class="row"><div class="span' + span+1 + '">';
	form  += 	    '<div class="alert alert-success">';
	form  += 		'<textarea  style="resize:none" id="' + textAreaDivID + '" name="' + textAreaDivID + '" class="span' + span + '">';
	if(textareaContents != undefined) 
		form  +=			textareaContents
		
	form  +=		'</textarea></br>';
	form  += 		contentAfterForm;
	form  += 		'</div>';
	form  += '</div></div>';
	return form;
}

// This function is called when a user initiates a search from NavBar
function search()
{
	var searchStr = $('#searchStr').val();
	sendAjaxRequest("searchProduct", "searchStr="+searchStr,
						 function(response) {
							alert("Search not implemented yet!");
						 });
}

// This function is called to initiate logout, which sends AJAX request to destroy the session and reload the page.
function performLogout()
{
	sendAjaxRequest("logout", "", function(r) {window.location.reload(false);} );
}

// This function is called to attempt a login which picks up Username and Password Form setup by index.php
function attemptLogin()
{
	var postVars = "";
	postVars += 'username='  + $('#loginUsername').val();
	postVars += '&password=' + $('#password').val();
	
	sendAjaxRequest("login", postVars, 
					function(response) {
						updateTopBar(response.navBarData);
						updateSideBar(response.sideBarData);
                        showUserEvents();
						// updateMainContent(getDismissibleAlert("You've logged in!", "", "info"));
					},
					function(response) {
						prependMainContent(getDismissibleAlert("Error", response.msg, "warning"));
					});
}

function showRegistrationModal()
{
	var body = '<input class="form-control" type="text" placeholder="Email" name="newReg_email" value="" id="newReg_email" /></BR>\
				<input class="form-control" type="password" placeholder="Password" name="newReg_password" value="" id="newReg_password" /></BR>\
				<label>Your information</label>\
				<input class="form-control" type="text" placeholder="First Name" name="newReg_firstName" value="" id="newReg_firstName" /></BR>\
				<input class="form-control" type="text" placeholder="Last Name" name="newReg_lastName" value="" id="newReg_lastName" /></BR>\
				<label>Sign-up Passcode</label>\
				<input class="form-control" type="text" placeholder="Passcode" name="newReg_passcode" value="" id="newReg_passcode" /></BR>';
				
	var heading = 	'<button type="button" class="btn btn-success" onClick="javascript: registerUser()" name="submit"><i class="icon-share-alt icon-white"></i>&nbsp;<b>Register</b></button>\
					<button type="button" class="btn btn-warning" onClick="javascript: hidePopupModal()" name="submit"><i class="icon-remove icon-white"></i>&nbsp;<B>Cancel</b></button>';
					
	showPopupModal("Sign-Up", body, heading, "300");
}

function registerUser()
{
	// Get the variables from the <input> fields of showRegistrationModal()
	var username = $("#newReg_email").val();
	var password = $("#newReg_password").val();
	var fname = $("#newReg_firstName").val();
	var lname = $("#newReg_lastName").val();
	var passcode = $("#newReg_passcode").val();
    
	// Create a list of variables to send through POST to PHP:
	var postVars = "";
	postVars += "username="+username;
	postVars += "&password="+password;
	postVars += "&fname="+fname;
	postVars += "&lname="+lname;
	postVars += "&passcode="+passcode;
    
	// Hide the popup modal and update main content if registration succeeds
	hidePopupModal();
	sendAjaxRequest("registerUser", postVars, 
						function(r) {                            
                            updateTopBar(r.navBarData);
                            updateSideBar(r.sideBarData);
                            showUserEvents(function() {                                            
                                                prependMainContent(getDismissibleAlert("Registration Successful", "Welcome " + fname, "success"), 5000);
                                            } );
						},
                        function(r) {
                            var reason = "";
                            reason += "Sign-up Password is likely incorrect<BR/>";
                            reason += "(" + r.msg + ")";
                            
                            prependMainContent(getDismissibleAlert("Error", reason, "danger"), 5000);
                        }
					);
}










function QRHandler()
{
	var content = "<div class='well'>"
	content += "<form id='text_to_qr'>"
	content+='<textarea rows="3" id="comment" form="text_to_qr"> Enter emails </textarea>'
	content+='<p><buton class="btn btn-mini btn-primary" onclick="textbox_reader()" type="submit">submit</button></p>'
	content += "</form>"
	content += "</div>"
	
	updateMainContent(content);
}


function textbox_reader()
{
	var postVars = "";
	postVars += 'comment='  + $('#comment').val();
        sendAjaxRequest("createQRcode", postVars,
                        function(response) {
                        // Produce html we want to display in a variable called 'html'
                        var content = "<p>" + postVars + "</p>"
                        content += "<div class='well'>"
                        content += "<img src='" + response.filename + "' width='200' hight='200'></img>"
                        content += "</div>"
                        updateMainContent(content);
                     }
                    );
         
}

function myGraph()
{
	var postVars ="";
	postVars += 'demo='  + $('#demo').val();
	
	sendAjaxRequest("graphrequest", postVars, 
					function(response) {
						var content="";
						content+='<form>'
						content+='<iframe width="420" height="345"'
						content+='src="http://www.w3.org/2000/svg">'					
						content+='</iframe>'
						content+='</form>'
						
						updateMainContent(content);
					}
                    );
}

function MyInfo()
{
        var postVars ="";

        sendAjaxRequest("getAdminInfo", postVars,
                                        function(response) {
                                                var content = "<div class='well'>";
                                                content += "<h1>User Info</h1>";
                                                for(var i=0; i<response.userArray.length; i++) {
                                                        content += "<p>Name: " + response.userArray[i]["firstname"] + " " + response.userArray[i]["lastname"] + "</p>";
                                                        content += "<p>Email: " + response.userArray[i]["email"] + "</p>";
                                                        content += "</div>";
                                                }
                                                updateMainContent(content);
                                        }
                    );
}

function readlogfile(){
        var postVars ="";
        sendAjaxRequest("getMailLog", postVars,
                                        function(response) {
                                                var content = "<div class='well'>";
                                                content += response.filecontent;
                                                content += "</div>";
                                                updateMainContent(content);
                                        }
                    );
}


function MyHome()
{
	var content = "<div class='well'>"
	content += "<h1>Not implemented";
	content += "</div>"
	updateMainContent(content);
}


function UploadGuestHandler(Event_id)
{
	var content = "<div class='well'>";
	content += '<input type="file" name="file_upload" id="file_upload"/>';
	content += "</div>";
	//content += "<h1>" + Event_id + "</h1>";
	updateMainContent(content);

	$('#file_upload').uploadify({
		'swf'      : 'uploadify/uploadify.swf',
		'uploader' : 'csvParse.php',
		'formData' : {'event_id': Event_id},
		// Your options here
	        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            		alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        	},
		'onUploadSuccess' : function(file, data, response) {
           		RegisteredHandler(Event_id); 	
        	}	
        });


}

function RegisteredHandler(Event_id)
{
	var postVars ="";
	postVars += 'event_id=' + Event_id;
    
        sendAjaxRequest("Registered", postVars,
                        function(response) {
				var content = "<div class='well'>";
				content += '<button type="button" title="Upload Guest List" class="btn btn-default btn-lg" onClick="UploadGuestHandler(' + response.event_id + ')">'
				content += "<span class='glyphicon glyphicon-upload'></span>";
				content += '</button>';
				content += '<button type="button" title="Send EMails" class="btn btn-default btn-lg" onClick="MailHandler(' + response.event_id + ')">'
				content += "<span class='glyphicon glyphicon-send'></span>";
				content += '</button>';
				content += "<a href = 'csvEventAttendeesExport.php?event_id="+response.event_id+"'>";
				content += '<button type="button" title="Download" class="btn btn-default btn-lg" style="float:right;">'
				content += "<span class='glyphicon glyphicon-save'></span>";
				content += '</button>';
				content += "</a>";
				content += "<table class='table table-hover'>";
				content += "<thead>";
				content += "<th>SJSU ID#</th>";
				content += "<th>First Name</th>";
				content += "<th>Last Name</th>";
				content += "<th>Status</th>";
				content += "</thead>";
				content += "<tbody>";
                        	for(var i=0; i<response.registered.length; i++) {
					content += "<tr>";
					content += "<td>" + response.registered[i]['sjsu_id'] + "</td>";
					content += "<td>" + response.registered[i]['first_name'] + "</td>";
					content += "<td>" + response.registered[i]['last_name'] + "</td>";
  				        var s_status = response.registered[i]['is_checked_in'];
                			if (s_status == "Checked In") {
						content += "<td><span class='glyphicon glyphicon-ok'></span></td>";
                			}
					else {
						content += "<td><span class='glyphicon glyphicon-remove'></span></td>";
                			}
					content += "</tr>";
                 		}  
				content += "</tbody>";
				content += "</table>";       
				content += "</div>";
				updateMainContent(content);
                     }  
                    );


}


function MailHandler(event_id)
{
	$.ajax({
		type: "POST",
		url: "mailRequest.php",
		data: {"event_id":event_id},
		success: function (data){alert(data)},
		});
}

function EventHandler()
{
        sendAjaxRequest("getEvents", "",
                        function(response) {
                        // Produce html we want to display in a variable called 'html'
			var content = "<div class='well'>"
			content += "<h1>All Events</h1>"
			content += "<a href = 'dummyCreateQRCodes.php'>";
			content += '<button type="button" title="Live Stream" class="btn btn-default btn-lg">'
			content += "<i class='icon-eye-open'></i>";
			content += '</button>';
			content += "</a>";

			content += "</div>"
                        // response.integers.length can be accessed to find the array size
                        // response.integers[] can be accessed using index
                        // End the HTML table


			content += '<div class="well">';
			content += '<div class="panel-group" id="accordion">';

                        for(var i=0; i<response.sample_events.length; i++) {
                        	content += '      <div class="panel panel-default">';
                        	content += '           <div class="panel-heading">';
                        	content += '              <h4 class="panel-title">';
                        	content += '             <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse' + i + '">';
                                content += response.sample_events[i]['event_name']
                        	content += '                </a>';
                        	content += '              </h4>';
                        	content += '            </div>';
                        	content += '            <div id="collapse' + i + '" class="panel-collapse collapse">';
                        	content += '              <div class="panel-body">';
				content += '<p>'
				content += '<i class="icon-calendar"></i> '
                                content += response.sample_events[i]['date_time']
				content += '</p>'
				content += '<p>'
				content += '<button type="button" title="Results" class="btn btn-default btn-lg" onClick="RegisteredHandler(' + response.sample_events[i]['event_id'] + ')">'
				content += '<i class="icon-th-list"></i>'
				content += '</button>'
				content += '</p>'
                        	content += '              </div>';
                        	content += '            </div>';
                        	content += '          </div>';
                        }

                        content += '        </div></div>';

                        updateMainContent(content);
                     }  
                    );

}

function ContactHandler()
{
var header = '<center><h1>Android App & Web Developers</h1></center>';
	sendAjaxRequest("getSampleNames", "",
        		function(response) {
			// Produce html we want to display in a variable called 'html'
			var content = "<table class='table table-hover'>";
			content += "<thead>";
			content += "<tr>";
			content += "<th></th>"
			content += "<th>Name</th>"
			content += "<th>Email</th>"
			content += "<th>Role</th>"
            content += "<th>Profile</th>"
			content += "</tr>";
			content += "</thead>";
			content += "<tbody>";
			
			// response.integers.length can be accessed to find the array size
			// response.integers[] can be accessed using index
			for(var i=0; i<response.sample_names.length; i++) {
				content += "<tr>";
				i+1;
				content += "<td>" + '<div> <img src="developers_images/'+response.sample_names[i]['name']+'.jpg" width="203" height="200" class="circular" style="height: auto; display: inline;">  </div>' + "</td>";
				content += '<td>' + response.sample_names[i]['name'] + '</td>';
				content += '<td>' + response.sample_names[i]['email'] + '</td>';
				content += '<td>' + response.sample_names[i]['role'] + '</td>';
                content += '<td>' + response.sample_names[i]['link'] + '</td>';
				content += "</tr>";
			}
			content += "</tbody>";
			// End the HTML table
			content += "</table>"
			//content += '  <div> <img src="developers_images/Arutro Montoya.jpg" width="203" height="200" class="circular" style="height: auto; display: inline;">  </div>';	
			//content += '  <div> <img src="developers_images/Darryl DelaCruz.jpg" width="203" height="200" class="circular" style="height: auto; display: inline;">  </div>';	
			//content += '  <div> <img src="developers_images/Phouc Tran.jpg" width="203" height="200" class="circular" style="height: auto; display: inline;">  </div>';	
			//content += '  <div> <img src="developers_images/Erik Montoya.jpg" width="203" height="200" class="circular" style="height: auto; display: inline;">  </div>';	

			
			updateMainContent(header + content);
                     }	
                    );

}

function DBHandler()
{
	sendAjaxRequest("db_info", "",
        		function(response) {
			var content = "";
			content += "<table class='table table-hover'>";
                        content += "<thead>";
                        content += "<tr>";
                        content += "<th>#</th>"
                        content += "<th>a</th>"
                        content += "<th>b</th>"
                        content += "<th>c</th>"
                        content += "</tr>";
                        content += "</thead>";
                        content += "<tbody>";
                        for(var i=0; i<response.info.length; i++) {
                                content += "<tr>";
                                content += "<td>" + (i+1) + "</td>";
                                content += '<td>' + response.info[i][0] + '</td>';
                                content += '<td>' + response.info[i][1] + '</td>';
                                content += '<td>' + response.info[i][2] + '</td>';
                                content += "</tr>";
                        }
                        content += "</tbody>";
                        // End the HTML table
                        content += "</table>"
			updateMainContent(content);

			}
			);
}

function WebInfoHandler()
{
	var content = "<div class='well'>"
	content += "<h2>Website Info</h2>"
	content += "</div>"
	content += '<div id="carousel-example-generic" class="carousel slide">'
	content += '<!-- Indicators -->'
	content +=  '<ol class="carousel-indicators">'
	content +=     '<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>'
	content +=     '<li data-target="#carousel-example-generic" data-slide-to="1"></li>'
	content +=     '<li data-target="#carousel-example-generic" data-slide-to="2"></li>'
	content +=   '</ol>'

	content +=   '<!-- Wrapper for slides -->'
	content +=   '<div class="carousel-inner">'
	content +=     '<div class="item active">'
	content +=       '<img src="..." alt="...">'
	content +=       '<div class="carousel-caption">'
	content +=       '...'
	content +=       '</div>'
	content +=     '</div>'
	content +=     '...'
	content +=   '</div>'

	content +=   '<!-- Controls -->'
	content +=   '<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">'
	content +=     '<span class="icon-prev"></span>'
	content +=   '</a>'
	content +=   '<a class="right carousel-control" href="#carousel-example-generic" data-slide="next">'
	content +=     '<span class="icon-next"></span>'
	content +=  ' </a>'
	content += '</div>'
	updateMainContent(content);
}
/**
 * This shows a Pop-up modal given the contents of heading, body, and footer.
 * Another way to use this function is not pass in any parameters, in which 
 * case, it will display display the previous modal with previous parameters.
 * For example: 
 *    showPopupModal("heading, "body", "footer");
 *    hidePopupModal();
 *    // At some point later:
 *    showPopupModal(); // Redisplay previous modal
 */
function PopupModalSplashScreen(heading, body, footer, pixelWidth)
{
	// No parameters?  Simply show previous modal and return
	if(!heading) {
		$('#actualPopupModalDivID').modal('show');
		return;
	}
    
	var footerContent = "";
	if(footer && "" != footer) {
		footerContent += '<div class="modal-footer"><form onsubmit="return false;">' + footer + '</form></div>';
	}
	
	$('#popupModalDivHeader').html(heading);
    $('#popupModalDivBody').html(body);
    $('#popupModalDivFooter').html(footer);
	
	// Now that Modal HTML is displayed, turn this HTML into actual Modal
    showSplashScreen(3000);//$('#popupModalId').modal('show');
    
    /*
	$('#popupModalId').modal({
		keyboard: true
	}).css({
		width: pixelWidth ? pixelWidth+'px' : '500px',
		'margin-left': function () { return -($(this).width() / 2); }
	});
    */
}
function SplashScreen()
{
			var header = '<center>Android App & Web Developers</center>';
	sendAjaxRequest("getSampleNames", "",
        		function(response) {
			var content = "<table class='table table-hover'>";
			content += "<thead>";
			content += "<tr>";
			content += "<th>Name</th>"
			content += "<th>Email</th>"
			content += "</tr>";
			content += "</thead>";
			content += "<tbody>";
	
			for(var i=0; i<response.sample_names.length; i++) {
				content += "<tr>";
				i+1;
				content += '<td>' + response.sample_names[i]['name'] + '</td>';
				content += '<td>' + response.sample_names[i]['email'] + '</td>';
				content += "</tr>";
			}
			content += "</tbody>";
			content += "</table>"// End of table
			content += "<center><img src='developers_images/Senior_Team2.jpg' width='400' ></img></center>"; //Image of Developer team
			PopupModalSplashScreen(header, content , 'SJSU CmpE Almuni', 500);
                     }	
                    );

}
/**
 * This modifies the temporary HTML DIV used to output Error Messages or Temporary notifications to the user
 */
 var FADEOUT_TIMER_ID_Splash = 0;
 
function showSplashScreen(timeout)
{
	// Cancel any previous timer
	clearTimeout(FADEOUT_TIMER_ID_Splash);
    
	//TODO when multiple calls are made, dismissible alert doesn't display for full 5 seconds
	var topDiv = $("#popupModalId");
	topDiv.modal('show');
	if(!timeout) {
		timeout = 3000;
	}
	if(timeout) {
		FADEOUT_TIMER_ID_Splash = setTimeout(function () {
			topDiv.fadeOut(250); hidePopupModal();
		}, timeout);
	}
}





