<?php

namespace Crud\Testing\Gateway;

class PersisterGatewaySpy extends PersisterGatewayDummy {

	private $objects = [];

	public function persist($object) {
		$this->objects[] = $object;
	}
	
	public function getPersistingTimes() {
		return count($this->objects);
	}
	
	public function getLastPersistedObject() {
		return end($this->objects);
	}
	
	public function getPersistedObjects() {
		return $this->objects;
	}

}
