<?php

namespace data;


class View {


	// format data using xsl transformation
	// file name format for xsl > $form.name.xsl
	public static function formatted ($form, $xsl) {

		global $su;

		$result = "";


// debug(Entries::get());

		$xsl_path = Config::form_content() . $form . "." . $xsl;

		if (file_exists($xsl_path)) {

	        // Converts PHP Array to XML with the root element being 'root-element-here'
	        $xml = Array2XML::createXML('records', Entries::array());

			// load xsl

			$xslt = new \DomDocument();
			$xslt->load($xsl_path);
			
			// create and call xslt processor
			$t = new \XSLTProcessor();
			$t->importStylesheet($xslt);

			// set attributes
			if (Config::url_detail()) {
				$t->setParameter("", "url", Config::url_detail());
			}
			else {
				$t->setParameter("", "url", $su);
			}

			$t->setParameter("", "form", $form);


			// transform
			$result = $t->transformToXml($xml);

		    return $result;
		}

		else {
			Message::failure("fail_noformat");

			return $result;
		}

	}


	private static function array2xml(array $data, \SimpleXMLElement $xml)
	{
	    foreach ($data as $k => $v) {
	        is_array($v) ? self::array2xml($v, $xml->addChild($k)) : $xml->addChild($k, $v);
	    }
	    return $xml;
	}


//deprecated
	// render the entries as list
	// public static function list() {

	// 	// display count of entries
	// 	$ret = '<p>';

	// 		if (Entries::count() !== false) {
	// 			$ret .= Entries::count();
	// 		}
	// 		else {
	// 			$ret .= Text::none();
	// 		}

	// 	$ret .= ' ' . Text::entries() . '</p>';


	// 	if (Entries::get(0)) {

	// 		$ret .= '<table class="form_list_table">';

	// 			// create header
	// 			$ret .= '<th>#</th>'; // count field
	// 			$ret .= '<th class="form_list_head">' . Text::username() . '</th>'; // count field
	// 			$ret .= '<th class="form_list_head">' . Text::time() . '</th>'; // count field


	// 			// create headline from legend
	// 			foreach (Entries::get(0)->legend() as $value) {

	// 				$ret .= '<th class="form_list_head">';
	// 					$ret .= ucfirst(str_replace("_", " ", $value));
	// 				$ret .= '</th>';
	// 			}


	// 			// create lines
	// 			foreach (Entries::get() as $idx => $line) {

	// 				if ($line != "") {

	// 					$ret .= "<tr>";

	// 						// count row
	// 						$ret .= '<td class="form_list_cell">';
	// 							// $ret .= '<a href="#"';
	// 								// $ret .= ' title="' . Text::edit() . '"';
	// 							// $ret .= '>';
	// 							$ret .= ($idx + 1) . '</a>';
	// 						$ret .= '</td>';

	// 						// user
	// 						$ret .= '<td class="form_list_cell">' . $line->stat("user") . '</td>';

	// 						// user
	// 						$ret .= '<td class="form_list_cell">' . View::htime($line->stat("timestamp")) . '</td>';

	// 						// iterate keys
	// 						while ($value = $line->get()) {

	// 							$ret .= '<td class="form_list_cell">';
	// 								$ret .= $value[key($value)];
	// 							$ret .= "</td>";
	// 						}

	// 					$ret .= "<tr>";

	// 				}
	// 			}

	// 		$ret .= "</table>";
	// 	}

	// 	return $ret;
	// }


	// get csv data
	public static function csv () {

		$csv = "";
		$csv_ary = [];

		if (Entries::get()) {

			foreach (Entries::get(0)->legend() as $value) {

				$csv_ary[] = '"' . $value . '"';
			}

			$csv = implode(";", $csv_ary) . "\n";
			$csv_ary = [];


			// create lines
			foreach (Entries::get() as $idx => $line) {

				if ($line != "") {

					$line->reset();

					// iterate keys
					while ($value = $line->get()) {
						$csv_ary[] = '"' . $value[key($value)] . '"';
					}

					$csv .= implode(";", $csv_ary) . "\n";
					$csv_ary = [];
				}
			}
		}

		return $csv;
	}


	// show timestamp as human readable time
	public static function htime($timestamp) {

		return date("j.n.Y G:i", $timestamp);
	}
}

?>