<?php

namespace Greenhouse\Model;

class Rule {

	/**
	 * @var Sensor
	 */
	private $sensor;

	/**
	 * @var Sensor
	 */
	private $sensor2;

	/**
	 * @var string
	 */
	private $valueType;

	/**
	 * @var int
	 */
	private $beginMonth;

	/**
	 * @var int
	 */
	private $beginDay;

	/**
	 * @var int
	 */
	private $endMonth;

	/**
	 * @var int
	 */
	private $endDay;

	/**
	 * @var string
	 */
	private $relation;

	/**
	 * @var int
	 */
	private $dayBeginTime;

	/**
	 * @var int
	 */
	private $dayEndTime;

	/**
	 * @var OktoController
	 */
	private $optoController;

	public function getSensor() {
		return $this->sensor;
	}

	public function setSensor(Sensor $sensor) {
		$this->sensor = $sensor;
	}

	public function getSensor2() {
		return $this->sensor2;
	}

	public function setSensor2(Sensor $sensor2) {
		$this->sensor2 = $sensor2;
	}

	public function getValueType() {
		return $this->valueType;
	}

	public function setValueType($valueType) {
		$this->valueType = $valueType;
	}

	public function getBeginMonth() {
		return $this->beginMonth;
	}

	public function setBeginMonth($beginMonth) {
		$this->beginMonth = $beginMonth;
	}

	public function getBeginDay() {
		return $this->beginDay;
	}

	public function setBeginDay($beginDay) {
		$this->beginDay = $beginDay;
	}

	public function getEndMonth() {
		return $this->endMonth;
	}

	public function setEndMonth($endMonth) {
		$this->endMonth = $endMonth;
	}

	public function getEndDay() {
		return $this->endDay;
	}

	public function setEndDay($endDay) {
		$this->endDay = $endDay;
	}

	public function getRelation() {
		return $this->relation;
	}

	public function setRelation($relation) {
		$this->relation = $relation;
	}

	public function getDayBeginTime() {
		return $this->dayBeginTime;
	}

	public function setDayBeginTime($dayBeginTime) {
		$this->dayBeginTime = $dayBeginTime;
	}

	public function getDayEndTime() {
		return $this->dayEndTime;
	}

	public function setDayEndTime($dayEndTime) {
		$this->dayEndTime = $dayEndTime;
	}

	public function getOptoController() {
		return $this->optoController;
	}

	public function setOptoController(OktoController $optoController) {
		$this->optoController = $optoController;
	}

}
