<?php

/* set failure or success messages
 * handle multiple messages
 * render messages
 *
 * @version: 1.1
 * @author: Thomas H Winkler
 * @copyright: 2018-2020
 */

namespace data;

class Message {

	private static $success;
	private static $failure;

	public static function reset() {

		self::$success = [];
		self::$failure = [];
	}


	// set message text
	// return array of messages text = false
	public static function success($text = false) {

		if ($text !== false) {
			self::$success[] = $text;
		}

		else {
			return self::$success;
		}
	}


	// set message text
	// return array of messages text = false
	public static function failure($text = false) {

		if ($text !== false) {
			self::$failure[] = $text;
		}

		else {
			return self::$failure;
		}
	}


	// render messages
	public static function render() {

		$o = "";

		if (self::$success && count(self::$success)) {

			foreach (self::$success as $message) {
				$o .= '<div class="xh_info">';
					$o .= Text::get($message);
				$o .= '</div>';
			}

			$o .= '<br>';
		}

		if (self::$failure && count(self::$failure)) {

			foreach (self::$failure as $message) {
				$o .= '<div class="xh_warning">';
					$o .= Text::get($message);
				$o .= '</div>';
			}

			$o .= '<br>';
		}

		self::reset();

		return $o;
	}
}