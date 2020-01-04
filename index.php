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
spl_autoload_register(function ($path) {

	if ($path && strpos($path, "form\\") !== false) {
		$path = "classes/" . str_replace("form\\", "", strtolower($path)) . ".php";
		include_once $path; 
	}
});


form\Main::init($plugin_cf, $plugin_tx);


// plugin to create a form and send the result to an email address
function form($form="", $function="") {

	global $onload, $su, $f;

	// init memberaccess integration
	// fm\Memberaccess::init();


	// create form definition path and load entries
	$path = FORM_CONTENT_BASE . FORM_PATH . "/" . $form . ".xml";

	form\Parse::load($path);
	form\Parse::parse();


	// memberaccess integration
	if(class_exists("ma\Access")) {
		define("FORM_MAIL_ACCESS_SUPPORT", true);
	}
	else {
		define("FORM_MAIL_ACCESS_SUPPORT", false);
	}


	$ret = "";
	$ret_send = "";


	// return script include
	$ret .= '<script type="text/javascript" src="' . FORM_BASE . 'script/form.js"></script>';

	// add to onload
	$onload .= "form_init();";


	if (form\Parse::exists()) {

		switch (strtolower($function)) {


			// admin
			case "administration":
				fm\Admin::fetch($path);
				$ret .= fm\Admin::render($form);
				break;


			// show form
			default:
				$ret .= form\Parse::serialise();


// die($ret);
				break;

		}
	}


	// no form definition found
	else {
		$ret .= '<div class="xh_fail">Form definition not found</div>';
	}


	// EXECUTE SEND ACTION
	if (fm\Session::post("action") == "form_send") {


// 		// no setting in form definition
// 		// use global settings
// 		if (!$settings) {

// 			$settings = [
// 				// "target" => "",
// 				"sender" => fm\Config::mail_sender(),
// 				"address" => fm\Config::mail_address(),
// 				"subject" => fm\Config::mail_subject()
// 			];
// 		}

// 		$sender = new fm\Sender($settings["sender"], $form);
// 		$sender->set_key_names(["Frage","Antwort"]);
// 		$sender->add_data($_POST);

// 		$res = $sender->send($settings["address"], $settings["subject"]);


// 		if ($res) {
// 			$ret_send .= '<div class="xh_info">' . fm\Text::mail_sent() . '</div>';
// 		}
// 		else {
// 			$ret_send .= '<div class="xh_warning">' . fm\Text::mail_sent_fail(). '</div>';
// 		}

// 		// create remember string
// 		$remember = $_POST;
// 		$remember["action"] = "ma_remember";

// // debug($remember);

// 		foreach ($remember as $key => $val) {
// 			$rem[] = $key . "=" . $val;
// 		}


// 		// return link
// 		$ret_send .= '<p><a href="?' . $su . '&' . implode("&", $rem) . '">neue Bewertung</a></p>';

// 		return $ret_send;
	}

	// return form
	else {
		return $ret;
	}
}


?>
