<?php

namespace Greenhouse\Model;

class Sensor {

	private $id;

	private $code;

	private $location;

	private $typeId;

	private $type;

	private $pin;

	private $work;

	public function getId() {
		return $this->id;
	}

	public function getCode() {
		return $this->code;
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setLocation($location) {
		$this->location = $location;
	}

	public function getTypeId() {
		return $this->typeId;
	}

	public function setTypeId($typeId) {
		$this->typeId = $typeId;
	}

	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getPin() {
		return $this->pin;
	}

	public function setPin($pin) {
		$this->pin = $pin;
	}

	public function getWork() {
		return $this->work;
	}

	public function setWork($work) {
		$this->work = $work;
	}

}
