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
	public static function render($form, $filter = false) {

		$ret = "";
		$csv = "";
		$csv_ary = [];

// TODO filter and sort
		if ($filter) {
			
			$keyval = explode (":", $filter);
			
			// filter entries by key=value
			if (count ($keyval) > 1) {
				Entries::filter($keyval[0]), $keyval[1]);
			}
		}

		// render list of entries
		$ret = View::list();


		// save csv file
		// create download directory
		if (!file_exists(FORM_DOWNLOADS_BASE . FORM_MAIL_PATH)) {
			mkdir(FORM_DOWNLOADS_BASE . FORM_MAIL_PATH, 0777, true);
		}


// TODO create download files
// add links to formatted output
		// write data
		file_put_contents(FORM_DOWNLOADS_BASE . FORM_MAIL_PATH . '/' . $form . '_result_utf8.csv', $csv);

		file_put_contents(FORM_DOWNLOADS_BASE . FORM_MAIL_PATH . '/' . $form . '_result.csv', mb_convert_encoding($csv, "Windows-1252"));

		// add download link
		$ret .= '<p><a href="' . FORM_DOWNLOADS_BASE . FORM_MAIL_PATH . '/' . $form . '_result.csv">Als CSV-File herunterladen</a></p>';

		$ret .= '<p><a href="' . FORM_DOWNLOADS_BASE . FORM_MAIL_PATH . '/' . $form . '_result_utf8.csv">Als UTF-8 kodiertes CSV-File herunterladen</a></p>';

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