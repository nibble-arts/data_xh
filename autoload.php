<?php

// init class autoloader
function autoload () {

	spl_autoload_register(function ($path) {
	
		if ($path && strpos($path, "form\\") !== false) {

			$path = "classes/" . str_replace("form\\", "", strtolower($path)) . ".php";

			include_once $path; 
		}
	});
}
	
?>