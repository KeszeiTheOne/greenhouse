<?php

namespace Crud\Testing\Gateway\Filtering;

class FilteringGatewaySpy extends FilteringGatewayStub {

	private $criterias = [];

	public function filter($criteria) {
		$this->criterias[] = $criteria;
		
		return parent::filter($criteria);
	}
	
	public function getFilteringTimes() {
		return count($this->criterias);
	}
	
	public function getLastFilteredCriteria() {
		return end($this->criterias);
	}
	
	public function getFilteredCriterias() {
		return $this->criterias;
	}

}
