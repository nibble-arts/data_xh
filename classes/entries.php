<?php

namespace form;

class Entries {


	private static $entries = false;


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

		if (self::$entries) {

			if ($idx !== false) {

				if ($idx < count(self::$entries)) {
					return self::$entries[$idx];
				}
			}

			else {
				return self::$entries;
			}
		}

		return false;
	}


	// filter entries by key
	// key: meta:field, value
	//      data:legend, value
	public static function filter($key, $value) {

		$filter = explode(":", $key);
		$filtered = [];

		// valid key
		if (count($filter) == 2) {

			foreach (self::$entries as $entry) {

				switch ($filter[0]) {

					case "data":
						break;


					// filter by meta entry
					case "meta":
						if ($entry->meta($filter[1]) == $value) {
							$filtered[] = $entry;
						}

						break;

				}
			}
		}

		self::$entries = $filtered;
	}


	// sort entries by key and direction
	public static function sort($order, $dir) {

	}


	// return count of entries
	public static function count() {

		if (self::$entries) {
			return count(self::$entries);
		}

		return false;
	}
}