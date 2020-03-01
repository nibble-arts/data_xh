<?php

namespace data;

class Query {


	private $request;
	private $query;


	// definition format:
	//	type:query@source[>fields[>format]]
	//
	//  	field=value^,field=value@file>disp1,disp2,...
	//		^ boolean and
	//		, boolean or
	//		or before and, no brackets
	public function __construct($definition) {

		$request = [];

		$parts = explode (":", $definition);

		if (count ($parts) > 1) {

			preg_match("/([^\@]+)@([^\>]+)>?(.*)/", $parts[1], $matches);

			$this->query = $matches[1];

			$this->request["type"] = $parts[0];
			$this->request["source"] = $matches[2];
			$this->request["fields"] = $matches[3];
			$this->request["alias"] = "";
			$this->request["format"] = "";

			// split display and format
			preg_match("$([^\>]+)>?(.*)$", $this->request["fields"], $matches);


			// add format
			if (count($matches) > 1 && $matches[2]) {
				$this->request["fields"] = $matches[1];
				$this->request["format"] = $matches[2];
			}

			// add aliases
			list($this->request["fields"], $this->request["alias"]) = $this->alias($this->request["fields"]);
		}
	}


	// get aliases
	private function alias($field_string) {

		$f = [];
		$a = [];

		$fields = explode(",", $field_string);

		foreach ($fields as $field) {

			if ($field) {

				// has alias
				if (preg_match("$([^\ ]+)\ as\ (.*)$", $field, $match)) {
					$f[] = $match[1];
					$a[] = $match[2];
				}

				else {
					$f[] = $field;
					$a[] = "";
				}
			}
		}

		return [$f, $a];
	}


	// return definition
	public function definition() {

		if (is_array($this->request)) {

			$ret = $this->request["type"] . ":" . $this->query . "@" . $this->request["source"];

			if ($this->request["fields"]) {
				$ret .= ">" . $this->request["fields"];
			}

			if ($this->request["format"]) {
				$ret .= ">" . $this->request["format"];
			}

			return $ret;
		}

		return false;
	}


	// set query strint
	public function set_query($string) {
		$this->query = $string;
	}

	
	// return raw query string
	public function raw_query() {
		return $this->query;
	}


	// return query array
	public function query() {
		return $this->split_query($this->query);
	}


	// return query array
	public function array() {
		return $this->request;
	}


	// return part as magic method
	public function __call($name, $attr) {

		if (isset($this->request[$name])) {
			return $this->request[$name];
		}
	}


	// parse parameter variables
	// param overrides http parameter
	public function parse($param = []) {

		// check for variable string
		while (preg_match('/\$([^\,\^\b]+)/', $this->query, $match) !== false) {

			// no hit
			if (!count($match)) {
				break;
			}

			$var = $match[1];

			if (isset($param[$var])) {
				$this->query = str_replace($match[0], $param[$var], $this->query);
			}

			elseif (Session::param($var)) {
				$this->query = str_replace($match[0], Session::param($var), $this->query);
			}

			else {
				$this->query = str_replace($match[0], "*", $this->query);
			}
		}
	}


	// split query string
	// comma separated => or
	// ^ separated => and
	//   array[ and [ or, or ], and [ or, or ] ]
	private function split_query ($query) {

		$bool = explode("^", $query);

		for ($i = 0;$i < count($bool); $i++) {
			$bool[$i] = explode(",", $bool[$i]);
		}

		return $bool;
	}
}