
/**
 * After the navs are setup, this method will load the actual content
 * This is a single entry point that displayed one of the contents
 * inside of it based on the contentId
 */
function eventsButtonHandler(contentId)
{
    if (0 == contentId) {
        showUserEvents();
    }
    else if (1 == contentId) {
        showUserEventCreate();
    }
    else if (2 == contentId) {
        showUserMultiEvents();
    }
    else if (3 == contentId) {
        showUserMultiEventCreate();
    }
}

/**
 * This is called by showUserEvents() to get content of ONE event
 * @param ev The event structure with name, datetime, id, and info of the event
 * @param expandedView  If true, this event will show expanded view (rather than collapsed)
 */
function getEventContent(ev, expandedView)
{
    var content = "";
    if (expandedView) {
        expandedView = "collapse in";
    }
    else {
        expandedView = "collapse";
    }
    
	content += '<div class="panel panel-default">\
					<div class="panel-heading">\
						<h4 class="panel-title">\
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse' + ev.event_id + '">' +
								'<B>' + urldecode(ev.event_name) + '&nbsp;&nbsp; (' + urldecode(ev.date_time) + ')</B>' +
							'</a>' +
                                 '<div width="100px" align="right">' +
                                 '&nbsp;&nbsp;<button type="button" title="Realtime viewer" class="btn btn-default" onClick="realtimeEventViewer(' + ev.event_id + ')"><span class="glyphicon glyphicon-dashboard"></span>&nbsp;</button>' + 
                                 '&nbsp;&nbsp;<button type="button" title="Results" class="btn btn-info" onClick="RegisteredHandler(' + ev.event_id + ')"><span class="glyphicon glyphicon-th-list"></span>&nbsp;</button>' + 
                                 '&nbsp;&nbsp;<button type="button" title="Delete the event" class="btn btn-warning" onClick="deleteAdminEvent(' + ev.event_id + ')"><span class="glyphicon glyphicon-trash"></span>&nbsp;Delete</button>' +
                                '</div>' + 
                        '</h4>\
					</div>' + 
					'<div id="collapse' + ev.event_id + '" class="panel-collapse ' + expandedView + '">\
						<div class="panel-body">' +                            
							urldecode(ev.event_info) +                            
						'</div>\
					</div>\
				</div>';
    
    return content;
}

/**
 * This function shows the user events that were created
 * @param funcAfterShowCompletes  The callback function to invoke after loading finishes
 */
function showUserEvents(funcAfterShowCompletes)
{
    sendAjaxRequest("getAdminEvents", "",
                         function(response) {
                            var content = "";
                            if (0 == response.eventsArray.length) {
                                content += '<div class="jumbotron"><H1>Hello there!</H1>';
                                content += '<HR><p>I see that you are new here, and you have not created any events yet.</p>';
                                content += '<HR><p>Begin the experience by creating a new QR-Event!</p>';
                                content += '<HR></DIV>';
                            }
                            else {
                                content += '<div class="panel panel-primary"><div class="panel-heading">\
                                                <h3><center>Your Events</center></h3>\
                                            </div></div>';

								content += '<div class="panel-group" id="accordion">';
                                for (var i = 0; i < response.eventsArray.length; i++) {
                                    content += getEventContent(response.eventsArray[i], 0==i);
                                }
								content += '</div>';
                            }
                            updateMainContent(content);
                            
                            if (funcAfterShowCompletes) {
                                funcAfterShowCompletes();
                            }
                         });
}

/**
 * This shows the form fields to be able to create a new event
 */
function showUserEventCreate()
{
    var content = "";

    var length = tinymce.editors.length;
    for (var i=0; i < length; i++) {
        tinyMCE.execCommand('mceRemoveControl',false, tinymce.editors[i].id); 
    };
    
    /* Event name and time */
    content += '<form class="form-horizontal" role="form">';
        content += '<div class="form-group">';
        content += '<label for="userInputEventName" class="col-sm-2 control-label">Event Name</label>';
        content += '<div class="col-sm-5">';
        content += '<input type="text" class="form-control" id="userInputEventName" placeholder="Event Name">';
        content += '</div><div class="col-sm-3">'
        content += '<button type="button" class="btn btn-success btn-lg" href="#" onClick="javascript: verifyCreateNewEvent()"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Create Event</button>';
        content += '</div></div>';
        
        content += '<div class="form-group">';
        content += '<label for="userInputEventTime" class="col-sm-2 control-label">Event Time</label>';
        content += '<div class="col-sm-5">';
        content += '<input type="text" class="form-control" id="userInputEventTime" placeholder="Event Time">';
        content += '</div></div>';
    content += '</form><HR>';
    
    
    content += '<textarea class="eventTinymce" id="userInputEventInfo"></textarea><HR>';


// imhere

	content += '<table class="table table-hover"><TR><TD>';

        
    content += '</TD><TD>';
	content += '</TD></TR></table>';
	
    updateMainContent('<div class="well"><BR/>' + content + '</div>');
    



    // After publishing content, convert textarea to datetime picker
    $('#userInputEventTime').datetimepicker({ dateFormat: 'yy-mm-dd' });
    
    // After writing HTML content, convert the textarea to TinyMCE based text
    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "eventTinymce",
        
        plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "bold italic underline strikethrough forecolor removeformat hr| alignleft aligncenter alignright alignjustify | formatselect fontsizeselect",
        toolbar2: "cut copy paste | table bullist numlist | outdent indent blockquote | undo redo | link unlink preview fullscreen | spellchecker nonbreaking",

        menubar: false,
        toolbar_items_size: 'small'
    });
    
    var ed = tinymce.get('userInputEventInfo');
    if (ed) {
        ed.setContent('');
    }
}

/**
 * This function is called by the user clicking the create new event button.
 * We will grab the input values and create the event
 */
function verifyCreateNewEvent()
{
    var exitPopupModal = "";
    exitPopupModal += '<button type="button" class="btn btn-warning btn-lg" href="#" onClick="javascript: hidePopupModal()">';
    exitPopupModal += '<i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;Edit</button>';
        
	var eventName = $("#userInputEventName").val();
	var eventTime = $("#userInputEventTime").val();
	var eventInvitees = $("#userInputEventInvitees").val();
	var eventInfo = '<h3>' + eventName + '</h3> (' + eventTime + ')<HR>' + 
                    tinymce.get('userInputEventInfo').getContent();
    
    if (eventName == "") { showPopupModal("Error", "You must input the event name.", exitPopupModal); }
    else if (eventTime == "") { showPopupModal("Error", "You must input the event time.", exitPopupModal); }
    else if (eventInfo == "") { showPopupModal("Error", "You must input the event information to send out to guests.", exitPopupModal); }
    else if (eventInvitees == "") { showPopupModal("Error", "You must input the event attendees.", exitPopupModal); }
    else {
        var button = "";
        button += exitPopupModal;
    	
        button += '<button type="button" class="btn btn-success btn-lg" href="#" onClick="javascript: commitCreateNewEvent()">';
        button += '<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Finalize Event</button>';
    	
        showPopupModal("Verify Event Details", eventInfo, button);
    }
}   

/**
 * After a form fields are validated, this will send the event info to the server
 * to create the event
 */
function commitCreateNewEvent()
{
    var timeout = 10 * 1000;
    
	var eventName = $("#userInputEventName").val();
	var eventTime = $("#userInputEventTime").val();
	var eventInvitees = $("#userInputEventInvitees").val();
	var eventInfo = tinymce.get('userInputEventInfo').getContent();
	var filename = $("#attendeesFileName").val();
    
    var postVars = "";
    postVars += "name=" +urlencode(eventName);
    postVars += "&time="+(eventTime);
    postVars += "&list="+urlencode(eventInvitees);
    postVars += "&info="+urlencode(eventInfo);
    postVars += "&file="+urlencode(filename);

    sendAjaxRequest("createAdminEvent", postVars,
                         function(response) {
                            // Display events page after creating the event
                            showUserEvents(function() {                            
                                                prependMainContent(getDismissibleAlert("All Done", eventName + " has been created!", "success"), timeout);
                                            }
                                           );
                         });
}


/**
 * After a form fields are validated, this will send the event info to the server
 * to create the event
 */
function commitCreateNewMultiEvent()
{
    var timeout = 10 * 1000;
    
	var eventInfo = tinymce.get('userInputEventInfo').getContent();
    
        var eventName = $("#userInputEventName").val();
        var eventInvitees = $("#userInputEventInvitees").val();
        var eventselection = [];
        var eventIDsCSV = "";

        $('#eventCheckbox :checked').each(function() {
                eventIDsCSV += $(this).val();
		eventIDsCSV += ",";
        });

    var postVars = "";




    postVars += "name=" +urlencode(eventName);
    postVars += "&info="+urlencode(eventInfo);
    postVars += "&eventids="+urlencode(eventIDsCSV);

    sendAjaxRequest("createAdminMultiEvent", postVars,
                         function(response) {
                            // Display events page after creating the event
                            showUserEvents(function() {                            
                                                prependMainContent(getDismissibleAlert("All Done", eventName + " has been created!", "success"), timeout);
                                            }
                                           );
                         });
}


/**
 * Given an event id, this will delete the event
 */
function deleteAdminEvent(id)
{
    var timeout = 10 * 1000;
    
    showPopupModal("Ooops", "Cannot delete for this preview version", "");
    return;
    
    sendAjaxRequest("deleteAdminEvent", "eventId="+id,
                         function(response) {
                            showUserEvents(function() {                            
                                                prependMainContent(getDismissibleAlert("All Done", "Event has been deleted!", "warning"), timeout);
                                            }
                                           );
                         });
}



function verifyEventInvite()
{
    var exitPopupModal = "";
    exitPopupModal += '<button type="button" class="btn btn-warning btn-lg" href="#" onClick="javascript: hidePopupModal()">';
    exitPopupModal += '<i class="glyphicon glyphicon-edit"></i>&nbsp;&nbsp;Edit</button>';

        var eventName = $("#userInputEventName").val();
        var eventInvitees = $("#userInputEventInvitees").val();
        var eventselection = [];
        $('#eventCheckbox :checked').each(function() {
                eventselection.push($(this).val());
        });

        var eventInfo = '<h3>' + eventName + '</h3><p> Events' + JSON.stringify(eventselection) + '</p><HR>' +
                    tinymce.get('userInputEventInfo').getContent();

    if (eventName == "") { showPopupModal("Error", "You must input the event name.", exitPopupModal); }
    else if (eventInfo == "") { showPopupModal("Error", "You must input the event information to send out to guests.", exitPopupModal); }
    else if (eventselection == []) { showPopupModal("Error", "You must select Events.", exitPopupModal); }
    else if (eventInvitees == "") { showPopupModal("Error", "You must input the event attendees.", exitPopupModal); }
    else {
        var button = "";
        button += exitPopupModal;

        button += '<button type="button" class="btn btn-success btn-lg" href="#" onClick="javascript: commitCreateNewMultiEvent()">';
        button += '<i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Finalize Event</button>';

        showPopupModal("Verify Event Details", eventInfo, button);
    }
}

function getSelectEventContent(ev)
{
    var content = "";
    content += "<input type='checkbox' name='eventselection' value='" + ev.event_id + "'>";
    content += " ";
    content += urldecode(ev.event_name);
    content += " - ";
    content += urldecode(ev.date_time);
    content += "<br>";
    return content;
}



/**
 * This shows the form fields to be able to create a new multi event
 */
function showUserMultiEventCreate()
{


    sendAjaxRequest("getAdminEvents", "",
                         function(response) {
        var content = "";

    var length = tinymce.editors.length;
    for (var i=0; i < length; i++) {
        tinyMCE.execCommand('mceRemoveControl',false, tinymce.editors[i].id);
    };

    var pagetitle = "";
    pagetitle += '<div class="panel panel-primary">';
    pagetitle += '<div class="panel-heading">';
    pagetitle += '<h3>';
    pagetitle += '<center>Event Invite</center>';
    pagetitle += '</h3>';
    pagetitle += '</div>';
    pagetitle += '</div>';

    /* Event name */
    content += '<form class="form-horizontal" role="form">';
        content += '<div class="form-group">';
        content += '<label for="userInputEventName" class="col-sm-3 control-label">Group Invite Name</label>';
        content += '<div class="col-sm-5">';
        content += '<input type="text" class="form-control" id="userInputEventName" placeholder="Q-IN Event Invite">';
        content += '</div><div class="col-sm-3">'
        content += '</div></div>';
    content += '</form><HR>';


    content += '<textarea class="eventTinymce" id="userInputEventInfo"></textarea><HR>';
        content += '<table class="table table-hover"><TR><TD>';
    content += '</TD><TD>';
        content += '</TD></TR></table>';

                            if (0 == response.eventsArray.length) {
                                content += '<p>Please Create Events to include</p>';
                            }
                            else {
                                content += '<div class="panel-group" id="accordion">';
                                content += '<div id="eventCheckbox" class="checkbox">';
                                content += "<label>";
                                for (var i = 0; i < response.eventsArray.length; i++) {
                                    content += getSelectEventContent(response.eventsArray[i]);
                                }
                                content += "</label>";
                                content += '</div>';
                                                                content += '</div>';
                            }
       content += '<button type="button" class="btn btn-success btn-lg" href="#" onClick="javascript: verifyEventInvite()"><i class="glyphicon glyphicon-envelope"></i>&nbsp;&nbsp;Create Event</button>';

    updateMainContent( pagetitle + '<div class="well"><BR/>' + content + '</div>');

    // After writing HTML content, convert the textarea to TinyMCE based text
    tinymce.init({
        mode : "specific_textareas",
        editor_selector : "eventTinymce",

        plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste fullpage textcolor"
        ],

        toolbar1: "bold italic underline strikethrough forecolor removeformat hr| alignleft aligncenter alignright alignjustify | formatselect fontsizeselect",
        toolbar2: "cut copy paste | table bullist numlist | outdent indent blockquote | undo redo | link unlink preview fullscreen | spellchecker nonbreaking",

        menubar: false,
        toolbar_items_size: 'small'
    });

    var ed = tinymce.get('userInputEventInfo');
    if (ed) {
        ed.setContent('');
    }
                         });
}



function showUserMultiEvents(funcAfterShowCompletes)
{
    sendAjaxRequest("getAdminMultiEvents", "",
                         function(response) {
                            var content = "";
                            if (0 == response.multieventsArray.length) {
                                content += '<div class="jumbotron"><H1>Hello there!</H1>';
                                content += '<HR><p>I see that you are new here, and you have not created any Multi-Events yet.</p>';
                                content += '<HR><p>Begin the experience by creating a new Multi-Event</p>';
                                content += '<HR></DIV>';
                            }
                            else {
                                content += '<div class="panel panel-primary"><div class="panel-heading">\
                                                <h3><center>Your Multi Events</center></h3>\
                                            </div></div>';

				content += '<div class="panel-group" id="accordion">';
                                for (var i = 0; i < response.multieventsArray.length; i++) {
                                    content += '<p>' + JSON.stringify(response.multieventsArray[i]) + '</p>';
                                    //content += getMultiEventContent(response.multieventsArray[i], 0==i);
                                }
				content += '</div>';
                            }
                            updateMainContent(content);
                            
                            if (funcAfterShowCompletes) {
                                funcAfterShowCompletes();
                            }
                         });
}


