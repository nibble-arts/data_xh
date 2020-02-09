<?php

namespace form;

class Api {

	public static function fetch($query) {

		if ($query) {

			$data =  Source::fetch($query);

			echo json_encode($data);

			die();
		}
	}
}

?>