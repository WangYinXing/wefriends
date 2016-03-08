<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function mh_loadVerificationEmailTemplate($ctrl, $account) {
	$account['base_url'] = $_SERVER['SERVER_NAME'];
	
	$template = $ctrl->load->view('email/verification', $account, TRUE);

	return $template;
}

function mh_send($bcc, $subject, $html) {
	foreach ($bcc as $email) {
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: $email <$email>' . "\r\n";
		$headers .= 'From: Wefriends verification <noreply@wefriends.us>' . "\r\n";
		//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
		//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
		
		mail($email, $subject, $html, $headers);
	}
}

function mh_sendViaMailgun($bcc, $subject, $html) {
	if (!count($bcc))
		return;

	$ch = curl_init();

	foreach ($bcc as $email) {
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_USERPWD, 'api:key-061710f7633b3b2e2971afade78b48ea');
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	    curl_setopt($ch, CURLOPT_URL, 
	          'https://api.mailgun.net/v3/sandboxa8b6f44a159048db93fd39fc8acbd3fa.mailgun.org/messages');
	    curl_setopt($ch, CURLOPT_POSTFIELDS, 
	            array('from' => 'noreply@coatingsestimator.com',
	                  'to' => $email . ' <' . $email . '>',
	                  'subject' => $subject,
	                  'html' => $html));
	    $result = curl_exec($ch);

	    //print_r("#########" . $result);
	}

	curl_close($ch);
}

?>