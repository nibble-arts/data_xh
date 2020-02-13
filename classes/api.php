<?php

namespace form;

class Api {

	public static function fetch() {

		$query = Session::param("source");

		switch (Session::param("action")) {
			
			case "insert":

				Message::success("insert");
				break;
				
			case "update":

				Message::success("update");
				break;
			
			case "delete":

				Message::success("delete");
				break;
				
			case "select":
			// default:
				if ($query) {

					$data =  Source::fetch($query);

					echo json_encode($data);
		
					die();
				}
				break;
		}
	}
}

?>