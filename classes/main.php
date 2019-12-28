<?php

namespace fm;


class Main {

	public static function init($config, $text) {

		// load plugin data
		Session::load();
		Config::init($config["form_mail"]);
		Text::init($text["form_mail"]);

	}
}