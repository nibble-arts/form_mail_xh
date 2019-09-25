<?php


class FormMailSender {

	private $form;
	private $keys;
	private $data;
	private $sender;

	function __construct($sender, $form) {

		$this->form = $form;

		$this->keys = [];
		$this->data = [];
		$this->sender = $sender;
	}


	// send mail
	public function send($receiver, $subject) {


		$base_dir = FORM_CONTENT_BASE . FORM_MAIL_PATH . "/";

		// create dir if not exists
		if (!file_exists($base_dir . $this->form)) {
			mkdir($base_dir . $this->form);
		}

		$file_name = $this->form . "_" . time() . ".txt";


		// send mail
		// return mail($receiver, $subject, $this->render(), $this->mail_header());

		// save to file
		return file_put_contents($base_dir . $this->form . "/" . $file_name, $this->render());
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

		// $csv = '"' . implode('";"', $this->keys) . '"' . "\r\n";

		// $header = [];
		// $data = [];
		// $legend = [];

		// CSV
		// $idx = 0;
		// foreach ($this->data as $k => $v) {

		// 	if ($k != "action") {
		// 		$header[] = '"' . $idx . '"';
		// 		$data[] = '"' . $v . '"';

		// 		$legend[] = '"' . $idx . '";"' . $k . '"';
		// 	}

		// 	$idx++;			
		// }

		// $csv = implode(";", $header) . "\r\n";
		// $csv .= implode(";", $data) . "\r\n";

		// $csv .= "\r\n";

		// $csv .= implode("\r\n", $legend);

		// $csv .= "\r\n";

		// $csv .= '"timestamp";"' . time() . '"';

		// INI
		$idx = 0;
		$data = [];
		$legend = [];

		foreach ($this->data as $k => $v) {

			if ($k != "action") {

				$data[] = $idx . '="' . $v . '"';
				$legend[] = $idx . '="' . $k . '"';
			}

			$idx++;			
		}

		// data section
		$ini = "[data]\n";
		$ini .= implode("\n", $data);
		$ini .= "\n";

		$ini .= "\n";
		$ini .= "[legend]\n";
		$ini .= implode("\n", $legend);
		$ini .= "\n";

		$ini .= "\n";
		$ini .= "[meta]\n";
		$ini .= "time=" . date("Y-m-dTH:i:s", time()) . "\n";
		$ini .= "timestamp=" . time();

		// add active user
		if (FORM_MAIL_ACCESS_SUPPORT) {
			$ini .= "\n";
			$ini .= "user=" . ma\Access::user()->username() . "\n";
		}

		return $ini;
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