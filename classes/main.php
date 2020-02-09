<?php

namespace form;


class Main {

	// init plugin
	public static function init($config, $text) {

		// load plugin data
		Session::load();
		Config::init($config["form"]);
		Text::init($text["form"]);

		// Parse::init();

	}


	public static function load($form) {

		// load form
		Form::init($form);

		// load data
		$query = Form::get_self();

		// create source uri and load data
		if ($query) {

			$query = self::variables($query);

			$uri = \form\Path::create([$_SERVER['SCRIPT_URI']]) . "?source=" . $query;

			debug(json_decode(file_get_contents($uri)));
		}


		// // load xsl



// debug(Form::xml());
	}


	// parse uri variables
	public static function variables($query) {

debug($query);
		preg_match_all("/[^\$]?+(\$[^,\^\@]+)/", $query, $matches);

debug($matches);

		return $query;
	}


	public static function render() {

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