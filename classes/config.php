<?php

/* CMSimple_XH config class
 *
 * @ Version 1.0
 * init with config array
 */

namespace data;

class Config {

	private static $config;


	// init config
	public static function init($data) {
		self::$config = $data;
	}


	// get by magic method
	public static function __callStatic($name, $attr) {

		return self::get($name);
	}


	// get config parameter
	public static function get($name = false) {

		if (isset(self::$config[$name])) {
			return self::$config[$name];
		}

		elseif ($name === false) {
			return self::$config;
		}

		else {
			return false;
		}
	}
}

?>