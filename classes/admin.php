<?php


namespace form;


class Admin {

	private static $path;

	private static $legend;
	private static $data;
	private static $meta;

	private static $entries;

	// fetch form data
	public static function fetch($path) {

		self::$path = $path;
		self::$legend = false;

// load entries
		Entries::load($path);

	}


	// render form data in list
	// optional: filter by field value
	// filter=field:value
	public static function render($attr) {

		$ret = "";
		$csv = "";
		$filter = false;
		$xsl = false;

		if (isset($attr["format"])) {
			$xsl = $attr["format"];
		}

		else {
			return false;
		}

// TODO filter and sort
		if (isset($attr["filter"])) {

			$keyval = explode ("=", $attr["filter"]);
			
			// filter entries by key=value
			preg_match("/([^\:]+):([^\=]+)[\=]?(.*)/", $attr["filter"], $matches);
			
			if (count ($matches) > 2) {
				Entries::filter($matches[1], $matches[2], $matches[3]);
			}
		}

		$xsl = $xsl . ".xsl";

		// render entries
		$ret = View::formatted($xsl);
		// $csv = View::csv();






		return $ret;



		// save csv file
		// create download directory
		if (!file_exists(FORM_DOWNLOADS_BASE . FORM_PATH)) {
			if (!mkdir(FORM_DOWNLOADS_BASE . FORM_PATH, 0777, true)) {
				Message::failure("fail_download_mkdir");
			}
		}


		if ($csv) {
			
			// write data
			file_put_contents(FORM_DOWNLOADS_BASE . FORM_PATH . '/' . $form . '_result_utf8.csv', $csv);

			file_put_contents(FORM_DOWNLOADS_BASE . FORM_PATH . '/' . $form . '_result.csv', mb_convert_encoding($csv, "Windows-1252"));

			// add download link
			$ret .= '<p><a href="' . FORM_DOWNLOADS_BASE . FORM_PATH . '/' . $form . '_result.csv">Als CSV-File herunterladen</a></p>';

			$ret .= '<p><a href="' . FORM_DOWNLOADS_BASE . FORM_PATH . '/' . $form . '_result_utf8.csv">Als UTF-8 kodiertes CSV-File herunterladen</a></p>';
		}


		return $ret;
	}


	// create array grouped by field name
	private static function group($group) {

		// field for grouping found
		if (($idx = array_search($group, self::$legend)) !== false) {

			foreach ($data as $entry) {

			}
		}

		return $data;
	}
}

?>