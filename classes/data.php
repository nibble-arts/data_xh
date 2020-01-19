<?php

namespace form;

class Data {

	private $entries;
	private $path;

	// construct data object
	public function __construct ($path = false) {
		
		$this->entries = [];
		
		if ($path) {
			$this->load ($path);
	}


	// load files from path
	public function load($path, $file = false) {

		$this->path = $path;
		
		// load data file
		// [id]
		// key = "value"
		// ...
		if ($file) {
			if (file_exists($path . $file)) {
				$file_data = parse_ini_file($path . '/' . $file, true);
				
				if (is_array($file_data)) {
					
					foreach ($file_data as $idx => $entry) {
						$this->entries[$idx] = new Entry($entry);
					}
				}
			}
		}
			
				
		// load from single entry file list
		// auto increment id
		if (file_exists($path)) {

			$dir = scandir($path);
			
			foreach($dir as $file) {

				if (pathinfo($path . '/' . $file, PATHINFO_EXTENSION) == "ini") {

					$data = parse_ini_file($path . '/' . $file, true);

					if($data) {
						$this->$entries[] = new Entry($data);
					}
				}
			}
		}
	}


	// get entry or array of entries
	public function get($idx = false) {

		if ($this->$entries) {

			if ($idx !== false) {

				if ($idx < count($this->$entries)) {
					return $this->$entries[$idx];
				}
			}

			else {
				return $this->$entries;
			}
		}

		return false;
	}


	// add Entry
	// key => value array
	public function add($data) {
		
		$this->entries [] = new Entry($data);
	}
	
	
	// update entry
	public function update ($idx, $data) {
	}
	
	
	// remove entry
	public function remove ($idx) {
	}


	// filter entries by key, value
	// type: data or meta
	// key: meta:field 				uses the memberaccess user nameas value
	//      data: key = value 		compares the key value
	public function filter($type, $key, $value) {

		$filter = explode(":", $key);
		$filtered = [];

		// valid key
		if ($type && $key) {

			foreach ($this->$entries as $entry) {

				switch ($type) {

					case "data":
						if ($entry->find($key, $value)) {
							$filtered[] = $entry;
						}
						break;


					// filter by meta entry
					case "meta":

						// memberaccess supported
						// get username
						if ($key == "user" && class_exists("\ma\Access") && \ma\Access::user()) {
							$value = \ma\Access::user()->username();
						}

						if ($entry->meta($key) == $value) {
							$filtered[] = $entry;
						}

						break;
				}
			}
		}

		$this->$entries = $filtered;
	}


	// sort entries by key and direction
	public function sort($key, $order, $dir) {

	}


	// save data to file
	public function save() {
		
		$string = "";
		
		foreach ($this->entries as $entry) {
			$string .= $entry->ini();
		}
	}
	
	
	// return count of entries
	public function count() {

		if ($this->$entries) {
			return count($this->$entries);
		}

		return false;
	}
}