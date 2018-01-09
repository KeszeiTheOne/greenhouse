<?php

namespace Crud\Testing\Gateway\Finder;

class FinderGatewaySpy extends FinderGatewayStub {

	private $ids = [];

	public function find($id) {
		$this->ids[] = $id;

		return parent::find($id);
	}

	public function getFindedTimes() {
		return count($this->ids);
	}

	public function getLastFindedId() {
		return end($this->ids);
	}

	public function getFindedIds() {
		return $this->ids;
	}

}
