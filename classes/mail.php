<?php

namespace form;


class Mail {

	private $sender;
	private $receiver;


	public function __construct($sender) {
		$this->sender = $sender;
	}


	// send mail
	public function send($receiver, $subject, $message) {

		return mail($receiver, $subject, $message, $this->mail_header());
	}


	// create mail header
	private function mail_header() {

		$mail_header  = 'MIME-Version: 1.0' . "\r\n";
		$mail_header .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
		// $mail_header .= "To: <$to>" . "\r\n";
		$mail_header .= 'From: ' . $this->sender . "\r\n";

		return $mail_header;
	}

}