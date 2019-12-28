<?php

namespace form;

class Parse {
	
	private static $tags = [];
	private static $html = false;
	
	
	// initialize tag class
	// load list of available classes
	public static function init () {
		
		if (file_exists ("tag")) {
			//Todo check for classes
			$self::tags = [];
		}
	}
	
	
	// load html string and create dom document
	public static function load ($html) {
		
		$self::html = new DomDocument("1.0", "UTF-8");
		self::$html->loadHTML($html);
	}
	
	
	// replace all tags from the tag class list
	public static function parse () {
		
		// html loaded, iterate tag classes
		if ($self::html) {
			foreach ($self::tags as $tag) {
				$self::replace($tag);
			}
		}
		
		return $self::serialise ();
	}
	
	
	// replace node using the tag class
	private static function replace ($tag) {
			
		// hey nodes
		$nodes = $self::html->getElementsByTagName($tag);
		
		// tag found
		if (count ($nodes)) {
			
			// get class if exists
			$className = "tag\"" . $tag;
			
			if (class_exists($className)) {
				
				// iterate nodes
				foreach ($nodes as $node) {
					
					// call tag class
					$newNode = className($node);
					$self::html->replaceChild($newNode, $node);
			}
		}
	}
	
	
	// serialise dom document
	private static function serialise () {
		
		if ($self::html) {
			return $self::html->saveXML()
		}
		
		return "";
	}
}

?>