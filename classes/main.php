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

		// Parse::init();

	}


	public static function load($form) {

		self::$form = $form;

		$query = false;
		$data = false;
		$xsl = false;

		self::$xml = new \DOMDocument("1.0", "UTF-8");
		self::$xml->loadXML("<form></form>");

		$field_xml = new \DOMDocument("1.0", "UTF-8");
		$field_xml->loadXML("<fields></fields>");

		$data_xml = new \DOMDocument("1.0", "UTF-8");
		$data_xml->loadXML("<data></data>");

		// ========================
		// load form
		Form::init(self::$form);

		$field_xml->loadXML(Form::xml());

		// ========================
		// load data
		$query = Form::get_self();

		// create source uri for api and load data
		if ($query) {

			// parse variables
			$query = self::variables($query);

			// load data from api
			$uri = \form\Path::create([$_SERVER['SCRIPT_URI']]) . "?source=" . $query;
			$data = json_decode(file_get_contents($uri), true);

			// convert to xml
			$data_xml = Array2XML::createXML("data", $data);

		}

		// ========================
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


	// parse uri variables
	public static function variables($query) {

		// check for variable string
		if (preg_match('/\$([^\,\^\@\b]+)/', $query, $match)) {

			$var = $match[1];

			if (Session::param($match[1])) {
				$query = str_replace($match[0], Session::param($match[1]), $query);
			}
		}

		return $query;
	}


	// render form with format
	public static function render($format) {

		$result = "";

		$path = Path::create([Config::form_content(),  self::$form, $format . ".xsl"]);

		if (file_exists($path)) {

			// load stylesheet
			$xslt = new \DomDocument();
			$xslt->load($path);

			// create xslt processor
			$t = new \XSLTProcessor();
			$t->importStylesheet($xslt);

			// transform
			$result = $t->transformToXml(self::$xml);

		}

		else {
			Message::failure("fail_noform");
		}

		return $result;
	}



	// execute save action
	public static function action ($form) {

		if (Session::post("action")) {

		}

		// check for action
		if (Session::post("_formsubmit_")) {

			$data = [];
			$keys = Session::get_param_keys();

			// get valus from post_prefix* keys
			foreach ($keys as $key) {

				if (($pos = strpos($key, Config::post_prefix())) !== false) {
					$data[substr($key, $pos + strlen(Config::post_prefix()))] = Session::post($key);
				}
			}

			$entry = new Entry($data);
			$entry->save(FORM_CONTENT_BASE . Config::form_path() . "/" . $form . "/", time() ."_" . $form . ".ini");


//TODO get email metadata from xml
			
			if (class_exists ("\ma\Access") && \ma\Access::logged()) {

				$receiver = \ma\Access::user("email");
				$subject = "Wettbewerbsnennung";

				$message = "Ihre Nennung ist eingegangen\n\n" . $entry->render();

				$email = new Mail("noreply@filmautoren.at");

				if ($email->send($receiver, $subject, $message)) {
					Message::success("email_sent");
				}
				else {
					Message::failure("email_fail");
				}
			}

			Session::remove_http();
		}
	}
}

?>