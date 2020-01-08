<?php

namespace form\source;

class File {


	private static $data;
	private static $request = [];

	// fetch data from file
	// definition format:
	//  	field=value@file>disp1,disp2,...
	public static function fetch ($definition) {

		$ret = "";
		self::split($definition);

		$path = FORM_CONTENT_BASE . FORM_PATH . "/file." . self::$request["file"] . ".ini";

		if (file_exists($path)) {
			
			self::$data = parse_ini_file($path, true);
			$ret = self::query();

		}

		return $ret;
	}


	// find in data
	private static function query () {

		$ret = [];
		$bool_and = [];
		$parts = self::split_query(self::$request["query"]);

		foreach ($parts as $and) {

			$bool_or = [];

			foreach ($and as $or) {
				$bool_or = array_merge($bool_or, self::get_records($or));
			}

			// set first and element
			if (!count($bool_and)) {
				$bool_and = $bool_or;
			}

			// boolean and
			else {
				$bool_and = array_intersect($bool_and, $bool_or);
			}
		}

		// fetch data
		foreach ($bool_and as $idx) {
			$new = self::get_record($idx);
			// $new["_id"] = $idx;

			$ret[$idx] = $new;
		}

		return $ret;
	} 


	// return a record
	// optional fields array, select fields to return
	private static function get_record ($idx, $fields = false) {

		$disp = array_filter(explode(",", self::$request["display"]));

		// get record data
		if (isset(self::$data[$idx])) {
			$data = self::$data[$idx];
		}

		// display fields defined
		if (count($disp)) {

			// iterate display fields
			foreach ($disp as $field) {

				// field exists > fetch data
				if (isset($data[$field])) {
					$ret[$field] = $data[$field];
				}

			}
		}

		// return all data
		else {
			$ret = $data;
		}

		return $ret;
	}


	// query data and return an array of record ids
	private static function get_records ($query) {

		$equ = explode("=", $query);

		if (count($equ) > 1) {
			return self::get_ids($equ[0], $equ[1]);
		}
	}


	// get record by field, value
	private static function get_ids ($field, $value) {

		$ret = [];

		foreach (self::$data as $key => $entry) {
//TODO add truncation

			if (isset($entry[$field]) && ($entry[$field] == $value || $value == "*")) {

				// add value
				$ret[] = $key;
			}
		}

		return $ret;
	}


	// split definition
	private static function split ($definition) {

		$ret = [];

		preg_match("$([^\@]+)@([^\>]+)>?(.*)$", $definition, $matches);

		self::$request["query"] = $matches[1];
		self::$request["file"] = $matches[2];
		self::$request["display"] = $matches[3];

	}


	// split query string
	// comma separated => or
	// whitespace separated => and
	//   array[ and [ or, or ], and [ or, or ] ]
	private static function split_query ($query) {

		$bool = explode(" ", $query);

		for ($i = 0;$i < count($bool); $i++) {

			$bool[$i] = explode(",", $bool[$i]);
		}

		return $bool;
	}


	// write file
	// Entry $data
	public static function write ($name, $data) {

		// debug($name);
		// debug($data);
	}
}

?>