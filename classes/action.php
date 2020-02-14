<?php

namespace form;

class Action {

		// execute save action
	public static function execute ($form) {


		// new save action
		if (Session::post("form_action") == "form_insert" && (Session::post("form_button") == "speichern")) {

			// debug(Session::debug());


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

			// update data
			if (Source::update(Form::get_self(), $data)) {
				Message::success("data_save");
			}

			else {
				Message::failure("fail_filewrite");
			}

//TODO add remove_http with wildcards
			// Session::remove_http(Config::post_prefix());

		}


		// check for action
// 		if (Session::post("_formsubmit_")) {

// 			$data = [];
// 			$keys = Session::get_param_keys();

// 			// get valus from post_prefix* keys
// 			foreach ($keys as $key) {

// 				if (($pos = strpos($key, Config::post_prefix())) !== false) {
// 					$data[substr($key, $pos + strlen(Config::post_prefix()))] = Session::post($key);
// 				}
// 			}

// 			$entry = new Entry($data);
// 			$entry->save(FORM_CONTENT_BASE . Config::form_path() . "/" . $form . "/", time() ."_" . $form . ".ini");


// //TODO get email metadata from xml
			
// 			if (class_exists ("\ma\Access") && \ma\Access::logged()) {

// 				$receiver = \ma\Access::user("email");
// 				$subject = "Wettbewerbsnennung";

// 				$message = "Ihre Nennung ist eingegangen\n\n" . $entry->render();

// 				$email = new Mail("noreply@filmautoren.at");

// 				if ($email->send($receiver, $subject, $message)) {
// 					Message::success("email_sent");
// 				}
// 				else {
// 					Message::failure("email_fail");
// 				}
// 			}

// 			Session::remove_http();
// 		}
 	}
}