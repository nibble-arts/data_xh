<?php

namespace form;

class Form {

	private static $fields = false;
	private static $cursor;
	private static $data;


	// load form definition and create fields
	public static function init($form) {

		self::reset();

		$path = Path::create([Config::form_content(), $form, "form.ini"]);

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

					self::$fields[$name] = new Field($name, $def, $source, $check);
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

		if (is_array($data)) {
			
			// iterate data > save data from existing fields
			foreach($data as $key => $entry) {
				if (self::field_exists($key)) {
					self::$fields[$key]->addData($entry);
				}
				
				else {
					Message::failure("data parser: field " . $key . " doesn't exist");
			}
		}
	}

	// reset cursor
	public static function reset() {
		self::$cursor = 0;
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
		
		$ret = '<>';
		
		if (self::$fields) {
			foreach (self::$fields as $field) {
				
			}
			
			Array2XML::createXML("form", $ary);
		}
	}
}

?>