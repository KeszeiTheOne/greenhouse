<?php

namespace Greenhouse\Model;

class ManualData {

	private $id;

	private $timestamp;

	private $manualId;

	private $manualValue;

	public function getId() {
		return $this->id;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function getManualId() {
		return $this->manualId;
	}

	public function getManualValue() {
		return $this->manualValue;
	}

	public function setManualId($manualId) {
		$this->manualId = $manualId;
	}

	public function setManualValue($manualValue) {
		$this->manualValue = $manualValue;
	}

}
