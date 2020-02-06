<?php

namespace form;

class Field {

	private $name;
	private $type;
	private $mandatory = false;
	private $check;

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

	// get name
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


		switch ($this->type) {

			case "input":
				$ret = $this->input($value);
				break;

			case "checkbox":
				$ret = $this->checkbox($checkbox);
				break;

			case "radio":
				$ret = $this->radio($value);
				break;

			case "select":
				$ret = $this->select($value);
				break;

			case "hidden":
				$ret = $this->hidden($value);
				break;

			case "textarea";
				$ret = $this->textarea($value);
				break;

			default:
				$ret = $this->type;
		}

		return $ret;
	}


	// input field
	private function input($value) {

		$ret = '<div class="form_label">' . ucfirst($this->name) . '</div>';

		$ret .= '<div class="form_value">';

			$ret .= ' <input type="text"';
				$ret .= $this->attributes($value);
			$ret .= '/>';

		$ret .= '</div>';

		return $ret;
	}

	// radio field
	private function radio($value) {

	}

	// checkbox field
	private function checkbox($value) {

	}

	// select field
	private function select($value) {

	}

	// hidden field
	private function hidden($value) {
		return '<input type="hidden" name="form_' . $this->name . '" value="' . $this->mandatory . '">';
	}

	// textarea field
	private function textarea($value) {

	}

	// add attributes
	private function attributes($value) {

		$ret = 'name="form_' . $this->name . '"';

		if ($value) {
			$ret .= ' value="' . $value . '"';
		}

		if ($this->source) {
			$ret .= ' source="' . $this->source . '"';
		}

		if ($this->check) {
			$ret .= ' check="' . $this->check . '"';
		}

		return $ret;
	}
}

?>