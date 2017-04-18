<?php

require_once 'Mail.php';

$from = "boosterchopper@hotmail.de";
$to = "pierre@cup7.com";
$to2 = "dennis.horn@stud.hs-kempten.de";
$subject = "Hi!";
$body = "Hi,\n\nHow are you?";

$host = "smtp-mail.outlook.com";
$username = "boosterchopper@hotmail.de";
$password = "Asdf1234";

$headers = array ('From' => $from,
		'To' => $to,
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