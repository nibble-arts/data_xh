<?php

namespace form\source;

class File {


	// fetch data from file
	public static function fetch ($definition) {

		$ret = "";
		$parts = explode ("@", $definition);
		
		if (count ($parts) > 1) {

			$field = $parts[0];
			$file = $parts[1];
	
			$path = FORM_CONTENT_BASE . FORM_PATH . "/file." . $file . ".ini";

			if (file_exists($path)) {
				
				$data = parse_ini_file($path);
				if ($data && isset($data[$field])) {
					$ret = implode("|", $data[$field]);
				}

			}
		}

		return $ret;
	}


	// write file
	// Entry $data
	public static function write ($name, $data) {

		debug($name);
		debug($data);
	}
}

?>