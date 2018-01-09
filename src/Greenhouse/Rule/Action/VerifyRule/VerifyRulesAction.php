<?php

namespace Greenhouse\Rule\Action\VerifyRule;

use Crud\Action\AbstractAction;
use Crud\Action\FilteringGateway;
use Crud\Action\NullRequest;
use Crud\Action\Request;
use Crud\Exception\UnexpectedType;
use Crud\Gateway\FinderGateway;
use Crud\Gateway\PersisterGateway;
use Crud\Gateway\TypeChecked\TypeCheckedFilteringGateway;
use Crud\Gateway\TypeChecked\TypeCheckedFinderGateway;
use DateTime;
use Greenhouse\Model\Rule;
use Greenhouse\Model\Sensor;
use Greenhouse\Model\SensorData;
use Greenhouse\Rule\Gateway\SensorDataCriteria;

/**
 * @todo Refactoring
 */
class VerifyRulesAction extends AbstractAction {

	/**
	 * @var FilteringGateway
	 */
	private $ruleGateway;

	/**
	 * @var FinderGateway 
	 */
	private $sensorDataGateway;

	/**
	 * @var PersisterGateway
	 */
	private $rulePersisterGateway;

	public function run(Request $request) {
		if (!$request instanceof NullRequest) {
			throw new UnexpectedType;
		}

		$rules = $this->ruleGateway->filter(null);

		foreach ($rules as $rule) {
			$rule = $this->ensureRule($rule);
			$sensorData = $this->sensorDataGateway->find($this->createSensorDataCriteria($rule->getSensor()));
			$sensorData2 = $this->sensorDataGateway->find($this->createSensorDataCriteria($rule->getSensor2()));

			$now = new DateTime("now");

			$beginDate = new DateTime($now->format("Y") . "-" . $rule->getBeginMonth() . "-" . $rule->getBeginDay());
			$endDate = new DateTime(($now->format("Y") + ($rule->getEndMonth() < $rule->getBeginMonth() ? 1 : 0)) . "-" . $rule->getEndMonth() . "-" . $rule->getEndDay());
			$active = 0;
			if ($beginDate <= $now && $now <= $endDate) {
				$condition = false;
				switch ($rule->getRelation()) {
					case "<":
						$condition = $sensorData->getValue() < $sensorData2->getValue();
						break;
					case ">":
						$condition = $sensorData->getValue() > $sensorData2->getValue();
						break;
					case "=":
						$condition = $sensorData->getValue() == $sensorData2->getValue();
						break;
					case "<=":
						$condition = $sensorData->getValue() <= $sensorData2->getValue();
						break;
					case ">=":
						$condition = $sensorData->getValue() >= $sensorData2->getValue();
						break;
				}

				$active = $condition ? 1 : 0;
			}
			$optoController = $rule->getOptoController();
			$optoController->setActive($active);

			$this->rulePersisterGateway->persist($rule);
		}
	}

	private function createSensorDataCriteria(Sensor $sensor) {
		$criteria = new SensorDataCriteria();
		$criteria->setSensor($sensor);
		$criteria->setIsLastData(true);

		return $criteria;
	}

	private function ensureRule($rule) {
		if (!$rule instanceof Rule) {
			throw new UnexpectedType;
		}

		return $rule;
	}

	public function setRuleGateway(FilteringGateway $ruleGateway) {
		$this->ruleGateway = new TypeCheckedFilteringGateway($ruleGateway, Rule::class);
	}

	public function setSensorDataGateway(FinderGateway $sensorDataGateway) {
		$this->sensorDataGateway = new TypeCheckedFinderGateway($sensorDataGateway, SensorData::class);
	}

	public function setRulePersisterGateway(PersisterGateway $rulePersisterGateway) {
		$this->rulePersisterGateway = $rulePersisterGateway;
	}

}
