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

				foreach (Entries::get(0)->legend() as $value) {
					
					$ret .= '<th class="form_list_head">';
						$ret .= ucfirst(str_replace("_", " ", $value));
					$ret .= '</th>';

					$csv_ary[] = '"' . $value . '"';
				}

				$csv = implode(";", $csv_ary) . "\n";
				$csv_ary = [];


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

								$csv_ary[] = '"' . $value[key($value)] . '"';
							}

						$ret .= "<tr>";

						$csv .= implode(";", $csv_ary) . "\n";
						$csv_ary = [];
					}
				}

			$ret .= "</table>";
		}

		return $ret;
	}


	// show timestamp as human readable time
	public static function htime($timestamp) {

		return date("j.n.Y G:i", $timestamp);
	}
}

?>