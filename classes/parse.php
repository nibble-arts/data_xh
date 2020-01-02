<?php

namespace form;

class Parse {
	
	private static $tags = [];
	private static $html = false;
	
	
	// initialize tag class
	// load list of available classes
	public static function init () {
		
		if (file_exists (__DIR__ . "/tag")) {
			//Todo check for classes

			$path = __DIR__ . "/tag/";

			$dirs = scandir($path);

			foreach ($dirs as $file) {

				if (is_file($path . $file)) {
					self::$tags[pathinfo($file, PATHINFO_FILENAME)] = file_get_contents($path . $file);
				}
			}
		}
	}
	
	
	// load html string and create dom document
	public static function load ($html) {

		if (file_exists($html)) {
			$html = file_get_contents($html);
		}

		self::$html = new \DomDocument("1.0", "UTF-8");
		self::$html->loadXML($html);
	}
	
	
	// replace all tags from the tag class list
	public static function parse () {
		
		// html loaded, iterate tag classes
		if (self::$html) {

			foreach (self::$tags as $tag=>$xsl) {
				self::replace($tag, $xsl);
			}
		}
		
		return self::serialise ();
	}
	
	
	// serialise dom document
	public static function serialise () {

		if (self::$html) {
			return self::$html->saveXML();
		}
		
		return "";
	}
	
	
	// replace node using the tag class
	private static function replace ($tag, $xsl_string) {
			
		// hey nodes
		$nodes = self::$html->getElementsByTagName($tag);

		// tag found
		if ($nodes->length && in_array($tag, array_keys(self::$tags))) {

			// iterate nodes
			foreach ($nodes as $node) {

				// get source data
				if ($source = $node->getAttribute("source")) {
					debug("read source ".$source);

					$xml = '<data><option>Region 1</option><option>Region 2</option></data>';
				}

debug($xsl_string);
				$xsl = new \DomDocument();
				$xsl->load($xsl_string);
debug($xsl->saveXML());
				$t = new \XSLTProcessor();
				$t->importStylesheet($xsl);
				$t->transformToXml(self::$html);
// debug($t);
				// call tag class
				// $newNode = $className::parse($node, $source);
				// self::$html->replaceChild($newNode, $node);
			}
		}
	}
}

?>