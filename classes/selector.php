<?php


namespace fm;


class Selector {

	private $selection;
	private $group_field;
	private $field;
	private $field_name;
	private $fields;
	private $fields_name;
	private $format;


	public function __construct($path) {

		$this->group_field = false;
		$this->field = false;
		$this->field_name = "Feld";
		$this->fields = false;
		$this->fields_name = "Fields";
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
			
			if (isset($this->selection["_field_name"])) {
				$this->field_name = $this->selection["_field_name"];
			}
			
			if (isset($this->selection["_fields_name"])) {
				$this->fields_name = $this->selection["_fields_name"];
			}
		}
	}


	public function render($format = false) {

		$ret = "";
		$group_field = false;


		if ($this->selection) {

			$ret .= "<p>";
				$ret .= $this->field_name . " " . $this->select(array_keys($this->selection), "filmblock", ["fields" => $this->group_field]);
				$ret .= "<br>";


				foreach ($this->selection as $idx => $filme) {

					if ($idx[0] != "_") {

						$ret .= '<p name="fm_filmlist_' . $idx . '">';
							$ret .= $this->fields_name . " " . $this->select($filme, "", ["fields" => $this->fields, "format" => $format]) . '<br>';
						$ret .= '</p>';
					}
				}

			$ret .= "</p>";
		}

		return $ret;

	}


	private function select($opt, $name, $options = false) {

		$ret = "";
		$ret .= '<select class="selector"';

			if (isset($options["fields"])) {
				$ret .= ' fields="' . $options["fields"] . '"';
			}

			if ($name) {
				$ret .= ' name="' . $name . '"';
			}

		$ret .= '>';


		// add empty option
		$ret .= '<option></option>';

		foreach ($opt as $idx => $entry) {
		
			if ($entry[0] != "_") {

				// select value to display by format field
				if (isset($options["format"])) {

					$field = $options["format"];

					// show selected field
					if (($key = array_search($field, explode("|", $this->fields))) !== false) {
						$value = explode("|", $entry)[$key];
					}

					// no fields defined -> show all
					else {
						$value = $entry;
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