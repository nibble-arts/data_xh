<?php

namespace form;

class Source {
	

	private static $data = false;


	// fetch data from source
	public static function fetch ($query = false) {
		return self::parse($query);
	}
	

	// updata data of source
	public static function update ($query, $data) {
debug($query);
debug($data);

		return true;
	}


	// get source query string without format
	public static function get_query () {

		if (self::$data) {

			$request = self::$data["request"];
			return $request["type"] . ":" . $request["query"] . "@" . $request["file"] . ">" . $request["display"];
		}
	}
	

	// Parse source query
	private static function parse($query) {

		$ret = ["data" => ""];

		$parts = explode (":", $query);

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
		
		// source query not correct
		else {
			Message::failure ("source_definition_error");
		}

		return $ret;
	}


	// parse query string
	private static function parse_query() {
		
	}
}

?>