<?php


class FormMailSender {

	private $keys;
	private $data;
	private $sender;

	function __construct($sender) {

		$this->keys = [];
		$this->data = [];
		$this->sender = $sender;
	}


	// send mail
	public function send($receiver, $subject) {

		return mail($receiver, $subject, $this->render(), $this->mail_header());
	}


	// set key names for csv data
	public function set_key_names($keys) {
		$this->keys = $keys;
	}


	// add array or key value for csv line
	public function add_data($key, $value = false) {

		// is assoc array -> add key->value 
		if (is_array($key)) {
			$this->data = $key;
		}

		elseif ($key !== false && $value !== false) {
			$this->data[$key] = $value;
		}
	}


	// render data to csv
	private function render() {

		$csv = '"' . implode('";"', $this->keys) . '"' . "\r\n";

		foreach ($this->data as $k => $v) {

			// collect informations
			if ($k != "action") {
				$csv .= '"' . $k . '";"' . $v . '"' . "\r\n";
			}
		}

		return $csv;
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

?>