<?php

namespace fm;

class Entries {


	private static $entries;


	// load files from path
	public static function load($path) {

		if (file_exists($path)) {

			$dir = scandir($path);
			
			foreach($dir as $file) {

				if (!is_dir($path . '/' . $file)) {

					$data = parse_ini_file($path . '/' . $file, true);

					if($data) {
						self::$entries[] = new Entry($data);
					}
				}
			}
		}
	}


	// get array of entries
	public static function get($idx = false) {

		if ($idx !== false) {

			if ($idx < count(self::$entries)) {
				return self::$entries[$idx];
			}
		}

		else {
			return self::$entries;
		}

		return false;
	}

}