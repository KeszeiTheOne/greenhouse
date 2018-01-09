<?php

namespace Crud\Gateway\TypeChecked;

use Crud\Action\FilteringGateway;
use Crud\Exception\UnexpectedType;

class TypeCheckedFilteringGateway implements FilteringGateway {

	/**
	 * @var FilteringGateway
	 */
	private $gateway;

	private $className;

	public function __construct(FilteringGateway $gateway, $className) {
		$this->gateway = $gateway;
		$this->className = $className;
	}

	public function filter($criteria) {
		$results = $this->gateway->filter($criteria);

		foreach ($results as $result) {
			if (null !== $result && !is_a($result, $this->className)) {
				throw new UnexpectedType;
			}
		}

		return $results;
	}

}
