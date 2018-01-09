<?php

namespace Crud\Testing\Gateway\Finder;

use Crud\Testing\Gateway\FinderGatewayDummy;

class FinderGatewayStub extends FinderGatewayDummy {

	private $model;

	public function __construct($model) {
		$this->model = $model;
	}

	public function find($id) {
		return $this->model;
	}

}
