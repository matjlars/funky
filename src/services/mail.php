<?php
namespace funky\services;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class mail{
	// accepts an array of options
	// returns FALSE on success or an error string on failure.
	// so you can do it like this:
	//   if($err = f()->mail->send($opts)){
	//     // display $err
	//   }else{
	//     // success
	//   }
	//
	// $opts keys:
	// 'to' can be a string with one email address or an array with multiple. required.
	//     in order to specify a display name for a "to", it must be an array.
	//     if you pass an array, the keys should be the email address(es) and the value(s) should be the display names.
	//     if the values are null, the display name will not be used.
	// 'subject' is required. a string.
	// 'html' is an html string for the body of the email.
	// 'text' is a text version of the body. if left out, this will be auto-generated using php's strip_tags()
	//     at least either 'html' or 'text' is required.
	// 'cc' is same format as 'to'
	// 'bcc' is same format as 'to'
	// 'attachment' can be one of the following three things:
	//     1. a string containing the server path to a file (for one file. simplest.)
	//     2. an array containing the keys defined below (for one file, with options)
	//     3. an array containing arrays containing the keys defined below (for multiple files)
	//
	// attachment array keys:
	//     path (required)
	//     name
	//     encoding
	//     type
	//     disposition ('attachment' is default. 'inline' is the other option)
	// see PHPMailer's addAttachment function for the specifics of those options.
	public function send($opts=[]){
		try{
			$mail = $this->create_mailer();

			$this->to($mail, $opts);
			$this->cc($mail, $opts);
			$this->bcc($mail, $opts);
			$mail->Subject = $opts['subject'];
			$this->body($mail, $opts);
			$this->attachment($mail, $opts);

			$mail->send();
		}catch(\Exception $e){
			return $e->getMessage();
		}

		// success!
		return false;
	}

	// returns a new PHPMailer object that is SMTP authenticated.
	// throws an exception if there is anything wrong with the smtp config
	// use this if the send($opts) formatting is too strict for something you want to do with PHPMailer.
	public function create_mailer(){
		$use_exceptions = true;
		$mail = new PHPMailer($use_exceptions);

		$mail->isSMTP();
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
		$mail->SMTPAuth = true;

		$c = f()->config;
		$mail->Host = $c->smtp_host;
		$mail->Username = $c->smtp_username;
		$mail->Password = $c->smtp_password;
		$mail->Port = $c->smtp_port;
		$mail->setFrom($c->smtp_from_email, $c->smtp_from_name);

		// optional reply to email and name
		if(isset($c->smtp_reply_email)){
			$reply_name = isset($c->smtp_reply_name) ? $c->smtp_reply_name : '';
			$mail->addReplyTo($c->smtp_reply_email, $reply_name);
		}

		return $mail;
	}

	// tries to utilize the "to" opt in $opts
	// throws an exception if anything goes wrong.
	protected function to($mail, $opts){
		$to = $opts['to'];

		if(is_array($to)){
			foreach($to as $email=>$name){
				if(is_string($name)){
					$mail->addAddress($email, $name);
				}else{
					$mail->addAddress($email);
				}
			}
		}else{
			$mail->addAddress($to);
		}
	}

	// tries to use 'html' and/or 'text' opts.
	// throws an exception if neither exist.
	protected function body($mail, $opts){
		// html
		if(isset($opts['html'])){
			$mail->isHTML(true);
			$mail->Body = $opts['html'];
			$mail->AltBody = isset($opts['text']) ? $opts['text'] : strip_tags($opts['html']);
			return;
		}

		// try only "text" with no html
		if(isset($opts['text'])){
			$mail->isHTML(false);
			$mail->Body = $opts['text'];
			return;
		}

		throw new \Exception('No "html" or "text" given for the message body.');
	}

	// tries to use the 'cc' opt.
	// fails safely.
	protected function cc($mail, $opts){
		if(!isset($opts['cc'])) return;
		$cc = $opts['cc'];

		if(is_array($cc)){
			foreach($cc as $email=>$name){
				if(is_string($name)){
					$mail->addCC($email, $name);
				}else{
					$mail->addCC($email);
				}
			}
		}else{
			$mail->addCC($cc);
		}
	}

	// tries to use the 'bcc' opt.
	// fails safely.
	protected function bcc($mail, $opts){
		if(!isset($opts['bcc'])) return;
		$bcc = $opts['bcc'];

		if(is_array($bcc)){
			foreach($bcc as $email=>$name){
				if(is_string($name)){
					$mail->addBCC($email, $name);
				}else{
					$mail->addBCC($email);
				}
			}
		}else{
			$mail->addBCC($bcc);
		}
	}

	// tries to use the 'attachment' opt.
	// fails safely.
	protected function attachment($mail, $opts){
		if(empty($opts['attachment'])) return;

		$attachment = $opts['attachment'];

		// allow sending just the path of a file to attach
		if(is_string($attachment)){
			$mail->addAttachment($attachment);
			return;
		}

		// stick a single array into an array
		// so the caller doesn't need to
		if(isset($attachment['path'])) $attachment = [$attachment];

		if(is_array($attachment)){
			foreach($attachment as $a){
				$path = $a['path'];
				$name = isset($a['name']) ? $a['name'] : '';
				$encoding = isset($a['encoding']) ? $a['encoding'] : PHPMailer::ENCODING_BASE64;
				$type = isset($a['type']) ? $a['type'] : '';
				$disposition = isset($a['disposition']) ? $a['disposition'] : 'attachment';
				$mail->addAttachment($path, $name, $encoding, $type, $disposition);
			}
		}
	}
}