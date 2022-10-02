<?php
namespace funky\services;

class recaptcha{
	public function __construct(){
		f()->template->addHeadTag('<script src="https://www.google.com/recaptcha/api.js" async defer></script>');
	}

	// returns an html string containing all you need to put it on the page
	public function tag(){
		return '<div class="g-recaptcha" data-sitekey="'.$this->get_site_key().'"></div>';
	}

	// verifies the response in POST with the google service
	// returns false if recaptcha verifies the user
	// otherwise, returns a string which is a user-readable error message.
	// so you can do something like this:
	// if($error = f()->recaptcha->error()) return $error;
	public function error(){
		// no token = no verification
		if(empty($_POST['g-recaptcha-response'])) return 'No recaptcha token.';

		// prepare post data
		$data = [
			'secret'=>$this->get_secret_key(),
			'response'=>$_POST['g-recaptcha-response'],
		];
		if(!empty($_SERVER['REMOTE_ADDR'])) $data['remoteip'] = $_SERVER['REMOTE_ADDR'];

		$c = \curl_init('https://www.google.com/recaptcha/api/siteverify');
		\curl_setopt($c, CURLOPT_POSTFIELDS, $data);
		\curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

		$response = \curl_exec($c);
		\curl_close($c);

		$response = \json_decode($response, true);

		// if it succeeded, we're done!
		if(isset($response['success']) && $response['success'] === true) return false;

		// uh oh!
		return $this->get_error($response['error-codes']);
	}

	// site key and secret keys.
	// if you'd rather store them in the codebase than the config,
	// make a recaptcha service in your site:
	//   1. make a file at src/services/recaptcha.php with this template:
	/*
	namespace services;

	class recaptcha extends \funky\services\recaptcha{
		protected function get_site_key(){
			return 'my_site_key_here';
		}
		protected function get_secret_key(){
			return 'my_secret_key_here';
		}
	}
	*/
	protected function get_site_key(){
		return f()->config->recaptcha_site_key;
	}

	protected function get_secret_key(){
		return f()->config->recaptcha_secret_key;
	}

	private function get_error($error_codes){
		$errors = [
			'missing-input-secret'=>'The secret parameter is missing. The site admin needs to add a "recaptcha_site_key" entry into the funky config.',
			'invalid-input-secret'=>'The secret parameter is invalid or malformed. The site admin needs to get a new site secret from Google and update it in the funky config.',
			'missing-input-response'=>'The response parameter is missing. The site admin needs to make sure the recaptcha field is in the form, and that the form is using POST.',
			'invalid-input-response'=>'The response parameter is invalid or malformed.',
			'bad-request'=>'The request is invalid or malformed. This is server-speak for "something is wrong, and I\'m not quite sure what."',
			'timeout-or-duplicate'=>'The recaptcha response is no longer valid. It is either too old or has been used previously.',
		];

		// compile all of relevent errors:
		$error_strings = [];
		foreach($error_codes as $ec){
			if(isset($errors[$ec])) $error_strings[] = $errors[$ec];
		}

		if(empty($error_strings)) $error_strings[] = 'Unknown recaptcha error.';
		return implode(' and ', $error_strings).' Please try submitting again.';
	}
}
