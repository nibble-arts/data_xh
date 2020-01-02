<?php

namespace form;


class Main {

	public static function init($config, $text) {

		// load plugin data
		Session::load();
		Config::init($config["form"]);
		Text::init($text["form"]);
		// Tag::init();
		Parse::init();

	}
}