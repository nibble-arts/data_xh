<?php

namespace form;

class Source {
	

	private static $data = false;


	// fetch data from source
	public static function fetch ($definition = false) {

		if ($definition) {
			return self::parse($definition);
		}
	}
	

	// get source query string without format
	public static function get_query () {

		if (self::$data) {

			$request = self::$data["request"];
			return $request["type"] . ":" . $request["query"] . "@" . $request["file"] . ">" . $request["display"];
		}
	}
	

	// Parse source definition
	private static function parse($definition) {

		$ret = ["data" => ""];

		$parts = explode (":", $definition);

		if (count ($parts) > 1) {
			
			$className = "form\\source\\" . ucfirst(trim($parts[0]));

			// call source class
			if (class_exists($className)) {
				self::$data = $className::fetch(trim($parts[1]));
				$ret = self::$data["data"];
			}
			
			// class not found
			// return empty string
			else {
				Message::failure ("source_class_missing");
			}
		}
		
		// source definition not correct
		else {
			Message::failure ("source_definition_error");
		}

		return $ret;
	}
}

?>