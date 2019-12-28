<?php

namespace form;

class Tag {
	
	private $name;
	private $attributes;
	private $children;
	
	// create trash from Dom Element
	public function __construct ($element) {
		
		// set name
		$this->name ($element->nodeName);
		
		// set children
		$this->children = $element->childNodes;
		
		// extract attributes
		if ($element->hasAttributes()) {
			
			foreach ($element->getAttributes() as $attr) {
				$this->attribute ($attr->nodeName, $attr->nodeValue);
			}
		}
	}
	
	
	// get/set name
	public function name ($name = false) {
		
		if ($name) {
			$this->name = $name;
		}
		else {
			return $name;
		}
	}
	
	
	// get/set attribute
	public function attribute ($name, $value = false) {
		
		if {$value) {
			$this->attributes[$name] = $value;
		}
		else {
			if (isset($this->attributes[$name])) {
				return $this->attributes[$name];
			}
			else {
				return false;
			}
		}
	}
	
	
	// remove attribute
	public function removeAttr {$name) {
		
		if ($this->attribute ($name)) {
			unset ($this->attributes[$name]);
		}
	}
	
	
	// get element node
	public function get () {
		
		// create new node
		$node = new DomNode($this->name);
		
		// add attributes
		foreach ($this->attributes as $name => $val) {
			$node->addAttribute($name, $val);
		}
		
		// add children
		foreach ($this->children as $child) {
			$node->appendChild($child);
		}
		
		return $node;
	}
}

?>