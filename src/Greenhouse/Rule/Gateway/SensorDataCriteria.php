<?php

namespace Greenhouse\Rule\Gateway;

class SensorDataCriteria {

	private $sensor;

	private $isLastData;

	public function getSensor() {
		return $this->sensor;
	}

	public function getIsLastData() {
		return $this->isLastData;
	}

	public function setSensor($sensor) {
		$this->sensor = $sensor;
	}

	public function setIsLastData($isLastData) {
		$this->isLastData = $isLastData;
	}

}
