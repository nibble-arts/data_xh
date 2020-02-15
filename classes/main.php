<?php

namespace form;


class Main {

	private static $xml;
	private static $form;


	// init plugin
	public static function init($config, $text) {

		// load plugin data
		Session::load();
		Config::init($config["form"]);
		Text::init($text["form"]);

	}


	public static function load($form) {

		global $su;

		self::$form = $form;

		$data = false;
		$xsl = false;

		self::$xml = new \DOMDocument("1.0", "UTF-8");
		self::$xml->loadXML("<form></form>");

		$field_xml = new \DOMDocument("1.0", "UTF-8");
		$field_xml->loadXML("<fields></fields>");

		$data_xml = new \DOMDocument("1.0", "UTF-8");
		$data_xml->loadXML("<data></data>");

		// ===============================================
		// load form
		Form::init(self::$form);

		$field_xml->loadXML(Form::xml());


		// ===============================================
		// load initial data
		// parse query and variables
		$q = new Query(Form::get_self());
		$q->parse();

		// load data from api
		$urlbase = Path::create([Session::uri('root')]);

		$uri = "http://" . \form\Path::create($urlbase) . "?&action=select&source=" . $q->definition();
		$data = json_decode(file_get_contents($uri), true);

		// convert to xml
		$data_xml = Array2XML::createXML("data", $data);


		// ===============================================
		// combine xmls
		$root = self::$xml->getElementsByTagName("form")->item(0);

		// add fields to xml
		$fields = $field_xml->getElementsByTagName("fields")->item(0);
		$new = self::$xml->importNode($fields, true);
		$root->appendChild($new);

		// add data to xml
		$datas = $data_xml->getElementsByTagName("data")->item(0);
		$new = self::$xml->importNode($datas, true);
		$root->appendChild($new);

	}



	// render form with format
	public static function render($format) {

		global $onload;

		$result = "";

		$path = Path::create([Config::form_content(),  self::$form, $format . ".xsl"]);

		if (file_exists($path)) {

			// load stylesheet
			$xslt = new \DomDocument();
			$xslt->load($path);

			// create xslt processor
			$t = new \XSLTProcessor();
			$t->importStylesheet($xslt);

			// add parameters
			$t->setParameter("", "uri", Config::url_detail());
			$t->setParameter("", "form", self::$form);
			$t->setParameter("", "prefix", Config::post_prefix());

			// add referer
			if (isset($_SERVER['HTTP_REFERER'])) {
				$t->setParameter("", "return", $_SERVER['HTTP_REFERER']);
			}

			// transform
			$result = $t->transformToXml(self::$xml);

		}

		else {
			Message::failure("fail_noform");
		}


		// add javascript start 
		$onload .= "form_init();";

		// add messages
		$result = Message::render() . $result;

		return $result;
	}
}

?>