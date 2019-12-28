<?php

// define ('FORM_MAIL_PATH', $plugin_cf["form_mail"]["form_mail_path"]);

define ('FORM_MAIL_FORMS', $plugin_tx['form_mail']['forms']);

define ('FORM_MAIL_FAIL_READ', $plugin_tx['form_mail']['fail_fileread']);
define ('FORM_MAIL_FAIL_WRITE', $plugin_tx['form_mail']['fail_filewrite']);
define ('FORM_MAIL_FAIL_EXISTS', $plugin_tx['form_mail']['fail_fileexists']);

define ('FORM_MAIL_SAVED', $tx['message']['saved']);




function form_settings($action, $admin, $plugin) {

	global $plugin_tx, $o;

	$type = false;
	$file = false;
	$data = "";

	$actionArray = explode(":", $action);

	$command = $actionArray[0];

	if (count($actionArray) > 1) {

		$typeFile = explode("|", $actionArray[1]);

		$type = form_mail_set_slash($typeFile[0], false);
		$file = trim($typeFile[1]);
	}


	//=========================
	// save action
	if ($command == "save") {

		// set action array for edit display
		$command = "plugin_edit";

		$extenstion = pathinfo($_POST["file"], PATHINFO_EXTENSION);
		$file = pathinfo($_POST["file"], PATHINFO_FILENAME);

		$data = $_POST["data"];


		$file .= ".ini";


		// create path
		$path = FORM_CONTENT_BASE . FORM_MAIL_PATH . "/" . $file;



		// file already exists => dont save
		if (file_exists($path) && $data == "") {
				$o .= '<div class="xh_warning">' . str_replace("%s", $file, FORM_MAIL_FAIL_EXISTS) . '</div>';
		}


		// save file
		else {

			// save data
			if (file_put_contents($path, $data) !== false) {
				$o .= '<div class="xh_success">' . str_replace("%s", $file, FORM_MAIL_SAVED) . '</div>';
			}

			// error writing the file
			else {
				$o .= '<div class="xh_fail">' . str_replace("%s", $file, FORM_MAIL_FAIL_WRITE) . '</div>';
			}
		}

	}



	//=========================
	// switch action
	switch ($command) {

		case 'plugin_text':

			if ($file) {

				$delPath = explode("_", $type)[0] . "/" . $file;

				if (file_exists(FORM_CONTENT_BASE . $delPath)) {
					unlink(FORM_CONTENT_BASE . $delPath);
				}

			}

			$o .= "<h1>" . $plugin_tx["form_mail"]["title_settings"] . "</h1>";

			$o .= "<p>" . $plugin_tx["form_mail"]["description_settings"] . "</p>";


			// check form path
			if (file_exists(FORM_CONTENT_BASE . FORM_MAIL_PATH)) {
				$o .= "<h4>" . FORM_MAIL_FORMS . "</h4>";
				$o .= form_mail_list_files(FORM_MAIL_PATH);
			}
			else {
				$o .= '<div class="xh_fail">' . $plugin_tx["form_mail"]["fail_formpath"] . '</div>';
			}

			break;


		//=========================
		// start editing
		case 'plugin_edit':

			// title
			$o .= "<h1>" . str_replace("%s", $file, str_replace("%t", ucfirst($type), $plugin_tx["form_mail"]["title_edit"])) . "</h1>";

			$o .= '<p><a href="?form_mail&admin=plugin_main&action=plugin_text&normal">' . $plugin_tx["form_mail"]["back"] . '</a></p>';

			$o .= form_mail_edit_data($type, $file);
			break;

	}


	return $o;
}


function form_mail_list_files($type) {

	global $c, $h;

	$ret = "";

	if ($handler = opendir(FORM_CONTENT_BASE . $type)) {

		// new file
		$ret .= '<form method="post" action="?form_mail" accept-charset="UTF-8">';

			$ret .= '<input type="text" name="file">';

			$ret .= '<input name="type" value="' . $type . '" type="hidden">';
			$ret .= '<input name="action" value="save" type="hidden">';
			$ret .= '<input name="admin" value="plugin_main" type="hidden">';

			$ret .= '<input class="submit" value="New anlegen" type="submit">';

		$ret .= '</form>';


		// list of files
		$ret .= "<ul>";


		while (($file = readdir($handler)) != false) {
			
			if (pathinfo($file, PATHINFO_EXTENSION) == "ini") {

				$ret .= '<li><a href="?form_mail&admin=plugin_main&action=plugin_edit:' . $type . "|" . $file . ' &normal"> ' . $file . '</a> ';

			 	$ret .= ' <a href="?form_mail&admin=plugin_main&action=plugin_text:' . $type . '_del|' . $file . '&normal"><img src="' . FORM_MAIL_BASE . '/images/del.gif"></a></li>';

			}
		}

		$ret .= "</ul>";
	}

	return $ret;
}


function form_mail_edit_data($type, $file) {

	$ret = "";
	
	$data = file_get_contents(FORM_CONTENT_BASE . FORM_MAIL_PATH . "/" . $file);

	if ($data === false) {
		$ret .= '<div class="xh_fail">' . str_replace("%s", form_mail_set_slash($type, true) . $file, FORM_MAIL_FAIL_READ) . '</div>';
	}

	else {

		$ret .= '<form method="post" action="?form_mail" accept-charset="UTF-8">';
			$ret .= '<input class="submit" value="Sichern" type="submit">';

			$ret .= '<textarea name="data" rows="24" class="xh_file_edit" autofocus>' . $data . '</textarea>';

			$ret .= '<input name="type" value="' . form_mail_set_slash($type, false) . '" type="hidden">';
			$ret .= '<input name="file" value="' . $file . '" type="hidden">';
			$ret .= '<input name="action" value="save" type="hidden">';
			$ret .= '<input name="admin" value="plugin_main" type="hidden">';

			$ret .= '<input class="submit" value="Sichern" type="submit">';
		$ret .= '</form>';
	}

	return $ret;

}


function form_mail_set_slash($path, $type) {

	// set slash
	if ($type) {
		if (substr($path, strlen($path)-1) != "/")
			$path .= "/";
	}

	// remove slash
	else {
		if (substr($path, strlen($path)-1) == "/")
			$path = substr($path, 0, strlen($path) - 1);
	}

	return $path;
}

?>