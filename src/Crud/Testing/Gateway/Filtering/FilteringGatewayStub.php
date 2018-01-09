<?php

namespace Crud\Testing\Gateway\Filtering;

use Crud\Action\FilteringGateway;

class FilteringGatewayStub implements FilteringGateway {

	private $models = [];

	public function __construct(array $models) {
		$this->models = $models;
	}

	public function filter($criteria) {
		return $this->models;
	}

}
