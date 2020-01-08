<?php

namespace form;

class Source {
	
	public static function fetch ($definition = false) {
		
		if ($definition) {
			return self::parse($definition);
		}
	}
	
	
	// Parse source definition
	private static function parse($definition) {
		$ret = "";
		
		$parts = explode (":", $definition);
		
		if (count ($parts) > 1) {
			
			$className = "form\\source\\" . ucfirst(trim($parts[0]));

			// call source class
			if (class_exists($className)) {
				$ret = $className::fetch(trim($parts[1]));
			}
			
			// class not found
			// return empty string
			else {
				Message::failure ("source_class_missing");
			}
		}
		
		// source definition not correct
		else {
			Message::failure ("source_definition_error");
		}
		
		return $ret;
	}
}

?>