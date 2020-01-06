<?php

namespace form;

class Entry {

	private $data;
	private $legend;
	private $meta;

	private $cursor;

	public function __construct($data) {

		$this->reset();

		$this->data = $data["data"];
		$this->legend = $data["legend"];
		$this->meta = [];

		// handle data without meta section
		if (isset($data["meta"])) {
			$this->meta = $data["meta"];
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
}

?>