<?php

namespace form;

class Api {

	public static function fetch($definition) {
		
		if ($definition) {

			$data =  Source::fetch($definition);

			echo json_encode($data);

			die();
		}
	}
}

?>