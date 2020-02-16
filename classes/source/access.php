<?php

/*
 * acces the informational from the memberaccess plugin
 */
namespace data\source;

class Access {

	public static function fetch ($attribute) {

		if (class_exists ("\ma\Access") && \ma\Access::logged()) {

			return ["data" => \ma\Access::user($attribute)];
		}
	}
}

?>