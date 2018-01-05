<?php

namespace Tests\Greenhouse\Rule\AddRule;

use Crud\Action\AbstractAction;
use Crud\Exception\InvalidRequest;
use Crud\Exception\UnexpectedType;
use Crud\Testing\ModelDummy;
use Exception;
use PHPUnit\Framework\TestCase;

class AddRuleTest extends TestCase {

	/**
	 * @test
	 */
	public function givenUnknownRequest_whenRun_thenThrowsUnexpectedType() {
		$this->assertRunThrows(new ModelDummy(), UnexpectedType::class);
	}

	/**
	 * @test
	 * @dataProvider invalidParams
	 */
	public function givenInvalidRequest_whenRun_thenThrowsInvalidRequest($param, $value) {
		$request = $this->request();
		$request->$param = $value;

		$exc = $this->assertRunThrows($request, InvalidRequest::class);

		$this->assertSame($request, $exc->getRequest());
	}

	public function invalidParams() {
		return [
			["sensorId", null],
			["sensorId2", null],
			["value", null],
			["logic", null],
			["beginDate", null],
			["endDate", null],
			["oktoControllerId", null],
		];
	}

	private function runAction($request) {
		$action = new AddRuleAction("response");
		$action->run($request);
	}

	private function request() {
		$request = new AddRuleRequest();
		$request->sensorId = "sensorId";
		$request->sensorId2 = "sensorId2";
		$request->value = "value";
		$request->logic = "<";
		$request->beginDate = "begin";
		$request->endDate = "end";
		$request->oktoControllerId = "oktoControllerId";

		return $request;
	}

	protected function assertRunThrows($request, $expectedException) {
		try {
			$this->runAction($request);
		}
		catch (Exception $exc) {
			$this->assertInstanceOf($expectedException, $exc, $exc->getMessage());
			return $exc;
		}

		$this->fail("$expectedException should be thrown.");
	}

}

class AddRuleAction extends AbstractAction {

	public function run($request) {
		if (!$request instanceof AddRuleRequest) {
			throw new UnexpectedType;
		}

		if (!$request->isValid()) {
			throw new InvalidRequest($request);
		}
	}

}

class AddRuleRequest {

	public $sensorId;

	public $sensorId2;

	public $value;

	public $logic;

	public $beginDate;

	public $endDate;

	public $oktoControllerId;

	public function isValid() {
		return null !== $this->sensorId &&
			null !== $this->sensorId2 &&
			null !== $this->value &&
			null !== $this->logic &&
			null !== $this->beginDate &&
			null !== $this->endDate &&
			null !== $this->oktoControllerId;
	}

}
