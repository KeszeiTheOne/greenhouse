<?php

namespace Greenhouse\OktoController\AddOktoController;

use Crud\Action\AbstractAction;
use Crud\Action\Request;
use Crud\Exception\InvalidRequest;
use Crud\Exception\UnexpectedType;
use Crud\Gateway\PersisterGateway;
use Greenhouse\Model\OktoController;

class AddOktoControllerAction extends AbstractAction {

	/**
	 * @var PersisterGateway
	 */
	private $oktoControllerGateway;

	public function run(Request $request) {
		$request = $this->ensureRequest($request);
		$okto = new OktoController();
		$okto->setName($request->name);
		$this->oktoControllerGateway->persist($okto);
	}

	private function ensureRequest($request) {
		if (!$request instanceof AddOktoControllerRequest) {
			throw new UnexpectedType;
		}

		if (!$request->isValid()) {
			throw new InvalidRequest($request);
		}

		return $request;
	}

	public function setOktoControllerGateway(PersisterGateway $oktoControllerGateway) {
		$this->oktoControllerGateway = $oktoControllerGateway;
	}

}
