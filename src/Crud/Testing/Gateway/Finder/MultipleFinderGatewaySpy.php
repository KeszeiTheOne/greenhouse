<?php

namespace Crud\Testing\Gateway\Finder;

class MultipleFinderGatewaySpy extends FinderGatewaySpy {

	private $models = [];

	private $current = 0;

	public function __construct(array $models) {
		$this->models = $models;
	}

	public function find($id) {
		parent::find($id);

		$model = $this->models[$this->current];
		$this->current++;

		return $model;
	}

}
