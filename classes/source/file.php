<?php

namespace form\source;

class File {

	public static function fetch ($file) {
	
		$path = FORM_CONTENT_BASE . FORM_PATH . "/" . $file;
		
		if ($file_exists($path)) {
			
			$data = parse_ini_file($path);
			
			if ($data) {
				
			}
		return "";
	}
}

?>