<?php


include_once FORM_MAIL_BASE . "lib/class.renderer.php";


class Mail_form_block {

	private $text;
	private $params;
	private $global_params;
	private $in_table;

	private $settings;

	// list of valid formats
	protected $formats = [
		"title",
		"string",
		"text",
		"input"
	];


	// lists of valid parameters	
	protected $parameters = [
		"formid",
		"format",
		"size",
		"rows",
		"cols",
		"name",
		"mandatory",
		"optional",
		"check",
		"options"
	];


	// create block
	public function __construct($text = false, $data = false) {

		$this->reset();

		if ($text) {
			$this->text = $text;
		}

		if ($data) {
			$this->set($text, $data);
		}
	}


	// reset block date
	public function reset() {

		$this->in_table = false;
		$this->params = [];
		$this->global_params = [];
		$this->text = "";

		$this->settings = false;

	}


	// set block date
	public function set($text, $data) {


		$this->text = $text;
		$this->params = $data;

		// reset table counter if has format
		// save params as global
		if ($this->format()) {
			$this->global_params = $this->params;
		}
	}


	// get value by name
	public function get($name) {

		if (/*in_array($name, $this->parameters) && */isset($this->params[$name])) {
			return $this->params[$name];
		}

		else {
			return false;
		}
	}


	// set/get text string
	public function text($text = false) {
		
		if ($text !== false) {
			$this->text = $text;
		}

		else {
			return $this->text;
		}

	}


	// get settings
	public function settings () {
		return $this->settings;
	}


	// return options || global_options
	public function options() {

		$ret_ary = [];

		// options exist
		

		// get global options
		if ($this->global_options()) {
			$global_options = $this->global_options();

			foreach ($global_options as $k => $v) {
				$ret_ary[$k] = $v;
			}
		}
		
		// get options
		if ($this->get("options")) {
			foreach ($this->get("options") as $k => $v) {
				$ret_ary[$k] = $v;
			}
		}

		return $ret_ary;
	}


	// get global_options
	private function global_options() {

		if (isset($this->global_params["options"])) {
			return $this->global_params["options"];
		}

		else {
			return false;
		}
	} 


	// get value or param string by name
	public function __call($name, $args) {

		if ($val = $this->get($name)) {

			// return parameter format string
			if (count($args) > 0 && $args[0] === true && $val !== false) {
				return ' ' . $name . '="' . $val . '"';
			}

			// return value
			else {
				return $val;
			}
		}

		else {
			// return false;
		}

	}


	public function render() {

		$ret = "";

		if ($this->format()) {

			// end open table
			if ($this->in_table) {
				$ret .= "</table>";
				$this->in_table = false;
			}

			$ret .= $this->render_formatted();
		}

		else {
			$ret .= $this->render_field();
		}

		return $ret;
	}


	// render text
	private function render_formatted() {

		$ret = "";

		// switch format
		switch ($this->format()) {

			// start new table block
			case "title":
				$ret .= Form_mail_renderer::title($this->text);
				break;

			case "string":
				$ret .= Form_mail_renderer::string($this->text);
				break;


			case "text":
				$ret .= Form_mail_renderer::text($this);
				break;


			case "input":
				$ret .= Form_mail_renderer::input($this);
				break;
		}

		return $ret;
	}


	// render input field
	private function render_field() {

		$ret = "";
		
		// start table
		if (!$this->in_table) {

			$this->in_table = true;

			$ret .= '<table class="form_mail_table">';
			$ret .= Form_mail_renderer::global_header($this->global_options());

		}

		// create text field
		$ret .= '<tr>';

			// display text
			$ret .= '<td class="form_mail_text"' . $this->params() . '>' . $this->text() . '</td>';


			// start cell for option list
			if (!$this->global_options()) {
				$ret .= '<td class="form_mail_cell"' . $this->params() . '>';
			}

			// create radio buttons
			foreach ($this->options() as $option) {

				// start cell per option
				if ($this->global_options()) {
					$ret .= '<td class="form_mail_cell"' . $this->params() . '>';
				}


				// create radio button
				$ret .= '<input type="radio" name="' . $this->text . '" value="' . $option . '">';


				// end cell per option 
				if ($this->global_options()) {
					$ret .= '</td>';
				}

				// options list in one cell
				else {
					$ret .= $option . "<br>";
				}
			}


			// end cell for option list
			if (!$this->global_options()) $ret .= '</td>';


		$ret .= "</tr>";

		return $ret;
	}


	// return formatted param string
	public function params() {

		$ret = "";
		$ret_ary = [];

		// collect global parameters
		foreach ($this->global_params as $k => $v) {

			if (!is_array($v)) {
				$ret_ary[$k] = $v;
			}
		}

		// collect parameters
		// local overrides global
		foreach ($this->params as $k => $v) {
			if (!is_array($v)) {
				$ret_ary[$k] = $v;
			}
		}

		// create formatted output
		foreach ($ret_ary as $k => $v) {
			$ret .= ' ' . $k . '="' . $v . '"';
		}

		return $ret;
	}
}

?>