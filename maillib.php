<?php

require_once "Mail.php";
require_once "Mail/mime.php";
require_once("settings.php");


// PHP_DOC
// INPUT: General Mail Info of user with event info
// OUTPUT: No return
// DESCRIPTION: Generate email message and send email
function sendTicketLink ($toFirstName, $toLastName, $eventName, $ticketURL, $to, $subject){

       $html = generateBody('html',$toFirstName,$toLastName,$eventName,$ticketURL);
       $text = generateBody('text',$toFirstName,$toLastName,$eventName,$ticketURL);

	sendEmail($to, $subject, $html, $text);

}

// PHP_DOC
// INPUT: Mail requirements, html, text
// OUTPUT: Mail info is logged to file
// DESCRIPTION: access email and send mail to attendie. email relay of 12
function sendEmail($to, $subject, $html, $text, $attachments = array() ){

/* Uncomment this section to enable relay bewteen different accounts
	$emailnum = rand(1,12);
	//$emailnum = 1;
	if ($emailnum == 1){
		$emailsuffix = "";
	} else {
		$emailsuffix = $emailnum;
	}
	$outbox = "sjsu.ticket" . $emailsuffix . "@gmail.com";
**/
	$outbox = APP_EMAIL;
	$password = APP_EMAIL_PASSWORD;

	$smtp = Mail::factory('smtp', array(
      		'host' => 'ssl://smtp.gmail.com',
      		'port' => '465',
      		'auth' => true,
      		'username' => $outbox,
      		'password' => $password
	));

	$from = 'SJSU Graduate Orientation<'. $outbox .'>';
        $mime = new Mail_mime(array('eol'=>"\n"));
        $mime->setTXTBody($text);
        $mime->setHTMLBody($html);

	if (!empty($attachments)){
		foreach ($attachments as $attachment){
			$mime->addAttachment($attachment);
		}

	}

        $body = $mime->get();

        $headers = array(
                'From' => $from,
                'To' => $to,
                'Subject' => $subject
        );

        $headers = $mime->headers($headers);


        $mailDigest = array ($headers, $body);

        //var_dump ($mailDigest);

        $mail = $smtp->send($to, $headers, $body);

        $success_emails = 0;
        $fail_emails = 0;

	$ret = "";


        if (PEAR::isError($mail)) {
                $ret = "ERROR:  ". $mail->getMessage() . " " . $outbox ."\n";
                $fail_emails ++;
        } else {
                $success_emails ++;
                $ret = "Message successfully sent! via ". $outbox ." \n\n";
        }

        $total_emails = $success_emails + $fail_emails;
	$date = date('Y M d - H:i:s');
	$cmd = sprintf('echo "%s - Sending to: %s - %s: %s" >> /home/ddelacruz/log/mail.log', $date, $to, $subject, $ret);

	shell_exec($cmd);

	//return $ret;
        //ho ("$success_emails emails where sent out of $total_emails");
}

// PHP_DOC
// INPUT: User Info with Event Info 
// OUTPUT: Return body of email
// DESCRIPTION: Generic Send Ticket Code
function generateBody($type, $first_name, $last_name, $event_name, $ticket_url){

  if ($type == 'html'){
      $br = "<br><br>";
  } else if ($type == 'text'){
      $br = "\n\n";
  
  }

        $body = "Greetings, ".$first_name . " " . $last_name . "!";

        $body .= $br."We are so excited to see you at ". $event_name; 
        $body .= ". To help make the day smoother, please print out the ticket using the following link. Thank you!!!";

  if ($type == 'html'){
          $body .= $br."<a href ='http://".SERVER_IP."/getTicket.php?ticket=" . $ticket_url."'>
Download Ticket</a>";
  } else if ($type == 'text') {
    $body .= $br."Copy and paste the following link to your browser";
    $body .= $br."http://".SERVER_IP."/getTicket.php?ticket=" . $ticket_url;
  }

    $body .= $br."Once again, welcome.";

    $body .= $br."SJSU Ticketing System";

  return $body;
}

// PHP_DOC
// INPUT: text object, with object type
// OUTPUT: return modified text
// DESCRIPTION: add html tag to underline
function underlinetext($text, $type){
        if ($type == 'text'){
                return $text;
        } else if ($type == 'html'){
                return  "<p style='text-decoration:underline'>" . $text
."</p>";
        }

}

// PHP_DOC
// INPUT: text object, with object type
// OUTPUT: return modified text
// DESCRIPTION: add html tag to bold
function boldtext($text, $type){
        if ($type == 'text'){
                return "*" . $text . "*";
        } else if ($type == 'html'){
                return "<p style='font-weight:bold'>" . $text . "</p>";
        }
}

// PHP_DOC
// INPUT: text object, with object type, url
// OUTPUT: return modified text
// DESCRIPTION: add html tag to convert to link. 
function htmllink($text, $link, $type){
        if ($type == 'text'){
                return "\n\nCopy and paste the following in your browser:
\n\n" . $link . "\n\n";
        } else if ($type == 'html'){
                return "<a href='".$link."'>" . $text . "</a>";
        }
}

// PHP_DOC
// INPUT: text object, with object type
// OUTPUT: return modified text
// DESCRIPTION: add html tag p or new lines to text type
function paragraph($text, $type){

        if ($type == 'text'){
                return "\n\n" . $text . "\n\n";
        } else if ($type == 'html'){
                return "<p>" . $text . "</p>";
        }

}



?>
