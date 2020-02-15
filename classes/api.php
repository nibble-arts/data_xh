<?php

namespace form;

class Api {

	public static function fetch() {

		$query = new Query(Session::param("source"));

		Source::load($query);

		switch (Session::param("action")) {
			
			case "insert":
debug("insert");
debug(Session::debug());
				Message::success("insert");

				echo "";
				die();

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

					$data =  Source::fetch();

					echo json_encode($data);
		
					die();
				}
				break;
		}

	}
}

?>