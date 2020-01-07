<?php

namespace form;

class Entry {

	private $data;
	private $legend;
	private $meta;

	private $cursor;

	public function __construct($data) {

		$this->reset();

		// is associative array
		// split into data and legend 
		if(array_keys($data) !== range(0, count($data) - 1)) {
			$this->data = array_values($data);
			$this->legend = array_keys($data);
		}

		else {
			$this->data = $data["data"];
			$this->legend = $data["legend"];
		}

		$this->meta = [];

		// handle data without meta section
		if (isset($data["meta"])) {
			$this->meta = $data["meta"];
		}

		// add metadata
		else {
			$this->meta = [
				"time" => "",
				"timestamp" => time()
			];


//TODO
			// if memberaccess plugin exists, add user
			if (class_exists ("\ma\Access") && \ma\Access::logged()) {
				$this->meta["user"] = \ma\Access::user("username");
			}
		}
	}


	// reset cursor
	public function reset() {
		$this->cursor = 0;
	}


	// get key value pair
	public function get ($idx = false) {

		// return key => value by idx
		if ($idx !== false) {
			if (isset($this->data[$idx]) && isset($this->legend[$idx])) {
				return [$this->legend[$idx] => $this->data[$idx]];
			}
		}

		// return key => value using cursor
		elseif ($this->cursor < count($this->data)) {

			if (isset($this->data[$this->cursor]) && isset($this->legend[$this->cursor])) {
				$this->cursor++;
				return [$this->legend[$this->cursor-1] => $this->data[$this->cursor-1]];
			}
		}

		return false;
	}



	// get legend array
	public function legend() {
		return $this->legend;
	}

	
	// return meta section value or meta array
	public function meta ($key = false) {

		// return key value
		if ($key) {

			if (isset($this->meta[$key])) {
				return $this->meta[$key];
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


	// save entry to path
	public function save ($path, $file) {

		if (!file_exists($path)) {
			mkdir($path, true);
		}

		file_put_contents($path.$file, $this->array2ini());
	}


	// render to ini string
	private function array2ini () {

		$idx = 0;
		$data = [];
		$legend = [];


		for ($idx = 0; $idx < count($this->data); $idx++) {

			$data[] = $idx . '="' . $this->data[$idx] . '"';
			$legend[] = $idx . '="' . $this->legend[$idx] . '"';
		}


		// data section
		$ini = "[data]\n";
		$ini .= implode("\n", $data);
		$ini .= "\n";

		$ini .= "\n";
		$ini .= "[legend]\n";
		$ini .= implode("\n", $legend);
		$ini .= "\n";

		$ini .= "\n";
		$ini .= "[meta]\n";
		$ini .= "time=" . date("Y-m-dTH:i:s", time()) . "\n";
		$ini .= "timestamp=" . time();

		// add active user
		if (class_exists("\ma\Access") && \ma\Access::user()) {
			$ini .= "\n";
			$ini .= "user=" . \ma\Access::user()->username() . "\n";
		}

		return $ini;
	}
}

?>