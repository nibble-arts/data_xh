<?php

namespace form;


class Main {

	// init plugin
	public static function init($config, $text) {


		// load plugin data
		Session::load();
		Config::init($config["form"]);
		Text::init($text["form"]);

		Parse::init();

	}


	// execute action
	public static function action ($form) {

		// check for action
		if (Session::post("_formsubmit_")) {

			$data = [];
			$keys = Session::get_param_keys();

			// get valus from _form_* keys
			foreach ($keys as $key) {

				if (($pos = strpos($key, "_form_")) !== false) {
					$data[substr($key, $pos + strlen("_form_"))] = Session::post($key);
				}
			}

			$entry = new Entry($data);
			$entry->save(FORM_CONTENT_BASE . FORM_PATH . "/" . $form . "/", time() ."_" . $form . ".ini");

		}
	}
}

?>