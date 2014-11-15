$(document).ready(function(){
	//alert("hi");

        $('#custom_attendees_upload').uploadify({
                'swf'      : 'uploadify/uploadify.swf',
                'uploader' : 'addAttendeesCsv.php',
                //'formData' : {'event_id': Event_id},
                // Your options here
                'onUploadError' : function(file, errorCode, errorMsg, errorString) {
                        alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
                },
                'onUploadSuccess' : function(file, data, response) {
                        //RegisteredHandler(Event_id);

                        var content = "";

                        content += "<table class='table table-hover'>";
                        content += "<tr>";
			content += "<th>SJSU ID#</th>";
                        content += "<th>First Name</th>";
                        content += "<th>Last Name</th>";
                        content += "<th>Email</th>";
                        content += "</tr>";
                        var jsonData = JSON.parse(data);

                        var attendees = jsonData['attendees'];

                        var filename = jsonData['file'];

                        //alert(data);

                        $.each (attendees, function(index, attendee){
                                content +=
                                        "<tr>" +
					"<td>" + attendee['sjsu_id'] + "</td>" +
                                        "<td>" + attendee['first_name'] + "</td>" +
                                        "<td>" + attendee['last_name'] + "</td>" +
                                        "<td>" + attendee['email'] + "</td>" +
                                        "</tr>";


                        });

                        $("#newAttendees").empty();
                        $("#newAttendees").append(content);
                        $("#attendeesFileName").empty();
                        $("#attendeesFileName").val(filename);
                }
        });

});


function sendemails(){
	var filename = $("#attendeesFileName").val();
	$.post(
		"customMailRequest.php",
		{
			filename: filename
		},
		function (data){
			alert (data);
		},
		"text"
	);	
}
