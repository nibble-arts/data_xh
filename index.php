<?php

/*if (!defined('CMSIMPLE_VERSION') || preg_match('#/database/index.php#i',$_SERVER['SCRIPT_NAME'])) 
{
    die('no direct access');
}*/


define("FORM_CONTENT_BASE", $pth["folder"]["content"]);
define("FORM_DOWNLOADS_BASE", $pth["folder"]["downloads"]);

define("FORM_BASE", $pth["folder"]["plugin"]);
define("FORM_PATH", $plugin_cf["form"]["form_path"]);


// init class autoloader
// include "autoload.php";
// autoload("form");

spl_autoload_register(function ($path) {

	if ($path && strpos($path, "form\\") !== false) {

		$path = "classes/" . str_replace("form\\", "", strtolower($path)) . ".php";
		$path = str_replace("\\", "/", $path);
		
		include_once $path; 
	}
});


form\Main::init($plugin_cf, $plugin_tx);


// plugin to create a form and send the result to an email address
function form($form = "", $function = "", $filter = false) {

	global $onload, $su, $f;


	form\Main::action($form);


//	$path = FORM_CONTENT_BASE . FORM_PATH . "/" . $form;
//	form\Entries::load($path);


	// create form definition path and load entries
	$path = FORM_CONTENT_BASE . FORM_PATH . "/" . $form . ".xml";


	// load form and parse
	form\Parse::load($path);
	form\Parse::parse($filter);


	$ret = "";
	$ret_send = "";


	// return script include
	$ret .= '<script type="text/javascript" src="' . FORM_BASE . 'script/form.js"></script>';

	// add to onload
	$onload .= "form_init();";


	$ret .= form\Message::render();


	// if form exist, render
	if (form\Parse::exists()) {

		switch (strtolower($function)) {

			// admin
			case "administration":
				form\Admin::fetch($path);
				$ret .= form\Admin::render($form, $filter);
				break;


			// show form
			default:
				$ret .= form\Parse::serialise();
				break;

		}
	}


	// no form definition found
	else {
		$ret .= '<div class="xh_fail">Form definition not found</div>';
	}

	return $ret;
}


?>
