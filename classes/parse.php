<?php

namespace form;

class Parse {
	
	private static $xslt;
	private static $html = false;
	
	
	// initialize tag class
	// load list of available classes
	public static function init () {
		
		$path = __DIR__ . "/tag/parse.xsl";

		self::$xslt = new \DomDocument();
		self::$xslt->load($path);

	}
	
	
	// load html string and create dom document
	public static function load ($html) {

		self::$html = new \DomDocument("1.0", "UTF-8");

		if (file_exists($html)) {
			self::$html->load($html);
		}

	}
	
	
	// check if data exists
	public static function exists() {

		if (!self::$html) {
			return false;
		}

		return true;
	}


	// serialise dom document
	public static function serialise () {

		if (self::$html) {
			return self::$html->saveXML();
		}
		
		return "";
	}
	
	
	// replace node using the tag class
	public static function parse () {
		

		$xpath = new \DOMXpath(self::$html);
		$sources = $xpath->query("//*[@source]");

		// collect external data
		foreach ($sources as $idx => $source) {

			// get source definition
			$d = Source::fetch($source->getAttribute("source"));

			// replace source attribute with data
			if ($d) {
				$source->setAttribute("source", $d);
			}

			// set ajax attribute
			// remove source
			// deactivate field
			else {

				$newAttr = self::$html->createAttribute("ajax");
				$newAttr->value = Source::get_query();

				$source->appendChild($newAttr);

				$newAttr = self::$html->createAttribute("disabled");
				$newAttr->value = "disabled";

				$source->appendChild($newAttr);

				$source->removeAttribute("source");
			}
		}


		// create and call xslt processor
		$t = new \XSLTProcessor();
		$t->importStylesheet(self::$xslt);

		// transform
		$result = $t->transformToXml(self::$html);

		// create new result dom xml
		$new_xml = new \DomDocument("1.0", "UTF-8");
		$new_xml->loadXML($result);

		self::$html = $new_xml;
	}
}

?>