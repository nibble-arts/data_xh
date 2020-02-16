<?php

namespace data;

class Action {

		// execute save action
	public static function execute ($form) {

		// Source::load(Form::get_self());

		// new save action
		if (Session::post("data_action") == "form_update" && (Session::post("data_button") == "speichern")) {

			$data = [];
			$keys = Session::get_param_keys();

			// get valus from post_prefix* keys
			foreach ($keys as $key) {

				if (($pos = strpos($key, Config::post_prefix() . "_")) !== false) {

					// extract id and field name
					$data_parts = explode("_", substr($key, $pos + strlen(Config::post_prefix() . "_")));

					// has both components
					if (count($data_parts) > 1) {
						$data[$data_parts[0]][$data_parts[1]] = Session::post($key);
					}

					// $data[substr($key, $pos + strlen(Config::post_prefix() . "_"))] = Session::post($key);
				}
			}
		}
 	}
}