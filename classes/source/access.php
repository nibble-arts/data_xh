<?php

/*
 * acces the informational from the memberaccess plugin
 */
namespace form\source;

class Access {

	public static function fetch ($attribute) {
	
		if (class_exists (\ma\Access) && \ma\Access::logged()) {
			
			return \ma\Access::user($attribute);
		}
	}
}

?>