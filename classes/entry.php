<?php

namespace form;

class Entry {

	private $data;
	private $meta;

	private $cursor;
 

	// two variants for construction 
	// $data: [data => [key => value, ...], meta => []]
	// $data: [key => value, ...]
	public function __construct($data) {

		$this->reset();
		$this->meta = [
				"__created" => time(),
				"__creator" => "",
				"__modified" => time(),
				"__modifier" => ""
			];

		// data has data section
		if (isset($data["data"])) {
			$this->data = $data["data"];
			$this->meta = [];
		}

		// use key => value array as data
		else {
			$this->data = $data;
		}

		// data has meta section
		if (isset($data["meta"])) {
			$this->meta = $data["meta"];
		}

		// add metadata
		else {
			$this->meta = [
				"__created" => time(),
				"__creator" => "",
				"__modifier" => ""
			];

			// if memberaccess plugin exists, add user
			if (class_exists ("\ma\Access") && \ma\Access::logged()) {
				$this->meta["__creator"] = \ma\Access::user("username");
			}
		}
	}


	// reset cursor
	public function reset() {
		$this->cursor = 0;
	}


	// get key value pair by index
	public function get ($idx = false) {

		// get key by id
		if ($idx !== false) {
			$key = $this->legend($idx);
		}

		// get key by cursor
		else {
			$key = $this->legend($this->cursor++);
		}

		// return key => value by idx
		if ($key !== false) {
			return [$key => $this->data[$key]];
		}

		return false;
	}


	// get value by key
	public function get_by_key($key) {
		
		if (isset($this->data[$key])) {
			
			return $this->data[$key];
		}
		
		return false;
	}
	
	
	// find key = value
//TODO add truncation
	public function find ($key, $value) {

		if (isset($this->data[$key]) && $this->data[$key] == $value) {
			return $this->data[$key];
		}

		else return false;
	}

	// get array of keys
	public function legend($idx = false) {

		// return legend entry by id
		if ($idx !== false) {

			if ($idx < count($this->data)) {
				return array_keys($this->data)[$idx];
			}

			else {
				return false;
			}
		}

		// return legend array
		else {
			return array_keys($this->data);
		}

		return array_keys($this->data);
	}

	
	// return meta section value or meta array
	public function meta ($key = false) {

		// return key value
		if ($key) {

			if (isset($this->meta["__" . $key])) {
				return $this->meta["__" . $key];
			}
			else {
				return false;
			} 
		}

		// return array
		else {
			return $this->meta;
		}
	}


	// render to ini string
	private function ini () {

		$data = [];
		$initemp = [];

		// create key = value lines
		while (($entry = $this->get()) !== false) {
			$initemp[] = key($entry) . '="' . $entry[key($entry)] . '"';
		}

		// data
		$ini .= implode("\n", $initemp);

		// metadata
		$ini .= "\n";
		$ini .= "__created=" . $this->meta["__created"];
		$ini .= "__modifier=" . $this->meta["__modifier"];
		$ini .= "__modified=" . time();

		// add active user
		if (class_exists("\ma\Access") && \ma\Access::user()) {
			$ini .= "\n";
			$ini .= "__creator=" . \ma\Access::user()->username() . "\n";
		}

		return $ini;
	}


	// render entry as text
	public function render () {

		$ret = "\n";
		$this->reset();

		while (($keyval = $this->get()) !== false) {
			$ret .= ucfirst(key($keyval)) . ": " . $keyval[key($keyval)] . "\n";
		}

		$ret .= "\nUser: " . $this->meta("user") . " - Time: " . View::htime($this->meta("timestamp"));

		return $ret;
	}

}

?>