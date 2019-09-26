<?php

class Form_Mail_Selector {

	private $selection;
	private $group_field;
	private $fields;
	private $format;


	public function __construct($path) {

		$this->group_field = false;
		$this->fields = false;
		$this->selection = false;


		if (file_exists($path . ".selection.ini")) {
			$this->selection = parse_ini_file($path . ".selection.ini", true);

			// get group field name
			if (isset($this->selection["_field"])) {
				$this->group_field = $this->selection["_field"];
				unset ($this->selection["_field"]);
			}

			if (isset($this->selection["_fields"])) {
				$this->fields = $this->selection["_fields"];
			}
		}
	}


	public function render($format = false) {

		$ret = "";
		$group_field = false;


		if ($this->selection) {


			$ret .= "<p>";
				$ret .= "Programm " . $this->select(array_keys($this->selection), "filmblock", ["fields" => $this->group_field]);
				$ret .= "<br>";


				foreach ($this->selection as $idx => $filme) {

					if ($idx[0] != "_") {

						$ret .= '<p name="fm_filmlist_' . $idx . '">';
							$ret .= "Film " . $this->select($filme, "", ["fields" => $this->fields, "format" => $format]) . '<br>';
						$ret .= '</p>';
					}
				}

			$ret .= "</p>";
		}

		return $ret;

	}


	private function select($opt, $name, $options = false) {

		$ret = "";

		$ret .= '<select ';

			if (isset($options["fields"])) {
				$ret .= ' fields="' . $options["fields"] . '"';
			}

			if ($name) {
				$ret .= ' name="' . $name;
			}

		$ret .= '">';


		// add empty option
		$ret .= '<option></option>';

		foreach ($opt as $idx => $entry) {
		
			if ($entry[0] != "_") {

				// select value to display by format field
				if (isset($options["format"])) {

					$field = $options["format"];

					if (($idx = array_search($field, explode("|", $this->fields))) !== false) {

						$value = explode("|", $entry)[$idx];
					}
				}

				else {
					$value = $entry;
				}

				$ret .= '<option value="' . $entry . '">' . $value . '</option>';
			}

		}

		$ret .= "</select>";

		return $ret;
	}
}