<?php

namespace Tests\Greenhouse\Rule\VerifyRules;

use Crud\Action\ActionTestCaseTait;
use Crud\Action\NullRequest;
use Crud\Exception\UnexpectedType;
use Crud\Testing\Fixtures\Action\RequestDummy;
use Crud\Testing\Gateway\Filtering\FilteringGatewaySpy;
use Crud\Testing\Gateway\Finder\MultipleFinderGatewaySpy;
use Crud\Testing\Gateway\PersisterGatewaySpy;
use Crud\Testing\ModelDummy;
use DateTime;
use Greenhouse\Model\OktoController;
use Greenhouse\Model\Rule;
use Greenhouse\Model\Sensor;
use Greenhouse\Model\SensorData;
use Greenhouse\Rule\Action\VerifyRule\VerifyRulesAction;
use Greenhouse\Rule\Gateway\SensorDataCriteria;
use PHPUnit\Framework\TestCase;
use stdClass;

class VerifyRulesTest extends TestCase {

	use ActionTestCaseTait;

	/**
	 * @var FilteringGatewaySpy
	 */
	private $ruleGateway;

	/**
	 * @var MultipleFinderGatewaySpy
	 */
	private $sensorDataGateway;

	/**
	 * @var PersisterGatewaySpy
	 */
	private $rulePersisterGateway;

	protected function setUp() {
		parent::setUp();

		$this->setRulesWillBeCreatedBefore([
			$this->rule()
		]);

		$this->setSensorDataWillBeFinded([
			$this->sensorData(),
			$this->sensorData()
		]);

		$this->rulePersisterGateway = new PersisterGatewaySpy();
	}

	private function setRulesWillBeCreatedBefore(array $rules) {
		$this->ruleGateway = new FilteringGatewaySpy($rules);
	}

	public function setSensorDataWillBeFinded(array $sensorDatas) {
		$this->sensorDataGateway = new MultipleFinderGatewaySpy($sensorDatas);
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
	public function givenUnknownRule_whenRun_thenThrowsUnexpectedType() {
		$this->setRulesWillBeCreatedBefore([
			new ModelDummy()
		]);

		$this->assertRunThrows(new NullRequest(), UnexpectedType::class);

		$this->assertRuleFiltered();
	}

	/**
	 * @test
	 */
	public function givenUnknownSensorData_whenRun_thenThrowsUnexpectedType() {
		$this->setRulesWillBeCreatedBefore([
			$rule = $this->rule()
		]);
		$this->setSensorDataWillBeFinded([new ModelDummy()]);

		$this->assertRunThrows(new NullRequest(), UnexpectedType::class);

		$this->assertRuleFiltered();
		$this->assertSame(1, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
	}

	/**
	 * @test
	 */
	public function givenIntervalinFuture_whenRun_thenThrowsOutOfDateInterval() {
		$now = new DateTime("now");
		$rule = $this->rule();
		$rule->setOptoController($optoController = new OktoController());
		$rule->setBeginMonth($this->genFutureMonth(1));
		$rule->setEndMonth($this->genFutureMonth(2));
		$this->setRulesWillBeCreatedBefore([
			$rule
		]);

		$this->runAction(new NullRequest());

		$this->assertRuleFiltered();
		$this->assertSame(0, $optoController->getActive());
		$this->assertSame(1, $this->rulePersisterGateway->getPersistingTimes());
		$this->assertSame($rule, $this->rulePersisterGateway->getLastPersistedObject());
		$this->assertSame(2, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor2());
	}

	/**
	 * @test
	 */
	public function givenIntervalinPass_whenRun_thenOptoControllerActivateIsDown() {
		$now = new DateTime("now");
		$rule = $this->rule();
		$rule->setOptoController($optoController = new OktoController());
		$rule->setBeginMonth($this->genPassMonth(2));
		$rule->setEndMonth($this->genPassMonth(1));
		$this->setRulesWillBeCreatedBefore([
			$rule
		]);

		$this->runAction(new NullRequest());

		$this->assertRuleFiltered();
		$this->assertSame(0, $optoController->getActive());
		$this->assertSame(1, $this->rulePersisterGateway->getPersistingTimes());
		$this->assertSame($rule, $this->rulePersisterGateway->getLastPersistedObject());
		$this->assertSame(2, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor2());
	}

	/**
	 * @test
	 * @dataProvider invalidRelations
	 */
	public function givenInvalidRelation_whenRun_thenOptoControllerActivateIsDown($relation) {
		$rule = $this->rule();
		$rule->setRelation($relation);
		$rule->setOptoController($optoController = new OktoController());
		$this->setRulesWillBeCreatedBefore([
			$rule
		]);

		$this->runAction(new NullRequest());

		$this->assertRuleFiltered();
		$this->assertSame(0, $optoController->getActive());
		$this->assertSame(1, $this->rulePersisterGateway->getPersistingTimes());
		$this->assertSame($rule, $this->rulePersisterGateway->getLastPersistedObject());
		$this->assertSame(2, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor2());
	}

	public function invalidRelations() {
		return [
			["a"],
			[1],
			[""],
			[new stdClass],
			[2.2]
		];
	}

	/**
	 * @test
	 */
	public function givenNotValidCondition_whenRun_thenOptoControllerActiveIsDown() {
		$rule = $this->rule();
		$rule->setRelation("=");
		$rule->setOptoController($optoController = new OktoController());
		$this->setRulesWillBeCreatedBefore([
			$rule
		]);
		$sensorData1 = $this->sensorData(2);
		$sensorData2 = $this->sensorData(3);
		$this->setSensorDataWillBeFinded([
			$sensorData1,
			$sensorData2,
		]);

		$this->runAction(new NullRequest());

		$this->assertRuleFiltered();
		$this->assertSame(0, $optoController->getActive());
		$this->assertSame(1, $this->rulePersisterGateway->getPersistingTimes());
		$this->assertSame($rule, $this->rulePersisterGateway->getLastPersistedObject());
		$this->assertSame(2, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor2());
	}

	/**
	 * @test
	 */
	public function givenValidCondition_whenRun_thenOptoControllerActiveIsUp() {
		$rule = $this->rule();
		$rule->setRelation("=");
		$rule->setOptoController($optoController = new OktoController());
		$this->setRulesWillBeCreatedBefore([
			$rule
		]);
		$sensorData1 = $this->sensorData(2);
		$sensorData2 = $this->sensorData(2);
		$this->setSensorDataWillBeFinded([
			$sensorData1,
			$sensorData2,
		]);

		$this->runAction(new NullRequest());

		$this->assertRuleFiltered();
		$this->assertSame(1, $optoController->getActive());
		$this->assertSame(1, $this->rulePersisterGateway->getPersistingTimes());
		$this->assertSame($rule, $this->rulePersisterGateway->getLastPersistedObject());
		$this->assertSame(2, $this->sensorDataGateway->getFindedTimes());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor());
		$this->assertSensorDataFindedContainsSensor($rule->getSensor2());
	}

	public function runAction($request) {
		$action = new VerifyRulesAction("responder");
		$action->setRuleGateway($this->ruleGateway);
		$action->setSensorDataGateway($this->sensorDataGateway);
		$action->setRulePersisterGateway($this->rulePersisterGateway);

		$action->run($request);
	}

	private function rule() {
		$rule = new Rule();
		$rule->setBeginMonth($this->genFutureMonth(0));
		$rule->setBeginDay(1);
		$rule->setEndMonth($this->genFutureMonth(1));
		$rule->setEndDay(30);
		$rule->setRelation("=");
		$rule->setValueType("type");
		$rule->setSensor($this->sensor());
		$rule->setSensor2($this->sensor());
		$rule->setOptoController(new OktoController());

		return $rule;
	}

	private function sensor() {
		$sensor = new Sensor();
		$sensor->setType("type");

		return $sensor;
	}

	private function sensorData($value = 1) {
		$sensorData = new SensorData();
		$sensorData->setValue($value);

		return $sensorData;
	}

	private function genFutureMonth(int $monthsTime) {
		$nowMonth = (int) (new DateTime("now"))->format("m");
		if (($nowMonth + $monthsTime) > 12) {
			return $nowMonth - $monthsTime;
		}
		else {
			return $nowMonth + $monthsTime;
		}
	}

	private function genPassMonth(int $monthsTime) {
		$nowMonth = (int) (new DateTime("now"))->format("m");
		if (($nowMonth - $monthsTime) < 0) {
			return 12 - $monthsTime;
		}
		else {
			return $nowMonth - $monthsTime;
		}
	}

	private function assertRuleFiltered() {
		$this->assertSame(1, $this->ruleGateway->getFilteringTimes());
		$this->assertSame(null, $this->ruleGateway->getLastFilteredCriteria());
	}

	private function assertSensorDataFindedContainsSensor($sensor) {
		$ids = $this->sensorDataGateway->getFindedIds();
		foreach ($ids as $id) {
			$this->assertInstanceOf(SensorDataCriteria::class, $id);
			$this->assertSame(true, $id->getIsLastData());
			if ($sensor === $id->getSensor()) {
				$this->assertSame($sensor, $id->getSensor());
				return true;
			}
		}

		$this->fail("Sensor not Contains in SensorData Gateway criterias");
	}

}
