<?php

namespace form;

class Form {

	private static $fields = false;
	private static $_self = false;

	private static $cursor;


	// load form definition and create fields
	public static function init($form) {

		self::reset();

		$path = Path::create([Config::form_content(), $form, "form.ini"]);

		if (file_exists($path)) {

			$data = parse_ini_file($path, true);

			// fields found
			if (isset($data["fields"])) {

				// iterate fields
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

					self::$fields[$name] = new Field($name, $def, $source, $check);
				}

				// add _self source
				if (isset($data["source"]["_self"])) {
					self::$_self = $data["source"]["_self"];
				}
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


	// reset cursor
	public static function reset() {
		self::$cursor = 0;
	}


	// get source by name
	public static function get_self() {

		return self::$_self;
	}

	// check if field exists
	public static function field_exists($name) {
		return isset(self::$fields[$name]);
	}
	

	// get field by name
	// if no name, get iterate with cursor
	public static function get($name = false) {

		if ($name != false && isset(self::$fields[$name])) {
			return self::$fields[$name];
		}

		elseif (isset(self::$fields[self::$cursor])) {
			return self::$fields[self::$cursor++];
		}

		return false;
	}
	

	// render form to xml
	public static function xml() {
		
		$ret .= '<fields>';

			if (self::$fields) {

				foreach (self::$fields as $field) {
					$ret .= $field->render();
				}
	
			}

		$ret .= '</fields>';

		return $ret;
	}
}

?>