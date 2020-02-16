<?php

namespace data;

class Field {

	private $name;
	private $type;
	private $mandatory = false;
	private $check = false;
	private $source = false;


	// construct field object
	public function __construct($name, $type, $source, $check) {

		$type_mand = explode(">", $type);

		$this->name = $name;

		$this->type = $type_mand[0];

		if (count($type_mand) > 1) {
			$this->mandatory = true;
		}

		$this->source = $source;
		$this->check = $check;

	}

	// render field
	public function render($value = false) {

		$ret = '<' . $this->name . '>';

			$ret .= '<type>' . $this->type . '</type>';
			
			// add mandatory option
			if ($this->mandatory) {
				$ret .= '<mandatory>' . $this->mandatory . '</mandatory>';
			}
			
			// add check string
			if ($this->check) {
				$ret .= '<check>' . $this->check . '</check>';
			}
			
			// add source string
			if ($this->source) {
				$ret .= '<source>' . $this->source . '</source>';
			}
			
			// add value if present
			if ($value != false) {
				$ret .= '<value>' . $value . '</value>';
			}
			
		$ret .= '</' . $this->name . '>';

		return $ret;
	}
}

?>