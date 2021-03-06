<?php

namespace data\source;


class File {


	private $data;
	private $query;

	// create with query object
	public function __construct($query) {

		$this->query = $query;

		$path = \data\Path::create([\data\Config::file_content_path(), "file." . $query->source() . ".ini"]);

		// load file
		if (file_exists($path)) {
			$this->data = parse_ini_file($path, true);
		}
	}


	// fetch data from file
	// definition format:
	//  	field=value^,field=value@file>disp1,disp2,...
	//		^ boolean and
	//		, boolean or
	//		or before and, no brackets
	public function fetch () {

		return [
			"data" => $this->query(),
			"request" => $this->query
		];
	}


	public function update ($data) {

	}


	// find in data
	private function query () {

		$ret = [];
		$bool_and = [];
		$parts = $this->query->query();

		foreach ($parts as $and) {

			$bool_or = [];

			// iterate ands
			foreach ($and as $or) {

				if (is_array($this->get_records($or))) {
					$bool_or = array_merge($bool_or, $this->get_records($or));
				}
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
			$new = $this->get_record($idx);
			$ret[$idx] = $new;
		}

		if ($this->query->format()) {
			$ret = implode("|", $ret);
		}

		return $ret;
	} 


	// return a record
	// optional fields array, select fields to return
	private function get_record ($idx, $fields = false) {

		$fields = $this->query->fields();
		$alias = $this->query->alias();
		$format = $this->query->format();
		// $disp = array_filter(explode(",", $fields)); deprecated: fields in array

		// get record data
		if (isset($this->data[$idx])) {
			$data = $this->data[$idx];
		}

		// display fields defined
		if (count($fields)) {

			// iterate display fields
			// use alias
			foreach ($fields as $idx => $field) {

				// field exists > fetch data
				if (isset($data[$field])) {

					// user alias
					if ($alias[$idx]) {
						$alias_name = $alias[$idx];
					}

					// use field name
					else {
						$alias_name = $field;
					}

					$ret[$alias_name] = $data[$field];
				}
			}
		}

		// return all data
		else {
			$ret = $data;
		}

		// format fields
		if ($format) {
			$ret = $this->format($ret, $format);
		}

		return $ret;
	}


	// query data and return an array of record ids
	private function get_records ($query) {

		// return all data
		if ($query == "*") {
			return array_keys($this->data);
		}

		// get field/value pair
		$equ = explode("=", $query);

		if (count($equ) > 1) {
			return $this->get_ids($equ[0], $equ[1]);
		}
	}


	// get record by field, value
	private function get_ids ($field, $value) {

		$ret = [];

		foreach ($this->data as $key => $entry) {
//TODO add truncation
			if (isset($entry[$field]) && ($entry[$field] == $value || $value == "*")) {

				// add value
				$ret[] = $key;
			}
		}

		return $ret;
	}


	// format fields by format string
	//		fields in curly brackets are replaced by the vale
	private function format ($fields, $format) {

		$ret = "";
		$cursor = 0;
		$check = $format;

		// match {field_name}
		preg_match_all("$\{([^\{]+)\}$", $check, $matches, PREG_OFFSET_CAPTURE);

		// iterate found fields
		foreach ($matches[1] as $match) {

			$name = $match[0];
			$start = $match[1];
			$len = strlen($match[0]);

			// add leading part
			$ret .= substr($format, $cursor, $start - $cursor-1);

			// replace {field} by dada
			if (isset($fields[$name])) {
				$ret .= $fields[$name];
			}

			$cursor = $start + $len + 1;
		}

		// add rest
		$ret .= substr($format, $cursor);

		return $ret;
	}


	// write file
	// Entry $data
	public function write ($name, $data) {

		// debug($name);
		// debug($data);
	}
}

?>