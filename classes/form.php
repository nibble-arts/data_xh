<?php

namespace form;

class Form {

	public static function init($form) {
debug("init form");

		$path = Config::form_content() . $form . "/form.ini";

debug($path);
	}
}

?>