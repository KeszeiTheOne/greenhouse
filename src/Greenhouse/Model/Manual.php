<?php

namespace Greenhouse\Model;

class Manual {

	private $id;

	private $label;

	private $work;

	public function getId() {
		return $this->id;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function getWork() {
		return $this->work;
	}

	public function setWork($work) {
		$this->work = $work;
	}

}
