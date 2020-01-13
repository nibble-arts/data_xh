<?php

namespace form;


class View {


	// render the entries as list
	public static function list() {

		// display count of entries
		$ret = '<p>';

			if (Entries::count() !== false) {
				$ret .= Entries::count();
			}
			else {
				$ret .= Text::none();
			}

		$ret .= ' ' . Text::entries() . '</p>';


		if (Entries::get(0)) {

			$ret .= '<table class="form_list_table">';

				// create header
				$ret .= '<th>#</th>'; // count field
				$ret .= '<th class="form_list_head">' . Text::username() . '</th>'; // count field
				$ret .= '<th class="form_list_head">' . Text::time() . '</th>'; // count field


				// create headline from legend
				foreach (Entries::get(0)->legend() as $value) {

					$ret .= '<th class="form_list_head">';
						$ret .= ucfirst(str_replace("_", " ", $value));
					$ret .= '</th>';
				}


				// create lines
				foreach (Entries::get() as $idx => $line) {

					if ($line != "") {

						$ret .= "<tr>";

							// count row
							$ret .= '<td class="form_list_cell">';
								// $ret .= '<a href="#"';
									// $ret .= ' title="' . Text::edit() . '"';
								// $ret .= '>';
								$ret .= ($idx + 1) . '</a>';
							$ret .= '</td>';

							// user
							$ret .= '<td class="form_list_cell">' . $line->meta("user") . '</td>';

							// user
							$ret .= '<td class="form_list_cell">' . View::htime($line->meta("timestamp")) . '</td>';

							// iterate keys
							while ($value = $line->get()) {

								$ret .= '<td class="form_list_cell">';
									$ret .= $value[key($value)];
								$ret .= "</td>";
							}

						$ret .= "<tr>";

					}
				}

			$ret .= "</table>";
		}

		return $ret;
	}


	// get csv data
	public static function csv () {

		$csv = "";
		$csv_ary = [];

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

		return $csv;
	}


	// show timestamp as human readable time
	public static function htime($timestamp) {

		return date("j.n.Y G:i", $timestamp);
	}
}

?>