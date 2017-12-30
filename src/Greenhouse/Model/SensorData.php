<?php

namespace Greenhouse\Model;

class SensorData {

	private $id;

	private $timestamp;

	/**
	 * @var Sensor
	 */
	private $sensor;

	private $value;

	private $valueType;

	public function getId() {
		return $this->id;
	}

	public function getTimestamp() {
		return $this->timestamp;
	}

	public function setTimestamp($timestamp) {
		$this->timestamp = $timestamp;
	}

	public function getSensor() {
		return $this->sensor;
	}

	public function setSensor(Sensor $sensor) {
		$this->sensor = $sensor;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($sensorValue) {
		$this->value = $sensorValue;
	}

	public function getValueType() {
		return $this->valueType;
	}

	public function setValueType($sensorValueType) {
		$this->valueType = $sensorValueType;
	}

}
