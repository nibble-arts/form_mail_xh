<?php

class Admin {

	private static $path;

	private static $legend;
	private static $data;
	private static $meta;


	// fetch form data
	public static function fetch($path) {

		self::$path = $path;
		self::$legend = false;

		if (file_exists($path)) {

			$dir = scandir($path);
			
			foreach($dir as $file) {

				if (!is_dir($path . '/' . $file)) {

					$data = parse_ini_file($path . '/' . $file, true);

					// get legend from first entry
					if (!self::$legend) {
						self::$legend = $data["legend"];
					}

					// get data
					self::$data[] = $data["data"];

					// get metadata
					self::$meta[] = $data["meta"];
				}
			}

		}
	}


	// render form data in list
	// optional: group by field name
	public static function render($group = false) {

		// if ($group) {
		// 	$data = self::group($group);
		// }

		// else {
		// 	$data = self::$data;
		// }


		$ret = "";
		$csv = "";
		$csv_ary = [];

		$ret .= '<table class="form_mail_list_table">';

			// create header
			foreach (self::$legend as $value) {
				
				$ret .= '<th class="form_mail_list_head">';
					$ret .= ucfirst(str_replace("_", " ", $value));
				$ret .= '</th>';

				$csv_ary[] = '"' . $value . '"';
			}

			$csv .= implode(";", $csv_ary) . "\n";
			$csv_ary = [];


			// create lines
			foreach (self::$data as $line) {

				$ret .= "<tr>";

					foreach ($line as $value) {

						$ret .= '<td class="form_mail_list_cell">';
							$ret .= $value;
						$ret .= "</td>";

						$csv_ary[] = '"' . $value . '"';
					}

				$ret .= "<tr>";

				$csv .= implode(";", $csv_ary) . "\n";
				$csv_ary = [];
			}

		$ret .= "</table>";

		// save csv file
		file_put_contents(self::$path . '/' . 'result.csv', $csv);

		return $ret;
	}


	// create array grouped by field name
	private static function group($group) {

		// field for grouping found
		if (($idx = array_search($group, self::$legend)) !== false) {

			foreach ($data as $entry) {

			}
		}

		return $data;
	}
}

?>