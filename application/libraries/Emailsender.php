<?php
class Emailsender {

	function __construct() {
		$this->latestErr = "";
	}
	
	function loadVerificationEmailTemplate($ctrl, $account) {
		$account['base_url'] = $_SERVER['SERVER_NAME'];
		
		$template = $ctrl->load->view('email/verification', $account, TRUE);

		return $template;
	}
	
	public function send($bcc, $subject, $html) {
		
	}

	public function sendViaMailgun($bcc, $subject, $html) {
		if (!count($bcc))
			return;

		$ch = curl_init();

		foreach ($bcc as $user) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		    curl_setopt($ch, CURLOPT_USERPWD, 'api:key-061710f7633b3b2e2971afade78b48ea');
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		    curl_setopt($ch, CURLOPT_URL, 
		          'https://api.mailgun.net/v3/sandboxa8b6f44a159048db93fd39fc8acbd3fa.mailgun.org/messages');
		    curl_setopt($ch, CURLOPT_POSTFIELDS, 
		            array('from' => 'noreply@wefriends.us <postmaster@wefriends.us>',
		                  'to' => $user->username . ' <' . $user->email . '>',
		                  'subject' => $subject,
		                  'html' => $html));
		    $result = curl_exec($ch);
		}

		curl_close($ch);
	}
}
?>