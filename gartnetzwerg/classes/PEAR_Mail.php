<?php

require_once 'Mail.php';

$from = "gartnetzwerg@outlook.de";
$to = "pierre@cup7.com";
$to2 = "dennis.horn@stud.hs-kempten.de";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";

$host = "smtp-mail.outlook.com";
$username = "gartnetzwerg@outlook.de";
$password = "hellomy4plants";

$headers = array ('From' => $from,
		'To' => $to2,
		'Subject' => $subject);
$smtp = Mail::factory('smtp',
		array ('host' => $host,
				'auth' => true,
				'username' => $username,
				'password' => $password));
		
		$mail = $smtp->send($to, $headers, $body);
		
		if (PEAR::isError($mail)) {
			echo("<p>" . $mail->getMessage() . "</p>\n");
		} else {
			echo("<p>Message successfully sent!</p>\n");
		}

?>