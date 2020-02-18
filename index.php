<?php

/*if (!defined('CMSIMPLE_VERSION') || preg_match('#/database/index.php#i',$_SERVER['SCRIPT_NAME'])) 
{
    die('no direct access');
}*/


define("DATA_CONTENT_BASE", $pth["folder"]["content"]);
define("DATA_DOWNLOADS_BASE", $pth["folder"]["downloads"]);
define("DATA_BASE", $pth["folder"]["plugin"]);


// init class autoloader
// include "autoload.php";
// autoload("form");

spl_autoload_register(function ($path) {

	if ($path && strpos($path, "data\\") !== false) {

		$path = "classes/" . str_replace("data\\", "", strtolower($path)) . ".php";
		$path = str_replace("\\", "/", $path);

		include_once $path; 
	}
});


// init plugin
data\Main::init($plugin_cf, $plugin_tx);

// execute api access
data\Api::fetch();


// ===============================================================
// plugin to create a form and send the result to an email address
function data($form = false, $format = false, $query = false) {

	global $onload, $su, $f;

	$ret = "";

	// override form name from http
	// if (data\Session::param("form")) {
	// 	$form = data\Session::param("form");
	// }

	// override query from plugin call
	if (!data\Session::param("query")) {
		data\Session::set_param("query", $query);
	}

	// execute form actions
	data\Main::load($form);
	data\Action::execute($form);

	$ret = data\Main::render($format);

	$ret .= data\Message::render();

	return $ret;
}


?>
