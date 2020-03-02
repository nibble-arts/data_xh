<?php

namespace data;

class Api {

	public static function fetch() {

		if (Session::param("action") && Session::param("source")) {

			// create query object from parameter source
			$query = new Query(Session::param("source"));

			// load source data
			Source::load($query);

			switch (Session::param("action")) {
				
				case "insert":
	debug("insert");
	// debug(Session::debug());
					Message::success("insert");

					// echo "";
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

						$data = Source::fetch();
						echo json_encode($data);
					}

					break;
			}

			// stop execution
			die();
		}

		// remove source from http parameters
		// Session::remove_http("source");
	}
}

?>