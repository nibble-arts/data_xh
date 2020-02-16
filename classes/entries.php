<?php

namespace data;

class Entries {


	private static $entries = false;


	// load files from path
	public static function load($path) {

		if (file_exists($path)) {

			$dir = scandir($path);

			$idx = 0;
			foreach($dir as $file) {

				if (pathinfo($path . '/' . $file, PATHINFO_EXTENSION) == "ini") {

					$data = parse_ini_file($path . '/' . $file, true);

					if($data) {

						// no id > add stat[id]
						if (!isset($data["stat"]["id"])) {
							$data["stat"]["id"] = $idx;
						}

						self::$entries[] = new Entry($data);

						$idx++;
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


	// get assoc array
	public static function array($idx = false) {

		$ret = [];
		$d = self::get($idx);

		if (is_array($d)) {
			
			foreach ($d as $entry) {

				$ret[] = $entry->array();
			}
		}

		return $ret;
	}


	// filter entries by key, value
	// type: data or meta
	// key: meta:field 				uses the memberaccess user nameas value
	//      data: key = value 		compares the key value
	public static function filter($type, $key, $value) {

		$filtered = [];

		// get source: data or stat
		$filter = explode(":", $key);

		// get value
		$values = explode("@", $value);
		// select source
		// @ http > get from session
		if (count($values) > 1) {

			switch($values[1]) {
				case "http":

					$value = Session::param($values[0]);
					break;
			}
		}

		// valid key
		if ($type && $key) {

			foreach (self::$entries as $entry) {

				switch ($type) {

					case "data":
						if ($entry->find($key, $value)) {
							$filtered[] = $entry;
						}
						break;


					// filter by meta entry
					case "stat":

						// memberaccess supported
						// get username
						if ($key == "user" && class_exists("\ma\Access") && \ma\Access::user()) {
							$value = \ma\Access::user()->username();
						}
// debug($entry);
// debug($value);
						if ($entry->stat($key) && $entry->stat($key) == $value) {
							$filtered[] = $entry;
						}

						break;
				}
			}
		}

		self::$entries = $filtered;
	}


	// sort entries by key and direction
	public static function sort($key, $order, $dir) {

	}


	// return count of entries
	public static function count() {

		if (self::$entries) {
			return count(self::$entries);
		}

		return false;
	}
}