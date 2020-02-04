<?php

namespace form;

class XML {

	public static function array2xml ($root, $ary) {
debug("a2x");

		$ret = [];

		if (!is_array($ary)) {
			$ary = [$ary];
		}

		// iterate array
		foreach ($ary as $key => $element) {

			$ret = $element;

			// recursion
			if (is_array($ret)) {
				$ret = self::array2xml($key, $ret);
			}

			debug($element);
			debug("&lt;".$key."&gt;".$ret."&lt;/".$key."&gt;");
		}

		return $ret;
	}
}