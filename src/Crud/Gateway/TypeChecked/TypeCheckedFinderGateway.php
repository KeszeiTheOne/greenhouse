<?php

namespace Crud\Gateway\TypeChecked;

use Crud\Exception\UnexpectedType;
use Crud\Gateway\FinderGateway;

class TypeCheckedFinderGateway implements FinderGateway {

	/**
	 * @var FinderGateway
	 */
	private $gateway;

	private $className;

	public function __construct(FinderGateway $gateway, $className) {
		$this->gateway = $gateway;
		$this->className = $className;
	}

	public function find($id) {
		$object = $this->gateway->find($id);

		if (null !== $object && !is_a($object, $this->className)) {
			throw new UnexpectedType;
		}

		return $object;
	}

}
