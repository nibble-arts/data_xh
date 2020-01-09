<?php

namespace form\source;

class File {


	private static $data;
	private static $request = [];

	// fetch data from file
	// definition format:
	//  	field=value^,field=value@file>disp1,disp2,...
	//		^ boolean and
	//		, boolean or
	//		or before and, no brackets
	public static function fetch ($definition) {

		$ret = "";
		self::split($definition);

		$path = FORM_CONTENT_BASE . FORM_PATH . "/file." . self::$request["file"] . ".ini";

		if (file_exists($path)) {
			
			self::$data = parse_ini_file($path, true);
			$ret = self::query();

		}
debug($ret);
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
				// debug(self::get_records($or));
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
			$ret[$idx] = $new;
		}

		return $ret;
	} 


	// return a record
	// optional fields array, select fields to return
	private static function get_record ($idx, $fields = false) {

		// get field list and format
		preg_match("$([^\>]+)>?(.*)$", self::$request["display"], $matches);

		$fields = $matches[1];
		$format = $matches[2];

		$disp = array_filter(explode(",", $fields));

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

		// format fields
		if ($format) {
			self::format($ret, $format);
		}

		return $ret;
	}


	// query data and return an array of record ids
	private static function get_records ($query) {

		// return all data
		if ($query == "*") {
			return array_keys(self::$data);
		}

		// get field/value pair
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

		$bool = explode("^", $query);

		for ($i = 0;$i < count($bool); $i++) {

			$bool[$i] = explode(",", $bool[$i]);
		}

		return $bool;
	}


	// format fields by format string
	//		fields in curly brackets are replaced by the vale
	private static function format ($fields, $format) {

		$ret = "";
		$cursor = 0;
		$check = $format;


preg_match_all("$\{([^\{]+)\}$", $check, $matches, PREG_OFFSET_CAPTURE);
debug($matches[1]);

		foreach ($matches[1] as $match) {

			$name = $match[0];
			$start = $match[1];
			$len = strlen($match[0]);

debug($name." ".$pos." ".$len);

			// add leading part
			$ret .= substr($format, $cursor, $start - $cursor);

			// replace {field} by dada
			if (isset($fields[$name])) {
				$ret .= $fields[$name];
			}

			$cursor = $start + $len;
		}


debug("result: ".$ret);
return;

$i=0;
		while ($i<10) {

			preg_match_all("$\{([^\{]+)\}$", $check, $matches, PREG_OFFSET_CAPTURE);

			if (count($matches)) {
debug($matches[1]);

				$search = $matches[0][0];
				$name = $matches[1][1];
				$pos = $matches[0][1];
				$len = strlen($search);

debug($pos." ".$len);

				// replace {field} by dada
				if (isset($fields[$name])) {
					$ret .= substr($check, 0, $len - 1);
					$ret .= $fields[$name];
				}

				$check = substr($check, $pos + $len); 
			}
			else {
				break;
			}
$i++;
		}

		$ret .= $check;


debug("result: ".$ret);
	}


	// write file
	// Entry $data
	public static function write ($name, $data) {

		// debug($name);
		// debug($data);
	}
}

?>