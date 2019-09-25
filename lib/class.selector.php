<?php

class Form_Mail_Selector {

	private $selection;

	public function __construct($path) {

		$this->selection = false;

		if (file_exists($path . ".selection.ini")) {
			$this->selection = parse_ini_file($path . ".selection.ini", true);
		}
	}


	public function render() {

		$ret = "";

		if ($this->selection) {

			$ret .= "<p>";
				$ret .= "Filmblock " . $this->select(array_keys($this->selection), "filmblock");
				$ret .= "<br>";
				$ret .= "Film " . $this->select([], "filme");
			$ret .= "</p>";
		}

		return $ret;

	}


	private function select($opt, $name) {

		$ret = "";

		$ret .= "<select name=" . $name . ">";

		foreach ($opt as $entry) {
			$ret .= "<option>" . $entry . "</option>";
		}

		$ret .= "</select>";

		return $ret;
	}
}