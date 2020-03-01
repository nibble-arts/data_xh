<?php


namespace data;


// dave $data string to file with name
// return download links for windows and UTF-8
class File {


	// save csv-file
	public static function save($form, $data) {

		$ret = "";

		$path = Path::create([DATA_DOWNLOADS_BASE, Config::data_path()]);

		// save csv file with form name
		// create download directory
		if (!file_exists($path)) {
			if (!mkdir($path, 0777, true)) {
				Message::failure("fail_download_mkdir");
			}
		}

		$path = Path::create([$path, $form]);

		if ($data) {
			
			// write data
			file_put_contents($path . '_result_utf8.csv', $data);

			file_put_contents($path . '_result.csv', mb_convert_encoding($data, "Windows-1252"));

			// add download link
			$ret .= '<ul>';
			$ret .= '<li><a href="' . $path . '_result.csv">Als Windows CSV-File herunterladen</a></li>';

			$ret .= '<li><a href="' . $path . '_result_utf8.csv">Als UTF-8 kodiertes CSV-File herunterladen</a></li>';
		}

$ret .= '</ul>';
		return $ret;
	}
}

?>