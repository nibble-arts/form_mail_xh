<?php

namespace fm;


class View {


	// render the entries as list
	public static function list() {


		$ret = '<p>' . Entries::count() . ' ' . Text::entries() . '</p>';
		
		$ret .= '<table class="form_mail_list_table">';

			// create header
			$ret .= '<th>#</th>'; // count field

			foreach (Entries::get(0)->legend() as $value) {
				
				$ret .= '<th class="form_mail_list_head">';
					$ret .= ucfirst(str_replace("_", " ", $value));
				$ret .= '</th>';

				$csv_ary[] = '"' . $value . '"';
			}

			$csv = implode(";", $csv_ary) . "\n";
			$csv_ary = [];


			// create lines
			foreach (Entries::get() as $idx => $line) {

				if ($line != "") {
					$ret .= "<tr>";

						$ret .= '<td class="form_mail_list_cell">' . $idx . '</td>';

						// foreach ($line as $value) {

						while ($value = $line->get()) {

							$ret .= '<td class="form_mail_list_cell">';
								$ret .= $value[key($value)];
							$ret .= "</td>";

							$csv_ary[] = '"' . $value[key($value)] . '"';
						}

					$ret .= "<tr>";

					$csv .= implode(";", $csv_ary) . "\n";
					$csv_ary = [];
				}
			}

		$ret .= "</table>";

		return $ret;
	}
}

?>