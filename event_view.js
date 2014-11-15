var EVENT_VIEWER_SPEEDOMETER_UPDATE_RATE_MS = 2 * 1000;
var EVENT_VIEWER_SPEEDOMETER_MAX = 30;

/**
 * This is the entry point of the realtime event viewer
 * User gets here after clicking the "View" button for an event.
 * We setup two nested DIVs to display the attendee list and speedometer
 */
function realtimeEventViewer(eventId)
{
    /* Setup the DIVs that will display the Graphs and attendee list */
    var content = "";
    var graphDivName = "graphDivAttendeeInfo";
    var tableDivName = "tableDivAttendeeList";
    
    /* Top graphs */
    content += '<div id="' + graphDivName + '">';
    content += "<h1>Loading...</h1>";
    content += "</div>";
    
    /* Bottom table (list) */
    content += '<div id="' + tableDivName + '">';
    content += "<h1>Loading...</h1>";
    content += "</div>";
    updateMainContent(content);
    
    /* The DIVs are now setup, so populate them with the data */
    evDrawGraphAndAttendeeTable(eventId, graphDivName, tableDivName);					  
}

/**
 * Given DIV Ids of the graph and attendee table, this function will get the
 * event attendee list from the server and write the contents for the graph
 * and the attendee table.
 * @param eventId  The event ID to write the data for
 */
function evDrawGraphAndAttendeeTable(eventId, graphDivName, tableDivName)
{
    var data = "eventId="+ eventId;
    sendAjaxRequest("getEventAttendeeList", data, 
                    function(rsp) { 
                        evCreateTableAndGraph(rsp, eventId, graphDivName);
                        evCreateAttendeeTable(rsp, eventId, tableDivName) 
                    });
}

/**
 * This function will draw the graphs based on the user data
 * @param rsp The server response of attendee list 
 */
function evCreateTableAndGraph(rsp, eventId, divName)
{
    var content = "";

    var totalCount = rsp.list.length;
    var checkinCount = 0;
    
    for (var i = 0; i < totalCount; i++) {
        var checkedIn = (rsp.list[i].checked_in == "1");
        if (checkedIn) {
            checkinCount++;
        }
	};
    
    var highchartDiv = '<div id="highchartContainer" style="min-width: 150px; height: 400px;"></div>';
                        
    content += '<table class="table table-bordered"><tr>';
    content += '<td style="min-width: 300px;">' + evGetCheckinCountAndBarGraph(checkinCount, totalCount) + '</td>';
    content += '<td style="min-width: 300px;"><center>' + highchartDiv + '</center></td>';
    content += '</tr></table>';
    
	$('#' + divName).html(content);
    evCreateSpeedometer("highchartContainer", eventId);
}

/**
 * This retrieves the HTML content of bar-graph and table that displays
 * the number of expecting attendees, the remaining count, and actual count
 */
function evGetCheckinCountAndBarGraph(checkinCount, totalCount)
{
    var content = "";
    
    content += '\
    <table id="checkedInTableId" class="table table-hover">\
    <tr><td colspan="2"><center><h4>Checkin Progress</h4></center></td></tr>\
    <tr><td class="progbar" colspan="2"><span id="barGraphTableId">' + evGetProgressBar(100 * checkinCount / totalCount) + '</span></td></tr>\
    <tr class="warning"><td><B>Expecting :</B></td><td><span class="badge" id="checkedInTableTotalCount">' + totalCount + '</span></td></tr>\
    <tr class="success"><td><B>Actual :</B></td><td><span class="badge" id="checkedInTableCheckinCount">' + checkinCount + '</span></td></tr>\
    <tr class="active"><td><B>Remaining :</B></td><td><span class="badge" id="checkedInTableRemainingCount">' + (totalCount - checkinCount) + '</span></td></tr>\
    <tr><td colspan="2">&nbsp;</td></tr>\
    <tr><td colspan="2"><center><h4>Last Check-ins</h4></center></td></tr>\
    <tr><td colspan="2" class="lastcheckin"><center><No one></center></td></tr>\
    </table>';
    
    return content;
}

/**
 * This will either increment, or decrement the bar graph and table cell values
 * created by evGetCheckinCountAndBarGraph().
 * @param incr  If true, we will increment the count, otherwise decrement the count
 */
function evUpdateCheckinCountAndBarGraph(incr)
{
    // TODO Do not hard-code the table ID and the TD names
    var total = $("#checkedInTableTotalCount").html();
    var count = $("#checkedInTableCheckinCount").html();
    
    if (incr) {
        count++;
    }
    else {
        count--;
    }
    
    // TODO Argg: More hard-coded stuff
    $("#checkedInTableCheckinCount").html(count);
    $("#checkedInTableRemainingCount").html(total - count);
    $("#barGraphTableId").html(evGetProgressBar(100 * count / total));
}

/**
 * This function can be called to dynamically check-in or check-out an attendee
 * by the user viewing an event attendee table list.
 * 
 * After the checkin/checkout is done successfully, this function will also
 * change the user's row icon to checkin/checkout icon.
 */
function checkinCheckoutAttendee(userId, eventId, checkedIn)
{
	var postVars = "";
	postVars += 'userId='  + userId
	postVars += '&eventId=' + eventId;
    postVars += '&checkedIn=' + checkedIn;
	
	sendAjaxRequest("checkinCheckoutAttendee", postVars, 
					function(response) {
                        var newIcon = evCheckedIconHtml(userId, eventId, checkedIn);
                        
                        /* We only have to worry about if user clicked "check-out" icon
                         * in which case we decrement the count of attendee checkins.
                         * Our periodic update only handles increment of checkins, but
                         * not decrement, so that's why we have to handle it here
                         */
                        if (!checkedIn) {
                            evUpdateCheckinCountAndBarGraph(checkedIn);
                        }
                        
                        // TODO Do not hardcode trid_ and td.checkedIcon
                        $("#trid_" + userId + " td.checkinTime").html(checkedIn ? "Now" : "--");
                        $("#trid_" + userId + " td.checkedIcon").html(newIcon);
					});
}

/**
 * Given the userId, eventId, and checkedIn flag, this function will generate
 * the HTML for either checked-in or checked-out icon with a URL that the user
 * can click to checkin/checkout a user
 */
function evCheckedIconHtml(userId, eventId, checkedIn)
{
    var checkedIcon = "";
    
    if (checkedIn) {
        checkedIcon += '<center><button type="button" class="btn btn-success btn-default btn-xs" onClick="checkinCheckoutAttendee(' + userId + ',' + eventId + ', 0)">';        
        checkedIcon += '<span class="glyphicon glyphicon-check"></span></a>';  
        checkedIcon += '</button></center>';
    }
    else {
        checkedIcon += '<center><button type="button" class="btn btn-danger btn-default btn-xs" onClick="checkinCheckoutAttendee(' + userId + ',' + eventId + ', 1)">';
        checkedIcon += '<span class="glyphicon glyphicon-remove"></span></a>';
        checkedIcon += '</button></center>';
    }
    
    return checkedIcon;
}

/**
 * This function returns the bar graph HTML content that displays the bargraph to the user
 */
function evGetProgressBar(percentFill)
{
    var content = "";
    
    if (percentFill >= 80) {
        content += '<div class="progress progress-striped">\
                        <div class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">\
                            <span class="sr-only">100% Complete</span>\
                        </div>\
                    </div>';
    }
    else {
        /* Empty bargraph doesn't look good :( */
        if (percentFill < 3) {
            percentFill = 3;
        }
        
        content += '<div class="progress progress-striped active">\
                        <div class="progress-bar"  role="progressbar" aria-valuenow="' + percentFill + '" aria-valuemin="0" aria-valuemax="100" style="width: ' + percentFill + '%">\
                            <span class="sr-only">' + percentFill + '% Complete</span>\
                        </div>\
                    </div>';
    }

    return content;
}

/**
 * This function will draw the attendee table based on the user data
 * @param rsp The server response of attendee list 
 * @param eventId This ID is used to checkin/checkout a user upon user click
 * @param divName  The DIV name where we will publish our table
 *
 * Each table row we draw for an attendee, we ID it with the user_id of the
 * attendee.  This is later used to update the user row data later, for example
 * when a checkin happens, we change user's checkin time.
 */
function evCreateAttendeeTable(rsp, eventId, divName)
{
    var content = "";
    content +=  '<HR><table class="table table-condensed"> <tr class="success">';
	content +=  '<th>Last Name</th> <th>First Name</th>';
	content +=  '<th>Check-in Time</th><th><center>Status</center></th>  </tr>';
    
	for (var i = 0; i < rsp.list.length; i++) {
        var first = rsp.list[i].firstname;
        var last  = rsp.list[i].lastname;
        
        var checkinTime = "--";        
        if (rsp.list[i].checkin_time != null) {
            var t = rsp.list[i].checkin_time + ".000Z";
            checkinTime = $.localtime.toLocalTime(t, 'yyyy-MM-dd HH:mm:ss');
            //checkinTime = t;
        }
        
        var checkedIn = (rsp.list[i].checked_in == "1");
        var checkedIcon = evCheckedIconHtml(rsp.list[i].user_id, eventId, checkedIn);
        
        content += '<tr id="trid_' + rsp.list[i].user_id + '">';
        content += '<td class="lastname">'  + last + '</td>';
        content += '<td class="firstname">' + first+ '</td>';
        content += '<td class="checkinTime">' + checkinTime + '</td>';
        content += '<td class="checkedIcon">' + checkedIcon + '</td>';
        content += "</tr>";
	};
    
	$('#' + divName).html(content);
}

/**
 * This function gets the list of latest attendee checkins and changes
 * the attendee checkin time and icon.
 * This function will also increment the checkin counts at the
 * attendee count table
 */
function evUpdateAttendeeStatusFromLatestCheckins(rsp, eventId)
{
    var lastCheckins = "";
    var lastCheckinNameCount = 3;
    
    for (var i = 0; i < rsp.list.length; i++)
    {
        if (i < lastCheckinNameCount) {
            lastCheckins += rsp.list[i].firstname + " " + rsp.list[i].lastname + "<BR/>";
        }
        
        var checkedIn = true;
        var userId = rsp.list[i].user_id;
        var t = rsp.list[i].checkin_time + ".000Z";
        var checkinTime = $.localtime.toLocalTime(t, 'yyyy-MM-dd HH:mm:ss');
        
        var newIcon = evCheckedIconHtml(userId, eventId, checkedIn);
        
        /* Check if we are changing checkin time, if so, then we will
         * also increment the checkin count
         */
        var userRowCheckinTimeDiv = $("#trid_" + userId + " td.checkinTime");
        if (userRowCheckinTimeDiv.html() != checkinTime) {
            userRowCheckinTimeDiv.html(checkinTime);
            evUpdateCheckinCountAndBarGraph(true);
        }
        
        /* Update the icon if it is not the checked-in icon */
        var userRowIconDiv = $("#trid_" + userId + " td.checkedIcon");
        if (userRowIconDiv.html() != newIcon) {
            userRowIconDiv.html(newIcon);
        }
    }
    
    var lastCheckinDiv = $("#checkedInTableId td.lastcheckin");
    if (lastCheckinDiv.html() != lastCheckins) {
        lastCheckinDiv.html(lastCheckins);
    }
}

/**
 * Once the speedometer is drawn out, this will update it with the new data
 * from the server.  The divId is used to find the highcharts object and the
 * event id is used to retrieve the latest list
 */
function evUpdateSpeedometer(divId, eventId)
{
    var chart = $('#' + divId).highcharts();
    
    if (chart) {
        sendAjaxRequest("getLastCheckinList", "eventId="+eventId+"&minutes=1", 
                function(response) {
                    var point = chart.series[0].points[0];
                    var newVal = response.list.length;
                    
                    if (newVal > EVENT_VIEWER_SPEEDOMETER_MAX) {
                        newVal = EVENT_VIEWER_SPEEDOMETER_MAX;
                    }
                    
                    point.update(newVal);
                    evUpdateAttendeeStatusFromLatestCheckins(response, eventId);
                    
                    // Set the next callback
                    setTimeout(function(){evUpdateSpeedometer(divId, eventId);}, EVENT_VIEWER_SPEEDOMETER_UPDATE_RATE_MS);
                });
    }
}

/**
 * This draws the highcharts speedometer based on the data of the eventId
 */
function evCreateSpeedometer(divId, eventId)
{
    $('#'+divId).highcharts({
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'Checkins during last minute'
	    },
	    
	    pane: {
	        startAngle: -180,
	        endAngle: 90,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: EVENT_VIEWER_SPEEDOMETER_MAX,
	        
	        minorTickInterval: 2.5,
	        minorTickWidth: 2,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#333',
	
            tickInterval: 5,
	        tickPixelInterval: 30,
	        tickWidth: 3,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#000',
	        labels: {
	            step: 1,
	            rotation: 'auto',
                style: {
                    color: '#000000',
                    fontWeight: 'bold'
                }
	        },
	        title: {
	            text: 'checkins/min'
	        },
	        plotBands: [{
	            from: 0,
	            to: 20,
	            color: '#55BF3B' // green
	        }, {
	            from: 20,
	            to: 25,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 25,
	            to: 30,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Checkins',
	        data: [0],
	        tooltip: {
	            valueSuffix: ' /min'
	        }
	    }]
	});
    
    // Kick-off the timer that will update the speedometer periodically.
    setTimeout(function(){evUpdateSpeedometer(divId, eventId);}, EVENT_VIEWER_SPEEDOMETER_UPDATE_RATE_MS);
}
