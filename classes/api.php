<?php

namespace form;

class Api {

	public static function fetch($query) {

		$query = Session::param("source");
		
		switch (Session::param("action")) {
			
			case "insert":
				break;
				
			case "update":
				break;
			
			case "delete":
				break;
				
			case "select":
			default:
				if ($query) {
		
					$data =  Source::fetch($query);
		
					echo json_encode($data);
		
					die();
				}
				break;
	}
}

?>