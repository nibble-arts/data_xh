<?php

namespace form;

class Field {

	private $name;
	private $type;
	private $mandatory = false;
	private $check = false;
	
	private $value;

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


	// get name
	public function name() {
		return $this->name;
	}

	// set/get values
	public function value($value = false) {
		if ($value !== false) {
			self::$value = $value;
		}
		
		return self::$value;
	}
		
	// get check
	public function check() {
		return $this->check;
	}

	// get type
	public function type() {
		return $this->type;
	}

	// get mandatory state
	public function mandatory() {
		return $this->mandatory;
	}

	// render field
	public function render($value = false) {

		$ret = '<' . $this->name . '>';
			$ret .= '<type>' . $this->type . '</type>';
			
			// add mandatory option
			if ($this->mandatory) {
				$ret .= '<mandatory>' .= $this->mandatory . '</mandatoty>';
			}
			
			// add check string
			if ($this->check) {
				$ret .= '<check>' .= $this->check . '</check>';
			}
			
			$ret .= '<value>' . $value . '</value>';
			
		$ret .= '</' . $name . '>';
	}
}

?>