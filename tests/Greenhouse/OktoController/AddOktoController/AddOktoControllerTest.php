<?php

namespace Tests\Greenhouse\OktoController\AddOktoController;

use Crud\Action\ActionTestCaseTait;
use Crud\Exception\InvalidRequest;
use Crud\Exception\UnexpectedType;
use Crud\Testing\Fixtures\Action\RequestDummy;
use Crud\Testing\Gateway\PersisterGatewaySpy;
use Greenhouse\Model\OktoController;
use Greenhouse\OktoController\AddOktoController\AddOktoControllerAction;
use Greenhouse\OktoController\AddOktoController\AddOktoControllerRequest;
use PHPUnit\Framework\TestCase;

class AddOktoControllerTest extends TestCase {

	use ActionTestCaseTait;

	/**
	 * @var PersisterGatewaySpy
	 */
	private $oktoControllerGateway;

	protected function setUp() {
		parent::setUp();

		$this->oktoControllerGateway = new PersisterGatewaySpy();
	}

	/**
	 * @test
	 */
	public function givenUnkownRequest_whenRun_thenThrowsUnexpectedType() {
		$this->assertRunThrows(new RequestDummy(), UnexpectedType::class);
	}

	/**
	 * @test
	 */
	public function givenNullNameInRequest_whenRun_thenThrowsInvalidRequest() {
		$request = $this->request();
		$request->name = null;

		$this->assertRunThrows($request, InvalidRequest::class);
	}

	/**
	 * @test
	 */
	public function addOktoController() {
		$this->runAction($this->request());

		$this->assertSame(1, $this->oktoControllerGateway->getPersistingTimes());
		$this->assertInstanceOf(OktoController::class, $okto = $this->oktoControllerGateway->getLastPersistedObject());
		$this->assertSame("name", $okto->getName());
	}

	private function runAction($request) {
		$action = new AddOktoControllerAction("responder");
		$action->setOktoControllerGateway($this->oktoControllerGateway);
		$action->run($request);
	}

	private function request() {

		$request = new AddOktoControllerRequest();
		$request->name = "name";

		return $request;
	}

}
