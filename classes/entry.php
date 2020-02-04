<?php

namespace form;

class Entry {

	private $data;
	private $stat;

	private $cursor;
 

	// two variants for construction 
	// $data: [data => [key => value, ...], stat => []]
	// $data: [key => value, ...]
	public function __construct($data) {

		$this->reset();

		// data has data section
		if (isset($data["data"])) {
			$this->data = $data["data"];
			$this->stat = [];
		}

		// use key => value array as data
		else {
			$this->data = $data;
		}

		// data has stat section
		if (isset($data["stat"])) {
			$this->stat = $data["stat"];
		}

		// add statdata
		else {
			$this->stat = [
				"time" => "",
				"timestamp" => time()
			];

			// if memberaccess plugin exists, add user
			if (class_exists ("\ma\Access") && \ma\Access::logged()) {
				$this->stat["user"] = \ma\Access::user("username");
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


	// get array data
	public function array() {
		return["data" => $this->data, "stat" => $this->stat];
	}


	// find key = value
//TODO add truncation
	public function find ($key, $value) {

		if (isset($this->data[$key]) && $this->data[$key] == $value) {
			return $this->data[$key];
		}

		else return false;
	}

	// get legend array
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

	
	// return stat section value or stat array
	public function stat ($key = false) {

		// return key value
		if ($key) {

			if (isset($this->stat[$key])) {
				return $this->stat[$key];
			}
			else {
				return false;
			} 
		}

		// return array
		else {
			return $this->stat;
		}
	}


	// save entry to path
	public function save ($path, $file) {

		// create dir if not exists
		if (!file_exists($path)) {

			if (!mkdir($path, true)) {
				Message::failure("fail_data_mkdir");
				return false;
			}
		}

		if (!file_put_contents($path.$file, $this->array2ini())) {
			Message::failure("fail_filewrite");
		}

		else {
			Message::success("data_save");
		}
	}


	// render to ini string
	private function array2ini () {

		$data = [];
		$initemp = [];

		// create key = value lines
		while (($entry = $this->get()) !== false) {
			$initemp[] = key($entry) . '="' . $entry[key($entry)] . '"';
		}

		// data section
		$ini = "[data]\n";
		$ini .= implode("\n", $initemp);
		$ini .= "\n";

		$ini .= "\n";
		$ini .= "[stat]\n";
		$ini .= 'time="' . date("Y-m-dTH:i:s", time()) . "\"\n";
		$ini .= "timestamp=" . time();

		// add active user
		if (class_exists("\ma\Access") && \ma\Access::user()) {
			$ini .= "\n";
			$ini .= "user=" . \ma\Access::user()->username() . "\n";
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

		$ret .= "\nUser: " . $this->stat("user") . " - Time: " . View::htime($this->stat("timestamp"));

		return $ret;
	}

}

?>