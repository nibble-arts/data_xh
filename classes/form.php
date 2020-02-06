<?php

namespace form;

class Form {

	private static $fields = false;
	private static $cursor;
	private static $data;


	// load form definition and create fields
	public static function init($form) {

		self::reset();

		$path = Path::create([Config::form_content(), $form . ".ini"]);

		if (file_exists($path)) {
			$data = parse_ini_file($path, true);

			if (isset($data["fields"])) {

				foreach ($data["fields"] as $name => $def) {

					$source = false;
					$check = false;

					// get source definitions for field
					if (isset($data["source"][$name])) {
						$source = $data["source"][$name];
					}

					// get check definitions for field
					if (isset($data["check"][$name])) {
						$check = $data["check"][$name];
					}

					self::$fields[] = new Field($name, $def, $source, $check);
				}

				// if _self source, load initial data


			}

			// no field section found
			else {
				Message::failure("form_nofields");

				return false;
			}
		}

		// form not found
		else {
			Message::failure("fail_noform");

			return false;
		}
	}

	// set loaded data for form
	public static function set($data) {

	}

	// reset cursor
	public static function reset() {
		self::$cursor = 0;
	}

	// get field by id / cursor
	public static function get($idx = false) {

		if ($idx && isset(self::$fields[$idx])) {
			return self::$fields[$idx];
		}

		elseif (isset(self::$fields[self::$cursor])) {
			return self::$fields[self::$cursor++];
		}

		return false;
	}
}

?>