<?php

namespace form;

class Source {
	

	private static $data = false;
	private static $plugin = false;


	// load data source by query
	public static function load($query) {

		$ret = ["data" => ""];

		// has query
		if ($query) {

			$parts = explode (":", $query);

			if (count ($parts) > 1) {

				$className = "form\\source\\" . ucfirst(trim($parts[0]));

				// call source class > load data
				if (class_exists($className)) {

					self::$plugin = new $className(trim($parts[1]));
					self::$data = self::$plugin->fetch();
				}
				
				// class not found
				else {
					Message::failure ("source_class_missing");
				}
			}
			
			// source query not correct
			else {
				Message::failure ("source_definition_error");
			}
		}
	}


	// fetch data from source
	public static function fetch () {

		if (isset(self::$data["data"])) {
			return self::$data["data"];
		}

		return ["data" => ""];
	}
	

	// updata data of source
	public static function update ($query, $data) {

// debug($query);
// debug($data);

debug(self::fetch());

		return true;
	}


	// get source query string without format
	public static function get_query () {

		if (self::$data) {

			$request = self::$data["request"];

			return $request["type"] . ":" . $request["query"] . "@" . $request["file"] . ">" . $request["display"];
		}
	}
}

?>