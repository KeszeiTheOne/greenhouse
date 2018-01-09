<?php

namespace Crud\Action;

use Exception;

trait ActionTestCaseTait {

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

	abstract public function runAction($request);
}
