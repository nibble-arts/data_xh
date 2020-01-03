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
	
	
	// serialise dom document
	public static function serialise () {

		if (self::$html) {
			return self::$html->saveXML();
		}
		
		return "";
	}
	
	
	// replace node using the tag class
	public static function parse () {
			
		// hey nodes
		$nodes = self::$html->getElementsByTagName($tag);
		$datas = [];


		$xpath = new \DOMXpath(self::$html);
		$sources = $xpath->query("//*[@source]");

		// collect external data
		foreach ($sources as $source) {
//TODO call extern reference

			$datas[$source->nodeName][] = $source->getAttribute("source");
		}


		// create and call xslt processor
		$t = new \XSLTProcessor();
		$t->importStylesheet($xsl);

		// add source data as parameters
		foreach ($datas as $tag => $data) {
			$param[$tag] = '<' . $tag . '>' . implode($data) . '</' . $tag . '>';
		}

		// set data as parameters
		$t->setParameter('', $param);

		// transform
		$result = $t->transformToXml(self::$html);
debug($result);
		// create new result dom xml
		$new_xml = new \DomDocument("1.0", "UTF-8");
		$new_xml->loadXML($result);

		self::$html = $new_xml;
	}
}

?>